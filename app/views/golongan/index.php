<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-layer-group me-2"></i>Master Golongan Jabatan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Golongan Jabatan</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/golongan/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Golongan
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="golonganTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Golongan</th>
                        <th>Level</th>
                        <th>Jumlah Karyawan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($golongan)): ?>
                        <?php $no = 1; foreach ($golongan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= Helper::escape($row->kode_golongan) ?></strong></td>
                                <td><?= Helper::escape($row->nama_golongan) ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        Level <?= $row->level ?>-<?= $row->sub_level ?>
                                    </span>
                                </td>
                                <td><?= $row->jumlah_pekerja ?? 0 ?> Karyawan</td>
                                <td>
                                    <?php if ($row->is_active): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/golongan/edit/<?= $row->id_golongan ?>" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/golongan/delete/<?= $row->id_golongan ?>" 
                                       class="btn btn-sm btn-danger btn-delete" 
                                       title="Hapus"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus golongan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data golongan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Wait for jQuery to load
if (typeof jQuery === 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initGolonganTable, 100);
    });
} else {
    $(document).ready(initGolonganTable);
}

function initGolonganTable() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#golonganTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "pageLength": 25
        });
    }
}
</script>
