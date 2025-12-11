<?php
function bacaFile($file) {
    if (!file_exists($file)) return [];
    $handle = fopen($file, 'r');
    $lines = [];
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if ($line !== '') $lines[] = $line;
    }
    fclose($handle);
    return $lines;
}

function tulisFile($file, $data) {
    $handle = fopen($file, 'w');
    foreach ($data as $line) {
        fwrite($handle, $line . "\n");
    }
    fclose($handle);
}

function tambahKeFile($file, $line) {
    $handle = fopen($file, 'a');
    fwrite($handle, $line . "\n");
    fclose($handle);
}

function cariByID($file, $id) {
    $data = bacaFile($file);
    foreach ($data as $line) {
        $parts = explode('|', $line);
        if (isset($parts[0]) && $parts[0] == $id) {
            return $parts;
        }
    }
    return null;
}

function updateStokBuku($id_buku, $stok_baru) {
    $file = 'data/buku.txt';
    $lines = bacaFile($file);
    $updated = [];
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if ($parts[0] == $id_buku) {
            $parts[4] = $stok_baru;
            $line = implode('|', $parts);
        }
        $updated[] = $line;
    }
    tulisFile($file, $updated);
}

function validasiEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function generateKodeBuku($judul, $id) {
    $judul_bersih = preg_replace('/[^A-Za-z0-9]/', '', $judul);
    return substr(strtoupper($judul_bersih), 0, 3) . $id;
}
?>