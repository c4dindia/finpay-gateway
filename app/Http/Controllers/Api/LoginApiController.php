<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginApiController extends Controller
{
    public function createToken(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)
            ->where('status', '1')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Incorrect credentials.'
            ], 403);
        }

        $company = Company::where("user_id",$user->id)->first();
        if(!$company || $company->status == '0'){
            return response()->json([
                'message' => 'Company Account is Deactivated'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'token'   => $user->createToken($request->email, ['*'])->plainTextToken,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.'
            ],200);
    }
}
