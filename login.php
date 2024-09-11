<?php
session_start(); // Mulai sesi

// Sertakan file koneksi.php
include('koneksi.php');

// Inisialisasi pesan kesalahan
$error = "";

// Menangani permintaan POST jika ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil nilai dari form
    $nomorRumah = mysqli_real_escape_string($koneksi, $_POST["nomor_rumah"]);
    $sandi = mysqli_real_escape_string($koneksi, $_POST["sandi"]);

    // Melakukan validasi (contoh: pastikan kedua input tidak kosong)
    if (empty($nomorRumah) || empty($sandi)) {
        $error = "Nomor rumah dan sandi harus diisi";

    } else {
        // Cek apakah login sebagai admin
        if ($nomorRumah === "admin" && $sandi === "admin") {
            // Jika login sebagai admin, arahkan ke halaman admin
            header("Location: http://172.16.100.128/button/");
            exit(); // Pastikan untuk keluar setelah pengalihan header
        } else {
            // Query untuk memeriksa kecocokan nomor rumah dan sandi di tabel user
            $query = "SELECT * FROM user WHERE norum = '$nomorRumah' AND sandi = '$sandi'";
            $result = $koneksi->query($query);

            if ($result->num_rows > 0) {
                // Login berhasil
                // Tambahkan data login ke dalam tabel logrum
                $logData = "$nomorRumah";
                $queryInsert = "INSERT INTO logrum (log) VALUES ('$logData')";
                $koneksi->query($queryInsert);

                // Simpan nomor rumah ke dalam sesi
                $_SESSION['nomor_rumah'] = $nomorRumah;

                // Arahkan ke halaman index.php
                header("Location: esp_iot/index.php");
                exit(); // Pastikan untuk keluar setelah pengalihan header
            } else {
                // Login gagal
                $error = "Nomor rumah atau sandi salah";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background: url('whitehouse.jpg') no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input {
            padding: 10px;
            margin: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
    
    <title>Login Page</title>
</head>
<body>
    <div class="container">
        <h2>Masuk</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="nomor_rumah" placeholder="Nomor Rumah" required>
            <br>
            <input type="password" name="sandi" placeholder="Sandi" required>
            <br>
            <button type="submit">Masuk</button>
        </form>
        <div class="error"><?php echo $error; ?></div>
        <a href="registrasi.php">Daftar</a>
    </div>
</body>
</html>
