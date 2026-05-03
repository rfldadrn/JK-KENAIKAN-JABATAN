</main>
    
    <!-- Footer -->
    <footer class="footer" style="margin-left: var(--sidebar-width); padding: 20px; text-align: center; background: white; border-top: 1px solid #e0e0e0;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <p class="mb-0 text-muted">
                        &copy; <?= date('Y') ?> <?= APP_NAME ?> - Bank Rakyat Indonesia Wilayah Padang. All rights reserved.
                    </p>
                    <p class="mb-0 text-muted small">
                        Version <?= APP_VERSION ?> | Powered by PHP Native MVC
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom JS -->
    <script>
    jQuery(document).ready(function($) {
        // Sidebar toggle for mobile
        $('#sidebar-toggle').on('click', function() {
            $('#sidebar').toggleClass('show');
        });
        
        // Close sidebar when clicking outside on mobile
        $(document).on('click', function(e) {
            if ($(window).width() < 768) {
                if (!$(e.target).closest('#sidebar, #sidebar-toggle').length) {
                    $('#sidebar').removeClass('show');
                }
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Confirm delete actions
        $('.btn-delete, .delete-btn').on('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Session timeout check (every 5 minutes)
        setInterval(function() {
            $.ajax({
                url: '<?= BASE_URL ?>/auth/checkSession',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'expired') {
                        alert('Session Anda telah berakhir. Silakan login kembali.');
                        window.location.href = '<?= BASE_URL ?>/auth/login';
                    }
                }
            });
        }, 300000); // 5 minutes
        
        // DataTables default configuration
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 10,
            responsive: true
        });
    });
    </script>
    
    <!-- DataTables filter for Data Pekerja -->
    <?php if (isset($currentPage) && $currentPage === 'pekerja'): ?>
        <script>
            if (typeof jQuery !== 'undefined') {
                jQuery(document).ready(function($) {
                    // Initialize DataTable
                    var table = $('#pekerjaTable').DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                        },
                        "pageLength": 25,
                        "order": [[2, 'asc']]
                    });
                    // Function to apply filters
                    function applyFilters() {
                        var filterDivisi = $('#filterDivisi').val();
                        var filterJabatan = $('#filterJabatan').val();
                        var filterGolongan = $('#filterGolongan').val();
                        var filterStatus = $('#filterStatus').val();
                        $('#pekerjaTable tbody tr').each(function() {
                            var $row = $(this);
                            var rowDivisi = $row.data('divisi') || '';
                            var rowJabatan = $row.data('jabatan') || '';
                            var rowGolongan = $row.data('golongan') || '';
                            var rowStatus = $row.data('status') || '';
                            var showRow = true;
                            if (filterDivisi && rowDivisi !== filterDivisi) showRow = false;
                            if (filterJabatan && rowJabatan !== filterJabatan) showRow = false;
                            if (filterGolongan && rowGolongan !== filterGolongan) showRow = false;
                            if (filterStatus && rowStatus !== filterStatus) showRow = false;
                            if (showRow) { $row.show(); } else { $row.hide(); }
                        });
                        table.draw(false);
                    }
                    $('#filterDivisi, #filterJabatan, #filterGolongan, #filterStatus').on('change', function() {
                        applyFilters();
                    });
                    $('#btnResetFilter').on('click', function() {
                        $('#filterDivisi, #filterJabatan, #filterGolongan, #filterStatus').val('');
                        $('#pekerjaTable tbody tr').show();
                        table.draw(false);
                    });
                });
            } else {
                console.error('jQuery not loaded! Cannot initialize filters.');
            }
        </script>
    <?php endif; ?>
    
    <?php if (isset($additionalJS)): ?>
        <?= $additionalJS ?>
    <?php endif; ?>
</body>
</html>
