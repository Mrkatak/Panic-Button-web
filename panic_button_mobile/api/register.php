<?php

$host = 'localhost';
$dbname = 'panic_button'; 
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mendapatkan data dari request POST
$nomorRumah = $_POST['nomorRumah'];
$sandi = $_POST['sandi'];

// Mengecek apakah data sudah lengkap
if (empty($nomorRumah) || empty($sandi)) {
    echo json_encode(array("status" => "error", "message" => "Semua data harus diisi"));
    exit();
}

// Mengecek apakah nomor rumah sudah ada di database
$checkQuery = "SELECT * FROM user WHERE norum = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param('s', $nomorRumah); // 's' berarti string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jika nomor rumah sudah ada, registrasi gagal
    echo json_encode(array("status" => "error", "message" => "Nomor rumah sudah terdaftar"));
} else {
    // Jika nomor rumah belum ada, lanjutkan dengan registrasi
    $sql = "INSERT INTO user (norum, sandi) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $nomorRumah, $sandi); // 'ss' berarti dua string
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Registrasi berhasil"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Gagal registrasi: " . $conn->error));
    }
}

$conn->close();
?>
