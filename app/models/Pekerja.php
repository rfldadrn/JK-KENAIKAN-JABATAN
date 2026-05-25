<?php
/**
 * Pekerja.php - Pekerja Model
 */

class Pekerja extends Model
{
    protected $table = 'pekerja';
    protected $primaryKey = 'id_pekerja';

    /**
     * Get all pekerja with details
     */
    public function getAllWithDetails()
    {
        $sql = "SELECT p.*, 
                d.nama_divisi, 
                j.nama_jabatan,
                g.kode_golongan, g.nama_golongan,
                atasan.nama_lengkap as nama_atasan
                FROM {$this->table} p
                LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
                LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
                LEFT JOIN golongan_jabatan g ON p.id_golongan_saat_ini = g.id_golongan
                LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
                ORDER BY p.nama_lengkap ASC";
        
        return $this->query($sql);
    }

    /**
     * Get pekerja by ID with details
     */
    public function getWithDetails($id)
    {
        $sql = "SELECT p.*, 
                d.nama_divisi, d.kode_divisi,
                j.nama_jabatan, j.kode_jabatan,
                g.kode_golongan, g.nama_golongan, g.level, g.sub_level,
                atasan.nama_lengkap as nama_atasan,
                atasan.nip as nip_atasan
                FROM {$this->table} p
                LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
                LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
                LEFT JOIN golongan_jabatan g ON p.id_golongan_saat_ini = g.id_golongan
                LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
                WHERE p.{$this->primaryKey} = :id";
        
        return $this->queryOne($sql, [':id' => $id]);
    }

    /**
     * Get active pekerja for dropdown
     */
    public function getActiveForDropdown()
    {
        $sql = "SELECT id_pekerja, nip, nama_lengkap, id_jabatan
                FROM {$this->table}
                WHERE status_kepegawaian = 'aktif'
                ORDER BY nama_lengkap ASC";
        
        return $this->query($sql);
    }

    /**
     * Get by NIP
     */
    public function getByNip($nip)
    {
        return $this->getOne(['nip' => $nip]);
    }

    /**
     * Check if NIP exists
     */
    public function nipExists($nip, $exceptId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE nip = :nip";
        
        if ($exceptId) {
            $sql .= " AND {$this->primaryKey} != :id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nip', $nip);
        
        if ($exceptId) {
            $stmt->bindParam(':id', $exceptId);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $result->count > 0;
    }

    /**
     * Get subordinates (bawahan)
     */
    public function getSubordinates($id_atasan)
    {
        $sql = "SELECT p.*, 
                j.nama_jabatan,
                g.kode_golongan
                FROM {$this->table} p
                LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
                LEFT JOIN golongan_jabatan g ON p.id_golongan_saat_ini = g.id_golongan
                WHERE p.id_atasan = :id_atasan
                AND p.status_kepegawaian = 'aktif'
                ORDER BY p.nama_lengkap ASC";
        
        return $this->query($sql, [':id_atasan' => $id_atasan]);
    }

    /**
     * Calculate masa kerja
     */
    public function getMasaKerja($id)
    {
        $pekerja = $this->getById($id);
        if (!$pekerja) return 0;
        
        $start = new DateTime($pekerja->tanggal_bergabung);
        $now = new DateTime();
        $diff = $start->diff($now);
        
        return $diff->y + ($diff->m / 12); // Return in years with decimal
    }

    /**
     * Search pekerja
     */
    public function search($keyword)
    {
        $sql = "SELECT p.*, 
                d.nama_divisi, 
                j.nama_jabatan,
                g.kode_golongan
                FROM {$this->table} p
                LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
                LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
                LEFT JOIN golongan_jabatan g ON p.id_golongan_saat_ini = g.id_golongan
                WHERE p.nip LIKE :keyword
                   OR p.nama_lengkap LIKE :keyword
                   OR p.email LIKE :keyword
                ORDER BY p.nama_lengkap ASC";
        
        $keyword = "%{$keyword}%";
        return $this->query($sql, [':keyword' => $keyword]);
    }

    /**
     * Get employee performance report data
     */
    public function getKinerjaLaporan()
    {
        $sql = "SELECT p.nip, p.nama_lengkap, p.nilai_kinerja_terakhir,
                p.tanggal_bergabung, p.status_kepegawaian,
                d.nama_divisi, j.nama_jabatan,
                g.kode_golongan
                FROM {$this->table} p
                LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
                LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
                LEFT JOIN golongan_jabatan g ON p.id_golongan_saat_ini = g.id_golongan
                ORDER BY p.nilai_kinerja_terakhir DESC, p.nama_lengkap ASC";

        return $this->query($sql);
    }
}
