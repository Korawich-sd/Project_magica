<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: signin.php");
} ?>

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
            <form action="../admin/add_product_db.php" method="POST" enctype="multipart/form-data">
                <div class="col-auto">
                    <label for="p_name" class="col-form-label">ชื่อสินค้า</label>
                    <input type="text" name="p_name" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="t_name">ประเภทสินค้า</label>
                    <select class="form-control" name="t_name" id="t_name">
                        <option value="<?php echo isset($_SESSION['t_name']) ? $_SESSION['t_name'] : ""; ?>" selected disabled>-กรุณาเลือกประเภทสินค้า-</option>
                        <?php foreach ($query as $value) { ?>
                            <option value="<?= $value['t_id'] ?>"><?= $value['t_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="detail" class="col-form-label">รายละเอียด</label>
                    <input type="text" name="detail" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="price" class="col-form-label">ราคา(บาท)</label>
                    <input type="text" name="price" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="amount" class="col-form-label">จำนวนสินค้า</label>
                    <input type="number" name="amount" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="unit" class="col-form-label">หน่วย </label>
                    <select class="form-control" name="unit" id="">
                    <option value="" selected disabled>-กรุณาเลือกหน่วย-</option>
                        <option value="กล่อง">กล่อง</option>
                        <option value="ชิ้น">ชิ้น</option>
                        <option value="กระปุก">กระปุก</option>
                    </select>
                </div>

                <div class="col-auto mt-4" style="display: inline-flex;">
                    <label for="img" class="col-form-label">รูปภาพ </label>
                    <div class="filewrap">
                        <input name="img1" id="imgInput1" class="form-control" type="file" />
                        <img width="100%" id="previewImg1" alt="">
                    </div>
                    <div class="filewrap">
                        <input name="img2" id="imgInput2" class="form-control" type="file" />
                        <img width="100%" id="previewImg2" alt="">
                    </div>
                    <div class="filewrap">
                        <input name="img3" id="imgInput3" class="form-control" type="file" />
                        <img width="100%" id="previewImg3" alt="">
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
        let imgInput2 = document.getElementById('imgInput2');
        let previewIm2 = document.getElementById('previewImg2');
        let imgInput3 = document.getElementById('imgInput3');
        let previewImg3 = document.getElementById('previewImg3');

        imgInput1.onchange = evt => {
            const [file] = imgInput1.files;
            if (file) {
                previewImg1.src = URL.createObjectURL(file);
            }
        }
        imgInput2.onchange = evt => {
            const [file] = imgInput2.files;
            if (file) {
                previewImg2.src = URL.createObjectURL(file);
            }
        }
        imgInput3.onchange = evt => {
            const [file] = imgInput3.files;
            if (file) {
                previewImg3.src = URL.createObjectURL(file);
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