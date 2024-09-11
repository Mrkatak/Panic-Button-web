<?php
require_once('koneksi.php');

// Mendapatkan parameter 'nomor_rumah' dari URL
$nomorRumah = isset($_GET['nomor_rumah']) ? mysqli_real_escape_string($koneksi, $_GET['nomor_rumah']) : '';

// Validasi parameter 'nomor_rumah'
if (!empty($nomorRumah)) {
    // Query untuk mendapatkan semua log untuk nomor rumah tertentu
    $sqlDetailLog = "SELECT * FROM monitor_log WHERE nomor_rumah = '$nomorRumah' ORDER BY waktu DESC";
    $resultDetailLog = $koneksi->query($sqlDetailLog);

    $data = array();

    if ($resultDetailLog && $resultDetailLog->num_rows > 0) {
        while ($rowDetailLog = $resultDetailLog->fetch_assoc()) {
            $rowDetailLog['status'] = getStatus($rowDetailLog['waktu']);
            $data[] = $rowDetailLog;
        }
        // Kembalikan data dalam format JSON
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Tidak ada data log untuk nomor rumah ini.']);
    }
} else {
    echo json_encode(['error' => 'Nomor rumah tidak ditemukan atau tidak valid.']);
}

$koneksi->close();

// Function to determine user status
function getStatus($timestamp) {
    $currentTime = time();
    $logTime = strtotime($timestamp);
    return ($currentTime - $logTime < 10 * 60) ? 'Online' : 'Offline';  // Online jika log kurang dari 10 menit
}
?>
