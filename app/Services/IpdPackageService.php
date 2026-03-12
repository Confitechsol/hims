<?php

namespace App\Services;

use App\Models\IpdDetail;
use App\Models\IpdPackage;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class IpdPackageService
{
    /**
     * Apply a package to an IPD patient
     *
     * @param int $ipdId IPD patient ID
     * @param int $packageId Package ID to apply
     * @param string|null $appliedDate Date when package is applied (Y-m-d format, default: today)
     * @param string|null $notes Notes for package application
     * @param float|null $packageRateOverride Optional custom package amount (overrides package master rate)
     * @return array Result with status, message, and package data
     */
    public function applyPackage($ipdId, $packageId, $appliedDate = null, $notes = null, $packageRateOverride = null)
    {
        try {
            DB::beginTransaction();

            // Validate IPD exists and is not discharged
            $ipd = IpdDetail::find($ipdId);
            if (!$ipd) {
                throw new \Exception("IPD record not found: {$ipdId}");
            }

            if ($ipd->discharged === 'yes') {
                throw new \Exception("Cannot apply package to a discharged patient");
            }

            // Validate package exists and is active
            $package = Package::find($packageId);
            if (!$package) {
                throw new \Exception("Package not found: {$packageId}");
            }

            if (!$package->is_active) {
                throw new \Exception("Package is not active");
            }

            // Set applied date
            if ($appliedDate === null) {
                $appliedDate = Carbon::today()->format('Y-m-d');
            }

            // Validate applied date is not before admission date
            $admissionDate = Carbon::parse($ipd->date)->format('Y-m-d');
            if (Carbon::parse($appliedDate)->isBefore(Carbon::parse($admissionDate))) {
                throw new \Exception("Package cannot be applied before admission date ({$admissionDate})");
            }

            // Check if same package is already applied on the same date
            $existing = IpdPackage::where('ipd_id', $ipdId)
                ->where('package_id', $packageId)
                ->where('applied_date', $appliedDate)
                ->where('status', 'applied')
                ->first();

            if ($existing) {
                throw new \Exception("This package is already applied on {$appliedDate}");
            }

            // Calculate package charges with discounts and GST (use override amount if provided).
            // Package Master (packages table) is never updated; custom rate is stored only in ipd_packages
            // so it affects only this patient's estimate and final bill.
            $calculatedCharge = $this->calculatePackageCharge($ipd, $package, $packageRateOverride);

            // Create IpdPackage record (per-patient; does not modify Package Master)
            $ipdPackage = IpdPackage::create([
                'ipd_id' => $ipdId,
                'package_id' => $packageId,
                'applied_date' => $appliedDate,
                'applied_by' => Auth::id() ?? null,
                'package_rate' => $calculatedCharge['package_rate'],
                'discount_percentage' => $calculatedCharge['discount_percentage'],
                'discount_amount' => $calculatedCharge['discount_amount'],
                'gst_amount' => $calculatedCharge['gst_amount'],
                'final_amount' => $calculatedCharge['final_amount'],
                'status' => 'applied',
                'note' => $notes,
            ]);

            // Create transaction record for audit trail in transactions table
            // NOTE: This is a non-payment transaction (type = 'package', section = 'ipd')
            Transaction::create([
                'hospital_id'   => $ipd->hospital_id ?? '1',
                'branch_id'     => $ipd->branch_id ?? '1',
                'type'          => 'package',          // custom type for package application
                'section'       => 'ipd',
                'patient_id'    => $ipd->patient_id ?? null,
                'ipd_id'        => $ipdId,
                'amount_type'   => 'credit',
                'amount'        => $calculatedCharge['final_amount'],
                'payment_mode'  => null,
                'payment_date'  => Carbon::parse($appliedDate)->setTimeFromTimeString(now()->toTimeString()),
                'note'          => "Package '{$package->name}' applied on {$appliedDate}",
                'received_by'   => Auth::id() ?? null,
                'created_at'    => now(),
            ]);

            DB::commit();

            Log::info("Package applied successfully", [
                'ipd_id' => $ipdId,
                'package_id' => $packageId,
                'package_name' => $package->name,
                'applied_date' => $appliedDate,
                'final_amount' => $calculatedCharge['final_amount'],
            ]);

            return [
                'success' => true,
                'message' => "Package '{$package->name}' applied successfully",
                'data' => $ipdPackage->load(['package', 'appliedBy']),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error applying package: " . $e->getMessage(), [
                'ipd_id' => $ipdId,
                'package_id' => $packageId,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Update package amount for an applied IPD package (on-the-fly change).
     * Recalculates discount, GST and final amount from the new package rate.
     * Only updates ipd_packages; Package Master (packages table) is never modified.
     *
     * @param int $ipdId IPD patient ID
     * @param int $ipdPackageId IpdPackage record ID
     * @param float $newPackageRate New package amount (INR)
     * @return array Result with success and message
     */
    public function updatePackageAmount($ipdId, $ipdPackageId, $newPackageRate)
    {
        try {
            $ipdPackage = IpdPackage::where('id', $ipdPackageId)
                ->where('ipd_id', $ipdId)
                ->where('status', 'applied')
                ->with('package')
                ->firstOrFail();

            $ipd = IpdDetail::find($ipdId);
            if (!$ipd) {
                throw new \Exception("IPD record not found");
            }
            if ($ipd->discharged === 'yes') {
                throw new \Exception("Cannot change package amount after discharge");
            }

            $packageRate = (float) $newPackageRate;
            $discountPercentage = (float) ($ipdPackage->discount_percentage ?? 0);
            $discountAmount = $discountPercentage > 0 ? ($packageRate * $discountPercentage) / 100 : 0;
            $amountAfterDiscount = $packageRate - $discountAmount;
            $gstPct = (float) ($ipdPackage->package->gst_amount ?? 0);
            $gstAmount = $gstPct > 0 ? ($amountAfterDiscount * $gstPct) / 100 : 0;
            $finalAmount = $amountAfterDiscount + $gstAmount;

            $ipdPackage->update([
                'package_rate' => round($packageRate, 2),
                'discount_amount' => round($discountAmount, 2),
                'gst_amount' => round($gstAmount, 2),
                'final_amount' => round($finalAmount, 2),
            ]);

            return [
                'success' => true,
                'message' => 'Package amount updated',
                'data' => $ipdPackage->fresh(),
            ];
        } catch (\Exception $e) {
            Log::error('Error updating package amount: ' . $e->getMessage(), [
                'ipd_id' => $ipdId,
                'ipd_package_id' => $ipdPackageId,
            ]);
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Remove a package from an IPD patient
     *
     * @param int $ipdId IPD patient ID
     * @param int $ipdPackageId IPD Package ID to remove
     * @return array Result with status and message
     */
    public function removePackage($ipdId, $ipdPackageId)
    {
        try {
            DB::beginTransaction();

            // Find the IPD package record
            $ipdPackage = IpdPackage::where('id', $ipdPackageId)
                ->where('ipd_id', $ipdId)
                ->firstOrFail();

            // Check if bill is already finalized
            $ipd = IpdDetail::find($ipdId);
            if ($ipd && $ipd->discharged === 'yes') {
                throw new \Exception("Cannot remove package after patient is discharged");
            }

            // Mark package as cancelled instead of deleting
            $ipdPackage->update([
                'status' => 'cancelled',
            ]);

            // Create transaction record for reversal
            Transaction::where('reference_type', 'package_application')
                ->where('reference_id', $ipdPackageId)
                ->update([
                    'amount' => -abs($ipdPackage->final_amount),
                    'description' => "Package cancelled - " . $ipdPackage->package->name,
                ]);

            DB::commit();

            Log::info("Package removed successfully", [
                'ipd_id' => $ipdId,
                'ipd_package_id' => $ipdPackageId,
                'package_name' => $ipdPackage->package->name,
            ]);

            return [
                'success' => true,
                'message' => "Package removed successfully",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error removing package: " . $e->getMessage(), [
                'ipd_id' => $ipdId,
                'ipd_package_id' => $ipdPackageId,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate package charges with applicable discounts and GST
     *
     * @param IpdDetail $ipd IPD patient record
     * @param Package $package Package to apply
     * @param float|null $rateOverride Optional custom package rate (overrides package master)
     * @return array Calculated charges array
     */
    private function calculatePackageCharge($ipd, $package, $rateOverride = null)
    {
        $packageRate = $rateOverride !== null && $rateOverride !== '' ? (float) $rateOverride : (float) ($package->package_rate ?? 0.00);
        
        // Calculate discount percentage
        // Priority: MOU Discount > Special Discount
        $discountPercentage = 0;
        $discountAmount = 0;

        if ($ipd->organisation_id) {
            // For TPA/Organisation admitted patients, apply organisation discount
            $organisationDiscount = $ipd->mou_discount ?? 0;
            if ($organisationDiscount > 0) {
                $discountPercentage = $organisationDiscount;
            }
        }

        // Apply special discount if available and higher than existing
        if ($ipd->special_discount && $ipd->special_discount > $discountPercentage) {
            $discountPercentage = $ipd->special_discount;
        }

        // Calculate discount amount
        if ($discountPercentage > 0) {
            $discountAmount = ($packageRate * $discountPercentage) / 100;
        }

        // Amount after discount
        $amountAfterDiscount = $packageRate - $discountAmount;

        // Calculate GST if applicable
        $gstAmount = 0;
        if ($package->gst_amount && $package->gst_amount > 0) {
            $gstAmount = ($amountAfterDiscount * $package->gst_amount) / 100;
        }

        // Final amount
        $finalAmount = $amountAfterDiscount + $gstAmount;

        return [
            'package_rate' => round($packageRate, 2),
            'discount_percentage' => round($discountPercentage, 2),
            'discount_amount' => round($discountAmount, 2),
            'gst_amount' => round($gstAmount, 2),
            'final_amount' => round($finalAmount, 2),
        ];
    }

    /**
     * Get all applied packages for an IPD patient
     *
     * @param int $ipdId IPD patient ID
     * @param string $status Filter by status (applied, completed, cancelled, all)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAppliedPackages($ipdId, $status = 'applied')
    {
        $query = IpdPackage::where('ipd_id', $ipdId)
            ->with(['package', 'appliedBy']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        return $query->orderBy('applied_date', 'desc')->get();
    }

    /**
     * Get package details for billing display
     *
     * @param int $ipdId IPD patient ID
     * @return array Array of packages with their charges
     */
    public function getPackagesForBilling($ipdId)
    {
        $packages = IpdPackage::where('ipd_id', $ipdId)
            ->where('status', 'applied')
            ->with('package')
            ->orderBy('applied_date', 'asc')
            ->get();

        $packageCharges = [];
        $totalPackageAmount = 0;

        foreach ($packages as $ipdPackage) {
            $packageCharges[] = [
                'id' => $ipdPackage->id,
                'package_name' => $ipdPackage->package->name,
                'applied_date' => $ipdPackage->applied_date,
                'package_rate' => $ipdPackage->package_rate,
                'discount_percentage' => $ipdPackage->discount_percentage,
                'discount_amount' => $ipdPackage->discount_amount,
                'gst_amount' => $ipdPackage->gst_amount,
                'final_amount' => $ipdPackage->final_amount,
            ];

            $totalPackageAmount += $ipdPackage->final_amount;
        }

        return [
            'packages' => $packageCharges,
            'total' => round($totalPackageAmount, 2),
            'count' => count($packageCharges),
        ];
    }

    /**
     * Check if a service is excluded in any applied package
     *
     * @param int $ipdId IPD patient ID
     * @param int $chargeId Charge ID to check
     * @return bool True if charge is excluded in any applied package
     */
    public function isChargeExcluded($ipdId, $chargeId)
    {
        $appliedPackages = IpdPackage::where('ipd_id', $ipdId)
            ->where('status', 'applied')
            ->pluck('package_id')
            ->toArray();

        if (empty($appliedPackages)) {
            return false;
        }

        return \App\Models\PackageExclude::whereIn('package_id', $appliedPackages)
            ->where('charge_id', $chargeId)
            ->exists();
    }
}
