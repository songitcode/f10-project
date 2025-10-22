<?php
// app/Http/Controllers/RepairBillController.php
namespace App\Http\Controllers;

use App\Models\RepairBill;
use App\Models\User;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RepairBillController extends Controller
{
    // Danh sách hóa đơn
    public function index(Request $request)
    {
        // Lấy toàn bộ hóa đơn cùng nhân viên
        $repairBills = RepairBill::with('user')
            ->orderByDesc('created_at')
            ->get();
        $employees = User::active()->get();
        return view('admin.dashboard', compact('repairBills', 'employees'));
    }

    public function create()
    {
        // Nhân viên chỉ thấy hóa đơn của mình, quản lý thấy tất cả
        if (auth()->user()->roleLevel <= 4) {
            $employees = User::active()->get();
        } else {
            $employees = User::where('id', auth()->id())->get();
        }

        return view('repair-bills.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_momo' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => 'nullable|string|max:255',
            'services' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'employee_earnings' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $bill = RepairBill::create([
            'bill_code' => 'HD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'user_id' => Auth::id(),
            'customer_momo' => $request->customer_momo,
            'vehicle_type' => $request->vehicle_type,
            'license_plate' => $request->license_plate,
            'services' => $request->services,
            'total_amount' => $request->total_amount,
            'employee_earnings' => $request->employee_earnings,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        $real_name = $request->user()->real_name;

        ActivityLogger::employee($real_name . ' đã thêm hóa đơn', $bill->toArray());

        return redirect()->back()->with('success', 'Hóa đơn #' . $bill->bill_code . ' đã được tạo thành công!');
    }

    // Xem chi tiết hóa đơn theo ID
    public function show($id)
    {
        $bill = RepairBill::with('user')->findOrFail($id);
        $services = json_decode($bill->services, true);

        return response()->json([
            'bill' => $bill,
            'services' => $services,
            'updater' => $bill->user->updater, // Người đã chỉnh sửa nhân viên này
            'employee' => $bill->user,
        ]);
    }

    // Cập nhật trạng thái (hoàn thành / huỷ)
    public function updateStatus(Request $request, $id)
    {
        $bill = RepairBill::findOrFail($id);

        // Kiểm tra quyền thay đổi trạng thái
        // if (auth()->user()->roleLevel > 3 && $bill->user_id != auth()->id()) {
        //     abort(403, 'Bạn không có quyền thay đổi trạng thái hóa đơn này!');
        // }
        if (!auth()->user()->isManager()) {
            abort(403, 'Bạn không có quyền thay đổi trạng thái hóa đơn này!');
        }

        $bill->update([
            'status' => $request->status,
        ]);
        $bill_realname = $request->user()->real_name;

        ActivityLogger::admin('Cập nhật trạng thái ' . $bill_realname, $request, $bill->toArray());

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}