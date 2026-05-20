<?php

namespace App\Http\Middleware;

use App\Models\PThreePaymentMethod;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAllowedSitesForX1PaymentPage
{
    /**
     * List of allowed domains/sites.
     *
     * @var array
     */
    protected $allowedSites = [
        'https://careperspectivesservices.co.uk/',
        'https://payment.ryzen-pay.com/',
        'https://tech.careperspectivesservices.co.uk/',

        'https://howtopay.com/',
        'https://payments.howtopay.com/',
        'https://payments.howtopay.com/gateway/',

        '*',
        '',
        '/',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $referer = $request->headers->get('referer');
        Log::info('from: '.$referer);

        $dynamicUrls = PThreePaymentMethod::all('redirect_url')->pluck('redirect_url')->toArray();
        $this->allowedSites = array_merge($this->allowedSites, $dynamicUrls);

        if (!in_array($referer, $this->allowedSites)) {
            Log::info('allowed X1 urls: ',$this->allowedSites);
            return response()->json(['error' => 'Unauthorized in Middleware'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
