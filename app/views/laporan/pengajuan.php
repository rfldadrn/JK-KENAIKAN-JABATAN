<div class="content-header">
    <h1><i class="fas fa-chart-bar me-2"></i>Laporan Pengajuan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item">Laporan</li>
            <li class="breadcrumb-item active">Pengajuan</li>
        </ol>
    </nav>
</div>

<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="mb-0"><?= $stats->total ?? 0 ?></h3>
                <small class="text-muted">Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-warning">
            <div class="card-body">
                <h3 class="mb-0 text-warning"><?= $stats->pending ?? 0 ?></h3>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-info">
            <div class="card-body">
                <h3 class="mb-0 text-info"><?= $stats->disetujui_atasan ?? 0 ?></h3>
                <small class="text-muted">Atasan</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-primary">
            <div class="card-body">
                <h3 class="mb-0 text-primary"><?= $stats->disetujui_manager ?? 0 ?></h3>
                <small class="text-muted">Manager</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-success">
            <div class="card-body">
                <h3 class="mb-0 text-success"><?= $stats->disetujui ?? 0 ?></h3>
                <small class="text-muted">Disetujui</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-danger">
            <div class="card-body">
                <h3 class="mb-0 text-danger"><?= $stats->ditolak ?? 0 ?></h3>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Pengajuan</h5>
        <button onclick="window.print()" class="btn btn-sm btn-primary">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="laporanTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Pengajuan</th>
                        <th>Pekerja</th>
                        <th>Tanggal</th>
                        <th>Golongan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($pengajuan as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= Helper::escape($p->nomor_pengajuan) ?></td>
                            <td>
                                <strong><?= Helper::escape($p->nip) ?></strong> - <?= Helper::escape($p->nama_lengkap) ?>
                            </td>
                            <td><?= Helper::formatDate($p->tanggal_pengajuan) ?></td>
                            <td>
                                <?= Helper::escape($p->golongan_sekarang) ?> → <?= Helper::escape($p->golongan_tujuan) ?>
                            </td>
                            <td><?= Helper::getStatusBadge($p->status) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
if (typeof jQuery !== 'undefined') {
    $(document).ready(function() {
        $('#laporanTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "pageLength": 25,
            "order": [[3, 'desc']]
        });
    });
}
</script>

<script>
if (typeof jQuery !== 'undefined') {
    $(document).ready(function() {
        $('#laporanTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "pageLength": 25
        });
    });
}
</script>
