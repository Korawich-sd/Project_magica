<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("location: signin.php");
}

if (isset($_POST['t_name'])) {
    $t_name = $_POST['p_type'];

    if (empty($t_name)) {
        echo "<script>alert('กรุณากรอกชื่อประเภทสินค้า')</script>";
    } else {
        try {
            $check_data = $conn->prepare("SELECT * FROM p_type");
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            if ($row['t_name'] == $t_name) {
                echo "<script>alert('มีประเภทสินค้านี้แล้วในระบบ')</script>";
            } else if (!isset($_SESSION['error'])) {

                $stmt = $conn->prepare("INSERT INTO p_type(t_name) VALUES(:t_name)");
                $stmt->bindParam(":t_name", $t_name);
                $stmt->execute();
                // $_SESSION['success'] = "เพิ่มข้อมูลประเภทสินค้าเรียบร้อย";
                echo "<script>alert('เพิ่มข้อมูลประเภทสินค้าเรียบร้อย')</script>";
                session_destroy();
                // header("refresh:0.0000000001; url=../admin/admin.php");
            } else {
                //$_SESSION['error'] = "มีบางอย่างผิดพลาด";
                echo "<script>alert('มีบางอย่างผิดพลาด')</script>";
                //header("refresh:0.0000000001; url=../admin/admin.php");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
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
    <title>User Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <?php
        if (isset($_SESSION['admin_login'])) {
            $user_id = $_SESSION['admin_login'];
            $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        ?>
        <h3 class="mt-4"> Welcome Admin, <?php echo $row['firstname'] . ' ' . $row['urole'] ?></h3>
        <a href="../logout.php" class="btn btn-danger">Logout</a>

    </div>

    <div class="container">
        <div class="row g-3 align-items-center">
            <form action="../admin/admin.php" method="POST">
                <div class="col-auto">
                    <label for="inputPassword6" class="col-form-label">ประเภทสินค้า</label>
                </div>
                <div class="col-auto">
                    <input type="text" name="p_type" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="submit" name="t_name" class="btn btn-success">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
        <div class="conatiner mt-5">
            <a href="../admin/add_product.php"> <button type="submit" name="t_name" class="btn btn-success">เพิ่มข้อมูลสินค้า</button></a>
        </div>
        <div class="conatiner mt-5">
            <a href="../admin/add_shipping.php"> <button type="submit" name="t_name" class="btn btn-success">เพิ่มข้อมูลบริษัทขนส่ง</button></a>
        </div>
    </div>
</body>

</html>