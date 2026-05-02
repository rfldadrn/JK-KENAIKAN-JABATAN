<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - <?= APP_NAME ?></title>
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
        .forgot-container {
            width: 100%;
            max-width: 500px;
            padding: 15px;
        }
        .forgot-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }
        .forgot-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .forgot-header i {
            color: #0052CC;
            margin-bottom: 15px;
        }
        .forgot-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .forgot-header p {
            color: #666;
            font-size: 0.9rem;
        }
        .btn-back {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <i class="fas fa-lock fa-3x"></i>
                <h2>Lupa Password?</h2>
                <p>Silakan hubungi Administrator/HC untuk reset password Anda</p>
            </div>

            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Informasi:</strong><br>
                Untuk keamanan, reset password hanya dapat dilakukan oleh Administrator sistem. 
                Silakan hubungi bagian Human Capital (HC) untuk bantuan.
            </div>

            <div class="text-center mt-4">
                <p class="mb-2"><strong>Kontak HC:</strong></p>
                <p class="mb-1"><i class="fas fa-phone me-2"></i> (0751) 123-4567</p>
                <p class="mb-1"><i class="fas fa-envelope me-2"></i> hc@bri-padang.co.id</p>
            </div>

            <a href="<?= BASE_URL ?>/auth/login" class="btn btn-primary btn-back">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
