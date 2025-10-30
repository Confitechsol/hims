<?php

namespace App\Http\Controllers;
use App\Models\Income;
use App\Models\IncomeHead;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    function index(Request $request){
        $incomes = Income::with(['incomeHead']);
        $incomeHeads = IncomeHead::all();
         $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
           $perPage = 5;
        }
        if($request->has('search')){
            $search_term = $request->search;
            $incomes->where(function ($query) use ($search_term) {
            $query->where('name', 'like', "%{$search_term}%")
                ->orWhere('invoice_no', 'like', "%{$search_term}%")
                ->orWhereHas('incomeHead', function ($q) use ($search_term) {
                    $q->where('income_category', 'like', "%{$search_term}%");
                });
        });
         $incomes = $incomes->paginate($perPage);
       return array("result"=>$incomes);
        }
        $incomes = $incomes->paginate($perPage);
        return view('admin.income.index', compact('incomes','incomeHeads'));

    }
    function create(Request $request){
        $request->validate([
            'income_head_id' => 'required|exists:income_head,id',
            'name' => 'required|string|max:255',
            'invoice_no' => 'required|string|max:255|unique:income,invoice_no',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);
        $document = null;
        if ($request->hasFile('document')) {
            $request->validate([
                'document' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
    
            // Get file and create unique name
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
    
            // Move file to public/uploads
            $file->move(public_path('uploads'), $fileName);
    
            $document = '/uploads/' . $fileName;
        }
        Income::create([
            'inc_head_id' => $request->income_head_id,
            'name' => $request->name,
            'invoice_no' => $request->invoice_no,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
            'documents' => $document,
        ]);

        return redirect()->back()->with('success', 'Income created successfully.');
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:income,id',
            'income_head_id' => 'required|exists:income_head,id',
            'name' => 'required|string|max:255',
            'invoice_no' => 'required|string|max:255|unique:income,invoice_no,' . $request->id,
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);
    
        $income = Income::findOrFail($request->id);
    
        // Handle document update (optional)
        if ($request->hasFile('document')) {
            $request->validate([
                'document' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
    
            // Remove old document if it exists and is local
            if (!empty($income->documents)) {
                $oldPath = str_replace(url('/'), public_path(), $income->documents);
                dd($oldPath);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
    
            // Upload new file
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
    
            // Save full dynamic URL
            $income->documents = url('uploads/' . $fileName);
        }
    
        // Update other fields
        $income->inc_head_id = $request->income_head_id;
        $income->name = $request->name;
        $income->invoice_no = $request->invoice_no;
        $income->amount = $request->amount;
        $income->date = $request->date;
        $income->note = $request->note;
    
        $income->save();
    
        return redirect()->back()->with('success', 'Income updated successfully.');
    }
    
    function destroy(Request $request){
        $request->validate([
            'id' => 'required|exists:income,id',
        ]);

        Income::findOrFail($request->id)->delete();

        return redirect()->back()->with('success', 'Income deleted successfully.');
    }
}
