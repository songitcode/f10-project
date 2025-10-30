<link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">

@auth
    <header class="main-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('home') }}" class="nav-link-custom">
                    <div class="logo">
                        <img src="{{ asset('assets/images/f10-auto-repair-logo.png') }}" alt="F10 Auto Repair Logo">
                        <h4 class="alfa-slab-one-regular" style="height: 20px">F10 Auto Repair</h4>
                    </div>
                </a>
                <div class="user-info">
                    <span>Xin chào, {{ Auth::user()->position->name ?? '' }}
                        <strong>{{ Auth::user()->real_name ?? '-' }}</strong></span>
                    <a href="{{ route('profile') }}">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->real_name) }}&background=random"
                            class="rounded-circle me-2" alt="{{ Auth::user()->real_name }}" width="50" height="50">
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/profile"><i class="fas fa-user me-2"></i>Hồ sơ</a></li>
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
    @php
        $users = \App\Models\User::where('position_id', 1)->take(1)->get();
    @endphp
    <div class="nav-info">
        <div class="container">
            @foreach ($users as $user)
                <span><i class="fa-brands fa-discord"></i>
                    {{ $user->position->name ?? 'admin' }}:
                    <strong> {{ $user->momo ?? 0000 }} |HH| {{ $user->real_name ?? 'admin'}} |F10</strong></span>
            @endforeach
        </div>
    </div>
    <!-- <! Navigation > -->
    <nav class="main-nav bg-white shadow-sm border-bottom py-2">
        <div class="container box-nav-menu justify-content-md-start">
            <button class="mobile-menu-toggle d-md-none w-100 text-start btn btn-light border-0 px-3 py-2 text-primary"
                type="button" data-bs-toggle="collapse" data-bs-target="#mainMenuContent" aria-controls="mainMenuContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars me-2"></i>MENU
            </button>

            <div class="collapse navbar-collapse d-md-flex justify-content-md-center" id="mainMenuContent">
                <ul class="nav nav-pills main-nav-list flex-column flex-md-row">
                    <li class="nav-item-custom">
                        <a class="nav-link nav-link-hover-cus {{ Request::is('home') ? 'nav-active-custom' : '' }}"
                            href="{{ route('home') }}" data-tab="home">
                            <i class="fas fa-home me-2"></i>Trang chủ
                        </a>
                    </li>

                    <li class="nav-item-custom">
                        <a class="nav-link nav-link-hover-cus {{ Request::is('bills*') ? 'nav-active-custom' : '' }}"
                            href="{{ route('bills.index') }}">
                            <i class="fas fa-file-invoice me-2"></i>Hóa đơn
                        </a>
                    </li>

                    <li class="nav-item-custom">
                        <a class="nav-link nav-link-hover-cus {{ Request::is('profile') ? 'nav-active-custom' : '' }}"
                            href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i>Hồ sơ
                        </a>
                    </li>

                    <li class="nav-item-custom">
                        <a class="nav-link nav-link-hover-cus {{ Request::is('work-schedule') ? 'nav-active-custom' : '' }}"
                            href="{{ route('work-schedule') }}">
                            <i class="fa-solid fa-calendar-week me-2"></i>Lịch làm việc
                        </a>
                    </li>

                    @if (Auth::user()->isManager())
                        <li class="nav-item-custom">
                            <a class="nav-link nav-link-hover-cus nav-link-admin {{ Request::is('admin*') ? 'active-admin' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-cogs me-2"></i>Quản lý
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <nav class="main-nav-custom py-2">
        <div class="container box-nav-menu justify-content-md-start">
            <button class="mobile-menu-toggle d-md-none w-100 text-start btn btn-light border-0 px-3 py-2 text-primary"
                type="button" data-bs-toggle="collapse" data-bs-target="#mainMenuContent" aria-controls="mainMenuContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars me-2"></i>MENU
            </button>

            <div class="collapse navbar-collapse d-md-flex justify-content-md-center" id="mainMenuContent">
                <ul class="nav nav-pills main-nav-list flex-column flex-md-row">
                    <li class="nav-item-custom">
                        <a class="nav-link nav-link-hover-cus {{ Request::is('home') ? 'nav-active-custom' : '' }}"
                            href="{{ route('home') }}" data-tab="home">
                            <i class="fas fa-home me-2"></i>Trang chủ
                        </a>
                    </li>

                    <li class="nav-item-custom">
                        <a class="nav-link nav-link-hover-cus {{ Request::is('bills*') ? 'nav-active-custom' : '' }}"
                            href="{{ route('bills.index') }}">
                            <i class="fas fa-file-invoice me-2"></i>Hóa đơn
                        </a>
                    </li>

                    <li class="nav-item-custom">
                        <a class="nav-link nav-link-hover-cus {{ Request::is('profile') ? 'nav-active-custom' : '' }}"
                            href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i>Hồ sơ
                        </a>
                    </li>

                    <li class="nav-item-custom">
                        <a class="nav-link nav-link-hover-cus {{ Request::is('work-schedule') ? 'nav-active-custom' : '' }}"
                            href="{{ route('work-schedule') }}">
                            <i class="fa-solid fa-calendar-week me-2"></i>Lịch làm việc
                        </a>
                    </li>

                    @if (Auth::user()->isManager())
                        <li class="nav-item-custom">
                            <a class="nav-link nav-link-hover-cus nav-link-admin {{ Request::is('admin*') ? 'active-admin' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-cogs me-2"></i>Quản lý
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
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
        const toggleButton = document.querySelector('.mobile-menu-toggle');
        const menu = document.getElementById('mainMenuContent');

        toggleButton.addEventListener('click', () => {
            menu.classList.toggle('show');
        });

        // Đóng menu khi click ra ngoài
        document.addEventListener('click', e => {
            if (!e.target.closest('.main-header') && !e.target.closest('.main-nav')) {
                menu.classList.remove('show');
            }
        });

    </script>
@else
    {{-- <a href="{{ route('login') }}"></a> --}}
@endauth