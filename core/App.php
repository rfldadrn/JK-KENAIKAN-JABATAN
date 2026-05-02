<?php
/**
 * App.php - Main Router/Application Class
 * Handles URL routing and controller dispatching
 */

class App
{
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // Check if controller exists
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        // Require the controller
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check if method exists
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        } else {
            // If no method specified, try 'index', otherwise keep default
            if (method_exists($this->controller, 'index')) {
                $this->method = 'index';
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call the controller method with params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parse URL from REQUEST_URI
     * Format: /controller/method/param1/param2
     */
    private function parseURL()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            // Remove query string
            $uri = strtok($_SERVER['REQUEST_URI'], '?');
            
            // Remove base path if exists
            $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
            if ($scriptName !== '/') {
                $uri = str_replace($scriptName, '', $uri);
            }
            
            // Clean and explode
            $url = rtrim($uri, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', trim($url, '/'));
            
            return $url;
        }
        
        return [];
    }
}
