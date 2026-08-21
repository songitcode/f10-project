@extends('layouts.app')

@section('title', 'Hóa Đơn')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endpush
@section('content')
    <div id="bills">
        <!-- <h2 class="page-title"><i class="fas fa-file-invoice-dollar me-2"></i>Quản lý hóa đơn</h2> -->

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBillModal">
                    <i class="fas fa-plus me-2"></i>Thêm hóa đơn mới
                </button>
            </div>
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Tìm kiếm hóa đơn...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select class="form-select" style="width: 200px;">
                    <option selected>Tất cả trạng thái</option>
                    <option>Đã duyệt</option>
                    <option>Chờ duyệt</option>
                    <option>Đang xử lý</option>
                    <option>Từ chối</option>
                </select>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="h3">Danh sách hóa đơn</span>
                <div class="d-flex">
                    <span class="me-3">Tổng hóa đơn: <strong>{{ $bills->count() }}</strong></span>
                    <span class="me-3">Tổng doanh thu: <strong>{{ number_format($bills->sum('employee_earnings'), 0) }}
                            $</strong></span>
                    <span>Hoa hồng hiện tại:
                        <strong>{{ number_format(auth()->user()->position->salary_percentage, 0) }}%</strong></span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mã hóa đơn</th>
                                <th>Momo</th>
                                <th>Ngày tạo</th>
                                <th>Loại xe</th>
                                <th>Dịch vụ</th>
                                <th>Hóa đơn</th>
                                <th>Bảo hiểm/ Voucher</th>
                                <th>Tiền bảo hiểm</th>
                                <th>Hoa hồng</th>
                                <th>Tiền hoa hồng</th>
                                <th>Tổng nhận được</th>
                                <!-- <th>Trạng thái</th> -->
                                <!-- <th>Thao tác</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bills as $bill)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>#{{ $bill->bill_code }}</td>
                                    <td>{{ $bill->customer_momo }}</td>
                                    <td>{{ $bill->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $bill->vehicle_type }}</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 300px;"
                                            data-bs-toggle="tooltip" title="{{ $bill->services }}">
                                            {{ $bill->services }}
                                        </span>
                                    </td>
                                    {{-- Tổng tiền gốc --}}
                                    <td class="text-warning tienHoaDon">
                                        <strong>{{ number_format($bill->total_amount, 0, ',', '.') }} $</strong>
                                    </td>
                                    {{-- Voucher --}}
                                    <td>
                                        @if($bill->voucher)
                                            <span class="badge bg-info">{{ $bill->voucher->code }}</span><br>
                                            <small>Giảm: <span class="text-danger">-{{ number_format($bill->discount_amount, 0, ',', '.') }}$</span></small>
                                        @else
                                            <span class="text-muted">Không</span>
                                        @endif
                                    </td>
                                    <td><strong class="text-secondary">{{ number_format($bill->final_amount, 0, ',', '.') }}$</strong></td>
                                    <td class="text-primary hoaHong">
                                        {{ number_format($bill->percentage, 0) }}%
                                    </td>
                                    {{-- Tiền nhân viên nhận --}}
                                    <td>
                                        <strong
                                            class="text-success">{{ number_format($bill->employee_earnings, 0, ',', '.') }}$</strong>
                                    </td>

                                    <td class="text-success">
                                        <strong>{{ number_format($bill->employee_earnings, 0, ',', '.') }}
                                            $</strong>
                                    </td>
                                    {{--
                                    <td>
                                        @if ($bill->status === 'completed')
                                        <span class="badge bg-success">Đã duyệt</span>
                                        @elseif ($bill->status === 'pending')
                                        <span class="badge bg-warning">Chờ duyệt</span>
                                        @else
                                        <span class="badge bg-secondary">{{ ucfirst($bill->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary"><i class="fas fa-eye"></i></button>
                                            <button class="btn btn-outline-warning"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                    --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Chưa có hóa đơn nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Trước</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Tiếp</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- MODAL -->
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
                                <label for="editVoucher">Bảo hiểm / Voucher</label>
                                <select id="voucher_id" name="voucher_id" class="form-select">
                                    <option value="">-- Không áp dụng --</option>
                                    @foreach($vouchers as $voucher)
                                        <option value="{{ $voucher->id }}">
                                            {{ $voucher->name }} ({{ $voucher->code }})
                                        </option>
                                    @endforeach
                                </select>
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

@push('scripts')
    <script></script>
@endpush