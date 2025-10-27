<?php

namespace App\Http\Controllers;

use App\Models\MedicineBatchDetail;
use App\Models\MedicineSupplier;
use App\Models\Pharmacy;
use App\Models\SupplierBillBasic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmacyPurchaseController extends Controller
{
    /**
     * Display purchase orders
     */
    public function index()
    {
        $purchases = SupplierBillBasic::with(['supplier', 'receivedBy'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('admin.pharmacy.purchase.index', compact('purchases'));
    }

    /**
     * Show form to create purchase order
     */
    public function create()
    {
        $suppliers = MedicineSupplier::all();
        $medicines = Pharmacy::active()->get();

        return view('admin.pharmacy.purchase.create', compact('suppliers', 'medicines'));
    }

    /**
     * Store a new purchase order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_no' => 'nullable|string|max:100',
            'date' => 'required|date',
            'supplier_id' => 'required|exists:medicine_supplier,id',
            'medicines' => 'required|array|min:1',
            'medicines.*.pharmacy_id' => 'required|exists:pharmacy,id',
            'medicines.*.batch_no' => 'required|string|max:100',
            'medicines.*.expiry' => 'required|date',
            'medicines.*.quantity' => 'required|numeric|min:1',
            'medicines.*.purchase_price' => 'required|numeric|min:0',
            'medicines.*.mrp' => 'required|numeric|min:0',
            'medicines.*.sale_rate' => 'required|numeric|min:0',
            'medicines.*.tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_mode' => 'nullable|string|max:30',
            'payment_date' => 'nullable|date',
            'cheque_no' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'note' => 'nullable|string',
            'payment_note' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $total = 0;
            $totalTax = 0;

            foreach ($validated['medicines'] as $medicine) {
                $medicineAmount = $medicine['quantity'] * $medicine['purchase_price'];
                $total += $medicineAmount;
                $totalTax += $medicine['tax'] ?? 0;
            }

            $discount = $validated['discount'] ?? 0;
            $netAmount = $total + $totalTax - $discount;

            // Handle file upload
            $attachmentPath = null;
            $attachmentName = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentPath = $file->store('purchase_attachments', 'public');
                $attachmentName = $file->getClientOriginalName();
            }

            // Create supplier bill
            $supplierBill = SupplierBillBasic::create([
                'invoice_no' => $validated['invoice_no'] ?? '',
                'date' => $validated['date'],
                'supplier_id' => $validated['supplier_id'],
                'total' => $total,
                'tax' => $totalTax,
                'discount' => $discount,
                'net_amount' => $netAmount,
                'note' => $validated['note'] ?? '',
                'payment_mode' => $validated['payment_mode'] ?? null,
                'payment_date' => $validated['payment_date'] ?? null,
                'cheque_no' => $validated['cheque_no'] ?? null,
                'cheque_date' => $validated['cheque_date'] ?? null,
                'payment_note' => $validated['payment_note'] ?? '',
                'attachment' => $attachmentPath,
                'attachment_name' => $attachmentName,
                'received_by' => Auth::id(),
            ]);

            // Create medicine batches
            foreach ($validated['medicines'] as $medicine) {
                $batchAmount = $medicine['quantity'] * $medicine['purchase_price'];

                MedicineBatchDetail::create([
                    'supplier_bill_basic_id' => $supplierBill->id,
                    'pharmacy_id' => $medicine['pharmacy_id'],
                    'inward_date' => $validated['date'],
                    'expiry' => $medicine['expiry'],
                    'batch_no' => $medicine['batch_no'],
                    'packing_qty' => '1', // Default
                    'purchase_rate_packing' => $medicine['purchase_price'],
                    'quantity' => $medicine['quantity'],
                    'mrp' => $medicine['mrp'],
                    'purchase_price' => $medicine['purchase_price'],
                    'tax' => $medicine['tax'] ?? 0,
                    'sale_rate' => $medicine['sale_rate'],
                    'batch_amount' => $batchAmount,
                    'amount' => $batchAmount,
                    'available_quantity' => $medicine['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('pharmacy.purchase.show', $supplierBill->id)
                ->with('success', 'Purchase order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified purchase order
     */
    public function show($id)
    {
        $purchase = SupplierBillBasic::with(['supplier', 'batches.pharmacy', 'receivedBy'])
            ->findOrFail($id);

        return view('admin.pharmacy.purchase.show', compact('purchase'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $purchase = SupplierBillBasic::with(['batches'])->findOrFail($id);
        $suppliers = MedicineSupplier::all();
        $medicines = Pharmacy::active()->get();

        return view('admin.pharmacy.purchase.edit', compact('purchase', 'suppliers', 'medicines'));
    }

    /**
     * Update purchase order
     */
    public function update(Request $request, $id)
    {
        $purchase = SupplierBillBasic::findOrFail($id);

        $validated = $request->validate([
            'invoice_no' => 'nullable|string|max:100',
            'date' => 'required|date',
            'supplier_id' => 'required|exists:medicine_supplier,id',
            'payment_mode' => 'nullable|string|max:30',
            'payment_date' => 'nullable|date',
            'cheque_no' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'note' => 'nullable|string',
            'payment_note' => 'nullable|string',
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $validated['attachment'] = $file->store('purchase_attachments', 'public');
                $validated['attachment_name'] = $file->getClientOriginalName();
            }

            $purchase->update($validated);

            return redirect()->route('pharmacy.purchase.show', $purchase->id)
                ->with('success', 'Purchase order updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Print purchase order
     */
    public function print($id)
    {
        $purchase = SupplierBillBasic::with(['supplier', 'batches.pharmacy', 'receivedBy'])
            ->findOrFail($id);

        return view('admin.pharmacy.purchase.print', compact('purchase'));
    }

    /**
     * Search purchase orders
     */
    public function search(Request $request)
    {
        $query = SupplierBillBasic::with(['supplier', 'receivedBy']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('invoice_no')) {
            $query->where('invoice_no', 'like', "%{$request->invoice_no}%");
        }

        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->paid();
            } else {
                $query->unpaid();
            }
        }

        $purchases = $query->orderBy('date', 'desc')->paginate(20);

        return view('admin.pharmacy.purchase.index', compact('purchases'));
    }
}

