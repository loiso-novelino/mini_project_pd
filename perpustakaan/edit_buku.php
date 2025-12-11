<?php
require_once 'functions.php';

$id = $_GET['id'] ?? '';
$buku = $id ? cariByID('data/buku.txt', $id) : null;

if (!$buku || count($buku) < 6) {
    die("Buku tidak ditemukan.");
}

$kategori_list = ['Fiksi', 'Nonfiksi', 'Teknologi', 'Sejarah', 'Sains', 'Lainnya'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $tahun = trim($_POST['tahun']);
    $stok = (int)$_POST['stok'];
    $kategori = $_POST['kategori'];

    if (strlen($judul) < 3 || strlen($penulis) < 3) {
        $error = "Judul dan penulis minimal 3 karakter.";
    } elseif ($stok < 0) {
        $error = "Stok tidak boleh negatif.";
    } elseif (!in_array($kategori, $kategori_list)) {
        $error = "Kategori tidak valid.";
    } else {
        $judul = strtoupper($judul);
        $penulis = ucwords(strtolower($penulis));
        generateKodeBuku($judul, $id);

        $lines = bacaFile('data/buku.txt');
        $updated = [];
        foreach ($lines as $line) {
            $parts = explode('|', $line);
            if ($parts[0] == $id) {
                $parts = [$id, $judul, $penulis, $tahun, $stok, $kategori];
            }
            $updated[] = implode('|', $parts);
        }
        tulisFile('data/buku.txt', $updated);
        header("Location: daftar_buku.php?pesan=edit_berhasil");
        exit;
    }
}

$judul = $buku[1];
$penulis = $buku[2];
$tahun = $buku[3];
$stok = $buku[4];
$kategori = $buku[5];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Buku</title>
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
            <h2>✏️ Edit Buku</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($judul) ?>" required>
                </div>
                <div class="form-group">
                    <label>Penulis</label>
                    <input type="text" name="penulis" value="<?= htmlspecialchars($penulis) ?>" required>
                </div>
                <div class="form-group">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun" value="<?= $tahun ?>" min="1900" max="2030" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?= $stok ?>" min="0" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <?php foreach ($kategori_list as $k): ?>
                            <option value="<?= $k ?>" <?= $k === $kategori ? 'selected' : '' ?>><?= $k ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>

            <a href="daftar_buku.php" class="btn btn-secondary back-link">Batal / Kembali ke Daftar</a>
        </div>
    </main>
    <script src="assets/js/main.js"></script>
</body>
</html>