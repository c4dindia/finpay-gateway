<?php

use App\Http\Controllers\AlikassaController;
use App\Http\Controllers\Aliz7SaleController;
use App\Http\Controllers\Api\Client\DashboardController as DashboardApiController;
use App\Http\Controllers\Api\Client\DeveleopersAreaController as DevelopersAreaApiController;
use App\Http\Controllers\Api\Client\DocumentsController as DocumentsApiController;
use App\Http\Controllers\Api\Client\TransactionsController as TransactionApiController;
use App\Http\Controllers\Api\LoginApiController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\DirepayController;
use App\Http\Controllers\FineraController;
use App\Http\Controllers\Inabit\InabitOrganizationController;
use App\Http\Controllers\Inabit\InabitDebugController;
use App\Http\Controllers\Inabit\InabitMetaController;
use App\Http\Controllers\Inabit\InabitWebhookController;
use App\Http\Controllers\InabitController;
use App\Http\Controllers\KeynexPayController;
use App\Http\Controllers\LuqapayController;
use App\Http\Controllers\LuqaPaySubscriptionController;
use App\Http\Controllers\PaymentPageController;
use App\Http\Controllers\PaytoroController;
use App\Http\Controllers\PGTechPayController;
use App\Http\Controllers\RyvylController;
use App\Http\Controllers\SecurePayZoneController;
use App\Http\Controllers\StradaPayController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransvoucherController;
use App\Http\Controllers\TrigoPaymentsController;
use App\Http\Controllers\X1Controller;
use App\Http\Controllers\NiobiController;
use App\Http\Controllers\SmilePayController;
use App\Http\Controllers\TrustitController;
use App\Http\Controllers\ValensPayController;
use App\Http\Controllers\YaspaController;
use App\Http\Controllers\UniqoPayController;
use App\Http\Controllers\UpiPaymentController;
use App\Http\Controllers\UpiV2Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Mobile API
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->middleware('guest:sanctum')->group(function () {
        Route::post('/login', [LoginApiController::class, 'createToken']);
    });

    Route::middleware('auth:sanctum')->get('me', function (Request $request) {
        return $request->user();
    });

    Route::prefix('client')->middleware(['auth:sanctum'])->group(function () {

        Route::get('/dashboard', [DashboardApiController::class, 'dashboard']);
        Route::get('/dashboard/currency/totals', [DashboardApiController::class, 'dashboardCurrencyTotals']);
        Route::get('/dashboard/transactions',[DashboardApiController::class,'dashboardTransactionsData']);
        Route::get('/dashboard/chart-data',[DashboardApiController::class,'dashboardChartData']);

        Route::get('/developers-area',[DevelopersAreaApiController::class,'getDelevelopersCredentials']);

        Route::get('/documents/download-p3-h2h-documentation', [DocumentsApiController::class,'downloadP3H2HDoc']);
        Route::get('/documents/download-p3-pay-by-link-doc',   [DocumentsApiController::class,'downloadP3PayByLinkDoc']);
        Route::get('/documents/download-p4-h2h-documentation', [DocumentsApiController::class,'downloadP4H2HDoc']);
        Route::get('/documents/download-p7-h2h-documentation', [DocumentsApiController::class,'downloadP7H2HDoc']);
        Route::get('/documents/download-p8-h2h-documentation', [DocumentsApiController::class,'downloadP8H2HDoc']);
        Route::get('/documents/download-p11-h2h-documentation',[DocumentsApiController::class,'downloadP11H2HDoc']);
        Route::get('/documents/download-p12-h2h-documentation',[DocumentsApiController::class,'downloadP12H2HDoc']);
        Route::get('/documents/download-p13-h2h-documentation',[DocumentsApiController::class,'downloadP13H2HDoc']);

        Route::get('/transactions',[TransactionApiController::class, 'getTransactions']);
        Route::post('/transactions/download',[TransactionApiController::class, 'downloadTransaction']);
        Route::get('/transactions/{checkout_id}',[TransactionApiController::class, 'getSpecificTransaction']);
        Route::get('/failed-transactions',[TransactionApiController::class, 'getFailedTransactions']);

        Route::delete('/logout',[LoginAPiController::class,'logout']);
    });
});

Route::post('/p7/get-qr-code',[SecurePayZoneController::class,'generateQRcode']);
Route::get('/p7/get-status/{checkout_id}',[SecurePayZoneController::class,'getStatus']);

Route::middleware(['bearer.token'])->group(function () {

    Route::post('/payment/{accId}/p1/checkout', [PaymentPageController::class, 'paystraxCheckoutDetail']);  //paystrax checkout create
    Route::get('/payment/{accId}/p1/getPaymentStatus/{checkout_id}', [PaymentPageController::class, 'paystraxGetPaymentStatus']); //paystrax payment status

    Route::post('/payment/{accId}/p2/checkout', [PaymentPageController::class, 'getCryptoPaymentRId']);     //paybis Checkout Create
    Route::get('/payment/{accId}/p2/getPaymentStatus/{checkout_id}', [PaymentPageController::class, 'paybisGetPaymentStatus']); //paybis payment status

    Route::post('/payment/{accId}/p3/checkout', [X1Controller::class, 'getX1CheckoutDetail']);    //X1 Checkout Create
    Route::get('/payment/{accId}/p3/getPaymentStatus/{checkout_id}', [X1Controller::class, 'x1GetPaymentStatus']); //X1 payment status

    Route::post('/payment/{accId}/p3/host-to-host', [X1Controller::class, 'getX1HostToHost']);    //X1 Host to Host

    Route::post('/payment/{accId}/p4/checkout', [StradaPayController::class, 'getStradaPayCheckoutDetail']);    //StradaPay Checkout Create
    Route::get('/payment/{accId}/p4/getPaymentStatus/{checkout_id}', [StradaPayController::class, 'stradapayGetPaymentStatus']); //StradaPay payment status

    Route::post('/payment/{accId}/p4/host-to-host', [StradaPayController::class, 'getStradaPayHostToHost']);    //Stradapay Host to Host

    Route::post('/payment/{accId}/p5/checkout', [RyvylController::class, 'ryvylCheckoutDetail']);  //ryvyl checkout create
    Route::get('/payment/{accId}/p5/getPaymentStatus/{checkout_id}', [RyvylController::class, 'ryvylGetPaymentStatus']); //ryvyl payment status

    Route::post('/payment/{accId}/p6/checkout',[TransvoucherController::class,'transvoucherCheckoutDetail']);
    Route::get('/payment/{accId}/p6/getPaymentStatus/{checkout_id}', [TransvoucherController::class, 'transvoucherGetPaymentStatus']);

    Route::post('/payment/{accId}/p7/checkout',[SecurePayZoneController::class,'createCheckout']);
    Route::post('/payment/{accId}/p7/host-to-host', [SecurePayZoneController::class, 'h2hSecurePayZone']);    //SecurePay Zone Host to Host
    Route::get('/payment/{accId}/p7/getPaymentStatus/{checkout_id}', [SecurePayZoneController::class, 'getTransactionStatus']);

    Route::post('/payment/{accId}/p8/checkout',[LuqapayController::class,'createCheckout']);
    Route::post('/payment/{accId}/p8/host-to-host', [LuqapayController::class, 'h2hLuqapay']);    //Luqapay Host to Host
    Route::get('/payment/{accId}/p8/getPaymentStatus/{checkout_id}', [LuqapayController::class, 'getTransactionStatus']);

    Route::post('/payment/{accId}/p8/subscription/verify-card', [LuqaPaySubscriptionController::class, 'verifyCard']);
    Route::post('/payment/{accId}/p8/subscription/store-card',  [LuqaPaySubscriptionController::class, 'storeCard']);
    Route::post('/payment/{accId}/p8/subscription/charge',      [LuqaPaySubscriptionController::class, 'chargeStoredCard']);
    Route::post('/payment/{accId}/p8/subscription/pay',      [LuqaPaySubscriptionController::class, 'storeCardAndCharge']);

    Route::post('/payment/{accId}/p9/checkout', [TrigoPaymentsController::class, 's2hTrigoPay']);    //TrigoPay Server to Host
    Route::post('/payment/{accId}/p9/subscription', [TrigoPaymentsController::class, 's2hTrigoPaySubscription']);
    Route::get('/payment/{accId}/p9/getPaymentStatus/{checkout_id}', [TrigoPaymentsController::class, 'getTransactionStatus']);

    // Route::post('/payment/{accId}/p10/register-customer',[InabitController::class,'registerCustomer']);
    Route::post('/payment/{accId}/p10/checkout',[InabitController::class,'createCheckout']);    //Inabit Customer
    Route::post('/payment/{accId}/p10/purchase/checkout',[InabitController::class,'createPurchaseCheckout']);  //Inabit Purchase
    Route::get('/payment/{accId}/p10/getPaymentStatus/{checkout_id}', [InabitController::class, 'getTransactionStatus']);
    Route::get('/payment/{accId}/p10/purchase/getPaymentStatus/{checkout_id}', [InabitController::class, 'getPurchaseTransactionStatus']);

    Route::post('/payment/{accId}/p11/host-to-host', [PaytoroController::class, 'h2hPaytoro']);    //h2h Paytoro
    Route::get('/payment/{accId}/p11/getPaymentStatus/{checkout_id}', [PaytoroController::class, 'getTransactionStatus']);

    Route::post('/payment/{accId}/p12/host-to-host', [PGTechPayController::class, 'h2hPgTechPay']);    //h2h PgTechPay
    Route::post('/payment/{accId}/p12/host-to-host/test', [PGTechPayController::class, 'h2hPgTechPayTest']);
    Route::get('/payment/{accId}/p12/getPaymentStatus/{checkout_id}', [PGTechPayController::class, 'getTransactionStatus']);

    Route::post('/payment/{accId}/p13/host-to-host', [Aliz7SaleController::class, 'h2hCheckout']);    //h2h Aliz7
    Route::get('/payment/{accId}/p13/getPaymentStatus/{checkout_id}', [Aliz7SaleController::class, 'getTransactionStatus']);

    Route::post('/payment/{accId}/p14/checkout', [NiobiController::class, 'createCheckout']);   //Niobi pay-by-link checkout
    Route::get('/payment/{accId}/p14/getPaymentStatus/{checkout_id}', [NiobiController::class, 'getTransactionStatus']); // niobi payment status

    Route::post('/payment/{accId}/p15/checkout', [SmilePayController::class, 'createCheckout']);   //Smile pay-by-link checkout
    Route::get('/payment/{accId}/p15/getPaymentStatus/{checkout_id}', [SmilePayController::class, 'getTransactionStatus']); // Smile payment status

    Route::post('/payment/{accId}/p16/checkout', [TrustitController::class, 'createCheckout']);   //Trustit pay-by-link checkout
    Route::get('/payment/{accId}/p16/getPaymentStatus/{checkout_id}', [TrustitController::class, 'getTransactionStatus']); // Trustit payment status

    Route::post('/payment/{accId}/p17/checkout', [DirepayController::class, 'createCheckout']);   //Dire Pay pay-by-link checkout
    Route::get('/payment/{accId}/p17/getPaymentStatus/{checkout_id}', [DirepayController::class, 'getTransactionStatus']); // Dire Pay payment status
    Route::post('/payment/{accId}/p17/create-payout',[DirepayController::class,'createWithdrawal']); //Dire Pay withdrawal/ Payout

    Route::post('/payment/{accId}/p18/checkout', [KeynexPayController::class, 'createCheckout']);   //Keynex Pay pay-by-link checkout
    Route::get('/payment/{accId}/p18/getPaymentStatus/{checkout_id}', [KeynexPayController::class, 'getTransactionStatus']); // Keynex Pay payment status

    Route::post('/payment/{accId}/p19/create-customer',[ValensPayController::class, 'create']);             //Valens Pay create customer
    Route::post('/payment/{accId}/p19/checkout/{customerId}', [ValensPayController::class, 'requestTransfer']);          //Valens Pay pay-by-link checkout
    Route::get('/payment/{accId}/p19/getPaymentStatus/{customerId}/{payment_id}', [ValensPayController::class, 'getTransactionStatus']); //Valens Pay payment status

    Route::post('/payment/{accId}/p20/checkout', [YaspaController::class, 'createCheckout']);   //Yaspa pay-by-link checkout
    Route::post('/payout/{accId}/p20/checkout', [YaspaController::class, 'createPayout']);   //Yaspa payout
    Route::get('/payment/{accId}/p20/getPaymentStatus/{checkout_id}', [YaspaController::class, 'getTransactionStatus']); // Yaspa payment status

    Route::post('/payment/{accId}/p21/checkout', [AlikassaController::class, 'createCheckout']);   //Alikassa pay-by-link checkout
    Route::get('/payment/{accId}/p21/getPaymentStatus/{checkout_id}', [AlikassaController::class, 'getTransactionStatus']); // Alikassa payment status
    
    Route::post('/payment/{accId}/p22/checkout', [UniqoPayController::class, 'createCheckout']);   //UniqoPay pay-by-link checkout
    Route::get('/payment/{accId}/p22/getPaymentStatus/{checkout_id}', [UniqoPayController::class, 'getTransactionStatus']); // UniqoPay payment status
    
    Route::post('/payment/{accId}/p23/checkout', [UpiPaymentController::class, 'createCheckout']);    //Upi Host to Host
    Route::post('/payout/{accId}/p23/checkout', [UpiPaymentController::class, 'createPayout']);    //Upi Payout
    Route::get('/payment/{accId}/p23/getPaymentStatus/{checkout_id}', [UpiPaymentController::class, 'getTransactionStatus']); //Upi payment status
    Route::post('/payment/{accId}/p23/v2/checkout', [UpiV2Controller::class, 'createCheckoutV2']);    //Upi Payin Checkout V2
    Route::get('/payment/{accId}/p23/v2/getPaymentStatus/{checkout_id}', [UpiV2Controller::class, 'getTransactionStatus']); //Upi v2 payment status

    Route::post('/p3/generate-payment-link', [TransactionController::class, 'generateX1PaymentResponse']); //X1 Generate Payment Link
});

Route::post('/paybis/webhook', [PaymentPageController::class, 'handleWebhook']);    //Paybis Notification Handle
Route::post('/payment/p3/notification', [X1Controller::class, 'handleNotification']);   //X1 Notification Handle
Route::post('/payment/p4/notification', [StradaPayController::class, 'handleCallback']);   //StradaPay Notification Handle

Route::post('/payment/p7/notification', [SecurePayZoneController::class, 'handlePaymentNotification']); //Secure Pay Zone Notification Handle

Route::post('/p8/purchase/notify',[LuqaPaySubscriptionController::class, 'ipn']); // IPN Luqapay Suscritpion callback URL you give to LuqaPay (Centrue):

Route::match(['get','post'], '/p9/notification', [TrigoPaymentsController::class, 'webhook'])->name('trigopayNotification'); //P9 TrigoPayments Notification
Route::match(['get','post'],'/p10/notification', [InabitWebhookController::class, 'handleNotification']); //Inabit Notification Handle
Route::match(['get','post'],'/p10/purchase/notification', [InabitWebhookController::class, 'handleNotificationPurchaseWidget']); //Inabit Purchase Widget Notification Handle
Route::match(['get','post'],'/p11/notification', [PaytoroController::class, 'handleNotification']); //Paytoro Notification Handle
Route::post('/p12/notification', [PGTechPayController::class, 'handleNotification'])->name('pgtechpayNotification'); //Paytoro Notification Handle
Route::post('/p13/notification', [Aliz7SaleController::class, 'handleWebhook'])->name('aliz7Webhook'); //Aliz7 Notification Handle
Route::match(['get','post'],'/p14/notification', [NiobiController::class, 'handleNotification']); //Niobi Notification Handle
Route::match(['get','post'],'/p15/notification', [SmilePayController::class, 'handleNotification']); //SmilePay Notification Handle
Route::match(['get','post'],'/p16/notification', [TrustitController::class, 'handleNotification']); //Trustit Notification Handle
Route::match(['get','post'],'/p17/notification', [DirepayController::class, 'handleNotification']); //DirePay Notification Handle
Route::match(['get','post'],'/p18/notification', [KeynexPayController::class, 'handleNotification']); //Keynex Pay Notification Handle
Route::match(['get','post'],'/p19/notification', [ValensPayController::class, 'handleNotification']); //Valens Pay Notification Handle
Route::match(['get','post'],'/p20/notification', [YaspaController::class, 'handleNotification']); //Yaspa Notification Handle
Route::match(['get','post'],'/p21/notification', [AlikassaController::class, 'handleNotification']); //Alikassa Notification Handle
Route::match(['get','post'],'/p22/notification', [UniqoPayController::class, 'handleNotification']); //UniqoPay Notification Handle

Route::match(['get','post'],'/p23/payin/notification', [UpiPaymentController::class, 'payinNotification']); //Upi Payin Notification Handle
Route::match(['get','post'],'/p23/payout/notification', [UpiPaymentController::class, 'payoutNotification']); //Upi Payout Notification Handle


Route::get('/transvoucher/update/{status}/transactions',[TransvoucherController::class,'updatePaymentStatus']);
Route::get('/luqapay/update/all-pending-transactions',[LuqapayController::class,'updatePaymentStatus']);


Route::get('/p6/{accId}/openPaymentLinkGenrator',[TransvoucherController::class,'openPaymentLink']);

Route::get('/test',[TrigoPaymentsController::class,'test2']);

Route::get('/finera', [FineraController::class, 'handle']);

// mobile Apis
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/home', [LoginController::class, 'home']);
    Route::post('/filter-home-currency', [LoginController::class, 'filterHomeCurrency']);

    Route::get('/transactions', [LoginController::class, 'transactions']);
    Route::post('/filter-transactions', [LoginController::class, 'filterTransactions']);

    Route::get('/failed-transactions', [LoginController::class, 'failedTransactions']);
    Route::post('/filter-failed-transactions', [LoginController::class, 'filterFailedTransactions']);
    Route::post('/logout', [LoginController::class, 'logout']);
});


Route::get('/inabit/wallets', [InabitDebugController::class, 'wallets']); //http://127.0.0.1:8000/api/inabit/wallets
Route::post('/inabit/api-wallets', [InabitDebugController::class, 'createApiWallet']);  //dont use

Route::get('/inabit/organizations', [InabitOrganizationController::class, 'index']);

Route::get('/inabit/asset', [InabitMetaController::class, 'financialAsset']);
Route::get('/inabit/blockchain/code', [InabitMetaController::class, 'blockchainByCode']);
Route::get('/inabit/blockchain/name', [InabitMetaController::class, 'blockchainByName']);

Route::post('/inabit/register/customer-email',[InabitDebugController::class,'registerCustomerUuidByEmail']);
Route::post('/inabit/customer/retrieve-address-token',[InabitDebugController::class,'generateAddressToken']);
Route::get('/inabit/transaction/{transactionId}',[InabitDebugController::class,'getInabitTransaction']);

//for non-existing API routes
Route::any('{any}', function () {
    return response()->json([
        'status' => false,
        'message' => 'Resource not found check in api Url '
    ], 404);
})->where('any', '.*');
