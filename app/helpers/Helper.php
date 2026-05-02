<?php
/**
 * Helper.php - General Helper Functions
 * Provides utility functions used throughout the application
 */

class Helper
{
    /**
     * Format date to Indonesian format
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function formatDate($date, $format = 'd F Y')
    {
        if (empty($date) || $date === '0000-00-00') {
            return '-';
        }

        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $timestamp = strtotime($date);
        $formatted = date($format, $timestamp);

        // Replace month with Indonesian
        foreach ($bulan as $num => $nama) {
            $formatted = str_replace(date('F', $timestamp), $nama, $formatted);
        }

        return $formatted;
    }

    /**
     * Format datetime to Indonesian format
     * @param string $datetime
     * @return string
     */
    public static function formatDateTime($datetime)
    {
        if (empty($datetime)) {
            return '-';
        }
        return self::formatDate($datetime, 'd F Y H:i');
    }

    /**
     * Format currency to Rupiah
     * @param int|float $amount
     * @return string
     */
    public static function formatRupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format file size
     * @param int $bytes
     * @return string
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Sanitize string for output
     * @param string $string
     * @return string
     */
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate random string
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * Hash password
     * @param string $password
     * @return string
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify password
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Truncate text
     * @param string $text
     * @param int $length
     * @param string $suffix
     * @return string
     */
    public static function truncate($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) > $length) {
            return substr($text, 0, $length) . $suffix;
        }
        return $text;
    }

    /**
     * Create URL slug
     * @param string $text
     * @return string
     */
    public static function slug($text)
    {
        $text = preg_replace('/[^a-zA-Z0-9\s-]/', '', $text);
        $text = strtolower(trim($text));
        $text = preg_replace('/[\s-]+/', '-', $text);
        return $text;
    }

    /**
     * Calculate age from birthdate
     * @param string $birthdate
     * @return int
     */
    public static function calculateAge($birthdate)
    {
        if (empty($birthdate)) {
            return 0;
        }
        $birth = new DateTime($birthdate);
        $today = new DateTime();
        return $birth->diff($today)->y;
    }

    /**
     * Calculate work period
     * @param string $startDate
     * @param string $endDate
     * @return array [years, months]
     */
    public static function calculateWorkPeriod($startDate, $endDate = null)
    {
        if (empty($startDate)) {
            return ['years' => 0, 'months' => 0];
        }

        $start = new DateTime($startDate);
        $end = $endDate ? new DateTime($endDate) : new DateTime();
        $diff = $start->diff($end);

        return [
            'years' => $diff->y,
            'months' => $diff->m,
            'days' => $diff->d
        ];
    }

    /**
     * Get status badge HTML
     * @param string $status
     * @return string
     */
    public static function getStatusBadge($status)
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'disetujui_atasan' => '<span class="badge bg-info">Disetujui Atasan</span>',
            'disetujui_manager' => '<span class="badge bg-primary">Disetujui Manager</span>',
            'disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'ditolak_atasan' => '<span class="badge bg-danger">Ditolak Atasan</span>',
            'ditolak_manager' => '<span class="badge bg-danger">Ditolak Manager</span>',
            'ditolak_kepala_wilayah' => '<span class="badge bg-danger">Ditolak Kepala Wilayah</span>',
            'dibatalkan' => '<span class="badge bg-secondary">Dibatalkan</span>',
            'aktif' => '<span class="badge bg-success">Aktif</span>',
            'nonaktif' => '<span class="badge bg-secondary">Nonaktif</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">' . $status . '</span>';
    }

    /**
     * Get role label
     * @param string $role
     * @return string
     */
    public static function getRoleLabel($role)
    {
        $labels = [
            'admin' => 'Admin/HC',
            'pekerja' => 'Pekerja',
            'atasan' => 'Atasan Langsung',
            'manager' => 'Manager Wilayah',
            'kepala_wilayah' => 'Kepala Wilayah'
        ];

        return $labels[$role] ?? $role;
    }

    /**
     * Generate pagination HTML
     * @param int $currentPage
     * @param int $totalPages
     * @param string $baseUrl
     * @return string
     */
    public static function pagination($currentPage, $totalPages, $baseUrl)
    {
        if ($totalPages <= 1) {
            return '';
        }

        $html = '<nav><ul class="pagination">';

        // Previous button
        if ($currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">Previous</a></li>';
        }

        // Page numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = ($i === $currentPage) ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
        }

        // Next button
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">Next</a></li>';
        }

        $html .= '</ul></nav>';

        return $html;
    }

    /**
     * Log activity
     * @param string $aktivitas
     * @param string $modul
     */
    public static function logActivity($aktivitas, $modul)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "INSERT INTO log_aktivitas (id_user, aktivitas, modul, ip_address, user_agent) 
                VALUES (:id_user, :aktivitas, :modul, :ip_address, :user_agent)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id_user' => Session::getUserId(),
            ':aktivitas' => $aktivitas,
            ':modul' => $modul,
            ':ip_address' => $_SERVER['REMOTE_ADDR'],
            ':user_agent' => $_SERVER['HTTP_USER_AGENT']
        ]);
    }

    /**
     * Redirect with flash message
     * @param string $url
     * @param string $type
     * @param string $message
     */
    public static function redirectWithFlash($url, $type, $message)
    {
        Session::setFlash($type, $message);
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }

    /**
     * Check if request is AJAX
     * @return bool
     */
    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Debug variable (only in development)
     * @param mixed $var
     * @param bool $die
     */
    public static function debug($var, $die = false)
    {
        if (APP_ENV === 'development') {
            echo '<pre>';
            print_r($var);
            echo '</pre>';
            if ($die) {
                die();
            }
        }
    }
}
