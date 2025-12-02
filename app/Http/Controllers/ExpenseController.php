<?php

namespace App\Http\Controllers;
use App\Models\Expense;
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
         return view('admin.expense.index',compact('expenses'));

     }
    
}
