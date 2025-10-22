@extends('layouts.app')

@section('title', Auth::user()->real_name ?? 'N/A')

@section('content')
    <div id="profile" class="profile-content">
        <h2 class="page-title"><i class="fas fa-user me-2"></i>Hồ sơ cá nhân</h2>
        <div class="row">
            <!-- Cột bên trái -->
            <div class="col-md-4">
                <div class="card profile-card">
                    <div class="card-body text-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->real_name) }}&background=random"
                            class="profile-avatar mb-3" width="100" height="100" alt="{{ $user->real_name }}">
                        <h3 class="profile-name">{{ $user->real_name }}</h3>
                        <p class="profile-position">
                            {{ $user->position->name ?? 'Chưa có chức vụ' }} - {{ $user->role->name ?? 'N/A' }}
                        </p>

                        <div class="profile-stats">
                            <div class="profile-stat">
                                <div class="profile-stat-value">{{ $tongSoHoaDon }}</div>
                                <div class="profile-stat-label">Hóa đơn</div>
                            </div>
                            <div class="profile-stat">
                                <div class="profile-stat-value">{{ number_format($tongTienHoaDon) }}$</div>
                                <div class="profile-stat-label">Thu nhập HĐ</div>
                            </div>
                            {{--
                            <div class="profile-stat">
                                <div class="profile-stat-value">{{ $user->role->name ?? 'N/A' }}</div>
                                <div class="profile-stat-label">Quyền</div>
                            </div>
                            --}}
                        </div>

                        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa hồ sơ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cột bên phải -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Thông tin cá nhân</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4"><label>Tên thật</label>
                                <p>{{ $user->real_name }}</p>
                            </div>
                            <div class="col-md-4"><label>Tên ingame</label>
                                <p>{{ $user->ingame_name }}</p>
                            </div>
                            <div class="col-md-4"><label>Email</label>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><label>Ngày sinh</label>
                                <p>{{ optional($user->birthday)->format('d/m/Y') ?? 'Chưa có' }}</p>
                            </div>
                            <div class="col-md-4"><label>Ngày vào làm</label>
                                <p>{{ optional($user->start_date)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-4"><label>MoMo</label>
                                <p>{{ $user->momo ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div>
                            <label>Tên đăng nhập</label>
                            <p>{{ $user->name }}</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Thay đổi mật khẩu</a>
                        </div>
                    </div>
                </div>

                <!-- Thống kê -->
                <div class="card">
                    <div class="card-header">Thống kê hiệu suất</div>
                    <div class="card-body">
                        <canvas id="performanceChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa hồ sơ -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('profile.update') }}" method="POST" id="updateProfileForm" class="modal-content">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Chỉnh sửa hồ sơ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tên thật</label>
                        <input type="text" name="real_name" value="{{ old('real_name', $user->real_name) }}"
                            class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Tên ingame</label>
                        <input type="text" name="ingame_name" value="{{ old('ingame_name', $user->ingame_name) }}"
                            class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>MoMo</label>
                        <input type="text" name="momo" value="{{ old('momo', $user->momo) }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Ngày sinh</label>
                        <input type="date" name="birthday" value="{{ optional($user->birthday)->format('Y-m-d') }}"
                            class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal đổi mật khẩu -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('profile.change-password') }}" method="POST" id="changePasswordForm"
                class="modal-content">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Đổi mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Lịch sử hoạt động</div>
        <div class="card-body">
            <div class="border-bottom py-2 table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Người thực hiện</th>
                            <th>Hành động</th>
                            <th>meta</th>
                            <th>time</th>
                        </tr>
                    </thead>
                    @forelse($user->employeeLogs()->latest()->take(10)->get() as $log)
                        <tbody>
                            <tr>
                                <td>{{ $log->user->real_name ?? 'N/A' }}</td>
                                <td>{{ $log->action }}</td>
                                <td>
                                    <details>
                                        <summary class="text-primary">Xem chi tiết</summary>
                                        <pre
                                            class="small bg-light p-2 rounded">{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </details>
                                </td>
                                <td><i class="far fa-clock me-1"></i>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    @empty
                        <p class="text-muted mb-0">Chưa có hoạt động nào được ghi nhận.</p>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Biểu đồ thống kê hiệu suất
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('performanceChart');
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                        'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                    ],
                    datasets: [{
                        label: 'Doanh thu (USD)',
                        data: chartData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => value.toLocaleString() + '$'
                            },
                            title: {
                                display: true,
                                text: 'Thu nhập theo tháng'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: context => ' ' + context.formattedValue + '$'
                            }
                        }
                    }
                }
            });
        });
    </script>

@endsection