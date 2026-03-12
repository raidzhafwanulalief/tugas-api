<?php
/**
 * ============================================================
 * index.php - Halaman utama / landing page
 * ============================================================
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NontonFilmKuy API</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0f0f1a;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            text-align: center;
            padding: 40px;
        }
        h1 {
            font-size: 3rem;
            background: linear-gradient(135deg, #e50914, #ff6b35);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        p {
            color: #aaa;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }
        .btn-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 15px 35px;
            border-radius: 8px;
            font-size: 1rem;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.2s, opacity 0.2s;
        }
        .btn:hover { transform: translateY(-3px); opacity: 0.9; }
        .btn-primary { background: #e50914; color: white; }
        .btn-secondary { background: #1c1c2e; color: #e50914; border: 2px solid #e50914; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎬 NontonFilmKuy API</h1>
        <p>REST API Sistem Manajemen Film — PHP & MySQL</p>
        <div class="btn-group">
            <a href="dokumentasi.php" class="btn btn-primary">📄 Dokumentasi API</a>
            <a href="eksekusi_api.php" class="btn btn-secondary">⚡ Coba API</a>
        </div>
    </div>
</body>
</html>