<?php
require_once __DIR__ . '/includes/functions.php';
require_admin();
require_once __DIR__ . '/config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    set_flash('error', 'ID mahasiswa tidak valid.');
    redirect('mahasiswa.php');
}

$stmt = $pdo->prepare('SELECT * FROM mahasiswa WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    set_flash('error', 'Data mahasiswa tidak ditemukan.');
    redirect('mahasiswa.php');
}

$error = '';
$data = [];
foreach (['nim', 'nama', 'email', 'jurusan', 'angkatan', 'jenis_kelamin', 'no_hp', 'alamat'] as $f) {
    $data[$f] = (string)($row[$f] ?? '');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$data, $error] = read_mahasiswa_input();

    if ($error === '') {
        $cek = $pdo->prepare('SELECT id FROM mahasiswa WHERE nim = ? AND id != ? LIMIT 1');
        $cek->execute([$data['nim'], $id]);
        if ($cek->fetch()) {
            $error = t('duplicate');
        } else {
            $pdo->prepare('UPDATE mahasiswa SET nim=?, nama=?, email=?, jurusan=?, angkatan=?, jenis_kelamin=?, no_hp=?, alamat=? WHERE id=?')
                ->execute([$data['nim'], $data['nama'], $data['email'], $data['jurusan'], (int)$data['angkatan'],
                           $data['jenis_kelamin'], $data['no_hp'] ?: null, $data['alamat'] ?: null, $id]);
            set_flash('success', t('success_edit'));
            redirect('mahasiswa.php');
        }
    }
}

$action = 'edit.php?id=' . (int)$id;
$pageTitle = t('form_edit');
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div><p class="eyebrow">EDIT RECORD</p><h1><?= e(t('form_edit')) ?></h1></div>
</div>
<?php require __DIR__ . '/includes/form_mahasiswa.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
