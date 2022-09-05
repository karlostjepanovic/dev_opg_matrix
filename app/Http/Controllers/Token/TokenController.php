<?php

namespace App\Http\Controllers\Token;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function generate()
    {
        $ga = new GoogleAuthenticator();
        $code = $ga->createSecret();
        $qr = $ga->getQRCodeGoogleUrl('OPGmatrix', $code);
        return response()->json([
            'qr' => $qr,
            'code' => $code,
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'code'  => 'required',
            'otp'   => 'required',
        ]);
        $ga = new GoogleAuthenticator();
        $result = $ga->verifyCode($request->code, $request->otp, 3);
        if($result){
            return $result;
        }else{
            return response()->json([
                'message' => 'Jednokratna lozinka je neispravna.'
            ], 422);
        }
    }

    public static function verify(Request $request)
    {
        $request->validate([
            'otp'   => 'required',
        ]);
        $ga = new GoogleAuthenticator();
        $result = Auth::user() && $ga->verifyCode(Auth::user()->otp_token, $request->otp, 3);
        if($result){
            Auth::user()->update([
                'last_otp' => Carbon::now()
            ]);
            return true;
        }else{
            return response()->json([
                'message' => 'Jednokratna lozinka je neispravna.'
            ], 422);
        }
    }
}
