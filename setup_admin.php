<?php
// Jalankan sekali untuk membuat akun admin, lalu HAPUS file ini.
require_once __DIR__ . '/config/database.php';

$messages = [];

// Migrasi otomatis jika database lama belum punya kolom terbaru.
$needed = [
    'users' => ['role' => "ENUM('admin','user') NOT NULL DEFAULT 'admin'"],
    'mahasiswa' => [
        'no_hp' => 'VARCHAR(20) NULL',
        'alamat' => 'TEXT NULL',
    ],
];
foreach ($needed as $table => $columns) {
    foreach ($columns as $column => $definition) {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
            $messages[] = "Kolom $table.$column ditambahkan.";
        }
    }
}

$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
$stmt->execute(['admin']);

if ($stmt->fetch()) {
    $messages[] = 'Akun admin sudah ada, tidak dibuat ulang.';
} else {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)')
        ->execute(['admin', $hash, 'admin']);
    $messages[] = 'Akun admin dibuat. Username: admin, Password: admin123';
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><title>Setup Admin</title></head>
<body style="font-family:sans-serif;max-width:600px;margin:40px auto">
<h2>Setup selesai</h2>
<ul><?php foreach ($messages as $m): ?><li><?= htmlspecialchars($m) ?></li><?php endforeach; ?></ul>
<p><b>Hapus file setup_admin.php</b> setelah ini, lalu <a href="login.php">login di sini</a>.</p>
</body></html>
