<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class EmailController extends Controller
{
    public function index(){
        $user = Auth::user();

        if (!$user || !$user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
    
        // Fetch the email config for this hospital (you can filter by 'smtp' or 'php_mailer' if needed)
        $emailConfig = EmailConfig::where('hospital_id', $user->hospital_id)->first();
        return view('admin.setup.email-setting', compact('emailConfig'));
    }
    public function saveSetting(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        if (!$user || !$user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        if($request->email_type == "php_mailer"){
            EmailConfig::updateOrCreate(
                [
                    'hospital_id' => $user->hospital_id,
                    'email_type'  => $request->email_type,
                ],
                [
                    'smtp_auth'     => 'true',
                    'is_active'     => 'yes',
                ]
            );
        }
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email_type'    => 'required|string|max:50',
            'smtp_server'   => 'required|string',
            'smtp_port'     => 'required|integer',
            'smtp_username' => 'required|string',
            'smtp_password' => 'required|string',
            'ssl_tls'       => 'nullable|in:ssl,tls'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create or update the email config
        EmailConfig::updateOrCreate(
            [
                'hospital_id' => $user->hospital_id,
                'email_type'  => $request->email_type,
            ],
            [
                'smtp_server'   => $request->smtp_server,
                'smtp_port'     => $request->smtp_port,
                'smtp_username' => $request->smtp_username,
                'smtp_password' => $request->smtp_password, // Optionally: Crypt::encryptString(...)
                'ssl_tls'       => $request->ssl_tls,
                'smtp_auth'     => $request->smtp_auth || 0,
                'is_active'     => 'true',
            ]
        );

        return redirect()->back()->with('success', 'Email settings saved successfully.');
    }
}
