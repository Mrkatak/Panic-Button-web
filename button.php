<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['log'])) {
        $log = $_POST['log'];

        require_once('koneksi.php'); 
        $log = $koneksi->real_escape_string($log);
        $sql = "INSERT INTO logrum (logrum) VALUES ('$log')";
        
        if ($koneksi->query($sql) === TRUE) {
            echo "Data log berhasil disimpan: " . $log;
        } else {
            echo "Gagal menyimpan data: " . $koneksi->error;
        }
        $koneksi->close();
    } else {
        http_response_code(400); // Permintaan Buruk
        echo "Parameter 'log' tidak ditemukan dalam permintaan POST.";
    }
} else {
    http_response_code(405); 
    echo "Hanya metode POST yang diperbolehkan.";
}
?>
