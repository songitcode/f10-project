<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\ActivityLogger;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tongSoHoaDon = $user->repairBills()->count();
        $tongTienHoaDon = $user->repairBills()->sum('employee_earnings');

        // 📊 Lấy dữ liệu doanh thu theo tháng trong năm hiện tại
        $year = now()->year;
        $monthlyEarnings = $user->repairBills()
            ->selectRaw('MONTH(created_at) as month, SUM(employee_earnings) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Chuẩn hóa dữ liệu 12 tháng (0 nếu chưa có hóa đơn)
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyEarnings[$i] ?? 0;
        }

        return view('pages.profile', compact('user', 'tongSoHoaDon', 'tongTienHoaDon', 'chartData'));
    }

    // Cập nhật thông tin hồ sơ
    public function update(Request $request)
    {
        $user = Auth::user();

        $kiemTraDuLieu = $request->validate([
            'real_name' => 'required|string|max:255',
            'ingame_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'momo' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
        ]);

        $user->update([
            'real_name' => $request->real_name,
            'ingame_name' => $request->ingame_name,
            'email' => $request->email,
            'momo' => $request->momo,
            'birthday' => $request->birthday,
        ]);

        ActivityLogger::employee($user->real_name . ' đã cập nhật hồ sơ', $kiemTraDuLieu);

        return redirect()->back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    // Cập nhật mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        ActivityLogger::employee($user->real_name . ' đã đổi mật khẩu', $request->all());

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
