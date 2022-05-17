<?php 
session_start();
$_SESSION['loggedin'] = false;
$_SESSION['current_account']= false;
include './menu.php';


if (!empty($_POST)) {
  $account = [
    'password' => hash('md5', $_POST['password']),
    'email' => $_POST['username'],
  ];
  $fp = fopen('account.db', 'r');
  while (($line = fgets($fp)) !== false) {
      $temp = explode(",", $line);
      if (($temp[0] == $account['password']) && (trim($temp[1]) == $account['email'])){
          $_SESSION['loggedin'] = true;
          $_SESSION['current_account']= $account['email'];
          header('location: index.php');
      }
  }
  echo "<h2 style='color: red;'>ERROR: INVALID INFORMATION.</h2>";
  fclose($fp);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <form action="login.php" method="POST" enctype="multipart/form-data">
    <label for="username">Email: </label>
    <input type="text" name="username" id="username" placeholder="Email...">
    
    <label for="password">Password: </label>
    <input type="password" name="password" id="password" placeholder="password...">
    
    <button type="submit">Login</button>
    <button>
    <a href="./register.php">Create a new account</a>
    </button>
  </form>

 
</body>
</html>

<?php
echo "<br><br><br><br><br><br><br><br><br>";
include './popup.html';
include './footer.html';

?>