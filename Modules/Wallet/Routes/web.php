<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->group(function () {
	Route::middleware(['web', 'auth'])->group(function () {
		/* Route::resource('wallet', 'WalletController', ['names' => 'wallet'])->only([
            'index', 'show'
        ]);*/
		Route::resource('credit-redeem-requests', 'RedeemCreditRequestController', ['names' => 'redeemRequest'])->only([
			'index'
		]);
		Route::post('get-confirm-box', 'RedeemCreditRequestController@getConfirmBox')->name('redeemRequest.getConfirmBox');
		Route::post('change-redeem-status', 'RedeemCreditRequestController@changeRedeemStatus')->name('redeemRequest.changeRedeemStatus');
	});
});


//Use Route for Auth User
Route::group(
	[
		'prefix' => '',
		'middleware' => ['auth', 'frontpermssion']
	],
	function () {
		Route::get('investor/my-wallet', 'Frontend\MyWalletController@myWallet')->name('investor.myWallet');
		Route::get('borrower/my-wallet', 'Frontend\MyWalletController@myWallet')->name('borrower.myWallet');
		Route::get('investor/proceed-to-wallet-payment/{slug}', 'Frontend\MyWalletController@proceedToAddWalletMoneyPayForm')->name('investor.proceedToWalletPaymentForm');
		Route::get('borrower/proceed-to-wallet-payment/{slug}', 'Frontend\MyWalletController@proceedToAddWalletMoneyPayForm')->name('borrower.proceedToWalletPaymentForm');

		Route::post('dashboard/wallet-payment', 'Frontend\MyWalletController@makeWalletPayment')->name('dashboard.makeWalletPayment');

		Route::get('investor/my-transactions', 'Frontend\MyWalletController@walletPaymentSummary')->name('investor.walletPaymentSummary');
		Route::get('borrower/my-transactions', 'Frontend\MyWalletController@walletPaymentSummary')->name('borrower.walletPaymentSummary');

		Route::get('investor/redeem-credit', 'Frontend\MyWalletController@redeemCredit')->name('investor.redeemCredit');
		Route::get('borrower/redeem-credit', 'Frontend\MyWalletController@redeemCredit')->name('borrower.redeemCredit');
	}
);
