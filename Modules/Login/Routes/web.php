<?php

use Modules\Login\Http\Controllers\Auth\VerificationController;
use Modules\Login\Http\Controllers\Auth\ForgotPasswordController;
use Modules\Login\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Login Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['reset' => false,'verify' => false,'login' => false, 'register' => false]);

//Verify Email custom for all types of Routes
Route::get('customer/email/verify',  [VerificationController::class, 'show'])->name('verification.notice');
Route::post('customer/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::get('customer/email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::get('agent/email/verify', [VerificationController::class, 'show'])->name('agent.verification.notice');
Route::post('agent/email/resend', [VerificationController::class, 'resend'])->name('agent.verification.resend');
Route::get('agent/email/verify/{id}', [VerificationController::class, 'verify'])->name('agent.verification.verify');

Route::get('company/email/verify', [VerificationController::class, 'show'])->name('company.verification.notice');
Route::post('company/email/resend', [VerificationController::class, 'resend'])->name('company.verification.resend');
Route::get('company/email/verify/{id}', [VerificationController::class, 'verify'])->name('company.verification.verify');

Route::get('owner/email/verify', [VerificationController::class, 'show'])->name('vendor.verification.notice');
Route::post('owner/email/resend', [VerificationController::class, 'resend'])->name('vendor.verification.resend');
Route::get('owner/email/verify/{id}', [VerificationController::class, 'verify'])->name('vendor.verification.verify');
//End Verify Email custom for all types of Routes

//Route::get('customer/login', '\Modules\Login\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('customer/login', '\Modules\Login\Http\Controllers\Auth\LoginController@login')->name('login');
Route::get('customer/login', '\Modules\Login\Http\Controllers\Auth\LoginController@showLoginForm')->name('customer.login');

Route::get('customer/register', '\Modules\Login\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('customer/register', '\Modules\Login\Http\Controllers\Auth\RegisterController@register')->name('register');

Route::get('customer/resend-mobile-otp', '\Modules\Login\Http\Controllers\Auth\LoginController@resendOTPMobile')->name('resend.otp');
Route::get('owner/resend-mobile-otp', '\Modules\Login\Http\Controllers\Auth\LoginController@resendOTPMobile')->name('vendor.resend.otp');
// Register mobile OTP routes
Route::get('customer/verify-mobile-register-otp', '\Modules\Login\Http\Controllers\Auth\RegisterController@getcustomerMobileVerify')->name('customer.MobileVerify');
Route::post('customer/verify-mobile-register-otp', '\Modules\Login\Http\Controllers\Auth\RegisterController@customerMobileVerifyOTP')->name('customer.MobileVerify.OTP');

// Login Mobile OTP routes
Route::get('customer/verify-mobile-login-otp/{url?}', '\Modules\Login\Http\Controllers\Auth\LoginController@customerMobileLoginOTPScreen')->name('customer.MobileLoginOTPScreen');
Route::post('customer/verify-mobile-login-otp', '\Modules\Login\Http\Controllers\Auth\LoginController@customerMobileLoginOTPVerify')->name('customer.MobileLoginOTPVerify');

// Customer Login Routes
Route::post('customer/login-mobile', '\Modules\Login\Http\Controllers\Auth\LoginController@userLoginbyMobile')->name('customer.login.mobile');
Route::post('customer/login', '\Modules\Login\Http\Controllers\Auth\LoginController@userLogin')->name('auth.login');
Route::post('customer/logout', '\Modules\Login\Http\Controllers\Auth\LoginController@logout')->name('customer.logout');
Route::get('logout', '\Modules\Login\Http\Controllers\Auth\LoginController@logout')->name('auth.logout');

 
// Owner Login Routes
Route::post('owner/register', '\Modules\Login\Http\Controllers\Auth\VendorRegisterController@userRegister')->name('vendor.register');
Route::get('owner/login', '\Modules\Login\Http\Controllers\Auth\LoginController@showVendorLoginForm')->name('vendor.login');

Route::post('owner/logout', '\Modules\Login\Http\Controllers\Auth\LoginController@logout')->name('owner.logout');
Route::post('owner/vendor-register', '\Modules\Login\Http\Controllers\Auth\VendorRegisterController@userRegister')->name('vendor.registered');

Route::post('owner/login-mobile-vendor', '\Modules\Login\Http\Controllers\Auth\LoginController@vendorLoginbyMobile')->name('vendor.login.mobile');
Route::post('owner/verify-vendor-mobile-register-otp', '\Modules\Login\Http\Controllers\Auth\LoginController@vendorMobileLoginOTPVerify')->name('vendor.MobileVerify.OTP');

// Agent Login Routes
Route::get('agent/login', '\Modules\Login\Http\Controllers\Auth\LoginController@showAgentLoginForm')->name('agent.login');
Route::post('agent/login', '\Modules\Login\Http\Controllers\Auth\LoginController@agentLogin')->name('agent.login');
Route::post('agent/logout', '\Modules\Login\Http\Controllers\Auth\LoginController@logout')->name('agent.logout');

// Company Login Routes
Route::get('company/login', '\Modules\Login\Http\Controllers\Auth\LoginController@showCompanyLoginForm')->name('company.login');
Route::post('company/login', '\Modules\Login\Http\Controllers\Auth\LoginController@companyLogin')->name('company.login');
Route::post('company/logout', '\Modules\Login\Http\Controllers\Auth\LoginController@logout')->name('company.logout');


//Custom EMail Reset Routes for all type Roles
Route::post('customer/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('customer.password.email');
Route::get('customer/password/reset',  [ForgotPasswordController::class, 'showLinkRequestForm'])->name('customer.password.request');
Route::post('customer/password/reset', [ResetPasswordController::class, 'reset'])->name('customer.password.update');
Route::get('customer/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('customer.password.reset');

Route::post('owner/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('vendor.password.email');
Route::get('owner/password/reset',  [ForgotPasswordController::class, 'showLinkRequestForm'])->name('vendor.password.request');
Route::post('owner/password/reset', [ResetPasswordController::class, 'reset'])->name('vendor.password.update');
Route::get('owner/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('vendor.password.reset');

Route::post('agent/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('agent.password.email');
Route::get('agent/password/reset',  [ForgotPasswordController::class, 'showLinkRequestForm'])->name('agent.password.request');
Route::post('agent/password/reset', [ResetPasswordController::class, 'reset'])->name('agent.password.update');
Route::get('agent/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('agent.password.reset');

Route::post('company/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('company.password.email');
Route::get('company/password/reset',  [ForgotPasswordController::class, 'showLinkRequestForm'])->name('company.password.request');
Route::post('company/password/reset', [ResetPasswordController::class, 'reset'])->name('company.password.update');
Route::get('company/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('company.password.reset');
//End Custom EMail Reset Routes for all type Roles