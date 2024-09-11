<?php

// Simpan dengan nama file proses.php
require "koneksidb.php";

// Cek apakah parameter state, id, dan nomor_rumah ada
if (isset($_GET['state']) && isset($_GET["id"]) && isset($_GET['nomor_rumah'])) {
    $ID = $_GET["id"];
    $state = $_GET['state'];
    $nomorRumah = $_GET['nomor_rumah'];

    // Debug: Tulis parameter ke log atau output
    error_log("Received nomor_rumah: $nomorRumah");

    $data = query("SELECT * FROM tabel_kontrol WHERE ID = '$ID'")[0];

    // Cek tipe dan sesuaikan state
    if ($data["TYPE"] == "Active Low") {
        if ($state == 1) {
            $state = 0;
        } else {
            $state = 1;
        }
    }

    if ($data["TYPE"] == "Active High") {
        if ($state == 1) {
            $state = 1;
        } else {
            $state = 0;
        }
    }

    // Perbarui state
    $sql = "UPDATE tabel_kontrol SET STATE = '$state' WHERE ID = '$ID'";
    $koneksi->query($sql);

    // Menerima nomor rumah dari GET request (dikirim dari aplikasi Android)
    if (isset($_GET['nomor_rumah'])) {
        $nomorRumah = $_GET['nomor_rumah'];

        // Debug: Tulis nomor rumah ke log atau output
        error_log("Inserting nomor_rumah into monitor_log: $nomorRumah");

        // Jika state adalah 1 (aktif), simpan nomor rumah ke tabel monitor_log
        if ($state == 1) {
            $query = "INSERT INTO monitor_log (nomor_rumah, waktu) VALUES ('$nomorRumah', NOW())";
            if ($koneksi->query($query) === FALSE) {
                error_log("Error inserting into monitor_log: " . $koneksi->error);
            }
        }
    }
}

// Inisialisasi $json sebagai array kosong
$json = array();

// Proses output device
if (isset($_GET["board"])) {
    $board = $_GET["board"];
    $data = query("SELECT * FROM tabel_kontrol");

    foreach ($data as $row) {
        if ($board == $row["BOARD"]) {
            $json[$row["GPIO"]] = $row["STATE"];
        }
    }

    $result = json_encode($json);
    echo $result;
}
?>