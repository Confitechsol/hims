<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            // Redirect already logged-in users
            $user = Auth::user();
            $role = Role::find($user->role);

            return $role && $role->name === 'Admin'
                ? redirect('/dashboard')
                : redirect('/');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ✅ Attempt login
        $credentials = [
            'email'    => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $role = Role::find($user->role);

            return $role && $role->name === 'Admin'
                ? redirect()->intended('/dashboard')
                : redirect()->intended('/');
            // ✅ Load settings
            // $settings = Setting::first();
            // $lang = $user->language_id
            //     ? Language::find($user->language_id)
            // // : Language::find($settings->lang_id);
            //     : Language::find($user->language_id);

            // ✅ Build session data
            // $session_data = [
            //     'id'       => $user->id,
            //     'username' => $user->name,
            //     'email'    => $user->email,
            //     'roles'    => $user->roles,
            //     // 'date_format'     => $settings->date_format,
            //     // 'currency_symbol' => $settings->currency_symbol,
            //     // 'start_month'     => $settings->start_month,
            //     // 'timezone'        => $settings->timezone,
            //     // 'sch_name'        => $settings->name,
            //     'language' => [
            //         'lang_id'  => $lang->id,
            //         'language' => $lang->language,
            //     ],
            //     'is_rtl'   => $lang->is_rtl ?? false,
            //                                  // 'theme'           => $settings->theme,
            //                                  // 'base_url'        => $settings->base_url,
            //                                  // 'folder_path'     => $settings->folder_path,
            //                                  // 'time_format'     => $settings->time_format === '24-hour',
            //     'prefix'   => Prefix::all(), // example
            // ];

            // Session::put('hospitaladmin', $session_data);

            // ✅ Redirect
            // if (Session::has('redirect_to')) {
            //     return redirect(Session::get('redirect_to'));
            // }

            // return redirect()->route('admin.dashboard');
        }

        // ❌ Invalid login
        return redirect()->back()->with('error_message', 'Invalid username or password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
