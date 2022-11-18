<!-- Responsive -->
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
session_start();
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
//error_reporting(0);
require_once '../config/db.php';
if (!isset($_SESSION['dealer_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: ../signin.php");
}
if (isset($_GET['p_id'])) {

    $stmtPD_D = $conn->prepare("SELECT * FROM product_dealer WHERE p_id = :p_id");
    $stmtPD_D->bindParam("p_id", $_GET['p_id'], PDO::PARAM_INT);
    $stmtPD_D->execute();
    $rowPD_D = $stmtPD_D->fetch(PDO::FETCH_ASSOC);

    if ($stmtPD_D->rowCount() != 1) {
        header('location: ../dealer/dealer_product.php');
        exit();
    }
}

if (isset($_SESSION['dealer_login'])) {
    $user_id = $_SESSION['dealer_login'];
    $stmt = $conn->query("SELECT * FROM dealer WHERE id = $user_id");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $check_data = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");
    $check_data->execute();
    $rowCheck = $check_data->fetchAll();
}
$value = 1;

if (isset($_POST['add-to-card'])) {
    $p_id = $_POST['p_id'];
    $qty = $_POST['quantity'];
    $user_id = $row['id'];
    $status_cart = 1;
    //  $total_price = $_POST['sp1'];
    $check_state = true;

    $price = explode('/', $qty);
    print_r($price);



    if (!empty($qty) && !empty($p_id)) {
        if (!empty($rowCheck)) {
            foreach ($rowCheck as $rowCheck) {
                if ($rowCheck['product_id'] == $p_id) {
                    $check_state = true; //มีสินค้าแล้ว
                    echo "<script>
                    $(document).ready(function() {
                        Swal.fire({
                            position: 'top-end',
                            text: 'มีสินค้านี้ในรถเข็นแล้ว',
                            icon: 'warning',
                            timer: 12000,
                            showConfirmButton: false
                        });
                    })
                    </script>";
                    header("refresh:2; url=dealer_product_detail.php?p_id=$p_id");
                    if ($check_state == true) {
                        break;
                    }
                } else {
                    $check_state = false;
                }
            }
            if ($check_state == false) {
                $sql = $conn->prepare("INSERT INTO cart(qty,price ,product_id, user_id, status_cart)
                    VALUES(:qty,:price ,:product_id, :user_id, :status_cart)");
                $sql->bindParam(":qty", $qty);
                $sql->bindParam(":price", $price[1]);
                $sql->bindParam(":product_id", $p_id);
                $sql->bindParam(":user_id", $user_id);
                $sql->bindParam(":status_cart", $status_cart);
                $sql->execute();

                if ($sql) {
                    // echo "<script>alert('เพิ่มสินค้าในรถเข็นแล้ว')</script>";
                    echo "<script>
                    $(document).ready(function() {
                        Swal.fire({
                            position: 'top-end',
                            text: 'เพิ่มสินค้าในรถเข็นแล้ว',
                            icon: 'success',
                            timer: 12000,
                            showConfirmButton: false
                        });
                    })
                    </script>";
                    $check_state = true;
                    header("refresh:2; url=dealer_product_detail.php?p_id=$p_id");
                } else {
                    echo "<script>alert('เพิ่มสินค้าในรถเข็นไม่สำเร็จ')</script>";
                    header("refresh:0.0000000001; url=dealer_product_detail.php?p_id=$p_id");
                }
            }
        } else {
            $sql = $conn->prepare("INSERT INTO cart(qty,price,product_id, user_id, status_cart)
                        VALUES(:qty,:price,:product_id, :user_id, :status_cart)");
            $sql->bindParam(":qty", $qty);
            $sql->bindParam(":price", $price[1]);
            $sql->bindParam(":product_id", $p_id);
            $sql->bindParam(":user_id", $user_id);
            $sql->bindParam(":status_cart", $status_cart);
            $sql->execute();

            if ($sql) {
                //  echo "<script>alert('เพิ่มสินค้าในรถเข็นแล้ว')</script>";
                echo "<script>
                $(document).ready(function() {
                    Swal.fire({
                        position: 'top-end',
                        text: 'เพิ่มสินค้าในรถเข็นแล้ว',
                        icon: 'success',
                        timer: 12000,
                        showConfirmButton: false
                    });
                })
                </script>";
                $check_state = true;
                header("refresh:2; url=dealer_product_detail.php?p_id=$p_id");
            } else {
                echo "<script>alert('เพิ่มสินค้าในรถเข็นไม่สำเร็จ')</script>";
                header("refresh:0.0000000001; url=dealer_product_detail.php?p_id=$p_id");
            }
        }
    } else {
        // echo "<script>alert('มีบางอย่างผิดพลาด')</script>";
        echo "<script>
        $(document).ready(function() {
            Swal.fire({
                text: 'เลือกจำนวนก่อนเพิ่มสินค้า',
                icon: 'warning',
                timer: 12000,
                showConfirmButton: false
            });
        })
        </script>";
        // header("refresh:3; url=dealer_gen_product_detail.php?p_id=$p_id");
    }
}

if (isset($_POST['buy-now'])) {
    $p_id = $_POST['p_id'];
    $qty = $_POST['quantity'];
    $user_id = $row['id'];
    $status_cart = 1;
    //  $total_price = $_POST['sp1'];
    $check_state = true;

    $price = explode('/', $qty);
    print_r($price);



    if (!empty($qty) && !empty($p_id)) {
        if (!empty($rowCheck)) {
            foreach ($rowCheck as $rowCheck) {
                if ($rowCheck['product_id'] == $p_id) {
                    $check_state = true; //มีสินค้าแล้ว
                    // echo "<script>
                    // $(document).ready(function() {
                    //     Swal.fire({
                    //         position: 'top-end',
                    //         text: 'มีสินค้านี้ในรถเข็นแล้ว',
                    //         icon: 'warning',
                    //         timer: 12000,
                    //         showConfirmButton: false
                    //     });
                    // })
                    // </script>";
                    header("refresh:0.0000001; url=dealer_cart.php");
                    if ($check_state == true) {
                        break;
                    }
                } else {
                    $check_state = false;
                }
            }
            if ($check_state == false) {
                $sql = $conn->prepare("INSERT INTO cart(qty,price ,product_id, user_id, status_cart)
                    VALUES(:qty,:price ,:product_id, :user_id, :status_cart)");
                $sql->bindParam(":qty", $qty);
                $sql->bindParam(":price", $price[1]);
                $sql->bindParam(":product_id", $p_id);
                $sql->bindParam(":user_id", $user_id);
                $sql->bindParam(":status_cart", $status_cart);
                $sql->execute();

                if ($sql) {
                    // echo "<script>alert('เพิ่มสินค้าในรถเข็นแล้ว')</script>";
                    // echo "<script>
                    // $(document).ready(function() {
                    //     Swal.fire({
                    //         position: 'top-end',
                    //         text: 'เพิ่มสินค้าในรถเข็นแล้ว',
                    //         icon: 'success',
                    //         timer: 12000,
                    //         showConfirmButton: false
                    //     });
                    // })
                    // </script>";
                    $check_state = true;
                    header("refresh:0.0000001; url=dealer_cart.php");
                } else {
                    echo "<script>alert('มีบางอย่างผิดพลาด')</script>";
                    header("refresh:0.0000000001; url=dealer_product_detail.php?p_id=$p_id");
                }
            }
        } else {
            $sql = $conn->prepare("INSERT INTO cart(qty,price,product_id, user_id, status_cart)
                        VALUES(:qty,:price,:product_id, :user_id, :status_cart)");
            $sql->bindParam(":qty", $qty);
            $sql->bindParam(":price", $price[1]);
            $sql->bindParam(":product_id", $p_id);
            $sql->bindParam(":user_id", $user_id);
            $sql->bindParam(":status_cart", $status_cart);
            $sql->execute();

            if ($sql) {
                //  echo "<script>alert('เพิ่มสินค้าในรถเข็นแล้ว')</script>";
                // echo "<script>
                // $(document).ready(function() {
                //     Swal.fire({
                //         position: 'top-end',
                //         text: 'เพิ่มสินค้าในรถเข็นแล้ว',
                //         icon: 'success',
                //         timer: 12000,
                //         showConfirmButton: false
                //     });
                // })
                // </script>";
                $check_state = true;
                header("refresh:1; url=dealer_cart.php");
            } else {
                echo "<script>alert('มีบางอย่างผิดพลาด')</script>";
                header("refresh:0.0000000001; url=dealer_product_detail.php?p_id=$p_id");
            }
        }
    }
}

//Comment System
if (isset($_POST["submit_comment"])) {
    $p_id1 = $_GET['p_id'];
    $comment_text = $_POST["comment_text"];
    $cus_name = $row["firstname"] . " " . $row["lastname"];

    if (empty($comment_text)) {
        // echo "<script>alert('กรุณาแสดงความคิดเห็นก่อนโพสต์')</script>";
        echo "<script>
        $(document).ready(function() {
            Swal.fire({
                text: 'กรุณาแสดงความคิดเห็นก่อนกดโพสต์',
                icon: 'warning',
                timer: 12000,
                showConfirmButton: false
            });
        })
        </script>";
    } else {
        $comment = $conn->prepare("INSERT INTO comment(user_id,cus_name,product_id,comment_text)
                                      VALUES(:user_id,:cus_name,:product_id,:comment_text) ");
        $comment->bindParam(":user_id", $user_id);
        $comment->bindParam(":cus_name", $cus_name);
        $comment->bindParam(":product_id", $p_id1);
        $comment->bindParam(":comment_text", $comment_text);
        $comment->execute();
    }
}

$result_comment = $conn->prepare("SELECT * FROM comment WHERE product_id = :p_id");
$result_comment->bindParam(":p_id", $_GET["p_id"]);
$result_comment->execute();
$row_comment = $result_comment->fetchAll();

//function
// if (isset($_POST['function']) && $_POST['function'] == 'p_price2') {
//     echo $rowPD_D['p_price2'];
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail - MAGICA</title>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
</head>

<body style="font-family: 'Kanit', sans-serif ;">
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
    <div class="container-fluid box-pdd">
        <div class="img-pdd">
            <div class="img-box1-pdd">
                <div class="img1">
                    <a data-fancybox="gallery" href="../asset/upload/product/<?= $rowPD_D['p_img1']; ?>">
                        <img src="../asset/upload/product/<?= $rowPD_D['p_img1']; ?>" width="100%" alt="">
                    </a>

                </div>
            </div>
            <div class="img1-box2-pdd">
                <div class="img2">
                    <a data-fancybox="gallery" href="../asset/upload/product/<?= $rowPD_D['p_img2']; ?>">
                        <img src="../asset/upload/product/<?= $rowPD_D['p_img2']; ?>" width="100%" alt="">
                    </a>

                </div>
                <div class="img3">
                    <a data-fancybox="gallery" href="../asset/upload/product/<?= $rowPD_D['p_img3']; ?>">
                        <img src="../asset/upload/product/<?= $rowPD_D['p_img3']; ?>" width="100%" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="text-pdd">
            <form method="POST">
                <p class="pd-name" id="product-name"><?= $rowPD_D['p_name'] ?></p>
                <!-- <span class="pd-price">฿</span>
                <input class="pd-price" id="pd-price1" type="text" name="sp1" value="<?= number_format($rowPD_D['p_price1'], 2)  ?> " readonly>
                <input class="pd-price none" id="pd-price2" type="text" name="sp" value="<?= number_format($rowPD_D['p_price2'], 2)  ?> " readonly>
                <input class="pd-price none" id="pd-price3" type="text" name="sp" value="<?= number_format($rowPD_D['p_price3'], 2)  ?> " readonly>
                <input class="pd-price none" id="pd-price4" type="text" name="sp" value="<?= number_format($rowPD_D['p_price4'], 2)  ?> " readonly>
                <input class="pd-price none" id="pd-price5" type="text" name="sp" value="<?= number_format($rowPD_D['p_price5'], 2)  ?> " readonly> -->


                <p class="pd-price" id="pd-price1">฿<?= number_format($rowPD_D['p_price1'], 2) ?> <span class="vat">(ราคานี้ยังไม่รวมภาษีมูลค่าเพิ่ม)</span></p>
                <p class="pd-price none" id="pd-price2">฿<?= number_format($rowPD_D['p_price2'], 2)  ?> <span class="vat"> (ราคานี้ยังไม่รวมภาษีมูลค่าเพิ่ม)</span></p>
                <p class="pd-price none" id="pd-price3">฿<?= number_format($rowPD_D['p_price3'], 2)  ?> <span class="vat"> (ราคานี้ยังไม่รวมภาษีมูลค่าเพิ่ม)</span></p>
                <p class="pd-price none" id="pd-price4">฿<?= number_format($rowPD_D['p_price4'], 2)  ?> <span class="vat">(ราคานี้ยังไม่รวมภาษีมูลค่าเพิ่ม)</span></p>
                <p class="pd-price none" id="pd-price5">฿<?= number_format($rowPD_D['p_price5'], 2)  ?> <span class="vat"> (ราคานี้ยังไม่รวมภาษีมูลค่าเพิ่ม)</span></p>
                <div class="descript">
                    <p class="pd-title">คุณสมบัติ</p>
                    <ul>
                        <li>
                            <p class="pd-descript"><?= $rowPD_D['p_detail'] ?></p>
                        </li>
                    </ul>
                </div>

                <div class="pur-bill ">
                    <input type="text" style="display: none;" name="p_id" value="<?= $rowPD_D['p_id'] ?>">
                    <span class="txt-qty">จำนวน</span>
                    <div class="a-1" id="a-1">
                        <div class="number-input" id="number-input">
                            <input type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="plus" value="-">
                            <input class="quantity" min="1" name="quantity" value="<?php echo $value ?>" type="number">
                            <input type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus" value="+">
                        </div>
                    </div>
                    <div class="a-1" id="a-2">
                        <div class="dealer-radio-buttons-list">
                            <div class="radio1">
                                <label class="radio dealer-radio">
                                    <input class="radio__input" id="amount-one" type="radio" value="50/450" name="quantity" checked>
                                    <span class="radio__span" id="am-1">50 <?= $rowPD_D["p_unit"] ?></span>
                                </label>
                            </div>
                            <div class="radio2">
                                <label class="radio dealer-radio">
                                    <input class="radio__input" id="amount-two" type="radio" value="100/420" name="quantity">
                                    <span class="radio__span" id="am-2">100 <?= $rowPD_D["p_unit"] ?></span>
                                </label>
                            </div>
                            <div class="radio3">
                                <label class="radio dealer-radio">
                                    <input class="radio__input" id="amount-three" type="radio" value="300/390" name="quantity">
                                    <span class="radio__span" id="am-3">300 <?= $rowPD_D["p_unit"] ?></span>
                                </label>
                            </div>
                            <div class="radio4">
                                <label class="radio dealer-radio">
                                    <input class="radio__input" id="amount-four" type="radio" value="500/350" name="quantity">
                                    <span class="radio__span" id="am-4">500 <?= $rowPD_D["p_unit"] ?></span>
                                </label>
                            </div>
                            <div class="radio5">
                                <label class="radio dealer-radio">
                                    <input class="radio__input" id="amount-five" type="radio" value="1000/300" name="quantity">
                                    <span class="radio__span" id="am-5">1000 <?= $rowPD_D["p_unit"] ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="amount-pd">มีสินค้าทั้งหมด <?= $rowPD_D["p_amount"] . ' ' . $rowPD_D["p_unit"] ?></p>

                <div class="btn-cart-buy">
                    <button type="submit" name="add-to-card" class="btn" id="btn-add-to-cart"><i class="bi bi-cart3" id="icon-add-to-cart"></i>เพิ่มในรถเข็น</button>
                    <button type="submit" name="buy-now" class="btn" id="btn-buy">ซื้อทันที</button>

                </div>
                <div class="head-rate-dealer">
                    <p class="title-rate-dealer">พิเศษสำหรับตัวแทนจำหน่าย <span class="txt-rate-dealer">(ยิ่งระดับตัวแทนสูงจะได้รับราคาพิเศษในการสั่งซื้อ)</span></p>
                    <p class="txt-rate-dealer">สั่งซื้อ 50 ชิ้น ขึ้นไปจะได้ระดับตัวแทนเป็น member</p>
                    <p class="txt-rate-dealer">สั่งซื้อ 100 ชิ้น ขึ้นไปจะได้ระดับตัวแทนเป็น member</p>
                    <p class="txt-rate-dealer">สั่งซื้อ 300 ชิ้น ขึ้นไปจะได้ระดับตัวแทนเป็น VIP</p>
                    <p class="txt-rate-dealer">สั่งซื้อ 500 ชิ้น ขึ้นไปจะได้ระดับตัวแทนเป็น SUPER VIP</p>
                    <p class="txt-rate-dealer">สั่งซื้อ 1000 ชิ้น ขึ้นไปจะได้ระดับตัวแทนเป็น Dealer</p>
                </div>
            </form>
        </div>
    </div>
    <div class="line-cut-detail-pd"></div>
    <div class="related">
        <div class="comment-pd">
            <!-- <div class="sum-comment">
                
                 <div class="box-sum-comment">
                    <div class="box-content-sum-comment">
                       
                    </div>
                </div>
            </div> -->
            <div class="body-comment">
                <span class="txt-review">ความคิดเห็น <span><?= count($row_comment) ?> รายการ</span></span>
                <!-- <span class="txt-title-comment">แสดงความคิดเห็น</span> -->
                <div class="box-comment">
                    <form class="form-comment" method="post">
                        <input type="text" name="comment_text" class="input-comment" placeholder="แสดงความคิดเห็น">
                        <button type="submit" name="submit_comment" class="btn-comment">โพสต์</button>
                    </form>
                </div>
                <div class="txt-comment">
                    <?php
                    if (count($row_comment) > 0) { ?>
                        <?php foreach (array_reverse($row_comment) as $row_comment) { ?>
                            <div class="content-comment">
                                <div class="box-content-comment">

                                    <span class="txt-comment-cusname"><?= $row_comment["cus_name"] ?></span>
                                    <span class="txt-comment-date"><?= $row_comment["created_at"] ?></span>
                                </div>

                                <span class="text-comment"><?= $row_comment["comment_text"] ?></span>
                            </div>
                            <div class="line-bottom-comment"></div>
                        <?php }
                        ?>
                    <?php  } else {
                    ?>
                        <span class="txt-not-comment">ยังไม่มีการแสดงความคิดเห็น</span>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="related-pd">
            <div class="txt-related">
                <span>สินค้าที่เกี่ยวข้อง</span>
            </div>

            <div class="g-box-related">
                <?php
                $stmtRT = $conn->prepare("SELECT * FROM product_dealer WHERE t_id = :t_id");
                $stmtRT->bindParam(":t_id", $rowPD_D['t_id']);
                $stmtRT->execute();
                $rowRT = $stmtRT->fetchAll(PDO::FETCH_ASSOC);

                $i = 0;
                ?>
                <?php
                foreach ($rowRT as $rowRT) { ?>
                    <div class="related-RT">
                        <a class="a-none" href="../dealer/dealer_product_detail.php?p_id=<?= $rowRT['p_id']; ?>">
                            <div class="card" id="card">
                                <div class="card-top">
                                    <img src="../asset/upload/product/<?= $rowRT['p_img1'] ?>" class="card-img-top" alt="...">
                                </div>
                                <div class="card-body">
                                    <p class="card-title"><?= $rowRT['p_name'] ?></p>
                                    <p class="card-text">฿<?= number_format($rowRT['p_price1'], 2) ?></p>
                                    <a href="../dealer/dealer_product_detail.php?p_id=<?= $rowRT['p_id']; ?>" class="btn " id="btn-dp" style="margin: 0 auto;">รายละเอียด</a>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                    $i++;
                    if ($i == 4) {
                        break;
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="footer" style="margin-top: 20px;">
        <?php include('footer.php')
        ?>
    </div>
</body>
<script src="/magica/action.js"></script>
<script type="text/javascript">
    var a = $('#product-name').html();
    var quan = $('#am-1').html();
    console.log(quan);

    //การเลือกจำนวน
    if (a == "แคปซูลจี-เฮิร์บ วัน (G-HERB Capsule 1) ") {
        $('#a-1').addClass("none");
        $('.radio3').addClass("none");
        $('.radio4').addClass("none");
        $('.radio5').addClass("none");
        $('#a-2').removeClass("none");
        $('#am-1').html("144 กระปุก");
        $('#am-2').html("720 กระปุก");

        value1 = "144/680";
        value2 = "720/650";
        $('#amount-one').attr('value', value1);
        $('#amount-two').attr('value', value2);

    } else if (a == "G-HERB PLUS") {
        $('#a-1').addClass("none");
        $('#a-2').removeClass("none");
    } else {
        $('#a-1').addClass("none");
        $('#a-2').removeClass("none");
    }


    //คลิกแล้วเปลี่ยนราคา
    $('#amount-one').click(function() {
        $('#pd-price5').addClass("none");
        $('#pd-price4').addClass("none");
        $('#pd-price3').addClass("none");
        $('#pd-price2').addClass("none");
        $('#pd-price1').removeClass("none");
    });
    $('#amount-two').click(function() {
        $('#pd-price5').addClass("none");
        $('#pd-price4').addClass("none");
        $('#pd-price3').addClass("none");
        $('#pd-price1').addClass("none");
        $('#pd-price2').removeClass("none");
    });
    $('#amount-three').click(function() {
        $('#pd-price5').addClass("none");
        $('#pd-price4').addClass("none");
        $('#pd-price2').addClass("none");
        $('#pd-price1').addClass("none");
        $('#pd-price3').removeClass("none");
    });
    $('#amount-four').click(function() {
        $('#pd-price5').addClass("none");
        $('#pd-price3').addClass("none");
        $('#pd-price2').addClass("none");
        $('#pd-price1').addClass("none");
        $('#pd-price4').removeClass("none");
    });
    $('#amount-five').click(function() {
        $('#pd-price4').addClass("none");
        $('#pd-price3').addClass("none");
        $('#pd-price2').addClass("none");
        $('#pd-price1').addClass("none");
        $('#pd-price5').removeClass("none");
    });
</script>

</html>