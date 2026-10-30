<?php
require_once __DIR__ . '/includes/functions.php';
require_admin();
require_once __DIR__ . '/config/database.php';

$error = '';
$data = ['nim' => '', 'nama' => '', 'email' => '', 'jurusan' => '', 'angkatan' => date('Y'),
         'jenis_kelamin' => '', 'no_hp' => '', 'alamat' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$data, $error] = read_mahasiswa_input();

    if ($error === '') {
        $cek = $pdo->prepare('SELECT id FROM mahasiswa WHERE nim = ? LIMIT 1');
        $cek->execute([$data['nim']]);
        if ($cek->fetch()) {
            $error = t('duplicate');
        } else {
            $pdo->prepare('INSERT INTO mahasiswa (nim, nama, email, jurusan, angkatan, jenis_kelamin, no_hp, alamat)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([$data['nim'], $data['nama'], $data['email'], $data['jurusan'], (int)$data['angkatan'],
                           $data['jenis_kelamin'], $data['no_hp'] ?: null, $data['alamat'] ?: null]);
            set_flash('success', t('success_add'));
            redirect('mahasiswa.php');
        }
    }
}

$action = 'tambah.php';
$pageTitle = t('form_add');
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div><p class="eyebrow">NEW RECORD</p><h1><?= e(t('form_add')) ?></h1></div>
</div>
<?php require __DIR__ . '/includes/form_mahasiswa.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
