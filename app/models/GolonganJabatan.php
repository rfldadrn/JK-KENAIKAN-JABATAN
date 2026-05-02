<?php
/**
 * GolonganJabatan.php - Golongan Jabatan Model
 */

class GolonganJabatan extends Model
{
    protected $table = 'golongan_jabatan';
    protected $primaryKey = 'id_golongan';

    /**
     * Get all active golongan
     * @return array
     */
    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 
                ORDER BY level ASC, sub_level ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get golongan by level
     * @param int $level
     * @return array
     */
    public function getByLevel($level)
    {
        $sql = "SELECT * FROM {$this->table} WHERE level = :level AND is_active = 1 
                ORDER BY sub_level ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':level', $level);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get next golongan (untuk validasi kenaikan)
     * @param int $currentGolonganId
     * @return object|false
     */
    public function getNextGolongan($currentGolonganId)
    {
        $current = $this->getById($currentGolonganId);
        if (!$current) {
            return false;
        }

        // Cari golongan 1 tingkat di atas
        $sql = "SELECT * FROM {$this->table} 
                WHERE (level = :level AND sub_level > :sub_level)
                   OR (level = :next_level)
                AND is_active = 1
                ORDER BY level ASC, sub_level ASC
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':level' => $current->level,
            ':sub_level' => $current->sub_level,
            ':next_level' => $current->level + 1
        ]);
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Check if kode_golongan exists
     * @param string $kode
     * @param int $exceptId
     * @return bool
     */
    public function kodeExists($kode, $exceptId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE kode_golongan = :kode";
        
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
     * Get golongan with stats
     * @return array
     */
    public function getWithStats()
    {
        $sql = "SELECT g.*, 
                COUNT(p.id_pekerja) as jumlah_pekerja
                FROM {$this->table} g
                LEFT JOIN pekerja p ON g.id_golongan = p.id_golongan_saat_ini 
                    AND p.status_kepegawaian = 'aktif'
                GROUP BY g.id_golongan
                ORDER BY g.level ASC, g.sub_level ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
