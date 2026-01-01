<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
{
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.admin-login');
}

  public function login(Request $request)
{
    $data = $request->validate([
        'username' => ['required','string','alpha_dash','min:3','max:32'],
        'password' => ['required','string'],
    ]);

    $ok = \Auth::attempt(
        ['username' => $data['username'], 'password' => $data['password']],
        $request->boolean('remember')
    );

    if ($ok) {
        $request->session()->regenerate();

        // REMOVEMOS a verificação de logout forçado aqui.
        // Agora, tanto Admin quanto Staff podem entrar.
        
        return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors(['username' => __('auth.failed')])->onlyInput('username');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
