<div class="content-header">
    <h1><i class="fas fa-chart-bar me-2"></i>Laporan Data Karyawan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item">Laporan</li>
            <li class="breadcrumb-item active">Karyawan</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Karyawan</h5>
        <button onclick="window.print()" class="btn btn-sm btn-primary">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="laporanPekerjaTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Golongan</th>
                        <th>Masa Kerja</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($pekerja as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= Helper::escape($p->nip) ?></td>
                            <td><?= Helper::escape($p->nama_lengkap) ?></td>
                            <td><?= Helper::escape($p->nama_divisi ?? '-') ?></td>
                            <td><?= Helper::escape($p->nama_jabatan ?? '-') ?></td>
                            <td><?= Helper::escape($p->kode_golongan ?? '-') ?></td>
                            <td>
                                <?php 
                                $masa = Helper::calculateWorkPeriod($p->tanggal_bergabung);
                                echo $masa['years'] . ' tahun';
                                ?>
                            </td>
                            <td>
                                <?php if ($p->status_kepegawaian === 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= ucfirst($p->status_kepegawaian) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
if (typeof jQuery !== 'undefined') {
    $(document).ready(function() {
        $('#laporanPekerjaTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
}
</script>
