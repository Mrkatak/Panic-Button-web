<?php
// Sertakan file koneksi.php
include('koneksi.php');

// Inisialisasi array untuk respons
$response = array();

// Menangani permintaan POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil nilai dari form
    $nomorRumah = mysqli_real_escape_string($koneksi, $_POST["nomor_rumah"]);
    $sandi = mysqli_real_escape_string($koneksi, $_POST["sandi"]);

    // Melakukan validasi
    if (empty($nomorRumah) || empty($sandi)) {
        $response['status'] = 'error';
        $response['message'] = 'Nomor rumah dan sandi harus diisi';
    } else {
        // Query untuk memeriksa kecocokan nomor rumah dan sandi di tabel user
        $query = "SELECT * FROM user WHERE norum = '$nomorRumah' AND sandi = '$sandi'";
        $result = $koneksi->query($query);

        if ($result->num_rows > 0) {
            // Login berhasil
            $response['status'] = 'success';
            $response['message'] = 'Login berhasil';

            // Tambahkan data login ke dalam tabel logrum
            $logData = "User dengan nomor rumah $nomorRumah berhasil login";
            $queryInsert = "INSERT INTO logrum (log) VALUES ('$logData')";
            $koneksi->query($queryInsert);
        } else {
            // Login gagal
            $response['status'] = 'error';
            $response['message'] = 'Nomor rumah atau sandi salah';
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request';
}

// Mengembalikan respons dalam format JSON
echo json_encode($response);
?>
