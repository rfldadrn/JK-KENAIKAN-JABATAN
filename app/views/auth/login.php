<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0052CC 0%, #003D99 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #0052CC 0%, #003D99 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-header img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }
        .login-header h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .login-header p {
            font-size: 0.9rem;
            margin: 0;
            opacity: 0.9;
        }
        .login-body {
            padding: 40px 35px;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }
        .form-control {
            border-left: none;
            padding: 12px;
        }
        .form-control:focus {
            border-color: #0052CC;
            box-shadow: 0 0 0 0.2rem rgba(0, 82, 204, 0.25);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(135deg, #0052CC 0%, #003D99 100%);
            border: none;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 82, 204, 0.3);
        }
        .alert {
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .footer-text {
            text-align: center;
            color: white;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .forgot-password {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 20px;
        }
        .forgot-password a {
            color: #0052CC;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-building fa-3x mb-3"></i>
                <h1><?= APP_NAME ?></h1>
                <p>Sistem Informasi Kenaikan Golongan Jabatan</p>
            </div>
            
            <div class="login-body">
                <?php
                // Display flash message
                $flash = Session::getFlash();
                if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
                        <?= Helper::escape($flash['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= Helper::escape($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/auth/login">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" 
                                   class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Masukkan username"
                                   value="<?= isset($old['username']) ? Helper::escape($old['username']) : '' ?>"
                                   autofocus>
                        </div>
                        <?php if (isset($errors['username'])): ?>
                            <div class="text-danger small"><?= $errors['username'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password">
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <div class="text-danger small"><?= $errors['password'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="forgot-password">
                        <a href="<?= BASE_URL ?>/auth/forgotPassword">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Gunakan username dan password yang telah diberikan oleh Admin
                    </small>
                </div>
            </div>
        </div>

        <div class="footer-text">
            <p>&copy; <?= date('Y') ?> Bank Rakyat Indonesia - Wilayah Padang</p>
            <p class="mb-0">Version <?= APP_VERSION ?></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
