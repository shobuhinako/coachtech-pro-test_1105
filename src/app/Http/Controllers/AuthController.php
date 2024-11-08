<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view ('/auth/register');
    }

    public function showLoginForm()
    {
        return view ('/auth/login');
    }

    public function create(Request $request)
    {
        $form = $request->only('name', 'email', 'password');
        $user = User::create([
            'name' => $form['name'],
            'email' => $form['email'],
            'password' => bcrypt($form['password']),
        ]);

        return redirect ('/login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // ログイン成功時の処理
            $user = Auth::user();

            // セッションの再生成（セキュリティ対策）
            $request->session()->regenerate();

            // ホームページにリダイレクト
            return redirect('/');
        }

            // 認証失敗時の処理
            return back()->withErrors(['email' => '認証情報が正しくありません。'])->withInput();
    }

    public function index()
    {
        $shops = Shop::all();

        return view('index', ['shops' => $shops]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
