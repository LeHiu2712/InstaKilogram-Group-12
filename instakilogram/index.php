<link rel="stylesheet" href="index.css">
<?php
session_start();
include "./menu.php";
include "./formpostcontent.html";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']){
  
if (isset($_POST['content']) && isset($_FILES['img'])){
  $content = $_POST['content'];
  $file = $_FILES['img'];
  $filename= $file['name'];
  $filename= explode('.', $filename);
  $ext = end($filename);
  $new_file= uniqid().'.'.$ext;
   
  $errors=[];
  $allow_size=100;
  //File check
  $allow_ext=['png', 'jpg', 'jpeg', 'gif', 'jfif'];
  if (in_array($ext, $allow_ext)){
    $size= $file['size']/1024/1024; //convert to MB
    if ($size <= $allow_size){
      $target= 'images/'.$new_file;
      $upload= move_uploaded_file($file['tmp_name'], $target);
      if (!$upload){
        $errors[]='upload_err';
      }else {
        $post = [
          'content' => $_POST['content'],
          'img' => $target,
          'modifier' => $_POST['modifier'],
          'user' => $_SESSION['current_account']
        ];
        $fp = fopen('post.db', 'a');
        fputcsv($fp, $post); 
        fclose($fp);
      }
    }else {
      $errors[]= 'size_err';
    }
  }else {
    $errors[]= 'ext_err';
  }
  
  if (!empty($errors)){
    $mess='';
    if (in_array('ext_err', $errors)){
      $mess='Invalid file format';
    }elseif (in_array('size_err', $errors)){
      $mess='File is too big >100MB';
    }else {
      $mess= 'Cannot upload at the moment, please try again later';
    }
  }else {
    echo 'Uploaded';
  }
}else {
  echo "Please provide more information";
}
}else {
  echo "Please <a href='./login.php'>LOGIN</a>";
}

//list post
$fp = fopen('post.db', 'r');
echo '<div class="body">';
while (($line = fgets($fp)) !== false) {
    $temp = explode(",", $line);
    if (isset($_SESSION['loggedin'])){
      if ("admin@gmail.com"==$_SESSION['current_account']){
        echo '
          <div class="item">
            <img src="'.$temp[1].'" alt="" />
            <h2>'.$temp[0].'</h2>
            <p>'.$temp[2].'</p>
            <p>'.$temp[3].'</p>
          </div>
        ';
      }else {
        if ($temp[2]!='private'){
          echo '
            <div class="item">
              <img src="'.$temp[1].'" alt="" />
              <h2>'.$temp[0].'</h2>
              <p>'.$temp[2].'</p>
              <p>'.$temp[3].'</p>
            </div>
          ';
        } 
        else
        if (trim($temp[3])==trim($_SESSION['current_account'])){
          echo '
          <div class="item">
            <img src="'.$temp[1].'" alt="" />
            <h2>'.$temp[0].'</h2>
            <p>'.$temp[2].'</p>
            <p>'.$temp[3].'</p>
          </div>
          ';
        }
      }
    }else {
      if ($temp[2]=='public'){
        echo '
          <div class="item">
            <img src="'.$temp[1].'" alt="" />
            <h2>'.$temp[0].'</h2>
            <p>'.$temp[2].'</p>
            <p>'.$temp[3].'</p>
          </div>
        ';
      }
    }
    
    
}
echo '</div>';
fclose($fp);


include './popup.html';
include './footer.html';
?>
