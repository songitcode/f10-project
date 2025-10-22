<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $role->update($validated);

        ActivityLogger::admin('Chỉnh sửa quyền', $role, json_encode($validated, JSON_UNESCAPED_UNICODE));

        return redirect()->back()->with('success', 'Cập nhật quyền thành công!');
    }
}
