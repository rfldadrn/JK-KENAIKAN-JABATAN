<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-user me-2"></i>Detail Karyawan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/pekerja">Data Karyawan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/pekerja/edit/<?= $pekerja->id_pekerja ?>" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="<?= BASE_URL ?>/pekerja" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <?php if ($pekerja->foto): ?>
                    <img src="<?= BASE_URL ?>/<?= $pekerja->foto ?>" 
                         alt="<?= Helper::escape($pekerja->nama_lengkap) ?>" 
                         class="rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 150px; height: 150px; font-size: 48px;">
                        <?= strtoupper(substr($pekerja->nama_lengkap, 0, 1)) ?>
                    </div>
                <?php endif; ?>
                
                <h4><?= Helper::escape($pekerja->nama_lengkap) ?></h4>
                <p class="text-muted mb-2"><?= Helper::escape($pekerja->nip) ?></p>
                <p class="mb-3">
                    <?php if ($pekerja->status_kepegawaian === 'aktif'): ?>
                        <span class="badge bg-success">Aktif</span>
                    <?php elseif ($pekerja->status_kepegawaian === 'cuti'): ?>
                        <span class="badge bg-warning">Cuti</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Nonaktif</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <?php if (!empty($bawahan)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Bawahan Langsung (<?= count($bawahan) ?>)</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($bawahan as $b): ?>
                        <a href="<?= BASE_URL ?>/pekerja/detail/<?= $b->id_pekerja ?>" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= Helper::escape($b->nama_lengkap) ?></strong><br>
                                    <small class="text-muted"><?= Helper::escape($b->nama_jabatan) ?></small>
                                </div>
                                <span class="badge bg-info"><?= Helper::escape($b->kode_golongan) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Email</th>
                        <td><?= Helper::escape($pekerja->email) ?></td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td><?= Helper::escape($pekerja->no_telepon ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>
                            <?= $pekerja->tanggal_lahir ? Helper::formatDate($pekerja->tanggal_lahir) : '-' ?>
                            <?php if ($pekerja->tanggal_lahir): ?>
                                (<?= Helper::calculateAge($pekerja->tanggal_lahir) ?> tahun)
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= Helper::escape($pekerja->alamat ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Informasi Kepegawaian</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Divisi</th>
                        <td><?= Helper::escape($pekerja->nama_divisi ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td><?= Helper::escape($pekerja->nama_jabatan ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Golongan Saat Ini</th>
                        <td>
                            <span class="badge bg-info fs-6">
                                <?= Helper::escape($pekerja->kode_golongan ?? '-') ?>
                            </span>
                            - <?= Helper::escape($pekerja->nama_golongan ?? '-') ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Atasan Langsung</th>
                        <td><?= Helper::escape($pekerja->nama_atasan ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Bergabung</th>
                        <td>
                            <?= Helper::formatDate($pekerja->tanggal_bergabung) ?>
                            <?php 
                            $masaKerja = Helper::calculateWorkPeriod($pekerja->tanggal_bergabung);
                            ?>
                            <br><small class="text-muted">
                                (Masa kerja: <?= $masaKerja['years'] ?> tahun <?= $masaKerja['months'] ?> bulan)
                            </small>
                        </td>
                    </tr>
                    <tr>
                        <th>Nilai Kinerja Terakhir</th>
                        <td>
                            <?php if ($pekerja->nilai_kinerja_terakhir): ?>
                                <strong class="<?= $pekerja->nilai_kinerja_terakhir >= 80 ? 'text-success' : 'text-warning' ?>">
                                    <?= $pekerja->nilai_kinerja_terakhir ?>
                                </strong>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
