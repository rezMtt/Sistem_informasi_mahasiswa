<?php
$pageTitle = $pageTitle ?? t('app');
?>
<!DOCTYPE html>
<html lang="<?= e($_SESSION['lang'] ?? 'id') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | <?= e(t('app')) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="dashboard.php"><span class="brand-mark">M</span><?= e(t('app')) ?></a>
    <nav>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="password.php"><?= e(t('change_password')) ?></a>
            <a href="dashboard.php"><?= e(t('dashboard')) ?></a>
            <a href="mahasiswa.php"><?= e(t('students')) ?></a>
            <a href="logout.php"><?= e(t('logout')) ?></a>
        <?php endif; ?>
        <a class="language" href="set_language.php?lang=id">ID</a>
        <span class="muted">/</span>
        <a class="language" href="set_language.php?lang=en">EN</a>
    </nav>
</header>
<main class="container">
