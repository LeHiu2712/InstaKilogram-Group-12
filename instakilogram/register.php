<?php
include './menu.php';

if (isset($_POST) && isset($_FILES['img'])){
  $username= $_POST['username'];
  $password= $_POST['password'];
  $password2= $_POST['password2'];
  $fullname= $_POST['fullname'];
  $file= $_FILES['img'];

  $error="";
  if (!strpos($username, "@gmail.com")){
    $error=$error."Invalid gmail-";
  }

  if ($password!=$password2){
    $error=$error."Password not confirm-";
  }

  if (strlen($password)<8){
    $error=$error."Password must reach 8 character-";
  }

  if (strlen($fullname)<2){
    $error=$error."Fullname not long enough-";
  }

  if (strlen($file['name'])<=1){
    $error=$error."Invalid file";
  }

  $fp = fopen('account.db', 'r');
  while (($line = fgets($fp)) !== false) {
      $temp = explode(",", $line);
      if ($temp[1]==$username){
          $error=$error."Email already exists";
      }
  }
  fclose($fp);


  if (strlen($error)>5){
    echo '<h3 style="color: red;">ERROR: '.$error.'</h3>';
  }else {
    $account = [
      'password' => hash('md5', $_POST['password']),
      'email' => $_POST['username'],
      'fullname' => str_replace(" ", "-", trim($_POST['fullname'])),
      'avt' => uploadFile($_FILES['img'])
    ];
    
    $fp = fopen('account.db', 'a');
    fputcsv($fp, $account); 
    fclose($fp);

    echo "<h1 style='color: blue;'>Resgister success. You can <a href='./login.php'>Login here</a></h1>";
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
  @media (max-width: 767px) {
    form{
      display: flex;
      flex-direction: column;
      width: 400px;
      margin: 0 auto;
    }
    label{
      font-size: large;
      font-weight: 900;
      margin-top: 20px;
    }
    input{
      width: 300px;
      height: 50px;
      border-radius: 16px;
      font-size: small;
      padding-left: 10px;
      font-weight: 900;
      color: black;
      margin-top: 5px;
    }
    select{
      width: 300px;
    }
    button {
      width: 130px;
      height: 50px;
      border-radius: 16px;
      margin-top: 24px;
      cursor: pointer;
    }
  }
</style>
<body>
  <form action="register.php" method="POST" enctype="multipart/form-data">
    <label for="username">email: </label>
    <input type="text" name="username" id="username" required placeholder="Email...">
    
    <label for="password">Password: </label>
    <input type="password" name="password" id="password" required placeholder="password...">

    <label for="password2">Password: </label>
    <input type="text" name="password2" id="password2" required placeholder="Confirm password...">
    
    <label for="fullname">Fullname: </label>
    <input type="text" name="fullname" id="fullname" required placeholder="Fullname...">

    <label for="img">Avatar: </label>
    <input type="file" name="img" id="img" required>

    <div class="btnRegister">
      <button class="button" type="submit">Register</button>
      <button class="button" type="reset">Reset</button>
      <button class="button">
        <a href="./login.php">Back to Login</a>
      </button>
    </div>
    
  </form>

 
</body>
</html>


<?php
include './popup.html';
include './footer.html';
?>