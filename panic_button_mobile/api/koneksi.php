<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "panic_button";

    date_default_timezone_set('Asia/Jakarta');

    // Membuat koneksi ke database
    $koneksi = new mysqli($host, $username, $password, $database);

    // Periksa koneksi
    if ($koneksi->connect_error) {
        die("Koneksi ke database gagal: " . $koneksi->connect_error);
    }
?>
