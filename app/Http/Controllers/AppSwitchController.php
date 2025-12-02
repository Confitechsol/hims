<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AppSwitchController extends Controller
{
    /**
     * Redirect the authenticated user to the HRMS portal based on their role.
     */
    public function switchToClient(): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $roleName = optional(Role::find($user->role))->name;

        $email = $user->email;

        if (! $email && $user->username) {
            $email = strtolower($user->username).'@hims.local';
        }

        if (! $email) {
            return redirect()->back()->with('error', 'Unable to move to HR portal because the user does not have an email or username.');
        }

        $target = match (strtolower($roleName ?? '')) {
            'admin', 'superadmin', 'super admin', 'hr admin', 'hr' => 'admin',
            default => 'employee',
        };

        $token = Str::random(64);

        Cache::put(
            'hrms_switch_' . $token,
            [
                'email' => $email,
                'username' => $user->username,
                'name' => $user->username ?? $email,
                'user_id' => $user->id,
                'role' => strtolower($roleName ?? ''),
                'target' => $target,
            ],
            now()->addMinutes(5)
        );

        Log::info('HR portal token issued', [
            'token' => $token,
            'email' => $email,
            'target' => $target,
            'cache_store' => config('cache.default'),
        ]);

        $baseUrl = rtrim(config('services.hrms.base_url', url('/hrms')), '/');

        return redirect()->away($baseUrl . '/auth/switch?token=' . $token);
    }
}