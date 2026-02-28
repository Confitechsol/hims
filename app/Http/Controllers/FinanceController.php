<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeHead;
use App\Models\ExpenseHead;

class FinanceController extends Controller
{
    public function income()
    {
        // Check if user can view income head (permission category ID: 10)
        if (!canView(10)) {
            abort(403, 'You do not have permission to view income head records.');
        }
        
        $incomeHeads = IncomeHead::latest()->get();
        return view('admin.setup.income_head', compact('incomeHeads'));
    }

    public function incomeStore(Request $request)
    {
        // Check if user can add income head (permission category ID: 10)
        if (!canAdd(10)) {
            abort(403, 'You do not have permission to create income head records.');
        }
        
        $request->validate([
            'income_head.*' => 'required|string|max:255',
            'description.*' => 'nullable|string|max:500',
        ]);

        $incomeHeads = $request->income_head;
        $descriptions = $request->description;

        foreach ($incomeHeads as $index => $head) {
            IncomeHead::create([
                'income_category' => $head,
                'description' => $descriptions[$index] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Income Head added successfully.');
    }

    public function incomeUpdate(Request $request, $id)
    {
        // Check if user can edit income head (permission category ID: 10)
        if (!canEdit(10)) {
            abort(403, 'You do not have permission to edit income head records.');
        }
        
        $request->validate([
            'income_head' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $incomeHead = IncomeHead::findOrFail($id);
        $incomeHead->update([
            'income_category' => $request->income_head,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Income Head updated successfully.');
    }

    public function incomeDestroy($id)
    {
        // Check if user can delete income head (permission category ID: 10)
        if (!canDelete(10)) {
            abort(403, 'You do not have permission to delete income head records.');
        }
        
        $incomeHead = IncomeHead::findOrFail($id);
        $incomeHead->delete();

        return redirect()->back()->with('success', 'Income Head deleted successfully.');
    }


    public function expense()
    {
        // Check if user can view expense head (permission category ID: 13)
        if (!canView(13)) {
            abort(403, 'You do not have permission to view expense head records.');
        }
        
        $expenseHeads = ExpenseHead::latest()->get();
        return view('admin.setup.expense_head', compact('expenseHeads'));
    }

    public function expenseStore(Request $request)
    {
        // Check if user can add expense head (permission category ID: 13)
        if (!canAdd(13)) {
            abort(403, 'You do not have permission to create expense head records.');
        }
        
        $request->validate([
            'expense_head.*' => 'required|string|max:255',
            'description.*' => 'nullable|string|max:500',
        ]);

        $expenseHeads = $request->expense_head;
        $descriptions = $request->description;

        foreach ($expenseHeads as $index => $head) {
            ExpenseHead::create([
                'exp_category' => $head,
                'description' => $descriptions[$index] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Expense Head added successfully.');
    }

    public function expenseUpdate(Request $request, $id)
    {
        // Check if user can edit expense head (permission category ID: 13)
        if (!canEdit(13)) {
            abort(403, 'You do not have permission to edit expense head records.');
        }
        
        $request->validate([
            'expense_head' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $expenseHead = ExpenseHead::findOrFail($id);
        $expenseHead->update([
            'exp_category' => $request->expense_head,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Expense Head updated successfully.');
    }

    public function expenseDestroy($id)
    {
        // Check if user can delete expense head (permission category ID: 13)
        if (!canDelete(13)) {
            abort(403, 'You do not have permission to delete expense head records.');
        }
        
        $expenseHead = ExpenseHead::findOrFail($id);
        $expenseHead->delete();

        return redirect()->back()->with('success', 'Expense Head deleted successfully.');
    }

}
