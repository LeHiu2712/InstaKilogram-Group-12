<?php
session_start();
include './menu.php';

$curent_email="";
$curent_fullname="";
$curent_password="";
$curent_img="";
$list_account= array();
if (isset($_SESSION['acc_selected'])){
  $acc_selected= $_SESSION['acc_selected'];
  $fp = fopen('account.db', 'r');
  $counter=0;
  while (($line = fgets($fp)) !== false) {
      $temp = explode(",", $line);
      $account = [
        'password' => $temp[0],
        'email' => $temp[1],
        'fullname' => $temp[2],
        'avt' => $temp[3]
      ];
      $list_account[$counter]=$account;
      $counter++;
      if (trim($temp[1]) == $acc_selected){
          $curent_email= $acc_selected;
          $curent_fullname= $temp[2];
          $curent_password= $temp[0];
          $curent_img= $temp[3];
      }
  }
  fclose($fp);
}

if (isset($_GET['delete'])){
  $acc_delete= $_SESSION['acc_selected'];
  $new_list_account= array();
  $fp = fopen('account.db', 'r');
  $counter2=0;
  while (($line = fgets($fp)) !== false) {
      $temp = explode(",", $line);
      if ($acc_delete!=$temp[1]){
        $account = [
          'password' => $temp[0],
          'email' => $temp[1],
          'fullname' => $temp[2],
          'avt' => $temp[3]
        ];
        $new_list_account[$counter2]=$account;
        $counter2++;
      }
  }
  fclose($fp);

  $ft = fopen('account.db', 'w');
  fclose($ft);

  $fp = fopen('account.db', 'a');
  foreach($new_list_account as $key => $account_value){
    fputcsv($fp, [
      'password' => $account_value['password'],
      'email' => $account_value['email'],
      'fullname' => str_replace(" ", "-", trim($account_value['fullname'])),
      'avt' => trim($account_value['avt'])
    ]); 
    
  }
  fclose($fp);
  header('location: my_account.php');
}



if (isset($_POST) && isset($_FILES['img'])){
  $username= $_POST['username'];
  $password= $_POST['password'];
  $password2= $_POST['password2'];
  $fullname= $_POST['fullname'];
  $file= $_FILES['img'];

  $error="";

  if ($password!="" && $password!=$password2){
    $error=$error."Password not confirm-";
    if (strlen($password)<8){
      $error=$error."Password must reach 8 character-";
    }
  }

  if (strlen($error)>5){
    echo '<h3 style="color: red;">ERROR: '.$error.'</h3>';
  }else {
    if ($password!=null){
      $curent_password=$password;
    }

    if ($fullname!=null){
      $curent_fullname= $fullname;
    }

    if (strlen($_FILES['img']['name'])>2){
      $curent_img= uploadFile($_FILES['img']);
    }

    $new_account = [
      'password' => hash('md5', $curent_password),
      'email' => $curent_email,
      'fullname' => str_replace(" ", "-", trim($curent_fullname)),
      'avt' =>  trim($curent_img)
    ];
    
    $ft = fopen('account.db', 'w');
    fclose($ft);

    $fp = fopen('account.db', 'a');
    foreach($list_account as $key => $account_value){
      if ($account_value['email']==$curent_email){
        fputcsv($fp, $new_account); 
      }else {
        fputcsv($fp, [
          'password' => $account_value['password'],
          'email' => $account_value['email'],
          'fullname' => str_replace(" ", "-", trim($account_value['fullname'])),
          'avt' => trim($account_value['avt'])
        ]); 
      }
    }
    fclose($fp);

    echo "<h1 style='color: blue;'>Update success. You can <a href='./my_account.php'>Back</a></h1>";
  }

}
function uploadFile($F){
  if (isset($F)){
    $file = $F;
    $filename= $file['name'];
    $filename= explode('.', $filename);
    $ext = end($filename);
    $new_file= uniqid().'.'.$ext;
  
    $allow_size=100;
    //kiểm tra định dạng
    $allow_ext=['png', 'jpg', 'jpeg', 'gif', 'jfif'];
  
  
    if (in_array($ext, $allow_ext)){
      $size= $file['size']/1024/1024; //convert to MB
      if ($size <= $allow_size){
        $target= 'images/'.$new_file;
        $upload= move_uploaded_file($file['tmp_name'], $target);
        if ($upload){
          return $target;
        }
      }
    }
  }  
  return "null";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<style>
  form{
    display: flex;
    flex-direction: column;
    width: 600px;
    margin: 0 auto;
  }
  label{
    font-size: xx-large;
    font-weight: 900;
    margin-top: 50px;
  }
  input{
    width: 500px;
    height: 50px;
    border-radius: 16px;
    font-size: x-large;
    padding-left: 10px;
    font-weight: 900;
    color: black;
    margin-top: 5px;
  }
  .button {
    font-weight: 900;
    padding: 5px;
    box-sizing: border-box;
    width: 200px;
    height: 50px;
    border-radius: 16px;
    margin-top: 24px;
    cursor: pointer;
    margin: 20px;
  }
  .btnRegister{
    width: 400px;
    display: flex;
  }
</style>
<body>
  <form action="detail_account.php" method="POST" enctype="multipart/form-data">
    <label for="username">email: </label>
    <input type="text" name="username" id="username" placeholder="Reset Email..." value=<?php echo $curent_email;?>>
    
    <label for="password">Password: </label>
    <input type="password" name="password" id="password" placeholder="Reset password...">

    <label for="password2">Password: </label>
    <input type="text" name="password2" id="password2" placeholder="Confirm password...">
    
    <label for="fullname">Fullname: </label>
    <input type="text" name="fullname" id="fullname" placeholder="Reset Fullname..." value=<?php echo $curent_fullname;?>>

    <label for="img">Avatar: </label>
    <input type="file" name="img" id="img">

    <div class="btnRegister">
      <button class="button" type="submit">Update</button>
      <button class="button" type="reset">Reset</button>
      <button class="button">
        <a href="./detail_account.php?delete=true">Delete account</a>
      </button>
      <button class="button">
        <a href="./my_account.php">Back to my_account</a>
      </button>
    </div>
    
  </form>

 
</body>
</html>


<?php
include './popup.html';
include './footer.html';
?>