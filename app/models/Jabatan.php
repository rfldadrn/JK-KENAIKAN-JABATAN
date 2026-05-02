<?php
/**
 * Jabatan.php - Jabatan Model
 */

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';

    /**
     * Get all active jabatan
     * @return array
     */
    public function getAllActive()
    {
        $sql = "SELECT j.*, g.kode_golongan, g.nama_golongan
                FROM {$this->table} j
                LEFT JOIN golongan_jabatan g ON j.id_golongan_minimal = g.id_golongan
                WHERE j.is_active = 1 
                ORDER BY j.nama_jabatan ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get jabatan with details
     * @param int $id
     * @return object|false
     */
    public function getWithDetails($id)
    {
        $sql = "SELECT j.*, g.kode_golongan, g.nama_golongan
                FROM {$this->table} j
                LEFT JOIN golongan_jabatan g ON j.id_golongan_minimal = g.id_golongan
                WHERE j.{$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Check if kode_jabatan exists
     * @param string $kode
     * @param int $exceptId
     * @return bool
     */
    public function kodeExists($kode, $exceptId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE kode_jabatan = :kode";
        
        if ($exceptId) {
            $sql .= " AND {$this->primaryKey} != :id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':kode', $kode);
        
        if ($exceptId) {
            $stmt->bindParam(':id', $exceptId);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $result->count > 0;
    }

    /**
     * Get jabatan with stats
     * @return array
     */
    public function getWithStats()
    {
        $sql = "SELECT j.*, 
                g.kode_golongan, g.nama_golongan,
                COUNT(p.id_pekerja) as jumlah_pekerja
                FROM {$this->table} j
                LEFT JOIN golongan_jabatan g ON j.id_golongan_minimal = g.id_golongan
                LEFT JOIN pekerja p ON j.id_jabatan = p.id_jabatan 
                    AND p.status_kepegawaian = 'aktif'
                GROUP BY j.id_jabatan
                ORDER BY j.nama_jabatan ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Check if jabatan can be deleted
     * @param int $id
     * @return bool
     */
    public function canDelete($id)
    {
        $sql = "SELECT COUNT(*) as count FROM pekerja WHERE id_jabatan = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $result->count == 0;
    }
}
