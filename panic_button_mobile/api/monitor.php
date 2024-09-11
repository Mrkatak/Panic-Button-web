<?php
  
include('koneksi.php');

$query = "SELECT nomor_rumah, waktu FROM monitor_log ORDER BY id DESC LIMIT 4";
$result = $koneksi->query($query);

$dataTerbaru = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dataTerbaru[] = $row;
    }
} else {
    $dataTerbaru[] = [
        'nomor_rumah' => "Data tidak tersedia",
        'waktu' => "Data tidak tersedia"
    ];
}

echo json_encode($dataTerbaru);
?>

