@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('content')
    <div id="home" class="tab-content active fade-in">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Chào mừng đến với F10 Auto Repair</h1>
                <p class="hero-subtitle">Hệ thống quản lý hóa đơn sửa chữa xe Gacha City</p>
                <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#addBillModal">
                    <i class="fas fa-plus-circle me-2"></i>Tạo hóa đơn mới
                </button>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="stats-card stats-1">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <h3>24</h3>
                    <p>Tổng hóa đơn</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card stats-2">
                    <i class="fas fa-check-circle"></i>
                    <h3>24</h3>
                    <p>Đã hoàn thành</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card stats-3">
                    <i class="fas fa-clock"></i>
                    <h3>4</h3>
                    <p>Đang chờ xử lý</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card stats-4">
                    <i class="fas fa-dollar-sign"></i>
                    <h3>18,856</h3>
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
                        <div class="activity-item">
                            <div class="activity-icon" style="background-color: #2a9d8f;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Hoàn thành hóa đơn #HD0245</div>
                                <div class="activity-time">2 giờ trước</div>
                            </div>
                            <span class="badge bg-success">Đã duyệt</span>
                        </div>
                        <div class="activity-item">
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
                        </div>
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
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Tìm hóa đơn
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-print me-2"></i>In báo cáo
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-calendar-alt me-2"></i>Lịch làm việc
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <i class="fas fa-chart-line me-2"></i>Thống kê cá nhân
                    </div>
                    <div class="card-body">
                        <canvas id="personalStatsChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Hóa Đơn -->
    <div class="modal fade" id="addBillModal" tabindex="-1" aria-labelledby="addBillModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBillModalLabel">Thêm hóa đơn mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="billForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customerName" class="form-label">Tên khách hàng</label>
                                    <input type="text" class="form-control" id="customerName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customerPhone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="customerPhone" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicleType" class="form-label">Loại xe</label>
                                    <input type="text" class="form-control" id="vehicleType" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="licensePlate" class="form-label">Biển số</label>
                                    <input type="text" class="form-control" id="licensePlate">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="services" class="form-label">Dịch vụ sửa chữa</label>
                            <textarea class="form-control" id="services" rows="3" placeholder="Mô tả dịch vụ sửa chữa"
                                required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cost" class="form-label">Chi phí (VNĐ)</label>
                                    <input type="number" class="form-control" id="cost" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-select" id="status">
                                        <option value="pending">Chờ duyệt</option>
                                        <option value="in-progress">Đang xử lý</option>
                                        <option value="completed">Đã hoàn thành</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="notes" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary">Lưu hóa đơn</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Scripts cụ thể cho trang chủ
    </script>
@endsection