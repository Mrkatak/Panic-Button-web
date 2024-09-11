<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Panic Button</title>
  <link rel="stylesheet" href="style_1.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .status-online {
        color: green;
    }
    .status-offline {
        color: red;
    }
    h2 {
       text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="navigation">
      <ul>
        <li><a href=""><span class="icon"><ion-icon name="home-outline"></ion-icon></span><span class="title2">Panic Button</span></a></li>
        <li><a href="rekap.php"><span class="icon"><i class="material-icons">poll</i></span><span class="title">Rekap Data</span></a></li>
        <li><a href="monitor.php"><span class="icon"><i class="material-icons">computer</i></span><span class="title">Monitor</span></a></li>
        <li><a href="registrasi.php"><span class="icon"><i class="material-icons">account_box</i></span><span class="title">User Registrasi</span></a></li>
        <li><a href="esp_iot/index.php"><span class="icon"><i class="material-icons">toll</i></span><span class="title">Button</span></a></li>
      </ul>
    </div>

    <div class="main">
      <div class="topbar">
        <div class="toggle"><ion-icon name="menu-outline"></ion-icon></div>
      </div>

      <h2><b>Data Status Rumah Warga</b></h2>
      <table id="data-table">
        <tr>
          <th>Terakhir Dilihat</th>
          <th>Nomor Rumah</th>
          <th>Status Login</th>
          <th>Detail</th>
        </tr>
      </table>
    </div>
  </div>
  
  <script src="main.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
  function checkUserStatus() {
    fetch('status.php')
      .then(response => response.json())
      .then(data => {
        const table = document.getElementById('data-table');
        table.innerHTML = `
            <tr>
                <th>Terakhir Dilihat</th>
                <th>Nomor Rumah</th>
                <th>Status Login</th>
                <th>Detail</th>
            </tr>
        `;
        data.logs.forEach(log => {
            const statusClass = log.status === 'Online' ? 'status-online' : 'status-offline';
            table.innerHTML += `
                <tr>
                    <td>${log.waktu}</td>
                    <td>${log.nomorRumah}</td>
                    <td class="${statusClass}">${log.status}</td>
                    <td><a href="detail_log.php?log=${log.nomorRumah}">Detail Log</a></td>
                </tr>
            `;
        });
        
        // Check for notifications after updating the table
        checkNotification();
      })
      .catch(error => console.error('Error fetching status:', error));
  }

  function checkNotification() {
    const notifMessage = localStorage.getItem('notifMessage');
    const notifType = localStorage.getItem('notifType');
    
    if (notifMessage) {
      Swal.fire({
        title: notifMessage,
        icon: notifType,
        confirmButtonText: 'OK'
      });
      // Remove the notification after displaying
      localStorage.removeItem('notifMessage');
      localStorage.removeItem('notifType');
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    checkUserStatus(); // Check immediately when the page loads
    setInterval(checkUserStatus, 10000); // Then check every 10 seconds
  });
  </script>
</body>
</html>
