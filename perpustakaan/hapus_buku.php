<?php
require_once 'functions.php';

$id = $_GET['id'] ?? '';

if (!$id) {
    header("Location: daftar_buku.php");
    exit;
}

$pinjam_list = bacaFile('data/peminjaman.txt');
$masih_dipinjam = false;
foreach ($pinjam_list as $p) {
    $parts = explode('|', $p);
    if (isset($parts[2]) && $parts[2] == $id && isset($parts[5]) && $parts[5] === 'dipinjam') {
        $masih_dipinjam = true;
        break;
    }
}

if ($masih_dipinjam) {
    header("Location: daftar_buku.php?error=masih_dipinjam");
    exit;
}

$lines = bacaFile('data/buku.txt');
$updated = [];
foreach ($lines as $line) {
    $parts = explode('|', $line);
    if ($parts[0] != $id) {
        $updated[] = $line;
    }
}
tulisFile('data/buku.txt', $updated);

header("Location: daftar_buku.php?pesan=hapus_berhasil");
exit;
?>