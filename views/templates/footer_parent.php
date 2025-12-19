        </div> <!-- Penutup container-fluid -->
        </div> <!-- Penutup main-content -->

        <!-- Floating Action Button (opsional) -->
        <button class="fab" id="fabButton">
            <i class="fas fa-plus"></i>
        </button>

        <!-- JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

        <script>
// Sidebar Toggle for Mobile
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
});

document.getElementById('sidebarOverlay').addEventListener('click', function() {
    document.getElementById('sidebar').classList.remove('show');
    this.classList.remove('show');
});

// Close sidebar when clicking a link on mobile
document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth < 992) {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }
    });
});

// FAB Button Action
document.getElementById('fabButton')?.addEventListener('click', function() {
    // Tambah aksi FAB di sini
    // window.location.href = '<?= BASEURL; ?>/parent/create';
});

// Initialize DataTables if table exists
$(document).ready(function() {
    if ($('.datatable').length) {
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            },
            pageLength: 10,
            responsive: true
        });
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    if (window.innerWidth >= 992) {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }
});
        </script>

        <?php if (isset($data['js'])): ?>
        <?php foreach ($data['js'] as $js): ?>
        <script src="<?= $js; ?>"></script>
        <?php endforeach; ?>
        <?php endif; ?>
        </body>

        </html>