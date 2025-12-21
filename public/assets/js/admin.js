// // Xử lý chuyển tab
// document.querySelectorAll('.sidebar a').forEach(link => {
//     link.addEventListener('click', function (e) {
//         e.preventDefault();

//         // Xóa active class từ tất cả các tab và liên kết
//         document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
//         document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

//         // Thêm active class cho liên kết được click
//         this.classList.add('active');

//         // Hiển thị tab tương ứng
//         const tabId = this.getAttribute('data-tab');
//         document.getElementById(tabId).classList.add('active');

//         // Cập nhật tiêu đề trang
//         const pageTitle = document.getElementById('page-title');
//         pageTitle.textContent = this.textContent.trim();

//         // Đóng sidebar trên mobile
//         if (window.innerWidth < 992) {
//             document.getElementById('sidebar').classList.remove('active');
//             document.getElementById('mainContent').classList.remove('sidebar-open');
//         }
//     });
// });

// // Xử lý toggle sidebar trên mobile
// document.getElementById('sidebarToggle').addEventListener('click', function () {
//     const sidebar = document.getElementById('sidebar');
//     const mainContent = document.getElementById('mainContent');

//     sidebar.classList.toggle('active');
//     mainContent.classList.toggle('sidebar-open');
// });

// // Đóng sidebar khi click ra ngoài trên mobile
// document.addEventListener('click', function (event) {
//     const sidebar = document.getElementById('sidebar');
//     const sidebarToggle = document.getElementById('sidebarToggle');

//     if (window.innerWidth < 992 &&
//         !sidebar.contains(event.target) &&
//         !sidebarToggle.contains(event.target) &&
//         sidebar.classList.contains('active')) {
//         sidebar.classList.remove('active');
//         document.getElementById('mainContent').classList.remove('sidebar-open');
//     }
// });

// // Khởi tạo biểu đồ nhân sự
// const employeeCtx = document.getElementById('employeeChart').getContext('2d');
// const employeeChart = new Chart(employeeCtx, {
//     type: 'bar',
//     data: {
//         labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
//         datasets: [{
//             label: 'Số nhân viên',
//             data: [110, 115, 118, 120, 122, 125],
//             backgroundColor: 'rgba(52, 152, 219, 0.7)',
//             borderColor: 'rgba(52, 152, 219, 1)',
//             borderWidth: 1
//         }]
//     },
//     options: {
//         responsive: true,
//         maintainAspectRatio: false,
//         scales: {
//             y: {
//                 beginAtZero: true
//             }
//         }
//     }
// });

// // Khởi tạo biểu đồ chi phí sửa xe
// const repairCostCtx = document.getElementById('repairCostChart').getContext('2d');
// const repairCostChart = new Chart(repairCostCtx, {
//     type: 'line',
//     data: {
//         labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
//         datasets: [{
//             label: 'Chi phí sửa xe (triệu VNĐ)',
//             data: [8.5, 9.2, 10.1, 11.5, 12.2, 12.5],
//             backgroundColor: 'rgba(231, 76, 60, 0.2)',
//             borderColor: 'rgba(231, 76, 60, 1)',
//             borderWidth: 2,
//             tension: 0.3,
//             fill: true
//         }]
//     },
//     options: {
//         responsive: true,
//         maintainAspectRatio: false,
//         scales: {
//             y: {
//                 beginAtZero: true
//             }
//         }
//     }
// });

// // Xử lý Carousel Thêm Nhân Viên
// const employeeCarousel = new bootstrap.Carousel(document.getElementById('employeeCarousel'), {
//     interval: false
// });

// const employeeCarouselElement = document.getElementById('employeeCarousel');
// const prevBtn = document.getElementById('prevBtn');
// const nextBtn = document.getElementById('nextBtn');

// // Cập nhật trạng thái nút và chỉ bước
// employeeCarouselElement.addEventListener('slid.bs.carousel', function () {
//     const activeIndex = Array.from(this.querySelectorAll('.carousel-item')).indexOf(this.querySelector('.carousel-item.active'));

//     // Cập nhật chỉ bước
//     document.querySelectorAll('#employeeCarousel .step').forEach((step, index) => {
//         if (index === activeIndex) {
//             step.classList.add('active');
//         } else {
//             step.classList.remove('active');
//         }
//     });

//     // Cập nhật nút
//     if (activeIndex === 0) {
//         prevBtn.style.visibility = 'hidden';
//     } else {
//         prevBtn.style.visibility = 'visible';
//     }

//     if (activeIndex === 2) {
//         nextBtn.textContent = 'Hoàn tất';
//     } else {
//         nextBtn.textContent = 'Tiếp theo';
//     }
// });

// Xử lý nút tiếp theo
document.addEventListener('DOMContentLoaded', () => {
    const steps = document.querySelectorAll('.form-step');
    const indicators = document.querySelectorAll('.step');
    const nextBtn = document.getElementById('nextStepBtn');
    const prevBtn = document.getElementById('prevStepBtn');
    const submitBtn = document.getElementById('submitEmployeeBtn');

    let currentStep = 1;

    function showStep(step) {
        // Ẩn tất cả step
        steps.forEach(s => s.classList.remove('active'));
        indicators.forEach(i => i.classList.remove('active'));

        // Hiện step hiện tại
        document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
        document.querySelector(`.step[data-step="${step}"]`).classList.add('active');

        // Ẩn/hiện nút
        prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        nextBtn.style.display = step === steps.length ? 'none' : 'inline-block';
        submitBtn.style.display = step === steps.length ? 'inline-block' : 'none';
    }

    // Nút tiếp theo
    nextBtn.addEventListener('click', () => {
        if (currentStep < steps.length) {
            currentStep++;
            showStep(currentStep);
        }
    });

    // Nút quay lại
    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Khi mở modal lại thì reset về bước 1
    const addModal = document.getElementById('addEmployeeModal');
    addModal.addEventListener('hidden.bs.modal', () => {
        currentStep = 1;
        showStep(currentStep);
        document.getElementById('addEmployeeForm').reset();
    });

    showStep(currentStep);
});

// Hàm kiểm tra tính hợp lệ của form nhân viên
function validateEmployeeForm() {
    // Kiểm tra từng bước
    const name = document.getElementById('employeeRealName').value;
    const email = document.getElementById('employeeEmail').value;
    const position = document.getElementById('employeePosition').value;
    const role = document.getElementById('employeeRole').value;
    const username = document.getElementById('employeeUsername').value;
    const password = document.getElementById('employeePassword').value;
    const confirmPassword = document.getElementById('employeeConfirmPassword').value;

    if (!name || !email) {
        alert('Vui lòng điền đầy đủ thông tin cá nhân');
        employeeCarousel.to(0);
        return false;
    }

    if (!position || !role) {
        alert('Vui lòng chọn chức vụ và quyền');
        employeeCarousel.to(1);
        return false;
    }

    if (!username || !password || !confirmPassword) {
        alert('Vui lòng điền đầy đủ thông tin tài khoản');
        employeeCarousel.to(2);
        return false;
    }

    if (password !== confirmPassword) {
        alert('Mật khẩu xác nhận không khớp');
        employeeCarousel.to(2);
        return false;
    }

    return true;
}

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


// // Xử lý Carousel Thêm Chức Vụ
// const positionCarousel = new bootstrap.Carousel(document.getElementById('positionCarousel'), {
//     interval: false
// });

// const positionCarouselElement = document.getElementById('positionCarousel');
// const positionPrevBtn = document.getElementById('positionPrevBtn');
// const positionNextBtn = document.getElementById('positionNextBtn');

// // Cập nhật trạng thái nút và chỉ bước
// positionCarouselElement.addEventListener('slid.bs.carousel', function () {
//     const activeIndex = Array.from(this.querySelectorAll('.carousel-item')).indexOf(this.querySelector('.carousel-item.active'));

//     // Cập nhật chỉ bước
//     document.querySelectorAll('#positionCarousel .step').forEach((step, index) => {
//         if (index === activeIndex) {
//             step.classList.add('active');
//         } else {
//             step.classList.remove('active');
//         }
//     });

//     // Cập nhật nút
//     if (activeIndex === 0) {
//         positionPrevBtn.style.visibility = 'hidden';
//     } else {
//         positionPrevBtn.style.visibility = 'visible';
//     }

//     if (activeIndex === 1) {
//         positionNextBtn.textContent = 'Lưu chức vụ';
//         updatePositionConfirmation();
//     } else {
//         positionNextBtn.textContent = 'Tiếp theo';
//     }
// });

// // Xử lý nút tiếp theo
// positionNextBtn.addEventListener('click', function () {
//     const activeIndex = Array.from(positionCarouselElement.querySelectorAll('.carousel-item')).indexOf(positionCarouselElement.querySelector('.carousel-item.active'));

//     if (activeIndex === 1) {
//         const formValid = validatePositionForm();
//         if (formValid) {
//             alert('Thêm chức vụ thành công!');
//             // Ở đây sẽ có code để gửi dữ liệu đến server
//             // Sau đó đóng modal
//             const modal = bootstrap.Modal.getInstance(document.getElementById('addPositionModal'));
//             modal.hide();
//         }
//     } else {
//         positionCarousel.next();
//     }
// });

// // Xử lý nút quay lại
// positionPrevBtn.addEventListener('click', function () {
//     positionCarousel.prev();
// });

// // Cập nhật thông tin xác nhận chức vụ
// function updatePositionConfirmation() {
//     document.getElementById('confirmPositionName').textContent = document.getElementById('positionName').value || '-';
//     document.getElementById('confirmPositionCode').textContent = document.getElementById('positionCode').value || '-';
//     const levelSelect = document.getElementById('positionLevel');
//     document.getElementById('confirmPositionLevel').textContent = levelSelect.options[levelSelect.selectedIndex].text || '-';
//     document.getElementById('confirmPositionDescription').textContent = document.getElementById('positionDescription').value || '-';
// }

// // Kiểm tra tính hợp lệ của form chức vụ
// function validatePositionForm() {
//     const name = document.getElementById('positionName').value;
//     const code = document.getElementById('positionCode').value;
//     const level = document.getElementById('positionLevel').value;
//     const agreement = document.getElementById('confirmAgreement').checked;

//     if (!name || !code || !level) {
//         alert('Vui lòng điền đầy đủ thông tin chức vụ');
//         positionCarousel.to(0);
//         return false;
//     }

//     if (!agreement) {
//         alert('Vui lòng xác nhận thông tin trước khi lưu');
//         return false;
//     }

//     return true;
// }

// // Hàm để loại bỏ dấu tiếng Việt
// function removeVietnameseTones(str) {
//     var vietnamese = {
//         'á': 'a', 'à': 'a', 'ả': 'a', 'ã': 'a', 'ạ': 'a', 'ă': 'a', 'ắ': 'a', 'ằ': 'a', 'ẳ': 'a', 'ẵ': 'a', 'ặ': 'a',
//         'â': 'a', 'ấ': 'a', 'ầ': 'a', 'ẩ': 'a', 'ẫ': 'a', 'ậ': 'a', 'é': 'e', 'è': 'e', 'ẻ': 'e', 'ẽ': 'e', 'ẹ': 'e',
//         'ê': 'e', 'ế': 'e', 'ề': 'e', 'ể': 'e', 'ễ': 'e', 'ệ': 'e', 'í': 'i', 'ì': 'i', 'ỉ': 'i', 'ĩ': 'i', 'ị': 'i',
//         'ó': 'o', 'ò': 'o', 'ỏ': 'o', 'õ': 'o', 'ọ': 'o', 'ô': 'o', 'ố': 'o', 'ồ': 'o', 'ổ': 'o', 'ỗ': 'o', 'ộ': 'o',
//         'ơ': 'o', 'ớ': 'o', 'ờ': 'o', 'ở': 'o', 'ỡ': 'o', 'ợ': 'o', 'ú': 'u', 'ù': 'u', 'ủ': 'u', 'ũ': 'u', 'ụ': 'u',
//         'ư': 'u', 'ứ': 'u', 'ừ': 'u', 'ử': 'u', 'ữ': 'u', 'ự': 'u', 'ý': 'y', 'ỳ': 'y', 'ỷ': 'y', 'ỹ': 'y', 'ỵ': 'y',
//         'đ': 'd', 'Đ': 'd'
//     };

//     return str.split('').map(function (char) {
//         return vietnamese[char] || char;
//     }).join('');
// }

// // Hàm để tạo mã chức vụ từ tên chức vụ
// function generatePositionCode() {
//     const positionName = document.getElementById('positionName').value;
//     if (positionName) {
//         // Loại bỏ dấu tiếng Việt và chuyển thành chữ thường, thay thế khoảng trắng thành dấu gạch dưới
//         const code = removeVietnameseTones(positionName).toLowerCase().replace(/\s+/g, '_').replace(/[^\w\s_]/g, '');
//         document.getElementById('positionCode').value = code;  // Cập nhật mã chức vụ
//     }
// }

// // Lắng nghe sự kiện thay đổi trên trường "Tên chức vụ"
// document.getElementById('positionName').addEventListener('input', generatePositionCode);

// // Ẩn nút quay lại ở bước đầu tiên
// prevBtn.style.visibility = 'hidden';
// positionPrevBtn.style.visibility = 'hidden';

// // Xử lý thay đổi kích thước màn hình
// window.addEventListener('resize', function () {
//     if (window.innerWidth >= 992) {
//         document.getElementById('sidebar').classList.add('active');
//         document.getElementById('mainContent').classList.add('sidebar-open');
//     } else {
//         document.getElementById('sidebar').classList.remove('active');
//         document.getElementById('mainContent').classList.remove('sidebar-open');
//     }
// });

// // Khởi tạo trạng thái ban đầu
// if (window.innerWidth >= 992) {
//     document.getElementById('sidebar').classList.add('active');
//     document.getElementById('mainContent').classList.add('sidebar-open');
// }