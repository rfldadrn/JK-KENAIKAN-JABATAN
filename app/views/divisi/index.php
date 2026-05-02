<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-building me-2"></i>Master Divisi</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Divisi</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/divisi/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Divisi
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="divisiTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Divisi</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Pekerja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($divisi)): ?>
                        <?php $no = 1; foreach ($divisi as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= Helper::escape($row->kode_divisi) ?></strong></td>
                                <td><?= Helper::escape($row->nama_divisi) ?></td>
                                <td><?= Helper::truncate($row->deskripsi ?? '-', 50) ?></td>
                                <td><?= $row->jumlah_pekerja ?? 0 ?> pekerja</td>
                                <td>
                                    <?php if ($row->is_active): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/divisi/edit/<?= $row->id_divisi ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/divisi/delete/<?= $row->id_divisi ?>" 
                                       class="btn btn-sm btn-danger btn-delete" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data divisi</td>
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
        $('#divisiTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
}
</script>
