<?php
require_once 'functions.php';

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $hp = trim($_POST['hp']);
    $jk = $_POST['jk'];

    if (strlen($nama) < 3) {
        $error = "Nama minimal 3 karakter.";
    } elseif (!validasiEmail($email)) {
        $error = "Email tidak valid.";
    } else {
        $anggota_lines = bacaFile('data/anggota.txt');
        $last_id = 0;
        foreach ($anggota_lines as $line) {
            $parts = explode('|', $line);
            if (!empty($parts[0])) {
                $last_id = max($last_id, (int)$parts[0]);
            }
        }
        $new_id = $last_id + 1;
        $record = "$new_id|$nama|$email|$hp|$jk|aktif";
        tambahKeFile('data/anggota.txt', $record);
        $success = "Anggota berhasil ditambahkan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Anggota</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>📚 Sistem Manajemen Perpustakaan</h1>
        </div>
    </header>

    <main>
        <div class="card">
            <h2>➕ Tambah Anggota Baru</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" name="hp" required>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <div class="radio-group">
                        <label><input type="radio" name="jk" value="L" required> Laki-laki</label>
                        <label><input type="radio" name="jk" value="P"> Perempuan</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Anggota</button>
            </form>

            <a href="index.php" class="btn btn-secondary back-link">Kembali ke Dashboard</a>
        </div>
    </main>
    <script src="assets/js/main.js"></script>
</body>
</html>