<?php
/**
 * Divisi.php - Divisi Model
 */

class Divisi extends Model
{
    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';

    /**
     * Get all active divisi
     * @return array
     */
    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY nama_divisi ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Check if kode_divisi exists
     * @param string $kode
     * @param int $exceptId
     * @return bool
     */
    public function kodeExists($kode, $exceptId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE kode_divisi = :kode";
        
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
     * Get divisi with stats
     * @return array
     */
    public function getWithStats()
    {
        $sql = "SELECT d.*, 
                COUNT(p.id_pekerja) as jumlah_pekerja
                FROM {$this->table} d
                LEFT JOIN pekerja p ON d.id_divisi = p.id_divisi 
                    AND p.status_kepegawaian = 'aktif'
                GROUP BY d.id_divisi
                ORDER BY d.nama_divisi ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Check if divisi can be deleted
     * @param int $id
     * @return bool
     */
    public function canDelete($id)
    {
        $sql = "SELECT COUNT(*) as count FROM pekerja WHERE id_divisi = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $result->count == 0;
    }
}
