<?php
require_once('koneksi.php');

// Query untuk mendapatkan 10 data terbaru dari tabel monitor_log
$sqlStatus = "SELECT nomor_rumah, waktu FROM monitor_log ORDER BY waktu DESC ";
$resultStatus = $koneksi->query($sqlStatus);

$statusData = [];
if ($resultStatus && $resultStatus->num_rows > 0) {
    while ($rowStatus = $resultStatus->fetch_assoc()) {
        $nomorRumah = $rowStatus["nomor_rumah"];
        $waktu = $rowStatus["waktu"];

        // Menentukan status pengguna berdasarkan waktu log
        $status = getStatus($waktu);

        $statusData[] = [
            'nomorRumah' => $nomorRumah,
            'waktu' => $waktu,
            'status' => $status
        ];
    }
}

header('Content-Type: application/json');
echo json_encode(['logs' => $statusData]);

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
