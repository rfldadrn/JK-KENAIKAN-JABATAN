<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Kenaikan Jabatan</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/print-laporan.css">
</head>
<body>
<?php
$printedAt = $printedAt ?? date('Y-m-d H:i:s');
$hasilKenaikan = $hasilKenaikan ?? [];
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
        <h2>Laporan Informasi Hasil Kenaikan Jabatan Karyawan</h2>
    </div>

    <div class="report-meta">
        <table>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: <?= Helper::formatDateTime($printedAt) ?></td>
            </tr>
            <tr>
                <td>Total Data</td>
                <td>: <?= count($hasilKenaikan) ?> hasil disetujui</td>
            </tr>
        </table>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 36px;">No</th>
                <th>No Pengajuan</th>
                <th>NIP</th>
                <th>Nama Karyawan</th>
                <th>Divisi/Jabatan</th>
                <th>Golongan Lama</th>
                <th>Golongan Baru</th>
                <th>Tanggal Efektif</th>
                <th>Nomor SK</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($hasilKenaikan)): ?>
                <tr>
                    <td colspan="9" class="text-center">Belum ada data kenaikan jabatan yang disetujui.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($hasilKenaikan as $item): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= Helper::escape($item->nomor_pengajuan) ?></td>
                        <td><?= Helper::escape($item->nip) ?></td>
                        <td><?= Helper::escape($item->nama_lengkap) ?></td>
                        <td>
                            <?= Helper::escape($item->nama_divisi ?? '-') ?><br>
                            <span class="small"><?= Helper::escape($item->nama_jabatan ?? '-') ?></span>
                        </td>
                        <td class="text-center"><?= Helper::escape($item->golongan_lama ?? '-') ?></td>
                        <td class="text-center"><?= Helper::escape($item->golongan_baru ?? '-') ?></td>
                        <td class="text-center"><?= Helper::formatDate($item->tanggal_efektif) ?></td>
                        <td class="text-center"><?= Helper::escape($item->nomor_sk ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature">
        <div>Padang, <?= Helper::formatDate(date('Y-m-d')) ?></div>
        <div>Mengetahui,</div>
        <div class="name">Kepala Wilayah</div>
    </div>
</div>
</body>
</html>
