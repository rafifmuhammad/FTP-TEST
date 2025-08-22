<?php
session_start();

if (empty($_SESSION['login']) || $_SESSION['login'] !== true) {
  header('Location: login.php');
  exit;
}

// Ambil data koneksi dari session
$host = $_SESSION['host'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];

$files = [];
$error = '';

if ($host && $username && $password) {
  $conn = @ftp_connect($host);
  if ($conn && @ftp_login($conn, $username, $password)) {
    $files = @ftp_nlist($conn, "/ftp/");
    ftp_close($conn);
  } else {
    $error = "Gagal koneksi ke FTP server.";
  }
} else {
  $error = "Data koneksi tidak ditemukan di session.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
    crossorigin="anonymous" />
  <title>Dashboard FTP</title>
</head>

<body>
  <div class="container">
    <?php if (!empty($error)) : ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>
    <div class="flex-row">
      <a href="logout.php" class="btn btn-danger btn-sm">Keluar</a>
    </div>
    <?= var_dump($files); ?>
  </div>


  <script
    src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
    integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
    crossorigin="anonymous"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
    integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+"
    crossorigin="anonymous"></script>
</body>

</html>