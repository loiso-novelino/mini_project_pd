<?php
require_once 'functions.php';

$anggota_list = bacaFile('data/anggota.txt');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Anggota</title>
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
            <h2>👥 Daftar Anggota</h2>

            <?php if (empty($anggota_list)): ?>
                <div class="alert alert-error">Belum ada anggota terdaftar.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th data-label="ID">ID</th>
                            <th data-label="Nama">Nama</th>
                            <th data-label="Email">Email</th>
                            <th data-label="HP">HP</th>
                            <th data-label="JK">Jenis Kelamin</th>
                            <th data-label="Status">Status</th>
                            <th data-label="Aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anggota_list as $a):
                            $parts = explode('|', $a);
                            if (count($parts) < 6) continue;
                        ?>
                            <tr>
                                <td data-label="ID"><?= htmlspecialchars($parts[0]) ?></td>
                                <td data-label="Nama"><?= htmlspecialchars($parts[1]) ?></td>
                                <td data-label="Email"><?= htmlspecialchars($parts[2]) ?></td>
                                <td data-label="HP"><?= htmlspecialchars($parts[3]) ?></td>
                                <td data-label="JK"><?= $parts[4] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                <td data-label="Status">
                                    <?php if ($parts[5] === 'aktif'): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Aksi" class="actions">
                                    <a href="edit_anggota.php?id=<?= $parts[0] ?>" class="btn btn-secondary">✏️ Edit</a>
                                    <a href="riwayat_peminjaman.php?id=<?= $parts[0] ?>" class="btn btn-secondary">📜 Riwayat</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'edit_berhasil'): ?>
                <div class="alert alert-success">Data anggota berhasil diperbarui.</div>
            <?php endif; ?>

            <a href="index.php" class="btn btn-secondary back-link">Kembali ke Dashboard</a>
        </div>
    </main>
</body>
</html>