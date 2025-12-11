<?php
require_once 'functions.php';

$pinjam_list = [];
$all_pinjam = bacaFile('data/peminjaman.txt');
foreach ($all_pinjam as $p) {
    $parts = explode('|', $p);
    if (isset($parts[5]) && $parts[5] === 'dipinjam') {
        $pinjam_list[] = $parts;
    }
}

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pinjam = $_POST['pinjam'];
    $all = bacaFile('data/peminjaman.txt');
    $updated = [];
    $target = null;
    foreach ($all as $line) {
        $parts = explode('|', $line);
        if ($parts[0] == $id_pinjam && $parts[5] === 'dipinjam') {
            $parts[5] = 'dikembalikan';
            $target = $parts;
        }
        $updated[] = implode('|', $parts);
    }

    if ($target) {
        updateStokBuku($target[2], (int)cariByID('data/buku.txt', $target[2])[4] + 1);
        tulisFile('data/peminjaman.txt', $updated);
        $success = "Buku berhasil dikembalikan!";
        $pinjam_list = array_filter($pinjam_list, fn($p) => $p[0] != $id_pinjam);
    } else {
        $error = "Peminjaman tidak valid.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kembalikan Buku</title>
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
            <h2>📤 Kembalikan Buku</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if (empty($pinjam_list)): ?>
                <div class="alert alert-error">Tidak ada buku yang sedang dipinjam.</div>
            <?php else: ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Pilih Peminjaman</label>
                        <select name="pinjam" required>
                            <option value="">-- Pilih buku untuk dikembalikan --</option>
                            <?php
                            $today = date('Y-m-d');
                            foreach ($pinjam_list as $p):
                                $anggota = cariByID('data/anggota.txt', $p[1]);
                                $buku = cariByID('data/buku.txt', $p[2]);
                                $jatuh_tempo = $p[4];
                                $denda_text = '';
                                if ($jatuh_tempo < $today) {
                                    $diff = strtotime($today) - strtotime($jatuh_tempo);
                                    $hari_telat = floor($diff / (60 * 60 * 24));
                                    if ($hari_telat > 0) {
                                        $denda = $hari_telat * 2000;
                                        $denda_text = " ⚠️ Denda: Rp " . number_format($denda, 0, ',', '.');
                                    }
                                }
                                echo "<option value='{$p[0]}'>{$anggota[1]} - {$buku[1]} (Jatuh tempo: {$jatuh_tempo}){$denda_text}</option>";
                            endforeach;
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger">Kembalikan Buku</button>
                </form>
            <?php endif; ?>

            <a href="index.php" class="btn btn-secondary back-link">Kembali ke Dashboard</a>
        </div>
    </main>
</body>
</html>