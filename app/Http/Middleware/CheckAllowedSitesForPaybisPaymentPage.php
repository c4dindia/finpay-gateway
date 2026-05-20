<?php

namespace App\Http\Middleware;

use App\Models\PTwoPaymentMethod;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAllowedSitesForPaybisPaymentPage
{
    /**
     * List of allowed domains/sites.
     *
     * @var array
     */
    protected $allowedSites = [
        'https://careperspectivesservices.co.uk/',
        'https://payment.ryzen-pay.com/',
        'http://127.0.0.1:8000/',
        '*',
        '',
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
        // You can also use 'Origin' header if you prefer

        $dynamicUrls = PTwoPaymentMethod::all('redirect_url')->pluck('redirect_url')->toArray();
        $this->allowedSites = array_merge($this->allowedSites, $dynamicUrls);

        if (!in_array($referer, $this->allowedSites)) {
            Log::info('allowed Paybis urls: ',$this->allowedSites);
            return response()->json(['error' => 'Unauthorized in Middleware'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
