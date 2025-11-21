<?php

namespace App\Http\Controllers;
use App\Models\Expense;
use App\Models\ExpenseHead;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
     function index(Request $request){

     //   $expenses = Expense::with('expenseHead')->get();
          $expenses = Expense::with(['expenseHead']);
         $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
           $perPage = 5;
        }

         if($request->has('search')){
            $search_term = $request->search;
            $expenses->where(function ($query) use ($search_term) {
            $query->where('name', 'like', "%{$search_term}%")
                ->orWhere('invoice_no', 'like', "%{$search_term}%")
                ->orWhereHas('expenseHead', function ($q) use ($search_term) {
                    $q->where('exp_category', 'like', "%{$search_term}%");
                });
        });
        $expenses = $expenses->paginate($perPage);
       return array("result"=>$expenses);
        }
        $expenses = $expenses->paginate($perPage);

     //   return $expenses;
        // load expense heads for select options
        $expenseHeads = \DB::table('expense_head')->select('id','exp_category')->get();

         return view('admin.expense.index',compact('expenses','expenseHeads'));

     }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'expense_name' => 'required',
            'name' => 'required|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
            'attach_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $document = null;
        if ($request->hasFile('attach_document')) {
            $file = $request->file('attach_document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $document = '/uploads/' . $fileName;
        }

        // Resolve expense head id: if numeric and exists, use it; otherwise find or create by name
        $expenseNameInput = $request->input('expense_name');
        $expHeadId = null;
        if (is_numeric($expenseNameInput) && ExpenseHead::find((int)$expenseNameInput)) {
            $expHeadId = (int)$expenseNameInput;
        } else {
            $existing = ExpenseHead::where('exp_category', $expenseNameInput)->first();
            if ($existing) {
                $expHeadId = $existing->id;
            } else {
                $newHead = ExpenseHead::create([
                    'exp_category' => $expenseNameInput,
                    'is_active' => 1,
                ]);
                $expHeadId = $newHead->id;
            }
        }

        Expense::create([
            'exp_head_id' => $expHeadId,
            'name' => $validated['name'],
            'invoice_no' => $validated['invoice_number'] ?? null,
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'note' => $validated['description'] ?? null,
            'documents' => $document,
            'is_active' => 1,
        ]);

        return redirect()->back()->with('success', 'Expense saved successfully!');
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'expense_name' => 'required',
            'name' => 'required|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
            'attach_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        // Handle document replacement
        if ($request->hasFile('attach_document')) {
            // delete old file if it's a local upload path
            if (!empty($expense->documents) && str_starts_with($expense->documents, '/uploads/')) {
                $oldPath = public_path(ltrim($expense->documents, '/'));
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('attach_document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $expense->documents = '/uploads/' . $fileName;
        }

        // Resolve expense head id similar to create
        $expenseNameInput = $request->input('expense_name');
        $expHeadId = null;
        if (is_numeric($expenseNameInput) && ExpenseHead::find((int)$expenseNameInput)) {
            $expHeadId = (int)$expenseNameInput;
        } else {
            $existing = ExpenseHead::where('exp_category', $expenseNameInput)->first();
            if ($existing) {
                $expHeadId = $existing->id;
            } else {
                $newHead = ExpenseHead::create([
                    'exp_category' => $expenseNameInput,
                    'is_active' => 1,
                ]);
                $expHeadId = $newHead->id;
            }
        }

        $expense->exp_head_id = $expHeadId;
        $expense->name = $validated['name'];
        $expense->invoice_no = $validated['invoice_number'] ?? null;
        $expense->date = $validated['date'];
        $expense->amount = $validated['amount'];
        $expense->note = $validated['description'] ?? null;

        $expense->save();

        return redirect()->back()->with('success', 'Expense updated successfully!');
    }

    public function delete($id)
    {
        $expense = Expense::findOrFail($id);

        // Remove uploaded document if stored locally
        if (!empty($expense->documents) && strpos($expense->documents, '/uploads/') === 0) {
            $oldPath = public_path(ltrim($expense->documents, '/'));
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $expense->delete();

        return redirect()->back()->with('success', 'Expense deleted successfully!');
    }

    
}
