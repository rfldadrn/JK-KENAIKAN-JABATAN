<?php
/**
 * Config.php - Application Configuration
 * Contains all application constants and settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistem_kenaikan_golongan_bri');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application Configuration
define('APP_NAME', 'Sistem Kenaikan Golongan BRI');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, production

// URL Configuration
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', $protocol . '://' . $host . $scriptName);

// Path Configuration
define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Upload Configuration
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg']);
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg']);

// Session Configuration
define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds
define('SESSION_NAME', 'sikgol_bri_session');

// Pagination Configuration
define('ITEMS_PER_PAGE', 10);

// Date/Time Configuration
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i:s');
define('TIMEZONE', 'Asia/Jakarta');
date_default_timezone_set(TIMEZONE);

// Email Configuration (for notifications)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your_email@gmail.com');
define('SMTP_PASS', 'your_password');
define('SMTP_FROM', 'noreply@sikgol-bri.com');
define('SMTP_FROM_NAME', 'Sistem Kenaikan Golongan BRI');

// Business Rules Configuration
define('MIN_MASA_KERJA_TAHUN', 2); // Minimal 2 tahun untuk kenaikan golongan
define('MIN_NILAI_KINERJA', 80); // Minimal nilai kinerja 80
define('BATAS_REVIEW_HARI', 7); // Batas waktu review approval: 7 hari

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
