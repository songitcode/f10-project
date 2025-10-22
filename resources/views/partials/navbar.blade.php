@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
@endpush

@auth
    <!-- <! Header > -->
    <header class="main-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <img src="{{ asset('assets/images/f10-auto-repair-logo.png') }}" alt="F10 Auto Repair Logo">
                    <h4>F10 Auto Repair</h4>
                </div>
                <div class="user-info">
                    <span>Xin chào, {{ Auth::user()->position->name ?? '' }}
                        <strong>{{ Auth::user()->real_name ?? '-' }}</strong></span>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->real_name) }}&background=random"
                        class="rounded-circle me-2" alt="{{ Auth::user()->real_name }}" width="50" height="50">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i
                                        class="fas fa-user me-2"></i>Hồ sơ</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- <! Navigation > -->
    <nav class="main-nav">
        <div class="container">
            <button class="mobile-menu-toggle d-md-none w-100 text-start p-3">
                <i class="fas fa-bars me-2"></i>Menu
            </button>
            <ul class="nav nav-pills justify-content-center">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}"
                        data-tab="home">
                        <i class="fas fa-home me-2"></i>Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('bills*') ? 'active' : '' }}" href="{{ route('bills.index') }}">
                        <i class="fas fa-file-invoice me-2"></i>Hóa đơn
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('profile') ? 'active' : '' }}" href="{{ route(name: 'profile') }}">
                        <i class="fas fa-user me-2"></i>Hồ sơ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('profile') ? 'active' : '' }}" href="{{ route(name: 'profile') }}">
                        <i class="fas fa-user me-2"></i>Lịch làm việc
                    </a>
                </li>
                {{--
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('history') ? 'active' : '' }}" href="{{ route('history') }}">
                        <i class="fas fa-history me-2"></i>Lịch sử
                    </a>
                </li>
                --}}
                <!-- Quản Lý -->
                @if (Auth::user()->isManager())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-cogs me-2"></i>Quản lý
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    <script>
        // // Xử lý chuyển tab (nếu không sử dụng Laravel routing)
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                // Xóa active class từ tất cả các tab và liên kết
                document.querySelectorAll('.nav-link').forEach(a => a.classList.remove('active'));

                // Thêm active class cho liên kết được click
                this.classList.add('active');

                // Đóng menu mobile sau khi chọn tab
                if (window.innerWidth <= 768) {
                    document.querySelector('.nav-pills').classList.remove('show');
                }
            });
        });

        // Xử lý menu mobile
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function () {
            document.querySelector('.nav-pills').classList.toggle('show');
        });

        // Đóng menu mobile khi click ra ngoài
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.main-nav') && window.innerWidth <= 768) {
                document.querySelector('.nav-pills').classList.remove('show');
            }
        });
    </script>
@else
    {{-- <a href="{{ route('login') }}"></a> --}}
@endauth