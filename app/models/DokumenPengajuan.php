<?php
/**
 * DokumenPengajuan.php - Dokumen Pengajuan Model
 */

class DokumenPengajuan extends Model
{
    protected $table = 'dokumen_pengajuan';
    protected $primaryKey = 'id_dokumen';

    /**
     * Get documents by pengajuan
     */
    public function getByPengajuan($id_pengajuan)
    {
        return $this->getWhere(['id_pengajuan' => $id_pengajuan]);
    }

    /**
     * Delete by pengajuan
     */
    public function deleteByPengajuan($id_pengajuan)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_pengajuan = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_pengajuan);
        return $stmt->execute();
    }
}
