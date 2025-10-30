@extends('layouts.app')

@section('title', 'Trang Chủ')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
@endpush
@section('content')
    <div id="home" class="tab-content active fade-in">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Chào mừng đến với F10 Auto Repair</h1>
                <p class="hero-subtitle">Hệ thống quản lý hóa đơn sửa chữa xe Gacha City</p>
                <button class="btn-add-newbill shiny-cta" data-bs-toggle="modal" data-bs-target="#addBillModal">
                    <span><i class="fas fa-plus-circle me-2"></i>Tạo hóa đơn mới</span>
                </button>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="stats-card stats-1">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <h3>{{ auth()->user()->repairBills()->count() }}</h3>
                    <p>Tổng hóa đơn</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card stats-2">
                    <i class="fas fa-check-circle"></i>
                    <h3>{{ auth()->user()->repairBills()->where('status', 'completed')->count() }}</h3>
                    <p>Đã hoàn thành</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card stats-3">
                    <i class="fas fa-clock"></i>
                    <h3>{{ auth()->user()->repairBills()->where('status', 'pending')->count() }}</h3>
                    <p>Đang chờ xử lý</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card stats-4">
                    <i class="fas fa-dollar-sign"></i>
                    <h3>{{ number_format(auth()->user()->repairBills()->sum('employee_earnings'))}}</h3>
                    <p>Thu nhập</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="row">
            <div class="col-lg-8 col-md-7">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-history me-2"></i>Hoạt động gần đây
                    </div>
                    <div class="card-body">
                        @forelse(auth()->user()->employeeLogs()->latest()->take(4)->get() as $log)
                            <div class="activity-item">
                                <div class="activity-icon" style="background-color: #2a9d8f;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">{{ $log->action }}</div>
                                    <div class="activity-time"><i class="far fa-clock me-1"></i>
                                        {{ $log->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="detail-logs me-2">
                                    @php
                                        $metaData = is_string($log->meta) ? json_decode($log->meta, true) : (array) $log->meta;
                                        $formatKey = function ($key) {
                                            return ucfirst(str_replace(['_', '-'], ' ', $key));
                                        };
                                        $formatValue = function ($value) {
                                            if (is_bool($value)) {
                                                return $value ? '<span class="badge bg-success">Có</span>' : '<span class="badge bg-danger">Không</span>';
                                            }
                                            if (is_null($value) || $value === '') {
                                                return '<em class="text-muted">N/A</em>';
                                            }
                                            if (is_array($value) || is_object($value)) {
                                                $json = json_encode($value, JSON_UNESCAPED_UNICODE);
                                                return '<span class="text-info" title="' . htmlentities($json) . '">Chi tiết (Hover)</span>';
                                            }
                                            return e($value);
                                        };
                                    @endphp
                                    <details>
                                        <summary
                                            style="background: #c1121f; color: #fdf0d5; border-radius: 20px; padding: 5px 15px;">
                                            Xem chi tiết</summary>
                                        <table class="table table-sm table-striped mb-0">
                                            <tbody>
                                                @if(is_array($metaData) && count($metaData) > 0)
                                                    @foreach($metaData as $key => $value)
                                                        <tr>
                                                            <th class="text-nowrap" style="background: #fdf0d5; color: #c1121f;">
                                                                {{ $formatKey($key) }}
                                                            </th>
                                                            <td style="color: #c1121f;">
                                                                {!! $formatValue($value) !!}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted p-3">Không có dữ liệu chi tiết
                                                            (meta)</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </details>
                                </div>
                                {{--
                                <span class="badge bg-success">
                                    good quality
                                </span>
                                --}}
                            </div>
                        @empty
                            <p class="text-muted mb-0">Chưa có hoạt động nào được ghi nhận.</p>
                        @endforelse
                        {{-- <div class="activity-item">
                            <div class="activity-icon" style="background-color: #e9c46a;">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Bắt đầu sửa chữa xe cho khách Nguyễn Văn B</div>
                                <div class="activity-time">4 giờ trước</div>
                            </div>
                            <span class="badge bg-warning">Đang xử lý</span>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background-color: #e63946;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Hóa đơn #HD0243 cần bổ sung thông tin</div>
                                <div class="activity-time">Hôm qua, 15:30</div>
                            </div>
                            <span class="badge bg-danger">Cần sửa</span>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background-color: #d32f2f;">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Tạo hóa đơn mới #HD0246</div>
                                <div class="activity-time">Hôm qua, 10:15</div>
                            </div>
                            <span class="badge bg-primary">Mới</span>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBillModal">
                                <i class="fas fa-plus-circle me-2"></i>Tạo hóa đơn mới
                            </button>
                            {{-- <button class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Tìm hóa đơn
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-print me-2"></i>In báo cáo
                            </button> --}}
                            <button class="btn-work-schedule">
                                <a href="{{ route('work-schedule') }}" class="nav-link">
                                    <i class="fas fa-calendar-alt me-2"></i>Lịch làm việc
                                </a>
                            </button>
                        </div>
                    </div>
                </div>
                {{-- <div class="card mt-4">
                    <div class="card-header">
                        <i class="fas fa-chart-line me-2"></i>Thống kê cá nhân
                    </div>
                    <div class="card-body">
                        <canvas id="personalStatsChart" height="200"></canvas>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Modal thêm hóa đơn -->
    <div class="modal fade" id="addBillModal" tabindex="-1" aria-labelledby="addBillModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('bills.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBillModalLabel">Thêm hóa đơn mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Momo khách hàng</label>
                                <input type="text" name="customer_momo" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Loại xe</label>
                                <input type="text" name="vehicle_type" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Biển số</label>
                                <input type="text" name="license_plate" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tổng tiền ($)</label>
                                <input type="number" step="0.01" name="total_amount" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Dịch vụ sửa chữa</label>
                                <input type="text" name="services" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu hóa đơn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Scripts cụ thể cho trang chủ
    </script>
@endsection