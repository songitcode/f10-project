<?php
namespace App\Http\Controllers;

use App\Models\WorkSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\ActivityLogger;

class WorkScheduleController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'work_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'note' => 'nullable|string',
        ]);
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $exists = WorkSchedule::where('user_id', $request->user_id)
            ->where('work_date', $request->work_date)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_time', '<=', $start)
                            ->where('end_time', '>=', $end);
                    });
            })
            ->exists();
        if ($exists) {
            return back()->with('error', 'Nhân viên này đã có lịch làm trong khoảng thời gian đó!')->withInput();
        }
        WorkSchedule::create($request->all());
        ActivityLogger::admin('Tạo lịch cho ' . $request->user()->real_name, $request, $request->toArray());

        return back()->with('success', 'Đã thêm lịch làm việc thành công!');
    }

    public function update(Request $request, WorkSchedule $hr_lich_lam_viec)
    {
        $request->validate([
            'work_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'note' => 'nullable|string',
        ]);

        $hr_lich_lam_viec->update($request->all());

        ActivityLogger::admin('Sửa lịch cho ' . $request->user()->real_name, $request, $request->toArray());

        return back()->with('success', 'Cập nhật lịch thành công!');
    }

    public function destroy(WorkSchedule $hr_lich_lam_viec)
    {
        $hr_lich_lam_viec->delete();
        ActivityLogger::admin('Xóa lịch làm việc của ' . $hr_lich_lam_viec->user->real_name, $hr_lich_lam_viec, $hr_lich_lam_viec->user->toArray());
        return back()->with('success', 'Xóa lịch làm việc thành công!');
    }

    // Hiển thị theo tuần
    public function hienThiLichLamViecTuan()
    {
        // Tuần hiện tại (hoặc tuần được chọn)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = (clone $startOfWeek)->addDays(6); // 2-Chủ Nhật

        // Lấy tất cả lịch trong tuần này
        $schedules = WorkSchedule::with('user')
            ->whereBetween('work_date', [$startOfWeek, $endOfWeek])
            ->orderBy('work_date')
            ->get();

        // Gom lịch theo ngày
        $grouped = $schedules->groupBy('work_date');

        // Các mốc giờ (0 → 23)
        $hours = range(0, 23);

        return view('pages.work-schedule', [
            'grouped' => $grouped,
            'hours' => $hours,
            'startOfWeek' => $startOfWeek,
            'endOfWeek' => $endOfWeek,
        ]);
    }

    public function xoaToanBoLichLamViec()
    {
        WorkSchedule::truncate();
        ActivityLogger::admin('Xóa toàn bộ lịch làm việc', null, []);
        return back()->with('success', 'Đã xóa toàn bộ lịch làm việc thành công!');
    }
}
