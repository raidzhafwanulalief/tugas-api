<?php
/**
 * ============================================================
 * review.php - REST API untuk tabel review
 * Mendukung: GET, POST, PUT, DELETE
 * Fitur: review per film
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
    // GET - Ambil data review
    // --------------------------------------------------------
    case 'GET':
        $id       = isset($_GET['id'])       ? (int)$_GET['id']       : null;
        $id_film  = isset($_GET['id_film'])  ? (int)$_GET['id_film']  : null;

        // Ambil satu review berdasarkan ID
        if ($id) {
            $stmt = $koneksi->prepare("
                SELECT r.*, f.judul AS judul_film
                FROM review r
                LEFT JOIN film f ON r.id_film = f.id_film
                WHERE r.id_review = ?
            ");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_assoc();

            if ($data) {
                sendResponse(200, 'success', 'Data review ditemukan', $data);
            } else {
                sendResponse(404, 'error', 'Review dengan ID ' . $id . ' tidak ditemukan');
            }
        }

        // Ambil semua review milik satu film
        elseif ($id_film) {
            // Cek apakah film ada
            $cekFilm = $koneksi->query("SELECT id_film, judul FROM film WHERE id_film = $id_film");
            if ($cekFilm->num_rows === 0) {
                sendResponse(404, 'error', 'Film dengan ID ' . $id_film . ' tidak ditemukan');
            }
            $filmData = $cekFilm->fetch_assoc();

            $stmt = $koneksi->prepare("
                SELECT * FROM review
                WHERE id_film = ?
                ORDER BY tanggal_review DESC
            ");
            $stmt->bind_param('i', $id_film);
            $stmt->execute();
            $result = $stmt->get_result();

            $reviews = [];
            while ($row = $result->fetch_assoc()) $reviews[] = $row;

            $response = [
                'film'    => $filmData,
                'total'   => count($reviews),
                'reviews' => $reviews
            ];
            sendResponse(200, 'success', 'Review untuk film ' . $filmData['judul'], $response);
        }

        // Ambil semua review
        else {
            $result = $koneksi->query("
                SELECT r.*, f.judul AS judul_film
                FROM review r
                LEFT JOIN film f ON r.id_film = f.id_film
                ORDER BY r.tanggal_review DESC
            ");
            $data = [];
            while ($row = $result->fetch_assoc()) $data[] = $row;
            sendResponse(200, 'success', 'Semua data review berhasil diambil', $data);
        }
        break;

    // --------------------------------------------------------
    // POST - Tambah review baru
    // --------------------------------------------------------
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);

        // Validasi field wajib
        if (empty($input['id_film']) || empty($input['nama_reviewer']) || empty($input['komentar'])) {
            sendResponse(400, 'error', 'Field id_film, nama_reviewer, dan komentar wajib diisi');
        }

        $id_film       = (int)$input['id_film'];
        $nama_reviewer = $koneksi->real_escape_string(trim($input['nama_reviewer']));
        $komentar      = $koneksi->real_escape_string(trim($input['komentar']));
        $rating_review = isset($input['rating_review']) ? (float)$input['rating_review'] : 0.0;

        // Cek film ada
        $cekFilm = $koneksi->query("SELECT id_film FROM film WHERE id_film = $id_film");
        if ($cekFilm->num_rows === 0) {
            sendResponse(404, 'error', 'Film dengan ID ' . $id_film . ' tidak ditemukan');
        }

        if ($rating_review < 0 || $rating_review > 10) {
            sendResponse(400, 'error', 'Rating review harus bernilai antara 0 sampai 10');
        }

        $stmt = $koneksi->prepare("
            INSERT INTO review (id_film, nama_reviewer, komentar, rating_review, tanggal_review)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param('issd', $id_film, $nama_reviewer, $komentar, $rating_review);

        if ($stmt->execute()) {
            $newId  = $koneksi->insert_id;
            $result = $koneksi->query("
                SELECT r.*, f.judul AS judul_film FROM review r
                LEFT JOIN film f ON r.id_film = f.id_film
                WHERE r.id_review = $newId
            ");
            sendResponse(201, 'success', 'Review berhasil ditambahkan', $result->fetch_assoc());
        } else {
            sendResponse(500, 'error', 'Gagal menambahkan review: ' . $koneksi->error);
        }
        break;

    // --------------------------------------------------------
    // PUT - Update review berdasarkan ID
    // --------------------------------------------------------
    case 'PUT':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            sendResponse(400, 'error', 'Parameter id wajib disertakan');
        }

        $cek = $koneksi->query("SELECT id_review FROM review WHERE id_review = $id");
        if ($cek->num_rows === 0) {
            sendResponse(404, 'error', 'Review dengan ID ' . $id . ' tidak ditemukan');
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['nama_reviewer']) || empty($input['komentar'])) {
            sendResponse(400, 'error', 'Field nama_reviewer dan komentar wajib diisi');
        }

        $nama_reviewer = $koneksi->real_escape_string(trim($input['nama_reviewer']));
        $komentar      = $koneksi->real_escape_string(trim($input['komentar']));
        $rating_review = isset($input['rating_review']) ? (float)$input['rating_review'] : 0.0;

        $stmt = $koneksi->prepare("
            UPDATE review SET nama_reviewer = ?, komentar = ?, rating_review = ?
            WHERE id_review = ?
        ");
        $stmt->bind_param('ssdi', $nama_reviewer, $komentar, $rating_review, $id);

        if ($stmt->execute()) {
            $result = $koneksi->query("
                SELECT r.*, f.judul AS judul_film FROM review r
                LEFT JOIN film f ON r.id_film = f.id_film
                WHERE r.id_review = $id
            ");
            sendResponse(200, 'success', 'Review berhasil diperbarui', $result->fetch_assoc());
        } else {
            sendResponse(500, 'error', 'Gagal memperbarui review: ' . $koneksi->error);
        }
        break;

    // --------------------------------------------------------
    // DELETE - Hapus review berdasarkan ID
    // --------------------------------------------------------
    case 'DELETE':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            sendResponse(400, 'error', 'Parameter id wajib disertakan');
        }

        $cek = $koneksi->query("SELECT id_review FROM review WHERE id_review = $id");
        if ($cek->num_rows === 0) {
            sendResponse(404, 'error', 'Review dengan ID ' . $id . ' tidak ditemukan');
        }

        if ($koneksi->query("DELETE FROM review WHERE id_review = $id")) {
            sendResponse(200, 'success', 'Review dengan ID ' . $id . ' berhasil dihapus');
        } else {
            sendResponse(500, 'error', 'Gagal menghapus review: ' . $koneksi->error);
        }
        break;

    default:
        sendResponse(405, 'error', 'Method tidak diizinkan');
}

$koneksi->close();