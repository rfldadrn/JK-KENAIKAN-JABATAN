<div class="content-header">
    <h1><i class="fas fa-user-circle me-2"></i>Profil Saya</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Profil</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if ($pekerja): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pekerja</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <?php if ($pekerja->foto): ?>
                                <img src="<?= BASE_URL ?>/<?= $pekerja->foto ?>" 
                                     alt="<?= Helper::escape($pekerja->nama_lengkap) ?>" 
                                     class="rounded-circle mb-3" 
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 120px; height: 120px; font-size: 40px;">
                                    <?= strtoupper(substr($pekerja->nama_lengkap, 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">NIP</th>
                                    <td><?= Helper::escape($pekerja->nip) ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td><?= Helper::escape($pekerja->nama_lengkap) ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= Helper::escape($pekerja->email) ?></td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td><?= Helper::escape($pekerja->no_telepon ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <th>Divisi</th>
                                    <td><?= Helper::escape($pekerja->nama_divisi) ?></td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td><?= Helper::escape($pekerja->nama_jabatan) ?></td>
                                </tr>
                                <tr>
                                    <th>Golongan Saat Ini</th>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= Helper::escape($pekerja->kode_golongan) ?>
                                        </span>
                                        <?= Helper::escape($pekerja->nama_golongan) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Masa Kerja</th>
                                    <td>
                                        <?php 
                                        $masa = Helper::calculateWorkPeriod($pekerja->tanggal_bergabung);
                                        echo $masa['years'] . ' tahun ' . $masa['months'] . ' bulan';
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Informasi Akun</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Username</th>
                        <td><?= Helper::escape($user->username) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= Helper::escape($user->email) ?></td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td><?= Helper::getRoleLabel($user->role) ?></td>
                    </tr>
                    <tr>
                        <th>Last Login</th>
                        <td><?= $user->last_login ? Helper::formatDateTime($user->last_login) : '-' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Ubah Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/profil/changePassword">
                    <div class="mb-3">
                        <label class="form-label">Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="old_password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password" required minlength="6">
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" required minlength="6">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
