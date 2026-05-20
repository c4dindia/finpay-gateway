<?php

namespace App\Http\Middleware;

use App\Models\PFourPaymentMethod;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAllowedSitesForStradaPayPaymentPage
{
    /**
     * List of allowed domains/sites.
     *
     * @var array
     */
    protected $allowedSites = [
        '*',
        '',
        'http://127.0.0.1:8000'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function handle(Request $request, Closure $next): Response
    {
        $referer = $request->headers->get('referer');
        Log::info('from: '.$referer);

        $dynamicUrls = PFourPaymentMethod::all('redirect_url')->pluck('redirect_url')->toArray();
        $this->allowedSites = array_merge($this->allowedSites, $dynamicUrls);

        if (!in_array($referer, $this->allowedSites)) {
            Log::info('allowed Stradapay urls: ',$this->allowedSites);
            return response()->json(['error' => 'Unauthorized in Middleware'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
