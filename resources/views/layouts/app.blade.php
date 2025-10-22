<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>F10 Auto Repair - @yield('title', 'Trang Nhân Viên')</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/f10-auto-repair-logo.png') }}" type="image/x-icon">

    @if (!View::hasSection('hide_bootstrap'))
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('assets/bootstrap-5.3.7-dist/css/bootstrap.min.css') }}">
    @endif
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/fontawesome-6.5.0/css/all.min.css') }}">
    @if (!View::hasSection('hide_main_css'))
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    @endif
    @stack('styles')
</head>

<body>
    {{-- Nabar (Optional)--}}
    @if (!View::hasSection('hide_navbar'))
        @include('partials.navbar')
    @endif

    {{-- Main Content --}}
    <div class="container my-4">
        @yield('content')
    </div>

    {{-- Footer (Optional)--}}
    @if (!View::hasSection('hide_footer'))
        @include('partials.footer')
    @endif

    {{-- Hiển thị thông báo --}}
    <div class="notifications">
        <span id="session-success" data-message="{{ session('success') }}"></span>
        <span id="session-warning" data-message="{{ session('warning') }}"></span>
        <span id="session-info" data-message="{{ session('info') }}"></span>
        <span id="session-error" data-message="{{ session('error') }}"></span>
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="{{ asset('assets/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('session-success').dataset.message;
            const warningMessage = document.getElementById('session-warning').dataset.message;
            const errorMessage = document.getElementById('session-error').dataset.message;

            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: successMessage,
                    confirmButtonColor: '#28a745'
                });
            }

            if (warningMessage) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cảnh báo',
                    text: warningMessage,
                    confirmButtonColor: '#ffc107'
                });
            }

            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: errorMessage,
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    </script>
</body>

</html>