<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use phpseclib3\Net\SFTP;

if (!empty($_SESSION['login']) && $_SESSION['login'] === true) {
  header('Location: file.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $host = $_POST['host'] ?? '';
  $port = (int) ($_POST['port'] ?? 21);
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($port === 21) {
    // ================== LOGIN FTP ==================
    $conn = @ftp_connect($host, $port);
    if ($conn && @ftp_login($conn, $username, $password)) {
      $_SESSION['login'] = true;
      $_SESSION['mode'] = 'ftp';
      $_SESSION['host'] = $host;
      $_SESSION['port'] = $port;
      $_SESSION['username'] = $username;
      $_SESSION['password'] = $password;

      ftp_close($conn);
      header('Location: file.php');
      exit;
    } else {
      $error = "Login FTP gagal. Cek kembali host, username, dan password.";
    }
  } elseif ($port === 22) {
    // ================== LOGIN SFTP (phpseclib) ==================
    $sftp = new SFTP($host, $port);
    if ($sftp->login($username, $password)) {
      $_SESSION['login'] = true;
      $_SESSION['mode'] = 'sftp';
      $_SESSION['host'] = $host;
      $_SESSION['port'] = $port;
      $_SESSION['username'] = $username;
      $_SESSION['password'] = $password;
      header('Location: file.php');
      exit;
    } else {
      $error = "Login SFTP gagal. Cek kembali host, username, dan password.";
    }
  } else {
    $error = "Port tidak didukung. Gunakan 21 (FTP) atau 22 (SFTP).";
  }
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
  <title>Login FTP / SFTP</title>
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg">
          <div class="card-body">
            <h1 class="text-center mb-4">Login FTP / SFTP</h1>
            <?php if (!empty($error)) : ?>
              <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
              </div>
            <?php endif; ?>
            <form action="" method="post">
              <div class="form-group">
                <label for="host">Host</label>
                <input
                  type="text"
                  class="form-control"
                  id="host"
                  name="host"
                  autocomplete="on"
                  required />
              </div>
              <div class=" form-group">
                <label for="port">Port</label>
                <select class="form-control" id="port" name="port">
                  <option value="21">21 (FTP)</option>
                  <option value="22">22 (SFTP)</option>
                </select>
              </div>
              <div class="form-group">
                <label for="username">Username</label>
                <input
                  type="text"
                  class="form-control"
                  id="username"
                  name="username"
                  autocomplete="on"
                  required />
              </div>
              <div class=" form-group">
                <label for="password">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  name="password"
                  required />
              </div>
              <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS & Dependencies -->
  <script
    src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    crossorigin="anonymous"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
    crossorigin="anonymous"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
    crossorigin="anonymous"></script>
</body>

</html>