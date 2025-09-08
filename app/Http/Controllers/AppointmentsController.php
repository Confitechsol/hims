<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AppointmentsController extends Controller
{
    public function index()
    {
        $response = Http::get('https://hospitaldocker-gsgmhybjbvbpgxhb.canadacentral-01.azurewebsites.net/showbooking');
        // dd($response->json());
        $bookings = [];
        if ($response->ok()) {
            $bookings = $response->json();
        }
        return view('home.appointments', compact('bookings'));
    }
}