<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
session_start();
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
require_once '../config/db.php';
//error_reporting(0);
if (!isset($_SESSION['dealer_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: signin.php");
} else {
    $user_id = $_SESSION['dealer_login'];
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = $user_id");
    $stmt->execute();
    $rowCart = $stmt->fetchAll();
}

if (isset($_GET['act'])) {
    $name = $_GET['search'];
    $s =  urlencode($name);
    header("refresh:0.0000000001; url=dealer_product.php?search=$s");
}

if (isset($_POST['submit_order'])) {
    if (count((array)$_POST['ids']) > 0) {
        $status_cart = 2;
        $p_all = $_POST['ids'];
        $product = implode(",", $_POST['ids']);
        $qty_count = $_POST['quantity'];
        $qty = implode(",", $_POST['quantity']);

        $rev_qty_count = array_reverse($qty_count);
        $final_qty = array_map($rowCart["id"], $qty_count);

        $aaa = strrev($qty);

        //  $b = $rowCart["id"];

        for ($i = 0; $i < count($final_qty); $i++) {
            $stmt = $conn->prepare("UPDATE cart SET qty = :qty,status_cart = :status_cart WHERE id = :p_all");
            $stmt->bindParam(":qty", $final_qty[$i]);
            $stmt->bindParam(":status_cart", $status_cart);
            $stmt->bindParam(":p_all", $rowCart[$i]["id"]);
            $stmt->execute();
        }

        if ($stmt) {
            // echo "<script>alert('update successfully');</script>";
            header("refresh:0.0000000001; url=dealer_order.php?product=$product&qty=$aaa");
        } else {
            //   echo "<script>alert('มีบางอย่างผิดพลาด');</script>";
            header("refresh:0.0000000001; url=dealer_cart.php");
        }
    } else {
        //  echo "<script>alert('กรุณาเลือกสินค้าก่อนทำการสั่งซื้อ');</script>";
        echo "<script>
        $(document).ready(function() {
            Swal.fire({
                text: 'กรุณาเลือกสินค้าก่อนทำการสั่งซื้อ',
                icon: 'warning',
                timer: 10000,
                showConfirmButton: false
            });
        })
        </script>";
        // header("refresh:2; url=cart.php");
    }
}

if (isset($_POST['delete'])) {
    if (count((array)$_POST['ids']) > 0) {
        $all = implode(",", $_POST['ids']);
        $sql = $conn->prepare("DELETE FROM cart WHERE id in ($all)");
        $sql->execute();

        if ($sql) {
            //echo "<script>alert('ลบสินค้าในรถเข็นเรียบร้อยแล้ว');</script>";
            echo "<script>
            $(document).ready(function() {
                Swal.fire({
                    text: 'ลบสินค้าในรถเข็นเรียบร้อยแล้ว',
                    icon: 'success',
                    timer: 10000,
                    showConfirmButton: false
                });
            })
            </script>";
            header("refresh:2; url=dealer_cart.php");
        } else {
            echo "<script>alert('มีบางอย่างผิดพลาด');</script>";
            header("refresh:0.0000000001; url=dealer_cart.php");
        }
    } else {
        // echo "<script>alert('ต้องคลิกเลือกสินค้าก่อนลบ');</script>";
        echo "<script>
        $(document).ready(function() {
            Swal.fire({
                text: 'ต้องคลิกเลือกสินค้าก่อนลบ',
                icon: 'warning',
                timer: 10000,
                showConfirmButton: false
            });
        })
        </script>";
        header("refresh:2; url=dealer_cart.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - MAGICA</title>
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
</head>

<body style="font-family: 'Kanit', sans-serif ;">
    <!-- Header Zone-->
    <header class="container-fluid" id="navbar">
        <nav class="navbar-container container-fluid">
            <a href="../dealer/home.php" class="home-link">
                <img class="img-logo" src="/magica/asset/img/LogoMGC.png" alt="MGC">

            </a>
            <form class="s-search" method="get">
                <div class="container s-search">
                    <div class="input-group s-search">
                        <div class="form-outline">
                            <input type="search" id="form1" name="search" class="form-control search-bar" placeholder="ค้นหา" style="height: 45px; width: 290px; margin-left:30px; margin-top: 5px; border-radius: 5px 0px 0px 5px;box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.05); " />
                        </div>
                        <button type="submit" class="btn" name="act" value="q" style="height: 45px; width: 55px; margin-top: 5px; background-color: #014EB8; box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.05);">
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
            <div id="step1" class="progress__item active">
                <div class="progress__bar "></div>
                <div class="progress__step  color"><i class="bi bi-cart3 icon-color"></i></div>
                <div class="progress__text">รถเข็นสินค้า</div>
            </div>
            <div id="step2" class="progress__item ">
                <div class="progress__bar"></div>
                <div class="progress__step"><i class="bi bi-credit-card"></i></div>
                <div class="progress__text">ทำการสั่งซื้อ</div>
            </div>
            <?php

            ?>
            <div id="step3" class="progress__item">
                <div class="progress__bar"></div>
                <div class="progress__step"><i class="bi bi-check2-circle"></i></div>
                <div class="progress__text">สั่งซื้อเสร็จสิ้น</div>
            </div>

        </div>
    </div>

    <div class="bg-box-cart">

        <div class="box-cart">
            <!-- <div class="box-bg-cart"> -->
            <form action="../dealer/dealer_cart.php" class="box-bg-cart" method="post">

                <div class="select-all">
                    <input type="checkbox" class="form-check-input" id="select_all">
                    <span class="txt-select-all">เลือกทั้งหมด</span>
                    <button type="submit" class="btn-delete-cart-all" name="delete" onclick="return confirm('ต้องการลบสินค้าทั้งหมดในรถเข็นใช่ไหม?');"><i class="bi bi-trash-fill"></i></button>
                </div>
                <?php
                foreach ($rowCart as $rowCart) { ?>
                    <?php
                    $p_id = $rowCart['product_id'];
                    $sql_p = $conn->prepare("SELECT * FROM product_dealer WHERE p_id = $p_id ");
                    $sql_p->execute();
                    $row_p = $sql_p->fetchAll();


                    ?>

                    <?php
                    if ($sql_p->rowCount() > 0) { ?>
                        <?php foreach ($row_p as $row_p) { ?>
                            <div class="bg-content-cart">
                                <input type="checkbox" class="checkbox checkbox-only" name="ids[]" value=<?php echo $rowCart['id'] ?>>
                                <div class="box-img-cart"><img src="../asset/upload/product/<?= $row_p['p_img1'] ?>" width="100%" alt=""></div>
                                <div class="box-txt-cart">
                                    <span><?= $row_p['p_name'] ?></span>
                                    <p><?= '฿' . number_format($rowCart["price"], 2) ?>
                                        <!-- <span class="vat">(ราคาส่วนลดจะแสดงในหน้าสั่งซื้อ)</span> -->
                                    </p>
                                    <div class="number-input">
                                        <span class="dealer-txt-amount">จำนวน</span>
                                        <!-- <input type="button" class="dealer-txt-amount" value="จำนวน" readonly> -->
                                        <input class="quantity" name="quantity[]" value="<?= $rowCart['qty'] ?>" type="number" readonly>
                                        <!-- <input type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus" value="+"> -->
                                        <span class="dealer-txt-amount"><?= $row_p["p_unit"] ?></span>
                                    </div>
                                </div>

                                <button type="submit" class="btn-delete" onclick="return confirm('ต้องการลบสินค้านี้ในรถเข็นใช่ไหม');" name="delete"><i class="bi bi-trash-fill"></i></button>

                            </div>

                        <?php  }
                        ?>

                    <?php } else { ?>
                        <span>ยังไม่มีสินค้าในตะกร้า</span>
                <?php  }
                }
                ?>
                <button type="submit" class="btn" name="submit_order" id="btn-accept">ทำการสั่งซื้อ</button>

            </form>
            <!-- </div> -->

        </div>
    </div>

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
                        <a class="a-none" href="../dealer/dealer_product_detail.php?p_id=<?= $product['p_id']; ?>">
                            <div class="card" id="card">
                                <div class="card-top">
                                    <img src="../asset/upload/product/<?= $product['p_img1'] ?>" class="card-img-top" alt="...">
                                </div>
                                <div class="card-body">
                                    <p class="card-title"><?= $product['p_name'] ?></p>
                                    <p class="card-text">฿<?= number_format($product['p_price1'], 2)  ?></p>
                                    <a href="../dealer/dealer_product_detail.php?p_id=<?= $product['p_id']; ?>" class="btn " id="btn-dp" style="margin: 0 auto;">รายละเอียด</a>
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
                <a href="../dealer/dealer_product.php"><button type="button" class="btn" id="see-more">ดูเพิ่มเติม</button></a>
            </div>


        </div>
    </div>
    <div class="footer" style="margin-top: 20px;">
        <?php include('footer.php')
        ?>
    </div>
</body>
<script src="/magica/action.js"></script>
<script>
    //for checkbox
    $(document).ready(function() {
        $('#select_all').on('click', function() {
            if (this.checked) {
                $('.checkbox').each(function() {
                    this.checked = true;
                })
            } else {
                $('.checkbox').each(function() {
                    this.checked = false;
                })
            }
        })
        $('.checkbox').on('click', function() {
            if ($('.checkbox:checked').length == $('.checkbox').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        })

    });
</script>
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
        height: 4px;
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

</html>