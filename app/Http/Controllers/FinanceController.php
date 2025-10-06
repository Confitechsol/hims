<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeHead;
use App\Models\ExpenseHead;

class FinanceController extends Controller
{
    public function income()
    {
        $incomeHeads = IncomeHead::latest()->get();
        return view('admin.setup.income_head', compact('incomeHeads'));
    }

    public function incomeStore(Request $request)
    {
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
        $incomeHead = IncomeHead::findOrFail($id);
        $incomeHead->delete();

        return redirect()->back()->with('success', 'Income Head deleted successfully.');
    }


    public function expense()
    {
        $expenseHeads = ExpenseHead::latest()->get();
        return view('admin.setup.expense_head', compact('expenseHeads'));
    }

    public function expenseStore(Request $request)
    {
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
        $expenseHead = ExpenseHead::findOrFail($id);
        $expenseHead->delete();

        return redirect()->back()->with('success', 'Expense Head deleted successfully.');
    }

}
