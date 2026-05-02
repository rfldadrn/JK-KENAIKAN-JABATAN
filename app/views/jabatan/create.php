<div class="content-header">
    <h1><i class="fas fa-plus me-2"></i>Tambah Jabatan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/jabatan">Jabatan</a></li>
            <li class="breadcrumb-item active">Tambah</li>
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

        <form method="POST" action="<?= BASE_URL ?>/jabatan/store">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="kode_jabatan" class="form-label">Kode Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_jabatan" name="kode_jabatan" 
                               placeholder="Contoh: MGR, SPV, STAFF" value="<?= $old['kode_jabatan'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_jabatan" class="form-label">Nama Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" 
                               placeholder="Contoh: Manager IT" value="<?= $old['nama_jabatan'] ?? '' ?>" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="id_golongan_minimal" class="form-label">Golongan Minimal</label>
                <select class="form-select" id="id_golongan_minimal" name="id_golongan_minimal">
                    <option value="">Pilih Golongan Minimal (Opsional)</option>
                    <?php if (!empty($golongan)): ?>
                        <?php foreach ($golongan as $g): ?>
                            <option value="<?= $g->id_golongan ?>" 
                                    <?= ($old['id_golongan_minimal'] ?? '') == $g->id_golongan ? 'selected' : '' ?>>
                                <?= $g->kode_golongan ?> - <?= $g->nama_golongan ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="text-muted">Golongan minimal yang diperlukan untuk jabatan ini</small>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" 
                          rows="4" placeholder="Deskripsi jabatan dan tanggung jawabnya"><?= $old['deskripsi'] ?? '' ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/jabatan" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
