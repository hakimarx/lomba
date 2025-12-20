<?php
include __DIR__ . "/../../global/class/fungsi.php";
include __DIR__ . "/../dbku.php";
include __DIR__ . "/sesi.php";

// Process login BEFORE any HTML output
$login_error = "";
if (!empty($_POST['username'])) {
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];

    $query = "select * from user where username='$username' and password='$passwd' limit 1";
    $datauser = getdata($query);
    if (mysqli_num_rows($datauser) == 1) {
        $rowuser = mysqli_fetch_array($datauser);
        setlogin($rowuser['id']);
        header("location:../?page=utama");
        exit;
    } else {
        $login_error = "Login gagal! Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Musabaqah System</title>
    <link rel="stylesheet" href="../../global/css/modern.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e1e2e 50%, #0f172a 100%);
            position: relative;
            overflow: hidden;
        }

        /* Animated background shapes */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            animation: float 20s ease-in-out infinite;
        }

        body::before {
            width: 500px;
            height: 500px;
            background: rgba(16, 185, 129, 0.15);
            top: -200px;
            right: -100px;
        }

        body::after {
            width: 400px;
            height: 400px;
            background: rgba(6, 182, 212, 0.1);
            bottom: -150px;
            left: -100px;
            animation-delay: -10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            25% {
                transform: translate(30px, -30px) rotate(5deg);
            }

            50% {
                transform: translate(-20px, 20px) rotate(-5deg);
            }

            75% {
                transform: translate(20px, 10px) rotate(3deg);
            }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 60px rgba(16, 185, 129, 0.1);
            overflow: hidden;
            animation: cardAppear 0.6s ease-out;
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .login-header .icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 32px;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .login-header h1 {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin: 8px 0 0;
            position: relative;
            z-index: 1;
        }

        .login-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #f8fafc;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .form-input::placeholder {
            color: #64748b;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .demo-info {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 0.8rem;
            color: #34d399;
        }

        .demo-info .title {
            font-weight: 600;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .demo-info .credentials {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-family: monospace;
            color: #94a3b8;
        }

        .demo-info code {
            background: rgba(0, 0, 0, 0.3);
            padding: 2px 6px;
            border-radius: 4px;
            color: #34d399;
        }

        .login-footer {
            text-align: center;
            padding: 20px 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .login-footer p {
            color: #64748b;
            font-size: 0.8rem;
            margin: 0;
        }

        .login-footer a {
            color: #10b981;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <?php if (!empty($login_error)): ?>
        <script>
            alert('<?php echo $login_error; ?>');
        </script>
    <?php endif; ?>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="icon">🔐</div>
                <h1>Musabaqah</h1>
                <p>Sistem Manajemen Musabaqah & Hafidz</p>
            </div>

            <div class="login-body">
                <div class="demo-info">
                    <div class="title">💡 Demo Credentials</div>
                    <div class="credentials">
                        <span>Admin Provinsi: <code>adminprov / adminprov</code></span>
                        <span>Operator: <code>operator / operator</code></span>
                    </div>
                </div>

                <form action="" method="post">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-input" required value="adminprov" placeholder="Masukkan username">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="passwd" class="form-input" required value="adminprov" placeholder="Masukkan password">
                    </div>

                    <button type="submit" class="login-btn">
                        🚀 Login
                    </button>
                </form>
            </div>

            <div class="login-footer">
                <p>© 2025 Musabaqah System · <a href="#">Bantuan</a></p>
            </div>
        </div>
    </div>

</body>

</html>