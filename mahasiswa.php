<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
require_once __DIR__ . '/config/database.php';

$keyword = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM mahasiswa';
$params = [];
if ($keyword !== '') {
    $sql .= ' WHERE nim LIKE ? OR nama LIKE ? OR jurusan LIKE ?';
    $like = '%' . $keyword . '%';
    $params = [$like, $like, $like];
}
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

$flash = get_flash();
$pageTitle = t('students');
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div><p class="eyebrow">DATABASE</p><h1><?= e(t('students')) ?></h1><p class="muted"><?= e(t('subtitle')) ?></p></div>
    <?php if (is_admin()): ?><a class="btn primary" href="tambah.php">＋ <?= e(t('add')) ?></a><?php endif; ?>
</div>
<?php if ($flash): ?><div class="alert <?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= e($flash['message']) ?></div><?php endif; ?>
<section class="panel">
    <form class="search-form" method="GET" action="mahasiswa.php">
        <input type="search" name="q" value="<?= e($keyword) ?>" placeholder="<?= e(t('search')) ?>">
        <button class="btn dark" type="submit"><?= e(t('search_btn')) ?></button>
        <?php if ($keyword !== ''): ?><a class="btn light" href="mahasiswa.php"><?= e(t('cancel')) ?></a><?php endif; ?>
    </form>
    <?php if (!$rows): ?>
        <p class="empty"><?= e(t('no_data')) ?></p>
    <?php else: ?>
    <div class="table-wrap"><table>
        <thead><tr><th><?= e(t('nim')) ?></th><th><?= e(t('name')) ?></th><th><?= e(t('email')) ?></th><th><?= e(t('major')) ?></th><th><?= e(t('year')) ?></th><th><?= e(t('gender')) ?></th><?php if (is_admin()): ?><th><?= e(t('action')) ?></th><?php endif; ?></tr></thead>
        <tbody><?php foreach ($rows as $row): ?>
            <tr>
                <td class="code"><?= e($row['nim']) ?></td><td><b><?= e($row['nama']) ?></b></td>
                <td><?= e($row['email']) ?></td><td><?= e($row['jurusan']) ?></td>
                <td><?= e($row['angkatan']) ?></td><td><?= e($row['jenis_kelamin']) ?></td>
                <?php if (is_admin()): ?><td class="actions">
                    <a class="btn small light" href="edit.php?id=<?= (int)$row['id'] ?>"><?= e(t('edit')) ?></a>
                    <form method="POST" action="hapus.php" onsubmit="return confirm('<?= e(t('confirm_delete')) ?>')">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button class="btn small danger" type="submit"><?= e(t('delete')) ?></button>
                    </form>
                </td><?php endif; ?>
            </tr>
        <?php endforeach; ?></tbody>
    </table></div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
