<?php

namespace App\Http\Controllers;

use App\Models\MedicineCategory;
use App\Models\MedicinePurchase;
use App\Models\MedicinePurchaseDetail;
use App\Models\MedicineSupplier;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MedicinePurchaseController extends Controller
{
    /**
     * Display a listing of medicine purchases
     */
    public function index()
    {
        $purchases = MedicinePurchase::with(['supplier', 'purchaseDetails'])
            ->orderBy('purchase_date', 'desc')
            ->paginate(20);

        return view('admin.pharmacy.purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new medicine purchase
     */
    public function create()
    {
        $categories = MedicineCategory::all();
        $suppliers = MedicineSupplier::all();
        $medicines = Pharmacy::where('is_active', 'yes')->get();

        return view('admin.pharmacy.purchase.create', compact('categories', 'suppliers', 'medicines'));
    }

    /**
     * Store a newly created medicine purchase
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:medicine_suppliers,id',
            'purchase_date' => 'required|date',
            'invoice_no' => 'nullable|string|max:255',
            'total' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'payment_mode' => 'nullable|string',
            'payment_amount' => 'nullable|numeric|min:0',
            'cheque_no' => 'nullable|string',
            'cheque_date' => 'nullable|date',
            'payment_note' => 'nullable|string',
            'note' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Medicine details
            'medicine_id.*' => 'required|exists:pharmacy,id',
            'batch_no.*' => 'required|string',
            'expiry_date.*' => 'required|date',
            'mrp.*' => 'required|numeric|min:0',
            'sale_price.*' => 'required|numeric|min:0',
            'purchase_price.*' => 'required|numeric|min:0',
            'quantity.*' => 'required|integer|min:1',
            'tax_percentage.*' => 'nullable|numeric|min:0',
            'amount.*' => 'required|numeric|min:0',
            'batch_amount.*' => 'nullable|numeric|min:0',
            'packing_qty.*' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload
            $attachmentPath = null;
            $attachmentName = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentName = $file->getClientOriginalName();
                $attachmentPath = $file->store('purchase_documents', 'public');
            }

            // Create medicine purchase
            $purchase = MedicinePurchase::create([
                'supplier_id' => $validated['supplier_id'],
                'purchase_date' => $validated['purchase_date'],
                'invoice_no' => $validated['invoice_no'] ?? null,
                'total' => $validated['total'],
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'net_amount' => $validated['net_amount'],
                'payment_mode' => $validated['payment_mode'] ?? null,
                'payment_date' => now(),
                'payment_amount' => $validated['payment_amount'] ?? 0,
                'cheque_no' => $validated['cheque_no'] ?? null,
                'cheque_date' => $validated['cheque_date'] ?? null,
                'payment_note' => $validated['payment_note'] ?? null,
                'note' => $validated['note'] ?? null,
                'attachment' => $attachmentPath,
                'attachment_name' => $attachmentName,
                'received_by' => Auth::id(),
            ]);

            // Create purchase details for each medicine
            $medicineIds = $request->input('medicine_id');
            foreach ($medicineIds as $index => $medicineId) {
                MedicinePurchaseDetail::create([
                    'medicine_purchase_id' => $purchase->id,
                    'medicine_id' => $medicineId,
                    'inward_date' => $validated['purchase_date'],
                    'batch_no' => $request->input('batch_no')[$index],
                    'expiry_date' => $request->input('expiry_date')[$index],
                    'mrp' => $request->input('mrp')[$index],
                    'sale_price' => $request->input('sale_price')[$index],
                    'purchase_price' => $request->input('purchase_price')[$index],
                    'quantity' => $request->input('quantity')[$index],
                    'available_quantity' => $request->input('quantity')[$index], // Initially all available
                    'tax_percentage' => $request->input('tax_percentage')[$index] ?? 0,
                    'amount' => $request->input('amount')[$index],
                    'batch_amount' => $request->input('batch_amount')[$index] ?? null,
                    'packing_qty' => $request->input('packing_qty')[$index] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('medicine-purchase.index')
                ->with('success', 'Medicine purchase created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create purchase: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified medicine purchase
     */
    public function show($id)
    {
        $purchase = MedicinePurchase::with(['supplier', 'purchaseDetails.medicine', 'receivedBy'])
            ->findOrFail($id);

        return view('admin.pharmacy.purchase.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified medicine purchase
     */
    public function edit($id)
    {
        $purchase = MedicinePurchase::with('purchaseDetails')->findOrFail($id);
        $categories = MedicineCategory::all();
        $suppliers = MedicineSupplier::all();
        $medicines = Pharmacy::where('is_active', 'yes')->get();

        return view('admin.pharmacy.purchase.edit', compact('purchase', 'categories', 'suppliers', 'medicines'));
    }

    /**
     * Update the specified medicine purchase
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:medicine_suppliers,id',
            'purchase_date' => 'required|date',
            'invoice_no' => 'nullable|string|max:255',
            'total' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $purchase = MedicinePurchase::findOrFail($id);

            // Handle file upload
            if ($request->hasFile('attachment')) {
                // Delete old file
                if ($purchase->attachment) {
                    Storage::disk('public')->delete($purchase->attachment);
                }
                
                $file = $request->file('attachment');
                $attachmentName = $file->getClientOriginalName();
                $attachmentPath = $file->store('purchase_documents', 'public');
                $validated['attachment'] = $attachmentPath;
                $validated['attachment_name'] = $attachmentName;
            }

            $purchase->update($validated);

            DB::commit();

            return redirect()->route('medicine-purchase.index')
                ->with('success', 'Medicine purchase updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update purchase: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified medicine purchase
     */
    public function destroy($id)
    {
        try {
            $purchase = MedicinePurchase::findOrFail($id);

            // Delete attachment if exists
            if ($purchase->attachment) {
                Storage::disk('public')->delete($purchase->attachment);
            }

            // Delete purchase (details will be cascade deleted)
            $purchase->delete();

            return redirect()->route('medicine-purchase.index')
                ->with('success', 'Medicine purchase deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete purchase: ' . $e->getMessage());
        }
    }

    /**
     * Get medicines by category (AJAX)
     */
    public function getMedicinesByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        
        $medicines = Pharmacy::where('medicine_category_id', $categoryId)
            ->where('is_active', 'yes')
            ->select('id', 'medicine_name')
            ->get();

        return response()->json($medicines);
    }

    /**
     * Get medicine details (AJAX)
     */
    public function getMedicineDetails($id)
    {
        $medicine = Pharmacy::findOrFail($id);

        return response()->json([
            'id' => $medicine->id,
            'medicine_name' => $medicine->medicine_name,
            'gst_percentage' => $medicine->gst_percentage ?? 0,
        ]);
    }

    /**
     * Download purchase attachment
     */
    public function download($id)
    {
        $purchase = MedicinePurchase::findOrFail($id);

        if (!$purchase->attachment) {
            abort(404, 'Attachment not found');
        }

        return Storage::disk('public')->download($purchase->attachment, $purchase->attachment_name);
    }
}
