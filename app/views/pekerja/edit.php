<div class="content-header">
    <h1><i class="fas fa-edit me-2"></i>Edit Karyawan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/pekerja">Data Karyawan</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <?php 
        $errors = Session::get('errors');
        $old = Session::get('old');
        Session::remove('errors');
        Session::remove('old');
        ?>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/pekerja/update/<?= $pekerja->id_pekerja ?>" enctype="multipart/form-data">
            <h5 class="mb-3">Data Pribadi</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nip" name="nip" 
                               value="<?= $old['nip'] ?? $pekerja->nip ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                               value="<?= $old['nama_lengkap'] ?? $pekerja->nama_lengkap ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= $old['email'] ?? $pekerja->email ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="no_telepon" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                               value="<?= $old['no_telepon'] ?? $pekerja->no_telepon ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                       value="<?= $old['tanggal_lahir'] ?? $pekerja->tanggal_lahir ?>">
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= $old['alamat'] ?? $pekerja->alamat ?></textarea>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <?php if ($pekerja->foto): ?>
                    <div class="mb-2">
                        <img src="<?= BASE_URL ?>/<?= $pekerja->foto ?>" alt="Current photo" class="rounded" style="max-width: 150px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
            </div>

            <hr class="my-4">

            <h5 class="mb-3">Data Kepegawaian</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="id_divisi" class="form-label">Divisi <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_divisi" name="id_divisi" required>
                            <?php foreach ($divisi as $d): ?>
                                <option value="<?= $d->id_divisi ?>" <?= ($old['id_divisi'] ?? $pekerja->id_divisi) == $d->id_divisi ? 'selected' : '' ?>>
                                    <?= Helper::escape($d->nama_divisi) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="id_jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_jabatan" name="id_jabatan" required>
                            <?php foreach ($jabatan as $j): ?>
                                <option value="<?= $j->id_jabatan ?>" <?= ($old['id_jabatan'] ?? $pekerja->id_jabatan) == $j->id_jabatan ? 'selected' : '' ?>>
                                    <?= Helper::escape($j->nama_jabatan) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="id_golongan_saat_ini" class="form-label">Golongan <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_golongan_saat_ini" name="id_golongan_saat_ini" required>
                            <?php foreach ($golongan as $g): ?>
                                <option value="<?= $g->id_golongan ?>" <?= ($old['id_golongan_saat_ini'] ?? $pekerja->id_golongan_saat_ini) == $g->id_golongan ? 'selected' : '' ?>>
                                    <?= Helper::escape($g->kode_golongan) ?> - <?= Helper::escape($g->nama_golongan) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="id_atasan" class="form-label">Atasan Langsung</label>
                        <select class="form-select" id="id_atasan" name="id_atasan">
                            <option value="">Pilih Atasan (Opsional)</option>
                            <?php foreach ($atasan as $a): ?>
                                <?php if ($a->id_pekerja != $pekerja->id_pekerja): ?>
                                    <option value="<?= $a->id_pekerja ?>" <?= ($old['id_atasan'] ?? $pekerja->id_atasan) == $a->id_pekerja ? 'selected' : '' ?>>
                                        <?= Helper::escape($a->nip) ?> - <?= Helper::escape($a->nama_lengkap) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_bergabung" class="form-label">Tanggal Bergabung <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_bergabung" name="tanggal_bergabung" 
                               value="<?= $old['tanggal_bergabung'] ?? $pekerja->tanggal_bergabung ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nilai_kinerja_terakhir" class="form-label">Nilai Kinerja Terakhir</label>
                        <input type="number" class="form-control" id="nilai_kinerja_terakhir" 
                               name="nilai_kinerja_terakhir" step="0.01" min="0" max="100"
                               value="<?= $old['nilai_kinerja_terakhir'] ?? $pekerja->nilai_kinerja_terakhir ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="status_kepegawaian" class="form-label">Status Kepegawaian</label>
                <select class="form-select" id="status_kepegawaian" name="status_kepegawaian">
                    <option value="aktif" <?= ($old['status_kepegawaian'] ?? $pekerja->status_kepegawaian) == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="cuti" <?= ($old['status_kepegawaian'] ?? $pekerja->status_kepegawaian) == 'cuti' ? 'selected' : '' ?>>Cuti</option>
                    <option value="nonaktif" <?= ($old['status_kepegawaian'] ?? $pekerja->status_kepegawaian) == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="<?= BASE_URL ?>/pekerja" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
