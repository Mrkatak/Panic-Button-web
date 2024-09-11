<?php
// Sertakan file koneksi ke database
include('koneksi.php'); // Pastikan nama file koneksi benar

// Ambil semua data dari tabel monitor_log
$query = "SELECT id, nomor_rumah, waktu FROM monitor_log ORDER BY id DESC";
$result = $koneksi->query($query);

// Fungsi untuk menentukan status Online/Offline
function getStatus($waktu) {
    // Hitung selisih waktu sekarang dengan waktu di monitor_log
    $current_time = new DateTime();
    $log_time = new DateTime($waktu);
    $interval = $current_time->diff($log_time);

    // Tentukan user sebagai "Online" jika selisih waktu kurang dari 5 menit
    if ($interval->i < 1 && $interval->h == 0 && $interval->d == 0) {
        return "Online";
    } else {
        return "Offline";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rekap Data Monitor Log</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        .online {
            color: green;
            font-weight: bold;
        }
        .offline {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2>Rekap Data Monitor Log</h2>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nomor Rumah</th>
                    <th>Waktu</th>
                    <th>Status</th> <!-- Tambahkan kolom Status -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Periksa apakah ada hasil dari query
                if ($result->num_rows > 0) {
                    // Looping melalui hasil dan menampilkannya dalam tabel
                    while($row = $result->fetch_assoc()) {
                        // Tentukan status user
                        $status = getStatus($row['waktu']);
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nomor_rumah'] . "</td>";
                        echo "<td>" . $row['waktu'] . "</td>";
                        echo "<td class='" . strtolower($status) . "'>" . $status . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Data tidak tersedia</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
