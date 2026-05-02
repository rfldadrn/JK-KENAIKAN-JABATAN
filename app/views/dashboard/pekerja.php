<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-home me-2"></i>Dashboard</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/pengajuan/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Ajukan Kenaikan Golongan
            </a>
        </div>
    </div>
</div>

<!-- Profile Card -->
<div class="row mb-4">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="user-avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    <?= strtoupper(substr($pekerjaDetail->nama_lengkap, 0, 1)) ?>
                </div>
                <h4><?= Helper::escape($pekerjaDetail->nama_lengkap) ?></h4>
                <p class="text-muted mb-2"><?= Helper::escape($pekerjaDetail->nip) ?></p>
                <p class="mb-1"><strong><?= Helper::escape($pekerjaDetail->nama_jabatan) ?></strong></p>
                <p class="mb-1"><?= Helper::escape($pekerjaDetail->nama_divisi) ?></p>
                <div class="mt-3">
                    <span class="badge bg-primary" style="font-size: 1rem; padding: 10px 20px;">
                        Golongan <?= Helper::escape($pekerjaDetail->kode_golongan) ?>
                    </span>
                </div>
                <hr>
                <div class="text-start">
                    <p class="mb-2">
                        <i class="fas fa-calendar-alt me-2 text-muted"></i>
                        Masa Kerja: <strong><?= $pekerjaDetail->masa_kerja_tahun ?> tahun <?= $pekerjaDetail->masa_kerja_bulan ?> bulan</strong>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-star me-2 text-muted"></i>
                        Nilai Kinerja: <strong><?= $pekerjaDetail->nilai_kinerja_terakhir ?? 'Belum ada' ?></strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="icon bg-info">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stats-number"><?= $totalPengajuan ?></div>
                    <div class="stats-label">Total Pengajuan</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number"><?= $pengajuanAktif ?></div>
                    <div class="stats-label">Sedang Diproses</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number"><?= $pengajuanDisetujui ?></div>
                    <div class="stats-label">Disetujui</div>
                </div>
            </div>
        </div>
        
        <!-- Recent Pengajuan -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Pengajuan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nomor Pengajuan</th>
                                <th>Golongan Diajukan</th>
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
                                    <td colspan="4" class="text-center">Belum ada pengajuan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (!empty($recentPengajuan)): ?>
                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>/pengajuan" class="btn btn-primary btn-sm">
                            Lihat Semua <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Informasi Pengajuan Kenaikan Golongan</h5>
        <div class="row mt-3">
            <div class="col-md-6">
                <h6>Syarat Pengajuan:</h6>
                <ul>
                    <li>Masa kerja minimal <?= MIN_MASA_KERJA_TAHUN ?> tahun</li>
                    <li>Nilai kinerja minimal <?= MIN_NILAI_KINERJA ?>/100</li>
                    <li>Tidak ada pengajuan aktif yang sedang diproses</li>
                    <li>Melampirkan dokumen pendukung lengkap</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Proses Persetujuan:</h6>
                <ol>
                    <li>Persetujuan Atasan Langsung</li>
                    <li>Persetujuan Manager Wilayah</li>
                    <li>Persetujuan Final Kepala Wilayah</li>
                </ol>
            </div>
        </div>
    </div>
</div>
