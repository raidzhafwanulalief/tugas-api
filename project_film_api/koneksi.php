<?php
/**
 * ============================================================
 * koneksi.php - Konfigurasi koneksi database MySQL
 * Database: if0_41364053_db_film_api
 * Hosting: InfinityFree - nontonfilmkuy.xo.je
 * ============================================================
 */

define('DB_HOST', 'sql207.infinityfree.com');
define('DB_USER', 'if0_41364053');
define('DB_PASS', 'Dragoncity0905');        // Ganti dengan password hosting
define('DB_NAME', 'if0_41364053_db_film_api');

/**
 * Membuat koneksi ke database menggunakan MySQLi
 */
function getKoneksi() {
    $koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($koneksi->connect_error) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Koneksi database gagal: ' . $koneksi->connect_error
        ]);
        exit();
    }

    $koneksi->set_charset('utf8mb4');
    return $koneksi;
}

/**
 * Fungsi helper untuk mengirim response JSON
 */
function sendResponse($statusCode, $status, $message, $data = null) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    http_response_code($statusCode);

    $response = [
        'status'  => $status,
        'message' => $message
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}