<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Karyawan</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/print-laporan.css">
</head>
<body>
<?php
$printedAt = $printedAt ?? date('Y-m-d H:i:s');
$pekerja = $pekerja ?? [];
?>
<div class="print-container">
    <div class="no-print">
        <button type="button" onclick="window.print()">Cetak Laporan</button>
    </div>

    <div class="report-header">
        <h1>Bank Rakyat Indonesia - Wilayah Padang</h1>
        <p>Sistem Kenaikan Golongan Jabatan</p>
        <p>Alamat Kantor: Jl. Bagindo Aziz Chan, Padang</p>
    </div>

    <div class="report-title">
        <h2>Laporan Data Karyawan</h2>
    </div>

    <div class="report-meta">
        <table>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: <?= Helper::formatDateTime($printedAt) ?></td>
            </tr>
            <tr>
                <td>Total Data</td>
                <td>: <?= count($pekerja) ?> karyawan</td>
            </tr>
        </table>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 36px;">No</th>
                <th>NIP</th>
                <th>Nama Karyawan</th>
                <th>Divisi</th>
                <th>Jabatan</th>
                <th>Golongan</th>
                <th>Masa Kerja</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pekerja)): ?>
                <tr>
                    <td colspan="8" class="text-center">Data karyawan tidak tersedia.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($pekerja as $p): ?>
                    <?php $masaKerja = Helper::calculateWorkPeriod($p->tanggal_bergabung); ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= Helper::escape($p->nip) ?></td>
                        <td><?= Helper::escape($p->nama_lengkap) ?></td>
                        <td><?= Helper::escape($p->nama_divisi ?? '-') ?></td>
                        <td><?= Helper::escape($p->nama_jabatan ?? '-') ?></td>
                        <td class="text-center"><?= Helper::escape($p->kode_golongan ?? '-') ?></td>
                        <td class="text-center"><?= $masaKerja['years'] ?> th <?= $masaKerja['months'] ?> bln</td>
                        <td class="text-center"><?= ucfirst(Helper::escape($p->status_kepegawaian ?? '-')) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature">
        <div>Padang, <?= Helper::formatDate(date('Y-m-d')) ?></div>
        <div>Mengetahui,</div>
        <div class="name">Admin HC</div>
    </div>
</div>
</body>
</html>
