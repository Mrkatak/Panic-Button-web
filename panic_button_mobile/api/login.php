<?php

$host = 'localhost';
$dbname = 'panic_button';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}

$nomorRumah = $_POST['nomorRumah'] ?? '';
$sandi = $_POST['sandi'] ?? '';

if (empty($nomorRumah) || empty($sandi)) {
    echo json_encode(['status' => 'error', 'message' => 'Nomor Rumah dan Sandi harus diisi']);
    exit;
}

$sql = "SELECT * FROM user WHERE norum = :norum AND sandi = :sandi";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':norum', $nomorRumah);
$stmt->bindParam(':sandi', $sandi);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode(['status' => 'success', 'message' => 'Login berhasil']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nomor Rumah atau Sandi salah']);
}

