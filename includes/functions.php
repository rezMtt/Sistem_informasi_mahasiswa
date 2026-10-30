<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function require_login(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Permintaan tidak valid. Silakan kembali dan coba lagi.');
    }
}

function t(string $key): string {
    $lang = $_SESSION['lang'] ?? 'id';
    $dict = [
        'id' => [
            'app' => 'Sistem Informasi Data Mahasiswa', 'dashboard' => 'Dashboard',
            'students' => 'Data Mahasiswa', 'add' => 'Tambah Mahasiswa',
            'logout' => 'Keluar', 'login' => 'Masuk', 'username' => 'Username',
            'password' => 'Password', 'welcome' => 'Selamat datang',
            'total' => 'Total Mahasiswa', 'search' => 'Cari nama atau NIM...',
            'nim' => 'NIM', 'name' => 'Nama Lengkap', 'email' => 'Email',
            'major' => 'Program Studi', 'year' => 'Angkatan',
            'gender' => 'Jenis Kelamin', 'action' => 'Aksi', 'save' => 'Simpan',
            'cancel' => 'Batal', 'edit' => 'Edit', 'delete' => 'Hapus',
            'male' => 'Laki-laki', 'female' => 'Perempuan',
            'choose' => 'Pilih jenis kelamin', 'back' => 'Kembali ke data',
            'login_title' => 'Login Administrator', 'login_error' => 'Username atau password salah.',
            'required' => 'Semua kolom wajib diisi.', 'success_add' => 'Data mahasiswa berhasil ditambahkan.',
            'success_edit' => 'Data mahasiswa berhasil diperbarui.',
            'success_delete' => 'Data mahasiswa berhasil dihapus.',
            'duplicate' => 'NIM sudah terdaftar.', 'no_data' => 'Belum ada data mahasiswa.',
            'confirm_delete' => 'Yakin ingin menghapus data ini?', 'language' => 'Bahasa',
            'subtitle' => 'Kelola data mahasiswa dengan mudah dan terstruktur.',
            'form_add' => 'Form Tambah Mahasiswa', 'form_edit' => 'Form Edit Mahasiswa',
            'invalid_email' => 'Format email tidak valid.', 'invalid_year' => 'Angkatan harus antara 2000 dan 2100.',
            'invalid_gender' => 'Jenis kelamin tidak valid.', 'phone' => 'Nomor Telepon', 'address' => 'Alamat',
            'change_password' => 'Ganti Password', 'search_btn' => 'Cari'
        ],
        'en' => [
            'app' => 'Student Data Information System', 'dashboard' => 'Dashboard',
            'students' => 'Student Data', 'add' => 'Add Student',
            'logout' => 'Logout', 'login' => 'Login', 'username' => 'Username',
            'password' => 'Password', 'welcome' => 'Welcome',
            'total' => 'Total Students', 'search' => 'Search name or student ID...',
            'nim' => 'Student ID', 'name' => 'Full Name', 'email' => 'Email',
            'major' => 'Study Program', 'year' => 'Enrollment Year',
            'gender' => 'Gender', 'action' => 'Actions', 'save' => 'Save',
            'cancel' => 'Cancel', 'edit' => 'Edit', 'delete' => 'Delete',
            'male' => 'Male', 'female' => 'Female',
            'choose' => 'Select gender', 'back' => 'Back to data',
            'login_title' => 'Administrator Login', 'login_error' => 'Incorrect username or password.',
            'required' => 'All fields are required.', 'success_add' => 'Student successfully added.',
            'success_edit' => 'Student data successfully updated.',
            'success_delete' => 'Student successfully deleted.',
            'duplicate' => 'Student ID already exists.', 'no_data' => 'No student data yet.',
            'confirm_delete' => 'Are you sure you want to delete this record?', 'language' => 'Language',
            'subtitle' => 'Manage student records easily and efficiently.',
            'form_add' => 'Add Student Form', 'form_edit' => 'Edit Student Form',
            'invalid_email' => 'Invalid email format.', 'invalid_year' => 'Year must be between 2000 and 2100.',
            'invalid_gender' => 'Invalid gender.', 'phone' => 'Phone Number', 'address' => 'Address',
            'change_password' => 'Change Password', 'search_btn' => 'Search'
        ]
    ];
    return $dict[$lang][$key] ?? $key;
}

// Alias terjemahan (dipakai di dashboard.php)
function __(string $key): string {
    return t($key);
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function is_admin(): bool {
    return ($_SESSION['role'] ?? '') === 'admin';
}

function require_admin(): void {
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        exit('Akses ditolak. Halaman ini hanya untuk administrator.');
    }
}

// Pesan flash (disimpan di session, tampil sekali)
function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

// Ambil & validasi input form mahasiswa. Mengembalikan [data, pesan_error].
function read_mahasiswa_input(): array {
    $data = [];
    foreach (['nim', 'nama', 'email', 'jurusan', 'angkatan', 'jenis_kelamin', 'no_hp', 'alamat'] as $f) {
        $data[$f] = trim($_POST[$f] ?? '');
    }
    $error = '';
    if ($data['nim'] === '' || $data['nama'] === '' || $data['email'] === ''
        || $data['jurusan'] === '' || $data['angkatan'] === '' || $data['jenis_kelamin'] === '') {
        $error = t('required');
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error = t('invalid_email');
    } elseif (!ctype_digit($data['angkatan']) || (int)$data['angkatan'] < 2000 || (int)$data['angkatan'] > 2100) {
        $error = t('invalid_year');
    } elseif (!in_array($data['jenis_kelamin'], ['Laki-laki', 'Perempuan'], true)) {
        $error = t('invalid_gender');
    }
    return [$data, $error];
}
