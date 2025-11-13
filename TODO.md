# TODO: Implement Activity and API Logging Systems

## Steps to Complete:
- [x] Update `app/Services/LifoApiService.php` to insert logs into `api_logs` for each API operation (e.g., card requests, approvals).
- [x] Update password change logic in `app/Http/Controllers/Auth/LoginController.php` to insert logs into `activity_logs`.
- [x] Ensure `app/Http/Controllers/Dashbord/LogsController.php` has methods to retrieve and display logs in the views.
- [x] Add routes in `routes/web.php` for accessing logs if not present.
- [x] Run migrations to create the tables.
- [x] Test by changing a password and making an API call, then check logs.
- [x] Verify views display the logs correctly.
