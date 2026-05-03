<?php
/**
 * ApprovalHistory.php - Approval History Model
 */

class ApprovalHistory extends Model
{
    protected $table = 'approval_history';
    protected $primaryKey = 'id_approval';

    /**
     * Get approval history by pengajuan
     */
    public function getByPengajuan($id_pengajuan)
    {
        $sql = "SELECT ah.*, 
                u.username,
                p.nama_lengkap as nama_approver
                FROM {$this->table} ah
                LEFT JOIN users u ON ah.id_approver = u.id_user
                LEFT JOIN pekerja p ON u.id_pekerja = p.id_pekerja
                WHERE ah.id_pengajuan = :id_pengajuan
                ORDER BY ah.tanggal_approval ASC";
        
        return $this->query($sql, [':id_pengajuan' => $id_pengajuan]);
    }

    /**
     * Add approval record
     */
    public function addApproval($data)
    {
        return $this->insert($data);
    }
}
