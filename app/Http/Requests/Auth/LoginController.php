<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses login user.
     */
    public function login(LoginRequest $request)
    {
        // Lakukan validasi dan autentikasi
        $request->authenticate();

        // Regenerasi session untuk menghindari session fixation
        $request->session()->regenerate();

        // Redirect ke halaman tujuan setelah login
        return redirect()->intended('/dashboard');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session dan regenerasi token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
