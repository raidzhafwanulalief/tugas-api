<?php
/**
 * ============================================================
 * eksekusi_api.php - Halaman untuk mencoba API secara langsung
 * ============================================================
 */
$baseUrl = 'https://nontonfilmkuy.xo.je/project_film_api';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eksekusi API - NontonFilmKuy</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0f0f1a;
            --surface: #1a1a2e;
            --card: #16213e;
            --accent: #e50914;
            --text: #e0e0e0;
            --muted: #888;
            --success: #00c851;
            --border: #2a2a3e;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }
        header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        header h1 { font-size: 1.3rem; color: var(--accent); }
        header a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 6px 14px;
            border: 1px solid var(--border);
            border-radius: 6px;
            transition: all 0.2s;
        }
        header a:hover { border-color: var(--accent); color: var(--accent); }
        /* Tab Navigation */
        .tab-nav {
            display: flex;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 30px;
            gap: 5px;
            overflow-x: auto;
        }
        .tab-btn {
            padding: 14px 22px;
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 0.95rem;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .tab-btn.active { color: var(--text); border-bottom-color: var(--accent); }
        .tab-btn:hover { color: var(--text); }
        /* Content */
        .content { padding: 30px; max-width: 900px; }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        /* Form */
        .form-group {
            margin-bottom: 16px;
        }
        label {
            display: block;
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 6px;
        }
        input, select, textarea {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 10px 14px;
            color: var(--text);
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
        }
        textarea { min-height: 100px; resize: vertical; font-family: monospace; }
        /* Buttons */
        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: bold;
            transition: all 0.2s;
        }
        .btn:hover { opacity: 0.85; transform: translateY(-1px); }
        .btn-get    { background: #1a4a1a; color: #00c851; border: 1px solid #00c851; }
        .btn-post   { background: #1a3a4a; color: #33b5e5; border: 1px solid #33b5e5; }
        .btn-put    { background: #4a3a1a; color: #ffbb33; border: 1px solid #ffbb33; }
        .btn-delete { background: #4a1a1a; color: #ff4444; border: 1px solid #ff4444; }
        /* Card */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px 25px;
            margin-bottom: 20px;
        }
        .card h3 { margin-bottom: 15px; color: #ccc; font-size: 1rem; }
        /* Grid form */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
        /* Response box */
        .response-box {
            background: #050510;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            min-height: 150px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            white-space: pre-wrap;
            overflow-x: auto;
            color: #a8d8a8;
            margin-top: 15px;
        }
        .response-box.error { color: #ff7070; }
        .response-box.loading { color: var(--muted); }
        /* URL preview */
        .url-preview {
            background: #0a0a1a;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 10px 14px;
            font-family: monospace;
            font-size: 0.85rem;
            color: #7ec8e3;
            margin-top: 10px;
            word-break: break-all;
        }
        /* Section divider */
        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--accent);
            margin-bottom: 10px;
        }
        .row { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
    </style>
</head>
<body>

<header>
    <h1>⚡ Eksekusi API</h1>
    <a href="index.php">← Beranda</a>
    <a href="dokumentasi.php">📄 Dokumentasi</a>
</header>

<!-- Tab Navigation -->
<div class="tab-nav">
    <button class="tab-btn active" onclick="switchTab('get')">🟢 GET</button>
    <button class="tab-btn" onclick="switchTab('post')">🔵 POST</button>
    <button class="tab-btn" onclick="switchTab('put')">🟡 PUT</button>
    <button class="tab-btn" onclick="switchTab('delete')">🔴 DELETE</button>
    <button class="tab-btn" onclick="switchTab('genre')">🏷️ Genre</button>
    <button class="tab-btn" onclick="switchTab('review')">⭐ Review</button>
</div>

<div class="content">

    <!-- ======================================================
         TAB GET
    ====================================================== -->
    <div id="tab-get" class="tab-panel active">

        <!-- Ambil semua film -->
        <div class="card">
            <div class="section-title">GET — Semua Film</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Pencarian Judul</label>
                    <input type="text" id="get-search" placeholder="cth: Avengers">
                </div>
                <div class="form-group">
                    <label>ID Genre</label>
                    <input type="number" id="get-genre" placeholder="cth: 1">
                </div>
                <div class="form-group">
                    <label>Urutkan Berdasarkan</label>
                    <select id="get-sortby">
                        <option value="id_film">ID Film</option>
                        <option value="judul">Judul</option>
                        <option value="tahun_rilis">Tahun Rilis</option>
                        <option value="rating">Rating</option>
                        <option value="durasi">Durasi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Urutan</label>
                    <select id="get-order">
                        <option value="ASC">ASC (Naik)</option>
                        <option value="DESC">DESC (Turun)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Halaman (page)</label>
                    <input type="number" id="get-page" value="1" min="1">
                </div>
                <div class="form-group">
                    <label>Data per Halaman (limit)</label>
                    <input type="number" id="get-limit" value="10" min="1">
                </div>
            </div>
            <button class="btn btn-get" onclick="getFilm()">🔍 Ambil Data Film</button>
            <div class="url-preview" id="get-url-preview"><?= $baseUrl ?>/film.php</div>
        </div>

        <!-- Ambil film by ID -->
        <div class="card">
            <div class="section-title">GET — Film Berdasarkan ID</div>
            <div class="row">
                <div class="form-group" style="flex:1">
                    <label>ID Film</label>
                    <input type="number" id="get-id" placeholder="cth: 1">
                </div>
                <button class="btn btn-get" onclick="getFilmById()">🔍 Ambil</button>
            </div>
        </div>

        <!-- Fitur Tambahan -->
        <div class="card">
            <div class="section-title">GET — Fitur Tambahan</div>
            <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
                <button class="btn btn-get" onclick="getAction('top_rating')">⭐ Top Rating</button>
                <button class="btn btn-get" onclick="getAction('latest')">🆕 Film Terbaru</button>
                <button class="btn btn-get" onclick="getAction('count')">🔢 Hitung Film</button>
                <button class="btn btn-get" onclick="getAction('statistik_genre')">📊 Statistik Genre</button>
            </div>
        </div>

        <!-- Response -->
        <div class="section-title">RESPONSE</div>
        <div class="response-box loading" id="response-get">// Response akan muncul di sini...</div>
    </div>

    <!-- ======================================================
         TAB POST
    ====================================================== -->
    <div id="tab-post" class="tab-panel">
        <div class="card">
            <div class="section-title">POST — Tambah Film Baru</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Judul Film <span style="color:#ff4444">*</span></label>
                    <input type="text" id="post-judul" placeholder="cth: Avengers: Endgame">
                </div>
                <div class="form-group">
                    <label>Tahun Rilis <span style="color:#ff4444">*</span></label>
                    <input type="number" id="post-tahun" placeholder="cth: 2019">
                </div>
                <div class="form-group">
                    <label>Durasi (menit)</label>
                    <input type="number" id="post-durasi" placeholder="cth: 181">
                </div>
                <div class="form-group">
                    <label>Rating (0.0 - 10.0)</label>
                    <input type="number" id="post-rating" step="0.1" min="0" max="10" placeholder="cth: 8.4">
                </div>
                <div class="form-group">
                    <label>ID Genre</label>
                    <input type="number" id="post-genre" placeholder="cth: 1">
                </div>
            </div>
            <div class="form-group">
                <label>Preview JSON Body</label>
                <div class="url-preview" id="post-preview">-</div>
            </div>
            <button class="btn btn-post" onclick="postFilm()">➕ Tambah Film</button>
        </div>
        <div class="section-title">RESPONSE</div>
        <div class="response-box loading" id="response-post">// Response akan muncul di sini...</div>
    </div>

    <!-- ======================================================
         TAB PUT
    ====================================================== -->
    <div id="tab-put" class="tab-panel">
        <div class="card">
            <div class="section-title">PUT — Update Film</div>
            <div class="form-group">
                <label>ID Film yang Ingin Diupdate <span style="color:#ff4444">*</span></label>
                <input type="number" id="put-id" placeholder="cth: 1">
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Judul Film <span style="color:#ff4444">*</span></label>
                    <input type="text" id="put-judul" placeholder="Judul baru">
                </div>
                <div class="form-group">
                    <label>Tahun Rilis <span style="color:#ff4444">*</span></label>
                    <input type="number" id="put-tahun" placeholder="cth: 2020">
                </div>
                <div class="form-group">
                    <label>Durasi (menit)</label>
                    <input type="number" id="put-durasi" placeholder="cth: 120">
                </div>
                <div class="form-group">
                    <label>Rating (0.0 - 10.0)</label>
                    <input type="number" id="put-rating" step="0.1" min="0" max="10" placeholder="cth: 7.5">
                </div>
                <div class="form-group">
                    <label>ID Genre</label>
                    <input type="number" id="put-genre" placeholder="cth: 2">
                </div>
            </div>
            <button class="btn btn-put" onclick="putFilm()">✏️ Update Film</button>
        </div>
        <div class="section-title">RESPONSE</div>
        <div class="response-box loading" id="response-put">// Response akan muncul di sini...</div>
    </div>

    <!-- ======================================================
         TAB DELETE
    ====================================================== -->
    <div id="tab-delete" class="tab-panel">
        <div class="card">
            <div class="section-title">DELETE — Hapus Film</div>
            <div class="row">
                <div class="form-group" style="flex:1">
                    <label>ID Film yang Ingin Dihapus <span style="color:#ff4444">*</span></label>
                    <input type="number" id="delete-id" placeholder="cth: 1">
                </div>
                <button class="btn btn-delete" onclick="deleteFilm()">🗑️ Hapus Film</button>
            </div>
        </div>
        <div class="section-title">RESPONSE</div>
        <div class="response-box loading" id="response-delete">// Response akan muncul di sini...</div>
    </div>

    <!-- ======================================================
         TAB GENRE
    ====================================================== -->
    <div id="tab-genre" class="tab-panel">

        <!-- GET Genre -->
        <div class="card">
            <div class="section-title">GET — Genre</div>
            <div class="row">
                <div class="form-group" style="flex:1">
                    <label>ID Genre (kosongkan untuk semua)</label>
                    <input type="number" id="get-genre-id" placeholder="cth: 1">
                </div>
                <button class="btn btn-get" onclick="getGenre()">🔍 Ambil Genre</button>
            </div>
        </div>

        <!-- POST Genre -->
        <div class="card">
            <div class="section-title">POST — Tambah Genre</div>
            <div class="form-group">
                <label>Nama Genre <span style="color:#ff4444">*</span></label>
                <input type="text" id="post-genre-nama" placeholder="cth: Action">
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" id="post-genre-deskripsi" placeholder="cth: Film dengan banyak aksi">
            </div>
            <button class="btn btn-post" onclick="postGenre()">➕ Tambah Genre</button>
        </div>

        <!-- PUT Genre -->
        <div class="card">
            <div class="section-title">PUT — Update Genre</div>
            <div class="form-group">
                <label>ID Genre <span style="color:#ff4444">*</span></label>
                <input type="number" id="put-genre-id" placeholder="cth: 1">
            </div>
            <div class="form-group">
                <label>Nama Genre <span style="color:#ff4444">*</span></label>
                <input type="text" id="put-genre-nama" placeholder="Nama baru">
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" id="put-genre-deskripsi" placeholder="Deskripsi baru">
            </div>
            <button class="btn btn-put" onclick="putGenre()">✏️ Update Genre</button>
        </div>

        <!-- DELETE Genre -->
        <div class="card">
            <div class="section-title">DELETE — Hapus Genre</div>
            <div class="row">
                <div class="form-group" style="flex:1">
                    <label>ID Genre <span style="color:#ff4444">*</span></label>
                    <input type="number" id="delete-genre-id" placeholder="cth: 1">
                </div>
                <button class="btn btn-delete" onclick="deleteGenre()">🗑️ Hapus Genre</button>
            </div>
        </div>

        <div class="section-title">RESPONSE</div>
        <div class="response-box loading" id="response-genre">// Response akan muncul di sini...</div>
    </div>

    <!-- ======================================================
         TAB REVIEW
    ====================================================== -->
    <div id="tab-review" class="tab-panel">

        <!-- GET Review -->
        <div class="card">
            <div class="section-title">GET — Review</div>
            <div class="row">
                <div class="form-group" style="flex:1">
                    <label>ID Review (kosongkan untuk semua)</label>
                    <input type="number" id="get-review-id" placeholder="cth: 1">
                </div>
                <div class="form-group" style="flex:1">
                    <label>ID Film (review per film)</label>
                    <input type="number" id="get-review-film" placeholder="cth: 1">
                </div>
                <button class="btn btn-get" onclick="getReview()">🔍 Ambil Review</button>
            </div>
        </div>

        <!-- POST Review -->
        <div class="card">
            <div class="section-title">POST — Tambah Review</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>ID Film <span style="color:#ff4444">*</span></label>
                    <input type="number" id="post-review-film" placeholder="cth: 1">
                </div>
                <div class="form-group">
                    <label>Nama Reviewer <span style="color:#ff4444">*</span></label>
                    <input type="text" id="post-review-nama" placeholder="cth: Budi Santoso">
                </div>
                <div class="form-group">
                    <label>Rating Review (0.0 - 10.0)</label>
                    <input type="number" id="post-review-rating" step="0.1" min="0" max="10" placeholder="cth: 8.5">
                </div>
            </div>
            <div class="form-group">
                <label>Komentar <span style="color:#ff4444">*</span></label>
                <textarea id="post-review-komentar" placeholder="Tulis komentar review di sini..."></textarea>
            </div>
            <button class="btn btn-post" onclick="postReview()">➕ Tambah Review</button>
        </div>

        <!-- PUT Review -->
        <div class="card">
            <div class="section-title">PUT — Update Review</div>
            <div class="form-group">
                <label>ID Review <span style="color:#ff4444">*</span></label>
                <input type="number" id="put-review-id" placeholder="cth: 1">
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Reviewer <span style="color:#ff4444">*</span></label>
                    <input type="text" id="put-review-nama" placeholder="Nama baru">
                </div>
                <div class="form-group">
                    <label>Rating Review</label>
                    <input type="number" id="put-review-rating" step="0.1" min="0" max="10" placeholder="cth: 7.0">
                </div>
            </div>
            <div class="form-group">
                <label>Komentar <span style="color:#ff4444">*</span></label>
                <textarea id="put-review-komentar" placeholder="Komentar baru..."></textarea>
            </div>
            <button class="btn btn-put" onclick="putReview()">✏️ Update Review</button>
        </div>

        <!-- DELETE Review -->
        <div class="card">
            <div class="section-title">DELETE — Hapus Review</div>
            <div class="row">
                <div class="form-group" style="flex:1">
                    <label>ID Review <span style="color:#ff4444">*</span></label>
                    <input type="number" id="delete-review-id" placeholder="cth: 1">
                </div>
                <button class="btn btn-delete" onclick="deleteReview()">🗑️ Hapus Review</button>
            </div>
        </div>

        <div class="section-title">RESPONSE</div>
        <div class="response-box loading" id="response-review">// Response akan muncul di sini...</div>
    </div>

</div><!-- end .content -->

<script>
const BASE = '<?= $baseUrl ?>';

/* ============================================================
   FUNGSI NAVIGASI TAB
============================================================ */
function switchTab(name) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    event.target.classList.add('active');
}

/* ============================================================
   FUNGSI HELPER
============================================================ */
// Tampilkan response JSON ke elemen dengan id tertentu
function showResponse(elementId, data, isError = false) {
    const el = document.getElementById(elementId);
    el.textContent = JSON.stringify(data, null, 2);
    el.className = 'response-box' + (isError ? ' error' : '');
}

// Tampilkan loading state
function showLoading(elementId) {
    const el = document.getElementById(elementId);
    el.textContent = '⏳ Memproses request...';
    el.className = 'response-box loading';
}

// Fungsi fetch universal
async function apiRequest(url, method = 'GET', body = null) {
    const options = {
        method,
        headers: { 'Content-Type': 'application/json' }
    };
    if (body) options.body = JSON.stringify(body);
    const response = await fetch(url, options);
    return await response.json();
}

/* ============================================================
   GET FILM
============================================================ */
function getFilm() {
    const search   = document.getElementById('get-search').value;
    const genre    = document.getElementById('get-genre').value;
    const sortby   = document.getElementById('get-sortby').value;
    const order    = document.getElementById('get-order').value;
    const page     = document.getElementById('get-page').value;
    const limit    = document.getElementById('get-limit').value;

    let params = new URLSearchParams();
    if (search) params.set('search', search);
    if (genre)  params.set('id_genre', genre);
    params.set('sort_by', sortby);
    params.set('order', order);
    params.set('page', page);
    params.set('limit', limit);

    const url = `${BASE}/film.php?${params.toString()}`;
    document.getElementById('get-url-preview').textContent = url;
    showLoading('response-get');

    apiRequest(url)
        .then(data => showResponse('response-get', data))
        .catch(err => showResponse('response-get', { error: err.message }, true));
}

function getFilmById() {
    const id = document.getElementById('get-id').value;
    if (!id) { alert('ID Film wajib diisi!'); return; }
    const url = `${BASE}/film.php?id=${id}`;
    showLoading('response-get');
    apiRequest(url)
        .then(data => showResponse('response-get', data))
        .catch(err => showResponse('response-get', { error: err.message }, true));
}

function getAction(action) {
    const url = `${BASE}/film.php?action=${action}`;
    showLoading('response-get');
    apiRequest(url)
        .then(data => showResponse('response-get', data))
        .catch(err => showResponse('response-get', { error: err.message }, true));
}

/* ============================================================
   POST FILM
============================================================ */
// Update preview JSON saat user mengetik
['post-judul','post-tahun','post-durasi','post-rating','post-genre'].forEach(id => {
    document.getElementById(id).addEventListener('input', updatePostPreview);
});
function updatePostPreview() {
    const body = buildFilmBody('post');
    document.getElementById('post-preview').textContent = JSON.stringify(body, null, 2);
}
function buildFilmBody(prefix) {
    const body = {};
    const judul  = document.getElementById(prefix + '-judul').value;
    const tahun  = document.getElementById(prefix + '-tahun').value;
    const durasi = document.getElementById(prefix + '-durasi').value;
    const rating = document.getElementById(prefix + '-rating').value;
    const genre  = document.getElementById(prefix + '-genre').value;
    if (judul)  body.judul = judul;
    if (tahun)  body.tahun_rilis = parseInt(tahun);
    if (durasi) body.durasi = parseInt(durasi);
    if (rating) body.rating = parseFloat(rating);
    if (genre)  body.id_genre = parseInt(genre);
    return body;
}

function postFilm() {
    const body = buildFilmBody('post');
    if (!body.judul || !body.tahun_rilis) { alert('Judul dan tahun rilis wajib diisi!'); return; }
    showLoading('response-post');
    apiRequest(`${BASE}/film.php`, 'POST', body)
        .then(data => showResponse('response-post', data))
        .catch(err => showResponse('response-post', { error: err.message }, true));
}

/* ============================================================
   PUT FILM
============================================================ */
function putFilm() {
    const id = document.getElementById('put-id').value;
    if (!id) { alert('ID Film wajib diisi!'); return; }
    const body = buildFilmBody('put');
    if (!body.judul || !body.tahun_rilis) { alert('Judul dan tahun rilis wajib diisi!'); return; }
    showLoading('response-put');
    apiRequest(`${BASE}/film.php?id=${id}`, 'PUT', body)
        .then(data => showResponse('response-put', data))
        .catch(err => showResponse('response-put', { error: err.message }, true));
}

/* ============================================================
   DELETE FILM
============================================================ */
function deleteFilm() {
    const id = document.getElementById('delete-id').value;
    if (!id) { alert('ID Film wajib diisi!'); return; }
    if (!confirm(`Yakin ingin menghapus film dengan ID ${id}?`)) return;
    showLoading('response-delete');
    apiRequest(`${BASE}/film.php?id=${id}`, 'DELETE')
        .then(data => showResponse('response-delete', data))
        .catch(err => showResponse('response-delete', { error: err.message }, true));
}

/* ============================================================
   GENRE
============================================================ */
function getGenre() {
    const id  = document.getElementById('get-genre-id').value;
    const url = id ? `${BASE}/genre.php?id=${id}` : `${BASE}/genre.php`;
    showLoading('response-genre');
    apiRequest(url)
        .then(data => showResponse('response-genre', data))
        .catch(err => showResponse('response-genre', { error: err.message }, true));
}
function postGenre() {
    const nama = document.getElementById('post-genre-nama').value;
    const desk = document.getElementById('post-genre-deskripsi').value;
    if (!nama) { alert('Nama genre wajib diisi!'); return; }
    showLoading('response-genre');
    apiRequest(`${BASE}/genre.php`, 'POST', { nama_genre: nama, deskripsi: desk })
        .then(data => showResponse('response-genre', data))
        .catch(err => showResponse('response-genre', { error: err.message }, true));
}
function putGenre() {
    const id   = document.getElementById('put-genre-id').value;
    const nama = document.getElementById('put-genre-nama').value;
    const desk = document.getElementById('put-genre-deskripsi').value;
    if (!id || !nama) { alert('ID dan nama genre wajib diisi!'); return; }
    showLoading('response-genre');
    apiRequest(`${BASE}/genre.php?id=${id}`, 'PUT', { nama_genre: nama, deskripsi: desk })
        .then(data => showResponse('response-genre', data))
        .catch(err => showResponse('response-genre', { error: err.message }, true));
}
function deleteGenre() {
    const id = document.getElementById('delete-genre-id').value;
    if (!id) { alert('ID genre wajib diisi!'); return; }
    if (!confirm(`Yakin ingin menghapus genre dengan ID ${id}?`)) return;
    showLoading('response-genre');
    apiRequest(`${BASE}/genre.php?id=${id}`, 'DELETE')
        .then(data => showResponse('response-genre', data))
        .catch(err => showResponse('response-genre', { error: err.message }, true));
}

/* ============================================================
   REVIEW
============================================================ */
function getReview() {
    const id      = document.getElementById('get-review-id').value;
    const id_film = document.getElementById('get-review-film').value;
    let url;
    if (id)        url = `${BASE}/review.php?id=${id}`;
    else if (id_film) url = `${BASE}/review.php?id_film=${id_film}`;
    else           url = `${BASE}/review.php`;
    showLoading('response-review');
    apiRequest(url)
        .then(data => showResponse('response-review', data))
        .catch(err => showResponse('response-review', { error: err.message }, true));
}
function postReview() {
    const id_film  = document.getElementById('post-review-film').value;
    const nama     = document.getElementById('post-review-nama').value;
    const komentar = document.getElementById('post-review-komentar').value;
    const rating   = document.getElementById('post-review-rating').value;
    if (!id_film || !nama || !komentar) { alert('ID film, nama reviewer, dan komentar wajib diisi!'); return; }
    const body = { id_film: parseInt(id_film), nama_reviewer: nama, komentar };
    if (rating) body.rating_review = parseFloat(rating);
    showLoading('response-review');
    apiRequest(`${BASE}/review.php`, 'POST', body)
        .then(data => showResponse('response-review', data))
        .catch(err => showResponse('response-review', { error: err.message }, true));
}
function putReview() {
    const id       = document.getElementById('put-review-id').value;
    const nama     = document.getElementById('put-review-nama').value;
    const komentar = document.getElementById('put-review-komentar').value;
    const rating   = document.getElementById('put-review-rating').value;
    if (!id || !nama || !komentar) { alert('ID, nama reviewer, dan komentar wajib diisi!'); return; }
    const body = { nama_reviewer: nama, komentar };
    if (rating) body.rating_review = parseFloat(rating);
    showLoading('response-review');
    apiRequest(`${BASE}/review.php?id=${id}`, 'PUT', body)
        .then(data => showResponse('response-review', data))
        .catch(err => showResponse('response-review', { error: err.message }, true));
}
function deleteReview() {
    const id = document.getElementById('delete-review-id').value;
    if (!id) { alert('ID review wajib diisi!'); return; }
    if (!confirm(`Yakin ingin menghapus review dengan ID ${id}?`)) return;
    showLoading('response-review');
    apiRequest(`${BASE}/review.php?id=${id}`, 'DELETE')
        .then(data => showResponse('response-review', data))
        .catch(err => showResponse('response-review', { error: err.message }, true));
}
</script>
</body>
</html>