<?php
// app/Http/Controllers/PositionController.php
namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller as BaseController;
use App\Helpers\ActivityLogger;

class PositionController extends BaseController
{
    public function __construct()
    {
        // Chỉ cho phép người có quyền level 3 trở lên truy cập
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || auth()->user()->roleLevel > 3) {
                // abort(403, 'Bạn không có quyền truy cập!');
                return redirect()->back()->with('warning', 'Bạn không đủ quyền chỉnh sửa.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $positions = Position::with('role')->get();
        return view('admin.dashboard', compact('positions'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.dashboard', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:positions',
            'description' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'salary_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Tạo mã chức vụ tự động từ tên
        $code = Str::slug($request->name, '_');

        $themChucVu = Position::create([
            'name' => $request->name,
            'code' => $code,
            'description' => $request->description,
            'role_id' => $request->role_id,
            'salary_percentage' => $request->salary_percentage,
        ]);

        ActivityLogger::admin('Thêm chức vụ (' . $request->name . ')', $themChucVu, $themChucVu->toArray());

        return redirect()->route('admin.dashboard')
            ->with('success', 'Chức vụ đã được tạo thành công!');
    }

    public function edit(Position $position)
    {
        $roles = Role::all();
        return view('admin.dashboard', compact('position', 'roles'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,' . $position->id,
            'code' => 'required|string|max:255|unique:positions,code,' . $position->id,
            'salary_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            $position->update($request->all());

            ActivityLogger::admin('Chỉnh sửa chức vụ ' . $request->name, $position, $position->toArray());

            return redirect()->route('admin.dashboard')
                ->with('success', 'Chức vụ đã được cập nhật!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra, không thể cập nhật chức vụ. Vui lòng thử lại.');
        }
    }

    public function destroy(Position $position)
    {
        // Kiểm tra xem có nhân viên nào đang sử dụng chức vụ này không
        if ($position->users()->exists()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Không thể xóa chức vụ này vì có nhân viên đang sử dụng!');
        }

        $position->delete();

        ActivityLogger::admin('Xóa chức vụ ' . $position->name, $position, $position->toArray());

        return redirect()->route('admin.dashboard')
            ->with('success', 'Chức vụ đã được xóa!');
    }
}