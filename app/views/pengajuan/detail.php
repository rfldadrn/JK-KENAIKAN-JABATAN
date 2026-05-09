<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-file-alt me-2"></i>Detail Pengajuan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/pengajuan">Pengajuan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/pengajuan" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pengajuan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Nomor Pengajuan</th>
                        <td><strong><?= Helper::escape($pengajuan->nomor_pengajuan) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <td><?= Helper::formatDate($pengajuan->tanggal_pengajuan) ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?= Helper::getStatusBadge($pengajuan->status) ?></td>
                    </tr>
                    <tr>
                        <th>Karyawan</th>
                        <td>
                            <strong><?= Helper::escape($pengajuan->nip) ?></strong> - <?= Helper::escape($pengajuan->nama_lengkap) ?><br>
                            <small class="text-muted"><?= Helper::escape($pengajuan->nama_jabatan) ?> - <?= Helper::escape($pengajuan->nama_divisi) ?></small>
                        </td>
                    </tr>
                    <tr>
                        <th>Golongan Sekarang</th>
                        <td>
                            <span class="badge bg-secondary fs-6">
                                <?= Helper::escape($pengajuan->kode_golongan_sekarang) ?>
                            </span>
                            <?= Helper::escape($pengajuan->nama_golongan_sekarang) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Golongan Tujuan</th>
                        <td>
                            <span class="badge bg-info fs-6">
                                <?= Helper::escape($pengajuan->kode_golongan_tujuan) ?>
                            </span>
                            <?= Helper::escape($pengajuan->nama_golongan_tujuan) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Alasan Pengajuan</th>
                        <td><?= nl2br(Helper::escape($pengajuan->alasan_pengajuan)) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <?php if (!empty($dokumen)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>Dokumen Pendukung</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($dokumen as $dok): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php 
                                    $icon = 'fa-file';
                                    $iconColor = 'text-secondary';
                                    if (strpos($dok->mime_type, 'pdf') !== false) {
                                        $icon = 'fa-file-pdf';
                                        $iconColor = 'text-danger';
                                    } elseif (strpos($dok->mime_type, 'image') !== false) {
                                        $icon = 'fa-file-image';
                                        $iconColor = 'text-success';
                                    }
                                    ?>
                                    <i class="fas <?= $icon ?> <?= $iconColor ?> me-2"></i>
                                    <strong><?= ucwords(str_replace('_', ' ', $dok->jenis_dokumen)) ?></strong><br>
                                    <small class="text-muted">
                                        <?= Helper::escape($dok->nama_dokumen) ?> 
                                        (<?= Helper::formatFileSize($dok->file_size) ?>)
                                    </small>
                                </div>
                                <div>
                                    <a href="<?= BASE_URL ?>/dokumen/display/<?= $dok->id_dokumen ?>" target="_blank" class="btn btn-sm btn-primary me-1">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <a href="<?= BASE_URL ?>/dokumen/downloadFile/<?= $dok->id_dokumen ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>Dokumen Pendukung</h5>
                </div>
                <div class="card-body text-center text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>Tidak ada dokumen pendukung</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <?php if (!empty($approvalHistory)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Persetujuan</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($approvalHistory as $history): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong><?= ucwords($history->level_approval) ?></strong>
                                <?php if ($history->keputusan === 'approved'): ?>
                                    <span class="badge bg-success">Disetujui</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">
                                <?= Helper::escape($history->nama_approver ?? $history->username) ?><br>
                                <?= Helper::formatDateTime($history->tanggal_approval) ?>
                            </small>
                            <?php if ($history->catatan): ?>
                                <p class="mb-0 mt-2 small">
                                    <em>"<?= Helper::escape($history->catatan) ?>"</em>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
