<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RepairBill;
use App\Helpers\ActivityLogger;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairBillController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bills = RepairBill::with('user')->where('user_id', $user->id)->latest()->get();
        $vouchers = Voucher::where('is_active', true)->get();

        return view('pages.bills', compact('bills', 'vouchers'));
    }
    public function showBill($id)
    {
        $bill = RepairBill::with('user')->findOrFail($id);
        return response()->json($bill);
    }
    public function store(Request $request)
    {
        $request->validate([
            'customer_momo' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'services' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $position = $user->position;
        $percentage = $position->salary_percentage ?? 0;

        // 🔹 Lấy voucher (nếu có)
        $voucher = Voucher::find($request->voucher_id);
        $discount = 0;

        if ($voucher && $voucher->is_active) {
            if ($voucher->discount_percent > 0) {
                // Giảm theo %
                $discount = ($request->total_amount * $voucher->discount_percent) / 100;
            } elseif ($voucher->discount_amount > 0) {
                // Giảm theo số tiền cố định
                $discount = $voucher->discount_amount;
            }
        }

        // 🔹 Tổng tiền sau khi giảm
        $finalAmount = max(0, $request->total_amount - $discount);

        // 🔹 Tính hoa hồng dựa trên số tiền cuối cùng (đã trừ voucher)
        $employeeEarnings = ($finalAmount * $percentage) / 100;

        // 🔹 Lưu vào database
        $bill = RepairBill::create([
            'bill_code' => 'HD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'user_id' => $user->id,
            'customer_momo' => $request->customer_momo,
            'vehicle_type' => $request->vehicle_type,
            'services' => $request->services,
            'total_amount' => $request->total_amount,
            'voucher_id' => $voucher?->id,
            'discount_amount' => $discount,
            'final_amount' => $finalAmount,
            'percentage' => $percentage,
            'employee_earnings' => $employeeEarnings,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        ActivityLogger::employee($user->real_name . ' đã thêm hóa đơn', $bill->toArray());

        return redirect()->back()->with('success', 'Hóa đơn #' . $bill->bill_code . ' đã được tạo thành công!');
    }
}
