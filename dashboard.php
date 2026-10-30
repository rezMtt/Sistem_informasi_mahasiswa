<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
require_once __DIR__ . '/config/database.php';

$total = (int) $pdo->query('SELECT COUNT(*) FROM mahasiswa')->fetchColumn();
$recent = $pdo->query('SELECT nim, nama, jurusan, angkatan FROM mahasiswa ORDER BY id DESC LIMIT 5')->fetchAll();

$pageTitle = __('dashboard');
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div>
        <p class="eyebrow">OVERVIEW</p>
        <h1><?= e(t('welcome')) ?>, <?= e($_SESSION['username']) ?> 👋</h1>
        <p class="muted"><?= e(t('subtitle')) ?></p>
    </div>
    <?php if (is_admin()): ?><a class="btn primary" href="tambah.php">＋ <?= e(t('add')) ?></a><?php endif; ?>
</section>
<section class="stat-grid">
    <div class="stat-card">
        <span class="stat-icon">▤</span>
        <p><?= e(t('total')) ?></p>
        <strong><?= $total ?></strong>
    </div>
    <div class="stat-card accent-card">
        <span class="stat-icon">＋</span>
        <p><?= e(t('add')) ?></p>
        <strong><a href="tambah.php"><?= e(t('add')) ?> →</a></strong>
    </div>
</section>
<section class="panel">
    <div class="panel-heading">
        <div><p class="eyebrow">LATEST RECORDS</p><h2><?= e(t('students')) ?></h2></div>
        <a class="text-link" href="mahasiswa.php"><?= e(t('students')) ?> →</a>
    </div>
    <?php if (!$recent): ?>
        <p class="empty"><?= e(t('no_data')) ?></p>
    <?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><th><?= e(t('nim')) ?></th><th><?= e(t('name')) ?></th><th><?= e(t('major')) ?></th><th><?= e(t('year')) ?></th></tr></thead>
            <tbody><?php foreach ($recent as $row): ?>
                <tr><td><?= e($row['nim']) ?></td><td><?= e($row['nama']) ?></td><td><?= e($row['jurusan']) ?></td><td><?= e($row['angkatan']) ?></td></tr>
            <?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
