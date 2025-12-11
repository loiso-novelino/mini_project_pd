<?php
require_once 'functions.php';

$kategori_list = ['Fiksi', 'Nonfiksi', 'Teknologi', 'Sejarah', 'Sains', 'Lainnya'];
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $tahun = trim($_POST['tahun']);
    $stok = (int)$_POST['stok'];
    $kategori = $_POST['kategori'];

    if (strlen($judul) < 3 || strlen($penulis) < 3) {
        $error = "Judul dan penulis minimal 3 karakter.";
    } elseif ($stok <= 0) {
        $error = "Stok harus lebih dari 0.";
    } elseif (!in_array($kategori, $kategori_list)) {
        $error = "Kategori tidak valid.";
    } else {
        $judul = strtoupper($judul);
        $penulis = ucwords(strtolower($penulis));
        $buku_lines = bacaFile('data/buku.txt');
        $last_id = 0;
        foreach ($buku_lines as $line) {
            $parts = explode('|', $line);
            if (!empty($parts[0])) {
                $last_id = max($last_id, (int)$parts[0]);
            }
        }
        $new_id = $last_id + 1;
        generateKodeBuku($judul, $new_id);
        $record = "$new_id|$judul|$penulis|$tahun|$stok|$kategori";
        tambahKeFile('data/buku.txt', $record);
        $success = "Buku berhasil ditambahkan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Buku</title>
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
            <h2>➕ Tambah Buku Baru</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" required>
                </div>
                <div class="form-group">
                    <label>Penulis</label>
                    <input type="text" name="penulis" required>
                </div>
                <div class="form-group">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun" min="1900" max="2030" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" min="1" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach ($kategori_list as $k): ?>
                            <option value="<?= htmlspecialchars($k) ?>"><?= $k ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Buku</button>
            </form>

            <a href="index.php" class="btn btn-secondary back-link">Kembali ke Dashboard</a>
        </div>
    </main>
    <script src="assets/js/main.js"></script>
</body>
</html>