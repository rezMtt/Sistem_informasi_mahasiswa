<?php
// Form bersama untuk tambah.php dan edit.php. Membutuhkan $data, $action, $error.
$genders = ['Laki-laki' => t('male'), 'Perempuan' => t('female')];
?>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<section class="panel form-panel">
    <form method="POST" action="<?= e($action) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div class="form-grid">
            <div><label for="nim"><?= e(t('nim')) ?> *</label>
                <input type="text" id="nim" name="nim" maxlength="20" value="<?= e($data['nim']) ?>" required></div>
            <div><label for="nama"><?= e(t('name')) ?> *</label>
                <input type="text" id="nama" name="nama" maxlength="100" value="<?= e($data['nama']) ?>" required></div>
            <div><label for="email"><?= e(t('email')) ?> *</label>
                <input type="email" id="email" name="email" maxlength="100" value="<?= e($data['email']) ?>" required></div>
            <div><label for="jurusan"><?= e(t('major')) ?> *</label>
                <input type="text" id="jurusan" name="jurusan" maxlength="100" value="<?= e($data['jurusan']) ?>" required></div>
            <div><label for="angkatan"><?= e(t('year')) ?> *</label>
                <input type="number" id="angkatan" name="angkatan" min="2000" max="2100" value="<?= e($data['angkatan']) ?>" required></div>
            <div><label for="jenis_kelamin"><?= e(t('gender')) ?> *</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value=""><?= e(t('choose')) ?></option>
                    <?php foreach ($genders as $val => $label): ?>
                        <option value="<?= e($val) ?>" <?= $data['jenis_kelamin'] === $val ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select></div>
            <div><label for="no_hp"><?= e(t('phone')) ?></label>
                <input type="text" id="no_hp" name="no_hp" maxlength="20" value="<?= e($data['no_hp']) ?>"></div>
            <div><label for="alamat"><?= e(t('address')) ?></label>
                <input type="text" id="alamat" name="alamat" value="<?= e($data['alamat']) ?>"></div>
        </div>
        <div class="form-actions">
            <a class="btn light" href="mahasiswa.php"><?= e(t('cancel')) ?></a>
            <button class="btn primary" type="submit"><?= e(t('save')) ?></button>
        </div>
    </form>
</section>
