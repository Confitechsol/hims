<?php

namespace App\Http\Controllers;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    function index(Request $request){
        $incomes = Income::with(['incomeHead']);
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
        return view('admin.income.index', compact('incomes'));

    }
}
