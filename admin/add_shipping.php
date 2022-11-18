<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: signin.php");
} 

if (isset($_POST['submit_t_name'])) {
    $shipping_name = $_POST['shipping_name'];
    $shipping_cost = $_POST['shipping_cost'];
    $img1 = $_FILES['img1'];

    $allow = array('jpg', 'jpeg', 'png');
    $extention1 = explode(".", $img1['name']); //เเยกชื่อกับนามสกุลไฟล์
    $fileActExt1 = strtolower(end($extention1)); //แปลงนามสกุลไฟล์เป็นพิมพ์เล็ก
    $fileNew1 = rand() . "." . $fileActExt1;

    $filePath1 = "../asset/upload/shipping/" . $fileNew1;

    if (in_array($fileActExt1, $allow)) {
        if ($img1['size'] > 0 && $img1['error'] == 0) {
            if (move_uploaded_file($img1['tmp_name'], $filePath1)) {
                $sql = $conn->prepare("INSERT INTO shipping_company(shipping_name, shipping_cost,shipping_img) 
                VALUES(:shipping_name, :shipping_cost, :shipping_img)");
                $sql->bindParam(":shipping_name", $shipping_name);
                $sql->bindParam(":shipping_cost", $shipping_cost);
                $sql->bindParam(":shipping_img", $fileNew1);
                $sql->execute();

                if ($sql) {
                    echo "<script>alert('เพิ่มข้อมูลขนส่งเรียบร้อย')</script>";
                    header("refresh:0.0000000001; url=../admin/add_shipping.php");
                } else {
                    echo "<script>alert('มีบางอย่างผิดพลาด')</script>";
                    header("refresh:0.0000000001; url=../admin/add_shipping.php");
                }
            }
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
    <title>Document</title>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<?php
$sql_type = $conn->prepare("SELECT * FROM p_type");
$sql_type->execute();
$query = $sql_type->fetchAll();

?>

<body>
    <div class="container">
        <div class="row g-3 align-items-center">
            <form action="../admin/add_shipping.php" method="POST" enctype="multipart/form-data">
                <div class="col-auto">
                    <label for="p_name" class="col-form-label">ชื่อบริษัทขนส่ง</label>
                    <input type="text" name="shipping_name" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="price" class="col-form-label">ค่าจัดส่ง(บาท)</label>
                    <input type="text" name="shipping_cost" class="form-control">
                </div>
            
                <div class="col-auto mt-4" style="display: inline-flex;">
                    <label for="img" class="col-form-label">รูปภาพ </label>
                    <div class="filewrap">
                        <input name="img1" id="imgInput1" class="form-control" type="file" />
                        <img width="100%" id="previewImg1" alt="">
                    </div>
                </div>
                

                <div class="col-auto mt-4">
                    <button type="submit" name="submit_t_name" class="btn btn-success">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let imgInput1 = document.getElementById('imgInput1');
        let previewIm = document.getElementById('previewImg1');

        imgInput1.onchange = evt => {
            const [file] = imgInput1.files;
            if (file) {
                previewImg1.src = URL.createObjectURL(file);
            }
        }
    </script>
</body>

<style>
    /* input file */
    .filewrap {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #ccc;
        border: 1px solid #ccc;
        border-radius: 7px 7px 7px 7px;
        background-image: url('../asset/img/file-upload.png');
        background-repeat: no-repeat;
        background-size: cover;
        height: 159px;
        width: 139px;
        color: #fff;
        font-family: sans-serif;
        font-size: 12px;
        z-index: 1;
        margin: 5px;
    }

    input[type="file"] {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;

    }

    .bi-plus-circle {
        position: absolute;
        width: 60px;
        height: 60px;
        color: blue;
        font-size: 60px;
        z-index: 2;
    }
</style>

</html>