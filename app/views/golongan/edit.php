<div class="content-header">
    <h1><i class="fas fa-edit me-2"></i>Edit Golongan Jabatan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/golongan">Golongan Jabatan</a></li>
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

        <form method="POST" action="<?= BASE_URL ?>/golongan/update/<?= $golongan->id_golongan ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="kode_golongan" class="form-label">Kode Golongan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="kode_golongan" name="kode_golongan" 
                               value="<?= $old['kode_golongan'] ?? $golongan->kode_golongan ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_golongan" class="form-label">Nama Golongan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="nama_golongan" name="nama_golongan" 
                               value="<?= $old['nama_golongan'] ?? $golongan->nama_golongan ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="level" class="form-label">Level <span class="text-danger">*</span></label>
                        <select class="form-select" id="level" name="level" required>
                            <option value="1" <?= ($old['level'] ?? $golongan->level) == '1' ? 'selected' : '' ?>>Level 1 - Junior Staff</option>
                            <option value="2" <?= ($old['level'] ?? $golongan->level) == '2' ? 'selected' : '' ?>>Level 2 - Senior Staff</option>
                            <option value="3" <?= ($old['level'] ?? $golongan->level) == '3' ? 'selected' : '' ?>>Level 3 - Supervisor</option>
                            <option value="4" <?= ($old['level'] ?? $golongan->level) == '4' ? 'selected' : '' ?>>Level 4 - Manager</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sub_level" class="form-label">Sub Level <span class="text-danger">*</span></label>
                        <select class="form-select" id="sub_level" name="sub_level" required>
                            <option value="A" <?= ($old['sub_level'] ?? $golongan->sub_level) == 'A' ? 'selected' : '' ?>>A</option>
                            <option value="B" <?= ($old['sub_level'] ?? $golongan->sub_level) == 'B' ? 'selected' : '' ?>>B</option>
                            <option value="C" <?= ($old['sub_level'] ?? $golongan->sub_level) == 'C' ? 'selected' : '' ?>>C</option>
                            <option value="D" <?= ($old['sub_level'] ?? $golongan->sub_level) == 'D' ? 'selected' : '' ?>>D</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= $old['deskripsi'] ?? $golongan->deskripsi ?></textarea>
            </div>

            <div class="mb-3">
                <label for="syarat_minimal" class="form-label">Syarat Minimal</label>
                <textarea class="form-control" id="syarat_minimal" name="syarat_minimal" rows="3"><?= $old['syarat_minimal'] ?? $golongan->syarat_minimal ?></textarea>
            </div>

            <div class="mb-3">
                <label for="is_active" class="form-label">Status</label>
                <select class="form-select" id="is_active" name="is_active">
                    <option value="1" <?= ($old['is_active'] ?? $golongan->is_active) == '1' ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= ($old['is_active'] ?? $golongan->is_active) == '0' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/golongan" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
