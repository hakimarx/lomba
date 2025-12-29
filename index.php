<?php
// Load Configuration
include_once __DIR__ . "/global/class/Config.php";

// Process login at the very top to prevent "headers already sent" error
$login_error = "";
if (!empty($_POST['username'])) {
    include_once __DIR__ . "/global/class/fungsi.php";
    include_once __DIR__ . "/musabaqah/dbku.php";
    include_once __DIR__ . "/musabaqah/login/sesi.php";

    $username = $_POST['username'];
    $passwd = $_POST['passwd'];

    $query = "select * from user where username='$username' and password='$passwd' limit 1";
    $datauser = getdata($query);
    if (mysqli_num_rows($datauser) == 1) {
        $rowuser = mysqli_fetch_array($datauser);
        setlogin($rowuser['id']);
        header("location:musabaqah/?page=utama");
        exit;
    } else {
        $login_error = "Login gagal! Username atau password salah.";
    }
}

// Get config values
$appName = Config::get('app.name', 'E-LPTQ');
$appDesc = Config::get('app.description', 'Sistem Manajemen Musabaqah & Hafidz');
$metaDesc = Config::get('app.meta_desc', 'E-LPTQ: Sistem Manajemen Musabaqah & Hafidz');
$logoEmoji = Config::get('app.logo_emoji', '🕌');
$themeColor = Config::get('theme.primary', '#10b981');
$copyrightYear = Config::get('copyright.year', '2025');
$copyrightHolder = Config::get('copyright.holder', 'E-LPTQ');
$hafizLoginLink = Config::get('links.hafiz_login', 'musabaqah/hafidz/login.php');
$emaqraLink = Config::get('links.emaqra', 'emaqra/');
?>
<!DOCTYPE html>
<html lang="<?php echo Config::get('app.lang', 'id'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="<?php echo $themeColor; ?>">
    <meta name="description" content="<?php echo $metaDesc; ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?php echo $appName; ?>">
    <link rel="manifest" href="manifest.php">
    <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
    <title><?php echo $appName; ?> - <?php echo $appDesc; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e1e2e 50%, #0f172a 100%);
            position: relative;
            overflow: hidden;
        }

        /* Animated background */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            animation: float 20s ease-in-out infinite;
        }

        body::before {
            width: 600px;
            height: 600px;
            background: rgba(16, 185, 129, 0.12);
            top: -200px;
            right: -100px;
        }

        body::after {
            width: 400px;
            height: 400px;
            background: rgba(99, 102, 241, 0.1);
            bottom: -150px;
            left: -100px;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(5deg); }
            66% { transform: translate(-20px, 20px) rotate(-5deg); }
        }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 4em;
            margin-bottom: 16px;
        }

        .header h1 {
            font-size: 2.5em;
            font-weight: 700;
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95em;
        }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9em;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .form-input::placeholder { color: rgba(255, 255, 255, 0.3); }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(16, 185, 129, 0.4);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9em;
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 32px;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85em;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.85em;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .divider span { padding: 0 16px; }

        .quick-links {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .quick-link {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.85em;
            transition: all 0.2s ease;
        }

        .quick-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        @media (max-width: 480px) {
            .login-card { padding: 32px 24px; }
            .header h1 { font-size: 2em; }
            .logo { font-size: 3em; }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-card">
            <div class="header">
                <div class="logo"><?php echo $logoEmoji; ?></div>
                <h1><?php echo $appName; ?></h1>
                <p><?php echo $appDesc; ?></p>
            </div>

            <?php if ($login_error): ?>
                <div class="error-message"><?php echo $login_error; ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input" placeholder="Masukkan username" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="passwd" class="form-input" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-login">🔐 Masuk</button>
            </form>

            <div class="divider">
                <span>atau</span>
            </div>

            <div class="quick-links">
                <a href="<?php echo $hafizLoginLink; ?>" class="quick-link">📚 Login Hafidz</a>
                <a href="<?php echo $emaqraLink; ?>" class="quick-link">📖 Emaqra Tools</a>
            </div>

            <div class="footer">
                <p>&copy; <?php echo $copyrightYear . ' ' . $copyrightHolder; ?>. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/lomba/sw.js')
                    .then((registration) => {
                        console.log('SW registered:', registration.scope);
                    })
                    .catch((error) => {
                        console.log('SW registration failed:', error);
                    });
            });
        }
    </script>
</body>

</html>