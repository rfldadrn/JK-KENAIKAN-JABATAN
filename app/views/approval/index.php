<div class="content-header">
    <h1><i class="fas fa-check-circle me-2"></i>Persetujuan Pengajuan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Persetujuan</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Pengajuan Pending</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="approvalTable">
                <thead>
                    <tr>
                        <th>Nomor Pengajuan</th>
                        <th>Pekerja</th>
                        <th>Tanggal</th>
                        <th>Golongan Sekarang</th>
                        <th>Golongan Tujuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pending)): ?>
                        <?php foreach ($pending as $p): ?>
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
                                    <a href="<?= BASE_URL ?>/approval/review/<?= $p->id_pengajuan ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-clipboard-check"></i> Review
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pengajuan yang perlu disetujui</td>
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
        $('#approvalTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [[2, 'asc']]
        });
    });
}
</script>
