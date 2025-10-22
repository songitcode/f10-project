<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Position;
use App\Models\Role;
use App\Models\RepairBill;
use App\Models\ActivityLog;
use App\Models\EmployeeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_employees' => User::active()->count(),
            'new_employees' => User::where('start_date', '>=', now()->subMonth())->count(),
            'inactive_employees' => User::where('is_active', false)->count(),
            'total_bills' => RepairBill::count(),
            'total_positions' => Position::count(),
            'pending_bills' => RepairBill::where('status', 'pending')->count(),
            'completed_bills' => RepairBill::where('status', 'completed')->count(),
            'monthly_revenue' => RepairBill::whereMonth('created_at', now()->month)->sum('total_amount'),
        ];

        $employees = User::with(['position.role'])
            ->select('users.*')
            ->addSelect([
                'role_id' => Position::select('role_id')
                    ->whereColumn('positions.id', 'users.position_id')
                    ->limit(1),
            ])
            ->orderByRaw('role_id')
            ->get();

        $positions = Position::withCount('users')
            ->with('role')
            ->orderByRaw('role_id')
            ->get();
        $roles = Role::withCount('positions')->get();
        $repairBills = RepairBill::with('user')->latest()->get();

        // Top employees by revenue
        $topEmployees = User::with('position')
            ->withCount(['repairBills as repair_bills_count'])
            ->select('users.*')
            ->addSelect([
                'total_revenue' => RepairBill::select(DB::raw('SUM(total_amount)'))
                    ->whereColumn('user_id', 'users.id')
                    ->where('status', 'completed'),
                'total_earnings' => RepairBill::select(DB::raw('SUM(employee_earnings)'))
                    ->whereColumn('user_id', 'users.id')
                    ->where('status', 'completed')
            ])
            ->having('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            // ->limit(5)
            ->get();

        // 🔹 Thống kê số nhân viên theo tháng (12 tháng gần nhất)
        $employeeCounts = User::select(
            DB::raw('MONTH(start_date) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('start_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Chuẩn hóa đủ 12 tháng (nếu tháng nào không có dữ liệu)
        $months = collect(range(1, 12))->map(function ($m) use ($employeeCounts) {
            return $employeeCounts[$m] ?? 0;
        });

        $monthLabels = collect(range(1, 12))->map(fn($m) => 'Tháng ' . $m);
        $recentActivities = [
            ['message' => 'Hệ thống đã sẵn sàng', 'type' => 'primary', 'time' => 'Hôm nay'],
            ['message' => 'Chào mừng đến với F10 Auto Repair', 'type' => 'success', 'time' => 'Hôm nay'],
            ['message' => 'Tận hưởng một ngày làm việc năng động', 'type' => 'success', 'time' => 'Hôm nay'],
        ];
        $logs = ActivityLog::with('user')->latest()->paginate(10);
        $logsUser = EmployeeLog::with('user')->latest()->paginate(10);

        return view('admin.dashboard', compact(
            'stats',
            'employees',
            'positions',
            'roles',
            'repairBills',
            'topEmployees',
            'recentActivities',
            'months',
            'monthLabels',
            'logs',
            'logsUser',
        ));
    }
}