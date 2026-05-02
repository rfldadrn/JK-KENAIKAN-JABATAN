<?php
/**
 * Session.php - Session Management Helper
 * Handles all session-related operations
 */

class Session
{
    /**
     * Set session variable
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session variable
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Check if session variable exists
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session variable
     * @param string $key
     */
    public static function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy all session data
     */
    public static function destroy()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Set flash message
     * @param string $type - success, error, warning, info
     * @param string $message
     */
    public static function setFlash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Get and remove flash message
     * @return array|null
     */
    public static function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Check if user is logged in
     * @return bool
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Set user session data
     * @param array $userData
     */
    public static function setUserData($userData)
    {
        $_SESSION['user_id'] = $userData['id_user'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['id_pekerja'] = $userData['id_pekerja'] ?? null;
        $_SESSION['nama_lengkap'] = $userData['nama_lengkap'] ?? null;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
    }

    /**
     * Get user ID
     * @return int|null
     */
    public static function getUserId()
    {
        return self::get('user_id');
    }

    /**
     * Get user role
     * @return string|null
     */
    public static function getRole()
    {
        return self::get('role');
    }

    /**
     * Get pekerja ID
     * @return int|null
     */
    public static function getPekerjaId()
    {
        return self::get('id_pekerja');
    }

    /**
     * Check if session is expired
     * @return bool
     */
    public static function isExpired()
    {
        if (!self::isLoggedIn()) {
            return true;
        }

        $loginTime = self::get('login_time', 0);
        $currentTime = time();
        $elapsed = $currentTime - $loginTime;

        if ($elapsed > SESSION_TIMEOUT) {
            return true;
        }

        // Update login time for activity tracking
        self::set('login_time', $currentTime);
        return false;
    }

    /**
     * Regenerate session ID (security measure)
     */
    public static function regenerate()
    {
        session_regenerate_id(true);
    }
}
