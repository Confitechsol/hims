<?php

namespace App\Services;

use App\Models\IpdDetail;
use App\Models\IpdPackage;
use App\Models\Package;
use App\Models\Transaction;
use App\Services\PackageInsuranceRateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class IpdPackageService
{
    protected PackageInsuranceRateService $packageRateService;

    public function __construct(?PackageInsuranceRateService $packageRateService = null)
    {
        $this->packageRateService = $packageRateService ?? new PackageInsuranceRateService();
    }

    /**
     * Apply a package to an IPD patient
     *
     * @param int $ipdId IPD patient ID
     * @param int $packageId Package ID to apply
     * @param string|null $appliedDate Date when package is applied (Y-m-d format, default: today)
     * @param string|null $notes Notes for package application
     * @param float|null $packageRateOverride Optional custom package amount (overrides package master rate)
     * @param float|null $approvalPercentage Manual insurer approval % on contract rate (insurance packages only)
     * @return array Result with status, message, and package data
     */
    public function applyPackage($ipdId, $packageId, $appliedDate = null, $notes = null, $packageRateOverride = null, $approvalPercentage = null)
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
            $calculatedCharge = $this->calculatePackageCharge($ipd, $package, $packageRateOverride, $approvalPercentage);

            // Create IpdPackage record (per-patient; does not modify Package Master)
            $ipdPackage = IpdPackage::create([
                'ipd_id' => $ipdId,
                'package_id' => $packageId,
                'bed_group_id' => $ipd->bed_group_id,
                'applied_date' => $appliedDate,
                'applied_by' => Auth::id() ?? null,
                'package_rate' => $calculatedCharge['package_rate'],
                'approval_percentage' => $calculatedCharge['approval_percentage'],
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
     * Update an applied IPD package (amount, approval %, date, notes).
     *
     * @param int $ipdId IPD patient ID
     * @param int $ipdPackageId IpdPackage record ID
     * @param float|null $newPackageRate New contract package amount (INR); null keeps existing
     * @param mixed $approvalPercentage Approval % (0–100), null to clear, false to keep existing
     * @param mixed $appliedDate Applied date (Y-m-d), false to keep existing
     * @param mixed $note Note text, false to keep existing
     * @return array Result with success and message
     */
    public function updatePackageAmount(
        $ipdId,
        $ipdPackageId,
        ?float $newPackageRate = null,
        $approvalPercentage = false,
        $appliedDate = false,
        $note = false
    ) {
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
                throw new \Exception("Cannot change package after discharge");
            }

            $packageRate = $newPackageRate !== null
                ? (float) $newPackageRate
                : (float) $ipdPackage->package_rate;

            $approval = $approvalPercentage !== false
                ? $approvalPercentage
                : $ipdPackage->approval_percentage;

            if ($appliedDate !== false) {
                $admissionDate = Carbon::parse($ipd->date)->format('Y-m-d');
                if (Carbon::parse($appliedDate)->isBefore(Carbon::parse($admissionDate))) {
                    throw new \Exception("Package cannot be applied before admission date ({$admissionDate})");
                }
            }

            $calculated = $this->calculatePackageCharge(
                $ipd,
                $ipdPackage->package,
                $packageRate,
                $approval
            );

            $updates = [
                'package_rate' => $calculated['package_rate'],
                'approval_percentage' => $calculated['approval_percentage'],
                'discount_percentage' => $calculated['discount_percentage'],
                'discount_amount' => $calculated['discount_amount'],
                'gst_amount' => $calculated['gst_amount'],
                'final_amount' => $calculated['final_amount'],
            ];

            if ($appliedDate !== false) {
                $updates['applied_date'] = $appliedDate;
            }
            if ($note !== false) {
                $updates['note'] = $note;
            }

            $ipdPackage->update($updates);

            return [
                'success' => true,
                'message' => 'Package updated',
                'data' => $ipdPackage->fresh(['package', 'appliedBy']),
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

            // Update the matching package application transaction using existing Transaction fields
            $packageNote = "Package '{$ipdPackage->package->name}' applied on {$ipdPackage->applied_date}";
            Transaction::where('ipd_id', $ipdId)
                ->where('type', 'package')
                ->where('section', 'ipd')
                ->where('note', $packageNote)
                ->update([
                    'amount' => -abs($ipdPackage->final_amount),
                    'note' => "Package cancelled - " . $ipdPackage->package->name,
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
     * @param float|null $approvalPercentage Manual approval % on contract rate (insurance packages only)
     * @return array Calculated charges array
     */
    private function calculatePackageCharge($ipd, $package, $rateOverride = null, $approvalPercentage = null)
    {
        if ($rateOverride !== null && $rateOverride !== '') {
            $packageRate = (float) $rateOverride;
        } else {
            $packageRate = $this->packageRateService->resolveRate(
                $package,
                $ipd->bed_group_id ? (int) $ipd->bed_group_id : null
            );
        }

        $storedApproval = null;
        $chargeBase = $packageRate;

        if ($this->allowsApprovalPercentage($ipd, $package)
            && $approvalPercentage !== null
            && $approvalPercentage !== '') {
            $pct = (float) $approvalPercentage;
            if ($pct >= 0 && $pct <= 100) {
                $storedApproval = round($pct, 2);
                $chargeBase = $packageRate * ($pct / 100);
            }
        }
        
        // Calculate discount percentage
        // Priority: MOU Discount > Special Discount (applied after approval % when set)
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

        // Calculate discount amount on post-approval base
        if ($discountPercentage > 0) {
            $discountAmount = ($chargeBase * $discountPercentage) / 100;
        }

        // Amount after discount
        $amountAfterDiscount = $chargeBase - $discountAmount;

        // Calculate GST if applicable (GST treatment on approval payable — pending client confirmation)
        $gstAmount = 0;
        if ($package->gst_amount && $package->gst_amount > 0) {
            $gstAmount = ($amountAfterDiscount * $package->gst_amount) / 100;
        }

        // Final amount
        $finalAmount = $amountAfterDiscount + $gstAmount;

        return [
            'package_rate' => round($packageRate, 2),
            'approval_percentage' => $storedApproval,
            'discount_percentage' => round($discountPercentage, 2),
            'discount_amount' => round($discountAmount, 2),
            'gst_amount' => round($gstAmount, 2),
            'final_amount' => round($finalAmount, 2),
        ];
    }

    /**
     * Approval % applies to insurance packages, or any package on insurance/TPA/cashless IPD.
     */
    private function allowsApprovalPercentage(IpdDetail $ipd, Package $package): bool
    {
        if ($package->isInsurance()) {
            return true;
        }

        return (bool) ($ipd->insurance_company_id || $ipd->is_cashless || $ipd->organisation_id);
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
                'approval_percentage' => $ipdPackage->approval_percentage,
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
