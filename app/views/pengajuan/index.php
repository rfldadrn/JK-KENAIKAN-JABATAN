<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-file-alt me-2"></i>Daftar Pengajuan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengajuan</li>
                </ol>
            </nav>
        </div>
        <?php if (Session::get('role') === 'pekerja'): ?>
            <div>
                <a href="<?= BASE_URL ?>/pengajuan/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Buat Pengajuan
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="pengajuanTable">
                <thead>
                    <tr>
                        <?php if (in_array(Session::get('role'), ['admin', 'manager', 'kepala_wilayah'])): ?>
                            <th>Karyawan</th>
                        <?php endif; ?>
                        <th>Nomor Pengajuan</th>
                        <th>Tanggal</th>
                        <th>Golongan Sekarang</th>
                        <th>Golongan Tujuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pengajuan)): ?>
                        <?php foreach ($pengajuan as $p): ?>
                            <tr>
                                <?php if (in_array(Session::get('role'), ['admin', 'manager', 'kepala_wilayah'])): ?>
                                    <td>
                                        <strong><?= Helper::escape($p->nip) ?></strong><br>
                                        <small><?= Helper::escape($p->nama_lengkap) ?></small>
                                    </td>
                                <?php endif; ?>
                                <td><strong><?= Helper::escape($p->nomor_pengajuan) ?></strong></td>
                                <td><?= Helper::formatDate($p->tanggal_pengajuan) ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= Helper::escape($p->golongan_sekarang) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= Helper::escape($p->golongan_tujuan) ?>
                                    </span>
                                </td>
                                <td><?= Helper::getStatusBadge($p->status) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/pengajuan/detail/<?= $p->id_pengajuan ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= in_array(Session::get('role'), ['admin', 'manager', 'kepala_wilayah']) ? '7' : '6' ?>" class="text-center">
                                Belum ada pengajuan
                            </td>
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
        $('#pengajuanTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [[<?= in_array(Session::get('role'), ['admin', 'manager', 'kepala_wilayah']) ? '2' : '1' ?>, 'desc']]
        });
    });
}
</script>
