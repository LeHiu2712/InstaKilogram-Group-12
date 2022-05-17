<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="menu.css">
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<div class="header">
        <div class="container">
            <ul class="nav">
                <li> <a href="./index.php">Home</a><i class="fas fa-chevron-right"></i> </li>
                <li> <a href="./my_account.php">My Account</a> <i class="fas fa-chevron-right"></i></li>
            </ul>
            <!-- <div class="support">
                <span class="sp-dec">Hỗ trợ trực tuyến</span> <span> <i class="fas fa-shopping-cart"></i> Giỏ hàng</span>
            </div> -->
            <div class="curent_account">
            <?php 
                // session_start();
                echo "<h3 class='acc'>".$_SESSION['current_account']."</h3>";
                
            ?>
        </div>
        </div>
        
        
</div>