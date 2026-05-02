# STRUKTUR FOLDER & IMPLEMENTASI MVC
## SISTEM INFORMASI PENGAJUAN KENAIKAN GOLONGAN JABATAN PEKERJA
### PHP NATIVE - MVC ARCHITECTURE

---

## 1. FOLDER STRUCTURE

```
sistem_kenaikan_golongan_bri/
│
├── app/
│   ├── config/
│   │   ├── Database.php          # Konfigurasi database
│   │   └── Config.php             # Konfigurasi aplikasi (base_url, dll)
│   │
│   ├── controllers/
│   │   ├── AuthController.php    # Login, Logout, Register
│   │   ├── DashboardController.php
│   │   ├── PengajuanController.php
│   │   ├── ApprovalController.php
│   │   ├── PekerjaController.php
│   │   ├── GolonganController.php
│   │   ├── DivisiController.php
│   │   ├── JabatanController.php
│   │   ├── LaporanController.php
│   │   ├── NotifikasiController.php
│   │   └── ProfilController.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── Pekerja.php
│   │   ├── GolonganJabatan.php
│   │   ├── Divisi.php
│   │   ├── Jabatan.php
│   │   ├── Pengajuan.php
│   │   ├── DokumenPengajuan.php
│   │   ├── ApprovalHistory.php
│   │   ├── Notifikasi.php
│   │   └── LogAktivitas.php
│   │
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   ├── sidebar.php
│   │   │   └── navbar.php
│   │   │
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── forgot_password.php
│   │   │
│   │   ├── dashboard/
│   │   │   ├── admin.php
│   │   │   ├── pekerja.php
│   │   │   ├── atasan.php
│   │   │   ├── manager.php
│   │   │   └── kepala_wilayah.php
│   │   │
│   │   ├── pengajuan/
│   │   │   ├── index.php         # List pengajuan
│   │   │   ├── create.php        # Form buat pengajuan
│   │   │   ├── detail.php        # Detail pengajuan
│   │   │   └── riwayat.php       # Riwayat pengajuan
│   │   │
│   │   ├── approval/
│   │   │   ├── index.php         # List pending approval
│   │   │   └── review.php        # Form review approval
│   │   │
│   │   ├── pekerja/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   │
│   │   ├── golongan/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   │
│   │   ├── divisi/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   │
│   │   ├── jabatan/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   │
│   │   ├── laporan/
│   │   │   ├── pengajuan.php
│   │   │   ├── pekerja_per_golongan.php
│   │   │   └── riwayat_kenaikan.php
│   │   │
│   │   └── profil/
│   │       └── index.php
│   │
│   └── helpers/
│       ├── Session.php            # Helper untuk session
│       ├── Validation.php         # Helper validasi
│       ├── Upload.php             # Helper upload file
│       ├── Email.php              # Helper email (PHPMailer)
│       ├── Pdf.php                # Helper generate PDF (TCPDF/FPDF)
│       └── Helper.php             # Helper umum (format tanggal, dll)
│
├── core/
│   ├── App.php                    # Router utama
│   ├── Controller.php             # Base controller
│   └── Model.php                  # Base model
│
├── public/
│   ├── index.php                  # Entry point
│   ├── .htaccess                  # URL Rewriting
│   │
│   ├── assets/
│   │   ├── css/
│   │   │   ├── bootstrap.min.css
│   │   │   ├── style.css
│   │   │   └── custom.css
│   │   │
│   │   ├── js/
│   │   │   ├── jquery.min.js
│   │   │   ├── bootstrap.bundle.min.js
│   │   │   ├── chart.js
│   │   │   └── custom.js
│   │   │
│   │   ├── img/
│   │   │   ├── logo-bri.png
│   │   │   └── default-avatar.png
│   │   │
│   │   └── vendor/
│   │       ├── fontawesome/
│   │       └── datatables/
│   │
│   └── uploads/
│       ├── documents/            # Upload dokumen pengajuan
│       │   └── {tahun}/{bulan}/{nip}/
│       ├── sk/                   # File SK
│       │   └── {tahun}/
│       └── foto/                 # Foto pekerja
│           └── {nip}/
│
├── vendor/                       # Composer dependencies
│   └── autoload.php
│
├── composer.json
├── .env                          # Environment variables
├── .env.example
└── README.md
```

---

## 2. CORE FILES

### 2.1 public/index.php (Entry Point)

```php
<?php
/**
 * Entry Point Aplikasi
 * Sistem Informasi Kenaikan Golongan Jabatan BRI
 */

// Error Reporting (Development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Start
session_start();

// Autoload
require_once '../vendor/autoload.php';

// Load Environment Variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Load Config
require_once '../app/config/Config.php';
require_once '../app/config/Database.php';

// Load Core
require_once '../core/App.php';
require_once '../core/Controller.php';
require_once '../core/Model.php';

// Load Helpers
require_once '../app/helpers/Session.php';
require_once '../app/helpers/Helper.php';
require_once '../app/helpers/Validation.php';

// Initialize App
$app = new App();
```

### 2.2 public/.htaccess (URL Rewriting)

```apache
RewriteEngine On

# Jika folder/file tidak ada, arahkan ke index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]

# Prevent directory listing
Options -Indexes

# Security Headers
<IfModule mod_headers.c>
    Header set X-XSS-Protection "1; mode=block"
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
</IfModule>
```

---

### 2.3 core/App.php (Router)

```php
<?php
/**
 * Core App - Router
 */

class App {
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Check if controller exists
        if(isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            if(file_exists('../app/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // Require controller
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check if method exists
        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call controller & method with params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
```

---

### 2.4 core/Controller.php (Base Controller)

```php
<?php
/**
 * Base Controller
 */

class Controller {
    
    // Load model
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        // Extract data to variables
        extract($data);

        // Check if view file exists
        if(file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die('View does not exist: ' . $view);
        }
    }

    // Redirect
    public function redirect($url) {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }

    // Check if user is logged in
    public function isLoggedIn() {
        return Session::isLoggedIn();
    }

    // Check user role
    public function checkRole($allowedRoles = []) {
        if(!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }

        $userRole = Session::get('role');
        
        if(!empty($allowedRoles) && !in_array($userRole, $allowedRoles)) {
            Session::flash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('dashboard');
        }
    }

    // Flash message
    public function setFlash($type, $message) {
        Session::flash($type, $message);
    }
}
```

---

### 2.5 core/Model.php (Base Model)

```php
<?php
/**
 * Base Model
 */

class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Get all records
    public function getAll($orderBy = 'id') {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get by ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Insert
    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        foreach($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        return $stmt->execute();
    }

    // Update
    public function update($id, $data) {
        $set = [];
        foreach($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(', ', $set);
        
        $sql = "UPDATE {$this->table} SET {$set} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        foreach($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

    // Delete
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Execute custom query
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Count records
    public function count($where = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if(!empty($where)) {
            $conditions = [];
            foreach($where as $key => $value) {
                $conditions[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $stmt = $this->db->prepare($sql);
        
        foreach($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }
}
```

---

## 3. CONFIG FILES

### 3.1 app/config/Database.php

```php
<?php
/**
 * Database Configuration & Connection
 * Singleton Pattern
 */

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_NAME'] ?? 'sistem_kenaikan_golongan_bri';
        $username = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASS'] ?? '';

        try {
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
```

---

### 3.2 app/config/Config.php

```php
<?php
/**
 * Application Configuration
 */

// Base URL
define('BASE_URL', $_ENV['APP_URL'] ?? 'http://localhost/sistem_kenaikan_golongan_bri/public');

// App Name
define('APP_NAME', 'Sistem Kenaikan Golongan BRI');

// Upload Directories
define('UPLOAD_DIR', __DIR__ . '/../../public/uploads/');
define('UPLOAD_DOCUMENTS', UPLOAD_DIR . 'documents/');
define('UPLOAD_SK', UPLOAD_DIR . 'sk/');
define('UPLOAD_FOTO', UPLOAD_DIR . 'foto/');

// Upload Max Size (2MB)
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024);

// Allowed File Types
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf', 'image/jpeg', 'image/png']);

// Email Configuration
define('SMTP_HOST', $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
define('SMTP_PORT', $_ENV['SMTP_PORT'] ?? 587);
define('SMTP_USER', $_ENV['SMTP_USER'] ?? '');
define('SMTP_PASS', $_ENV['SMTP_PASS'] ?? '');
define('SMTP_FROM', $_ENV['SMTP_FROM'] ?? 'noreply@bri.co.id');
define('SMTP_NAME', $_ENV['SMTP_NAME'] ?? 'Sistem Kenaikan Golongan BRI');

// Session Timeout (30 minutes)
define('SESSION_TIMEOUT', 1800);

// Pagination
define('PER_PAGE', 10);
```

---

### 3.3 .env.example

```env
# Application
APP_URL=http://localhost/sistem_kenaikan_golongan_bri/public
APP_ENV=development

# Database
DB_HOST=localhost
DB_NAME=sistem_kenaikan_golongan_bri
DB_USER=root
DB_PASS=

# Email SMTP
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your-email@gmail.com
SMTP_PASS=your-app-password
SMTP_FROM=noreply@bri.co.id
SMTP_NAME=Sistem Kenaikan Golongan BRI
```

---

### 3.4 composer.json

```json
{
    "name": "bri/sistem-kenaikan-golongan",
    "description": "Sistem Informasi Pengajuan Kenaikan Golongan Jabatan Pekerja BRI Wilayah Padang",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "vlucas/phpdotenv": "^5.4",
        "phpmailer/phpmailer": "^6.6",
        "tecnickcom/tcpdf": "^6.4",
        "mpdf/mpdf": "^8.1"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}
```

---

## 4. IMPLEMENTASI SAMPLE

### 4.1 Example: AuthController.php

```php
<?php
/**
 * Auth Controller
 */

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function login() {
        // Jika sudah login, redirect ke dashboard
        if($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        // POST Request - Process Login
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = Validation::sanitize($_POST['username']);
            $password = $_POST['password'];

            // Validate
            $errors = [];
            if(empty($username)) {
                $errors[] = 'Username wajib diisi';
            }
            if(empty($password)) {
                $errors[] = 'Password wajib diisi';
            }

            if(!empty($errors)) {
                $data = [
                    'title' => 'Login',
                    'errors' => $errors
                ];
                $this->view('auth/login', $data);
                return;
            }

            // Check credentials
            $user = $this->userModel->getUserByUsername($username);

            if($user && password_verify($password, $user->password)) {
                // Check if user is active
                if($user->is_active == 0) {
                    $this->setFlash('error', 'Akun Anda tidak aktif. Hubungi administrator.');
                    $this->redirect('auth/login');
                    return;
                }

                // Set session
                Session::set('user_id', $user->id_user);
                Session::set('username', $user->username);
                Session::set('email', $user->email);
                Session::set('role', $user->role);
                Session::set('id_pekerja', $user->id_pekerja);

                // Update last login
                $this->userModel->updateLastLogin($user->id_user);

                // Log activity
                $this->userModel->logActivity($user->id_user, 'Login ke sistem', 'auth');

                // Redirect to dashboard
                $this->redirect('dashboard');
            } else {
                $this->setFlash('error', 'Username atau password salah');
                $this->redirect('auth/login');
            }
        } else {
            // GET Request - Show login form
            $data = [
                'title' => 'Login - ' . APP_NAME
            ];
            $this->view('auth/login', $data);
        }
    }

    public function logout() {
        Session::destroy();
        $this->redirect('auth/login');
    }
}
```

---

### 4.2 Example: User.php (Model)

```php
<?php
/**
 * User Model
 */

class User extends Model {
    protected $table = 'users';

    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function updateLastLogin($userId) {
        $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id_user = :id");
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function logActivity($userId, $activity, $module) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $stmt = $this->db->prepare("
            INSERT INTO log_aktivitas (id_user, aktivitas, modul, ip_address, user_agent) 
            VALUES (:user_id, :activity, :module, :ip_address, :user_agent)
        ");

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':activity', $activity);
        $stmt->bindParam(':module', $module);
        $stmt->bindParam(':ip_address', $ipAddress);
        $stmt->bindParam(':user_agent', $userAgent);

        return $stmt->execute();
    }
}
```

---

## 5. NEXT STEPS IMPLEMENTATION

1. **Setup Database** → Import SQL schema dan sample data
2. **Install Dependencies** → `composer install`
3. **Setup Environment** → Copy `.env.example` to `.env` dan sesuaikan
4. **Configure Web Server** → Setup virtual host di Laragon
5. **Develop Controllers** → Implementasi semua controller
6. **Develop Models** → Implementasi semua model
7. **Develop Views** → Implementasi semua view dengan Bootstrap
8. **Implement Helpers** → Upload, Email, PDF, Validation
9. **Testing** → Unit testing & integration testing
10. **Deployment** → Deploy ke server production

---

**Document Version**: 1.0  
**Last Updated**: 2024
