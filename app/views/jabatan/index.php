<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-briefcase me-2"></i>Master Jabatan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Jabatan</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/jabatan/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Jabatan
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="jabatanTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Jabatan</th>
                        <th>Golongan Minimal</th>
                        <th>Jumlah Pekerja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($jabatan)): ?>
                        <?php $no = 1; foreach ($jabatan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= Helper::escape($row->kode_jabatan) ?></strong></td>
                                <td><?= Helper::escape($row->nama_jabatan) ?></td>
                                <td>
                                    <?php if ($row->kode_golongan): ?>
                                        <span class="badge bg-info"><?= $row->kode_golongan ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $row->jumlah_pekerja ?? 0 ?> pekerja</td>
                                <td>
                                    <?php if ($row->is_active): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/jabatan/edit/<?= $row->id_jabatan ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/jabatan/delete/<?= $row->id_jabatan ?>" 
                                       class="btn btn-sm btn-danger btn-delete" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus jabatan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data jabatan</td>
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
        $('#jabatanTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
}
</script>
