# Auth setup and testing

How to test the new login and role-based dashboards locally:

1. Ensure your environment is configured (.env, DB connection).
2. Run migrations and seeders (if any):

   php artisan migrate
   php artisan db:seed

3. Create a user manually (tinker) if you don't have one:

   php artisan tinker
   >>> use App\\Models\\User; User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>'password','role'=>'admin']);

   Note: password will be hashed automatically because of the User model cast to 'hashed'.

4. Visit the login page: http://your-app.test/login
   - Login with the email and password above.
   - Admin users are redirected to /dashboard/admin
   - Regular users are redirected to /dashboard/user

5. To logout, POST to /logout (there is a named route 'logout').

Notes:
- The admin dashboard uses the existing `resources/views/dashboard.blade.php` and shows basic counts.
- The user dashboard is `resources/views/user/dashboard.blade.php`.
- The Auth flow is implemented in `app/Http/Controllers/AuthController.php`.

If you want, I can add a login link to the main layout and a logout form/button in the navbar.
