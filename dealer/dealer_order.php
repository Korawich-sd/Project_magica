<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../config/db.php';

error_reporting(0);
if (!isset($_SESSION['dealer_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: signin.php");
} else {
    $user_id = $_SESSION['dealer_login'];
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = $user_id");
    $stmt->execute();
    $rowCart = $stmt->fetchAll();

    $dataUser = $conn->prepare("SELECT * FROM dealer WHERE id = $user_id");
    $dataUser->execute();
    $rowUser = $dataUser->fetch(PDO::FETCH_ASSOC);

    if ($rowUser["address1"] != null) {
        $select_address_order[] = $rowUser["address"];
        array_push($select_address_order, $rowUser["address1"]);
    } else {
        $select_address_order[] = $rowUser["address"];
    }
}

if (isset($_GET['product'])) {
    $ids = $_GET['product'];
    //$qty = $_GET['qty'];
    $stmt = $conn->prepare("SELECT * FROM cart WHERE id in ($ids)");
    $stmt->execute();
    $product = $stmt->fetchAll();


    for ($i = 0; $i < count($product); $i++) {
        $p_id[] = $product[$i][3];
        $final_p_id = implode(",", $p_id);
        $ab[] = $product[$i]["product_id"];
    }

    for ($i = 0; $i < count($p_id); $i++) {
        $sql = $conn->prepare("SELECT * FROM product_dealer WHERE p_id = ($p_id[$i])");
        // $sql->bindParam(":final_p_id", $p_id[$i]);
        $sql->execute();
        $final_p[] = $sql->fetchAll();
    }
}
$shipping = $conn->prepare("SELECT * FROM shipping_company");
$shipping->execute();
$row_shipping = $shipping->fetchAll();


//function shipping
if (isset($_POST['function']) && $_POST['function'] == 'shipping_cost') {
    $shipping_name = $_POST['shipping_name'];
    $sql_ship = $conn->prepare("SELECT * FROM shipping_company WHERE shipping_name = '$shipping_name'");
    $sql_ship->execute();
    $shipping_cost = $sql_ship->fetch(PDO::FETCH_ASSOC);
    echo $shipping_cost['shipping_cost'];
    // echo $shipping_cost['shipping_img'];
    exit();
}
if (isset($_POST['function']) && $_POST['function'] == 'shipping_img') {
    $shipping_name = $_POST['shipping_name'];
    $sql_ship = $conn->prepare("SELECT * FROM shipping_company WHERE shipping_name = '$shipping_name'");
    $sql_ship->execute();
    $shipping_cost = $sql_ship->fetch(PDO::FETCH_ASSOC);
    //  echo $shipping_cost['shipping_cost'];
    echo  $shipping_cost['shipping_img'];
    exit();
}
// if (isset($_POST['function']) && $_POST['function'] == 'status_shipping') {
//     $status_shipping = $_POST['status_shipping'];
//     echo $status_shipping;
//     exit();
// }

if (isset($_POST['submit_order'])) {
    $note = $_POST['note'];
    $cus_id = $_SESSION['dealer_login'];
    $shipping_name = $_POST["shipping_name"];
    $payment_method = $_POST["payment_method"];
    $put_shipping_name = base64_encode($shipping_name);
    $cus_name = $user_id;
    $slip_img = $_FILES['slip_img'];
    $address_order = $_POST["address-order"];

    //ราคาทั้งหมด
    $ship_cost = 45;
    $totalPrice1 = 0;
    for ($i = 0; $i < count($product); $i++) {
        $qty1 =  $product[$i][1];
        $total1 = $product[$i][2];
        $totalPrice1 += $qty1 * $total1;
        $final_price = $totalPrice1 + $ship_cost;
    }
    $tax = ($final_price * 7) / 100;
    $last_price = $final_price + $tax;

    for ($i = 0; $i < count($product); $i++) {
        $qty2[] = $product[$i][1];
    }

    $allow = array('jpg', 'jpeg', 'png');
    $extention1 = explode(".", $slip_img['name']);
    $fileActExt1 = strtolower(end($extention1));
    $fileNew1 = rand() . "." . $fileActExt1;
    $filePath1 = "../asset/upload/slip_order/" . $fileNew1;
    $method = "Mobile Banking";
    $parcel_code = "-";
    $status_order = "1";
    $p_name =  $rowUser["firstname"] . " " . $rowUser["lastname"];

    if ($payment_method == 1) {

        if (in_array($fileActExt1, $allow)) {
            if ($slip_img['size'] > 0 && $slip_img['error'] == 0) {
                if (move_uploaded_file($slip_img['tmp_name'], $filePath1)) {

                    $order = $conn->prepare("INSERT INTO orders(cus_id, cus_name, address, tel, email, slip_img, total_price,shipping_company, parcel_code, status_order ,payment_method,note)
                                            VALUES(:cus_id, :cus_name, :address, :tel, :email, :slip_img, :total_price, :shipping_company, :parcel_code, :status_order , :payment_method, :note)");
                    $order->bindParam(":cus_id", $cus_id);
                    $order->bindParam(":cus_name", $p_name);
                    $order->bindParam(":address", $address_order);
                    $order->bindParam(":tel", $rowUser["tel"]);
                    $order->bindParam(":email", $rowUser["email"]);
                    $order->bindParam(":slip_img", $fileNew1);
                    $order->bindParam(":total_price", $last_price);
                    $order->bindParam(":shipping_company", $shipping_name);
                    $order->bindParam(":parcel_code", $parcel_code);
                    $order->bindParam(":status_order", $status_order);
                    $order->bindParam(":payment_method", $method);
                    $order->bindParam(":note", $note);
                    $order->execute();

                    $id_order = $conn->lastInsertId();

                    for ($i = 0; $i < count($qty2); $i++) {
                        $p_price[] = $product[$i][2] * $product[$i][1];
                        $order_detail = $conn->prepare("INSERT INTO orders_detail(order_id,product_id,qty,p_name,p_price,p_img)
                                                        VALUES(:order_id,:product_id,:qty,:p_name,:p_price,:p_img)");
                        $order_detail->bindParam(":order_id", $id_order);
                        $order_detail->bindParam(":product_id", $rowCart[$i]["id"]);
                        $order_detail->bindParam(":qty", $qty2[$i]);
                        $order_detail->bindParam(":p_name", $final_p[$i][0]["p_name"]);
                        $order_detail->bindParam(":p_price", $p_price[$i]);
                        $order_detail->bindParam(":p_img", $final_p[$i][0]["p_img1"]);
                        $order_detail->execute();
                    }

                    if ($order) {
                        include("dealer_sweet_alert.php");
                        $delete_p_cart = $conn->prepare("DELETE FROM cart WHERE id in ($ids)");
                        $delete_p_cart->execute();
                    } else {
                        header("location: dealer_cart.php");
                    }
                }
            }
        }
    } else
    if ($payment_method == 3) {
        $method = "ชำระเงินปลายทาง";
        $parcel_code = "-";
        $status_order = "1";
        $c_slip = "-";
        $p_name =  $rowUser["firstname"] . " " . $rowUser["lastname"];

        $delete_p_cart = $conn->prepare("DELETE FROM cart WHERE id in ($ids)");
        $delete_p_cart->execute();

        $order = $conn->prepare("INSERT INTO orders(cus_id, cus_name, address, tel, email, slip_img, total_price,shipping_company, parcel_code, status_order ,payment_method,note)
                                VALUES(:cus_id, :cus_name, :address, :tel, :email, :slip_img, :total_price, :shipping_company, :parcel_code, :status_order , :payment_method, :note)");
        $order->bindParam(":cus_id", $cus_id);
        $order->bindParam(":cus_name", $p_name);
        $order->bindParam(":address", $address_order);
        $order->bindParam(":tel", $rowUser["tel"]);
        $order->bindParam(":email", $rowUser["email"]);
        $order->bindParam(":slip_img", $c_slip);
        $order->bindParam(":total_price", $last_price);
        $order->bindParam(":shipping_company", $shipping_name);
        $order->bindParam(":parcel_code", $parcel_code);
        $order->bindParam(":status_order", $status_order);
        $order->bindParam(":payment_method", $method);
        $order->bindParam(":note", $note);
        $order->execute();

        $id_order = $conn->lastInsertId();

        for ($i = 0; $i < count($qty2); $i++) {
            $p_price[] = $product[$i][2] * $product[$i][1];
            $order_detail = $conn->prepare("INSERT INTO orders_detail(order_id,product_id,qty,p_name,p_price,p_img)
                                                        VALUES(:order_id,:product_id,:qty,:p_name,:p_price,:p_img)");
            $order_detail->bindParam(":order_id", $id_order);
            $order_detail->bindParam(":product_id", $rowCart[$i]["id"]);
            $order_detail->bindParam(":qty", $qty2[$i]);
            $order_detail->bindParam(":p_name", $final_p[$i][0]["p_name"]);
            $order_detail->bindParam(":p_price", $p_price[$i]);
            $order_detail->bindParam(":p_img", $final_p[$i][0]["p_img1"]);
            $order_detail->execute();
        }

        if ($order_detail) {
            include("dealer_sweet_alert.php");
        } else {
            header("location: dealer_cart.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order - MAGICA</title>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <style>
        .icon-color {

            font-size: 45px;
            color: #014EB8;
        }

        .bi-credit-card {
            font-size: 45px;
            color: #014EB8;
        }

        .bi-check2-circle {
            font-size: 45px;
            color: #014EB8;
        }

        .progress {
            display: grid;
            height: 100%;
            width: 100%;
            margin: 0 auto;
            padding-top: 55px;
            grid-auto-columns: 1fr;
            grid-auto-flow: column;
            grid-template-rows: max-content;
        }

        .progress__item {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .progress__step {
            width: 100px;
            height: 100px;
            border: 6px solid grey;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            z-index: 0;
        }

        .progress__bar {
            position: absolute;
            width: 100%;
            height: 6px;
            background-color: grey;
            /* z-index: -1; */
            transform: translateX(50%);
        }

        .progress__text {
            position: absolute;
            top: calc(100% + 8px);
            text-align: center;
            font-size: 16px;
            color: #014EB8;
            font-weight: 600;
        }

        .progress__item:last-child .progress__bar {
            display: none;
        }

        .progress__bar:after {
            content: '';
            display: block;
            width: 0;
            height: 6px;
            background-color: #014EB8;
        }

        .progress__item.ok .progress__step {
            /* border-color: green; */
            border: 4px solid #014EB8;
            /* color: white; */
            /* background: green; */
        }

        .progress__item.ok .progress__bar:after {
            animation: nextStep 1s;
            animation-fill-mode: forwards;
        }

        .progress__item.active .progress__step {
            color: blue;
            border: 6px solid #014EB8;
            animation: pulse 2s infinite 1s;
        }


        @keyframes nextStep {
            0% {
                width: 0%;
            }

            100% {
                width: 100%;
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(33, 131, 221, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(33, 131, 221, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(33, 131, 221, 0);
            }
        }
    </style>
</head>

<body style="font-family: 'Kanit', sans-serif;">
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
    <div class="box-status">
        <div class="progress">
            <div id="step1" class="progress__item ok">
                <div class="progress__bar "></div>
                <div class="progress__step  color"><i class="bi bi-cart3 icon-color"></i></div>
                <div class="progress__text">รถเข็นสินค้า</div>
            </div>
            <div id="step2" class="progress__item active">
                <div class="progress__bar"></div>
                <div class="progress__step"><i class="bi bi-credit-card"></i></div>
                <div class="progress__text">ทำการสั่งซื้อ</div>
            </div>

            <div id="step3" class="progress__item">
                <div class="progress__bar"></div>
                <div class="progress__step"><i class="bi bi-check2-circle"></i></div>
                <div class="progress__text">สั่งซื้อเสร็จสิ้น</div>
            </div>
        </div>
    </div>

    <div class="order-box-bg">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="order-address-box">
                <p class="order-txt-address"><i class="bi bi-pin-map-fill"></i>ที่อยู่ในการจัดส่ง</p>
                <div class="order-box-input-address">
                    <select name="address-order" id="address-order" class="form-select">
                        <?php
                        foreach ($select_address_order as $select_address_order) { ?>
                            <option value="<?= $select_address_order ?>"><?= $select_address_order ?></option>

                        <?php }
                        ?>

                    </select>
                    <a href="../dealer/dealer_profile_address.php"><button type="button" class="order-btn-address-submit">เปลี่ยน</button></a>

                </div>
            </div>

            <div class="order-product-box">
                <p class="order-txt-address">รายการสินค้า</p>
                <div class="order-product-content-box">
                    <table class="table table-hover table-order-style">
                        <thead>

                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col">ราคา</th>
                                <th scope="col">จำนวน</th>
                                <th scope="col">รวม</th>
                            </tr>
                        </thead>
                        <?php
                        for ($i = 0; $i < count($product); $i++) {  ?>

                            <tbody class="align-middle">
                                <tr>
                                    <th>
                                        <img class="img-list-order" src="../asset/upload/product/<?= $final_p[$i][0][10] ?>" alt="">
                                    </th>
                                    <td><?= $final_p[$i][0]["p_name"] ?></td>
                                    <td>฿<?= number_format($product[$i][2], 2)  ?></td>
                                    <td><?= $product[$i][1] ?></td>
                                    <td>฿<?= number_format($product[$i][2] * $product[$i][1], 2)  ?></td>
                                </tr>
                            </tbody>

                        <?php  }
                        ?>

                    </table>
                </div>
            </div>
     <?php
// echo '<pre>';
// print_r($product);
// echo '</pre>';
     ?>
            <div class="order-logis-box">
                <div class="order-logis-content-box">
                    <div class="code-select">
                        <i class="bi bi-upc-scan"></i>
                        <span class="order-logis-txt-code">โค้ดส่วนลด</span>
                        <input type="text" class="form-control" id="order-logis-code" placeholder="Code (ยังไม่เปิดให้ใช้งาน)" disabled>
                        <button type="submit" class="order-code-logis">เลือกโค้ดส่วนลด</button>
                    </div>
                    <div class="note">
                        <span class="order-logis-txt-code">หมายเหตุ</span>
                        <input type="text" name="note" class="form-control" id="order-logis-note" placeholder="ฝากข้อความถึงผู้ขาย (ไม่บังคับ)">
                    </div>
                    <div class="order-line-bottom-code"></div>
                    <div class="order-select-logis">
                        <span class="order-logis-txt-select">การจัดส่ง</span>

                        <select name="shipping_name" id="select-logis">
                            <!-- <option selected disabled>-กรุณาขนส่ง-</option> -->
                            <?php foreach ($row_shipping as $value) {
                            ?>
                                <option value="<?= $value['shipping_name'] ?>"><?= $value['shipping_name'] ?></option>

                            <?php

                            } ?>
                        </select>

                        <span id="shipping_cost">฿45.00</span>
                        <img id="logo-shipping" src="../asset/upload/shipping/191722181.png" alt="">
                    </div>
                </div>
            </div>

            <div class="order-payment-box">
                <div class="payment-box">
                    <div class="bord-payment-box">
                        <div class="txt-payment-title">
                            <img src="../asset/img/icon-payment.png" class="order-icon-payment" alt=""><span class="method-payment-txt">วิธีการชำระเงิน</span>
                        </div>
                        <span>เลือกวิธีชำระเงิน</span>
                        <div class="radio-buttons-list">
                            <label class="radio">
                                <input class="radio__input" type="radio" value="1" name="payment_method"  hidden>
                                <span class="radio__span " id="mobile-banking" data-bs-toggle="modal" data-bs-target="#paymentModal"> <img src="../asset/img/kbank-icon.png" class="icon-mobile-banking" alt="">Mobile Banking</span>
                            </label>
                            <!-- <label class="radio">
                                <input class="radio__input" type="radio" value="2" name="payment_method" hidden disabled>
                                <span class="radio__span"><img src="../asset/img/icon-credit.png" class="icon-credit" alt="">บัตรเครดิต/เดบิต</span>
                            </label> -->
                            <label class="radio">
                                <input class="radio__input" type="radio" value="3" name="payment_method" hidden>
                                <span class="radio__span" id="money"> <img src="../asset/img/icon-money.png" class="icon-money" alt="">ชำระเงินปลายทาง</span>
                            </label>

                        </div>
                        <!-- <button type="button" class="box-mobile-banking" value="โมบายแบงค์กิ้ง" id="mobile-banking" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <img src="../asset/img/kbank-icon.png" class="icon-mobile-banking" alt=""><span>โมบายแบงค์กิ้ง</span>
                        </button>
                        <div class="box-credit">
                            <img src="../asset/img/icon-credit.png" class="icon-credit" alt=""><span>บัตรเครดิต/เดบิต</span>
                        </div>

                        <div class="box-money"  id="money">
                            <input type="text" name="payment_money" class="box-money-input " value="ชำระเงินปลายทาง" readonly hidden>
                            <img src="../asset/img/icon-money.png" class="icon-money" alt=""><span>ชำระเงินปลายทาง</span>
                        </div> -->

                        <div class="status-payment-box">
                            <div class="filewrap">
                                <input class="form-control imgInput1" name="slip_img" id="imgInput1" type="file">
                                <img width="100%" id="previewImg1" alt="">
                            </div>
                        </div>
                        <span id="status_shipping"></span>
                    </div>
                </div>
                <div class="summary-box">
                    <div class="bg-summary-box">
                        <div class="txt-title-summary">
                            <img src="../asset/img/icon-summary-order.png" class="icon-summary-order" alt=""><span>ยอดสั่งซื้อทั้งหมด</span>
                        </div>
                        <div class="txt-content-summary1">
                            <span>ยอดรวมสินค้า:</span>
                            <span>
                                <?php
                                $totalPrice = 0;
                                for ($i = 0; $i < count($product); $i++) {
                                    $qty =  $product[$i][1];
                                    $total = $product[$i][2];
                                    $totalPrice += $qty * $total;
                                }
                                echo '฿' . number_format($totalPrice, 2);
                                ?>
                            </span>
                        </div>
                        <div class="txt-content-summary1">
                            <span>รวมค่าจัดส่ง:</span>
                            <span id="sum-shipping-cost">฿45.00</span>
                        </div>
                        <div class="txt-content-summary1">
                            <span>ภาษีมูลค่าเพิ่ม:</span> <span>7%</span>
                        </div>
                        <div class="txt-content-summary1">
                            <span>การชำระเงินทั้งหมด:</span>
                            <span>
                                <?php
                                $ship_cost = 45;
                                $final_price = $totalPrice + $ship_cost;
                                $tax = ($final_price * 7) / 100;
                                $last_price = $final_price + $tax;
                                echo '฿' . number_format($last_price, 2);
                                ?>
                            </span>
                        </div>
                        <div class="line-buttom-summaty"></div>
                        <div class="img-logo-summary">
                            <img src="../asset/img/LogoMGC.png" class="logo-mgc-summary" alt="">
                        </div>
                        <div class="btn-submit-order">
                            <button type="submit" name="submit_order" class="btn-order" id="submit_order">สั่งซื้อสินค้า</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  ">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="img-logo-mgc-payment">
                        <img src="../asset/img/LogoMGC.png" class="logo-mgc-summary" alt="">
                    </div> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="box-modal-payment">
                        <span class="txt-modal-title">ชำระเงินด้วยโมบายแบงค์กิ้ง</span>
                        <div class="txt-modal-body">
                            <p class="txt-modal-data">ธนาคาร: กสิกรไทย สาขาอเวนิว รัชโยธิน</p>
                            <p class="txt-modal-data">ชื่อบัญชี: บจม. หัวคิด ดี แอนด์ เบรนด์สตรอม อินโนเวชั่นส์</p>
                            <p>เลขที่บัญชี: 124-2-94877-3</p>
                        </div>
                        <p>หมายเหตุ: เมื่อทำการโอนชำระเงินค่าสินค้าเรียบร้อยแล้ว กรุณาแนบหลักฐานการชำระเงินด้านล่าง</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer" style="margin-top: 20px;">
        <?php include('footer.php') ?>
    </div>
    <script type="text/javascript">
        let imgInput1 = document.getElementById('imgInput1');
        let previewIm = document.getElementById('previewImg1');

        imgInput1.onchange = evt => {
            const [file] = imgInput1.files;
            if (file) {
                previewImg1.src = URL.createObjectURL(file);
            }
        }

        $("#money").click(function() {
            // console.log($(this).attr("value"));

            $(this).addClass("box-active-money");
            $("#mobile-banking").removeClass("box-active-money");
            $(".status-payment-box").addClass("box-hide-status");
            // var status_shipping = "ชำระเงินปลายทาง";
            // //console.log(status_shipping);
            // $.ajax({
            //     type: "POST",
            //     url: '../user/order.php',
            //     data: {
            //         status_shipping: status_shipping,
            //         function: 'status_shipping'
            //     },
            //     success: function(data) {
            //       //  console.log(data);
            //         //  $("#status_shipping").html(data);
            //     }
            // });
        });
        $("#mobile-banking").click(function() {
            //console.log($(this).attr("value"));
            $(this).addClass("box-active-money");
            $("#money").removeClass("box-active-money");
            $(".status-payment-box").removeClass("box-hide-status");
        });
        $('#select-logis').change(function() {
            var shipping_name = $(this).val();
            //  console.log(shipping_name);
            $.ajax({
                type: "POST",
                url: 'dealer_order.php',
                data: {
                    shipping_name: shipping_name,
                    function: 'shipping_cost'
                },
                success: function(data) {
                    // console.log(data);
                    $('#shipping_cost').html('฿' + data + '.00');
                    $('#sum-shipping-cost').html('฿' + data + '.00');
                }
            });
        });

        $('#select-logis').change(function() {
            var shipping_name = $(this).val();
            // console.log(shipping_name);
            $.ajax({
                type: "POST",
                url: 'dealer_order.php',
                data: {
                    shipping_name: shipping_name,
                    function: 'shipping_img'
                },
                success: function(data) {
                    //  console.log(data);
                    url = "../asset/upload/shipping/";
                    $('#logo-shipping').attr('src', url + data);
                }
            });
        });
    </script>
</body>
<script src="/magica/action.js"></script>
</html>