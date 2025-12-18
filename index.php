<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Lomba - MTQ & Emaqra</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            width: 100%;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .header h1 {
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
        }

        .card-icon {
            font-size: 4em;
            margin-bottom: 20px;
        }

        .card h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.8em;
        }

        .card p {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .card ul {
            text-align: left;
            margin-bottom: 25px;
            color: #555;
        }

        .card ul li {
            margin-bottom: 8px;
            list-style-position: inside;
        }

        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1em;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .footer {
            text-align: center;
            color: white;
            margin-top: 50px;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }

            .card {
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🕌 Sistem Lomba</h1>
            <p>Pilih aplikasi yang ingin Anda gunakan</p>
        </div>

        <div class="cards">
            <!-- Musabaqah Card -->
            <div class="card">
                <div class="card-icon">📊</div>
                <h2>MTQ Management</h2>
                <p>Sistem manajemen Musabaqah Tilawatil Quran lengkap dengan fitur administrasi dan penilaian</p>
                <ul>
                    <li>Manajemen Event & Peserta</li>
                    <li>Sistem Penilaian</li>
                    <li>Manajemen Hafidz</li>
                    <li>Laporan & Rekap</li>
                    <li>Multi-level Admin</li>
                </ul>
                <a href="musabaqah/" class="btn">Masuk ke MTQ</a>
            </div>

            <!-- Emaqra Card -->
            <div class="card">
                <div class="card-icon">📖</div>
                <h2>Emaqra Tools</h2>
                <p>Aplikasi pembelajaran Al-Quran dengan berbagai fitur untuk membantu hafalan dan pemahaman</p>
                <ul>
                    <li>Tilawah & MHQ</li>
                    <li>Tafsir Al-Quran</li>
                    <li>Mushaf Digital</li>
                    <li>Qiraat</li>
                    <li>MFQ/Hadits</li>
                </ul>
                <a href="emaqra/" class="btn btn-secondary">Buka Emaqra</a>
            </div>

            <!-- Hafidz Card -->
            <div class="card">
                <div class="card-icon">📚</div>
                <h2>Portal Hafidz</h2>
                <p>Portal khusus untuk Hafidz melaporkan kegiatan harian dan mengisi profil</p>
                <ul>
                    <li>Login Hafidz</li>
                    <li>Laporan Harian</li>
                    <li>Update Profil</li>
                    <li>Saran & Masukan</li>
                </ul>
                <a href="musabaqah/hafidz/login.php" class="btn" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">Login Hafidz</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2025 Sistem Lomba. All rights reserved.</p>
        </div>
    </div>
</body>

</html>