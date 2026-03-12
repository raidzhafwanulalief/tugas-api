<?php
/**
 * ============================================================
 * dokumentasi.php - Halaman dokumentasi lengkap REST API
 * ============================================================
 */
$baseUrl = 'https://nontonfilmkuy.xo.je/project_film_api';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi API - NontonFilmKuy</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0f0f1a;
            --surface: #1a1a2e;
            --card: #16213e;
            --accent: #e50914;
            --accent2: #ff6b35;
            --text: #e0e0e0;
            --muted: #888;
            --success: #00c851;
            --warning: #ffbb33;
            --danger: #ff4444;
            --info: #33b5e5;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.7;
        }
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 260px;
            height: 100vh;
            background: var(--surface);
            overflow-y: auto;
            padding: 20px 0;
            border-right: 1px solid #2a2a3e;
            z-index: 100;
        }
        .sidebar-logo {
            padding: 10px 20px 20px;
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--accent);
            border-bottom: 1px solid #2a2a3e;
            margin-bottom: 10px;
        }
        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .sidebar a:hover { color: var(--text); background: #2a2a3e; }
        .sidebar .section-title {
            padding: 15px 20px 5px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555;
        }
        /* Main content */
        .main {
            margin-left: 260px;
            padding: 40px;
            max-width: 900px;
        }
        h1 { font-size: 2.5rem; background: linear-gradient(135deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px; }
        h2 { font-size: 1.6rem; color: #fff; margin: 40px 0 15px; padding-bottom: 8px; border-bottom: 2px solid var(--accent); }
        h3 { font-size: 1.1rem; color: #ccc; margin: 20px 0 10px; }
        p { color: var(--muted); margin-bottom: 12px; }
        /* Badges method */
        .method {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-right: 8px;
        }
        .get    { background: #1a4a1a; color: var(--success); }
        .post   { background: #1a3a4a; color: var(--info); }
        .put    { background: #4a3a1a; color: var(--warning); }
        .delete { background: #4a1a1a; color: var(--danger); }
        /* Endpoint box */
        .endpoint {
            background: var(--card);
            border: 1px solid #2a2a3e;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 12px;
        }
        .endpoint-url {
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: #7ec8e3;
            background: #0a0a1a;
            padding: 8px 12px;
            border-radius: 4px;
            margin-top: 8px;
            display: block;
            word-break: break-all;
        }
        /* Code block */
        pre {
            background: #0a0a1a;
            border: 1px solid #2a2a3e;
            border-radius: 8px;
            padding: 20px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #a8d8a8;
            margin: 10px 0;
        }
        /* Table */
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: var(--surface); color: var(--accent); padding: 10px 15px; text-align: left; font-size: 0.85rem; }
        td { padding: 10px 15px; border-bottom: 1px solid #2a2a3e; font-size: 0.9rem; color: var(--muted); }
        td:first-child { color: #7ec8e3; font-family: monospace; }
        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .badge-req  { background: #4a1a1a; color: #ff6b6b; }
        .badge-opt  { background: #1a3a1a; color: #6bff6b; }
        /* Info box */
        .info-box {
            background: #1a2a3a;
            border-left: 4px solid var(--info);
            padding: 15px 20px;
            border-radius: 0 8px 8px 0;
            margin: 15px 0;
        }
        .info-box p { color: var(--text); margin: 0; }
        /* Status codes */
        .status-list { display: flex; gap: 10px; flex-wrap: wrap; margin: 10px 0; }
        .status-badge {
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .s200 { background: #1a4a1a; color: var(--success); }
        .s201 { background: #1a3a4a; color: var(--info); }
        .s400 { background: #4a3a1a; color: var(--warning); }
        .s404 { background: #3a1a3a; color: #cc88ff; }
        .s500 { background: #4a1a1a; color: var(--danger); }
        .home-btn {
            display: inline-block;
            padding: 8px 20px;
            background: var(--accent);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

<!-- Sidebar Navigasi -->
<nav class="sidebar">
    <div class="sidebar-logo">🎬 FilmKuy API</div>
    <div class="section-title">Umum</div>
    <a href="#pengantar">Pengantar</a>
    <a href="#database">Database</a>
    <a href="#status-code">Status Code</a>
    <a href="#format-response">Format Response</a>
    <div class="section-title">Endpoint Film</div>
    <a href="#film-get">GET Film</a>
    <a href="#film-post">POST Film</a>
    <a href="#film-put">PUT Film</a>
    <a href="#film-delete">DELETE Film</a>
    <a href="#film-fitur">Fitur Tambahan</a>
    <div class="section-title">Endpoint Genre</div>
    <a href="#genre-get">GET Genre</a>
    <a href="#genre-post">POST Genre</a>
    <a href="#genre-put">PUT Genre</a>
    <a href="#genre-delete">DELETE Genre</a>
    <div class="section-title">Endpoint Review</div>
    <a href="#review-get">GET Review</a>
    <a href="#review-post">POST Review</a>
    <a href="#review-put">PUT Review</a>
    <a href="#review-delete">DELETE Review</a>
    <div class="section-title">Panduan</div>
    <a href="#postman">Cara Pakai Postman</a>
</nav>

<!-- Konten Utama -->
<main class="main">
    <a href="index.php" class="home-btn">← Beranda</a>

    <h1>📄 Dokumentasi API</h1>
    <p>REST API Sistem Manajemen Film | PHP + MySQL | NontonFilmKuy</p>

    <!-- ===================== PENGANTAR ===================== -->
    <section id="pengantar">
        <h2>Pengantar</h2>
        <p>API ini menyediakan layanan CRUD lengkap untuk mengelola data <strong>film</strong>, <strong>genre</strong>, dan <strong>review</strong>. Semua response menggunakan format <strong>JSON</strong>. API dapat diakses dari Postman, browser, atau halaman eksekusi yang tersedia.</p>
        <div class="info-box">
            <p>🌐 <strong>Base URL:</strong> <code><?= $baseUrl ?></code></p>
        </div>
    </section>

    <!-- ===================== DATABASE ===================== -->
    <section id="database">
        <h2>Struktur Database</h2>
        <h3>Tabel: film</h3>
        <table>
            <tr><th>Field</th><th>Tipe</th><th>Keterangan</th></tr>
            <tr><td>id_film</td><td>INT (PK)</td><td>Primary Key, Auto Increment</td></tr>
            <tr><td>judul</td><td>VARCHAR(255)</td><td>Judul film</td></tr>
            <tr><td>tahun_rilis</td><td>YEAR</td><td>Tahun rilis film</td></tr>
            <tr><td>durasi</td><td>INT</td><td>Durasi dalam menit</td></tr>
            <tr><td>rating</td><td>DECIMAL(3,1)</td><td>Rating 0.0 - 10.0</td></tr>
            <tr><td>id_genre</td><td>INT (FK)</td><td>Referensi ke tabel genre</td></tr>
            <tr><td>tanggal_dibuat</td><td>DATETIME</td><td>Waktu data dibuat</td></tr>
        </table>
        <h3>Tabel: genre</h3>
        <table>
            <tr><th>Field</th><th>Tipe</th><th>Keterangan</th></tr>
            <tr><td>id_genre</td><td>INT (PK)</td><td>Primary Key, Auto Increment</td></tr>
            <tr><td>nama_genre</td><td>VARCHAR(100)</td><td>Nama genre</td></tr>
            <tr><td>deskripsi</td><td>TEXT</td><td>Deskripsi genre</td></tr>
        </table>
        <h3>Tabel: review</h3>
        <table>
            <tr><th>Field</th><th>Tipe</th><th>Keterangan</th></tr>
            <tr><td>id_review</td><td>INT (PK)</td><td>Primary Key, Auto Increment</td></tr>
            <tr><td>id_film</td><td>INT (FK)</td><td>Referensi ke tabel film</td></tr>
            <tr><td>nama_reviewer</td><td>VARCHAR(100)</td><td>Nama pengguna yang mereview</td></tr>
            <tr><td>komentar</td><td>TEXT</td><td>Isi komentar</td></tr>
            <tr><td>rating_review</td><td>DECIMAL(3,1)</td><td>Rating 0.0 - 10.0</td></tr>
            <tr><td>tanggal_review</td><td>DATETIME</td><td>Waktu review dibuat</td></tr>
        </table>
    </section>

    <!-- ===================== STATUS CODE ===================== -->
    <section id="status-code">
        <h2>HTTP Status Code</h2>
        <div class="status-list">
            <span class="status-badge s200">200 OK</span>
            <span class="status-badge s201">201 Created</span>
            <span class="status-badge s400">400 Bad Request</span>
            <span class="status-badge s404">404 Not Found</span>
            <span class="status-badge s500">500 Server Error</span>
        </div>
    </section>

    <!-- ===================== FORMAT RESPONSE ===================== -->
    <section id="format-response">
        <h2>Format Response</h2>
        <h3>✅ Response Sukses</h3>
        <pre>{
  "status": "success",
  "message": "Data berhasil diambil",
  "data": [ ... ]
}</pre>
        <h3>❌ Response Error</h3>
        <pre>{
  "status": "error",
  "message": "Data tidak ditemukan"
}</pre>
    </section>

    <!-- ===================== FILM GET ===================== -->
    <section id="film-get">
        <h2>🎬 Film — GET</h2>

        <div class="endpoint">
            <span class="method get">GET</span> <strong>Semua Film (dengan pagination)</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?page=1&limit=10</code>
        </div>

        <div class="endpoint">
            <span class="method get">GET</span> <strong>Film Berdasarkan ID</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?id=1</code>
        </div>

        <div class="endpoint">
            <span class="method get">GET</span> <strong>Cari Film Berdasarkan Judul</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?search=avengers</code>
        </div>

        <div class="endpoint">
            <span class="method get">GET</span> <strong>Filter Berdasarkan Genre</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?id_genre=2</code>
        </div>

        <div class="endpoint">
            <span class="method get">GET</span> <strong>Sorting</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?sort_by=rating&order=DESC</code>
        </div>

        <h3>Parameter Query String</h3>
        <table>
            <tr><th>Parameter</th><th>Tipe</th><th>Wajib</th><th>Keterangan</th></tr>
            <tr><td>id</td><td>integer</td><td><span class="badge badge-opt">Opsional</span></td><td>Ambil film berdasarkan ID</td></tr>
            <tr><td>search</td><td>string</td><td><span class="badge badge-opt">Opsional</span></td><td>Cari berdasarkan judul</td></tr>
            <tr><td>id_genre</td><td>integer</td><td><span class="badge badge-opt">Opsional</span></td><td>Filter berdasarkan genre</td></tr>
            <tr><td>sort_by</td><td>string</td><td><span class="badge badge-opt">Opsional</span></td><td>Kolom pengurutan (id_film, judul, tahun_rilis, durasi, rating)</td></tr>
            <tr><td>order</td><td>string</td><td><span class="badge badge-opt">Opsional</span></td><td>ASC atau DESC (default: ASC)</td></tr>
            <tr><td>page</td><td>integer</td><td><span class="badge badge-opt">Opsional</span></td><td>Halaman pagination (default: 1)</td></tr>
            <tr><td>limit</td><td>integer</td><td><span class="badge badge-opt">Opsional</span></td><td>Jumlah data per halaman (default: 10)</td></tr>
        </table>
    </section>

    <!-- ===================== FILM POST ===================== -->
    <section id="film-post">
        <h2>🎬 Film — POST</h2>
        <div class="endpoint">
            <span class="method post">POST</span> <strong>Tambah Film Baru</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php</code>
        </div>
        <h3>Request Body (JSON)</h3>
        <table>
            <tr><th>Field</th><th>Tipe</th><th>Wajib</th><th>Keterangan</th></tr>
            <tr><td>judul</td><td>string</td><td><span class="badge badge-req">Wajib</span></td><td>Judul film</td></tr>
            <tr><td>tahun_rilis</td><td>integer</td><td><span class="badge badge-req">Wajib</span></td><td>Tahun rilis (contoh: 2023)</td></tr>
            <tr><td>durasi</td><td>integer</td><td><span class="badge badge-opt">Opsional</span></td><td>Durasi dalam menit</td></tr>
            <tr><td>rating</td><td>decimal</td><td><span class="badge badge-opt">Opsional</span></td><td>Rating 0.0 - 10.0</td></tr>
            <tr><td>id_genre</td><td>integer</td><td><span class="badge badge-opt">Opsional</span></td><td>ID genre</td></tr>
        </table>
        <h3>Contoh Request Body</h3>
        <pre>{
  "judul": "Avengers: Endgame",
  "tahun_rilis": 2019,
  "durasi": 181,
  "rating": 8.4,
  "id_genre": 1
}</pre>
        <h3>Contoh Response (201)</h3>
        <pre>{
  "status": "success",
  "message": "Film berhasil ditambahkan",
  "data": {
    "id_film": 1,
    "judul": "Avengers: Endgame",
    "tahun_rilis": "2019",
    "durasi": 181,
    "rating": "8.4",
    "id_genre": 1,
    "tanggal_dibuat": "2024-01-15 10:30:00",
    "nama_genre": "Action"
  }
}</pre>
    </section>

    <!-- ===================== FILM PUT ===================== -->
    <section id="film-put">
        <h2>🎬 Film — PUT</h2>
        <div class="endpoint">
            <span class="method put">PUT</span> <strong>Update Film Berdasarkan ID</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?id=1</code>
        </div>
        <p>Request body sama seperti POST. Field <code>judul</code> dan <code>tahun_rilis</code> wajib diisi.</p>
    </section>

    <!-- ===================== FILM DELETE ===================== -->
    <section id="film-delete">
        <h2>🎬 Film — DELETE</h2>
        <div class="endpoint">
            <span class="method delete">DELETE</span> <strong>Hapus Film Berdasarkan ID</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?id=1</code>
        </div>
        <h3>Contoh Response (200)</h3>
        <pre>{
  "status": "success",
  "message": "Film dengan ID 1 berhasil dihapus"
}</pre>
    </section>

    <!-- ===================== FITUR TAMBAHAN ===================== -->
    <section id="film-fitur">
        <h2>🎬 Film — Fitur Tambahan</h2>

        <div class="endpoint">
            <span class="method get">GET</span> <strong>Top Rating</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?action=top_rating&limit=5</code>
        </div>
        <div class="endpoint">
            <span class="method get">GET</span> <strong>Film Terbaru</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?action=latest&limit=5</code>
        </div>
        <div class="endpoint">
            <span class="method get">GET</span> <strong>Hitung Total Film</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?action=count</code>
        </div>
        <div class="endpoint">
            <span class="method get">GET</span> <strong>Statistik Film per Genre</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/film.php?action=statistik_genre</code>
        </div>
    </section>

    <!-- ===================== GENRE ===================== -->
    <section id="genre-get">
        <h2>🏷️ Genre — GET</h2>
        <div class="endpoint">
            <span class="method get">GET</span> <strong>Semua Genre</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/genre.php</code>
        </div>
        <div class="endpoint">
            <span class="method get">GET</span> <strong>Genre Berdasarkan ID</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/genre.php?id=1</code>
        </div>
    </section>

    <section id="genre-post">
        <h2>🏷️ Genre — POST</h2>
        <div class="endpoint">
            <span class="method post">POST</span>
            <code class="endpoint-url"><?= $baseUrl ?>/genre.php</code>
        </div>
        <pre>{
  "nama_genre": "Action",
  "deskripsi": "Film dengan banyak adegan aksi dan pertarungan"
}</pre>
    </section>

    <section id="genre-put">
        <h2>🏷️ Genre — PUT</h2>
        <div class="endpoint">
            <span class="method put">PUT</span>
            <code class="endpoint-url"><?= $baseUrl ?>/genre.php?id=1</code>
        </div>
    </section>

    <section id="genre-delete">
        <h2>🏷️ Genre — DELETE</h2>
        <div class="endpoint">
            <span class="method delete">DELETE</span>
            <code class="endpoint-url"><?= $baseUrl ?>/genre.php?id=1</code>
        </div>
    </section>

    <!-- ===================== REVIEW ===================== -->
    <section id="review-get">
        <h2>⭐ Review — GET</h2>
        <div class="endpoint">
            <span class="method get">GET</span> <strong>Semua Review</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/review.php</code>
        </div>
        <div class="endpoint">
            <span class="method get">GET</span> <strong>Review Berdasarkan ID</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/review.php?id=1</code>
        </div>
        <div class="endpoint">
            <span class="method get">GET</span> <strong>Review Berdasarkan Film</strong>
            <code class="endpoint-url"><?= $baseUrl ?>/review.php?id_film=1</code>
        </div>
    </section>

    <section id="review-post">
        <h2>⭐ Review — POST</h2>
        <div class="endpoint">
            <span class="method post">POST</span>
            <code class="endpoint-url"><?= $baseUrl ?>/review.php</code>
        </div>
        <pre>{
  "id_film": 1,
  "nama_reviewer": "Budi Santoso",
  "komentar": "Film yang sangat seru dan menegangkan!",
  "rating_review": 9.0
}</pre>
    </section>

    <section id="review-put">
        <h2>⭐ Review — PUT</h2>
        <div class="endpoint">
            <span class="method put">PUT</span>
            <code class="endpoint-url"><?= $baseUrl ?>/review.php?id=1</code>
        </div>
    </section>

    <section id="review-delete">
        <h2>⭐ Review — DELETE</h2>
        <div class="endpoint">
            <span class="method delete">DELETE</span>
            <code class="endpoint-url"><?= $baseUrl ?>/review.php?id=1</code>
        </div>
    </section>

    <!-- ===================== POSTMAN ===================== -->
    <section id="postman">
        <h2>🛠️ Cara Menggunakan Postman</h2>

        <h3>1. GET Request</h3>
        <p>Pilih method <strong>GET</strong>, masukkan URL endpoint, lalu klik <strong>Send</strong>. Untuk parameter seperti <code>search</code>, <code>page</code>, dll., tambahkan melalui tab <strong>Params</strong>.</p>

        <h3>2. POST Request</h3>
        <p>Pilih method <strong>POST</strong>, masukkan URL endpoint. Buka tab <strong>Body</strong>, pilih <strong>raw</strong> dan format <strong>JSON</strong>. Masukkan data JSON, lalu klik <strong>Send</strong>.</p>

        <h3>3. PUT Request</h3>
        <p>Pilih method <strong>PUT</strong>, masukkan URL dengan parameter <code>?id=X</code> di akhir. Isi body JSON seperti POST, lalu klik <strong>Send</strong>.</p>

        <h3>4. DELETE Request</h3>
        <p>Pilih method <strong>DELETE</strong>, masukkan URL dengan parameter <code>?id=X</code>, lalu klik <strong>Send</strong>. Tidak perlu request body.</p>

        <div class="info-box">
            <p>💡 <strong>Tips:</strong> Pastikan header <code>Content-Type: application/json</code> sudah diatur pada tab <strong>Headers</strong> untuk request POST dan PUT.</p>
        </div>
    </section>

</main>

</body>
</html>