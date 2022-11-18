<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['dealer_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: signin.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Address - MAGICA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" href="../asset/img/icon-web.ico">
    <link rel="stylesheet" href="../asset/css/web_tablet_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body style="font-family: 'Kanit', sans-serif ; background-color: #F4F4F4;">
    <!-- Header Zone-->
    <header class="container-fluid" id="navbar">
        <nav class="navbar-container container-fluid">
            <a href="../dealer/home.php" class="home-link">
                <img class="img-logo" src="/magica/asset/img/LogoMGC.png" alt="MGC">

            </a>
            <div class="container s-search">
                <div class="input-group s-search">
                    <div class="form-outline">
                        <input type="search" id="form1" class="form-control search-bar" placeholder="ค้นหา" style="height: 45px; width: 290px; margin-left:30px; margin-top: 5px; border-radius: 5px 0px 0px 5px;box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.05); " />
                    </div>
                    <button type="button" class="btn " style="height: 45px; width: 55px; margin-top: 5px; background-color: #014EB8; box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.05);">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <button type="button" id="navbar-toggle" aria-controls="navbar-menu" aria-label="Toggle menu" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>


            <div id="navbar-menu" aria-labelledby="navbar-toggle">

                <ul class="navbar-links">
                    <div class="line-bottom">
                        <li class="navbar-item" style="padding-bottom: 5px;"><a class="navbar-link" href="../dealer/dealer_product.php">สินค้า</a>
                        </li>
                        <div class="line-center"></div>
                        <li class="navbar-item" style="padding-bottom: 5px;"><a class="navbar-link" href="#">โปรโมชั่น</a></li>
                        <div class="line-center"></div>
                        <li class="navbar-item" style="padding-bottom: 5px;"><a class="navbar-link" href="about.php">เกี่ยวกับเรา</a></li>
                        <a class="dropdown-item " href="../deale/dealer_cart.php"><button class="btn-register">รถเข็นสินค้า</button></a>
                        <a class="dropdown-item " href="../dealer/dealer_profile.php"><button class="btn-register">ข้อมูลของฉัน</button></a>
                        <a class="dropdown-item" href="../dealer/dealer_profile_bank.php"><button class="btn-register">บัญชีธนาคาร</button></a>
                        <a class="dropdown-item" href="../dealer/dealer_profile_password.php"><button class="btn-register">เปลี่ยนรหัสผ่าน</button></a>
                        <a class="dropdown-item" href="../dealer/dealer_profile_address.php"><button class="btn-register">ที่อยู่</button></a>
                        <a class="dropdown-item" href="../dealer/dealer_my_purchase.php"><button class="btn-register">การซื้อของฉัน</button></a>
                        <a class="dropdown-item" href="../logout.php"><button class="btn-register"><i class="bi bi-person-plus-fill"></i>ออกจากระบบ</button></a>
                    </div>
                    <div class="btnn-profile">
                        <div class="cart-icon"> <a href="../dealer/dealer_cart.php"><i class="bi bi-cart3"></i></a></div>
                        <div class="line-center-profile"></div>
                        <div class="profile-icon">
                            <div class="dropdown"><button type="button" id="icon-profile" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle" id="icon-login-profile"></i></button>
                                <ul class="dropdown-menu dropdown-menu-profile">
                                    <li>
                                        <div id="bg-name-profile">
                                            <?php
                                            if (isset($_SESSION['dealer_login'])) {
                                                $user_id = $_SESSION['dealer_login'];
                                                $stmt = $conn->query("SELECT * FROM dealer WHERE id = $user_id");
                                                $stmt->execute();
                                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                            }
                                            ?>
                                            <?php echo $row['firstname'] ?>
                                        </div>
                                    </li>
                                    <li><a class="dropdown-item " href="../dealer/dealer_profile.php"><i class="bi bi-person-circle" id="icon-login-profile-drop"></i>บัญชีของฉัน</a></li>
                                    <li><a class="dropdown-item" href="../dealer/dealer_my_purchase.php"><i class="bi bi-journal-text"></i>การซื้อของฉัน</a></li>
                                    <li><a class="dropdown-item" href="../logout.php"><i class="bi bi-box-arrow-right"></i>ออกจากระบบ</a></li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li><img class="img-logo-profile" src="/magica/asset/img/LogoMGC.png" alt="MGC">
                                </ul>
                            </div>
                        </div>
                    </div>
                </ul>

            </div>

        </nav>
    </header>
    <!-- Content Zone-->

    <div class="container-fluid">
        <div class="row">
            <div class="col-3 hid" style="padding-left: 0px; padding-right: 0px;">
                <?php include('../dealer/dealer_sidebar.php') ?>
            </div>
            <div class="col-9 sh" style="padding-left: 0px; padding-right: 0px;"> <?php include('../dealer/dealer_profile_address_form.php') ?></div>
        </div>
    </div>
    <div class="footer" style="margin-top: 20px;">
    <?php include('sfooter.php') ?>
    </div>
</body>
<script src="/magica/action.js"></script>
</html>
 