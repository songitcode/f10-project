<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with('creator')->latest()->get();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code',
            'name' => 'required|string|max:255',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Kiểm tra quyền hạng
        if (Auth::user()->role->level > 3) {
            return back()->withErrors('Bạn không có quyền tạo voucher.');
        }

        try {
            Voucher::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'discount_percent' => $request->discount_percent ?? 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'creator_id' => Auth::id(),
            ]);

            return back()->with('success', 'Tạo voucher thành công!');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->withErrors('Đã có lỗi xảy ra khi tạo voucher.');
        }
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $voucher->update([
            'name' => $request->name,
            'discount_percent' => $request->discount_percent ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return back()->with('success', 'Cập nhật voucher thành công!');
    }

    public function toggle(Voucher $voucher)
    {
        $voucher->update([
            'is_active' => !$voucher->is_active
        ]);

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Xóa voucher thành công!');
    }
}
