<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RepairBill;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairBillController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $bills = RepairBill::where('user_id', $userId)->latest()->get();

        return view('pages.bills', compact('bills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_momo' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => 'nullable|string|max:255',
            'services' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $position = $user->position;

        // Tính tiền nhân viên nhận được theo % chức vụ
        $percentage = $position->salary_percentage ?? 0;
        $employeeEarnings = ($request->total_amount * $percentage) / 100;

        $bill = RepairBill::create([
            'bill_code' => 'HD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'user_id' => Auth::id(),
            'customer_momo' => $request->customer_momo,
            'vehicle_type' => $request->vehicle_type,
            'license_plate' => $request->license_plate,
            'services' => is_array($request->services)
                ? json_encode($request->services, JSON_UNESCAPED_UNICODE)
                : $request->services,
            'total_amount' => $request->total_amount,
            'employee_earnings' => $employeeEarnings,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        ActivityLogger::employee(Auth::user()->real_name . ' đã thêm hóa đơn', $bill->toArray());

        return redirect()->back()->with('success', 'Hóa đơn #' . $bill->bill_code . ' đã được tạo thành công!');
    }
}
