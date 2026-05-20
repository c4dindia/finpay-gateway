<?php

namespace App\Http\Middleware;

use App\Models\Direpay;
use App\Models\PaytoroPayment;
use App\Models\PEightPaymentMethod;
use App\Models\PFivePaymentMethod;
use App\Models\PFourPaymentMethod;
use App\Models\PNinePaymentMethod;
use App\Models\POnePaymentMethod;
use App\Models\PSevenPaymentMethod;
use App\Models\PSixPaymentMethod;
use App\Models\PTenPaymentMethod;
use App\Models\PThirteenPaymentMethod;
use App\Models\NiobiPayment;
use App\Models\PEighteenPaymentMethod;
use App\Models\PThreePaymentMethod;
use App\Models\PTwelvePaymentMethod;
use App\Models\PTwoPaymentMethod;
use App\Models\SmilePay;
use App\Models\TrustitBanking;
use App\Models\ValensPay;
use App\Models\YaspaBanking;
use App\Models\UniqoPay;
use App\Models\UPIPayment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BearerTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $referer = $request->headers->get('referer');
        Log::info('API Request from: '.$referer);

        // Check if the Authorization header is present
        $authorizationHeader = $request->header('Authorization');

        if (is_null($authorizationHeader)) {
            return response()->json(['error' => 'Authorization header missing'], 401);
        }

        // Check if the header starts with 'Bearer '
        if (strpos($authorizationHeader, 'Bearer ') !== 0) {
            return response()->json(['error' => 'Invalid token format'], 401);
        }

        $token = $authorizationHeader;

        if (!$this->isValidToken($token)) {
            return response()->json(['error' => 'Unauthorized Token Used'], 401);
        }

        return $next($request);
    }

    /**
     * Validate the token.
     *
     * @param string $token
     * @return bool
     */
    protected function isValidToken($token)
    {
        if(POnePaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PTwoPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PThreePaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PFourPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PFivePaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PSixPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PSevenPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PEightPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PNinePaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PTenPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PaytoroPayment::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PTwelvePaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PThirteenPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(NiobiPayment::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(SmilePay::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(TrustitBanking::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(Direpay::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(PEighteenPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(ValensPay::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        if(YaspaBanking::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }
        
        if(UniqoPay::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }
        
        if(UPIPayment::where('b_token',$token)->where('status','1')->exists()){
            return true;
        }

        return false;
    }
}
