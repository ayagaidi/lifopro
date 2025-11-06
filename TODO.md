# TODO: Modify CAPTCHA Mechanism for Login After 5 Failed Attempts

## Information Gathered
- The application has three login systems: User (Auth/LoginController), Office (Office/Auth/OfficeloginController), and Company (Company/Auth/CompanyloginController).
- Each controller tracks failed login attempts using session key 'failed_attempts_' . $guardName.
- CAPTCHA is shown only after 5 or more failed attempts (>=5).
- On successful login, the counter resets to 0.
- Views:
  - resources/views/auth/login.blade.php: Uses @if($showCaptcha) to conditionally show CAPTCHA.
  - resources/views/office/auth/login.blade.php: Always shows CAPTCHA, needs modification.
  - resources/views/comapny/auth/login.blade.php: Uses @if(session('failed_attempts_companys', 0) >= 5), should use $showCaptcha for consistency.

## Plan
1. Update resources/views/office/auth/login.blade.php to conditionally show CAPTCHA using @if($showCaptcha).
2. Update resources/views/comapny/auth/login.blade.php to use @if($showCaptcha) instead of direct session check.
3. Verify controllers have correct logic (they do).

## Dependent Files to Edit
- resources/views/office/auth/login.blade.php
- resources/views/comapny/auth/login.blade.php

## Followup Steps
- Test login functionality for all three systems to ensure CAPTCHA appears only after 5 failed attempts and resets on success.
