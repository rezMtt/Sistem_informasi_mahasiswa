<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

// Jika pengguna sudah login, arahkan ke dashboard
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validasi CSRF
    verify_csrf();

    // Mengambil input dari form
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi input kosong
    if ($username === '' || $password === '') {

        $error = t('required');

    } else {

        // Mengambil data user berdasarkan username
        $stmt = $pdo->prepare(
            "SELECT id, username, password, role
             FROM users
             WHERE username = ?
             LIMIT 1"
        );

        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Memeriksa password
        if ($user && password_verify($password, $user['password'])) {

            // Membuat session baru
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Mengarahkan berdasarkan role
            if ($user['role'] === 'admin') {

                header('Location: dashboard.php');
                exit;

            } elseif ($user['role'] === 'user') {

                header('Location: dashboard.php');
                exit;

            } else {

                // Role tidak dikenali
                $_SESSION = [];
                session_destroy();

                $error = 'Hak akses akun tidak dikenali.';
            }

        } else {

            $error = t('login_error');

        }
    }
}

// Judul halaman
$pageTitle = 'Login Administrator';

// Memanggil header
require __DIR__ . '/includes/header.php';
?>

<section class="auth-wrap">

    <div class="auth-card">

        <div class="brand-large">M</div>

        <p class="eyebrow">
            STUDENT MANAGEMENT SYSTEM
        </p>

        <h1>Selamat Datang!</h1>

        <p class="muted">
            Silakan login untuk mengakses
            Sistem Informasi Data Mahasiswa.
        </p>

        <!-- Menampilkan pesan error -->
        <?php if ($error): ?>

            <div class="alert error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>

        <?php endif; ?>

        <!-- Form Login -->
        <form method="POST" action="login.php">

            <!-- CSRF Token -->
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>"
            >

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                placeholder="Masukkan username"
                required
                autocomplete="username"
            >

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
                autocomplete="current-password"
            >

            <button
                type="submit"
                class="btn primary full"
            >
                Login
            </button>

        </form>

        <p class="hint">
            Sistem Informasi Manajemen Data Mahasiswa
        </p>

    </div>

</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
