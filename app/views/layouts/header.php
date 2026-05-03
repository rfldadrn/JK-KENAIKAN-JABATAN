<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?><?= APP_NAME ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    
    <style>
        :root {
            --primary-color: #0052CC;
            --secondary-color: #003D99;
            --sidebar-width: 260px;
            --navbar-height: 60px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
        }
        
        /* Navbar */
        .navbar-main {
            height: var(--navbar-height);
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0 20px;
        }
        
        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .navbar-brand i {
            margin-right: 10px;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            overflow-y: auto;
            padding: 20px 0;
            z-index: 999;
            transition: all 0.3s;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li a {
            display: block;
            padding: 12px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu li a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: white;
            color: white;
        }
        
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.2);
            border-left-color: white;
            color: white;
            font-weight: 600;
        }
        
        .sidebar-menu li a i {
            width: 25px;
            margin-right: 10px;
        }
        
        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 15px 20px;
        }
        
        .sidebar-heading {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 10px 25px;
            margin-top: 10px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 25px;
            min-height: calc(100vh - var(--navbar-height));
        }
        
        /* Content Header */
        .content-header {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .content-header h1 {
            font-size: 1.8rem;
            margin: 0;
            color: #333;
        }
        
        .breadcrumb {
            margin: 0;
            padding: 0;
            background: none;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        
        .card-header {
            background: white;
            border-bottom: 2px solid #f0f0f0;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        /* User Profile Dropdown */
        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 8px;
            transition: background 0.3s;
        }
        
        .user-profile:hover {
            background: #f8f9fa;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }
        
        .user-info {
            text-align: right;
            margin-right: 10px;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            display: block;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: #666;
        }
        
        /* Notification Badge */
        .notification-icon {
            position: relative;
            margin-right: 20px;
            font-size: 1.3rem;
            color: #666;
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    <?php if (isset($additionalCSS)): ?>
        <?= $additionalCSS ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg">
        <div class="container-fluid">
            <button class="btn btn-link d-lg-none me-3" id="sidebar-toggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard">
                <i class="fas fa-building"></i>
                <?= APP_NAME ?>
            </a>
            
            <div class="ms-auto d-flex align-items-center">
                <!-- Notifications -->
                <div class="notification-icon" id="notification-icon">
                    <i class="fas fa-bell"></i>
                    <?php if (isset($unreadNotifications) && $unreadNotifications > 0): ?>
                        <span class="notification-badge"><?= $unreadNotifications ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <div class="user-profile" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            <?= strtoupper(substr(Session::get('nama_lengkap', Session::get('username')), 0, 1)) ?>
                        </div>
                        <div class="user-info d-none d-md-block">
                            <span class="user-name"><?= Helper::escape(Session::get('nama_lengkap', Session::get('username'))) ?></span>
                            <span class="user-role"><?= Helper::getRoleLabel(Session::getRole()) ?></span>
                        </div>
                        <i class="fas fa-chevron-down ms-2"></i>
                    </div>
                    
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/profil">
                                <i class="fas fa-user me-2"></i>Profil Saya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/auth/changePassword">
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>


    <!-- Flash Message (Presisi, di luar main-content) -->
    <?php if ($flash = Session::getFlash()): ?>
        <?php
            $alertClass = 'alert-info';
            if ($flash['type'] === 'success') $alertClass = 'alert-success';
            elseif ($flash['type'] === 'error') $alertClass = 'alert-danger';
            elseif ($flash['type'] === 'warning') $alertClass = 'alert-warning';
        ?>
        <div class="container-fluid" style="max-width: 700px; margin: calc(var(--navbar-height, 60px) + 20px) auto 0 auto;">
            <div class="alert <?= $alertClass ?> alert-dismissible fade show text-center fw-semibold" role="alert">
                <?= Helper::escape($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
