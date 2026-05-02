<?php
/**
 * User.php - User Model
 * Handles user data and authentication
 */

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';

    /**
     * Get user by username
     * @param string $username
     * @return object|false
     */
    public function getByUsername($username)
    {
        $sql = "SELECT u.*, p.nama_lengkap, p.nip, p.foto,
                p.id_divisi, p.id_jabatan, p.id_golongan_saat_ini
                FROM {$this->table} u
                LEFT JOIN pekerja p ON u.id_pekerja = p.id_pekerja
                WHERE u.username = :username AND u.is_active = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get user by email
     * @param string $email
     * @return object|false
     */
    public function getByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email AND is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Authenticate user
     * @param string $username
     * @param string $password
     * @return object|false
     */
    public function authenticate($username, $password)
    {
        $user = $this->getByUsername($username);
        
        if ($user && Helper::verifyPassword($password, $user->password)) {
            // Update last login
            $this->update($user->id_user, [
                'last_login' => date('Y-m-d H:i:s')
            ]);
            
            return $user;
        }
        
        return false;
    }

    /**
     * Create new user
     * @param array $data
     * @return int|false
     */
    public function createUser($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = Helper::hashPassword($data['password']);
        }
        
        return $this->insert($data);
    }

    /**
     * Update user password
     * @param int $userId
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = Helper::hashPassword($newPassword);
        return $this->update($userId, ['password' => $hashedPassword]);
    }

    /**
     * Check if username exists
     * @param string $username
     * @param int $exceptId
     * @return bool
     */
    public function usernameExists($username, $exceptId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = :username";
        
        if ($exceptId) {
            $sql .= " AND id_user != :id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        
        if ($exceptId) {
            $stmt->bindParam(':id', $exceptId);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $result->count > 0;
    }

    /**
     * Check if email exists
     * @param string $email
     * @param int $exceptId
     * @return bool
     */
    public function emailExists($email, $exceptId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        
        if ($exceptId) {
            $sql .= " AND id_user != :id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        
        if ($exceptId) {
            $stmt->bindParam(':id', $exceptId);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $result->count > 0;
    }

    /**
     * Get users by role
     * @param string $role
     * @return array
     */
    public function getByRole($role)
    {
        $sql = "SELECT u.*, p.nama_lengkap, p.nip
                FROM {$this->table} u
                LEFT JOIN pekerja p ON u.id_pekerja = p.id_pekerja
                WHERE u.role = :role AND u.is_active = 1
                ORDER BY p.nama_lengkap ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get user with pekerja details
     * @param int $userId
     * @return object|false
     */
    public function getUserWithDetails($userId)
    {
        $sql = "SELECT u.*, p.*, 
                d.nama_divisi, j.nama_jabatan, 
                g.kode_golongan, g.nama_golongan
                FROM {$this->table} u
                LEFT JOIN pekerja p ON u.id_pekerja = p.id_pekerja
                LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
                LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
                LEFT JOIN golongan_jabatan g ON p.id_golongan_saat_ini = g.id_golongan
                WHERE u.id_user = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Activate/Deactivate user
     * @param int $userId
     * @param int $status (1 = active, 0 = inactive)
     * @return bool
     */
    public function setActive($userId, $status)
    {
        return $this->update($userId, ['is_active' => $status]);
    }
}
