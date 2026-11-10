document.addEventListener('DOMContentLoaded', function () {
    var tombolMenu = document.querySelector('.tombol-menu');
    var menu = document.querySelector('.menu-utama');

    if (tombolMenu && menu) {
        tombolMenu.addEventListener('click', function () {
            menu.classList.toggle('terbuka');
        });
    }

    // Toggle dropdown pada layar sentuh / mobile
    var dropdownItems = document.querySelectorAll('.menu-dropdown');
    dropdownItems.forEach(function (item) {
        var toggleBtn = item.querySelector('.dropdown-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function (e) {
                if (window.innerWidth <= 880) {
                    e.preventDefault();
                    item.classList.toggle('terbuka');
                }
            });
        }
    });

    // Tutup menu mobile saat tautan biasa (non-dropdown toggle) diklik
    document.querySelectorAll('.menu-utama a:not(.dropdown-toggle)').forEach(function (tautan) {
        tautan.addEventListener('click', function () {
            if (menu && menu.classList.contains('terbuka')) {
                menu.classList.remove('terbuka');
            }
        });
    });
});
