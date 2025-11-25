<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/session-test', function () {
    if (!session()->has('visited')) {
        session(['visited' => true]);
        return 'Welcome, this is your first visit!';
    } else {
        return 'Welcome back!';
    }
});
Route::get('/session-status', function () {
    $lastActivityTime = session()->get('last_activity_time', now());  // استخدم الوقت الحالي إذا لم يكن هناك نشاط
    $lifetime = env('SESSION_LIFETIME', 5);  // قيمة الـ SESSION_LIFETIME من ملف .env

    // احسب الفرق بالثواني
    $timeElapsed = now()->diffInSeconds($lastActivityTime);
    $timeLeft = $lifetime * 60 - $timeElapsed;  // المدة المتبقية بالثواني

    return "Time left in session: " . max($timeLeft, 0) . " seconds";  // لا تقل عن 0
});






Auth::routes(['register' => false]);

// 2FA Routes
Route::get('2fa/verify', [App\Http\Controllers\TwoFactorAuthController::class, 'showVerificationForm'])->name('2fa.verify');
Route::post('2fa/verify', [App\Http\Controllers\TwoFactorAuthController::class, 'verify'])->name('2fa.verify.post');
Route::post('2fa/resend', [App\Http\Controllers\TwoFactorAuthController::class, 'resendOTP'])->name('2fa.resend');

Route::get('refreshcaptcha', [App\Http\Controllers\Auth\LoginController::class, 'refreshcaptcha'])->name('refreshcaptcha');
Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('/');
Route::middleware(['check.active'])->group(function () {
Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::namespace('Dashbord')->group(function () {
    Route::get('users', [App\Http\Controllers\Dashbord\UserController::class, 'index'])->name('users');
    Route::get('users/create', [App\Http\Controllers\Dashbord\UserController::class, 'create'])->name('users/create');
    Route::post('users/create', [App\Http\Controllers\Dashbord\UserController::class, 'store'])->name('users/store');;
    Route::get('users/users', [App\Http\Controllers\Dashbord\UserController::class, 'users'])->name('users/users');
    Route::get('users/changeStatus/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'changeStatus'])->name('users/changeStatus');
    Route::get('users/edit/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'edit'])->name('users/edit');
    Route::post('users/edit/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'update'])->name('users/update');
    Route::get('users/profile/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'show'])->name('users/profile');
    Route::get('users/changepassword/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'showChangePasswordForm'])->name('users/ChangePasswordForm');
    Route::POST('users/changepassword/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'changePassword'])->name('users/changepassword');
    Route::get('users/myactivity', [App\Http\Controllers\Dashbord\UserController::class, 'myactivity'])->name('users/myactivity');
    Route::get('users/changepassord/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'showChangePasswordForms'])->name('users/changepassord');
    Route::post('users/changepassord/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'changePasswords'])->name('users/changepasssrd/update');

    //----------------------------city-----------------------------------------------------------------       
    Route::get('cities', [App\Http\Controllers\Dashbord\CityController::class, 'index'])->name('cities');
    Route::get('cities/create', [App\Http\Controllers\Dashbord\CityController::class, 'create'])->name('cities/create');
    Route::post('cities/create', [App\Http\Controllers\Dashbord\CityController::class, 'store'])->name('cities/store');;
    Route::get('cities/cities', [App\Http\Controllers\Dashbord\CityController::class, 'cities'])->name('cities/cities');;
    Route::get('cities/edit/{id}', [App\Http\Controllers\Dashbord\CityController::class, 'edit'])->name('cities/edit');
    Route::post('cities/edit/{id}', [App\Http\Controllers\Dashbord\CityController::class, 'update'])->name('cities/update');
    Route::delete('cities/delete/{id}', [App\Http\Controllers\Dashbord\CityController::class, 'delete'])->name('cities/delete');
    // 
    Route::get('car', [App\Http\Controllers\Dashbord\CarController::class, 'index'])->name('car');
    Route::get('car/create', [App\Http\Controllers\Dashbord\CarController::class, 'create'])->name('car/create');
    Route::post('car/create', [App\Http\Controllers\Dashbord\CarController::class, 'store'])->name('car/store');;
    Route::get('car/car', [App\Http\Controllers\Dashbord\CarController::class, 'car'])->name('car/car');
    Route::get('car/changeStatus/{id}', [App\Http\Controllers\Dashbord\CarController::class, 'changeStatus'])->name('car/changeStatus');
    Route::get('car/edit/{id}', [App\Http\Controllers\Dashbord\CarController::class, 'edit'])->name('car/edit');
    Route::post('car/edit/{id}', [App\Http\Controllers\Dashbord\CarController::class, 'update'])->name('car/update');


    // 

    Route::get('roles/index', [App\Http\Controllers\Dashbord\RoleController::class, 'index'])->name('roles/index');
Route::get('roles/create', [App\Http\Controllers\Dashbord\RoleController::class, 'create'])->name('roles/create');
Route::post('roles/create', [App\Http\Controllers\Dashbord\RoleController::class, 'store'])->name('roles/store');;
Route::get('roles/cities', [App\Http\Controllers\Dashbord\RoleController::class, 'cities'])->name('roles/roles');;
Route::get('roles/edit/{id}', [App\Http\Controllers\Dashbord\RoleController::class, 'edit'])->name('roles/edit');
Route::patch('roles/edit/{id}', [App\Http\Controllers\Dashbord\RoleController::class, 'update'])->name('roles/update');
Route::DELETE('roles/delete/{id}', [App\Http\Controllers\Dashbord\RoleController::class, 'destroy'])->name('roles/delete');
Route::get('roles/show/{id}', [App\Http\Controllers\Dashbord\RoleController::class, 'show'])->name('roles/show');

// 
    Route::get('country', [App\Http\Controllers\Dashbord\CountryController::class, 'index'])->name('country');
    Route::get('country/create', [App\Http\Controllers\Dashbord\CountryController::class, 'create'])->name('country/create');
    Route::post('country/create', [App\Http\Controllers\Dashbord\CountryController::class, 'store'])->name('country/store');;
    Route::get('country/country', [App\Http\Controllers\Dashbord\CountryController::class, 'country'])->name('country/country');
    Route::get('country/changeStatus/{id}', [App\Http\Controllers\Dashbord\CountryController::class, 'changeStatus'])->name('country/changeStatus');
    Route::get('country/edit/{id}', [App\Http\Controllers\Dashbord\CountryController::class, 'edit'])->name('country/edit');
    Route::post('country/edit/{id}', [App\Http\Controllers\Dashbord\CountryController::class, 'update'])->name('country/update');

    // 
    Route::get('countryconditions', [App\Http\Controllers\Dashbord\CountrycConditionController::class, 'index'])->name('countryconditions');
    Route::get('countryconditions/create', [App\Http\Controllers\Dashbord\CountrycConditionController::class, 'create'])->name('countryconditions/create');
    Route::get('countryconditions/countryconditions', [App\Http\Controllers\Dashbord\CountrycConditionController::class, 'countryconditions'])->name('countryconditions/countryconditions');
    Route::post('countryconditions/create', [App\Http\Controllers\Dashbord\CountrycConditionController::class, 'store'])->name('countryconditions/store');;
    Route::get('countryconditions/changeStatus/{id}', [App\Http\Controllers\Dashbord\CountrycConditionController::class, 'changeStatus'])->name('countryconditions/changeStatus');
    Route::get('countryconditions/edit/{id}', [App\Http\Controllers\Dashbord\CountrycConditionController::class, 'edit'])->name('countryconditions/edit');
    Route::post('countryconditions/edit/{id}', [App\Http\Controllers\Dashbord\CountrycConditionController::class, 'update'])->name('countryconditions/update');


    // 
    Route::get('vehiclenationalities', [App\Http\Controllers\Dashbord\VehicleNationalityController::class, 'index'])->name('vehiclenationalities');
    Route::get('vehiclenationalities/create', [App\Http\Controllers\Dashbord\VehicleNationalityController::class, 'create'])->name('vehiclenationalities/create');
    Route::post('vehiclenationalities/create', [App\Http\Controllers\Dashbord\VehicleNationalityController::class, 'store'])->name('vehiclenationalities/store');;
    Route::get('vehiclenationalities/vehiclenationalities', [App\Http\Controllers\Dashbord\VehicleNationalityController::class, 'vehiclenationalities'])->name('vehiclenationalities/vehiclenationalities');
    Route::get('vehiclenationalities/changeStatus/{id}', [App\Http\Controllers\Dashbord\VehicleNationalityController::class, 'changeStatus'])->name('vehiclenationalities/changeStatus');
    Route::get('vehiclenationalities/edit/{id}', [App\Http\Controllers\Dashbord\VehicleNationalityController::class, 'edit'])->name('vehiclenationalities/edit');
    Route::post('vehiclenationalities/edit/{id}', [App\Http\Controllers\Dashbord\VehicleNationalityController::class, 'update'])->name('vehiclenationalities/update');



    // 
    Route::get('purposeofuses', [App\Http\Controllers\Dashbord\PurposeofuseController::class, 'index'])->name('purposeofuses');
    Route::get('purposeofuses/create', [App\Http\Controllers\Dashbord\PurposeofuseController::class, 'create'])->name('purposeofuses/create');
    Route::get('purposeofuses/purposeofuses', [App\Http\Controllers\Dashbord\PurposeofuseController::class, 'purposeofuses'])->name('purposeofuses/purposeofuses');
    Route::post('purposeofuses/create', [App\Http\Controllers\Dashbord\PurposeofuseController::class, 'store'])->name('purposeofuses/store');;
    Route::get('purposeofuses/changeStatus/{id}', [App\Http\Controllers\Dashbord\PurposeofuseController::class, 'changeStatus'])->name('purposeofuses/changeStatus');
    Route::get('purposeofuses/edit/{id}', [App\Http\Controllers\Dashbord\PurposeofuseController::class, 'edit'])->name('purposeofuses/edit');
    Route::post('purposeofuses/edit/{id}', [App\Http\Controllers\Dashbord\PurposeofuseController::class, 'update'])->name('purposeofuses/update');


    Route::get('region', [App\Http\Controllers\Dashbord\RegionController::class, 'index'])->name('region');
    Route::get('region/create', [App\Http\Controllers\Dashbord\RegionController::class, 'create'])->name('region/create');
    Route::post('region/create', [App\Http\Controllers\Dashbord\RegionController::class, 'store'])->name('region/store');;
    Route::get('region/region', [App\Http\Controllers\Dashbord\RegionController::class, 'region'])->name('region/region');;
    Route::get('region/edit/{id}', [App\Http\Controllers\Dashbord\RegionController::class, 'edit'])->name('region/edit');
    Route::post('region/edit/{id}', [App\Http\Controllers\Dashbord\RegionController::class, 'update'])->name('region/update');


    Route::get('insurance_clause', [App\Http\Controllers\Dashbord\InsuranceClauseController::class, 'index'])->name('insurance_clause');
    Route::get('insurance_clause/create', [App\Http\Controllers\Dashbord\InsuranceClauseController::class, 'create'])->name('insurance_clause/create');
    Route::post('insurance_clause/create', [App\Http\Controllers\Dashbord\InsuranceClauseController::class, 'store'])->name('insurance_clause/store');
    Route::get('insurance_clause/insurance_clause', [App\Http\Controllers\Dashbord\InsuranceClauseController::class, 'insuranceClause'])->name('insurance_clause/insurance_clause');
    Route::get('insurance_clause/edit/{id}', [App\Http\Controllers\Dashbord\InsuranceClauseController::class, 'edit'])->name('insurance_clause/edit');
    Route::post('insurance_clause/edit/{id}', [App\Http\Controllers\Dashbord\InsuranceClauseController::class, 'update'])->name('insurance_clause/update');





    Route::get('company', [App\Http\Controllers\Dashbord\CompanyController::class, 'index'])->name('company');
    Route::get('company/create', [App\Http\Controllers\Dashbord\CompanyController::class, 'create'])->name('company/create');
    Route::post('company/create', [App\Http\Controllers\Dashbord\CompanyController::class, 'store'])->name('company/store');
    Route::get('company/getCity/{id}', [App\Http\Controllers\Dashbord\CompanyController::class, 'getCity'])->name('company/getCity');
    Route::get('company/company', [App\Http\Controllers\Dashbord\CompanyController::class, 'companies'])->name('company/company');
    Route::get('company/changeStatus/{id}', [App\Http\Controllers\Dashbord\CompanyController::class, 'changeStatus'])->name('company/changeStatus');
    Route::get('company/edit/{id}', [App\Http\Controllers\Dashbord\CompanyController::class, 'edit'])->name('company/edit');
    Route::post('company/edit/{id}', [App\Http\Controllers\Dashbord\CompanyController::class, 'update'])->name('company/updates');



    Route::get('company_users/{company_id}', [App\Http\Controllers\Dashbord\CompanyUserController::class, 'index'])->name('company_users');
    Route::get('company_users/company_users/{id}', [App\Http\Controllers\Dashbord\CompanyUserController::class, 'company_users'])->name('company_users/company_users');
    Route::get('company_users/changeStatus/{id}', [App\Http\Controllers\Dashbord\CompanyUserController::class, 'changeStatus'])->name('company_users/changeStatus');
    Route::get('company_users/create/{company_id}', [App\Http\Controllers\Dashbord\CompanyUserController::class, 'create'])->name('company_users/create');
    Route::post('company_users/create/{company_id}', [App\Http\Controllers\Dashbord\CompanyUserController::class, 'store'])->name('company_users/store');
    Route::get('company_users/edit/{id}/{company_id}', [App\Http\Controllers\Dashbord\CompanyUserController::class, 'edit'])->name('company_users/edit');
    Route::post('company_users/edit/{id}/{company_id}', [App\Http\Controllers\Dashbord\CompanyUserController::class, 'update'])->name('company_users/update');
    Route::get('company_users/changepassord/{id}', [App\Http\Controllers\Dashbord\CompanyUserController::class, 'showChangePasswordForm'])->name('company_users/changepassord');
    Route::post('company_users/changepassord/{id}', [App\Http\Controllers\Dashbord\CompanyUserController::class, 'changePassword'])->name('company_users/changepassord/update');

    Route::get('offices', [App\Http\Controllers\Dashbord\OfficeController::class, 'index'])->name('offices');
    Route::get('offices/create', [App\Http\Controllers\Dashbord\OfficeController::class, 'create'])->name('offices/create');
    Route::post('offices/create', [App\Http\Controllers\Dashbord\OfficeController::class, 'store'])->name('offices/store');
    Route::get('offices/companyoffices', [App\Http\Controllers\Dashbord\OfficeController::class, 'companyoffices'])->name('offices/companyoffices');
    Route::get('offices/getall/{company_id}', [App\Http\Controllers\Dashbord\OfficeController::class, 'indexof'])->name('offices/getall');
    Route::get('offices/offices/{company_id}', [App\Http\Controllers\Dashbord\OfficeController::class, 'officesALL'])->name('offices/offices');
    Route::get('offices/changeStatus/{id}', [App\Http\Controllers\Dashbord\OfficeController::class, 'changeStatus'])->name('offices/changeStatus');
    Route::get('offices/edit/{id}', [App\Http\Controllers\Dashbord\OfficeController::class, 'edit'])->name('offices/edit');
    Route::post('offices/edit/{id}', [App\Http\Controllers\Dashbord\OfficeController::class, 'update'])->name('offices/updates');

    Route::get('offices_users/{offices_id}', [App\Http\Controllers\Dashbord\OfficeUserController::class, 'index'])->name('offices_users');
    Route::get('offices_users/create/{company_id}', [App\Http\Controllers\Dashbord\OfficeUserController::class, 'create'])->name('offices_users/create');
    Route::post('offices_users/create/{company_id}', [App\Http\Controllers\Dashbord\OfficeUserController::class, 'store'])->name('offices_users/store');
    Route::get('offices_users/offices_users/{id}', [App\Http\Controllers\Dashbord\OfficeUserController::class, 'offices_users'])->name('offices_users/offices_users');
    Route::get('offices_users/changeStatus/{id}', [App\Http\Controllers\Dashbord\OfficeUserController::class, 'changeStatus'])->name('offices_users/changeStatus');
    Route::get('offices_users/edit/{id}/{offices_id}', [App\Http\Controllers\Dashbord\OfficeUserController::class, 'edit'])->name('offices_users/edit');
    Route::post('offices_users/edit/{id}/{company_id}', [App\Http\Controllers\Dashbord\OfficeUserController::class, 'update'])->name('offices_users/update');
    Route::get('offices_users/changepassord/{id}', [App\Http\Controllers\Dashbord\OfficeUserController::class, 'showChangePasswordForm'])->name('offices_users/changepassordoffices_users');
    Route::post('offices_users/changepassord/{id}', [App\Http\Controllers\Dashbord\OfficeUserController::class, 'changePassword'])->name('offices_users/changepassord/update');


    Route::get('apiuser', [App\Http\Controllers\Dashbord\ApiuserController::class, 'index'])->name('apiuser');
    Route::get('apiuser/create', [App\Http\Controllers\Dashbord\ApiuserController::class, 'create'])->name('apiuser/create');
    Route::post('apiuser/create', [App\Http\Controllers\Dashbord\ApiuserController::class, 'store'])->name('apiuser/store');
    Route::get('apiuser/apiuser', [App\Http\Controllers\Dashbord\ApiuserController::class, 'apiuser'])->name('apiuser/apiuser');
    Route::get('apiuser/edit/{id}', [App\Http\Controllers\Dashbord\ApiuserController::class, 'edit'])->name('apiuser/edit');
    Route::post('apiuser/edit/{id}', [App\Http\Controllers\Dashbord\ApiuserController::class, 'update'])->name('apiuser/updates');


    Route::get('cardrequests', [App\Http\Controllers\Dashbord\RequestsController::class, 'index'])->name('cardrequests');
    Route::get('cardrequests/create', [App\Http\Controllers\Dashbord\RequestsController::class, 'create'])->name('cardrequests/create');
    Route::post('cardrequests/create', [App\Http\Controllers\Dashbord\RequestsController::class, 'store'])->name('cardrequests/store');
    Route::get('cardrequests/all', [App\Http\Controllers\Dashbord\RequestsController::class, 'ALLreqest'])->name('cardrequests/all');
    Route::get('cardrequests/updatestates/{id}', [App\Http\Controllers\Dashbord\RequestsController::class, 'updatestates'])->name('cardrequests/updatestates');
    Route::get('cardrequests/uplodecards/{id}', [App\Http\Controllers\Dashbord\RequestsController::class, 'uplodecards'])->name('cardrequests/uplodecards');

    Route::get('cardrequests/company', [App\Http\Controllers\Dashbord\RequestsController::class, 'indexco'])->name('cardrequests/company');
    Route::get('cardrequests/all/company', [App\Http\Controllers\Dashbord\RequestsController::class, 'ALLreqestcom'])->name('cardrequests/all/company');
    Route::get('cardrequests/upload-receipt/{id}', [App\Http\Controllers\Dashbord\RequestsController::class, 'showUploadReceiptForm'])->name('cardrequests/upload-receipt');
    Route::get('cardrequests/acceptrequest/{id}', [App\Http\Controllers\Dashbord\RequestsController::class, 'acceptrequest'])->name('cardrequests/acceptrequest');
    Route::get('cardrequests/rejectrequest/{id}', [App\Http\Controllers\Dashbord\RequestsController::class, 'rejectrequest'])->name('cardrequests/rejectrequest');
    Route::post('cardrequests/upload-payment-receipt/{id}', [App\Http\Controllers\Dashbord\RequestsController::class, 'uploadPaymentReceipt'])->name('cardrequests/upload-payment-receipt');

    
    Route::get('price', [App\Http\Controllers\Dashbord\PriceController::class, 'index'])->name('price');
    Route::get('price/create', [App\Http\Controllers\Dashbord\PriceController::class, 'create'])->name('price/create');
    Route::post('price/create', [App\Http\Controllers\Dashbord\PriceController::class, 'store'])->name('price/store');
    Route::get('price/price', [App\Http\Controllers\Dashbord\PriceController::class, 'price'])->name('price/price');
    Route::get('price/edit/{id}', [App\Http\Controllers\Dashbord\PriceController::class, 'edit'])->name('price/edit');
    Route::post('price/edit/{id}', [App\Http\Controllers\Dashbord\PriceController::class, 'update'])->name('price/updates');

    

    
    Route::get('card', [App\Http\Controllers\Dashbord\CardController::class, 'index'])->name('card');
    Route::get('card/all', [App\Http\Controllers\Dashbord\CardController::class, 'cardall'])->name('card/all');

    Route::get('card/active', [App\Http\Controllers\Dashbord\CardController::class, 'indexactive'])->name('card/active');
    Route::get('card/activeall', [App\Http\Controllers\Dashbord\CardController::class, 'cardallactive'])->name('card/activeall');
    Route::get('card/activeall/pdf', [App\Http\Controllers\Dashbord\CardController::class, 'printActiveCards'])->name('card/activeall/pdf');

    Route::get('card/inactive', [App\Http\Controllers\Dashbord\CardController::class, 'indexinactive'])->name('card/inactive');
    Route::get('card/inactiveall', [App\Http\Controllers\Dashbord\CardController::class, 'cardallinactive'])->name('card/inactiveall');
        Route::get('card/inactiveall/pdf', [App\Http\Controllers\Dashbord\CardController::class, 'printInactiveCards'])->name('card/inactiveall/pdf');

    Route::get('card/cancel', [App\Http\Controllers\Dashbord\CardController::class, 'indexcancel'])->name('card/cancel');
    Route::get('card/allcancel', [App\Http\Controllers\Dashbord\CardController::class, 'cardallcancel'])->name('card/allcancel');
    Route::get('card/cancelall/pdf', [App\Http\Controllers\Dashbord\CardController::class, 'indexcancelpdf'])->name('card/cancelall/pdf');



    Route::get('card/sold', [App\Http\Controllers\Dashbord\CardController::class, 'indexsold'])->name('card/sold');
    Route::get('card/allsold', [App\Http\Controllers\Dashbord\CardController::class, 'cardallsold'])->name('card/allsold');
    Route::get('card/soldall/pdf', [App\Http\Controllers\Dashbord\CardController::class, 'printSoldCardsPdf'])->name('card/soldall/pdf');

    Route::get('card/search', [App\Http\Controllers\Dashbord\CardController::class, 'search'])->name('card/search');
    Route::get('card/searchby', [App\Http\Controllers\Dashbord\CardController::class, 'searchby'])->name('card/searchby');
    Route::get('card/searchby/pdf', [App\Http\Controllers\Dashbord\CardController::class, 'searchbypdf'])->name('card/searchby/pdf');


    Route::get('report/issuing', [App\Http\Controllers\Dashbord\ReportController::class, 'index'])->name('report/issuing');
    Route::get('report/issuing/searchby', [App\Http\Controllers\Dashbord\ReportController::class, 'searchby'])->name('report/issuing/searchby');
    Route::get('report/issuing/summary', [App\Http\Controllers\Dashbord\ReportController::class, 'indexsummary'])->name('report/issuing/summary');
    Route::get('report/issuing/summary/{year}', [App\Http\Controllers\Dashbord\ReportController::class, 'indexsummaryByYear'])->name('report/issuing/summary/year');
    Route::get('report/issuing/search', [App\Http\Controllers\Dashbord\ReportController::class, 'searchpdf'])->name('report/issuing/search');

    // Year-based reports
    Route::get('report/issuing/{year}', [App\Http\Controllers\Dashbord\ReportController::class, 'indexByYear'])->name('report/issuing/year');
    Route::get('report/issuing/{year}/searchby', [App\Http\Controllers\Dashbord\ReportController::class, 'searchbyByYear'])->name('report/issuing/year/searchby');
    
 
     Route::get('report/issuing/export-xlsx', [App\Http\Controllers\Dashbord\ReportController::class, 'exportXlsx'])->name('report.issuing.export-xlsx');
     
     // routes/web.php
Route::get('/report/issuing/export-pdf', [App\Http\Controllers\Dashbord\ReportController::class, 'exportAllPdf'])
     ->name('report.issuing.export-pdf');
Route::get('/report/issuing/{year}/export-pdf', [App\Http\Controllers\Dashbord\ReportController::class, 'exportAllPdfByYear'])
     ->name('report.issuing.export-pdf-year');


    Route::get('report/issuing/summary/archives', [App\Http\Controllers\Dashbord\ReportController::class, 'indexsummaryarchives'])->name('report/issuing/summary/archives');
    Route::get('report/issuing/searchby/archives', [App\Http\Controllers\Dashbord\ReportController::class, 'searchbychives'])->name('report/issuing/searchby/archives');
    Route::get('report/issuing/search/summary/pdf', [App\Http\Controllers\Dashbord\ReportController::class, 'searchbychivespdf'])->name('report/issuing/search/summary/pdf');




    Route::get('report/issuing/search/summary', [App\Http\Controllers\Dashbord\ReportController::class, 'searchpdfsummery'])->name('report/issuing/search/summary');
    Route::get('report/cancelcards', [App\Http\Controllers\Dashbord\ReportController::class, 'indexcanelcard'])->name('report/cancelcards');
    Route::get('report/cancelcards/pdf', [App\Http\Controllers\Dashbord\ReportController::class, 'indexcanelcardpdf'])->name('report/cancelcards/pdf');

    Route::get('report/searchcacel', [App\Http\Controllers\Dashbord\ReportController::class, 'searchcacel'])->name('report/searchcacel');
    Route::get('report/requestcompany', [App\Http\Controllers\Dashbord\ReportController::class, 'indexreqiestcompany'])->name('report/requestcompany');
    Route::get('report/searchrequest', [App\Http\Controllers\Dashbord\ReportController::class, 'searchrequest'])->name('report/searchrequest');
    Route::get('report/requestcompany/pdf', [App\Http\Controllers\Dashbord\ReportController::class, 'indexRequestCompanyPdf'])->name('report/requestcompany/pdf');

    
    
   
    Route::get('report/officesuser/{id}', [App\Http\Controllers\Dashbord\ReportController::class, 'officesuser'])->name('report/officesuser');
    Route::get('report/companyuser/{id}', [App\Http\Controllers\Dashbord\ReportController::class, 'companyUser'])->name('report/companyuser');
    Route::get('report/offices/{id}', [App\Http\Controllers\Dashbord\ReportController::class, 'offices'])->name('report/offices');

    Route::get('report/stock', [App\Http\Controllers\Dashbord\ReportController::class, 'indexstock'])->name('report/stock');
    Route::get('report/sales', [App\Http\Controllers\Dashbord\ReportController::class, 'indexsales'])->name('report/sales');
        Route::get('report/salescount', [App\Http\Controllers\Dashbord\ReportController::class, 'indexsalescount'])->name('report/salescount');
    Route::get('report/stockpdf', [App\Http\Controllers\Dashbord\ReportController::class, 'stockpdf'])->name('report/stockpdf');
    Route::get('report/sales/pdf', [App\Http\Controllers\Dashbord\ReportController::class, 'indexsalespdf'])->name('report/sales/pdf');

    Route::get('viewdocument/{cardnumber}', [App\Http\Controllers\Dashbord\ReportController::class, 'viewdocument'])->name('viewdocument');
    Route::get('insurance-card', [App\Http\Controllers\Dashbord\ReportController::class, 'insuranceCard'])->name('insurance-card');

    
    
    
    Route::get('card/update', [App\Http\Controllers\Dashbord\CardController::class, 'replaceNumbers'])->name('card/update');
    // Route::get('caard', function () {
    //     return view('dashbord.report.result');
    // });
    
        Route::get('report/officeStats', [App\Http\Controllers\Dashbord\ReportController::class, 'officeStats'])->name('report/officeStats');
        Route::get('report/countryissuingsstats', [App\Http\Controllers\Dashbord\ReportController::class, 'countryissuingsstats'])->name('report/countryissuingsstats');
        Route::get('report/totalcompanyissuingstats', [App\Http\Controllers\Dashbord\ReportController::class, 'totalCompanyIssuingStats'])->name('report/totalcompanyissuingstats');
        Route::get('report/office-users-stats', [App\Http\Controllers\Dashbord\ReportController::class, 'officeUsersStats'])->name('report/officeUsersStats');
        Route::get('report/office-summary', [App\Http\Controllers\Dashbord\ReportController::class, 'officeSummaryReport'])->name('report/officeSummary');
        Route::get('report/companySummary', [App\Http\Controllers\Dashbord\ReportController::class, 'companySummaryReport'])->name('report/companySummary');
        Route::get('report/companySummary/pdf', [App\Http\Controllers\Dashbord\ReportController::class, 'companySummaryReportpdf'])->name('report/companySummary/pdf');
    Route::get('report/officeSummaryByCompany', [App\Http\Controllers\Dashbord\ReportController::class, 'officeSummaryByCompany'])->name('report/officeSummaryByCompany');

    Route::get('logs/activity', [App\Http\Controllers\Dashbord\LogsController::class, 'activityLogs'])->name('logs/activity');
    Route::get('logs/api', [App\Http\Controllers\Dashbord\LogsController::class, 'apiLogs'])->name('logs/api');

});


Route::namespace('Company')->group(function () {
    
      Route::get('company/report/officeStats', [App\Http\Controllers\Company\ReportController::class, 'officeStats'])->name('company/report/officeStats');
         Route::get('company/report/office-users-stats', [App\Http\Controllers\Company\ReportController::class, 'officeUsersStats'])->name('company/report/officeUsersStats');
    Route::get('company/login', [App\Http\Controllers\Company\Auth\CompanyloginController::class, 'showLoginForm'])->name('company/login');
    Route::get('company/refreshcaptcha', [App\Http\Controllers\Company\Auth\CompanyloginController::class, 'refreshcaptcha'])->name('company/refreshcaptcha');
    Route::post('company/store', [App\Http\Controllers\Company\Auth\CompanyloginController::class, 'login'])->name('company/store');
    Route::get('company/home', [App\Http\Controllers\Company\HomeController::class, 'index'])->name('company/home');
    Route::post('company/logout', [App\Http\Controllers\Company\Auth\CompanyloginController::class, 'logout'])->name('company/logout');
    Route::get('company/changepassword/{id}', [App\Http\Controllers\Company\HomeController::class, 'showChangePasswordForm'])->name('company/ChangePasswordForm');
    Route::POST('company/changepassword/{id}', [App\Http\Controllers\Company\HomeController::class, 'changePassword'])->name('company/changepassword');


    Route::get('company/company_users', [App\Http\Controllers\Company\CompanyUserController::class, 'index'])->name('company/company_users');
    Route::get('company/company_users/company_users', [App\Http\Controllers\Company\CompanyUserController::class, 'company_users'])->name('company/company_users/company_users');
    Route::get('company/company_users/changeStatus/{id}', [App\Http\Controllers\Company\CompanyUserController::class, 'changeStatus'])->name('company/company_users/changeStatus');
    Route::get('company/company_users/create', [App\Http\Controllers\Company\CompanyUserController::class, 'create'])->name('company/company_users/create');
    Route::post('company/company_users/create', [App\Http\Controllers\Company\CompanyUserController::class, 'store'])->name('company/company_users/store');
    Route::get('company/company_users/edit/{id}', [App\Http\Controllers\Company\CompanyUserController::class, 'edit'])->name('company/company_users/edit');
    Route::post('company/company_users/edit/{id}', [App\Http\Controllers\Company\CompanyUserController::class, 'update'])->name('company/company_users/update');
    Route::get('company/company_users/showpermission/{id}', [App\Http\Controllers\Company\CompanyUserController::class, 'showpermission'])->name('company/company_users/showpermission');
    Route::DELETE('company/company_users/deletePermission/{id}', [App\Http\Controllers\Company\CompanyUserController::class, 'deletePermission'])->name('company/company_users/deletePermission');
    Route::get('company/company_users/changepassord/{id}', [App\Http\Controllers\Company\CompanyUserController::class, 'showChangePasswordForm'])->name('company/changepassord/edit');
    Route::post('company/company_users/changepassord/{id}', [App\Http\Controllers\Company\CompanyUserController::class, 'changePassword'])->name('company/changepassord/update');
  
    
    

    Route::get('company/offices', [App\Http\Controllers\Company\OfficeController::class, 'index'])->name('company/offices');
    Route::get('company/offices/create', [App\Http\Controllers\Company\OfficeController::class, 'create'])->name('company/offices/create');
    Route::get('company/offices/getCity/{id}', [App\Http\Controllers\Company\OfficeController::class, 'getCity'])->name('company/offices/getCity');
    Route::post('company/offices/create', [App\Http\Controllers\Company\OfficeController::class, 'store'])->name('offices/store');
    Route::get('company/offices/offices/', [App\Http\Controllers\Company\OfficeController::class, 'officesALL'])->name('company/offices/offices');
    Route::get('company/offices/changeStatus/{id}', [App\Http\Controllers\Company\OfficeController::class, 'changeStatus'])->name('company/offices/changeStatus');
    Route::get('company/offices/edit/{id}', [App\Http\Controllers\Company\OfficeController::class, 'edit'])->name('company/offices/edit');
    Route::post('company/offices/edit/{id}', [App\Http\Controllers\Company\OfficeController::class, 'update'])->name('company/offices/updates');

    Route::get('company/offices_users/{offices_id}', [App\Http\Controllers\Company\OfficeUserController::class, 'index'])->name('company/offices_users');
    Route::get('company/offices_users/offices_users/{offices_id}', [App\Http\Controllers\Company\OfficeUserController::class, 'offices_users'])->name('company/offices_users/offices_users');
    Route::get('company/offices_users/create/{offices_id}', [App\Http\Controllers\Company\OfficeUserController::class, 'create'])->name('company/offices_users/create');
    Route::post('company/offices_users/create/{offices_id}', [App\Http\Controllers\Company\OfficeUserController::class, 'store'])->name('company/offices_users/store');
    Route::get('company/offices_users/changeStatus/{id}', [App\Http\Controllers\Company\OfficeUserController::class, 'changeStatus'])->name('company/offices_users/changeStatus');
    Route::get('company/offices_users/edit/{id}/{offices_id}', [App\Http\Controllers\Company\OfficeUserController::class, 'edit'])->name('company/offices_users/edit');
    Route::post('company/offices_users/edit/{id}/{offices_id}', [App\Http\Controllers\Company\OfficeUserController::class, 'update'])->name('company/offices_users/update');
    Route::get('company/offices_users/changep/{id}', [App\Http\Controllers\Company\OfficeUserController::class, 'showChangePasswordForm'])->name('company/offices_users/changep/edit');
    Route::post('company/offices_users/changep/{id}', [App\Http\Controllers\Company\OfficeUserController::class, 'changePassword'])->name('company/offices_users/changep');
    Route::get('company/offices_users/showpermission/{id}', [App\Http\Controllers\Company\OfficeUserController::class, 'showpermission'])->name('company/offices_users/showpermission');
    Route::DELETE('company/offices_users/deletePermission/{id}', [App\Http\Controllers\Company\OfficeUserController::class, 'deletePermission'])->name('company/offices_users/deletePermission');


    Route::get('company/cardrequests', [App\Http\Controllers\Company\RequestsController::class, 'index'])->name('company/cardrequests');
    Route::get('company/cardrequests/create', [App\Http\Controllers\Company\RequestsController::class, 'create'])->name('company/cardrequests/create');
    Route::post('company/cardrequests/create', [App\Http\Controllers\Company\RequestsController::class, 'store'])->name('company/cardrequests/store');
    Route::get('company/cardrequests/all', [App\Http\Controllers\Company\RequestsController::class, 'ALLreqest'])->name('company/cardrequests/all');
    Route::get('company/cardrequests/updatestates/{id}', [App\Http\Controllers\Company\RequestsController::class, 'updatestates'])->name('company/cardrequests/updatestates');
    Route::get('company/cardrequests/uplodecards/{id}', [App\Http\Controllers\Company\RequestsController::class, 'uplodecards'])->name('company/cardrequests/uplodecards');



    Route::get('company/card', [App\Http\Controllers\Company\CardController::class, 'index'])->name('company/card');
    Route::get('company/card/all', [App\Http\Controllers\Company\CardController::class, 'cardall'])->name('company/card/all');
    Route::get('company/card/all/pdf', [App\Http\Controllers\Company\CardController::class, 'printAllCardsPDF'])->name('company/card/all/pdf');



    Route::get('company/card/active', [App\Http\Controllers\Company\CardController::class, 'indexactive'])->name('company/card/active');
    Route::get('company/card/activeall', [App\Http\Controllers\Company\CardController::class, 'cardallactive'])->name('company/card/activeall');
    Route::get('company/card/activeall/pdf', [App\Http\Controllers\Company\CardController::class, 'indexactivepdf'])->name('company/card/activeall/pdf');

    Route::get('company/card/cancel', [App\Http\Controllers\Company\CardController::class, 'indexcancel'])->name('company/card/cancel');
    Route::get('company/card/allcancel', [App\Http\Controllers\Company\CardController::class, 'cardallcance'])->name('company/card/allcancel');
         Route::get('company/card/cancel/pdf', [App\Http\Controllers\Company\CardController::class, 'indexcancelpdf'])->name('company/card/cancel/pdf');

    Route::get('company/card/sold', [App\Http\Controllers\Company\CardController::class, 'indexsold'])->name('company/card/sold');
    Route::get('company/card/allsold', [App\Http\Controllers\Company\CardController::class, 'cardallsold'])->name('company/card/allsold');
        Route::get('company/card/sold/pdf', [App\Http\Controllers\Company\CardController::class, 'indexsoldpdf'])->name('company/card/sold/pdf');



    Route::get('company/card/search', [App\Http\Controllers\Company\CardController::class, 'search'])->name('company/card/search');
    Route::get('company/card/searchby', [App\Http\Controllers\Company\CardController::class, 'searchby'])->name('company/card/searchby');
    Route::get('company/card/searchby/pdf', [App\Http\Controllers\Company\CardController::class, 'searchbypdf'])->name('company/card/searchby/pdf');

        
    Route::get('company/issuing', [App\Http\Controllers\Company\IssuingController::class, 'index'])->name('company/issuing');
    Route::get('company/issuing/country/{id}', [App\Http\Controllers\Company\IssuingController::class, 'country'])->name('company/issuing/country');
    Route::get('company/issuing/tax', [App\Http\Controllers\Company\IssuingController::class, 'issuingtax'])->name('company/issuing/tax');
    Route::get('company/issuing/search-vehicle', [App\Http\Controllers\Company\IssuingController::class, 'searchVehicle'])->name('company/issuing/search-vehicle');
    Route::post('company/issuing', [App\Http\Controllers\Company\IssuingController::class, 'store'])->name('company/issuing/store');
    Route::get('company/viewdocument/{cardnumber}', [App\Http\Controllers\Company\IssuingController::class, 'viewdocument'])->name('company/viewdocument');
    Route::post('company/cancelplicy/{cardnumber}', [App\Http\Controllers\Company\IssuingController::class, 'cancelPolicy'])->name('company/cancelplicy');
    Route::get('company/document/{card_id}', [App\Http\Controllers\Company\IssuingController::class, 'document'])->name('company/document');


    
    Route::get('company/distribution', [App\Http\Controllers\Company\DistributionController::class, 'index'])->name('company/distribution');
    Route::get('company/distribution/create', [App\Http\Controllers\Company\DistributionController::class, 'create'])->name('company/distribution/create');
    Route::post('company/distribution/create', [App\Http\Controllers\Company\DistributionController::class, 'store'])->name('company/distribution/store');
    Route::get('company/distribution/distribution', [App\Http\Controllers\Company\DistributionController::class, 'distributions'])->name('company/distribution/distribution');
    Route::get('company/distribution/detail/{office_id}', [App\Http\Controllers\Company\DistributionController::class, 'indexdetail'])->name('company/distribution/detail');
    Route::get('company/distribution/detailall/{office_id}', [App\Http\Controllers\Company\DistributionController::class, 'detailall'])->name('company/distribution/detailall');
    Route::delete('company/distribution/destroy/{id}', [App\Http\Controllers\Company\DistributionController::class, 'destroy'])->name('company/distribution/destroy');


    Route::get('company/refund', [App\Http\Controllers\Company\RefundController::class, 'index'])->name('company/refund');
    Route::get('company/refund/create', [App\Http\Controllers\Company\RefundController::class, 'create'])->name('company/refund/create');
    Route::post('company/refund/create', [App\Http\Controllers\Company\RefundController::class, 'store'])->name('company/refund/store');
    Route::get('company/refund/refund', [App\Http\Controllers\Company\RefundController::class, 'refund'])->name('company/refund/refund');


    Route::get('company/report/issuing', [App\Http\Controllers\Company\ReportController::class, 'index'])->name('company/report/issuing');
    Route::get('company/report/issuing/summary', [App\Http\Controllers\Company\ReportController::class, 'indexsummary'])->name('company/report/issuing/summary');
    Route::get('company/report/issuing/search/summary', [App\Http\Controllers\Company\ReportController::class, 'searchpdfsummery'])->name('company/report/issuing/search/summary');

    Route::get('company/report/issuing/searchby', [App\Http\Controllers\Company\ReportController::class, 'searchby'])->name('company/report/issuing/searchby');
    Route::get('company/report/officesuser/{id}', [App\Http\Controllers\Company\ReportController::class, 'officesuser'])->name('company/report/officesuser');
    Route::get('company/report/companyuser', [App\Http\Controllers\Company\ReportController::class, 'companyUser'])->name('company/report/companyuser');
    Route::get('company/report/issuing/search/searchpdf', [App\Http\Controllers\Company\ReportController::class, 'searchpdf'])->name('company/report/issuing/search/searchpdf');

    Route::get('company/report/cancelcards', [App\Http\Controllers\Company\ReportController::class, 'indexcanelcard'])->name('company/report/cancelcards');

    Route::get('company/report/searchcacel', [App\Http\Controllers\Company\ReportController::class, 'searchcacel'])->name('company/report/searchcacel');
       Route::get('company/report/cancelcardspdf', [App\Http\Controllers\Company\ReportController::class, 'indexcanelcardpdf'])->name('company/report/cancelcardspdf');

    
    Route::get('company/report/stock', [App\Http\Controllers\Company\ReportController::class, 'indexstock'])->name('company/report/stock');
    Route::get('company/report/stock/company', [App\Http\Controllers\Company\ReportController::class, 'indexstockc'])->name('company/report/stock/company');
    Route::get('company/report/stock/companys', [App\Http\Controllers\Company\ReportController::class, 'stockcpdf'])->name('company/report/stock/companys');



    Route::get('company/report/stock/stockpdf', [App\Http\Controllers\Company\ReportController::class, 'stockpdf'])->name('company/report/stock/stockpdf');
    
      Route::get('company/report/companySummary', [App\Http\Controllers\Company\ReportController::class, 'officeSummaryForMyCompany'])->name('company/report/companySummary');
        Route::get('company/report/companySummary/pdf', [App\Http\Controllers\Company\ReportController::class, 'companySummaryReportpdf'])->name('company/report/companySummary/pdf');

     
});


Route::namespace('Office')->group(function () {
    Route::get('office/login', [App\Http\Controllers\Office\Auth\OfficeloginController::class, 'showLoginForm'])->name('office/login');
    Route::get('office/refreshcaptcha', [App\Http\Controllers\Office\Auth\OfficeloginController::class, 'refreshcaptcha'])->name('office/refreshcaptcha');
    Route::post('office/login', [App\Http\Controllers\Office\Auth\OfficeloginController::class, 'login'])->name('office/store');
    Route::get('office/home', [App\Http\Controllers\Office\HomeController::class, 'index'])->name('office/home');
    Route::post('office/logout', [App\Http\Controllers\Office\Auth\OfficeloginController::class, 'logout'])->name('office/logout');
    Route::get('office/changepassword/{id}', [App\Http\Controllers\Office\HomeController::class, 'showChangePasswordForm'])->name('office/ChangePasswordForm');
    Route::POST('office/changepassword/{id}', [App\Http\Controllers\Office\HomeController::class, 'changePassword'])->name('office/changepassword');
    Route::get('office/profile/{id}', [App\Http\Controllers\Office\HomeController::class, 'show'])->name('office/profile');

    Route::get('office/offices_users', [App\Http\Controllers\Office\OfficeUserController::class, 'index'])->name('office/offices_users');
    Route::get('office/offices_users/offices_users', [App\Http\Controllers\Office\OfficeUserController::class, 'offices_users'])->name('office/offices_users/offices_users');
    Route::get('office/offices_users/create', [App\Http\Controllers\Office\OfficeUserController::class, 'create'])->name('office/offices_users/create');
    Route::post('office/offices_users/create', [App\Http\Controllers\Office\OfficeUserController::class, 'store'])->name('office/offices_users/store');
    Route::get('office/offices_users/changeStatus/{id}', [App\Http\Controllers\Office\OfficeUserController::class, 'changeStatus'])->name('office/offices_users/changeStatus');
    Route::get('office/offices_users/edit/{id}', [App\Http\Controllers\Office\OfficeUserController::class, 'edit'])->name('office/offices_users/edit');
    Route::post('office/offices_users/edit/{id}', [App\Http\Controllers\Office\OfficeUserController::class, 'update'])->name('office/offices_users/update');
    Route::get('office/offices_users/changepassord/{id}', [App\Http\Controllers\Office\OfficeUserController::class, 'showChangePasswordForm'])->name('office/offices_users/changepassord');
    Route::post('office/offices_users/changepassord/{id}', [App\Http\Controllers\Office\OfficeUserController::class, 'changePassword'])->name('office/offices_users/changepassord/update');


    Route::get('office/offices_users/showpermission/{id}', [App\Http\Controllers\Office\OfficeUserController::class, 'showpermission'])->name('office/offices_users/showpermission');
    Route::DELETE('office/offices_users/deletePermission/{id}', [App\Http\Controllers\Office\OfficeUserController::class, 'deletePermission'])->name('office/offices_users/deletePermission');

    

    Route::get('office/card', [App\Http\Controllers\Office\CardController::class, 'index'])->name('office/card');
    Route::get('office/card/all', [App\Http\Controllers\Office\CardController::class, 'cardall'])->name('office/card/all');

    Route::get('office/card/active', [App\Http\Controllers\Office\CardController::class, 'indexactive'])->name('office/card/active');
    Route::get('office/card/activeall', [App\Http\Controllers\Office\CardController::class, 'cardallactive'])->name('office/card/activeall');


    Route::get('office/card/sold', [App\Http\Controllers\Office\CardController::class, 'indexsold'])->name('office/card/sold');
    Route::get('office/card/allsold', [App\Http\Controllers\Office\CardController::class, 'cardallsold'])->name('office/card/allsold');
    
    Route::get('office/card/cancel', [App\Http\Controllers\Office\CardController::class, 'indexcancel'])->name('office/card/cancel');
    Route::get('office/card/allcancel', [App\Http\Controllers\Office\CardController::class, 'cardallcancel'])->name('office/card/allcancel');


    Route::get('office/card/search', [App\Http\Controllers\Office\CardController::class, 'search'])->name('office/card/search');
    Route::get('office/card/searchby', [App\Http\Controllers\Office\CardController::class, 'searchby'])->name('office/card/searchby');


    Route::get('office/issuing', [App\Http\Controllers\Office\IssuingController::class, 'index'])->name('office/issuing');
    Route::get('office/issuing/country/{id}', [App\Http\Controllers\Office\IssuingController::class, 'country'])->name('office/issuing/country');
    Route::get('office/issuing/tax', [App\Http\Controllers\Office\IssuingController::class, 'issuingtax'])->name('office/issuing/tax');
    Route::get('office/issuing/search-vehicle', [App\Http\Controllers\Office\IssuingController::class, 'searchVehicle'])->name('office/issuing/search-vehicle');
    Route::post('office/issuing', [App\Http\Controllers\Office\IssuingController::class, 'store'])->name('office/issuing/store');
    Route::get('office/viewdocument/{cardnumber}', [App\Http\Controllers\Office\IssuingController::class, 'viewdocument'])->name('office/viewdocument');

    Route::get('office/document/{card_id}', [App\Http\Controllers\Office\IssuingController::class, 'document'])->name('office/document');

    Route::get('office/report/issuing', [App\Http\Controllers\Office\ReportController::class, 'index'])->name('office/report/issuing');
    Route::get('office/report/issuing/summary', [App\Http\Controllers\Office\ReportController::class, 'indexsummary'])->name('office/report/issuing/summary');
    Route::get('office/report/issuing/pdf', [App\Http\Controllers\Office\ReportController::class, 'searchpdf'])->name('office/report/issuing/pdf');
    Route::get('office/report/issuing/summary/pdf', [App\Http\Controllers\Office\ReportController::class, 'searchpdfresultsummary'])->name('office/report/issuing/summary/pdf');

   
    
    Route::get('office/report/issuing/searchby', [App\Http\Controllers\Office\ReportController::class, 'searchby'])->name('office/report/issuing/searchby');
 
 
    Route::get('office/report/stock', [App\Http\Controllers\Office\ReportController::class, 'indexstock'])->name('office/report/stock');


    
    
});

});