<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'name';

        $user = \App\Models\User::where($loginField, $credentials['username'])->first();

        // Kiểm tra tài khoản có tồn tại không
        if (!$user) {
            // ->withErrors([$loginField => 'Tài khoản không tồn tại.']);
            return back()
                ->with('error', 'Thông tin ' . $request->username . ' đăng nhập không tồn tại trên hệ thống.')
                ->onlyInput('username');
        }

        // 🔥 Nếu tài khoản bị vô hiệu
        if (!$user->is_active) {
            return back()->with('deactivated', 'Tài khoản của bạn đã bị vô hiệu hóa bởi quản trị viên.');
        }

        // Xác thực mật khẩu
        if (Auth::attempt([$loginField => $credentials['username'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();
            // ActivityLogger::admin($request->user()->real_name . ' Login', null, $request->all());
            return redirect()->intended('/');
        }

        return back()
            ->with('error', 'Thông tin ' . $request->username . ' đăng nhập không chính xác.')
            ->onlyInput('username');

        // return back()->withErrors([
        //     'username' => 'Thông tin đăng nhập không chính xác.',
        // ])->onlyInput('username');
    }


    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
