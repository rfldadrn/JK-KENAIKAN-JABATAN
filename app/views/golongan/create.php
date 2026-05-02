<div class="content-header">
    <h1><i class="fas fa-plus me-2"></i>Tambah Golongan Jabatan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/golongan">Golongan Jabatan</a></li>
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

        <form method="POST" action="<?= BASE_URL ?>/golongan/store">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="kode_golongan" class="form-label">Kode Golongan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= isset($errors['kode_golongan']) ? 'is-invalid' : '' ?>" 
                               id="kode_golongan" name="kode_golongan" 
                               placeholder="Contoh: I-A, II-B"
                               value="<?= $old['kode_golongan'] ?? '' ?>" required>
                        <small class="text-muted">Format: Level-SubLevel (contoh: I-A)</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_golongan" class="form-label">Nama Golongan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= isset($errors['nama_golongan']) ? 'is-invalid' : '' ?>" 
                               id="nama_golongan" name="nama_golongan" 
                               placeholder="Contoh: Golongan I-A (Junior Staff)"
                               value="<?= $old['nama_golongan'] ?? '' ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="level" class="form-label">Level <span class="text-danger">*</span></label>
                        <select class="form-select <?= isset($errors['level']) ? 'is-invalid' : '' ?>" 
                                id="level" name="level" required>
                            <option value="">Pilih Level</option>
                            <option value="1" <?= ($old['level'] ?? '') == '1' ? 'selected' : '' ?>>Level 1 - Junior Staff</option>
                            <option value="2" <?= ($old['level'] ?? '') == '2' ? 'selected' : '' ?>>Level 2 - Senior Staff</option>
                            <option value="3" <?= ($old['level'] ?? '') == '3' ? 'selected' : '' ?>>Level 3 - Supervisor</option>
                            <option value="4" <?= ($old['level'] ?? '') == '4' ? 'selected' : '' ?>>Level 4 - Manager</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sub_level" class="form-label">Sub Level <span class="text-danger">*</span></label>
                        <select class="form-select <?= isset($errors['sub_level']) ? 'is-invalid' : '' ?>" 
                                id="sub_level" name="sub_level" required>
                            <option value="">Pilih Sub Level</option>
                            <option value="A" <?= ($old['sub_level'] ?? '') == 'A' ? 'selected' : '' ?>>A</option>
                            <option value="B" <?= ($old['sub_level'] ?? '') == 'B' ? 'selected' : '' ?>>B</option>
                            <option value="C" <?= ($old['sub_level'] ?? '') == 'C' ? 'selected' : '' ?>>C</option>
                            <option value="D" <?= ($old['sub_level'] ?? '') == 'D' ? 'selected' : '' ?>>D</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" 
                          rows="3" placeholder="Deskripsi golongan"><?= $old['deskripsi'] ?? '' ?></textarea>
            </div>

            <div class="mb-3">
                <label for="syarat_minimal" class="form-label">Syarat Minimal</label>
                <textarea class="form-control" id="syarat_minimal" name="syarat_minimal" 
                          rows="3" placeholder="Contoh: Minimal 2 tahun pengalaman, nilai kinerja 80"><?= $old['syarat_minimal'] ?? '' ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/golongan" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
