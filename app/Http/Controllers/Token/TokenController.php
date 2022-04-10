<?php

namespace App\Http\Controllers\Token;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function generateToken()
    {
        $ga = new GoogleAuthenticator();
        $code = $ga->createSecret();
        $qr = $ga->getQRCodeGoogleUrl('educa', $code);
        return response()->json([
            'qr' => $qr,
            'code' => $code,
        ]);
    }

    public function verifyToken(Request $request)
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

    public static function check(Request $request)
    {
        $request->validate([
            'otp'   => 'required',
        ]);
        $ga = new GoogleAuthenticator();
        $result = Auth::user() && $ga->verifyCode(Auth::user()->otp_token, $request->otp, 3);
        if($result){
            return $result;
        }else{
            return response()->json([
                'message' => 'Jednokratna lozinka je neispravna.'
            ], 422);
        }
    }
}
