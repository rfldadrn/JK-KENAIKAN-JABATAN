<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Helper - Approval System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        h2 { color: #007bff; margin-top: 30px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .info { background: #e7f3ff; padding: 10px; border-left: 4px solid #007bff; margin: 15px 0; }
        .query-box {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #28a745;
            margin: 10px 0;
            font-family: monospace;
            overflow-x: auto;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-pending { background: #ffc107; color: #000; }
        .badge-approved { background: #28a745; color: white; }
        .badge-rejected { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug Helper - Sistem Approval Kenaikan Golongan</h1>
        
        <div class="info">
            <strong>📌 Tujuan:</strong> File ini membantu debugging masalah approval yang tidak muncul atau tidak berfungsi dengan benar.
        </div>

<?php
require_once '../app/config/Database.php';

$db = Database::getInstance()->getConnection();

echo "<h2>1️⃣ Validasi Data Pekerja & Atasan</h2>";

// Cek Sari Dewi dan atasannya
$sql = "SELECT 
    p.id_pekerja,
    p.nip,
    p.nama_lengkap,
    p.id_atasan,
    atasan.nip as nip_atasan,
    atasan.nama_lengkap as nama_atasan,
    u_atasan.username as username_atasan,
    u_atasan.role as role_atasan,
    gol.kode_golongan as golongan_saat_ini
FROM pekerja p
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
LEFT JOIN users u_atasan ON atasan.id_pekerja = u_atasan.id_pekerja
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
WHERE p.nip IN ('20210014', '20210015', '20230018', '20220016')
ORDER BY p.nip";

$result = $db->query($sql);
echo "<table>";
echo "<tr>
    <th>NIP</th>
    <th>Nama</th>
    <th>id_pekerja</th>
    <th>id_atasan</th>
    <th>Nama Atasan</th>
    <th>Username Atasan</th>
    <th>Role Atasan</th>
    <th>Golongan</th>
</tr>";

while ($row = $result->fetch(PDO::FETCH_OBJ)) {
    $atasanInfo = $row->nama_atasan ? 
        "<span class='success'>✓ {$row->nama_atasan}</span>" : 
        "<span class='error'>✗ TIDAK ADA ATASAN</span>";
    
    echo "<tr>
        <td>{$row->nip}</td>
        <td>{$row->nama_lengkap}</td>
        <td>{$row->id_pekerja}</td>
        <td>{$row->id_atasan}</td>
        <td>{$atasanInfo}</td>
        <td>{$row->username_atasan}</td>
        <td>{$row->role_atasan}</td>
        <td>{$row->golongan_saat_ini}</td>
    </tr>";
}
echo "</table>";

echo "<h2>2️⃣ Daftar Pengajuan (Semua Status)</h2>";

$sql = "SELECT 
    peng.id_pengajuan,
    peng.nomor_pengajuan,
    peng.tanggal_pengajuan,
    peng.status,
    p.nip,
    p.nama_lengkap as nama_pemohon,
    p.id_atasan,
    atasan.nip as nip_atasan,
    atasan.nama_lengkap as nama_atasan,
    g_sekarang.kode_golongan as dari_golongan,
    g_tujuan.kode_golongan as ke_golongan
FROM pengajuan peng
JOIN pekerja p ON peng.id_pekerja = p.id_pekerja
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
LEFT JOIN golongan_jabatan g_sekarang ON peng.id_golongan_saat_ini = g_sekarang.id_golongan
LEFT JOIN golongan_jabatan g_tujuan ON peng.id_golongan_diajukan = g_tujuan.id_golongan
ORDER BY peng.id_pengajuan DESC
LIMIT 20";

$result = $db->query($sql);
echo "<table>";
echo "<tr>
    <th>ID</th>
    <th>Nomor</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Pemohon</th>
    <th>Golongan</th>
    <th>Atasan</th>
</tr>";

while ($row = $result->fetch(PDO::FETCH_OBJ)) {
    $statusClass = '';
    if ($row->status == 'pending') $statusClass = 'badge-pending';
    elseif ($row->status == 'disetujui') $statusClass = 'badge-approved';
    elseif (strpos($row->status, 'ditolak') !== false) $statusClass = 'badge-rejected';
    else $statusClass = 'badge-pending';
    
    echo "<tr>
        <td>{$row->id_pengajuan}</td>
        <td>{$row->nomor_pengajuan}</td>
        <td>{$row->tanggal_pengajuan}</td>
        <td><span class='badge {$statusClass}'>{$row->status}</span></td>
        <td>{$row->nip} - {$row->nama_pemohon}</td>
        <td>{$row->dari_golongan} → {$row->ke_golongan}</td>
        <td>{$row->nip_atasan} - {$row->nama_atasan}</td>
    </tr>";
}
echo "</table>";

echo "<h2>3️⃣ Pending Approval untuk Maya Sari (id_pekerja=8)</h2>";
echo "<div class='info'>Login: <strong>20140008</strong> | Role: <strong>atasan</strong></div>";

$sql = "SELECT 
    pen.id_pengajuan,
    pen.nomor_pengajuan,
    pen.tanggal_pengajuan,
    pen.status,
    p.nip,
    p.nama_lengkap,
    p.id_atasan,
    g_sekarang.kode_golongan as golongan_sekarang,
    g_tujuan.kode_golongan as golongan_tujuan
FROM pengajuan pen
INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
WHERE p.id_atasan = 8
  AND pen.status = 'pending'
ORDER BY pen.tanggal_pengajuan ASC";

$result = $db->query($sql);
$count = 0;

echo "<table>";
echo "<tr>
    <th>Nomor Pengajuan</th>
    <th>Tanggal</th>
    <th>Pemohon</th>
    <th>Golongan</th>
    <th>Status</th>
</tr>";

while ($row = $result->fetch(PDO::FETCH_OBJ)) {
    $count++;
    echo "<tr>
        <td>{$row->nomor_pengajuan}</td>
        <td>{$row->tanggal_pengajuan}</td>
        <td>{$row->nip} - {$row->nama_lengkap}</td>
        <td>{$row->golongan_sekarang} → {$row->golongan_tujuan}</td>
        <td><span class='badge badge-pending'>{$row->status}</span></td>
    </tr>";
}

if ($count == 0) {
    echo "<tr><td colspan='5' class='error'>❌ TIDAK ADA DATA PENDING</td></tr>";
}
echo "</table>";

if ($count > 0) {
    echo "<div class='success'>✅ DITEMUKAN {$count} pengajuan pending untuk Maya Sari</div>";
} else {
    echo "<div class='error'>❌ TIDAK ADA pengajuan pending. Pastikan sudah jalankan file '05_Testing_Data_Approval.sql'</div>";
}

echo "<h2>4️⃣ Pending Approval untuk Dedi Kurniawan (id_pekerja=9)</h2>";
echo "<div class='info'>Login: <strong>20150009</strong> | Role: <strong>atasan</strong></div>";

$sql = "SELECT 
    pen.id_pengajuan,
    pen.nomor_pengajuan,
    pen.tanggal_pengajuan,
    pen.status,
    p.nip,
    p.nama_lengkap,
    g_sekarang.kode_golongan as golongan_sekarang,
    g_tujuan.kode_golongan as golongan_tujuan
FROM pengajuan pen
INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
WHERE p.id_atasan = 9
  AND pen.status = 'pending'
ORDER BY pen.tanggal_pengajuan ASC";

$result = $db->query($sql);
$count = 0;

echo "<table>";
echo "<tr>
    <th>Nomor Pengajuan</th>
    <th>Tanggal</th>
    <th>Pemohon</th>
    <th>Golongan</th>
    <th>Status</th>
</tr>";

while ($row = $result->fetch(PDO::FETCH_OBJ)) {
    $count++;
    echo "<tr>
        <td>{$row->nomor_pengajuan}</td>
        <td>{$row->tanggal_pengajuan}</td>
        <td>{$row->nip} - {$row->nama_lengkap}</td>
        <td>{$row->golongan_sekarang} → {$row->golongan_tujuan}</td>
        <td><span class='badge badge-pending'>{$row->status}</span></td>
    </tr>";
}

if ($count == 0) {
    echo "<tr><td colspan='5' class='warning'>⚠️ TIDAK ADA DATA PENDING</td></tr>";
}
echo "</table>";

echo "<h2>5️⃣ Pending untuk Manager (status: disetujui_atasan)</h2>";
echo "<div class='info'>Login sebagai <strong>MANAGER</strong> (20050002 atau lainnya)</div>";

$sql = "SELECT 
    pen.id_pengajuan,
    pen.nomor_pengajuan,
    pen.tanggal_pengajuan,
    pen.status,
    p.nip,
    p.nama_lengkap,
    g_sekarang.kode_golongan as golongan_sekarang,
    g_tujuan.kode_golongan as golongan_tujuan
FROM pengajuan pen
INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
WHERE pen.status = 'disetujui_atasan'
ORDER BY pen.tanggal_pengajuan ASC";

$result = $db->query($sql);
$count = 0;

echo "<table>";
echo "<tr>
    <th>Nomor Pengajuan</th>
    <th>Tanggal</th>
    <th>Pemohon</th>
    <th>Golongan</th>
</tr>";

while ($row = $result->fetch(PDO::FETCH_OBJ)) {
    $count++;
    echo "<tr>
        <td>{$row->nomor_pengajuan}</td>
        <td>{$row->tanggal_pengajuan}</td>
        <td>{$row->nip} - {$row->nama_lengkap}</td>
        <td>{$row->golongan_sekarang} → {$row->golongan_tujuan}</td>
    </tr>";
}

if ($count == 0) {
    echo "<tr><td colspan='4' class='warning'>⚠️ Belum ada yang disetujui atasan</td></tr>";
}
echo "</table>";

echo "<h2>6️⃣ Pending untuk Kepala Wilayah (status: disetujui_manager)</h2>";
echo "<div class='info'>Login: <strong>19900001</strong> | Role: <strong>kepala_wilayah</strong></div>";

$sql = "SELECT 
    pen.id_pengajuan,
    pen.nomor_pengajuan,
    pen.tanggal_pengajuan,
    pen.status,
    p.nip,
    p.nama_lengkap,
    g_sekarang.kode_golongan as golongan_sekarang,
    g_tujuan.kode_golongan as golongan_tujuan
FROM pengajuan pen
INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
WHERE pen.status = 'disetujui_manager'
ORDER BY pen.tanggal_pengajuan ASC";

$result = $db->query($sql);
$count = 0;

echo "<table>";
echo "<tr>
    <th>Nomor Pengajuan</th>
    <th>Tanggal</th>
    <th>Pemohon</th>
    <th>Golongan</th>
</tr>";

while ($row = $result->fetch(PDO::FETCH_OBJ)) {
    $count++;
    echo "<tr>
        <td>{$row->nomor_pengajuan}</td>
        <td>{$row->tanggal_pengajuan}</td>
        <td>{$row->nip} - {$row->nama_lengkap}</td>
        <td>{$row->golongan_sekarang} → {$row->golongan_tujuan}</td>
    </tr>";
}

if ($count == 0) {
    echo "<tr><td colspan='4' class='warning'>⚠️ Belum ada yang disetujui manager</td></tr>";
}
echo "</table>";

echo "<h2>7️⃣ Riwayat Approval History</h2>";

$sql = "SELECT 
    peng.nomor_pengajuan,
    peng.status as status_pengajuan,
    pekerja.nama_lengkap as pemohon,
    ah.level_approval,
    approver.nama_lengkap as approver,
    ah.keputusan,
    ah.tanggal_approval
FROM pengajuan peng
JOIN pekerja ON peng.id_pekerja = pekerja.id_pekerja
LEFT JOIN approval_history ah ON peng.id_pengajuan = ah.id_pengajuan
LEFT JOIN users u ON ah.id_approver = u.id_user
LEFT JOIN pekerja approver ON u.id_pekerja = approver.id_pekerja
ORDER BY peng.id_pengajuan DESC, ah.tanggal_approval
LIMIT 30";

$result = $db->query($sql);

echo "<table>";
echo "<tr>
    <th>Nomor Pengajuan</th>
    <th>Pemohon</th>
    <th>Status</th>
    <th>Level</th>
    <th>Approver</th>
    <th>Keputusan</th>
    <th>Tanggal</th>
</tr>";

while ($row = $result->fetch(PDO::FETCH_OBJ)) {
    $keputusan = $row->keputusan == 'approved' ? 
        "<span class='success'>✓ Approved</span>" : 
        "<span class='error'>✗ Rejected</span>";
    
    echo "<tr>
        <td>{$row->nomor_pengajuan}</td>
        <td>{$row->pemohon}</td>
        <td>{$row->status_pengajuan}</td>
        <td>{$row->level_approval}</td>
        <td>{$row->approver}</td>
        <td>{$keputusan}</td>
        <td>{$row->tanggal_approval}</td>
    </tr>";
}
echo "</table>";

echo "<h2>📝 Instruksi Testing</h2>";
echo "<div class='info'>";
echo "<ol>";
echo "<li><strong>Jalankan SQL Testing:</strong> Import file <code>05_Testing_Data_Approval.sql</code> di phpMyAdmin</li>";
echo "<li><strong>Refresh halaman ini</strong> untuk melihat data terbaru</li>";
echo "<li><strong>Login sebagai Atasan:</strong>
    <ul>
        <li>Username: <strong>20140008</strong> (Maya Sari)</li>
        <li>Password: <strong>password123</strong></li>
        <li>Menu: Pending Approval → harus muncul pengajuan Sari Dewi & Fitri</li>
    </ul>
</li>";
echo "<li><strong>Approve pengajuan</strong> dan cek status berubah</li>";
echo "<li><strong>Login sebagai Manager</strong> dan ulangi proses</li>";
echo "<li><strong>Login sebagai Kepala Wilayah</strong> untuk final approval</li>";
echo "</ol>";
echo "</div>";

?>

        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-left: 4px solid #28a745;">
            <strong>✅ Troubleshooting:</strong>
            <ul>
                <li>Jika <strong>tidak ada data pending</strong> → Jalankan file <code>05_Testing_Data_Approval.sql</code></li>
                <li>Jika <strong>atasan tidak valid</strong> → Cek kolom id_atasan di tabel pekerja</li>
                <li>Jika <strong>query error</strong> → Pastikan semua tabel sudah dibuat dengan benar</li>
            </ul>
        </div>

        <div style="margin-top: 20px; text-align: center; color: #666;">
            <small>Debug Helper v1.0 | Refresh halaman ini setelah melakukan perubahan data</small>
        </div>
    </div>
</body>
</html>
