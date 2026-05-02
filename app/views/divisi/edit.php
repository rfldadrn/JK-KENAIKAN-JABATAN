<div class="content-header">
    <h1><i class="fas fa-edit me-2"></i>Edit Divisi</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/divisi">Divisi</a></li>
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

        <form method="POST" action="<?= BASE_URL ?>/divisi/update/<?= $divisi->id_divisi ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="kode_divisi" class="form-label">Kode Divisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_divisi" name="kode_divisi" 
                               value="<?= $old['kode_divisi'] ?? $divisi->kode_divisi ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_divisi" class="form-label">Nama Divisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" 
                               value="<?= $old['nama_divisi'] ?? $divisi->nama_divisi ?>" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= $old['deskripsi'] ?? $divisi->deskripsi ?></textarea>
            </div>

            <div class="mb-3">
                <label for="is_active" class="form-label">Status</label>
                <select class="form-select" id="is_active" name="is_active">
                    <option value="1" <?= ($old['is_active'] ?? $divisi->is_active) == '1' ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= ($old['is_active'] ?? $divisi->is_active) == '0' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/divisi" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
