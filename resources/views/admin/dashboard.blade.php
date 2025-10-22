@extends('layouts.app')

@section('title', 'Bảng điều khiển Quản lý')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endpush

@section('hide_footer')@endsection
@section('hide_navbar')@endsection
@section('hide_main_css')@endsection

@section('content')
    <nav class="mobile-navbar">
        <div class="navbar-brand">
            <!-- <i class="fas fa-users-cog"></i> -->
             <img src="{{ asset('assets/images/f10-auto-repair-logo.png') }}" alt="" width="50" height="50">
             F10 HR
        </div>
        <button class="navbar-toggler" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 sidebar" id="sidebar">
            <div class="text-center mb-4">
                <h3><img src="{{ asset('assets/images/f10-auto-repair-logo.png') }}" alt="" width="100" height="100"> F10 HR</h3>
            </div>
            <ul class="nav flex-column">
                <li><a href="#" class="active" data-tab="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li><a href="#" data-tab="employee-management"><i class="fas fa-user-tie"></i> Quản lý nhân sự</a></li>
                <li><a href="#" data-tab="position-management"><i class="fas fa-briefcase"></i> Quản lý chức vụ</a></li>
                <li><a href="#" data-tab="role-management"><i class="fas fa-user-shield"></i> Danh sách quyền hạng</a></li>
                <li><a href="#" data-tab="repair-bills-management"><i class="fas fa-tools"></i> Quản lý hóa đơn</a></li>
                <li><a href="#" data-tab="reports"><i class="fas fa-chart-bar"></i> Báo cáo</a></li>
                <li><a href="#" data-tab="work-schedule"><i class="fas fa-calendar"></i> Quản Lý Lịch Làm Việc</a></li>
                <li><a href="#" data-tab="logs"><i class="fas fa-history"></i> LOG Quản lý</a></li>
                <li><a href="#" data-tab="logs-user"><i class="fas fa-history"></i> LOG Nhân viên</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 main-content" id="mainContent">
            <div class="box-btn-nav">
                <a href="{{ route('home') }}" class="btn-user-home btn-nav" title="Quay về trang User"
                    data-bs-toggle="tooltip">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
                <a class="btn-nav btn-admin-logout" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

            <!-- Header -->
            <div class="header">
                <h2 id="page-title">Dashboard Quản Lý Nhân Sự</h2>
                <div class="user-info">
                    <span>Xin chào, {{ Auth::user()->position->name ?? '' }}
                        <strong>{{ Auth::user()->real_name ?? Auth::user()->name }}</strong></span>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->real_name) }}&background=random"
                        class="rounded-circle me-2" alt="{{ Auth::user()->real_name }}" width="40" height="40">
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success') || session('error'))
                <div class="flash-message-container position-fixed top-0 start-50 translate-middle-x mt-4 z-index-1050"
                    style="width: 90%; max-width: 500px;">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center gap-2 shadow border-0 rounded-lg px-4 py-3 alert-dismissible fade show"
                            role="alert">
                            <i class="fas fa-check-circle fs-5"></i>
                            <div class="flex-grow-1">{{ session('success') }}</div>
                            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center gap-2 shadow border-0 rounded-lg px-4 py-3 alert-dismissible fade show"
                            role="alert">
                            <i class="fas fa-exclamation-circle fs-5"></i>
                            <div class="flex-grow-1">{{ session('error') }}</div>
                            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Dashboard Tab -->
            <div id="dashboard" class="tab-content active">
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card stats-card">
                            <i class="fas fa-users"></i>
                            <h3>{{ $stats['total_employees'] ?? 0 }}</h3>
                            <p>Tổng số nhân viên</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stats-card">
                            <i class="fas fa-user-plus"></i>
                            <h3>{{ $stats['new_employees'] ?? 0 }}</h3>
                            <p>Nhân viên mới</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stats-card">
                            <i class="fas fa-tools"></i>
                            <h3>{{ $stats['total_bills'] ?? 0 }}</h3>
                            <p>Tổng số hóa đơn</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stats-card">
                            <i class="fas fa-briefcase"></i>
                            <h3>{{ $stats['total_positions'] ?? 0 }}</h3>
                            <p>Tổng số chức vụ</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Biểu đồ nhân viên theo tháng ({{ now()->year }})</h5>
                            </div>
                            <div class="card-body" style="height: 250px;">
                                <canvas id="employeeChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-bell"></i> Thông báo gần đây
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    {{--@foreach($recentActivities as $activity)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $activity['message'] }}
                                        <span class="badge bg-{{ $activity['type'] }} rounded-pill">{{ $activity['time']
                                            }}</span>
                                    </li>
                                    @endforeach--}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DASHBOARD -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-users me-2"></i>Danh sách nhân viên
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Tên thật</th>
                                        <th>Tên Ingame</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Ngày sinh</th>
                                        <th>Momo</th>
                                        <th>Email</th>
                                        <th>Chức vụ</th>
                                        <th>Quyền</th>
                                        <th>Người Tạo</th>
                                        <th>Ngày Tham Gia</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @php
                                                    $name = str_replace('_', ' ', $employee->real_name ?? $employee->name_ingame ?? '');

                                                    $prefix = strtoupper(
                                                        collect(explode(' ', preg_replace('/[^a-zA-ZÀ-Ỵà-ỵ\s]/u', '', $name)))
                                                            ->map(fn($word) => mb_substr($word, 0, 1))
                                                            ->implode('')
                                                    );

                                                    if (empty($prefix)) {
                                                        $prefix = 'NAME-';
                                                    }
                                                @endphp
                                                {{ $prefix . $employee->id }}
                                            </td>
                                            <td>{{ $employee->real_name }}</td>
                                            <td>{{ $employee->ingame_name }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->birthday?->format('d/m/Y') }}</td>
                                            <td>{{ $employee->momo }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $employee->position->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary">{{ $employee->position->role->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>{{ $employee->creator->name ?? 'N/A' }}</td>
                                            <td>{{ $employee->start_date->format('d/m/Y') }}</td>
                                            <td>
                                                @if($employee->is_active)
                                                    <span class="badge bg-success">Đang hoạt động</span>
                                                @else
                                                    <span class="badge bg-danger">Vô hiệu hóa</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Danh sách nhân sự có hóa đơn -->
                <div class="card p-3">
                    <div class="row g-3">
                        <h2><i class="fas fa-users me-2"></i>Chi Tiết Hóa Đơn Nhân Viên</h2>
                        @foreach($employees as $emp)
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 employees-horver-effect">
                                    <div class="card-body d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($emp->real_name) }}&background=random"
                                            class="rounded-circle me-3" width="60" height="60" alt="{{ $emp->real_name }}">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $emp->real_name }}</h6>
                                            <small class="text-muted">({{ $emp->ingame_name }})</small><br>
                                            <span class="badge bg-warning mt-1">{{ $emp->position->name ?? 'N/A' }}</span>
                                            <span class="badge bg-secondary mt-1">{{ $emp->role->name }}
                                                #{{ $emp->role_level }}</span>
                                            <span class="badge bg-info mt-1">#{{ $loop->iteration }}</span>
                                            <span class="badge bg-info mt-1">{{ $emp->name }}</span>
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn-outline-secondary mt-2 view-bills"
                                                title="Xem danh sách hóa đơn" data-bs-toggle="tooltip"
                                                data-employee-id="{{ $emp->id }}">
                                                <i class="fas fa-file-invoice"></i> Mở
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Employee Management Tab -->
            <div id="employee-management" class="tab-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stats-employee d-flex">
                        <p>Total Emps: <strong>{{ $stats['total_employees'] ?? 0 }}</strong> -</p>
                        <p> New Emps (month: {{ now()->subMonth()->format('m') }}, {{ now()->subMonth()->format('m') + 1}}): <strong>{{ $stats['new_employees'] ?? 0 }}</strong></p>
                    </div>
                    @if (auth()->user()->isQuanLyNhanSu())
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                            <i class="fas fa-plus"></i> Thêm nhân viên
                        </button>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-users me-2"></i>Danh sách nhân viên
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Tên thật</th>
                                        <th>Tên Ingame</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Ngày sinh</th>
                                        <th>Momo</th>
                                        <th>Email</th>
                                        <th>Chức vụ</th>
                                        <th>Quyền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @php
                                                    $name = str_replace('_', ' ', $employee->real_name ?? $employee->name_ingame ?? '');
                                                    $prefix = strtoupper(
                                                        collect(explode(' ', preg_replace('/[^a-zA-ZÀ-Ỵà-ỵ\s]/u', '', $name)))
                                                            ->map(fn($word) => mb_substr($word, 0, 1))
                                                            ->implode('')
                                                    );
                                                    if (empty($prefix)) {
                                                        $prefix = 'NAME-';
                                                    }
                                                @endphp
                                                {{ $prefix . $employee->id }}
                                            </td>
                                            <td>
                                                {{--<div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->real_name) }}&background=random"
                                                        class="employee-photo me-2" alt="{{ $employee->real_name }}">
                                                </div> --}}
                                                {{ $employee->real_name }}
                                            </td>
                                            <td>{{ $employee->ingame_name }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->birthday?->format('d/m/Y') }}</td>
                                            <td>{{ $employee->momo }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>
                                                <span class="badge 
                                                    @switch($employee->position->role->level)
                                                        @case(1) bg-danger @break
                                                        @case(2) bg-warning text-dark @break
                                                        @case(3) bg-info text-dark @break
                                                        @case(4) bg-success @break
                                                        @default bg-secondary
                                                    @endswitch
                                                    ">
                                                    {{ $employee->position->name ?? 'N/A' }}
                                                </span>
                                                {{-- <span class="badge bg-secondary">{{ $employee->position->name ?? 'N/A'
                                                    }}</span>--}}
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @switch($employee->position->role->level)
                                                        @case(1) bg-danger @break
                                                        @case(2) bg-warning text-dark @break
                                                        @case(3) bg-info text-dark @break
                                                        @case(4) bg-success @break
                                                        @default bg-secondary
                                                    @endswitch
                                                    ">
                                                    {{ $employee->role->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($employee->is_active)
                                                    <span class="badge bg-success">Đang hoạt động</span>
                                                @else
                                                    <span class="badge bg-danger">Vô hiệu hóa</span>
                                                @endif
                                            </td>
                                            @if (auth()->user()->isQuanLyNhanSu())
                                            <td>
                                                <div class="btn-group gap-2">
                                                    <button class="btn btn-outline-primary edit-employee"
                                                        data-employee-id="{{ $employee->id }}" data-bs-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if($employee->is_active)
                                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger"
                                                                data-bs-toggle="tooltip" title="Vô hiệu hóa (BAND)"
                                                                onclick="return confirm('Bạn có chắc muốn vô hiệu hóa nhân viên này?')">
                                                                <i class="fas fa-user-slash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('employees.activate', $employee->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success"
                                                                data-bs-toggle="tooltip" title="Kích hoạt lại"
                                                                onclick="return confirm('Bạn có chắc muốn kích hoạt lại nhân viên này?')">
                                                                <i class="fas fa-user-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('employees.destroyPermanent', $employee->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này không?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip"
                                                            title="Xóa khỏi hệ thống"> <i class="fas fa-trash"></i> </button>
                                                    </form>
                                                </div>
                                            </td>
                                            @else
                                            <td>Không Có Quyền</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Position Management Tab -->
            <div id="position-management" class="tab-content">
                <!-- Nội dung quản lý chức vụ -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Quản lý chức vụ</h4>
                    @if (auth()->user()->isAdmin() || auth()->user()->isQuanLyToanHeThong())
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                        <i class="fas fa-plus"></i> Thêm chức vụ
                    </button>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-briefcase me-2"></i>Danh sách chức vụ
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Tên chức vụ</th>
                                        <th>Mã chức vụ</th>
                                        <th>Quyền hạn</th>
                                        <th>Mô tả</th>
                                        <th>Số nhân viên</th>
                                        <th>Phần trăm lương</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($positions as $position)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @php
                                                    // Lấy tên hoặc code của chức vụ
                                                    $name = str_replace('_', ' ', $position->code ?? $position->name ?? '');
                                                    // Tạo prefix: lấy chữ cái đầu của mỗi từ, viết hoa
                                                    $prefix = strtoupper(collect(explode(' ', preg_replace('/[^a-zA-ZÀ-Ỵà-ỵ\s]/u', '', $name)))
                                                        ->map(fn($word) => mb_substr($word, 0, 1))
                                                        ->implode(''));
                                                    // Nếu trống, dùng 'CV' (chức vụ)
                                                    if (empty($prefix)) {
                                                        $prefix = 'CV-';
                                                    }
                                                @endphp
                                                {{ $prefix . $position->id }}
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $position->name }}</span>
                                            </td>
                                            <td><code>{{ $position->code }}</code></td>
                                            <td>
                                                <span class="badge 
                                                    @switch($position->role->level)
                                                        @case(1) bg-danger @break
                                                        @case(2) bg-warning text-dark @break
                                                        @case(3) bg-info text-dark @break
                                                        @case(4) bg-success @break
                                                        @default bg-secondary
                                                    @endswitch
                                                    ">
                                                    {{ $position->role->name ?? 'Chưa gán' }}
                                                </span>
                                            </td>
                                            <td>{{ $position->description ?? '—' }}</td>
                                            <td>{{ $position->users->count() }}</td>
                                            <td>{{ $position->salary_percentage }}%</td>
                                            @if (auth()->user()->isAdmin() || auth()->user()->isQuanLyToanHeThong())
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary edit-position"
                                                            data-position-id="{{ $position->id }}" data-bs-toggle="tooltip"
                                                            title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('positions.destroy', $position->id) }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger"
                                                                data-bs-toggle="tooltip" title="Xóa chức vụ"
                                                                onclick="return confirm('Bạn có chắc muốn xóa chức vụ này?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @else
                                                <td>Không Có Quyền</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Role Management Tab -->
            <div id="role-management" class="tab-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Danh sách quyền hạng</h4>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-shield me-2"></i>Danh sách quyền
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên quyền</th>
                                        <th>Mô tả</th>
                                        <th>Cấp độ</th>
                                        <th>Số chức vụ</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>{{ $role->id }}</td>
                                            <td>
                                                <strong>{{ $role->name }}</strong>
                                            </td>
                                            <td>{{ $role->description ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">Cấp {{ $role->level }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $role->positions_count ?? 0 }}</span>
                                            </td>
                                            <td>{{ $role->created_at->format('d/m/Y') }}</td>
                                            
                                            @if (Auth::user()->isAdmin())
                                            <td class="text-center">
                                                <button class="btn btn-primary edit-role-btn"
                                                data-id="{{ $role->id }}"
                                                data-name="{{ $role->name }}"
                                                data-description="{{ $role->description }}">
                                                <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        @else
                                            <td>Không có quyền</td>
                                        @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Repair Bills Tab -->
            <div id="repair-bills-management" class="tab-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Danh sách nhân viên</h4>
                </div>
                <!-- Danh sách nhân sự có hóa đơn -->
                <div class="row g-3">
                    @foreach($employees as $emp)
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 employees-horver-effect">
                                <div class="card-body d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($emp->real_name) }}&background=random"
                                        class="rounded-circle me-3" width="60" height="60" alt="{{ $emp->real_name }}">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $emp->real_name }}</h6>
                                        <small class="text-muted">({{ $emp->ingame_name }})</small><br>
                                        <span class="badge bg-info mt-1">#{{ $loop->iteration }}</span>
                                        <span class="badge bg-warning mt-1">{{ $emp->position->name ?? 'N/A' }}</span>
                                        <span class="badge bg-secondary mt-1">{{ $emp->role->name }}
                                            #{{ $emp->role_level }}</span>
                                        <span class="badge bg-info mt-1">{{ $emp->name }}</span>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-outline-secondary mt-2 view-bills" title="Xem danh sách hóa đơn"
                                            data-bs-toggle="tooltip" data-employee-id="{{ $emp->id }}">
                                            <i class="fas fa-file-invoice"></i> Mở
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Filter Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form id="filterBillForm" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="filterStatus" class="form-label">Trạng thái</label>
                                        <select class="form-select" id="filterStatus" name="status">
                                            <option value="">Tất cả</option>
                                            <option value="pending">Chờ duyệt</option>
                                            <option value="in_progress">Đang xử lý</option>
                                            <option value="completed">Đã hoàn thành</option>
                                            <option value="cancelled">Đã hủy</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="filterEmployee" class="form-label">Nhân viên</label>
                                        <select class="form-select" id="filterEmployee" name="employee_id">
                                            <option value="">Tất cả</option>
                                            @foreach($employees as $employee)
                                                @if($employee->is_active)
                                                    <option value="{{ $employee->id }}">{{ $employee->real_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="filterDateFrom" class="form-label">Từ ngày</label>
                                        <input type="date" class="form-control" id="filterDateFrom" name="date_from">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="filterDateTo" class="form-label">Đến ngày</label>
                                        <input type="date" class="form-control" id="filterDateTo" name="date_to">
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Lọc
                                </button>
                                <button type="button" class="btn btn-secondary" id="resetFilter">
                                    <i class="fas fa-redo"></i> Đặt lại
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-tools me-2"></i>Danh sách tất cả hóa đơn
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã HD</th>
                                        <th>Bill Code</th>
                                        <th>Nhân viên</th>
                                        <th>Momo KH</th>
                                        <th>Loại xe</th>
                                        <th>Biển số</th>
                                        <th>Dịch vụ</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($repairBills as $bill)
                                        @php
                                            $services = json_decode($bill->services, true);
                                        @endphp
                                        <tr>
                                            <td>HD{{ str_pad($bill->id, 6, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $bill->bill_code }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($bill->user->real_name) }}&background=random"
                                                        class="rounded-circle me-2" width="30" height="30"
                                                        alt="{{ $bill->user->real_name }}">
                                                    {{ $bill->user->real_name }}
                                                </div>
                                            </td>
                                            <td>{{ $bill->customer_momo }}</td>
                                            <td>{{ $bill->vehicle_type }}</td>
                                            <td>{{ $bill->license_plate ?? 'N/A' }}</td>
                                            <td>
                                                {{--
                                                <span class="d-inline-block text-truncate" style="max-width: 200px;"
                                                    data-bs-toggle="tooltip" title="{{ $bill->services }}">
                                                    {{ $bill->services }}
                                                </span>
                                                --}}
                                                @foreach($services as $service)
                                                    <span class="badge bg-primary me-1">
                                                        <i class="bi bi-tools"></i> {{ $service }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <strong class="text-success">{{ number_format($bill->total_amount) }}$</strong>
                                            </td>
                                            <td>
                                                @if($bill->status == 'pending')
                                                    <span class="badge bg-warning">Chờ duyệt</span>
                                                @elseif($bill->status == 'in_progress')
                                                    <span class="badge bg-info">Đang xử lý</span>
                                                @elseif($bill->status == 'completed')
                                                    <span class="badge bg-success">Đã hoàn thành</span>
                                                @else
                                                    <span class="badge bg-danger">Đã hủy</span>
                                                @endif
                                            </td>
                                            <td>{{ $bill->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary view-bill"
                                                        data-bill-id="{{ $bill->id }}" data-bs-toggle="tooltip"
                                                        title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-warning edit-bill"
                                                        data-bill-id="{{ $bill->id }}" data-bs-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if($bill->status != 'completed' && $bill->status != 'cancelled')
                                                        <form action="{{ route('repair-bills.update-status', $bill->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="btn btn-outline-success"
                                                                data-bs-toggle="tooltip" title="Đánh dấu hoàn thành"
                                                                onclick="return confirm('Xác nhận hoàn thành hóa đơn này?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Reports Tab -->
            <div id="reports" class="tab-content">
                <!-- Report Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="reportFilterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="reportType" class="form-label">Loại báo cáo</label>
                                        <select class="form-select" id="reportType" name="report_type">
                                            <option value="employee">Báo cáo nhân sự</option>
                                            <option value="revenue">Báo cáo doanh thu</option>
                                            <option value="bills">Báo cáo hóa đơn</option>
                                            <option value="performance">Báo cáo hiệu suất</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="reportPeriod" class="form-label">Kỳ báo cáo</label>
                                        <select class="form-select" id="reportPeriod" name="period">
                                            <option value="today">Hôm nay</option>
                                            <option value="week">Tuần này</option>
                                            <option value="month" selected>Tháng này</option>
                                            <option value="quarter">Quý này</option>
                                            <option value="year">Năm nay</option>
                                            <option value="custom">Tùy chọn</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 custom-date" style="display: none;">
                                    <div class="mb-3">
                                        <label for="reportDateFrom" class="form-label">Từ ngày</label>
                                        <input type="date" class="form-control" id="reportDateFrom" name="date_from">
                                    </div>
                                </div>
                                <div class="col-md-3 custom-date" style="display: none;">
                                    <div class="mb-3">
                                        <label for="reportDateTo" class="form-label">Đến ngày</label>
                                        <input type="date" class="form-control" id="reportDateTo" name="date_to">
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-chart-bar"></i> Tạo báo cáo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stats-card bg-primary text-white">
                            <i class="fas fa-money-bill-wave"></i>
                            <h3>{{ number_format($reportStats['total_revenue'] ?? 0) }}₫</h3>
                            <p>Tổng doanh thu</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card bg-success text-white">
                            <i class="fas fa-receipt"></i>
                            <h3>{{ $reportStats['total_bills'] ?? 0 }}</h3>
                            <p>Tổng hóa đơn</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card bg-info text-white">
                            <i class="fas fa-users"></i>
                            <h3>{{ $reportStats['active_employees'] ?? 0 }}</h3>
                            <p>Nhân viên hoạt động</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card bg-warning text-white">
                            <i class="fas fa-chart-line"></i>
                            <h3>{{ $reportStats['completion_rate'] ?? 0 }}%</h3>
                            <p>Tỷ lệ hoàn thành</p>
                        </div>
                    </div>
                </div>

                <!-- Charts and Detailed Reports -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-chart-bar me-2"></i> Biểu đồ doanh thu theo tháng
                            </div>
                            <div class="card-body">
                                <canvas id="revenueChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-chart-pie me-2"></i> Phân loại hóa đơn
                            </div>
                            <div class="card-body">
                                <canvas id="billStatusChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-table me-2"></i> Chi tiết báo cáo
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="reportTable">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Nhân viên</th>
                                                <th>Số hóa đơn</th>
                                                <th>Tổng doanh thu</th>
                                                <th>Lương nhận được</th>
                                                <th>Tỷ lệ hoàn thành</th>
                                                <th>Đánh giá</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $employeePerformance = $employeePerformance ?? [];
                                            @endphp
                                            @foreach($employeePerformance as $performance)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $performance['employee_name'] ?? 'N/A' }}</td>
                                                    <td>{{ $performance['bill_count'] ?? 0 }}</td>
                                                    <td>{{ number_format($performance['total_revenue'] ?? 0) }}₫</td>
                                                    <td>{{ number_format($performance['estimated_salary'] ?? 0) }}₫</td>
                                                    <td>
                                                        @php
                                                            $completionRate = $performance['completion_rate'] ?? 0;
                                                            $progressBarClass = $completionRate >= 80 ? 'bg-success' : ($completionRate >= 60 ? 'bg-warning' : 'bg-danger');
                                                        @endphp
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar {{ $progressBarClass }}" role="progressbar"
                                                                style="width: {{ $completionRate }}%"
                                                                aria-valuenow="{{ $completionRate }}" aria-valuemin="0"
                                                                aria-valuemax="100">
                                                                {{ $completionRate }}%
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($completionRate >= 80)
                                                            <span class="badge bg-success">Xuất sắc</span>
                                                        @elseif($completionRate >= 60)
                                                            <span class="badge bg-warning">Khá</span>
                                                        @else
                                                            <span class="badge bg-danger">Cần cải thiện</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Work schedule Tab -->
            <div id="work-schedule" class="tab-content">
                <div class="card">
                    Lịch làm việc
                </div>
            </div>
            <!-- Logs Tab -->
            <div id="logs" class="tab-content">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>Nhật ký hoạt động hệ thống
                        </h5>
                        <span class="badge bg-light text-dark">{{ $logs->total() }} hoạt động</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Người thao tác</th>
                                        <th>Hành động</th>
                                        <th>Đối tượng</th>
                                        <th>Changes</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->real_name ?? 'Hệ thống') }}&background=random"
                                                        alt="Avatar" class="rounded-circle me-2" width="35" height="35">
                                                    <div>
                                                        <small>({{ $log->user->position->name }})</small>
                                                        <strong>{{ $log->user->real_name ?? 'Hệ thống' }}</strong><br>
                                                        <small class="text-muted">{{ $log->ip_address }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $log->action }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ class_basename($log->model_type)  }} #{{ $log->model_id }}</small>
                                            </td>
                                            <td style="max-width: 400px;">
                                                @if($log->changes)
                                                    <details>
                                                        <summary class="text-primary">Xem chi tiết</summary>
                                                        <pre class="bg-light p-2 mt-2 rounded small text-dark">
                                                            <table class="table table-sm table-bordered mb-0 bg-light">
                                                                @php
                                                                    $changes = is_string($log->changes) ? json_decode($log->changes, true) : $log->changes;
                                                                @endphp
                                                                <tbody>
                                                                    @if(is_array($changes))
                                                                        @foreach($changes as $key => $value)
                                                                            <tr>
                                                                                <th class="text-nowrap text-primary">{{ ucfirst(str_replace('_', ' ', $key)) ?? 'N/A' }}</th>
                                                                                <td>{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value ?? 'N/A' }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="2">No changes available</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </pre>
                                                    </details>
                                                @else
                                                    <span class="text-muted">Không có thay đổi</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>{{ $log->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                <i class="fas fa-info-circle me-2"></i>Chưa có hoạt động nào được ghi lại.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($logs->hasPages())
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-center">
                                {{ $logs->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- logs tab user -->
            <div id="logs-user" class="tab-content">
                @if (Auth::user()->isAdmin())
                <div class="card">
                    <div class="card-header text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Nhật ký hoạt động của nhân viên
                        </h5>
                        <span class="badge bg-light text-dark">{{ $logsUser->total() }} hoạt động</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Nhân viên</th>
                                    <th>Hành động</th>
                                    <th>Chi tiết</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logsUser as $log)
                                    <tr>
                                        <td>
                                            <strong>{{ $log->user->real_name ?? 'Không xác định' }}</strong><br>
                                            <small class="text-muted">{{ $log->employee->ingame_name ?? '' }}</small>
                                        </td>
                                        <td><span class="badge bg-info">{{ $log->action }}</span></td>
                                        <td>
                                            @if(is_array($log->meta))
                                                <details>
                                                    <summary class="text-primary">Xem chi tiết</summary>
                                                    <pre
                                                        class="small bg-light p-2 rounded">{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </details>
                                            @else
                                                <span class="text-muted">Không có chi tiết</span>
                                            @endif
                                        </td>
                                        <td> <i class="far fa-clock me-1"></i>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Chưa có hoạt động nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                         @if($logsUser->hasPages())
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-center">
                                    {{ $logsUser->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    @else
                        <div class="p-2 text-center">Bạn Không Có Quyền Xem Logs Này</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Nhân Viên-->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addEmployeeModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Thêm nhân viên mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addEmployeeForm" action="{{ route('employees.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="step-indicator mb-4">
                            <div class="step active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-label">Thông tin cá nhân</div>
                            </div>
                            <div class="step" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-label">Thông tin công việc</div>
                            </div>
                            <div class="step" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-label">Tài khoản hệ thống</div>
                            </div>
                        </div>

                        <!-- Step 1: Personal Information -->
                        <div class="form-step active" data-step="1">
                            <h5 class="text-primary mb-3">Thông tin cá nhân</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employeeRealName" class="form-label">Tên thật <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="employeeRealName" name="real_name"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employeeNameIngame" class="form-label">Tên ingame <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="employeeNameIngame" name="ingame_name"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employeeMoMo" class="form-label">Momo <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="employeeMoMo" name="momo" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employeeBirthday" class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" id="employeeBirthday" name="birthday">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="employeeEmail" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="employeeEmail" name="email" required>
                            </div>
                        </div>

                        <!-- Step 2: Job Information -->
                        <div class="form-step" data-step="2">
                            <h5 class="text-primary mb-3">Thông tin công việc</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employeePosition" class="form-label">Chức vụ <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="employeePosition" name="position_id" required>
                                            <option value="">Chọn chức vụ</option>
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}">
                                                    {{ $position->name }} ({{ $position->role->name ?? '' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employeeStartDate" class="form-label">Ngày vào làm <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="employeeStartDate" name="start_date"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: System Account -->
                        <div class="form-step" data-step="3">
                            <h5 class="text-primary mb-3">Tài khoản hệ thống</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employeeUsername" class="form-label">Tên đăng nhập <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="employeeUsername" name="username"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employeePassword" class="form-label">Mật khẩu <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="employeePassword" name="password"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="employeeConfirmPassword" class="form-label">Xác nhận mật khẩu <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="employeeConfirmPassword"
                                    name="password_confirmation" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendCredentials"
                                    name="send_credentials">
                                <label class="form-check-label" for="sendCredentials">
                                    Gửi thông tin đăng nhập qua email cho nhân viên
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-outline-primary" id="prevStepBtn" style="display: none;">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </button>
                        <button type="button" class="btn btn-primary" id="nextStepBtn">
                            Tiếp theo <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                        <button type="submit" class="btn btn-success" id="submitEmployeeBtn" style="display: none;">
                            <i class="fas fa-save me-1"></i>Tạo nhân viên
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Nhân Viên -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-dark">
                    <h5 class="modal-title" id="editEmployeeModalLabel">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa nhân viên (<span id="editName"></span>)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editEmployeeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editRealName" class="form-label">Tên thật <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editRealName" name="real_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editIngameName" class="form-label">Tên ingame <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editIngameName" name="ingame_name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editMomo" class="form-label">Momo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editMomo" name="momo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editBirthday" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="editBirthday" name="birthday">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editPosition" class="form-label">Chức vụ <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="editPosition" name="position_id" required>
                                        <option value="">Chọn chức vụ</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}">
                                                {{ $position->name }} ({{ $position->role->name ?? '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editStartDate" class="form-label">Ngày vào làm <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="editStartDate" name="start_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Chức Vụ -->
    <div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addPositionModalLabel">
                        <i class="fas fa-briefcase me-2"></i>Thêm chức vụ mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('positions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="positionName" class="form-label">Tên chức vụ <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="positionName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="positionCode" class="form-label">Mã chức vụ</label>
                            <input type="text" class="form-control" id="positionCode" name="code" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="positionDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="positionDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="positionLevel" class="form-label">Quyền hạng <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="positionLevel" name="role_id" required>
                                <option value="">Chọn quyền hạng</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">(Cấp {{ $role->level }}) - {{ $role->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="salaryPercentage" class="form-label">Phần trăm lương từ hóa đơn (%) <span
                                    class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="salaryPercentage"
                                name="salary_percentage" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo chức vụ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal sửa chức vụ -->
    <div class="modal fade" id="editPositionModal" tabindex="-1" aria-labelledby="editPositionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title fw-bold" id="editPositionLabel">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa chức vụ
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Đóng"></button>
                </div>
                <form id="editPositionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tên chức vụ</label>
                                <input type="text" class="form-control" id="editPositionName" name="name"
                                    placeholder="Nhập tên chức vụ" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Mã chức vụ</label>
                                <input type="text" class="form-control" id="editCode" name="code"
                                    placeholder="Ví dụ: ADMIN_F10" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Phần trăm lương %</label>
                                <input type="number" class="form-control" id="editPercentage" name="salary_percentage"
                                    placeholder="10%" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Mô tả</label>
                                <textarea class="form-control" id="editDescription" name="description" rows="2"
                                    placeholder="Mô tả ngắn gọn chức vụ"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Quyền hạn</label>
                                <select class="form-select" id="editRoleId" name="role_id" required>
                                    <option value="">-- Chọn quyền --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn btn-danger px-4 fw-semibold">
                                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal sửa quyền hạng -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="editRoleModalLabel">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa quyền
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editRoleName" class="form-label">Tên quyền</label>
                            <input type="text" class="form-control" id="editRoleName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRoleDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="editRoleDescription" name="description" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal danh sách hóa đơn -->
    <div class="modal fade" id="employeeBillsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-file-invoice me-2"></i>Hóa đơn của nhân viên</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="billsTableContainer">
                    <p class="text-center text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa hóa đơn -->
    <div class="modal fade" id="billEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Chỉnh sửa hóa đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editBillForm">
                        @csrf
                        <input type="hidden" id="editBillId">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Loại xe</label>
                                <input type="text" id="editVehicleType" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Biển số</label>
                                <input type="text" id="editLicensePlate" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Momo khách</label>
                                <input type="text" id="editCustomerMomo" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Tổng tiền ($)</label>
                                <input type="number" id="editTotalAmount" class="form-control" min="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Dịch vụ (cách nhau bằng dấu phẩy)</label>
                            <input type="text" id="editServices" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Ghi chú</label>
                            <textarea id="editNotes" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Trạng thái</label>
                            <select id="editStatus" class="form-select">
                                <option value="pending">Chờ duyệt</option>
                                <option value="in_progress">Đang xử lý</option>
                                <option value="completed">Đã hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chi tiết hóa đơn -->
    <div class="modal fade" id="billDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Chi tiết hóa đơn</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="billDetailContent">
                    <p class="text-center text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('assets/js/admin.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fix for mobile responsiveness
        window.addEventListener('resize', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth >= 992) {
                sidebar.classList.add('active');
                mainContent.classList.add('sidebar-open');
            } else {
                sidebar.classList.remove('active');
                mainContent.classList.remove('sidebar-open');
            }
        });

        // Initialize on load
        if (window.innerWidth >= 992) {
            document.getElementById('sidebar').classList.add('active');
            document.getElementById('mainContent').classList.add('sidebar-open');
        }

        // Chuyển Tab Dashboard
        document.addEventListener('DOMContentLoaded', function () {
            // Fix sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                    mainContent.classList.toggle('sidebar-open');
                });
            }

            // Fix tab switching
            document.querySelectorAll('.sidebar a[data-tab]').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Remove active class from all tabs and links
                    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

                    // Add active class to clicked link
                    this.classList.add('active');

                    // Show corresponding tab
                    const tabId = this.getAttribute('data-tab');
                    const targetTab = document.getElementById(tabId);
                    if (targetTab) {
                        targetTab.classList.add('active');
                    }

                    // ✅ Lưu tab hiện tại vào localStorage
                    localStorage.setItem('activeTab', tabId);

                    // Update page title
                    const pageTitle = document.getElementById('page-title');
                    if (pageTitle) {
                        pageTitle.textContent = this.textContent.trim();
                    }

                    // Close sidebar on mobile
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('active');
                        mainContent.classList.remove('sidebar-open');
                    }
                });
            });

            // Fix employee form steps
            let currentStep = 1;
            const totalSteps = 3;

            function showStep(step) {
                // Hide all steps
                document.querySelectorAll('.form-step').forEach(stepEl => {
                    stepEl.classList.remove('active');
                });

                // Show current step
                const currentStepEl = document.querySelector(`.form-step[data-step="${step}"]`);
                if (currentStepEl) {
                    currentStepEl.classList.add('active');
                }

                // Update step indicators
                document.querySelectorAll('.step-indicator .step').forEach(stepIndicator => {
                    const stepNumber = parseInt(stepIndicator.getAttribute('data-step'));
                    if (stepNumber <= step) {
                        stepIndicator.classList.add('active');
                    } else {
                        stepIndicator.classList.remove('active');
                    }
                });

                // Update buttons
                const prevBtn = document.getElementById('prevStepBtn');
                const nextBtn = document.getElementById('nextStepBtn');
                const submitBtn = document.getElementById('submitEmployeeBtn');

                if (prevBtn) prevBtn.style.display = step > 1 ? 'inline-block' : 'none';
                if (nextBtn) nextBtn.style.display = step < totalSteps ? 'inline-block' : 'none';
                if (submitBtn) submitBtn.style.display = step === totalSteps ? 'inline-block' : 'none';
            }

            // Next step button
            const nextStepBtn = document.getElementById('nextStepBtn');
            if (nextStepBtn) {
                nextStepBtn.addEventListener('click', function () {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            }

            // Previous step button
            const prevStepBtn = document.getElementById('prevStepBtn');
            if (prevStepBtn) {
                prevStepBtn.addEventListener('click', function () {
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            }

            // Auto-generate position code
            const positionNameInput = document.getElementById('positionName');
            const positionCodeInput = document.getElementById('positionCode');

            if (positionNameInput && positionCodeInput) {
                positionNameInput.addEventListener('input', function () {
                    const name = this.value;
                    const code = name.toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9\s]/g, '')
                        .replace(/\s+/g, '_');
                    positionCodeInput.value = code;
                });
            }

            // Khởi tạo modal và dữ liệu
            const editEmployeeModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
            const editEmployeeForm = document.getElementById('editEmployeeForm');
            const employeesData = @json($employees->keyBy('id')->toArray());
            const saveButton = editEmployeeForm.querySelector('button[type="submit"]');
            let originalData = {};

            // Khi nhấn nút chỉnh sửa nhân viên mở modal
            document.querySelectorAll('.edit-employee').forEach(button => {
                button.addEventListener('click', function () {
                    const employeeId = this.getAttribute('data-employee-id');
                    const employee = employeesData[employeeId];

                    if (!employee) return;

                    editEmployeeForm.action = `/employees/${employeeId}`;

                    // Lưu dữ liệu gốc
                    originalData = {
                        real_name: employee.real_name || '',
                        ingame_name: employee.ingame_name || '',
                        momo: employee.momo || '',
                        birthday: employee.birthday ? employee.birthday.substring(0, 10) : '',
                        email: employee.email || '',
                        position_id: employee.position_id ? String(employee.position_id) : '',
                        start_date: employee.start_date ? employee.start_date.substring(0, 10) : '',
                    };

                    // Gán vào form
                    document.getElementById('editRealName').value = originalData.real_name;
                    document.getElementById('editIngameName').value = originalData.ingame_name;
                    document.getElementById('editName').textContent = employee.name || '';
                    document.getElementById('editMomo').value = originalData.momo;
                    document.getElementById('editEmail').value = originalData.email;
                    document.getElementById('editPosition').value = originalData.position_id;
                    document.getElementById('editBirthday').value = originalData.birthday;
                    document.getElementById('editStartDate').value = originalData.start_date;

                    // Reset nút lưu
                    saveButton.disabled = true;
                    saveButton.title = 'Không có thay đổi để lưu';

                    // Hiển thị modal
                    editEmployeeModal.show();
                });
            });

            // Hàm so sánh thay đổi
            function hasEmployeeChanges() {
                const current = {
                    real_name: document.getElementById('editRealName').value.trim(),
                    ingame_name: document.getElementById('editIngameName').value.trim(),
                    momo: document.getElementById('editMomo').value.trim(),
                    birthday: document.getElementById('editBirthday').value.trim(),
                    email: document.getElementById('editEmail').value.trim(),
                    position_id: document.getElementById('editPosition').value,
                    start_date: document.getElementById('editStartDate').value.trim(),
                };

                // Nếu có bất kỳ trường nào khác với bản gốc → return true
                return Object.keys(originalData).some(key => originalData[key] !== current[key]);
            }

            // Theo dõi thay đổi
            editEmployeeForm.querySelectorAll('input, select').forEach(input => {
                ['input', 'change'].forEach(evt => {
                    input.addEventListener(evt, () => {
                        const changed = hasEmployeeChanges();
                        saveButton.disabled = !changed;
                        saveButton.title = changed ? 'Lưu thay đổi' : 'Không có thay đổi để lưu';
                    });
                });
            });


            document.getElementById('editEmployeeModal').addEventListener('hide.bs.modal', function (e) {
                if (hasEmployeeChanges()) {
                    if (!confirm('Bạn có thay đổi chưa lưu. Bạn có chắc muốn đóng?')) {
                        e.preventDefault(); // Ngăn đóng modal
                    }
                }
            });

            // Fix modal backdrop issues
            document.addEventListener('show.bs.modal', function () {
                document.body.classList.add('modal-open');
            });

            document.addEventListener('hidden.bs.modal', function () {
                document.body.classList.remove('modal-open');
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Reset employee form when modal is closed
            const addEmployeeModal = document.getElementById('addEmployeeModal');
            if (addEmployeeModal) {
                addEmployeeModal.addEventListener('hidden.bs.modal', function () {
                    currentStep = 1;
                    showStep(currentStep);
                    document.getElementById('addEmployeeForm').reset();
                });
            }
        });

        // ✅ Khi load lại trang, tự động mở tab trước đó
        document.addEventListener('DOMContentLoaded', function () {
            const savedTab = localStorage.getItem('activeTab');
            if (savedTab) {
                // Bỏ active mặc định
                document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

                // Kích hoạt lại tab đã lưu
                const activeLink = document.querySelector(`.sidebar a[data-tab="${savedTab}"]`);
                const activeTab = document.getElementById(savedTab);
                if (activeLink && activeTab) {
                    activeLink.classList.add('active');
                    activeTab.classList.add('active');
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('employeeChart').getContext('2d');
            const employeeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($monthLabels),
                    datasets: [{
                        label: 'Nhân viên mới',
                        data: @json($months),
                        backgroundColor: 'rgba(52, 152, 219, 0.7)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const editButtons = document.querySelectorAll('.edit-position');
            const editForm = document.getElementById('editPositionForm');

            // Danh sách chức vụ từ PHP để lấy dữ liệu nhanh
            const positions = @json($positions->keyBy('id'));

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-position-id');
                    const position = positions[id];

                    if (position) {
                        // Cập nhật form action
                        editForm.action = `/positions/${id}`;

                        // Đổ dữ liệu vào form
                        document.getElementById('editPositionName').value = position.name || '';
                        document.getElementById('editCode').value = position.code || '';
                        document.getElementById('editPercentage').value = position.salary_percentage || '';
                        document.getElementById('editDescription').value = position.description || '';
                        document.getElementById('editRoleId').value = position.role_id || '';

                        // Mở modal
                        const modal = new bootstrap.Modal(document.getElementById('editPositionModal'));
                        modal.show();
                    }
                });
            });
        });
        // JS Xử lý nút xem hóa đơn
        document.addEventListener('DOMContentLoaded', function () {
            const billModal = new bootstrap.Modal(document.getElementById('billDetailModal'));

            document.querySelectorAll('.view-bill').forEach(button => {
                button.addEventListener('click', async function () {
                    const billId = this.getAttribute('data-bill-id');
                    const content = document.getElementById('billDetailContent');
                    content.innerHTML = `<p class="text-muted text-center">Đang tải dữ liệu...</p>`;

                    try {
                        const response = await fetch(`/repair-bills/${billId}`);
                        const data = await response.json();

                        const bill = data.bill;
                        const emp = data.employee;
                        const services = data.services;

                        let serviceList = services.map(s => `<li>${s}</li>`).join('');

                        content.innerHTML = `
                                <div class="mb-3">
                                    <strong>Mã hóa đơn:</strong> HD${String(bill.id).padStart(4, '0')}
                                </div>
                                <div class="mb-3">
                                    <strong>Nhân viên:</strong> ${emp.real_name} (${emp.ingame_name})
                                </div>
                                <div class="mb-3">
                                    <strong>Momo Momo:</strong> ${bill.customer_momo}
                                </div>
                                <div class="mb-3">
                                    <strong>Loại xe:</strong> ${bill.vehicle_type}
                                </div>
                                <div class="mb-3">
                                    <strong>Biển số:</strong> ${bill.license_plate ?? 'N/A'}
                                </div>
                                <div class="mb-3">
                                    <strong>Dịch vụ:</strong>
                                    <ul>${serviceList}</ul>
                                </div>
                                <div class="mb-3">
                                    <strong>Tổng tiền:</strong> ${new Intl.NumberFormat().format(bill.total_amount)}$
                                </div>
                                <div class="mb-3">
                                    <strong>Trạng thái:</strong> ${bill.status}
                                </div>
                                <div class="mb-3">
                                    <strong>Ghi chú:</strong> ${bill.notes ?? 'Không có'}
                                </div>
                                <div class="text-end text-muted">
                                    <small>Tạo ngày: ${new Date(bill.created_at).toLocaleString()}</small>
                                </div>
                            `;
                        billModal.show();
                    } catch (error) {
                        content.innerHTML = `<p class="text-danger text-center">Lỗi khi tải dữ liệu!</p>`;
                    }
                });
            });
        });
        //// Xử lý nút sửa hóa đơn
        document.addEventListener('click', async function (e) {
            // 🟡 Khi nhấn nút Sửa
            if (e.target.closest('.btn-outline-warning')) {
                const id = e.target.closest('.btn-outline-warning').closest('tr').querySelector('.view-bill').getAttribute('data-bill-id');
                const res = await fetch(`/repair-bills/${id}`);
                const bill = await res.json();

                // Đổ dữ liệu vào form
                document.getElementById('editBillId').value = bill.id;
                document.getElementById('editVehicleType').value = bill.vehicle_type || '';
                document.getElementById('editLicensePlate').value = bill.license_plate || '';
                document.getElementById('editCustomerMomo').value = bill.customer_momo || '';
                document.getElementById('editTotalAmount').value = bill.total_amount || 0;
                document.getElementById('editServices').value = (JSON.parse(bill.services || '[]')).join(', ');
                document.getElementById('editNotes').value = bill.notes || '';
                document.getElementById('editStatus').value = bill.status;

                new bootstrap.Modal(document.getElementById('billEditModal')).show();
            }
        });
        // 🟢 Submit form chỉnh sửa
        document.addEventListener('DOMContentLoaded', () => {
            const editForm = document.getElementById('editBillForm');
            editForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                const id = document.getElementById('editBillId').value;

                const payload = {
                    vehicle_type: document.getElementById('editVehicleType').value,
                    license_plate: document.getElementById('editLicensePlate').value,
                    customer_momo: document.getElementById('editCustomerMomo').value,
                    total_amount: document.getElementById('editTotalAmount').value,
                    services: document.getElementById('editServices').value
                        .split(',')
                        .map(s => s.trim())
                        .filter(Boolean),
                    notes: document.getElementById('editNotes').value,
                    status: document.getElementById('editStatus').value,
                };

                try {
                    const res = await fetch(`/repair-bills/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(payload)
                    });

                    // Nếu request thất bại (419, 422, 500...)
                    if (!res.ok) {
                        const errText = await res.text();
                        console.error('❌ Lỗi server:', errText);
                        alert('Không thể lưu. Kiểm tra console để biết thêm chi tiết.');
                        return;
                    }

                    const result = await res.json();

                    if (result.success) {
                        // alert('✅ ' + result.message);
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã cập nhật!',
                            text: result.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                        const modalEl = document.getElementById('billEditModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();
                        setTimeout(() => location.reload(), 3000);
                    } else {
                        alert('❌ Cập nhật thất bại!');
                    }

                } catch (error) {
                    console.error(error);
                    alert('⚠️ Lỗi kết nối tới server!');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const employeeModal = new bootstrap.Modal(document.getElementById('employeeBillsModal'));
            const billDetailModal = new bootstrap.Modal(document.getElementById('billDetailModal'));

            // Nhấn "Xem hóa đơn"
            document.querySelectorAll('.view-bills').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const empId = this.getAttribute('data-employee-id');
                    const container = document.getElementById('billsTableContainer');
                    container.innerHTML = `<p class="text-center text-muted">Đang tải dữ liệu...</p>`;
                    employeeModal.show();

                    const res = await fetch(`/employees/${empId}/bills`);
                    const data = await res.json();

                    const emp = data.employee;
                    const bills = data.bills;

                    if (!bills.length) {
                        container.innerHTML = `<p class="text-center text-muted">Nhân viên này chưa có hóa đơn nào.</p>`;
                        return;
                    }

                    let rows = bills.map(b => {
                        const services = JSON.parse(b.services || '[]').map(s => `<span class="badge bg-primary me-1">${s}</span>`).join('');
                        return `
                            <tr>
                                <td>HD${String(b.id).padStart(4, '0')}</td>
                                <td>${b.bill_code}</td>
                                <td>${b.vehicle_type}</td>
                                <td>${b.license_plate ?? 'N/A'}</td>
                                <td>${b.customer_momo}</td>
                                <td>${services}</td>
                                <td><strong class="text-success">${new Intl.NumberFormat().format(b.total_amount)}$</strong></td>
                                <td><span class="badge bg-${b.status === 'completed' ? 'success' : (b.status === 'pending' ? 'warning' : 'secondary')}">${b.status}</span></td>
                                <td>${new Date(b.created_at).toLocaleString()}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info view-bill" data-bill-id="${b.id}"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                        `;
                    }).join('');

                    container.innerHTML = `
                        <h5 class="mb-3">#${emp.id} ${emp.position.name} <strong>${emp.real_name}</strong> (${emp.ingame_name}) - Username: ${emp.name}</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã HD</th>
                                        <th>Code</th>
                                        <th>Loại xe</th>
                                        <th>Biển số</th>
                                        <th>Momo KH</th>
                                        <th>Dịch vụ</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    `;
                });
            });

            // Nhấn "Xem chi tiết hóa đơn"
            document.addEventListener('click', async function (e) {
                if (e.target.closest('.view-bill')) {
                    const id = e.target.closest('.view-bill').getAttribute('data-bill-id');
                    const content = document.getElementById('billDetailContent');
                    content.innerHTML = `<p class="text-center text-muted">Đang tải dữ liệu...</p>`;
                    billDetailModal.show();

                    const res = await fetch(`/repair-bills/${id}`);
                    const bill = await res.json();
                    const services = JSON.parse(bill.services || '[]').map(s => `<li>${s}</li>`).join('');

                    content.innerHTML = `
                            <div><strong>Mã HD:</strong> HD${String(bill.id).padStart(6, '0')}</div>
                            <div><strong>Loại xe:</strong> ${bill.vehicle_type}</div>
                            <div><strong>Biển số:</strong> ${bill.license_plate}</div>
                            <div><strong>Momo:</strong> ${bill.customer_momo}</div>
                            <div><strong>Dịch vụ:</strong><ul>${services}</ul></div>
                            <div><strong>Tổng tiền:</strong> ${new Intl.NumberFormat().format(bill.total_amount)}$</div>
                            <div><strong>Trạng thái:</strong> ${bill.status}</div>
                            <div><strong>Ghi chú:</strong> ${bill.notes ?? 'Không có'}</div>
                        `;
                }
            });
        });
        ////
        // Mở modal sửa quyền hạng
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-role-btn');
            const form = document.getElementById('editRoleForm');
            const modal = new bootstrap.Modal(document.getElementById('editRoleModal'));

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const desc = this.dataset.description || '';

                    form.action = `/roles/${id}`;
                    form.querySelector('#editRoleName').value = name;
                    form.querySelector('#editRoleDescription').value = desc;

                    modal.show();
                });
            });
        });

        // Flash message tự ẩn
        setTimeout(() => {
            const flashMsg = document.querySelector('.flash-message-container .alert');
            if (flashMsg) {
                flashMsg.style.transition = 'opacity 0.5s ease';
                flashMsg.style.opacity = '0';
                setTimeout(() => flashMsg.remove(), 500);
            }
        }, 3000);

        // Xử lý filter báo cáo
        const reportPeriod = document.getElementById('reportPeriod');
        const customDateFields = document.querySelectorAll('.custom-date');

        if (reportPeriod) {
            reportPeriod.addEventListener('change', function () {
                if (this.value === 'custom') {
                    customDateFields.forEach(field => field.style.display = 'block');
                } else {
                    customDateFields.forEach(field => field.style.display = 'none');
                }
            });
        }

        // Xử lý reset filter hóa đơn
        const resetFilterBtn = document.getElementById('resetFilter');
        if (resetFilterBtn) {
            resetFilterBtn.addEventListener('click', function () {
                document.getElementById('filterBillForm').reset();
                window.location.href = window.location.pathname;
            });
        }
    </script>
@endpush