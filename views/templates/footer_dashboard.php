 </div> <!-- Penutup container-fluid -->
 </div>

 <!-- JavaScript Libraries -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

 <script>
// Mobile sidebar toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const body = document.body;

    // Toggle sidebar
    function toggleSidebar() {
        if (sidebar && sidebarOverlay) {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
            body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }
    }

    // Event listeners
    if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

    // Close sidebar when clicking on a link (mobile)
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                toggleSidebar();
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            if (sidebar) sidebar.classList.remove('show');
            if (sidebarOverlay) sidebarOverlay.classList.remove('show');
            body.style.overflow = '';
        }
    });

    // Initialize DataTables jika ada
    const tables = document.querySelectorAll('table[data-datatables="true"]');
    tables.forEach(table => {
        if ($.fn.DataTable.isDataTable(table)) return;
        $(table).DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            },
            order: [
                [0, 'desc']
            ]
        });
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Global helper functions
function confirmAction(message) {
    return confirm(message || 'Anda yakin ingin melanjutkan?');
}

function showLoading(button) {
    if (button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        button.disabled = true;
        return originalText;
    }
    return null;
}

function hideLoading(button, originalText) {
    if (button && originalText) {
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

// Form validation helpers
function validateRequiredFields(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Flash messages auto-dismiss
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 500);
    });
}, 5000);

// Chart.js global configuration
if (typeof Chart !== 'undefined') {
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
    Chart.defaults.borderColor = 'rgba(0, 0, 0, 0.05)';
}

// Mobile-specific enhancements
if (window.innerWidth <= 768) {
    // Better touch handling for buttons
    document.addEventListener('touchstart', function(e) {
        if (e.target.closest('.btn') || e.target.closest('.nav-link')) {
            e.target.style.transform = 'scale(0.98)';
        }
    }, {
        passive: true
    });

    document.addEventListener('touchend', function(e) {
        if (e.target.closest('.btn') || e.target.closest('.nav-link')) {
            e.target.style.transform = '';
        }
    }, {
        passive: true
    });

    // Prevent zoom on input focus for iOS
    document.addEventListener('focus', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
            document.body.style.zoom = "100%";
        }
    }, true);

    document.addEventListener('blur', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
            setTimeout(function() {
                document.body.style.zoom = "";
            }, 100);
        }
    }, true);
}
 </script>
 </body>

 </html>