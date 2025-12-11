<?php
require_once 'functions.php';

$id_anggota = $_GET['id'] ?? '';
$anggota = $id_anggota ? cariByID('data/anggota.txt', $id_anggota) : null;

if (!$anggota) {
    die("Anggota tidak ditemukan.");
}

$pinjam_list = bacaFile('data/peminjaman.txt');
$riwayat = [];
foreach ($pinjam_list as $p) {
    $parts = explode('|', $p);
    if (isset($parts[1]) && $parts[1] == $id_anggota) {
        $riwayat[] = $parts;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Peminjaman</title>
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
            <h2>📜 Riwayat Peminjaman: <?= htmlspecialchars($anggota[1]) ?></h2>

            <?php if (empty($riwayat)): ?>
                <div class="alert alert-error">Anggota ini belum pernah meminjam buku.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th data-label="Tgl Pinjam">Tanggal Pinjam</th>
                            <th data-label="Buku">Buku</th>
                            <th data-label="Jatuh Tempo">Jatuh Tempo</th>
                            <th data-label="Status">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $today = date('Y-m-d');
                        foreach ($riwayat as $p):
                            $buku = cariByID('data/buku.txt', $p[2]);
                            $judul_buku = $buku ? $buku[1] : "Buku dihapus";
                            $tgl_pinjam = $p[3];
                            $tgl_jatuh = $p[4];
                            $status = $p[5];
                            $terlambat = false;
                            $denda = 0;

                            if ($status === 'dipinjam' && $tgl_jatuh < $today) {
                                $terlambat = true;
                                $diff = strtotime($today) - strtotime($tgl_jatuh);
                                $hari_telat = floor($diff / (60 * 60 * 24));
                                if ($hari_telat > 0) {
                                    $denda = $hari_telat * 2000;
                                }
                            }
                        ?>
                            <tr>
                                <td data-label="Tgl Pinjam"><?= htmlspecialchars($tgl_pinjam) ?></td>
                                <td data-label="Buku"><?= htmlspecialchars($judul_buku) ?></td>
                                <td data-label="Jatuh Tempo"><?= htmlspecialchars($tgl_jatuh) ?></td>
                                <td data-label="Status">
                                    <?php if ($status === 'dipinjam'): ?>
                                        <span class="badge badge-warning">Dipinjam</span>
                                        <?php if ($terlambat): ?>
                                            <div class="alert alert-error" style="margin-top:8px; padding:6px;">
                                                ⚠️ TERLAMBAT! Denda: <strong>Rp <?= number_format($denda, 0, ',', '.') ?></strong>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-success">Dikembalikan</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <a href="daftar_anggota.php" class="btn btn-secondary back-link">Kembali ke Daftar Anggota</a>
        </div>
    </main>
</body>
</html>