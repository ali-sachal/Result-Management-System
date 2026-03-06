/* ============================================================
   Custom JS - Result Management System
   Counters, Utilities, Charts helpers
   ============================================================ */

/* ---- CSRF Token Setup for AJAX ----------------------------- */
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': csrfToken }
});

/* ---- Animated Number Counter ------------------------------ */
function animateCounter(el, target, duration = 1500) {
    let start = 0;
    const step = Math.ceil(target / (duration / 16));
    const timer = setInterval(() => {
        start += step;
        if (start >= target) {
            el.textContent = target.toLocaleString();
            clearInterval(timer);
        } else {
            el.textContent = start.toLocaleString();
        }
    }, 16);
}

function initCounters() {
    document.querySelectorAll('[data-counter]').forEach(el => {
        const target = parseInt(el.getAttribute('data-counter'), 10) || 0;
        animateCounter(el, target);
    });
}

/* ---- SweetAlert helpers ----------------------------------- */
function showSuccess(msg) {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: msg,
        timer: 2500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
        timerProgressBar: true,
    });
}

function showError(msg) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: msg,
        confirmButtonColor: '#6366f1',
    });
}

function showWarning(msg) {
    Swal.fire({
        icon: 'warning',
        title: 'Warning',
        text: msg,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
    });
}

/* ---- Sidebar Toggle --------------------------------------- */
function initSidebar() {
    // Only select the hamburger button and sidebar close button — NOT the dark mode toggle
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebarCloseBtn = document.querySelectorAll('.sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content');

    if (!sidebar) return;

    function isMobile() {
        return window.innerWidth < 992;
    }

    function openMobileSidebar() {
        sidebar.classList.add('show');
        if (overlay) overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileSidebar() {
        sidebar.classList.remove('show');
        if (overlay) overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    function toggleDesktopSidebar() {
        sidebar.classList.toggle('collapsed');
        if (mainContent) mainContent.classList.toggle('sidebar-collapsed');
        // Persist state
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('rms-sidebar-collapsed', isCollapsed ? '1' : '0');
    }

    // Restore desktop collapse state
    if (!isMobile()) {
        const savedCollapsed = localStorage.getItem('rms-sidebar-collapsed');
        if (savedCollapsed === '1') {
            sidebar.classList.add('collapsed');
            if (mainContent) mainContent.classList.add('sidebar-collapsed');
        }
    }

    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (isMobile()) {
                openMobileSidebar();
            } else {
                toggleDesktopSidebar();
            }
        });
    }

    // Close button inside sidebar (mobile only)
    sidebarCloseBtn.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            closeMobileSidebar();
        });
    });

    // Overlay click closes sidebar on mobile
    if (overlay) {
        overlay.addEventListener('click', () => {
            closeMobileSidebar();
        });
    }

    // On resize, clean up mobile state
    window.addEventListener('resize', () => {
        if (!isMobile()) {
            closeMobileSidebar();
        }
    });
}

/* ---- Page Loader ------------------------------------------ */
function initLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) {
        window.addEventListener('load', () => {
            loader.style.opacity = '0';
            setTimeout(() => loader.remove(), 400);
        });
    }
}

/* ---- Active nav highlighting ------------------------------ */
function highlightActiveNav() {
    const path = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.getAttribute('href') && path.startsWith(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });
}

/* ---- Auto-dismiss alerts ---------------------------------- */
function autoDismissAlerts() {
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 4000);
    });
}

/* ---- Dark mode toggle ------------------------------------- */
function initDarkMode() {
    const toggle = document.getElementById('darkModeToggle');
    const current = localStorage.getItem('rms-theme') || 'light';

    // Apply saved theme immediately
    applyTheme(current, toggle);

    if (toggle) {
        toggle.addEventListener('click', (e) => {
            e.stopPropagation(); // prevent any bubbling
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            applyTheme(newTheme, toggle);
            localStorage.setItem('rms-theme', newTheme);
        });
    }
}

function applyTheme(theme, toggleEl) {
    document.documentElement.setAttribute('data-theme', theme);
    if (toggleEl) {
        const icon = toggleEl.querySelector('i');
        if (icon) {
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        toggleEl.title = theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode';
    }
}

/* ---- On DOM Ready ----------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    initCounters();
    initSidebar();
    initLoader();
    autoDismissAlerts();
    initDarkMode();

    // AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 800, offset: 80, once: true, easing: 'ease-out-cubic' });
    }
});