
<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

// Hanya Admin yang boleh menghapus data
require_admin();

// Hapus data hanya melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode permintaan tidak diizinkan.');
}

// Validasi CSRF
verify_csrf();

// Ambil ID mahasiswa
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    set_flash('error', 'ID mahasiswa tidak valid.');
    redirect('mahasiswa.php');
}

// Pastikan data tersedia
$cek = $pdo->prepare(
    "SELECT id FROM mahasiswa WHERE id = ? LIMIT 1"
);
$cek->execute([$id]);

if (!$cek->fetch()) {
    set_flash('error', 'Data mahasiswa tidak ditemukan.');
    redirect('mahasiswa.php');
}

// Hapus data mahasiswa
$hapus = $pdo->prepare(
    "DELETE FROM mahasiswa WHERE id = ?"
);

$hapus->execute([$id]);

set_flash('success', 'Data mahasiswa berhasil dihapus.');

redirect('mahasiswa.php');
