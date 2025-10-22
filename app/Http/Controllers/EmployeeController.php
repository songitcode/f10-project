<?php
// app/Http/Controllers/EmployeeController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use App\Helpers\ActivityLogger;
use App\Models\ActivityLog;
// D:\source_web\f10-project\vendor\laravel\framework\src\Illuminate\Routing\Controller.php -> BaseController

class EmployeeController extends BaseController
{
    public function __construct()
    {
        // Chỉ cho phép người có quyền level 4 trở lên truy cập
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || auth()->user()->roleLevel > 4) {
                // abort(403, 'Bạn không có quyền truy cập!');
                return redirect()->back()->with('warning', 'Bạn không đủ quyền chỉnh sửa.');

            }
            return $next($request);
        });
    }

    public function index()
    {
        $employees = User::with(['position.role'])->get();
        return view('admin.dashboard', compact('employees'));
    }

    public function create()
    {
        $positions = Position::with('role')->get();
        return view('admin.dashboard', compact('positions'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này!');
        }
        // Validate dữ liệu
        $request->validate([
            'real_name' => 'required|string|max:255',
            'ingame_name' => 'required|string|max:255',
            'momo' => 'required|string|max:20',
            'birthday' => 'nullable|date',
            'email' => 'required|string|email|max:255|unique:users',
            'position_id' => 'required|exists:positions,id',
            'start_date' => 'required|date',
            'username' => 'required|string|max:255|unique:users,name',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        try {
            DB::transaction(function () use ($request) {
                $taoNhanVien = User::create([
                    'name' => $request->username,
                    'real_name' => $request->real_name,
                    'ingame_name' => $request->ingame_name,
                    'momo' => $request->momo,
                    'birthday' => $request->birthday,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'start_date' => $request->start_date,
                    'position_id' => $request->position_id,
                    'created_by' => Auth::id(),
                ]);
                // ✅ Ghi log hoạt động
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Thêm nhân viên mới',
                    'model_type' => get_class($taoNhanVien),
                    'model_id' => $taoNhanVien->id,
                    'changes' => $taoNhanVien->toArray(), // hoặc json_encode($taoNhanVien->toArray())
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);
                // ActivityLogger::admin('Thêm nhân viên mới', $taoNhanVien, $taoNhanVien->toArray());
            });

            return redirect()->route('admin.dashboard')
                ->with('success', 'Nhân viên đã được tạo thành công!');
        } catch (\Exception $e) {
            // Ghi log nếu cần
            Log::error('Lỗi khi tạo nhân viên: ' . $e->getMessage());

            return redirect()->route('admin.dashboard')
                ->with('error', 'Đã xảy ra lỗi khi tạo nhân viên. Vui lòng thử lại.');
        }
    }

    public function show(User $employee)
    {
        $employee->load(['position.role', 'repairBills']);
        return view('admin.dashboard', compact('employee'));
    }

    public function edit(User $employee)
    {
        $positions = Position::with('role')->get();
        return view('admin.dashboard', compact('employee', 'positions'));
    }

    public function update(Request $request, User $employee)
    {
        // Kiểm tra sự hợp lệ
        $validated = $request->validate([
            'real_name' => 'required|string|max:255',
            'ingame_name' => 'required|string|max:255',
            'momo' => 'required|string|max:20',
            'birthday' => 'nullable|date',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'position_id' => 'required|exists:positions,id',
            'start_date' => 'required|date',
        ]);

        // 2️⃣ Gán thêm người cập nhật
        $validated['updated_by'] = Auth::id();

        $userBeingEdited = User::findOrFail($employee->id); // user đang bị chỉnh sửa
        $currentUser = auth()->user(); // user đang đăng nhập

        // Kiểm tra nếu quyền của người hiện tại thấp hơn user đang bị chỉnh sửa
        if ($userBeingEdited->roleLevel < $currentUser->roleLevel) {
            return redirect()->back()->with('warning', 'Bạn không đủ quyền để chỉnh sửa người dùng này.');
        }
        // Kiểm tra nếu quyền của người hiện tại thấp hơn quyền được gán chỉnh sửa thì không cho phép
        $newPosition = Position::find($validated['position_id']);
        if ($newPosition && $newPosition->role->level < $currentUser->roleLevel) {
            return redirect()->back()->with('warning', 'Bạn không đủ quyền để gán vị trí này cho người dùng.');
        }

        // 3️⃣ Cập nhật
        $employee->update($validated);
        // ✅ Ghi log hoạt động
        ActivityLogger::admin('Thay đổi thông tin ' . $employee->real_name, $employee, $employee->toArray());

        return redirect()->route('admin.dashboard')
            ->with('success', 'Thông tin nhân viên đã được cập nhật!');
    }

    public function destroy(User $employee)
    {
        $userBeingEdited = User::findOrFail($employee->id); // user đang bị chỉnh sửa
        $currentUser = auth()->user(); // user đang đăng nhập

        // Kiểm tra nếu quyền của người hiện tại thấp hơn user đang bị chỉnh sửa
        if ($userBeingEdited->roleLevel <= $currentUser->roleLevel) {
            return redirect()->back()->with('warning', 'Bạn không đủ quyền vô hiệu hóa người dùng này.');
        }

        // Không xóa mà chỉ vô hiệu hóa tài khoản
        $employee->update([
            'is_active' => false,
            'deactivated_by' => Auth::id(), // ✅ người thực hiện vô hiệu hóa
        ]);

        ActivityLogger::admin('Vô hiệu hóa ' . $employee->real_name, $employee, $employee->toArray());

        return redirect()->route('admin.dashboard')
            ->with('success', 'Nhân viên đã được vô hiệu hóa!');
    }

    public function activate(User $employee)
    {
        $employee->update(['is_active' => true]);

        ActivityLogger::admin('Bỏ vô hiệu hóa ' . $employee->real_name, $employee, $employee->toArray());

        return redirect()->route('admin.dashboard')
            ->with('success', 'Nhân viên đã được kích hoạt lại!');
    }
    public function destroyPermanent($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete(); // Xóa luôn khỏi DB

        ActivityLogger::admin('Xóa (' . $employee->real_name . ') khỏi hệ thống', $employee, $employee->toArray());

        return redirect()->route('admin.dashboard')->with('success', 'Đã xóa nhân viên vĩnh viễn!');
    }

}