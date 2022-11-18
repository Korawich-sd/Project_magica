<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['dealer_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: signin.php");
} else {
    $user_id = $_SESSION['dealer_login'];
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = $user_id");
    $stmt->execute();
    $rowCart = $stmt->fetchAll();

    $dataUser = $conn->prepare("SELECT * FROM users WHERE id = $user_id");
    $dataUser->execute();
    $rowUser = $dataUser->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET["order_id"])) {
    $order_id = $_GET["order_id"];

    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_Id = :order_id");
    $stmt->bindParam(":order_id", $order_id);
    $stmt->execute();
    $row_Order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_Order["status_order"] == 1) {
        $status_order = "รออนุมัติคำสั่งซื้อ";
    } else {
        $status_order = "กำลังตวจสอบ";
    }


    $stmt2 = $conn->prepare("SELECT * FROM orders_detail WHERE order_id = :order_id");
    $stmt2->bindParam(":order_id", $order_id);
    $stmt2->execute();
    $row_Order_detail = $stmt2->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

<body style="font-family: 'Kanit', sans-serif ;">
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
            <div id="step2" class="progress__item ok">
                <div class="progress__bar"></div>
                <div class="progress__step"><i class="bi bi-credit-card"></i></div>
                <div class="progress__text">ทำการสั่งซื้อ</div>
            </div>

            <div id="step3" class="progress__item active">
                <div class="progress__bar"></div>
                <div class="progress__step"><i class="bi bi-check2-circle"></i></div>
                <div class="progress__text">สั่งซื้อเสร็จสิ้น</div>
            </div>
        </div>
    </div>
    <div class="box-order-detail">
        <div class="box-receipt">
            <div class="box-inside-receipt">
                <p class="txt-title-detail-order">สรุปคำสั่งซื้อ</p>
                <div class="body-detail-order">
                    <span id="order-date-detail"><?= $row_Order["order_date"] ?></span>
                    <span id="order-Id-detail">เลขที่คำสั่งซื้อ <?= $row_Order["order_Id"] ?></span>
                </div>
                <div class="body-detail-order">
                    <span>สถานะคำสั่งซื้อ: <span class="txt-status-order"><?= $status_order ?></span></span>
                </div>
                <div class="body-detail-order">
                    <table class="table table-borderless">
                        <thead class="pos-txt-table-detail-order">
                            <tr>
                                <th scope="col" style="padding-left: 0px;padding-right: 0px;">รายการสั่งซื้อ</th>
                                <th scope="col" class="pos-txt-body-detail-order">จำนวน</th>
                                <th scope="col" class="pos-txt-body-detail-order">ราคา</th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($row_Order_detail as $row_Order_detail) { ?>
                            <tbody class="pos-txt-table-detail-order ">
                                <tr>
                                    <td style="padding: 0px;"><?= $row_Order_detail["p_name"] ?></td>
                                    <td style="padding: 0px;" class="pos-txt-body-detail-order"><?= $row_Order_detail["qty"] ?></td>
                                    <td style="padding: 0px;" class="pos-txt-body-detail-order">฿<?= number_format($row_Order_detail["p_price"], 2) ?></td>
                                </tr>
                            </tbody>
                        <?php }
                        ?>

                    </table>
                </div>
                <div class="body-detail-order">
                    <span>ค่าจัดส่ง</span>
                    <span>฿45.00</span>
                </div>
                <div class="body-detail-order">
                    <span>ภาษีมูลค่าเพิ่ม</span>
                    <span>7%</span>
                </div>
                <div class="line-bottom-order-detail"></div>
                <div class="body-detail-order">
                    <span>ยอดรวมทั้งหมด</span>
                    <span>฿<?= number_format($row_Order["total_price"], 2) ?></span>
                </div>
                <div class="body-detail-order">
                    <span>รูปแบบการชำระเงิน</span>
                    <span><?= $row_Order["payment_method"] ?></span>
                </div>
                <div class="body-detail-order">
                    <span>จัดส่งโดย</span>
                    <span><?= $row_Order["shipping_company"] ?></span>
                </div>
                <img class="img-detail-order" src="../asset/img/LogoMGC.png" alt="">
            </div>
        </div>
        <a href="../dealer/dealer_my_purchase.php"><button type="button" class="btn-my-pay">ไปที่การซื้อของฉัน</button></a>
        <div class="line-bottom-outside-order-detail"></div>
        <div class="box-note">
            <span class="txt-title-note">หมายเหตุ: ทำไมขึ้นสถานะ <span class="txt-status-order">รออนุมัติคำสั่งซื้อ</span> </span>
            <span class="txt-body-note">1.เมื่อท่านกดสั่งซื้อแล้ว คำสั่งซื้อจะถูกส่งไปยังแอดมินเพื่อตรวจสอบคำสั่งซื้อ</span>
            <span class="txt-body-note">2.หลังจากแอดมินตรวจสอบคำสั่งซื้อและคำสั่งซื้อถูกต้องเรียบร้อยแล้วแอดมินจะทำการเปลี่ยนสถานะของคำสั่งซื้อเป็น <span class="txt-color-note">อนุมัติคำสั่งซื้อแล้ว</span></span>
        </div>
    </div>
    <div class="recommence-product">
        <div class="text-rec"><b>แนะนำสำหรับคุณ </b></div>
        <div class=" box-rec-pd">
            <?php
            $stmt = $conn->prepare("SELECT * FROM product ");
            $stmt->execute();
            $product = $stmt->fetchAll();

            $i = 0;

            if (!$product) {
                echo "ไม่มีข้อมูล";
            } else {
                foreach ($product as $product) {
            ?>
                    <div class="rec-pd">
                        <div class="card" id="card">
                            <div class="card-top">
                                <img src="../asset/upload/product/<?= $product['p_img1'] ?>" class="card-img-top" alt="...">
                            </div>
                            <div class="card-body">
                                <p class="card-title"><?= $product['p_name'] ?></p>
                                <p class="card-text">฿<?= number_format($product['p_price'], 2)  ?></p>
                                <a href="../dealer/dealer_product_detail.php?p_id=<?= $product['p_id']; ?>" class="btn " id="btn-dp" style="margin: 0 auto;">รายละเอียด</a>
                            </div>
                        </div>
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
                <a href="../dealer/dealer_product.php"><button type="button" class="btn" id="see-more">ดูเพิ่มเติม</button></a>
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
                url: '../dealer/dealer_order.php',
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
                url: '../dealer/dealer_order.php',
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