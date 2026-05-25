<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Pengajuan</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/print-laporan.css">
</head>
<body>
<?php
$printedAt = $printedAt ?? date('Y-m-d H:i:s');
$riwayatPengajuan = $riwayatPengajuan ?? [];
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
        <h2>Laporan Riwayat Pengajuan Kenaikan Golongan Jabatan</h2>
    </div>

    <div class="report-meta">
        <table>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: <?= Helper::formatDateTime($printedAt) ?></td>
            </tr>
            <tr>
                <td>Total Data</td>
                <td>: <?= count($riwayatPengajuan) ?> pengajuan</td>
            </tr>
        </table>
    </div>

    <?php
    $statusMap = [
        'pending' => 'Pending',
        'disetujui_atasan' => 'Disetujui Atasan',
        'disetujui_manager' => 'Disetujui Manager',
        'disetujui' => 'Disetujui',
        'ditolak_atasan' => 'Ditolak Atasan',
        'ditolak_manager' => 'Ditolak Manager',
        'ditolak_kepala_wilayah' => 'Ditolak Kepala Wilayah',
        'dibatalkan' => 'Dibatalkan'
    ];
    ?>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 36px;">No</th>
                <th>No Pengajuan</th>
                <th>Karyawan</th>
                <th>Divisi/Jabatan</th>
                <th>Tanggal Pengajuan</th>
                <th>Perubahan Golongan</th>
                <th>Status Akhir</th>
                <th>Approval</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($riwayatPengajuan)): ?>
                <tr>
                    <td colspan="8" class="text-center">Data riwayat pengajuan tidak tersedia.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($riwayatPengajuan as $item): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= Helper::escape($item->nomor_pengajuan) ?></td>
                        <td>
                            <?= Helper::escape($item->nama_lengkap) ?><br>
                            <span class="small">NIP: <?= Helper::escape($item->nip) ?></span>
                        </td>
                        <td>
                            <?= Helper::escape($item->nama_divisi ?? '-') ?><br>
                            <span class="small"><?= Helper::escape($item->nama_jabatan ?? '-') ?></span>
                        </td>
                        <td class="text-center"><?= Helper::formatDate($item->tanggal_pengajuan) ?></td>
                        <td class="text-center"><?= Helper::escape($item->golongan_sekarang ?? '-') ?> -> <?= Helper::escape($item->golongan_tujuan ?? '-') ?></td>
                        <td class="text-center"><?= Helper::escape($statusMap[$item->status] ?? $item->status) ?></td>
                        <td class="text-center">
                            <?= (int) $item->jumlah_approval ?> tahap<br>
                            <span class="small">
                                <?= !empty($item->tanggal_approval_terakhir) ? Helper::formatDateTime($item->tanggal_approval_terakhir) : '-' ?>
                            </span>
                        </td>
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
