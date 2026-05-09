<div class="content-header">
    <h1><i class="fas fa-list me-2"></i><?= $pageTitle ?></h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Semua Pengajuan</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><?= $pageTitle ?></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="allPengajuanTable">
                <thead>
                    <tr>
                        <th>Nomor Pengajuan</th>
                        <th>Karyawan</th>
                        <th>Tanggal</th>
                        <th>Golongan Sekarang</th>
                        <th>Golongan Tujuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($allPengajuan)): ?>
                        <?php foreach ($allPengajuan as $p): ?>
                            <tr>
                                <td><strong><?= Helper::escape($p->nomor_pengajuan) ?></strong></td>
                                <td>
                                    <strong><?= Helper::escape($p->nip) ?></strong><br>
                                    <?= Helper::escape($p->nama_lengkap) ?>
                                </td>
                                <td><?= Helper::formatDate($p->tanggal_pengajuan) ?></td>
                                <td><span class="badge bg-secondary"><?= Helper::escape($p->golongan_sekarang) ?></span></td>
                                <td><span class="badge bg-info"><?= Helper::escape($p->golongan_tujuan) ?></span></td>
                                <td>
                                    <?php
                                    $statusBadge = 'bg-secondary';
                                    $statusText = ucwords(str_replace('_', ' ', $p->status));
                                    if ($p->status === 'pending') {
                                        $statusBadge = 'bg-warning';
                                    } elseif ($p->status === 'disetujui_atasan') {
                                        $statusBadge = 'bg-info';
                                    } elseif ($p->status === 'disetujui_manager') {
                                        $statusBadge = 'bg-primary';
                                    } elseif ($p->status === 'disetujui') {
                                        $statusBadge = 'bg-success';
                                    } elseif (strpos($p->status, 'ditolak') !== false) {
                                        $statusBadge = 'bg-danger';
                                    }
                                    ?>
                                    <span class="badge <?= $statusBadge ?>"><?= Helper::escape($statusText) ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/pengajuan/detail/<?= $p->id_pengajuan ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-search"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada pengajuan dari bawahan Anda</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
if (typeof jQuery !== 'undefined') {
    $(document).ready(function() {
        $('#allPengajuanTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "pageLength": 25,
            "order": [[2, 'desc']]
        });
    });
}
</script>
