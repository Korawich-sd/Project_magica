<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: signin.php");
}

if (isset($_POST['submit_t_name'])) {
    $p_name = $_POST['p_name'];
    $t_name = $_POST['t_name'];
    $detail = $_POST['detail'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];
    $unit = $_POST['unit'];
    $img1 = $_FILES['img1'];
    $img2 = $_FILES['img2'];
    $img3 = $_FILES['img3'];
    $t_id = $_POST['t_name'];

    $allow = array('jpg', 'jpeg', 'png');
    $extention1 = explode(".", $img1['name']); //เเยกชื่อกับนามสกุลไฟล์
    $extention2 = explode(".", $img2['name']); //เเยกชื่อกับนามสกุลไฟล์
    $extention3 = explode(".", $img3['name']); //เเยกชื่อกับนามสกุลไฟล์
    $fileActExt1 = strtolower(end($extention1)); //แปลงนามสกุลไฟล์เป็นพิมพ์เล็ก
    $fileActExt2 = strtolower(end($extention2)); //แปลงนามสกุลไฟล์เป็นพิมพ์เล็ก
    $fileActExt3 = strtolower(end($extention3)); //แปลงนามสกุลไฟล์เป็นพิมพ์เล็ก
    $fileNew1 = rand() . "." . $fileActExt1;
    $fileNew2 = rand() . "." . $fileActExt2;
    $fileNew3 = rand() . "." . $fileActExt3;
    $filePath1 = "../asset/upload/product/" . $fileNew1;
    $filePath2 = "../asset/upload/product/" . $fileNew2;
    $filePath3 = "../asset/upload/product/" . $fileNew3;

    if (in_array($fileActExt1, $allow) && in_array($fileActExt2, $allow) && in_array($fileActExt3, $allow)) {
        if ($img1['size'] > 0 && $img1['error'] == 0 && $img2['size'] > 0 && $img2['error'] == 0 && $img3['size'] > 0 && $img3['error'] == 0) {
            if (move_uploaded_file($img1['tmp_name'], $filePath1) && move_uploaded_file($img2['tmp_name'], $filePath2) && move_uploaded_file($img3['tmp_name'], $filePath3)) {
                $sql = $conn->prepare("INSERT INTO product(p_name, p_detail, p_price, p_amount, p_unit, p_img1, p_img2, p_img3, t_id) 
                VALUES(:p_name,:p_detail,:p_price,:p_amount,:p_unit,:p_img1,:p_img2,:p_img3,:t_id)");
                $sql->bindParam(":p_name", $p_name);
                $sql->bindParam(":p_detail", $detail);
                $sql->bindParam(":p_price", $price);
                $sql->bindParam(":p_amount", $amount);
                $sql->bindParam(":p_unit", $unit);
                $sql->bindParam(":p_img1", $fileNew1);
                $sql->bindParam(":p_img2", $fileNew2);
                $sql->bindParam(":p_img3", $fileNew3);
                $sql->bindParam(":t_id", $t_id);
                $sql->execute();

                if ($sql) {
                    echo "<script>alert('เพิ่มข้อมูลสินค้าเรียบร้อย')</script>";
                    header("refresh:0.0000000001; url=../admin/add_product.php");
                } else {
                    echo "<script>alert('มีบางอย่างผิดพลาด')</script>";
                    header("refresh:0.0000000001; url=../admin/add_product.php");
                }
            }
        }
    }
}
?>