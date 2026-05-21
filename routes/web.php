<?php

use App\Http\Controllers\ClientDocumentsController;
use App\Http\Controllers\ClientHomeController;
use App\Http\Controllers\ClientTransactionsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DirepayController;
use App\Http\Controllers\FineraController;
use App\Http\Controllers\Inabit\InabitDebugController;
use App\Http\Controllers\InabitController;
use App\Http\Controllers\KeynexPayController;
use App\Http\Controllers\LuqapayController;
use App\Http\Controllers\PaymentPageController;
use App\Http\Controllers\PaystraxController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RyvylController;
use App\Http\Controllers\SecurePayZoneController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\StradaPayController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransvoucherController;
use App\Models\Company;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UpiPaymentController;
use App\Http\Controllers\UpiV2Controller;

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

Route::get('/', function () {
    // return \Illuminate\Support\Facades\Hash::make('jk2wfh3$1w@!');
    return redirect('/sign-in/user');
    // return view('welcome');
});

Route::get('/sign-in', function () {
    return redirect('/sign-in/user');
});
Route::get('/sign-in/user', function () {

    if (Auth::check())
    {
        if (auth()->user()->status == '1') {
            return redirect()->route('showHome');
        }
        if (auth()->user()->status == '0') {
            return redirect()->route('dashboard');
        }
    }

    return view('client.login');
});

Route::get('/dashboard', function () {
    $companies = Company::orderBy('company_name','asc')->get();
    return view('dashboard',compact('companies'));
})->middleware(['auth', 'verified', 'check.status'])->name('dashboard'); //admin side dashboard

//admin side dashboard pages
Route::middleware(['auth','check.status'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //admin side dashboard pages
    Route::get('/admin/register-company', [CompanyController::class, 'showRegisterCompany'])->name('showRegisterCompany');
    Route::post('/add-company',[CompanyController::class,'addCompany'])->name('addCompany');

    Route::get('/admin/add-payment-service', [CompanyController::class, 'showAddPaymentService'])->name('showAddPaymentService');
    Route::post('/assignPMtoComapany',[CompanyController::class,'assignPMtoComapany'])->name('assignPMtoComapany');    //assign payment method to company

    Route::get('/admin/p1-services',[CompanyController::class,'showPaystraxService'])->name('showPaystraxService');
    Route::get('/admin/p2-services',[CompanyController::class,'showPaybisService'])->name('showPaybisService');
    Route::get('/admin/p3-services',[CompanyController::class,'showX1Service'])->name('showX1Service');
    Route::get('/admin/p4-services',[CompanyController::class,'showStradapayService'])->name('showStradapayService');
    Route::get('/admin/p5-services',[CompanyController::class,'showRyvylService'])->name('showRyvylService');
    Route::get('/admin/p6-services',[CompanyController::class,'showTransvoucherService'])->name('showTransvoucherService');
    Route::get('/admin/p7-services',[CompanyController::class,'showSecurePayZoneService'])->name('showSecurePayZoneService');
    Route::get('/admin/p8-services',[CompanyController::class,'showLuqapayService'])->name('showLuqapayService');
    Route::get('/admin/p9-services',[CompanyController::class,'showTrigopaymentService'])->name('showTrigopaymentService');
    Route::get('/admin/p10-services',[CompanyController::class,'showInabitService'])->name('showInabitService');
    Route::get('/admin/p10-services/update-balance/{id}',[CompanyController::class,'updateInabitWidgetBalance']);
    Route::get('/admin/p10-services/purchase/update-balance/{id}',[CompanyController::class,'updateInabitPurchaseWidgetBalance']);
    Route::get('/admin/p11-services',[CompanyController::class,'showPaytoroPaymentService'])->name('showPaytoroPaymentService');
    Route::get('/admin/p12-services',[CompanyController::class,'showPGTechPayService'])->name('showPGTechPayService');
    Route::get('/admin/p13-services',[CompanyController::class,'showAliz7Service'])->name('showAliz7Service');
    Route::get('/admin/p14-services',[CompanyController::class,'showNiobiService'])->name('showNiobiService');
    Route::get('/admin/p15-services',[CompanyController::class,'showSmileService'])->name('showSmileService');
    Route::get('/admin/p16-services',[CompanyController::class,'showTrustitService'])->name('showTrustitService');
    Route::get('/admin/p17-services',[CompanyController::class,'showDirepayService'])->name('showDirepayService');
    Route::get('/admin/p18-services',[CompanyController::class,'showKeynexPayService'])->name('showKeynexPayService');
    Route::get('/admin/p19-services',[CompanyController::class,'showValensPayService'])->name('showValensPayService');
    Route::get('/admin/p20-services',[CompanyController::class,'showYaspaService'])->name('showYaspaService');
    Route::get('/admin/p21-services',[CompanyController::class,'showAlikassaService'])->name('showAlikassaService');
    Route::get('/admin/p22-services',[CompanyController::class,'showUniqoPayService'])->name('showUniqoPayService');
    
    Route::get('/admin/p23-services',[CompanyController::class,'showUpipayService'])->name('showUpipayService');
    Route::post('/admin/p23-services/edit',[CompanyController::class,'editUpipayCompanyDetails'])->name('edit-p23service');
    
    Route::get('/admin/p23-services/merchants',[CompanyController::class,'showUpipayMerchants'])->name('showUpipayMerchants');
    Route::post('/admin/p23-services/merchants/add',[CompanyController::class,'addUpipayMerchant'])->name('add-p23merchant');
    Route::post('/admin/p23-services/merchants/import',[CompanyController::class,'importMerchants'])->name('import-p23merchants');
    Route::post('/admin/p23-services/merchants/edit',[CompanyController::class,'editUpipayMerchantDetails'])->name('edit-p23merchant');
    Route::get('/admin/p23-services/merchants/delete/{id}',[CompanyController::class,'deleteUpipayMerchant'])->name('delete-p23merchant');
    Route::post('/admin/p23-services/merchants/delete-all',[CompanyController::class,'deleteAllUpipayMerchants'])->name('deleteAll-p23merchants');
    Route::get('/admin/p23-merchants/generate-link/{merchant_id}',[CompanyController::class,'generateUpipayMerchantLink'])->name('generate-p23merchant-link');
    
    Route::get('/admin/all-transactions',[CompanyController::class,'showAllTransactions'])->name('showAllTransactions');
    Route::get('/admin/all-declined-transactions',[CompanyController::class,'showAllFailedTransactions'])->name('showAllFailedTransactions');

    Route::get('/admin/settlements',[SettlementController::class,'showSettlements'])->name('showSettlements');
    Route::post('/admin/settlements/add',[SettlementController::class,'addSettlement'])->name('addSettlement');
});

//client side dashbard pages
Route::group(['prefix' => '/user', 'middleware' => ['auth','check.client.status']], function() {
    Route::get('/home', [ClientHomeController::class, 'showHome'])->name('showHome');
    Route::get('/updated-amount-value', [ClientHomeController::class, 'getUpdatedAmountValue'])->name('updated-amount-value');
    Route::get('/home/updated-chart-data/{currency}', [ClientHomeController::class, 'getUpdatedChartData'])->name('updated-chart-data');
    Route::get('/download-p3-h2h-documentation',[ClientHomeController::class,'downloadP3H2HDoc'])->name('downloadP3H2HDoc');
    Route::get('/download-p3-pay-by-link-doc',[ClientHomeController::class,'downloadP3PayByLinkDoc'])->name('downloadP3PayByLinkDoc');
    Route::get('/download-p4-h2h-documentation',[ClientHomeController::class,'downloadP4H2HDoc'])->name('downloadP4H2HDoc');
    Route::get('/download-p7-h2h-documentation',[ClientHomeController::class,'downloadP7H2HDoc'])->name('downloadP7H2HDoc');
    Route::get('/download-p8-h2h-documentation',[ClientHomeController::class,'downloadP8H2HDoc'])->name('downloadP8H2HDoc');
    Route::get('/download-p11-h2h-documentation',[ClientHomeController::class,'downloadP11H2HDoc'])->name('downloadP11H2HDoc');
    Route::get('/download-p12-h2h-documentation',[ClientHomeController::class,'downloadP12H2HDoc'])->name('downloadP12H2HDoc');
    Route::get('/download-p13-h2h-documentation',[ClientHomeController::class,'downloadP13H2HDoc'])->name('downloadP13H2HDoc');

    Route::get('/developer-area',[ClientHomeController::class,'developerArea'])->name('showDevelopersArea');

    Route::get('/transactions', [ClientTransactionsController::class,'showTransactions'])->name('showTransactions');
    Route::get('/transactions-download', [ClientTransactionsController::class,'downloadTransactions'])->name('transactions.download');
    Route::get('/failed-transactions', [ClientTransactionsController::class,'failedTransactions'])->name('showFailed-Transactions');

    Route::get('/documentations', [ClientDocumentsController::class, 'showDocumentations'])->name('showDocumentations');
    Route::get('/documentations/p1-service', [ClientDocumentsController::class,'p1DocPage'])->name('p1DocPage');
    Route::get('/documentations/p2-service', [ClientDocumentsController::class,'p2DocPage'])->name('p2DocPage');
    Route::get('/documentations/p3-service', [ClientDocumentsController::class,'p3DocPage'])->name('p3DocPage');
    Route::get('/documentations/p4-service', [ClientDocumentsController::class,'p4DocPage'])->name('p4DocPage');
    Route::get('/documentations/p5-service', [ClientDocumentsController::class,'p5DocPage'])->name('p5DocPage');
    Route::get('/documentations/p6-service', [ClientDocumentsController::class,'p6DocPage'])->name('p6DocPage');
    Route::get('/documentations/p7-service', [ClientDocumentsController::class,'p7DocPage'])->name('p7DocPage');
    Route::get('/documentations/p8-service', [ClientDocumentsController::class,'p8DocPage'])->name('p8DocPage');
    Route::get('/documentations/p8-subscription', [ClientDocumentsController::class,'p8SubscriptionDocPage'])->name('p8SubscriptionDocPage');
    Route::get('/documentations/p9-service', [ClientDocumentsController::class,'p9DocPage'])->name('p9DocPage');
    Route::get('/documentations/p10-service', [ClientDocumentsController::class,'p10DocPage'])->name('p10DocPage');
    Route::get('/documentations/p10-purchase-service', [ClientDocumentsController::class,'p10PurchaseDocPage'])->name('p10PurchaseDocPage');
    Route::get('/documentations/p14-service', [ClientDocumentsController::class,'p14DocPage'])->name('p14DocPage');
    Route::get('/documentations/p15-service', [ClientDocumentsController::class,'p15DocPage'])->name('p15DocPage');
    Route::get('/documentations/p16-service', [ClientDocumentsController::class,'p16DocPage'])->name('p16DocPage');
    Route::get('/documentations/p17-service', [ClientDocumentsController::class,'p17DocPage'])->name('p17DocPage');
    Route::get('/documentations/p18-service', [ClientDocumentsController::class,'p18DocPage'])->name('p18DocPage');
    Route::get('/documentations/p19-service', [ClientDocumentsController::class,'p19DocPage'])->name('p19DocPage');
    Route::get('/documentations/p20-payin-service', [ClientDocumentsController::class,'p20DocPage'])->name('p20DocPage');
    Route::get('/documentations/p20-payout-service', [ClientDocumentsController::class,'p20PayoutDocPage'])->name('p20PayoutDocPage');
    Route::get('/documentations/p21-service', [ClientDocumentsController::class,'p21DocPage'])->name('p21DocPage');
    Route::get('/documentations/p22-service', [ClientDocumentsController::class,'p22DocPage'])->name('p22DocPage');
    Route::get('/documentations/p23-payin-service', [ClientDocumentsController::class,'p23DocPage'])->name('p23DocPage');
    Route::get('/documentations/p23-payout-service', [ClientDocumentsController::class,'p23PayoutDocPage'])->name('p23PayoutDocPage');
    Route::get('/documentations/p23-payin-v2-service', [ClientDocumentsController::class,'p23v2DocPage'])->name('p23v2DocPage');

});

//paystrax payment page
Route::middleware(['check.allowedsites.for.paystrax.pp'])->group(function () {
    Route::get('/payment/p1/payment-page/{checkout_id}', [PaymentPageController::class, 'showPaystraxPaymentPage'])->name('showPaystraxPaymentPage');
});

//paybis payment page
Route::middleware(['check.allowedsites.for.paybis.pp'])->group(function () {
    Route::get('/payment/p2/payment-page/{checkout_id}', [PaymentPageController::class, 'showPaybisPaymentPage'])->name('showPaybisPaymentPage');
});

//X1 Payment Page
Route::middleware(['check.allowedsites.for.x1.pp'])->group(function () {
    Route::get('/payment/p3/payment-page/{checkout_id}', [PaymentPageController::class, 'showX1PaymentPage'])->name('showX1PaymentPage');
});

//StradaPay Payment Page
Route::middleware(['check.allowedsites.for.stradapay.pp'])->group(function () {
    Route::get('/payment/p4/payment-page/{checkout_id}', [PaymentPageController::class, 'showStradapayPaymentPage'])->name('showStradapayPaymentPage');
});

//Ryvyl Payment Page
Route::middleware(['check.allowedsites.for.paystrax.pp'])->group(function () {
    Route::get('/payment/p5/payment-page/{checkout_id}', [RyvylController::class, 'showRyvylPaymentPage'])->name('showRyvylPaymentPage');
});

// upi retry payment
Route::get('/p23/payment/{checkout_id}/retry', [UpiPaymentController::class, 'retryCheckout'])
    ->name('p23.payment.retry');
Route::get('/p23/payment/{checkout_id}/retry/v2', [UpiV2Controller::class, 'retryCheckout'])
    ->name('p23.payment.retry.v2');

//SecurePayZone Payment Page
Route::get('/payment/p7/payment-page/{checkout_id}',[SecurePayZoneController::class,'viewPaymentPage']);
Route::post('/p7/process-payment/{checkout_id}',[SecurePayZoneController::class,'makeSZPayment'])->name('makeSZPayment');   //submiting Secure Pay Zone form from payment page

//Luqapay Payment Page
Route::get('/payment/p8/payment-page/{checkout_id}',[LuqapayController::class,'viewPaymentPage'])->name('viewLuqapayPaymentPage');
Route::post('/p8/process-payment/{checkout_id}',[LuqapayController::class,'makeLuqapayPayment'])->name('makeLuqapayPayment');   //submiting Luqapay form from payment page
Route::get('/payment/p8/thank-you-page/{checkout_id}',[LuqapayController::class,'viewThankYouPage'])->name('viewLuqapayThankYouPage');

Route::get('/payment/p1/payment-status/{checkout_id}', [PaymentPageController::class, 'paystraxPaymentResult'])->name('paystraxPaymentResult'); //response from Paystrax
Route::get('/payment/p5/payment-status/{checkout_id}', [RyvylController::class, 'ryvylPaymentResult'])->name('ryvylPaymentResult'); //response from Ryvyl

Route::get('/error-payment-page', function () { return view('error-page'); }); //error page

Route::get('/p1/create-payment-link',[PaystraxController::class, 'showPaystraxPaymentCreater']);      //show create open Paystrax Payment Link Page
Route::post('/p1/generate-payment-link',[PaystraxController::class,'generatePaystraxPayment'])->name('paymentLinkPaystrax'); //Process  open Paystrax Payment Link Page
Route::get('/p1/payment-link/{checkout_id}', [PaystraxController::class, 'showPaystraxPaymentLink'])->name('showPaystraxPaymentLink'); //open Paystrax Payment Link Page

Route::get('/p3/create-payment-link',[TransactionController::class, 'showX1PaymentCreater']);      //show create open X1 Payment Link Page
Route::post('/p3/generate-payment-link',[TransactionController::class,'generateX1Payment'])->name('paymentLinkX1'); //Process  open X1 Payment Link Page
Route::get('/p3/payment-link/{checkout_id}', [TransactionController::class, 'showX1PaymentLink'])->name('showX1PaymentLink'); //open X1 Payment Link Page

Route::post('/p4/process-payment/{checkout_id}',[StradaPayController::class,'processStradaPay'])->name('processStradaPay');     //process stradapay payment-forms
Route::get('/payment/p4/returnurl-response', [StradaPayController::class, 'handleReturnUrl'])->name('p4.return.url');   //handles StradaPay Return URL response
Route::get('/p4/thank-you-page/{checkout_id}',[StradaPayController::class,'showThankYouPage'])->name('p4.thank.you.page');

Route::post('/p6/notification',[TransvoucherController::class, 'webhookNotification']);
Route::get('/p6/thank-you-page/{checkout_id}',[TransvoucherController::class,'thankYouPage']);
Route::get('/p6/{accId}/dynamic-payment-link',[TransvoucherController::class,'showPaymentLinkGeneratorPage']);
Route::get('/p6/update/{trxn_id}/transaction',[TransvoucherController::class,'updateP6TrxnStatus']);
Route::get('/p10/update/{trxn_id}/transaction',[InabitController::class,'updateP10TrxnStatus']);
Route::get('/p17/update/{trxn_id}/transaction',[DirepayController::class,'updateP17TrxnStatus']);
Route::get('/p18/update/{trxn_id}/transaction',[KeynexPayController::class,'updateP18TrxnStatus']);
Route::get('/p23/update/{checkout_id}/transaction',[UpiPaymentController::class,'updateP23TrxnStatus']);

Route::post('/p8/notification',[LuqapayController::class, 'webhookNotification']);

Route::get('/payment/p10/payment-page/{token}', [InabitController::class, 'showPaymentPage']);
Route::get('/payment/p10/purchase/payment-page/{token}', [InabitController::class, 'showPurchasePaymentPage']);

// upi payment
Route::get('/p23/payment/{checkout_id}', [UpiPaymentController::class, 'paymentPage'])->name('p23.payment.page');
Route::get('/p23/payment/v2/{checkout_id}', [UpiV2Controller::class, 'paymentPage'])->name('p23.payment.page-v2');
Route::get('/p23/payment-status/{checkout_id}', [UpiPaymentController::class, 'getPayinStatus'])->name('p23.payment.status');
Route::get('/p23/payment-status/v2/{checkout_id}', [UpiV2Controller::class, 'getPayinStatus'])->name('p23.payment.status.v2');
Route::get('/p23/payment-expired/{checkout_id}', [UpiPaymentController::class, 'markPayinExpired'])->name('p23.payment.expired');


Route::get('/inabit-health', function () {
    $response = Illuminate\Support\Facades\Http::get(config('services.inabit.base_url') . '/health');

    return $response; // or dd($response->json());
});

Route::get('/inabit-widget', [InabitDebugController::class, 'showWidgetPage'])->name('inabit.widget');

Route::get('/checking-test-page' , function() {
    return view('useless');
});

Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:cache');
    return 'Configuration, cache, and routes cleared.';
});

Route::post('/create-ryvyl-PAYPAL-CONTINUE-checkout',[FineraController::class,'paypalContinue']);
require __DIR__.'/auth.php';
