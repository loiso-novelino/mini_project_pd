<?php
require_once 'functions.php';

$id = $_GET['id'] ?? '';
$anggota = $id ? cariByID('data/anggota.txt', $id) : null;

if (!$anggota || count($anggota) < 6) {
    die("Anggota tidak ditemukan.");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $hp = trim($_POST['hp']);
    $jk = $_POST['jk'];
    $status = $_POST['status'];

    if (strlen($nama) < 3) {
        $error = "Nama minimal 3 karakter.";
    } elseif (!validasiEmail($email)) {
        $error = "Email tidak valid.";
    } else {
        $lines = bacaFile('data/anggota.txt');
        $updated = [];
        foreach ($lines as $line) {
            $parts = explode('|', $line);
            if ($parts[0] == $id) {
                $parts = [$id, $nama, $email, $hp, $jk, $status];
            }
            $updated[] = implode('|', $parts);
        }
        tulisFile('data/anggota.txt', $updated);
        header("Location: daftar_anggota.php?pesan=edit_berhasil");
        exit;
    }
}

$nama = $anggota[1];
$email = $anggota[2];
$hp = $anggota[3];
$jk = $anggota[4];
$status = $anggota[5];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Anggota</title>
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
            <h2>✏️ Edit Data Anggota</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" name="hp" value="<?= htmlspecialchars($hp) ?>" required>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <div class="radio-group">
                        <label><input type="radio" name="jk" value="L" <?= $jk === 'L' ? 'checked' : '' ?>> Laki-laki</label>
                        <label><input type="radio" name="jk" value="P" <?= $jk === 'P' ? 'checked' : '' ?>> Perempuan</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status Keanggotaan</label>
                    <select name="status">
                        <option value="aktif" <?= $status === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="tidak_aktif" <?= $status === 'tidak_aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>

            <a href="daftar_anggota.php" class="btn btn-secondary back-link">Batal / Kembali ke Daftar</a>
        </div>
    </main>
    <script src="assets/js/main.js"></script>
</body>
</html>