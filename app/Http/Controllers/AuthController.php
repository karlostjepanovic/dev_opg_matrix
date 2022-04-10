<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Token\GoogleAuthenticator;
use App\Models\App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Sučelje za prijavu
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function index()
    {
        if(Auth::check()){
            return redirect('set-family-farm');
        }else{
            return view('login');
        }
    }

    /**
     * Prijava korisnika
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function login(Request $request)
    {
        $request->validate([
            'username'  => 'required',
            'password'  => 'required|min:4'
        ]);

        $credentials = array(
            'username'  => $request->get('username'),
            'password' => $request->get('password')
        );

        if(Auth::attempt($credentials)) {
            Auth::user()->update([
                'last_login' => Carbon::now()
            ]);
            return redirect('/');
        }else{
            return back()->with('error', 'Krivo korisničko ime ili lozinka.');
        }
    }

    /**
     * Odjava korisnika
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        session()->flush();
        Auth::logout();
        return redirect()->route('login');
    }
}
