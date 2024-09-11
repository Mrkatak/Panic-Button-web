<?php
require_once('koneksi.php');

// Mendapatkan parameter 'log' dari URL
$nomorRumah = isset($_GET['log']) ? mysqli_real_escape_string($koneksi, $_GET['log']) : '';

// Validasi parameter 'log'
if (!empty($nomorRumah)) {
    // Query untuk mendapatkan semua log untuk nomor rumah tertentu dari tabel monitor_log
    $sqlDetailLog = "SELECT * FROM monitor_log WHERE nomor_rumah = '$nomorRumah' ORDER BY waktu DESC";
    $resultDetailLog = $koneksi->query($sqlDetailLog);

    if ($resultDetailLog && $resultDetailLog->num_rows > 0) {
        echo "<h2>Detail Log untuk Nomor Rumah: $nomorRumah</h2>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr>
            <th>Waktu</th>
            <th>Nomor Rumah</th>
            <th>Status</th>
            </tr>";

        while ($rowDetailLog = $resultDetailLog->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rowDetailLog["waktu"] . "</td>";
            echo "<td>" . $rowDetailLog["nomor_rumah"] . "</td>";

            $status = getStatus($rowDetailLog["waktu"]);
            $statusClass = strtolower($status);

            echo "<td class='$statusClass'>$status</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Tidak ada data log untuk nomor rumah: $nomorRumah.</p>";
    }
} else {
    echo "<p>Nomor rumah tidak ditemukan atau tidak valid.</p>";
}

$koneksi->close();

// Function to determine user status
function getStatus($timestamp) {
    $currentTime = new DateTime();
    $logTime = new DateTime($timestamp);
    $interval = $currentTime->diff($logTime);

    // Tentukan user sebagai "Online" jika selisih waktu kurang dari 5 menit
    return ($interval->i < 5 && $interval->h == 0 && $interval->d == 0) ? 'Online' : 'Offline';
}
?>
