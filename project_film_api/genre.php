<?php
/**
 * ============================================================
 * genre.php - REST API untuk tabel genre
 * Mendukung: GET, POST, PUT, DELETE
 * ============================================================
 */

require_once 'koneksi.php';

// Handle preflight CORS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$koneksi = getKoneksi();

// ============================================================
// ROUTING berdasarkan HTTP Method
// ============================================================
switch ($method) {

    // --------------------------------------------------------
    // GET - Ambil data genre
    // --------------------------------------------------------
    case 'GET':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        // Ambil satu genre berdasarkan ID
        if ($id) {
            $stmt = $koneksi->prepare("SELECT * FROM genre WHERE id_genre = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                sendResponse(200, 'success', 'Data genre ditemukan', $data);
            } else {
                sendResponse(404, 'error', 'Genre dengan ID ' . $id . ' tidak ditemukan');
            }

        // Ambil semua genre
        } else {
            $result = $koneksi->query("SELECT * FROM genre ORDER BY id_genre ASC");
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            sendResponse(200, 'success', 'Data genre berhasil diambil', $data);
        }
        break;

    // --------------------------------------------------------
    // POST - Tambah genre baru
    // --------------------------------------------------------
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);

        // Validasi input wajib
        if (empty($input['nama_genre'])) {
            sendResponse(400, 'error', 'Field nama_genre wajib diisi');
        }

        $nama_genre = $koneksi->real_escape_string(trim($input['nama_genre']));
        $deskripsi  = isset($input['deskripsi']) ? $koneksi->real_escape_string(trim($input['deskripsi'])) : '';

        $stmt = $koneksi->prepare("INSERT INTO genre (nama_genre, deskripsi) VALUES (?, ?)");
        $stmt->bind_param('ss', $nama_genre, $deskripsi);

        if ($stmt->execute()) {
            $newId = $koneksi->insert_id;
            $result = $koneksi->query("SELECT * FROM genre WHERE id_genre = $newId");
            $newData = $result->fetch_assoc();
            sendResponse(201, 'success', 'Genre berhasil ditambahkan', $newData);
        } else {
            sendResponse(500, 'error', 'Gagal menambahkan genre: ' . $koneksi->error);
        }
        break;

    // --------------------------------------------------------
    // PUT - Update genre berdasarkan ID
    // --------------------------------------------------------
    case 'PUT':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            sendResponse(400, 'error', 'Parameter id wajib disertakan');
        }

        // Cek apakah genre ada
        $cek = $koneksi->query("SELECT id_genre FROM genre WHERE id_genre = $id");
        if ($cek->num_rows === 0) {
            sendResponse(404, 'error', 'Genre dengan ID ' . $id . ' tidak ditemukan');
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['nama_genre'])) {
            sendResponse(400, 'error', 'Field nama_genre wajib diisi');
        }

        $nama_genre = $koneksi->real_escape_string(trim($input['nama_genre']));
        $deskripsi  = isset($input['deskripsi']) ? $koneksi->real_escape_string(trim($input['deskripsi'])) : '';

        $stmt = $koneksi->prepare("UPDATE genre SET nama_genre = ?, deskripsi = ? WHERE id_genre = ?");
        $stmt->bind_param('ssi', $nama_genre, $deskripsi, $id);

        if ($stmt->execute()) {
            $result = $koneksi->query("SELECT * FROM genre WHERE id_genre = $id");
            $updatedData = $result->fetch_assoc();
            sendResponse(200, 'success', 'Genre berhasil diperbarui', $updatedData);
        } else {
            sendResponse(500, 'error', 'Gagal memperbarui genre: ' . $koneksi->error);
        }
        break;

    // --------------------------------------------------------
    // DELETE - Hapus genre berdasarkan ID
    // --------------------------------------------------------
    case 'DELETE':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            sendResponse(400, 'error', 'Parameter id wajib disertakan');
        }

        $cek = $koneksi->query("SELECT id_genre FROM genre WHERE id_genre = $id");
        if ($cek->num_rows === 0) {
            sendResponse(404, 'error', 'Genre dengan ID ' . $id . ' tidak ditemukan');
        }

        if ($koneksi->query("DELETE FROM genre WHERE id_genre = $id")) {
            sendResponse(200, 'success', 'Genre dengan ID ' . $id . ' berhasil dihapus');
        } else {
            sendResponse(500, 'error', 'Gagal menghapus genre: ' . $koneksi->error);
        }
        break;

    default:
        sendResponse(405, 'error', 'Method tidak diizinkan');
}

$koneksi->close();