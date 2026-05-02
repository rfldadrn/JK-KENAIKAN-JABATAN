<div class="content-header">
    <h1><i class="fas fa-home me-2"></i>Dashboard Manager Wilayah</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb"><li class="breadcrumb-item active">Dashboard</li></ol>
    </nav>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="stats-card">
            <div class="icon bg-warning"><i class="fas fa-clock"></i></div>
            <div class="stats-number"><?= $pendingApproval ?></div>
            <div class="stats-label">Pending Approval Level 2</div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="stats-card">
            <div class="icon bg-success"><i class="fas fa-check"></i></div>
            <div class="stats-number"><?= $approvedTahunIni ?></div>
            <div class="stats-label">Approved Tahun Ini</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Pengajuan Menunggu Persetujuan Level 2</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>NIP/Nama</th>
                        <th>Divisi</th>
                        <th>Golongan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pendingPengajuan)): ?>
                        <?php foreach ($pendingPengajuan as $row): ?>
                            <tr>
                                <td><?= Helper::escape($row->nomor_pengajuan) ?></td>
                                <td>
                                    <strong><?= Helper::escape($row->nama_lengkap) ?></strong><br>
                                    <small class="text-muted"><?= Helper::escape($row->nip) ?></small>
                                </td>
                                <td><?= Helper::escape($row->nama_divisi) ?></td>
                                <td>
                                    <?= Helper::escape($row->golongan_saat_ini) ?> 
                                    <i class="fas fa-arrow-right mx-1"></i> 
                                    <?= Helper::escape($row->golongan_diajukan) ?>
                                </td>
                                <td><?= Helper::formatDate($row->tanggal_pengajuan) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/approval/review/<?= $row->id_pengajuan ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>Review
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pengajuan yang menunggu persetujuan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
