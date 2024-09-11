<?php

include('koneksi.php');

header('Content-Type: application/json');

// Query data dari database
$query = "SELECT id, nomor_rumah, waktu FROM monitor_log ORDER BY id DESC";
$result = $koneksi->query($query);

// array
$data = array();

// Ambil data dari hasil query
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['status'] = getStatus($row['waktu']);
        $data[] = $row;
    }
} else {
    $data[] = [
        'nomor_rumah' => "Data tidak tersedia",
        'waktu' => "Data tidak tersedia"
    ];
}

// Mengembalikan data dalam format JSON
echo json_encode($data);

// Fungsi untuk mendapatkan status berdasarkan waktu
function getStatus($waktu) {
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
