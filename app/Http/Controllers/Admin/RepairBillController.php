<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairBill;
use App\Models\User;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairBillController extends Controller
{
    public function index(Request $request)
    {
        // Lấy toàn bộ hóa đơn cùng nhân viên
        $repairBills = RepairBill::with('user')
            ->orderByDesc('created_at')
            ->get();
        $employees = User::active()->get();
        return view('admin.dashboard', compact('repairBills', 'employees'));
    }

    public function show($id)
    {
        $bill = RepairBill::with('user')->findOrFail($id);
        $services = json_decode($bill->services, true);

        return response()->json([
            'bill' => $bill,
            'services' => $services,
            'employee' => $bill->user,
        ]);
    }

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
