<?php
require_once 'functions.php';

$buku_list = bacaFile('data/buku.txt');
$anggota_list = bacaFile('data/anggota.txt');
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = $_POST['anggota'];
    $id_buku = $_POST['buku'];
    $tgl_pinjam = $_POST['tgl'];

    $buku = cariByID('data/buku.txt', $id_buku);
    if (!$buku) {
        $error = "Buku tidak ditemukan.";
    } elseif ((int)$buku[4] <= 0) {
        $error = "Stok buku habis.";
    } else {
        updateStokBuku($id_buku, (int)$buku[4] - 1);
        $tgl_jatuh_tempo = date('Y-m-d', strtotime($tgl_pinjam . ' +7 days'));
        $pinjam_lines = bacaFile('data/peminjaman.txt');
        $last_id = 0;
        foreach ($pinjam_lines as $line) {
            $parts = explode('|', $line);
            if (!empty($parts[0])) {
                $last_id = max($last_id, (int)$parts[0]);
            }
        }
        $new_id = $last_id + 1;
        $record = "$new_id|$id_anggota|$id_buku|$tgl_pinjam|$tgl_jatuh_tempo|dipinjam";
        tambahKeFile('data/peminjaman.txt', $record);
        $success = "Peminjaman berhasil! Batas pengembalian: " . $tgl_jatuh_tempo;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pinjam Buku</title>
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
            <h2>📥 Pinjam Buku</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Anggota</label>
                    <select name="anggota" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach ($anggota_list as $a): 
                            $a_part = explode('|', $a);
                            echo "<option value='{$a_part[0]}'>{$a_part[1]}</option>";
                        endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Buku</label>
                    <select name="buku" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach ($buku_list as $b): 
                            $b_part = explode('|', $b);
                            if ((int)$b_part[4] > 0) {
                                echo "<option value='{$b_part[0]}'>{$b_part[1]} (Stok: {$b_part[4]})</option>";
                            }
                        endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tgl" required>
                </div>
                <button type="submit" class="btn btn-primary">Pinjam Sekarang</button>
            </form>

            <a href="index.php" class="btn btn-secondary back-link">Kembali ke Dashboard</a>
        </div>
    </main>
</body>
</html>