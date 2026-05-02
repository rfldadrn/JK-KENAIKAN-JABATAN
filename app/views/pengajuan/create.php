<div class="content-header">
    <h1><i class="fas fa-plus me-2"></i>Buat Pengajuan Kenaikan Golongan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/pengajuan">Pengajuan</a></li>
            <li class="breadcrumb-item active">Buat Pengajuan</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Persyaratan</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Masa kerja: <strong><?= $masaKerja['years'] ?> tahun <?= $masaKerja['months'] ?> bulan</strong>
                        <?php if ($masaKerja['years'] >= MIN_MASA_KERJA_TAHUN): ?>
                            <span class="badge bg-success">✓ Memenuhi</span>
                        <?php else: ?>
                            <span class="badge bg-danger">✗ Belum</span>
                        <?php endif; ?>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Nilai Kinerja: <strong><?= $pekerja->nilai_kinerja_terakhir ?></strong>
                        <?php if ($pekerja->nilai_kinerja_terakhir >= MIN_NILAI_KINERJA): ?>
                            <span class="badge bg-success">✓ Memenuhi</span>
                        <?php else: ?>
                            <span class="badge bg-danger">✗ Belum</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Data Pekerja</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>NIP</th>
                        <td><?= Helper::escape($pekerja->nip) ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?= Helper::escape($pekerja->nama_lengkap) ?></td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td><?= Helper::escape($pekerja->nama_jabatan) ?></td>
                    </tr>
                    <tr>
                        <th>Golongan Saat Ini</th>
                        <td><span class="badge bg-secondary"><?= Helper::escape($pekerja->kode_golongan) ?></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/pengajuan/store" enctype="multipart/form-data">
                    <input type="hidden" name="id_golongan_saat_ini" value="<?= $pekerja->id_golongan_saat_ini ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Golongan Tujuan <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_golongan_diajukan" required>
                            <?php if ($nextGolongan): ?>
                                <option value="<?= $nextGolongan->id_golongan ?>">
                                    <?= $nextGolongan->kode_golongan ?> - <?= $nextGolongan->nama_golongan ?>
                                </option>
                            <?php else: ?>
                                <option value="">Tidak ada golongan lebih tinggi</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan Pengajuan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alasan_pengajuan" rows="4" required 
                                  placeholder="Jelaskan alasan Anda mengajukan kenaikan golongan"></textarea>
                    </div>

                    <h5 class="mt-4 mb-3">Dokumen Pendukung</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">Surat Permohonan <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="surat_permohonan" required accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Format: PDF, JPG, PNG. Max 2MB</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penilaian Kinerja <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="penilaian_kinerja" required accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Format: PDF, JPG, PNG. Max 2MB</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sertifikat/Pencapaian <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="sertifikat" required accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Format: PDF, JPG, PNG. Max 2MB</small>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= BASE_URL ?>/pengajuan" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Ajukan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
