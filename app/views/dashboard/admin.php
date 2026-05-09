<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-home me-2"></i>Dashboard Admin</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <span class="text-muted">
                <i class="fas fa-calendar me-2"></i><?= Helper::formatDate(date('Y-m-d')) ?>
            </span>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="icon bg-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-number"><?= $stats['total_pekerja'] ?></div>
            <div class="stats-label">Total Pekerja Aktif</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="icon bg-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-number"><?= $stats['pending_pengajuan'] ?></div>
            <div class="stats-label">Pengajuan Pending</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stats-number"><?= $stats['approved_tahun_ini'] ?></div>
            <div class="stats-label">Disetujui Tahun Ini</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="icon bg-info">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stats-number"><?= $stats['total_pengajuan'] ?></div>
            <div class="stats-label">Total Pengajuan</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Pengajuan -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Pengajuan Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>NIP/Nama</th>
                                <th>Golongan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentPengajuan)): ?>
                                <?php foreach ($recentPengajuan as $row): ?>
                                    <tr>
                                        <td><?= Helper::escape($row->nomor_pengajuan) ?></td>
                                        <td>
                                            <strong><?= Helper::escape($row->nama_lengkap) ?></strong><br>
                                            <small class="text-muted"><?= Helper::escape($row->nip) ?></small>
                                        </td>
                                        <td>
                                            <?= Helper::escape($row->golongan_saat_ini) ?> 
                                            <i class="fas fa-arrow-right mx-1"></i> 
                                            <?= Helper::escape($row->golongan_diajukan) ?>
                                        </td>
                                        <td><?= Helper::formatDate($row->tanggal_pengajuan) ?></td>
                                        <td><?= Helper::getStatusBadge($row->status) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="<?= BASE_URL ?>/pengajuan" class="btn btn-primary btn-sm">
                        Lihat Semua Pengajuan <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Status Pengajuan</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Karyawan per Golongan</h5>
            </div>
            <div class="card-body">
                <canvas id="golonganChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize charts after Chart.js is loaded
function initCharts() {
    if (typeof Chart === 'undefined') {
        setTimeout(initCharts, 100);
        return;
    }
    
    // Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($pengajuanByStatus, 'status')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($pengajuanByStatus, 'total')) ?>,
                    backgroundColor: ['#ffc107', '#17a2b8', '#007bff', '#28a745', '#dc3545', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Golongan Chart
    const golonganCtx = document.getElementById('golonganChart');
    if (golonganCtx) {
        new Chart(golonganCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($pekerjaByGolongan, 'kode_golongan')) ?>,
                datasets: [{
                    label: 'Jumlah Karyawan',
                    data: <?= json_encode(array_column($pekerjaByGolongan, 'total')) ?>,
                    backgroundColor: '#0052CC'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

// Start initialization
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCharts);
} else {
    initCharts();
}
</script>
