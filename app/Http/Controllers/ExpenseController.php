<?php

namespace App\Http\Controllers;
use App\Models\Expense;
use App\Models\ExpenseHead;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Permission fallback for environments where helper files are not autoloaded.
     */
    private function hasPermissionSafe(int $permCatId, string $type): bool
    {
        if (function_exists('isSuperAdmin') && isSuperAdmin()) {
            return true;
        }

        $roleName = strtolower(trim((string) session('user_role_name', '')));
        if (in_array($roleName, ['super admin', 'admin', 'administrator', 'adm'], true)) {
            return true;
        }

        $type = strtolower($type);

        if ($type === 'view' && function_exists('canView')) {
            return canView($permCatId);
        }
        if ($type === 'add' && function_exists('canAdd')) {
            return canAdd($permCatId);
        }
        if ($type === 'edit' && function_exists('canEdit')) {
            return canEdit($permCatId);
        }
        if ($type === 'delete' && function_exists('canDelete')) {
            return canDelete($permCatId);
        }

        // Fallback to session structure used by PermissionHelper::hasPermission()
        $permissions = session('user_permissions', []);
        $key = 'can_' . $type;

        return isset($permissions[$permCatId][$key]) && $permissions[$permCatId][$key] === true;
    }

     function index(Request $request){
        // Check if user can view expense (permission category ID: 12)
        if (!$this->hasPermissionSafe(12, 'view')) {
            abort(403, 'You do not have permission to view expense records.');
        }

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
        // Check if user can add expense (permission category ID: 12)
        if (!$this->hasPermissionSafe(12, 'add')) {
            abort(403, 'You do not have permission to create expense records.');
        }
        
        $validated = $request->validate([
            'expense_name' => 'required',
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'payment_mode' => 'required|string|in:Cash,Cheque,Card,UPI,Online,Transfer to Bank Account,Other',
            'bank_name' => 'nullable|string|max:255',
            'cheque_no' => 'nullable|string|max:100',
            'cheque_date' => 'nullable|date',
            'payment_reference' => 'nullable|string|max:255',
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
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'payment_mode' => $validated['payment_mode'],
            'bank_name' => $validated['bank_name'] ?? null,
            'cheque_no' => $validated['cheque_no'] ?? null,
            'cheque_date' => $validated['cheque_date'] ?? null,
            'payment_reference' => $validated['payment_reference'] ?? null,
            'note' => $validated['description'] ?? null,
            'documents' => $document,
            'is_active' => 1,
        ]);

        return redirect()->back()->with('success', 'Expense saved successfully!');
    }

    public function update(Request $request, $id)
    {
        // Check if user can edit expense (permission category ID: 12)
        if (!$this->hasPermissionSafe(12, 'edit')) {
            abort(403, 'You do not have permission to edit expense records.');
        }
        
        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'expense_name' => 'required',
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'payment_mode' => 'nullable|string|in:Cash,Cheque,Card,UPI,Online,Transfer to Bank Account,Other',
            'bank_name' => 'nullable|string|max:255',
            'cheque_no' => 'nullable|string|max:100',
            'cheque_date' => 'nullable|date',
            'payment_reference' => 'nullable|string|max:255',
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
        $expense->date = $validated['date'];
        $expense->amount = $validated['amount'];
        $expense->payment_mode = $validated['payment_mode'] ?? $expense->payment_mode;
        $expense->bank_name = $validated['bank_name'] ?? $expense->bank_name;
        $expense->cheque_no = $validated['cheque_no'] ?? $expense->cheque_no;
        $expense->cheque_date = $validated['cheque_date'] ?? $expense->cheque_date;
        $expense->payment_reference = $validated['payment_reference'] ?? $expense->payment_reference;
        $expense->note = $validated['description'] ?? null;

        $expense->save();

        return redirect()->back()->with('success', 'Expense updated successfully!');
    }

    public function delete($id)
    {
        // Check if user can delete expense (permission category ID: 12)
        if (!$this->hasPermissionSafe(12, 'delete')) {
            abort(403, 'You do not have permission to delete expense records.');
        }
        
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
