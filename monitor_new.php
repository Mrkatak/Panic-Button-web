<?php
// Sertakan file koneksi ke database
include('koneksi.php'); // Ganti dengan nama file koneksi yang benar

// Query untuk mengambil satu data terbaru dari tabel monitor_log
$query = "SELECT nomor_rumah, waktu FROM monitor_log ORDER BY id DESC LIMIT 1";
$result = $koneksi->query($query);

// Periksa apakah ada hasil
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nomorRumahTerbaru = $row['nomor_rumah'];
    $waktuTerbaru = $row['waktu'];
} else {
    $nomorRumahTerbaru = "Data tidak tersedia";
    $waktuTerbaru = "Data tidak tersedia";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monitor Log Terbaru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #333;
        }
        p {
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Data Terbaru dari Monitor Log</h2>
        <p><strong>Nomor Rumah:</strong> <span id="nomorRumah"><?php echo $nomorRumahTerbaru; ?></span></p>
        <p><strong>Waktu:</strong> <span id="waktu"><?php echo $waktuTerbaru; ?></span></p>
    </div>

    <script>
        setInterval(function() {
        location.reload();
    }, 1000);
    </script>
</body>
</html>
