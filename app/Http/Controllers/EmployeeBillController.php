<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RepairBill;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use Illuminate\Container\Attributes\Auth;

class EmployeeBillController extends Controller
{
    public function index()
    {
        // Lấy danh sách nhân sự có hóa đơn, đếm số hóa đơn mỗi người
        $employees = User::withCount('repairBills')
            ->where('is_active', true)
            ->orderByDesc('repair_bills_count')
            ->get();

        return view('admin.dashboard', compact('employees'));
    }

    // Lấy danh sách hóa đơn của nhân viên (AJAX)
    public function getBills($id)
    {
        $employee = User::with('position')->findOrFail($id);

        $bills = RepairBill::with(['voucher', 'user'])
            ->where('user_id', $id)
            ->orderByDesc('created_at')
            ->get();
        return response()->json([
            'employee' => $employee,
            'bills' => $bills,
        ]);
    }

    // Xem chi tiết hóa đơn
    public function showBill($id)
    {
        $bill = RepairBill::with(['voucher', 'user'])->findOrFail($id);
        return response()->json($bill);
    }

    public function updateBill(Request $request, $id)
    {
        $bill = RepairBill::findOrFail($id);

        $validated = $request->validate([
            'vehicle_type' => 'required|string|max:255',
            'customer_momo' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'services' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:pending,in_progress,completed,cancelled',
        ]);

        // Nếu có voucher ID hoặc code
        if ($request->filled('voucher_id') || $request->filled('voucher_code')) {
            $voucher = Voucher::where('id', $request->voucher_id)
                ->orWhere('code', $request->voucher_code)
                ->first();

            if ($voucher && $voucher->isValid()) {
                if ($voucher->discount_percent > 0) {
                    $discount = ($validated['total_amount'] * $voucher->discount_percent) / 100;
                } elseif ($voucher->discount_amount > 0) {
                    $discount = $voucher->discount_amount;
                } else {
                    $discount = 0;
                }
            } else {
                $voucher = null;
                $discount = 0;
            }
        } else {
            $voucher = null;
            $discount = 0;
        }

        $finalAmount = max(0, $validated['total_amount'] - $discount);
        $percentage = $bill->percentage ?? 0;
        $earnings = ($finalAmount * $percentage) / 100;

        $bill->update([
            'vehicle_type' => $validated['vehicle_type'],
            'customer_momo' => $validated['customer_momo'],
            'total_amount' => $validated['total_amount'],
            'services' => $validated['services'],
            'notes' => $validated['notes'],
            'status' => $validated['status'],
            'voucher_id' => $voucher?->id,
            'discount_amount' => $discount,
            'final_amount' => $finalAmount,
            'employee_earnings' => $earnings,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hóa đơn #' . $bill->bill_code . ' đã được cập nhật thành công!',
            'bill' => $bill,
        ]);
    }

}
