// Xử lý menu mobile
document.querySelector('.mobile-menu-toggle').addEventListener('click', function () {
    document.querySelector('.nav-pills').classList.toggle('show');
});

// Khởi tạo biểu đồ thống kê cá nhân
const personalStatsCtx = document.getElementById('personalStatsChart').getContext('2d');
const personalStatsChart = new Chart(personalStatsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Đã hoàn thành', 'Đang xử lý', 'Chờ duyệt'],
        datasets: [{
            data: [18, 4, 2],
            backgroundColor: [
                '#2a9d8f',
                '#e9c46a',
                '#ff5252'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Khởi tạo biểu đồ hiệu suất
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
        datasets: [{
            label: 'Hóa đơn hoàn thành',
            data: [5, 7, 4, 6],
            backgroundColor: 'rgba(198, 40, 40, 0.7)',
            borderColor: 'rgba(198, 40, 40, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Xử lý form thêm hóa đơn
document.querySelector('#addBillModal .btn-primary').addEventListener('click', function () {
    const form = document.getElementById('billForm');
    if (form.checkValidity()) {
        alert('Thêm hóa đơn thành công!');
        // Ở đây sẽ có code để gửi dữ liệu đến server
        // Sau đó đóng modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addBillModal'));
        modal.hide();
    } else {
        form.reportValidity();
    }
});

// Xử lý form chỉnh sửa hồ sơ
document.querySelector('#profileModal .btn-primary').addEventListener('click', function () {
    alert('Cập nhật hồ sơ thành công!');
    // Ở đây sẽ có code để gửi dữ liệu đến server
    // Sau đó đóng modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('profileModal'));
    modal.hide();
});

// Thêm hiệu ứng fade-in khi chuyển tab
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function () {
        const tabId = this.getAttribute('data-tab');
        const tabContent = document.getElementById(tabId);
        tabContent.classList.remove('fade-in');
        void tabContent.offsetWidth; // Trigger reflow
        tabContent.classList.add('fade-in');
    });
});

// Đóng menu mobile khi click ra ngoài
document.addEventListener('click', function (e) {
    if (!e.target.closest('.main-nav') && window.innerWidth <= 768) {
        document.querySelector('.nav-pills').classList.remove('show');
    }
});

// Xử lý hiển thị mật khẩu
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.toggle-password');
    if (!btn) return;

    const wrapper = btn.closest('.password-wrapper');
    const input = wrapper.querySelector('input[type="password"], input[type="text"]');

    if (!input) return;

    const isHidden = input.type === 'password';

    if (isHidden) {
        input.type = 'text';
        btn.setAttribute('aria-pressed', 'true');
        btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        btn.setAttribute('aria-pressed', 'false');
        btn.innerHTML = '<i class="fas fa-eye"></i>';
    }
});