<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
     public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials) && Auth::user()->isAdmin()) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/attendance/list');
        }

        // 認証失敗時はログアウトして戻る
        Auth::logout();
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }
}
