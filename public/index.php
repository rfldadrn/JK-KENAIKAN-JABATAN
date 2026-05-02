<?php
/**
 * index.php - Application Entry Point
 * All requests are routed through this file
 */

// Start session
session_name('sikgol_bri_session');
session_start();

// Load configuration
require_once '../app/config/Config.php';
require_once '../app/config/Database.php';

// Load core classes
require_once '../core/App.php';
require_once '../core/Controller.php';
require_once '../core/Model.php';

// Load helpers
require_once '../app/helpers/Session.php';
require_once '../app/helpers/Helper.php';
require_once '../app/helpers/Validation.php';
require_once '../app/helpers/Upload.php';

// Initialize application
$app = new App();
