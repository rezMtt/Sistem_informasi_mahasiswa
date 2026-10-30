<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
require_once __DIR__ . '/config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password_lama, $user['password'])) {
        $error = 'Password lama tidak sesuai.';
    } elseif (strlen($password_baru) < 8) {
        $error = 'Password baru minimal 8 karakter.';
    } elseif ($password_baru !== $konfirmasi) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')
            ->execute([password_hash($password_baru, PASSWORD_DEFAULT), $_SESSION['user_id']]);
        $success = 'Password berhasil diperbarui.';
    }
}

$pageTitle = t('change_password');
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div><p class="eyebrow">ACCOUNT</p><h1><?= e(t('change_password')) ?></h1></div>
</div>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<section class="panel form-panel">
    <form method="POST" action="password.php">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div class="form-grid">
            <div><label for="password_lama">Password Lama</label>
                <input type="password" id="password_lama" name="password_lama" required></div>
            <div></div>
            <div><label for="password_baru">Password Baru</label>
                <input type="password" id="password_baru" name="password_baru" minlength="8" required></div>
            <div><label for="konfirmasi">Konfirmasi Password Baru</label>
                <input type="password" id="konfirmasi" name="konfirmasi" minlength="8" required></div>
        </div>
        <div class="form-actions">
            <button class="btn primary" type="submit"><?= e(t('save')) ?></button>
        </div>
    </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
