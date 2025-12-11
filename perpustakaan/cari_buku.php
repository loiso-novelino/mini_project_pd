<?php
require_once 'functions.php';

function getKategoriUnik() {
    $buku_list = bacaFile('data/buku.txt');
    $kategori = [];
    foreach ($buku_list as $b) {
        $parts = explode('|', $b);
        if (isset($parts[5]) && !in_array($parts[5], $kategori)) {
            $kategori[] = $parts[5];
        }
    }
    return $kategori;
}

$hasil = [];
$keyword = '';
$kategori_pilih = '';
$kategori_list = getKategoriUnik();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = trim($_POST['keyword'] ?? '');
    $kategori_pilih = trim($_POST['kategori'] ?? '');

    $buku_list = bacaFile('data/buku.txt');

    foreach ($buku_list as $b) {
        $parts = explode('|', $b);
        if (count($parts) < 6) continue;

        $cocok = false;
        if ($keyword !== '') {
            if (stripos($parts[1], $keyword) !== false) {
                $cocok = true;
            }
        } elseif ($kategori_pilih !== '') {
            if ($parts[5] === $kategori_pilih) {
                $cocok = true;
            }
        }

        if ($cocok) {
            $hasil[] = $parts;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cari Buku</title>
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
            <h2>🔍 Cari Buku</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Cari Judul Buku</label>
                    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Masukkan judul...">
                </div>
                <div class="form-group">
                    <label>Filter Kategori</label>
                    <select name="kategori">
                        <option value="">-- Semua Kategori --</option>
                        <?php foreach ($kategori_list as $k): ?>
                            <option value="<?= htmlspecialchars($k) ?>" <?= $kategori_pilih === $k ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cari Buku</button>
            </form>

            <?php if (!empty($hasil)): ?>
                <h3>Hasil Pencarian (<?= count($hasil) ?> ditemukan)</h3>
                <table>
                    <thead>
                        <tr>
                            <th data-label="ID">ID</th>
                            <th data-label="Judul">Judul</th>
                            <th data-label="Penulis">Penulis</th>
                            <th data-label="Tahun">Tahun</th>
                            <th data-label="Stok">Stok</th>
                            <th data-label="Kategori">Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hasil as $b): ?>
                            <tr>
                                <td data-label="ID"><?= htmlspecialchars($b[0]) ?></td>
                                <td data-label="Judul"><?= htmlspecialchars($b[1]) ?></td>
                                <td data-label="Penulis"><?= htmlspecialchars($b[2]) ?></td>
                                <td data-label="Tahun"><?= htmlspecialchars($b[3]) ?></td>
                                <td data-label="Stok"><?= htmlspecialchars($b[4]) ?></td>
                                <td data-label="Kategori"><?= htmlspecialchars($b[5]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <div class="alert alert-error">Tidak ada buku ditemukan dengan kriteria tersebut.</div>
            <?php endif; ?>

            <a href="index.php" class="btn btn-secondary back-link">Kembali ke Dashboard</a>
        </div>
    </main>
</body>
</html>