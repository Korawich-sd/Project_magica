<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['dealer_general_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: ../signin.php");
}

if (isset($_GET['act'])) {
    $name = $_GET['search'];
    $s =  urlencode($name);
    header("refresh:0.0000000001; url=dealer_gen_product.php?search=$s");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAGICA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" href="../asset/img/icon-web.ico">
    <link rel="stylesheet" href="../asset/css/web_tablet_style.css?v=<?php echo time(); ?>">
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

<body style="font-family: 'Kanit', sans-serif ;">

    <!-- Header Zone-->
    <header class="container-fluid" id="navbar">
        <nav class="navbar-container container-fluid">
            <a href="../dealer_general/home.php" class="home-link">
                <img class="img-logo" src="/magica/asset/img/LogoMGC.png" alt="MGC">

            </a>
            <form class="s-search" method="get">
                <div class="container s-search">
                    <div class="input-group s-search">
                        <div class="form-outline">
                            <input type="search" id="form1" name="search" class="form-control search-bar" placeholder="ค้นหา" style="height: 45px; width: 290px; margin-left:30px; margin-top: 5px; border-radius: 5px 0px 0px 5px;box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.05); " />
                        </div>
                        <button type="submit" class="btn"  name="act" value="q" style="height: 45px; width: 55px; margin-top: 5px; background-color: #014EB8; box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.05);">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <button type="button" id="navbar-toggle" aria-controls="navbar-menu" aria-label="Toggle menu" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>


            <div id="navbar-menu" aria-labelledby="navbar-toggle">

                <ul class="navbar-links">
                    <div class="line-bottom">
                        <li class="navbar-item" style="padding-bottom: 5px;"><a class="navbar-link" href="../dealer_general/dealer_gen_product.php">สินค้า</a>
                        </li>
                        <div class="line-center"></div>
                        <li class="navbar-item" style="padding-bottom: 5px;"><a class="navbar-link" href="#">โปรโมชั่น</a></li>
                        <div class="line-center"></div>
                        <li class="navbar-item" style="padding-bottom: 5px;"><a class="navbar-link" href="about.php">เกี่ยวกับเรา</a></li>
                        <a class="dropdown-item " href="../dealer_general/dealer_gen_cart.php"><button class="btn-register">รถเข็นสินค้า</button></a>
                        <a class="dropdown-item " href="../dealer_general/dealer_gen_profile.php"><button class="btn-register">ข้อมูลของฉัน</button></a>
                        <a class="dropdown-item" href="../dealer_general/dealer_gen_profile_bank.php"><button class="btn-register">บัญชีธนาคาร</button></a>
                        <a class="dropdown-item" href="../dealer_general/dealer_gen_profile_password.php"><button class="btn-register">เปลี่ยนรหัสผ่าน</button></a>
                        <a class="dropdown-item" href="../dealer_general/dealer_gen_profile_address.php"><button class="btn-register">ที่อยู่</button></a>
                        <a class="dropdown-item" href="../dealer_general/dealer_gen_my_purchase.php"><button class="btn-register">การซื้อของฉัน</button></a>
                        <a class="dropdown-item" href="../logout.php"><button class="btn-register"><i class="bi bi-person-plus-fill"></i>ออกจากระบบ</button></a>
                    </div>
                    <div class="btnn-profile">
                        <div class="cart-icon"> <a href="../dealer_general/dealer_gen_cart.php"><i class="bi bi-cart3"></i></a></div>
                        <div class="line-center-profile"></div>
                        <div class="profile-icon">
                            <div class="dropdown"><button type="button" id="icon-profile" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle" id="icon-login-profile"></i></button>
                                <ul class="dropdown-menu dropdown-menu-profile">
                                    <li>
                                        <div id="bg-name-profile">
                                            <?php
                                            if (isset($_SESSION['dealer_general_login'])) {
                                                $user_id = $_SESSION['dealer_general_login'];
                                                $stmt = $conn->query("SELECT * FROM dealer_general WHERE id = $user_id");
                                                $stmt->execute();
                                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                            }
                                            ?>
                                            <?php echo $row['firstname'] ?>
                                        </div>
                                    </li>
                                    <li><a class="dropdown-item " href="../dealer_general/dealer_gen_profile.php"><i class="bi bi-person-circle" id="icon-login-profile-drop"></i>บัญชีของฉัน</a></li>
                                    <li><a class="dropdown-item" href="../dealer_general/dealer_gen_my_purchase.php"><i class="bi bi-journal-text"></i>การซื้อของฉัน</a></li>
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
    <div class="container-fluid">
        <div class="row" style="margin-top: 110px;">
            <div class="col-lg-12 m-auto" style="height: auto;">
                <!-- การสร้าง Carousel -->
                <div class="carousel slide" id="slider1" data-bs-ride="carousel">
                    <ol class="carousel-indicators">
                        <button class="active" data-bs-target="#slider1" data-bs-slide-to="0"></button>
                        <button data-bs-target="#slider1" data-bs-slide-to="1"></button>
                        <button data-bs-target="#slider1" data-bs-slide-to="2"></button>
                        <button data-bs-target="#slider1" data-bs-slide-to="3"></button>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="../asset/img/carousel-dealer1.gif" class="d-block m-auto carousel-img-user">

                        </div>
                        <div class="carousel-item">
                            <img src="../asset/img/carousel-dealer2.gif" class="d-block  m-auto carousel-img-user">

                        </div>
                        <div class="carousel-item">
                            <img src="../asset/img/carousel-dealer3.gif" class="d-block  m-auto carousel-img-user">

                        </div>
                        <div class="carousel-item">
                            <img src="../asset/img/carousel-dealer5.gif" class="d-block m-auto  carousel-img-user">

                        </div>
                    </div>
                    <!-- ควบคุมการ Slide ผ่านปุ่ม -->
                    <!-- <button class="carousel-control-prev" data-bs-slide="prev" data-bs-target="#slider1">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" data-bs-slide="next" data-bs-target="#slider1">
                            <span class="carousel-control-next-icon"></span>
                        </button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Zone catagorie medicine-->
    <div class="user-catagorie">
        <div class="item-catagorie" >
            <div class="text-catagorie"><b class="cat-txt-title-dealer">หมวดหมู่สินค้า</b></div>
            <div class="user-cat-box">
                <div class="bg-item">
                    <div class="item">
                        <a href="../dealer_general/dealer_gen_product.php?t_id=2&name=อาหารเสริม" style="text-decoration: none;">
                            <!-- <div class="item-bord"><img class="icon-med-hidden" src="/magica/asset/img/med-icon1.png" alt="med1"></div> -->
                            <img class="cir-con" src="../asset/img/cata-1.png" alt="">
                            <p class="text-item-1">อาหารเสริม </p>

                        </a>
                        <!-- 
                <div class="box1">
                    <img src="/magica/asset/img/med-icon1.png" alt="med1" style="width: 36px; height: 36px; ">
                </div> -->
                    </div>
                </div>
                <div class="bg-item">
                    <div class="item"><a href="../dealer_general/dealer_gen_product.php?t_id=1&name=ยาสามัญประจำบ้าน" style="text-decoration: none;">
                            <!-- <div class="item-bord"><img class="icon-med-hidden" src="/magica/asset/img/med-icon2.png" alt="med2"></div> -->
                            <img class="cir-con" src="../asset/img/cata-5.png" alt="">
                            <p class="text-item-1">ยาสามัญประจำบ้าน</p>
                        </a>
                        <!-- <div class="box1">
                    <img src="/magica/asset/img/med-icon2.png" alt="med2" style="width: 36px; height: 36px; ">
                </div> -->
                    </div>
                </div>

                <!-- 
            <div class="item"><a href="#" style="text-decoration: none;">
                    <div class="item-bord"><img class="icon-med-hidden" src="/magica/asset/img/med-icon3.png" alt="med3"></div>
                    <p class="text-item">ยาที่จ่ายผ่านคลินิก</p>
                </a>
                <div class="box1">
                    <img src="/magica/asset/img/med-icon3.png" alt="med3" style="width: 36px; height: 36px; ">
                </div>
            </div>

            <div class="item"><a href="#" style="text-decoration: none;">
                    <div class="item-bord"><img class="icon-med-hidden" src="/magica/asset/img/med-icon4.png" alt="med4"></div>
                    <p class="text-item">ยาที่ขึ้นทะเบียนแล้ว</p>
                </a>
                <div class="box1">
                    <img src="/magica/asset/img/med-icon4.png" alt="med4" style="width: 36px; height: 36px; ">
                </div>
            </div> -->
            </div>
        </div>
        <!-- <div class="line-bottom-med-cat"></div> -->
    </div>
    <!-- Zone catagorie medicine-->

    <div class="recommence-product">
        <div class="text-rec"><b>แนะนำสำหรับคุณ</b></div>
        <div class=" box-rec-pd">

            <?php
            $stmt = $conn->prepare("SELECT * FROM product_dealer ");
            $stmt->execute();
            $product = $stmt->fetchAll();

            $i = 0;

            if (!$product) {
                echo "ไม่มีข้อมูล";
            } else {
                foreach ($product as $product) {
            ?>
                    <div class="rec-pd">
                        <a class="a-none" href="../dealer_general/dealer_gen_product_detail.php?p_id=<?= $product['p_id']; ?>">
                            <div class="card" id="card">
                                <div class="card-top">
                                    <img src="../asset/upload/product/<?= $product['p_img1'] ?>" class="card-img-top" alt="...">
                                </div>
                                <div class="card-body" >
                                    <p class="card-title"><?= $product['p_name'] ?></p>
                                    <p class="card-text">฿<?= number_format($product['p_price1'], 2)  ?></p>
                                    <a href="../dealer_general/dealer_gen_product_detail.php?p_id=<?= $product['p_id']; ?>" class="btn " id="btn-dp" style="margin: 0 auto;">รายละเอียด</a>
                                </div>
                            </div>
                        </a>
                    </div>

            <?php
                    $i++;
                    if ($i == 5) {
                        break;
                    }
                }
            }

            ?>

            <!-- <div class="line-rec"></div> -->
            <div class="btn-see-more">
                <a href="../dealer_general/dealer_gen_product.php"><button type="button" class="btn" id="see-more">ดูเพิ่มเติม</button></a>
            </div>


        </div>
    </div>
    <!-- Zone Ads -->
    <!-- <div class="ads">
        <img src="../asset/img/ads2.jpg" width="45%" alt="">
        <img src="../asset/img/ads1.jpg" width="45%" alt="">
    </div> -->
    <div class="footer" style="margin-top: 20px;">
        <?php include('footer.php') ?>
    </div>
</body>
<script src="/magica/action.js"></script>

</html>