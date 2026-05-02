<div class="content-header">
    <h1><i class="fas fa-plus me-2"></i>Tambah Divisi</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/divisi">Divisi</a></li>
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

        <form method="POST" action="<?= BASE_URL ?>/divisi/store">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="kode_divisi" class="form-label">Kode Divisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_divisi" name="kode_divisi" 
                               placeholder="Contoh: IT, FIN, HR" value="<?= $old['kode_divisi'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_divisi" class="form-label">Nama Divisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" 
                               placeholder="Contoh: Information Technology" value="<?= $old['nama_divisi'] ?? '' ?>" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" 
                          rows="4" placeholder="Deskripsi divisi"><?= $old['deskripsi'] ?? '' ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/divisi" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
