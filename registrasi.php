<?php
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
        // Cek apakah nomor rumah sudah terdaftar
        $queryCheck = "SELECT * FROM user WHERE norum = '$nomorRumah'";
        $resultCheck = $koneksi->query($queryCheck);

        if ($resultCheck->num_rows > 0) {
            // Nomor rumah sudah terdaftar
            $error = "Nomor rumah sudah terdaftar. Silakan gunakan nomor rumah lain.";
        } else {
            // Nomor rumah belum terdaftar, lakukan proses registrasi
            $queryInsert = "INSERT INTO user (norum, sandi) VALUES ('$nomorRumah', '$sandi')";

            if ($koneksi->query($queryInsert) === TRUE) {
                // Registrasi berhasil, arahkan ke halaman login
                header("Location: login.php");
                exit();
            } else {
                $error = "Error: " . $queryInsert . "<br>" . $koneksi->error;
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
            background: url('whitehouse.jpg') no-repeat;
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
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

        button, a {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button {
            background-color: #3498db;
            color: #fff;
        }

        a {
            background-color: #2ecc71;
            color: #fff;
            text-decoration: none;
            margin-left: 10px;
        }

        button:hover, a:hover {
            background-color: #2980b9;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
    <title>Register Page</title>
</head>
<body>
    <div class="container">
        <h2>Daftar</h2>
        <!-- Perbaikan: Tag <form> ditambahkan -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <!-- Perbaikan: Tag <input> diberikan type "text" dan "password" -->
            <input type="text" name="nomor_rumah" placeholder="Nomor Rumah">
            <br>
            <input type="password" name="sandi" placeholder="Sandi">
            <br>
            <button type="submit">Daftar</button>
            <a href="login.php">Kembali</a>
        </form>
        <div class="error"><?php echo $error; ?></div>
    </div>
</body>
</html>
