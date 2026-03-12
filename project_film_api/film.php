<?php
/**
 * ============================================================
 * film.php - REST API untuk tabel film
 * Mendukung: GET, POST, PUT, DELETE
 * Fitur tambahan: search, filter, sort, pagination,
 *                 top rating, latest, count, join, statistik
 * ============================================================
 */

require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    http_response_code(200);
    exit();
}

$method  = $_SERVER['REQUEST_METHOD'];
$koneksi = getKoneksi();

switch ($method) {

    // --------------------------------------------------------
    // GET - Berbagai mode pengambilan data film
    // --------------------------------------------------------
    case 'GET':
        $id       = isset($_GET['id'])       ? (int)$_GET['id']       : null;
        $action   = isset($_GET['action'])   ? $_GET['action']         : null;
        $search   = isset($_GET['search'])   ? $_GET['search']         : null;
        $id_genre = isset($_GET['id_genre']) ? (int)$_GET['id_genre']  : null;
        $sort_by  = isset($_GET['sort_by'])  ? $_GET['sort_by']        : 'id_film';
        $order    = isset($_GET['order'])    ? strtoupper($_GET['order']) : 'ASC';
        $page     = isset($_GET['page'])     ? (int)$_GET['page']      : 1;
        $limit    = isset($_GET['limit'])    ? (int)$_GET['limit']     : 10;

        // Validasi sort_by agar aman dari SQL injection
        $allowedSort = ['id_film', 'judul', 'tahun_rilis', 'durasi', 'rating', 'tanggal_dibuat'];
        if (!in_array($sort_by, $allowedSort)) $sort_by = 'id_film';
        if (!in_array($order, ['ASC', 'DESC'])) $order = 'ASC';

        // -- Ambil satu film berdasarkan ID --
        if ($id) {
            $stmt = $koneksi->prepare("
                SELECT f.*, g.nama_genre, g.deskripsi AS deskripsi_genre
                FROM film f
                LEFT JOIN genre g ON f.id_genre = g.id_genre
                WHERE f.id_film = ?
            ");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_assoc();

            if ($data) {
                sendResponse(200, 'success', 'Data film ditemukan', $data);
            } else {
                sendResponse(404, 'error', 'Film dengan ID ' . $id . ' tidak ditemukan');
            }
        }

        // -- Action khusus --
        elseif ($action === 'top_rating') {
            // Tampilkan film dengan rating tertinggi
            $topLimit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $result = $koneksi->query("
                SELECT f.*, g.nama_genre
                FROM film f
                LEFT JOIN genre g ON f.id_genre = g.id_genre
                ORDER BY f.rating DESC
                LIMIT $topLimit
            ");
            $data = [];
            while ($row = $result->fetch_assoc()) $data[] = $row;
            sendResponse(200, 'success', 'Film dengan rating tertinggi', $data);
        }

        elseif ($action === 'latest') {
            // Tampilkan film terbaru berdasarkan tahun rilis
            $latestLimit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $result = $koneksi->query("
                SELECT f.*, g.nama_genre
                FROM film f
                LEFT JOIN genre g ON f.id_genre = g.id_genre
                ORDER BY f.tahun_rilis DESC, f.tanggal_dibuat DESC
                LIMIT $latestLimit
            ");
            $data = [];
            while ($row = $result->fetch_assoc()) $data[] = $row;
            sendResponse(200, 'success', 'Film terbaru', $data);
        }

        elseif ($action === 'count') {
            // Hitung total film
            $result = $koneksi->query("SELECT COUNT(*) AS total_film FROM film");
            $data   = $result->fetch_assoc();
            sendResponse(200, 'success', 'Total jumlah film', $data);
        }

        elseif ($action === 'statistik_genre') {
            // Statistik jumlah film per genre
            $result = $koneksi->query("
                SELECT g.id_genre, g.nama_genre, COUNT(f.id_film) AS jumlah_film
                FROM genre g
                LEFT JOIN film f ON g.id_genre = f.id_genre
                GROUP BY g.id_genre, g.nama_genre
                ORDER BY jumlah_film DESC
            ");
            $data = [];
            while ($row = $result->fetch_assoc()) $data[] = $row;
            sendResponse(200, 'success', 'Statistik film per genre', $data);
        }

        // -- Ambil semua film dengan search, filter, sort, pagination --
        else {
            $where    = [];
            $params   = [];
            $types    = '';

            // Filter pencarian judul
            if ($search) {
                $where[]  = 'f.judul LIKE ?';
                $params[] = '%' . $search . '%';
                $types   .= 's';
            }

            // Filter berdasarkan genre
            if ($id_genre) {
                $where[]  = 'f.id_genre = ?';
                $params[] = $id_genre;
                $types   .= 'i';
            }

            $whereSQL = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

            // Hitung total data untuk pagination
            $countSQL  = "SELECT COUNT(*) AS total FROM film f $whereSQL";
            $countStmt = $koneksi->prepare($countSQL);
            if ($types) $countStmt->bind_param($types, ...$params);
            $countStmt->execute();
            $totalData = $countStmt->get_result()->fetch_assoc()['total'];
            $totalPage = ceil($totalData / $limit);
            $offset    = ($page - 1) * $limit;

            // Query utama dengan JOIN
            $sql = "
                SELECT f.*, g.nama_genre
                FROM film f
                LEFT JOIN genre g ON f.id_genre = g.id_genre
                $whereSQL
                ORDER BY f.$sort_by $order
                LIMIT ? OFFSET ?
            ";

            $params[] = $limit;
            $params[] = $offset;
            $types   .= 'ii';

            $stmt = $koneksi->prepare($sql);
            if ($types) $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) $data[] = $row;

            // Response dengan info pagination
            $response = [
                'pagination' => [
                    'total_data' => (int)$totalData,
                    'total_page' => (int)$totalPage,
                    'current_page' => $page,
                    'per_page' => $limit
                ],
                'films' => $data
            ];

            sendResponse(200, 'success', 'Data film berhasil diambil', $response);
        }
        break;

    // --------------------------------------------------------
    // POST - Tambah film baru
    // --------------------------------------------------------
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);

        // Validasi field wajib
        if (empty($input['judul']) || empty($input['tahun_rilis'])) {
            sendResponse(400, 'error', 'Field judul dan tahun_rilis wajib diisi');
        }

        $judul        = $koneksi->real_escape_string(trim($input['judul']));
        $tahun_rilis  = (int)$input['tahun_rilis'];
        $durasi       = isset($input['durasi'])   ? (int)$input['durasi']            : null;
        $rating       = isset($input['rating'])   ? (float)$input['rating']          : 0.0;
        $id_genre     = isset($input['id_genre']) ? (int)$input['id_genre']          : null;

        // Validasi rating
        if ($rating < 0 || $rating > 10) {
            sendResponse(400, 'error', 'Rating harus bernilai antara 0 sampai 10');
        }

        // Cek id_genre jika diisi
        if ($id_genre) {
            $cekGenre = $koneksi->query("SELECT id_genre FROM genre WHERE id_genre = $id_genre");
            if ($cekGenre->num_rows === 0) {
                sendResponse(404, 'error', 'Genre dengan ID ' . $id_genre . ' tidak ditemukan');
            }
        }

        $stmt = $koneksi->prepare("
            INSERT INTO film (judul, tahun_rilis, durasi, rating, id_genre, tanggal_dibuat)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param('siidi', $judul, $tahun_rilis, $durasi, $rating, $id_genre);

        if ($stmt->execute()) {
            $newId = $koneksi->insert_id;
            $result = $koneksi->query("
                SELECT f.*, g.nama_genre FROM film f
                LEFT JOIN genre g ON f.id_genre = g.id_genre
                WHERE f.id_film = $newId
            ");
            sendResponse(201, 'success', 'Film berhasil ditambahkan', $result->fetch_assoc());
        } else {
            sendResponse(500, 'error', 'Gagal menambahkan film: ' . $koneksi->error);
        }
        break;

    // --------------------------------------------------------
    // PUT - Update film berdasarkan ID
    // --------------------------------------------------------
    case 'PUT':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            sendResponse(400, 'error', 'Parameter id wajib disertakan');
        }

        $cek = $koneksi->query("SELECT id_film FROM film WHERE id_film = $id");
        if ($cek->num_rows === 0) {
            sendResponse(404, 'error', 'Film dengan ID ' . $id . ' tidak ditemukan');
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['judul']) || empty($input['tahun_rilis'])) {
            sendResponse(400, 'error', 'Field judul dan tahun_rilis wajib diisi');
        }

        $judul       = $koneksi->real_escape_string(trim($input['judul']));
        $tahun_rilis = (int)$input['tahun_rilis'];
        $durasi      = isset($input['durasi'])   ? (int)$input['durasi']   : null;
        $rating      = isset($input['rating'])   ? (float)$input['rating'] : 0.0;
        $id_genre    = isset($input['id_genre']) ? (int)$input['id_genre'] : null;

        if ($rating < 0 || $rating > 10) {
            sendResponse(400, 'error', 'Rating harus bernilai antara 0 sampai 10');
        }

        $stmt = $koneksi->prepare("
            UPDATE film SET judul = ?, tahun_rilis = ?, durasi = ?, rating = ?, id_genre = ?
            WHERE id_film = ?
        ");
        $stmt->bind_param('siidii', $judul, $tahun_rilis, $durasi, $rating, $id_genre, $id);

        if ($stmt->execute()) {
            $result = $koneksi->query("
                SELECT f.*, g.nama_genre FROM film f
                LEFT JOIN genre g ON f.id_genre = g.id_genre
                WHERE f.id_film = $id
            ");
            sendResponse(200, 'success', 'Film berhasil diperbarui', $result->fetch_assoc());
        } else {
            sendResponse(500, 'error', 'Gagal memperbarui film: ' . $koneksi->error);
        }
        break;

    // --------------------------------------------------------
    // DELETE - Hapus film berdasarkan ID
    // --------------------------------------------------------
    case 'DELETE':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            sendResponse(400, 'error', 'Parameter id wajib disertakan');
        }

        $cek = $koneksi->query("SELECT id_film FROM film WHERE id_film = $id");
        if ($cek->num_rows === 0) {
            sendResponse(404, 'error', 'Film dengan ID ' . $id . ' tidak ditemukan');
        }

        if ($koneksi->query("DELETE FROM film WHERE id_film = $id")) {
            sendResponse(200, 'success', 'Film dengan ID ' . $id . ' berhasil dihapus');
        } else {
            sendResponse(500, 'error', 'Gagal menghapus film: ' . $koneksi->error);
        }
        break;

    default:
        sendResponse(405, 'error', 'Method tidak diizinkan');
}

$koneksi->close();