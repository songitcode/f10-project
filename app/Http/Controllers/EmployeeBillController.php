<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RepairBill;
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
        $bills = RepairBill::where('user_id', $id)
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
        $bill = RepairBill::with('user')->findOrFail($id);
        return response()->json($bill);
    }

    public function updateBill(Request $request, $id)
    {
        $bill = RepairBill::findOrFail($id);

        $validated = $request->validate([
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => 'nullable|string|max:255',
            'customer_momo' => 'required|string|max:20',
            'services' => 'nullable|string|max:500',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        // Giữ nguyên phần trăm cũ đã lưu
        $percentage = $bill->percentage ?? 0;

        // Tính lại tiền nhân viên theo tổng tiền mới và phần trăm cũ
        $employeeEarnings = ($validated['total_amount'] * $percentage) / 100;

        $bill->update([
            'vehicle_type' => $validated['vehicle_type'],
            'license_plate' => $validated['license_plate'] ?? null,
            'customer_momo' => $validated['customer_momo'],
            'services' => $validated['services'],
            'total_amount' => $validated['total_amount'],
            'employee_earnings' => $employeeEarnings,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            // Không cập nhật percentage để giữ nguyên tỉ lệ cũ
        ]);

        ActivityLogger::admin($request->user()->real_name . ' sửa hóa đơn ' . $bill->bill_code, $bill, $bill->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật hóa đơn thành công!',
            'bill' => $bill
        ]);
    }

}
