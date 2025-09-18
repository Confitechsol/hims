<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query       = User::query();
        $isDoctorTab = $request->get('tab') === 'doctor';
        if ($isDoctorTab) {
            // $query->where('created_by', Auth::id())->where('isCancelled', false);
            $query->where('', $request->get(''));
        } else {
            $query->where('is_active', 'yes');
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('username', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $users = $query->get();
        return view("admin.setup.users", compact("users", 'isDoctorTab'));
    }
}