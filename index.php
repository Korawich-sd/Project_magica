<?php
session_start();
require_once 'config/db.php';
?>

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

<!-- //// Header //// -->
<?php include('../magica/header.php'); ?>
<!-- //// Header //// -->


<!-- //// Content //// -->
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
                        <div class="carousel-item active" data-bs-interval="3000">
                            <img src="asset/img/carousel-dealer5.gif" class="d-block m-auto carousel-img-user">

                        </div>
                        <div class="carousel-item" data-bs-interval="4000">
                            <img src="asset/img/carousel-dealer1.gif" class="d-block  m-auto carousel-img-user">

                        </div>
                        <div class="carousel-item" data-bs-interval="4000">
                            <img src="asset/img/carousel-user.gif" class="d-block  m-auto carousel-img-user">

                        </div>
                        <div class="carousel-item" data-bs-interval="4000">
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
<!-- Zone catagorie medicine-->
<div class=" catagorie">
        <div class="item-catagorie">
            <div class="text-catagorie"><b class="cat-txt-title-dealer">หมวดหมู่สินค้า</b></div>
            <div class="dealer-cat-box">
                <div class="bg-item">
                    <div class="item">
                        <a href="product.php?t_id=2&name=อาหารเสริม" style="text-decoration: none;">
                            <!-- <div class="item-bord"><img class="icon-med-hidden" src="/magica/asset/img/med-icon1.png" alt="med1"></div> -->
                            <img class="cir-con" src="asset/img/cata-1.png" alt="">
                            <p class="text-item-1">อาหารเสริม </p>
                        </a>

                        <!-- <div class="box1">
                    <img src="/magica/asset/img/med-icon1.png" alt="med1" style="width: 36px; height: 36px; ">
                </div> -->
                    </div>
                </div>
                <!-- <div class="bg-item">
                    <div class="item"><a href="signin.php" style="text-decoration: none;">
                          
                            <img class="cir-con" src="asset/img/cata-2.png" alt="">
                            <p class="text-item-1">ยาจ่ายผ่านคลินิก</p>
                        </a>
                   
                    </div>
                </div>
                <div class="bg-item">
                    <div class="item"><a href="signin.php" style="text-decoration: none;">
                          
                            <img class="cir-con" src="asset/img/cata-3.png" alt="">
                            <p class="text-item-1">ยาที่ขึ้นทะเบียนแล้ว</p>
                        </a>
               
                    </div>
                </div>
                <div class="bg-item">
                    <div class="item"><a href="signin.php" style="text-decoration: none;">
         
                            <img class="cir-con" src="asset/img/cata-4.png" alt="">
                            <p class="text-item-1">ยาแผนโบราณ</p>
                        </a>
                   
                    </div>
                </div> -->
                <div class="bg-item">
                    <div class="item"><a href="product.php?t_id=1&name=ยาสามัญประจำบ้าน" style="text-decoration: none;">
                   
                            <img class="cir-con" src="asset/img/cata-5.png" alt="">
                            <p class="text-item-1">ยาสามัญประจำบ้าน</p>
                        </a>
                    
                    </div>
                </div>

            </div>
        </div>
        <!-- <div class="line-bottom-med-cat"></div> -->
    </div>

<!-- Zone recommence medicine-->
<div class="recommence-product">
        <div class="text-rec">
            <p class="text-rec">แนะนำสำหรับคุณ</p>
        </div>
        <div class=" box-rec-pd">

            <?php
            $t_id = 3;
            $stmt = $conn->prepare("SELECT * FROM product WHERE t_id < :t_id");
            $stmt->bindParam(":t_id", $t_id);
            $stmt->execute();
            $product = $stmt->fetchAll();

            $i = 0;

            if (!$product) {
                echo "ไม่มีข้อมูล";
            } else {
                foreach ($product as $product) {
            ?>
                    <div class="rec-pd">
                        <a class="a-none" href="signin.php">
                            <div class="card" id="card">
                                <div class="card-top">
                                    <img src="asset/upload/product/<?= $product['p_img1'] ?>" class="card-img-top" alt="...">
                                </div>
                                <div class="card-body">
                                    <p class="card-title"><?= $product['p_name'] ?></p>
                                    <p class="card-text">฿<?= number_format($product['p_price'], 2)  ?></p>
                                    <a href="product_detail.php?p_id=<?= $product['p_id']; ?>" class="btn " id="btn-dp" style="margin: 0 auto;">รายละเอียด</a>
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
                <a href="product.php"><button type="button" class="btn" id="see-more">ดูเพิ่มเติม</button></a>
            </div>


        </div>
    </div>
<!-- Zone recommence medicine-->


<!-- //// Content //// -->

<!-- ///// Footer   -->
<?php include('../magica/footer.php'); ?>
<!-- ///// Footer   -->

<script src="/magica/action.js"></script>