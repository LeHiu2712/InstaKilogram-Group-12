<?php
 session_start();
  include './menu.php';

 

  if (isset($_GET['account'])){
    $_SESSION['acc_selected']= $_GET['account'];
    header('location: detail_account.php');
  }




  if (!$_SESSION['loggedin']) {
    header('location: login.php');
  }
  else 
  if ($_SESSION['current_account']=='admin@gmail.com'){
    $fp = fopen('account.db', 'r');
    echo '<div class="acc_container">';
    while (($line = fgets($fp)) !== false) {
      $temp = explode(",", $line);
      echo '<a href="my_account.php?account='.$temp[1].'" class="account">
      <img src="'.$temp[3].'" alt="">
      <h1 class="fullname">Fullname: '.$temp[2].'</h1>
      <h2 class="email">Email: '.$temp[1].'</h2>
    </a>';
    }
    echo '</div>';
    fclose($fp);
  }else {
    $fp = fopen('account.db', 'r');
    echo '<div class="acc_container">';
    while (($line = fgets($fp)) !== false) {
      $temp = explode(",", $line);
      if ($temp[1]==$_SESSION['current_account']){
        echo '<a href="my_account.php?account='.$temp[1].'" class="account">
        <img src="'.$temp[3].'" alt="">
        <h1 class="fullname">Fullname: '.$temp[2].'</h1>
        <h2 class="email">Email: '.$temp[1].'</h2>
        </a>';
        break;
      }
    }
    echo '</div>';
    fclose($fp);
  }
  echo '<br><br><br><br>';
  echo '<a class="logout" href="./login.php">Logout</a>';
  echo '<br><br><br><br><br><br><br>';
  include './popup.html';
  include './footer.html';

?>




<style>
  .acc_container{
    display: flex;
    flex-wrap: wrap;
  }
  .account{
    width: 300px;
    margin: 20px;
    display: flex;
    flex-direction: column;
    text-align: center;
    text-decoration: none;
  }
  .account img{
    border-radius: 16px;
    width: 80%;
    height: 200px;
  }
  .logout{
    text-decoration: none;
    text-indent: 30px;
    font-size: 24px;
    border: solid 1px black;
    border-radius: 16px;
    padding: 10px;
  }
</style>

