<div class="content-header">
    <h1><i class="fas fa-history me-2"></i>Riwayat Persetujuan</h1>
</div>

<div class="card">
    <div class="card-header">
        <strong>Daftar Riwayat Approval Anda</strong>
    </div>
    <div class="card-body">
        <?php if (empty($approvalHistory)): ?>
            <div class="alert alert-info text-center mb-0">Belum ada riwayat persetujuan.</div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Pengajuan</th>
                        <th>Nama Pemohon</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Approval</th>
                        <th>Level</th>
                        <th>Keputusan</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($approvalHistory as $i => $row): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= Helper::escape($row->nomor_pengajuan) ?></td>
                        <td><?= Helper::escape($row->nama_pemohon) ?></td>
                        <td><?= Helper::formatDate($row->tanggal_pengajuan) ?></td>
                        <td><?= Helper::formatDateTime($row->tanggal_approval) ?></td>
                        <td><?= ucwords($row->level_approval) ?></td>
                        <td>
                            <?php if ($row->keputusan === 'approved'): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td><?= nl2br(Helper::escape($row->catatan)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
