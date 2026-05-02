<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-users me-2"></i>Data Pekerja</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Pekerja</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/pekerja/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Pekerja
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="pekerjaTable">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>NIP</th>
                        <th>Nama Lengkap</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Golongan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pekerja)): ?>
                        <?php foreach ($pekerja as $p): ?>
                            <tr>
                                <td>
                                    <?php if ($p->foto): ?>
                                        <img src="<?= BASE_URL ?>/<?= $p->foto ?>" 
                                             alt="<?= Helper::escape($p->nama_lengkap) ?>" 
                                             class="rounded-circle" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <?= strtoupper(substr($p->nama_lengkap, 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= Helper::escape($p->nip) ?></strong></td>
                                <td><?= Helper::escape($p->nama_lengkap) ?></td>
                                <td><?= Helper::escape($p->nama_divisi ?? '-') ?></td>
                                <td><?= Helper::escape($p->nama_jabatan ?? '-') ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= Helper::escape($p->kode_golongan ?? '-') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($p->status_kepegawaian === 'aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php elseif ($p->status_kepegawaian === 'cuti'): ?>
                                        <span class="badge bg-warning">Cuti</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/pekerja/detail/<?= $p->id_pekerja ?>" 
                                       class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/pekerja/edit/<?= $p->id_pekerja ?>" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/pekerja/delete/<?= $p->id_pekerja ?>" 
                                       class="btn btn-sm btn-danger" 
                                       title="Hapus"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus pekerja ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data pekerja</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
if (typeof jQuery !== 'undefined') {
    $(document).ready(function() {
        $('#pekerjaTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "pageLength": 25,
            "order": [[2, 'asc']]
        });
    });
}
</script>
