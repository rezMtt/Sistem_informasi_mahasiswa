<?php
require_once __DIR__ . '/includes/functions.php';
$lang = $_GET['lang'] ?? 'id';
if (in_array($lang, ['id', 'en'], true)) {
    $_SESSION['lang'] = $lang;
}
$back = $_SERVER['HTTP_REFERER'] ?? 'login.php';
$parts = parse_url($back);
$path = $parts['path'] ?? '';
// Redirect hanya ke halaman lokal aplikasi.
$target = basename($path);
$allowed = ['login.php', 'dashboard.php', 'mahasiswa.php', 'tambah.php', 'edit.php', 'password.php'];
header('Location: ' . (in_array($target, $allowed, true) ? $target : 'login.php'));
exit;
