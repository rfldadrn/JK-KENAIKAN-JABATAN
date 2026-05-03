<?php
/**
 * Pengajuan.php - Pengajuan Model
 */

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';

    /**
     * Get all pengajuan (all status) by bawahan langsung (atasan)
     */
    public function getAllByBawahan($id_atasan)
    {
        $sql = "SELECT pen.*, 
                p.nip, p.nama_lengkap,
                g_sekarang.kode_golongan as golongan_sekarang,
                g_tujuan.kode_golongan as golongan_tujuan
                FROM {$this->table} pen
                INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
                LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
                LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
                WHERE p.id_atasan = :id_atasan
                ORDER BY pen.tanggal_pengajuan DESC";
        return $this->query($sql, [':id_atasan' => $id_atasan]);
    }

    /**
     * Get all pengajuan with details
     */
    public function getAllWithDetails()
    {
        $sql = "SELECT pen.*, 
                p.nip, p.nama_lengkap, p.foto,
                g_sekarang.kode_golongan as golongan_sekarang,
                g_tujuan.kode_golongan as golongan_tujuan,
                d.nama_divisi, j.nama_jabatan
                FROM {$this->table} pen
                INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
                LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
                LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
                LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
                LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
                ORDER BY pen.tanggal_pengajuan DESC";
        
        return $this->query($sql);
    }

    /**
     * Get pengajuan by pekerja
     */
    public function getByPekerja($id_pekerja)
    {
        $sql = "SELECT pen.*, 
                g_sekarang.kode_golongan as golongan_sekarang,
                g_tujuan.kode_golongan as golongan_tujuan
                FROM {$this->table} pen
                LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
                LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
                WHERE pen.id_pekerja = :id_pekerja
                ORDER BY pen.tanggal_pengajuan DESC";
        
        return $this->query($sql, [':id_pekerja' => $id_pekerja]);
    }

    /**
     * Get pengajuan with full details
     */
    public function getWithDetails($id)
    {
        $sql = "SELECT pen.*, 
                p.nip, p.nama_lengkap, p.email, p.no_telepon, p.foto,
                p.tanggal_bergabung, p.nilai_kinerja_terakhir, p.id_atasan,
                g_sekarang.kode_golongan as kode_golongan_sekarang, 
                g_sekarang.nama_golongan as nama_golongan_sekarang,
                g_tujuan.kode_golongan as kode_golongan_tujuan,
                g_tujuan.nama_golongan as nama_golongan_tujuan,
                d.nama_divisi, j.nama_jabatan
                FROM {$this->table} pen
                INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
                LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
                LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
                LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
                LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
                WHERE pen.{$this->primaryKey} = :id";
        
        return $this->queryOne($sql, [':id' => $id]);
    }

    /**
     * Check active submission
     */
    public function hasActiveSubmission($id_pekerja)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE id_pekerja = :id_pekerja 
                AND status IN ('pending', 'disetujui_atasan', 'disetujui_manager')";
        
        $result = $this->queryOne($sql, [':id_pekerja' => $id_pekerja]);
        return $result->count > 0;
    }

    /**
     * Get pending approvals for atasan
     */
    public function getPendingForAtasan($id_pekerja)
    {
        $sql = "SELECT pen.*, 
                p.nip, p.nama_lengkap,
                g_sekarang.kode_golongan as golongan_sekarang,
                g_tujuan.kode_golongan as golongan_tujuan
                FROM {$this->table} pen
                INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
                LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
                LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
                WHERE p.id_atasan = :id_atasan
                AND pen.status = 'pending'
                ORDER BY pen.tanggal_pengajuan ASC";
        
        return $this->query($sql, [':id_atasan' => $id_pekerja]);
    }

    /**
     * Get pending approvals for manager
     */
    public function getPendingForManager()
    {
        $sql = "SELECT pen.*, 
                p.nip, p.nama_lengkap,
                g_sekarang.kode_golongan as golongan_sekarang,
                g_tujuan.kode_golongan as golongan_tujuan
                FROM {$this->table} pen
                INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
                LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
                LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
                WHERE pen.status = 'disetujui_atasan'
                ORDER BY pen.tanggal_pengajuan ASC";
        
        return $this->query($sql);
    }

    /**
     * Get pending approvals for kepala wilayah
     */
    public function getPendingForKepalaWilayah()
    {
        $sql = "SELECT pen.*, 
                p.nip, p.nama_lengkap,
                g_sekarang.kode_golongan as golongan_sekarang,
                g_tujuan.kode_golongan as golongan_tujuan
                FROM {$this->table} pen
                INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
                LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
                LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
                WHERE pen.status = 'disetujui_manager'
                ORDER BY pen.tanggal_pengajuan ASC";
        
        return $this->query($sql);
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'disetujui_atasan' THEN 1 ELSE 0 END) as disetujui_atasan,
                SUM(CASE WHEN status = 'disetujui_manager' THEN 1 ELSE 0 END) as disetujui_manager,
                SUM(CASE WHEN status = 'disetujui' THEN 1 ELSE 0 END) as disetujui,
                SUM(CASE WHEN status LIKE 'ditolak%' THEN 1 ELSE 0 END) as ditolak
                FROM {$this->table}";
        
        return $this->queryOne($sql);
    }
}
