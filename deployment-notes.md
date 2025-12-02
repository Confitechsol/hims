## App Switch SSO Deployment Checklist

### 1. Server prerequisites
- Host both apps on the same base domain (e.g. `https://example.com/hims` and `https://example.com/hrms`). Exact hostnames must match so cookies persist.
- Ensure the cache driver you plan to use (shared filesystem, Redis, DB, etc.) is available on the server and writable/readable by both apps.

### 2. Shared cache
1. Create a shared storage location accessible by both projects (local example: `D:\xampp-8.2\shared-cache`; production example: `/var/www/shared-cache`).
2. In both `.env` files:
   ```
   CACHE_DRIVER=shared
   SHARED_CACHE_PATH=/var/www/shared-cache
   SHARED_CACHE_LOCK_PATH=/var/www/shared-cache
   SHARED_CACHE_STORE=shared
   ```
3. Ensure `config/cache.php` contains:
   ```
   'shared' => [
       'driver' => 'file',
       'path' => env('SHARED_CACHE_PATH', base_path('../shared-cache')),
       'lock_path' => env('SHARED_CACHE_LOCK_PATH', base_path('../shared-cache')),
   ],
   'shared_store' => env('SHARED_CACHE_STORE', 'shared'),
   ```
4. Run `php artisan config:clear` and `php artisan cache:clear` in both apps.

### 3. Session configuration
- Assign unique cookie names:
  ```
  SESSION_COOKIE=hims_session   # in hims
  SESSION_COOKIE=hrms_session   # in hrms
  ```
- For non-HTTPS environments set `SESSION_SECURE_COOKIE=false`. In production HTTPS set it back to `true`.
- Leave `SESSION_DOMAIN` empty unless you intentionally share cookies across subdomains (then set `.example.com` in both).

### 4. HIMS switch controller
- `App\Http\Controllers\AppSwitchController@switchToClient`:
  - Determine `target` (`admin` or `employee`) from the user’s role.
  - Generate a token, fallback email, and cache payload (`email`, `username`, `name`, `user_id`, `role`, `target`) with 5-minute TTL.
  - Redirect to `config('services.hrms.base_url').'/auth/switch?token=...'`.
- `config/services.php` must include:
  ```
  'hrms' => [
      'base_url' => env('HRMS_URL', 'http://localhost/hrms'),
  ],
  ```
- In `.env` set `HRMS_URL` to the exact URL users open in the browser (host + path). Clear config cache afterward.
- Route registration in `hims/routes/web.php` (inside `auth` middleware):
  ```
  Route::get('/hr-portal/redirect', [AppSwitchController::class, 'switchToClient'])->name('hrms.switch');
  ```
- Blade button inside `@auth`:
  ```
  <a href="{{ route('hrms.switch') }}">Move to HR Portal</a>
  ```

### 5. HRMS switch controller
- `App\Http\Controllers\AppSwitchController@handleSwitch`:
  - Read token from shared cache via `Cache::get`.
  - Build fallback email if missing, derive `role_id` from `target` (adjust IDs to match your DB).
  - `User::firstOrCreate` with hashed random password; update `role_id` if user exists.
  - `Auth::login($user, true)` then redirect by role (`admin.home` vs `employee.home`).
  - `Cache::forget` the token after successful login.
- Route `/auth/switch` must be outside `auth` middleware.

### 6. Database/user prerequisites
- Ensure HRMS `users` table can handle synthetic accounts (nullable `employee_id` or defaults).
- Confirm `role_id` mapping: update controller’s `match` if your admin/employee IDs differ.

### 7. Middleware and routes
- `Route::middleware('auth')` should wrap only routes that need an authenticated session; keep `/auth/switch` public.
- `user-access` middleware uses `role_id` checks to restrict admin/employee dashboards.

### 8. Logging and diagnostics
- Both controllers log token issuance/consumption. Monitor `storage/logs/laravel.log` during rollout.
- If redirected back to `/login`, compare the host in the redirect URL with `HRMS_URL`. Session files under `storage/framework/sessions` should show `_previous.url`—if it’s `127.0.0.1` while dashboard uses `localhost`, you’ll lose the cookie.

### 9. Deployment sequence
1. Deploy code changes to HIMS and HRMS.
2. Create the shared cache directory; set permissions for the web/PHP user.
3. Update both `.env` files (cache settings, `HRMS_URL`, session cookie names, `SESSION_SECURE_COOKIE`).
4. Run `php artisan config:clear` and `php artisan cache:clear` in each project.
5. Restart any queue workers or services that cache config.
6. Test end-to-end:
   - Log into HIMS, click **Move to HR Portal**.
   - Confirm the redirect hits the exact `HRMS_URL`.
   - HRMS log should show `HR portal token consumed` with correct email/target, and the browser should land on the dashboard.

### 10. Production considerations
- For HTTPS deployments, set `APP_URL` and `HRMS_URL` to `https://...` and enable `SESSION_SECURE_COOKIE`.
- If apps reside on `hims.example.com` and `hrms.example.com`, set `SESSION_DOMAIN=.example.com` to allow cookies across subdomains.
- When multiple web nodes are involved, move the shared cache to Redis or database instead of filesystem.

Use this document when replicating the configuration on the production servers to ensure the custom SSO handoff works the same as it does locally.

