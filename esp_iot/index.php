<?php
session_start(); // Mulai sesi

require "template.php";

$data = query("SELECT * FROM tabel_kontrol");

// Cek apakah pengguna sudah login
if (!isset($_SESSION['nomor_rumah'])) {
    // Jika belum login, arahkan kembali ke halaman login
    header("Location: login.php");
    exit();
}

// Ambil nomor rumah dari sesi
$nomorRumah = $_SESSION['nomor_rumah'];

if (isset($_POST["simpan"])) {
    if (tambahOutput($_POST) > 0) {
        echo "
            <script>
                window.location.href='index.php';
            </script>
        ";
    } else {
        echo "
            <script>
                window.location.href='index.php';
            </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panic Button</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Tambahkan SweetAlert -->
    <style>
        body {
            background: url('hose.jpg') no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }
        h4 {
            color: #ffffff;
            font-family: Arial, sans-serif;
            font-size: 3.0em;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
            width: 100%;
        }
        .custom-table {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }
        .toggle-switch {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px 0;
        }
        .nomor-rumah {
            color: #ffffff;
            font-family: Arial, sans-serif;
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h4>PANIC BUTTON</h4>
    <div class="nomor-rumah">Nomor Rumah: <?php echo htmlspecialchars($nomorRumah); ?></div> <!-- Tampilkan nomor rumah -->
    <div class="custom-table">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">Tombol Darurat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row) : ?>
                <tr class="text-center">
                    <td>
                        <div class="toggle-switch">
                            <?php 
                            if ($row["TYPE"] == "Active High") {
                                if ($row["STATE"] == 0) {
                                    echo '<input type="checkbox" id="' . $row["ID"] . '" onchange="sendData(this)" data-toggle="toggle" data-onstyle="primary" data-offstyle="danger">';
                                } else {
                                    echo '<input type="checkbox" checked id="' . $row["ID"] . '" onchange="sendData(this)" data-toggle="toggle" data-onstyle="primary" data-offstyle="danger">';
                                }
                            }
                            if ($row["TYPE"] == "Active Low") {
                                if ($row["STATE"] == 1) {
                                    echo '<input type="checkbox" id="' . $row["ID"] . '" onchange="sendData(this)" data-toggle="toggle" data-onstyle="primary" data-offstyle="danger">';
                                } else {
                                    echo '<input type="checkbox" checked id="' . $row["ID"] . '" onchange="sendData(this)" data-toggle="toggle" data-onstyle="primary" data-offstyle="danger">';
                                }
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    function sendData(e) {
    var state = e.checked ? 1 : 0;

    // Jika toggle akan diaktifkan (On)
    if (state === 1) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Anda akan mengaktifkan Panic Button!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengonfirmasi, kirim data ke server
                updateToggleState(e, state);
            } else {
                // Jika pengguna membatalkan, kembalikan toggle ke posisi Off
                e.checked = false;
            }
        });
        } else {
            // Jika toggle akan dinonaktifkan (Off), langsung kirim data tanpa konfirmasi
            updateToggleState(e, state);
        }
    }
    function updateToggleState(e, state) {
    var xhr = new XMLHttpRequest();
    var message = state ? 'Panic Button telah diaktifkan.' : 'Panic Button telah dinonaktifkan.';
    var type = state ? 'success' : 'info';

    xhr.open("GET", "proses.php?id=" + e.id + "&state=" + state, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Setelah berhasil menyimpan ke server, set notifikasi
            localStorage.setItem('notifMessage', message);
            localStorage.setItem('notifType', type);
        }
    };
    xhr.send();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Periksa apakah ada notifikasi yang tersimpan
        var notifMessage = localStorage.getItem('notifMessage');
        var notifType = localStorage.getItem('notifType');

        if (notifMessage) {
            // Tampilkan notifikasi
            Swal.fire({
                title: notifMessage,
                icon: notifType,
                confirmButtonText: 'OK'
            });

            // Hapus notifikasi dari localStorage setelah ditampilkan
            localStorage.removeItem('notifMessage');
            localStorage.removeItem('notifType');
        }
    });
    </script>
</body>
</html>
