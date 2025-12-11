<?php
require_once 'functions.php';

$buku_list = bacaFile('data/buku.txt');

$kategori_unik = [];
foreach ($buku_list as $b) {
    $parts = explode('|', $b);
    if (isset($parts[5]) && !in_array($parts[5], $kategori_unik)) {
        $kategori_unik[] = $parts[5];
    }
}

$kategori_filter = $_GET['kategori'] ?? '';
if ($kategori_filter !== '') {
    $filtered = [];
    foreach ($buku_list as $b) {
        $parts = explode('|', $b);
        if (isset($parts[5]) && $parts[5] === $kategori_filter) {
            $filtered[] = $b;
        }
    }
    $buku_list = $filtered;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Sistem Manajemen Perpustakaan</h1>
        </div>
    </header>

    <main>
        <div class="card">
            <h2>Daftar Buku</h2>

            <div class="filter-bar">
                <a href="?kategori=" class="<?= $kategori_filter === '' ? 'active' : '' ?>">Semua</a>
                <?php foreach ($kategori_unik as $k): ?>
                    <a href="?kategori=<?= urlencode($k) ?>" class="<?= $kategori_filter === $k ? 'active' : '' ?>">
                        <?= htmlspecialchars($k) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (empty($buku_list)): ?>
                <div class="alert alert-error">Belum ada buku tersedia.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th data-label="ID">ID</th>
                            <th data-label="Judul">Judul</th>
                            <th data-label="Penulis">Penulis</th>
                            <th data-label="Tahun">Tahun</th>
                            <th data-label="Stok">Stok</th>
                            <th data-label="Kategori">Kategori</th>
                            <th data-label="Aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($buku_list as $b):
                            $parts = explode('|', $b);
                            if (count($parts) < 6) continue;
                        ?>
                            <tr>
                                <td data-label="ID"><?= htmlspecialchars($parts[0]) ?></td>
                                <td data-label="Judul"><?= htmlspecialchars($parts[1]) ?></td>
                                <td data-label="Penulis"><?= htmlspecialchars($parts[2]) ?></td>
                                <td data-label="Tahun"><?= htmlspecialchars($parts[3]) ?></td>
                                <td data-label="Stok"><?= htmlspecialchars($parts[4]) ?></td>
                                <td data-label="Kategori"><?= htmlspecialchars($parts[5]) ?></td>
                                <td data-label="Aksi" class="actions">
                                    <a href="edit_buku.php?id=<?= $parts[0] ?>" class="btn btn-secondary">✏️ Edit</a>
                                    <a href="hapus_buku.php?id=<?= $parts[0] ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('Yakin hapus buku ini?')">❌ Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] === 'hapus_berhasil') {
                    echo '<div class="alert alert-success">Buku berhasil dihapus.</div>';
                } elseif ($_GET['pesan'] === 'edit_berhasil') {
                    echo '<div class="alert alert-success">Buku berhasil diperbarui.</div>';
                }
            }
            if (isset($_GET['error']) && $_GET['error'] === 'masih_dipinjam') {
                echo '<div class="alert alert-error">Gagal: Buku ini sedang dipinjam dan tidak bisa dihapus.</div>';
            }
            ?>

            <a href="index.php" class="btn btn-secondary back-link">Kembali ke Dashboard</a>
        </div>
    </main>
</body>
</html>