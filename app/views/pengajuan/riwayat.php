<div class="content-header">
    <h1><i class="fas fa-history me-2"></i>Riwayat Pengajuan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Riwayat Pengajuan</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header">
        <strong>Daftar Pengajuan yang Telah Selesai</strong>
    </div>
    <div class="card-body">
        <?php if (empty($riwayat)): ?>
            <div class="alert alert-info text-center mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada riwayat pengajuan yang selesai.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Pengajuan</th>
                        <th>Tanggal</th>
                        <th>Golongan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat as $i => $row): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><strong><?= Helper::escape($row->nomor_pengajuan) ?></strong></td>
                        <td><?= Helper::formatDate($row->tanggal_pengajuan) ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= Helper::escape($row->golongan_sekarang) ?></span>
                            <i class="fas fa-arrow-right mx-1"></i>
                            <span class="badge bg-info"><?= Helper::escape($row->golongan_tujuan) ?></span>
                        </td>
                        <td><?= Helper::getStatusBadge($row->status) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/pengajuan/detail/<?= $row->id_pengajuan ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
