document.addEventListener('DOMContentLoaded', function() {
    const dropdownBtn = document.querySelector('#categoryDropdown');
    const dropdownMenu = document.querySelector('#categoryDropdownMenu');

    // Hiển thị/ẩn menu khi nhấp vào nút
    dropdownBtn.addEventListener('click', function(e) {
        e.preventDefault();
        dropdownMenu.classList.toggle('show');
    });

    // Đóng menu khi nhấp ra ngoài
    document.addEventListener('click', function(e) {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nhấp vào nút Đăng nhập (nếu cần)
    const loginLink = document.querySelector('.nav-link[href="/webbanhang/account"]');
    if (loginLink) {
        loginLink.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '/webbanhang/account';
        });
    }

    // Xử lý dropdown tài khoản
    const userDropdownBtn = document.querySelector('#userDropdown');
    const userDropdownMenu = document.querySelector('.dropdown-menu[aria-labelledby="userDropdown"]');
    if (userDropdownBtn && userDropdownMenu) {
        userDropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            userDropdownMenu.classList.toggle('show');
        });

        // Đóng dropdown khi nhấp ra ngoài
        document.addEventListener('click', function(e) {
            if (!userDropdownBtn.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                userDropdownMenu.classList.remove('show');
            }
        });
    }
});