<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!empty($_SESSION['login']) && $_SESSION['login'] === true) {
  header('Location: file.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $host = $_POST['host'] ?? '';
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $conn = @ftp_connect($host, 21);
  if ($conn && @ftp_login($conn, $username, $password)) {
    $_SESSION['login'] = true;
    $_SESSION['host'] = $_POST['host'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    ftp_close($conn);
    header('Location: file.php');
    exit;
  } else {
    $error = "Login FTP gagal. Cek kembali host, username, dan password.";
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
  <title>Login FTP</title>
</head>

<body>
  <div class="container">
    <h1 class="text-center mb-3">Login FTP</h1>
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
          aria-describedby="host" name="host" />
      </div>
      <div class="form-group">
        <label for="username">Username</label>
        <input
          type="text"
          class="form-control"
          id="username"
          aria-describedby="username" name="username" />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" />
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
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