<!-- Responsive -->
<?php
session_start();
require_once 'config/db.php';
// if (!isset($_SESSION['user_login'])) {
//     $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
//     header("location: signin.php");
// }

if (isset($_GET['act'])) {
    $name = $_GET['search'];
    $s =  urlencode($name);
    header("refresh:0.0000000001; url=product.php?search=$s");
}

if (isset($_GET['t_id']) & isset($_GET['name'])) {
    $stmt = $conn->prepare("SELECT * FROM product WHERE t_id=?");
    $stmt->execute([$_GET['t_id']]);
    //  $stmt->bindParam(":id", $_GET['t_id']); 
    $stmt->execute();
    $result = $stmt->fetchAll();
} else
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $s = '%$search%';
    $stmt = $conn->prepare("SELECT * FROM `product` WHERE `p_name` LIKE ?");
    $stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll();
} else {
    $stmt = $conn->prepare("SELECT * FROM product");
    $stmt->execute();
    $result = $stmt->fetchAll();
}
$stmtType = $conn->prepare("SELECT * FROM p_type WHERE t_id < 3");
$stmtType->execute();
$p_type = $stmtType->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product - MAGICA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" href="asset/img/icon-web.ico">
    <link rel="stylesheet" href="asset/css/web_tablet_style.css?v=<?php echo time(); ?>">
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

    <!-- //// Header //// -->
    <?php include('../magica/header.php'); ?>
    <!-- //// Header //// -->
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
                            <img src="asset/img/carousel-user.gif" class="d-block m-auto carousel-img-user">

                        </div>
                        <div class="carousel-item">
                            <img src="asset/img/carousel-user2.gif" class="d-block  m-auto carousel-img-user">

                        </div>
                        <div class="carousel-item ">
                            <img src="asset/img/carousel-user.gif" class="d-block  m-auto carousel-img-user">

                        </div>
                        <div class="carousel-item">
                            <img src="asset/img/carousel-user2.gif" class="d-block m-auto  carousel-img-user">

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

    <div class="pd-content">
        <div class="cata-med">
            <div class="bg-cat-med">
                <div class="pd-cat-text">
                    <i class="bi bi-text-indent-right"><span></i>หมวดหมู่ยา</span>
                </div>
                <?php foreach ($p_type as $p_type) { ?>
                    <a href="product.php?t_id=<?= $p_type['t_id']; ?>&name=<?= $p_type['t_name'] ?>" style="text-decoration: none; width: 90%; color: #000000;">
                        <div class="bg-data-med">
                            <span><?= $p_type['t_name'] ?></span>
                        </div>
                    </a>
                    <div class="line-bt-med"></div>
                <?php
                }
                ?>
                <div class="img-data-med">
                    <img class="img-bg-data-med" src="../asset/img/LogoMGC.png" alt="">
                </div>
            </div>
        </div>
        <div class="pd">
            <?php
            if (isset($_GET['name'])) { ?>
                <div class="title-pd">
                    <?php echo '<span style="color:#000000">' . $_GET['name'] . '</span>'; ?>
                </div>
                <div class="line-bt-title-pd"></div>
            <?php } else { ?>
                <div class="title-pd">
                    <span style="color:#000000">สินค้าทั้งหมด</span>
                </div>
                <div class="line-bt-title-pd"></div>
            <?php  }
            foreach ($result as $result) { ?>
                <div class="content-pd">
                    <div class="card" id="card">
                        <div class="card-top">
                            <img src="asset/upload/product/<?= $result['p_img1'] ?>" class="card-img-top" alt="...">
                        </div>
                        <div class="card-body">
                            <p class="card-title"><?= $result['p_name'] ?></p>
                            <p class="card-text">฿<?= number_format($result['p_price'], 2) ?></p>
                            <a href="product_detail.php?p_id=<?= $result['p_id']; ?>" class="btn " id="btn-dp" style="margin: 0 auto;">รายละเอียด</a>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>


    </div>
    <!-- Zone Ads -->
    <div class="ads">
        <img src="asset/img/ads2.jpg" width="45%" alt="">
        <img src="asset/img/ads1.jpg" width="45%" alt="">
    </div>
    <div class="footer" style="margin-top: 20px;">
        <!-- ///// Footer   -->
        <?php include('../magica/footer.php'); ?>
        <!-- ///// Footer   -->
    </div>
</body>
<script src="/magica/action.js"></script>

</html>