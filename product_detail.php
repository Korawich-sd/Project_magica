<!-- Responsive -->
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
session_start();
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
require_once 'config/db.php';

if (isset($_GET['p_id'])) {

    $stmtPD_D = $conn->prepare("SELECT * FROM product WHERE p_id = :p_id");
    $stmtPD_D->bindParam("p_id", $_GET['p_id'], PDO::PARAM_INT);
    $stmtPD_D->execute();
    $rowPD_D = $stmtPD_D->fetch(PDO::FETCH_ASSOC);

    if ($stmtPD_D->rowCount() != 1) {
        header('location: ../user/product.php');
        exit();
    }
}

if (isset($_SESSION['user_login'])) {
    $user_id = $_SESSION['user_login'];
    $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
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

    $check_state = true;

    // echo '<pre>';
    // print_r($p_id);
    // print_r($qty);
    // echo '</pre>';

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
                    header("refresh:2; url=product_detail.php?p_id=$p_id");
                    if ($check_state == true) {
                        break;
                    }
                } else {
                    $check_state = false;
                }
            }
            if ($check_state == false) {
                $sql = $conn->prepare("INSERT INTO cart(qty, product_id, user_id, status_cart)
                    VALUES(:qty, :product_id, :user_id, :status_cart)");
                $sql->bindParam(":qty", $qty);
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
                    header("refresh:2; url=product_detail.php?p_id=$p_id");
                } else {
                    echo "<script>alert('เพิ่มสินค้าในรถเข็นไม่สำเร็จ')</script>";
                    header("refresh:0.0000000001; url=product_detail.php?p_id=$p_id");
                }
            }
        } else {
            $sql = $conn->prepare("INSERT INTO cart(qty, product_id, user_id, status_cart)
                        VALUES(:qty, :product_id, :user_id, :status_cart)");
            $sql->bindParam(":qty", $qty);
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
                header("refresh:2; url=product_detail.php?p_id=$p_id");
            } else {
                echo "<script>alert('เพิ่มสินค้าในรถเข็นไม่สำเร็จ')</script>";
                header("refresh:0.0000000001; url=product_detail.php?p_id=$p_id");
            }
        }
    } else {
        echo "<script>alert('มีบางอย่างผิดพลาด')</script>";
        header("refresh:0.0000000001; url=product_detail.php?p_id=$p_id");
    }
}


if(isset($_POST['buy-now'])){
    $p_id = $_POST['p_id'];
    $qty = $_POST['quantity'];
    $user_id = $row['id'];
    $status_cart = 1;

    $check_state = true;
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
                    header("refresh:1; url=cart.php");
                    if ($check_state == true) {
                        break;
                    }
                } else {
                    $check_state = false;
                }
            }
            if ($check_state == false) {
                $sql = $conn->prepare("INSERT INTO cart(qty, product_id, user_id, status_cart)
                    VALUES(:qty, :product_id, :user_id, :status_cart)");
                $sql->bindParam(":qty", $qty);
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
                    header("refresh:2; url=cart.php");
                } else {
                    echo "<script>alert('มีข้อผิดพลาดบางอย่าง')</script>";
                    header("refresh:0.0000000001; url=product_detail.php?p_id=$p_id");
                }
            }
        } else {
            $sql = $conn->prepare("INSERT INTO cart(qty, product_id, user_id, status_cart)
                        VALUES(:qty, :product_id, :user_id, :status_cart)");
            $sql->bindParam(":qty", $qty);
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
                header("refresh:2; url=cart.php");
            } else {
                echo "<script>alert('มีบางอย่างผิดพลาด')</script>";
                header("refresh:0.0000000001; url=product_detail.php?p_id=$p_id");
            }
        }
    } else {
        echo "<script>alert('มีบางอย่างผิดพลาด')</script>";
        header("refresh:0.0000000001; url=product_detail.php?p_id=$p_id");
    }
}



// Comment System
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


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail - MAGICA</title>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
</head>

<body style="font-family: 'Kanit', sans-serif ;">
    <!-- Header Zone-->
   <!-- //// Header //// -->
<?php include('../magica/header.php'); ?>
<!-- //// Header //// -->

    <div class="container-fluid box-pdd">
        <div class="img-pdd">
            <div class="img-box1-pdd">
                <div class="img1">
                    <a data-fancybox="gallery" href="asset/upload/product/<?= $rowPD_D['p_img1']; ?>">
                        <img src="asset/upload/product/<?= $rowPD_D['p_img1']; ?>" width="100%" alt="">
                    </a>

                </div>
            </div>
            <div class="img1-box2-pdd">
                <div class="img2">
                    <a data-fancybox="gallery" href="asset/upload/product/<?= $rowPD_D['p_img2']; ?>">
                        <img src="asset/upload/product/<?= $rowPD_D['p_img2']; ?>" width="100%" alt="">
                    </a>

                </div>
                <div class="img3">
                    <a data-fancybox="gallery" href="asset/upload/product/<?= $rowPD_D['p_img3']; ?>">
                        <img src="asset/upload/product/<?= $rowPD_D['p_img3']; ?>" width="100%" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="text-pdd">
            <p class="pd-name"><?= $rowPD_D['p_name'] ?></p>
            <p class="pd-price">฿<?= number_format($rowPD_D['p_price'], 2)  ?> <span class="vat">(ราคานี้ยังไม่รวมภาษีมูลค่าเพิ่ม)</span></p>
            <div class="descript">
                <p class="pd-title">คุณสมบัติ</p>
                <ul>
                    <li>
                        <p class="pd-descript"><?= $rowPD_D['p_detail'] ?></p>
                    </li>
                </ul>
            </div>
            <form method="POST">
                <div class="pur-bill ">
                    <input type="text" style="display: none;" name="p_id" value="<?= $rowPD_D['p_id'] ?>">
                    <span class="txt-qty">จำนวน</span>
                    <div class="number-input">
                        <input type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="plus" value="-">
                        <input class="quantity" min="1" name="quantity" value="<?php echo $value ?>" type="number">
                        <input type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus" value="+">
                    </div>
                </div>
                <div class="btn-cart-buy">
                    <button type="button" onclick="location.href='signin.php';" name="add-to-card" class="btn" id="btn-add-to-cart" ><i class="bi bi-cart3" id="icon-add-to-cart"></i>เพิ่มในรถเข็น</button>
                   <button type="button" onclick="location.href='signin.php';"  name="buy-now" class="btn" id="btn-buy">ซื้อทันที</button>

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
                        <button type="button" onclick="location.href='signin.php';"  name="submit_comment" class="btn-comment">โพสต์</button>
                    </form>
                </div>
                <div class="txt-comment">
                    <?php
                    if (count($row_comment) > 0) { ?>
                        <?php foreach (array_reverse($row_comment) as $row_comment) { ?>
                            <div class="content-comment">
                                <div class="box-content-comment">

                                    <span class="txt-comment-cusname"><?= $row_comment["cus_name"] ?></span>
                                    <span><?= $row_comment["created_at"] ?></span>
                                </div>

                                <span><?= $row_comment["comment_text"] ?></span>
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
                $stmtRT = $conn->prepare("SELECT * FROM product WHERE t_id = :t_id");
                $stmtRT->bindParam(":t_id", $rowPD_D['t_id']);
                $stmtRT->execute();
                $rowRT = $stmtRT->fetchAll(PDO::FETCH_ASSOC);


                $i = 0;
                ?>

                <?php
                foreach ($rowRT as $rowRT) { ?>
                    <div class="related-RT">
                        <div class="card" id="card">
                            <div class="card-top">
                                <img src="asset/upload/product/<?= $rowRT['p_img1'] ?>" class="card-img-top" alt="...">
                            </div>
                            <div class="card-body">
                                <p class="card-title"><?= $rowRT['p_name'] ?></p>
                                <p class="card-text">฿<?= number_format($rowRT['p_price'], 2) ?></p>
                                <a href="product_detail.php?p_id=<?= $rowRT['p_id']; ?>" class="btn " id="btn-dp" style="margin: 0 auto;">รายละเอียด</a>
                            </div>
                        </div>
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
</html>