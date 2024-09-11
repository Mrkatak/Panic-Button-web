<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring and Log Data</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 30px;
            margin: 0px;
        }

        /* NOMOR RUMAH */
        .container {
            width: 100%;
            max-width: 550px;
            text-align: center;
            margin-top: 5px;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
        }

        .container h2 {
            color: #333;
            font-size: 1.5em;
            margin-top: -5px;
            margin-bottom: -5px;
        }


        .table-container {
            width: 100%;
            table-layout: fixed;
            margin-top: 20px;
        }

        #logTable {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #f2f2f2;
        }

        .red-background {
            background-color: red;
        }

        h1,
        h2 {
            text-align: center;
        }


        /* DATA REKAP */
        .rekap-container {
            width: 100%;
            max-width: 550px;
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }

        .rekap-container h2 {
            color: #333;
            font-size: 1.5em;
            margin-top: -5px;
            margin-bottom: -5px;
        }

        .rekap-container table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .rekap-container th,
        .rekap-container td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            font-size: 16px;
            width: 50%;
        }

        .rekap-container th {
            background-color: #f2f2f2;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

       /* JUDUL */
.judul-container {
    width: 30%;
    margin: 0 auto; /* Center the container */
    text-align: center;
    margin-top: -5px;
    margin-bottom: 10px;
    padding: 20px;
    border-radius: 8px;
}

.judul-container h1 {
    margin: 0;
}

/* Emergency background */
.judul-container.emergency {
    position: relative;
    color: #fff;
}

.judul-container.emergency::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #ff0000;
    border-radius: 8px;
    z-index: -1;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .judul-container {
        width: 90%; /* Adjust the width for smaller screens */
        padding: 15px; /* Decrease padding for smaller screens */
    }

    .judul-container h1 {
        font-size: 18px; /* Adjust font size for smaller screens */
    }
}



    </style>
</head>

<body>
    <div class="judul-container emergency">
        <h1>INFORMASI DARURAT</h1>
    </div>



    <div class="container">
        <h2>Nomor Rumah</h2>

        <?php
        require_once('koneksi.php');

        // Monitoring code
        $sqlLatestData = "SELECT * FROM logrum ORDER BY waktu DESC LIMIT 1";
        $resultLatestData = $koneksi->query($sqlLatestData);

        if ($resultLatestData && $resultLatestData->num_rows > 0) {
            $rowLatestData = $resultLatestData->fetch_assoc();

            $currentTime = time();
            $lastDataTime = strtotime($rowLatestData["waktu"]);
            // $isRedBackground = ($currentTime - $lastDataTime) < 10;

            // echo "<div class='table-container" . ($isRedBackground ? " red-background" : "") . "'>";
            echo "<table id='logTable'>";
            echo "<tr>";
            echo "<td style='font-size: 200px;'>" . $rowLatestData["log"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td style='font-size: 40px;'>" . $rowLatestData["waktu"] . "</td>";
            echo "</tr>";
            echo "</table>";

            echo "</div>";

        } else {
            echo "Tidak ada data log.";
        }
        ?>

    </div>


        <?php
        // Log data display
        $sqlRekapData = "SELECT * FROM logrum ORDER BY waktu DESC LIMIT 10";
        $resultRekapData = $koneksi->query($sqlRekapData);


        $koneksi->close();
        ?>

        <div style="margin-top: 20px;">
            <a href="rekap.php" target="_blank">
                <button>Data Rekap</button>
            </a>

            <a href="registrasi.php" target="_blank">
                <button>Daftar</button>
            </a>
        </div>



</body>
<script>
    function toggleBackground() {
        document.querySelector('.table-container').classList.toggle('red-background');
    }

    setInterval(function() {
        location.reload();
    }, 1000);
</script>

</html>