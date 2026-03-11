<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\RolesPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
                : redirect('/dashboard');
        }

        return view('admin.login');
    }
   public function login(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        
        //dd( $validator);
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }
        
        // ✅ Attempt login
        $credentials = [
            'username'    => $request->username,
            'password' => $request->password,
        ];
        //dd( $credentials);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $role = Role::find($user->role);
            
            // Store role permissions in session
            if ($role) {
                $permissions = RolesPermission::where('role_id', $role->id)
                    ->where('hospital_id', $user->hospital_id ?? null)
                    ->get()
                    ->keyBy('perm_cat_id')
                    ->map(function ($permission) {
                        return [
                            'can_view' => (bool) $permission->can_view,
                            'can_add' => (bool) $permission->can_add,
                            'can_edit' => (bool) $permission->can_edit,
                            'can_delete' => (bool) $permission->can_delete,
                        ];
                    })
                    ->toArray();
                
                Session::put('user_permissions', $permissions);
                Session::put('user_role_id', $role->id);
                Session::put('user_role_name', $role->name);
            }
            
            return $role && $role->name === 'Admin'
                ? redirect()->intended('/dashboard')
                : redirect()->intended('/dashboard');
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
        // Clear permission session data
        Session::forget('user_permissions');
        Session::forget('user_role_id');
        Session::forget('user_role_name');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
