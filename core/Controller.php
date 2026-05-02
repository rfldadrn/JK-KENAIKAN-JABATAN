<?php
/**
 * Controller.php - Base Controller Class
 * All controllers extend this base class
 */

class Controller
{
    /**
     * Load view file
     * @param string $view - View file path (without .php)
     * @param array $data - Data to pass to view
     */
    public function view($view, $data = [])
    {
        // Extract data array to variables
        extract($data);
        
        // Check if view file exists
        $viewFile = '../app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . $view);
        }
    }

    /**
     * Load model
     * @param string $model - Model name
     * @return object - Model instance
     */
    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    /**
     * Redirect to another page
     * @param string $url - URL to redirect to
     */
    public function redirect($url)
    {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }

    /**
     * Set flash message
     * @param string $type - Message type (success, error, warning, info)
     * @param string $message - Message text
     */
    public function setFlash($type, $message)
    {
        Session::setFlash($type, $message);
    }

    /**
     * Check if user is logged in
     * @return bool
     */
    public function isLoggedIn()
    {
        return Session::isLoggedIn();
    }

    /**
     * Check user role
     * @param string|array $roles - Required role(s)
     * @return bool
     */
    public function checkRole($roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        return in_array(Session::get('role'), $roles);
    }

    /**
     * Middleware: Require login
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->setFlash('error', 'Silakan login terlebih dahulu');
            $this->redirect('auth/login');
        }
    }

    /**
     * Middleware: Require specific role
     * @param string|array $roles
     */
    protected function requireRole($roles)
    {
        $this->requireLogin();
        if (!$this->checkRole($roles)) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('dashboard');
        }
    }

    /**
     * Return JSON response
     * @param mixed $data
     * @param int $statusCode
     */
    public function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Get POST data
     * @param string $key - Key name
     * @param mixed $default - Default value if not exists
     * @return mixed
     */
    protected function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    /**
     * Get GET data
     * @param string $key - Key name
     * @param mixed $default - Default value if not exists
     * @return mixed
     */
    protected function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
}
