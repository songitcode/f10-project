@extends('layouts.app') {{-- Kế thừa layout --}}

@section('title', 'Đăng nhập')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
@endpush

@section('hide_bootstrap') {{-- Ẩn Bootstrap nếu không cần thiết --}}
@endsection
@section('hide_footer') {{-- Ẩn Bootstrap nếu không cần thiết --}}
@endsection

@section('content')
    <div class="login-container">
        <div class="left-panel">
            <div class="logo">
                <div class="logo-circle">
                    <img src="{{ asset('assets/images/f10-auto-repair-logo.png') }}">
                </div>
                <div class="logo-text">F10 AUTO REPAIR</div>
            </div>
            <div class="welcome-text">
                <h1>Chào mừng đến với F10</h1>
                <p>Dịch vụ sửa chữa xe chuyên nghiệp hàng đầu. Đăng nhập để quản lý và theo dõi tiến trình nhân viên
                    của bạn.</p>
            </div>
            <div class="features">
                <div class="feature">
                    <h3>Tiêu trí hàng đầu</h3>
                </div>
                <div class="feature">
                    <i class="fas fa-star"></i>
                    <span>Chât lượng, uy tín</span>
                </div>
                <div class="feature">
                    <i class="fas fa-tools"></i>
                    <span>Sửa chữa chuyên nghiệp</span>
                </div>
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <span>Tiết kiệm thời gian</span>
                </div>
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <span>Hỗ trợ liên tục</span>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <div class="login-header">
                <h2>Đăng Nhập</h2>
                <p>Vui lòng nhập thông tin đăng nhập của bạn</p>
            </div>

            <form id="loginForm" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Tên đăng nhập hoặc Email</label>
                    <input type="text" id="username" name="username" class="form-control"
                        placeholder="Nhập tên đăng nhập hoặc email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu"
                        required>
                    @if (session('error'))
                        <div class="error-message" style="color: red; margin-top: 5px;">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                {{--
                <div class="options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <a href="#" class="forgot-password">Quên mật khẩu?</a>
                </div>
                --}}

                <button type="submit" class="login-btn">Đăng Nhập</button>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('deactivated'))
            Swal.fire({
                icon: 'error',
                title: 'Tài khoản bị vô hiệu hóa',
                text: '{{ session('deactivated') }}',
                confirmButtonText: 'Đã hiểu',
                confirmButtonColor: '#d33'
            });
        @endif
    </script>
@endpush