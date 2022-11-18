<!-- CSS only
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
JavaScript Bundle with Popper -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script> -->
<?php
error_reporting(0);
if (!isset($_SESSION)) {
    session_start();
}
require_once "../config/db.php";

if (isset($_SESSION['dealer_login'])) {
    $user_id = $_SESSION['dealer_login'];
    $stmt = $conn->prepare("SELECT * FROM dealer WHERE id = $user_id");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['submit'])) {
    $id = $row['id'];
    $address = $_POST['address'] . ' ' . $_POST['districts'] . ' ' . $_POST['amphures'] . ' ' . $_POST['provinces'] . ' ' . $_POST['zip_code'];
    $addressed =  $_POST['address'];
    $districts = $_POST['districts'];
    $amphures =  $_POST['amphures'];
    $provinces = $_POST['provinces'];
    $zip_code = $_POST['zip_code'];

    if (empty($addressed)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน')</script>";
        header("refresh:0.0000000000001; url=dealer_profile_address.php");
    } else if (empty($provinces)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน')</script>";
        header("refresh:0.0000000000001; url=dealer_profile_address.php");
    } else if (empty($amphures)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน')</script>";
        header("refresh:0.0000000001; url=dealer_profile_address.php");
    } else if (empty($districts)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน')</script>";
        header("refresh:0.0000000001; url=dealer_profile_address.php");
    } else {
        try {
            if (!isset($_SESSION['error'])) {
                $sql = $conn->prepare("UPDATE dealer SET address1 = :address1 WHERE id = :id");
                $sql->bindParam(":id", $id);
                $sql->bindParam(":address1", $address);
                $sql->execute();

                if ($sql) {
                    // echo "<script>alert('แก้ไขข้อมูลเสร็จสิ้น')</script>";
                    header("refresh:1; url=dealer_profile_address.php");
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}


?>

<?php
$sql_provinces = $conn->query("SELECT * FROM provinces");
$sql_provinces->execute();
$query = $sql_provinces->fetchAll();
?>

<div class="p-form-box">
    <div class="top-title">
        <div class="text-title">
            <span>ที่อยู่</span>
            <button type="submit" name="update" class="btn" id="c_address" data-bs-toggle="modal" data-bs-target="#userModal">เพิ่มที่อยู่ใหม่</button>
        </div>

    </div>
    <div class="line-cut-bottom"></div>
    <div class="content">
        <p class="address-text"><?php echo $row['address']; ?></p>
        <p class="address-text"><?php echo $row['address1']; ?></p>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-pin-map-fill"></i>ที่อยู่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="box-1">

                        <label for="address" class="form-label" id="label-address" style="margin-bottom: 0px;">ที่อยู่</label>
                        <input type="text" class="form-control" name="address" id="address" aria-describedby="address">

                    </div>
                    <div class="box-2">
                        <div class="container address-form">
                            <div class="m4">
                                <label for="sel1">จังหวัด</label>
                                <select class="form-control" name="provinces" id="provinces">
                                    <option value="<?php echo isset($_SESSION['provinces']) ? $_SESSION['provinces'] : ""; ?>" selected disabled>-กรุณาเลือกจังหวัด-</option>
                                    <?php foreach ($query as $value) { ?>
                                        <option value="<?= $value['name_th'] ?>"><?= $value['name_th'] ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                            <div class="m4">
                                <label for="sel1">อำเภอ</label>
                                <select class="form-control" name="amphures" id="amphures">
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="box-3">
                        <div class="container address-form">
                            <div class="m4">
                                <label for="sel1">ตำบล</label>
                                <select class="form-control" name="districts" id="districts">
                                </select>
                            </div>
                            <div class="m4">
                                <label for="sel1">รหัสไปรษณีย์</label>
                                <input type="text" name="zip_code" id="zip_code" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="box-4">
                        <div class="container address-form">
                            <button type="submit" class="btn" name="submit" id="c_address">ยืนยัน</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    .p-form-box {
        background-color: white;
        width: 90%;
        height: 700px;
        margin-top: 120px;
        box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.1);
    }

    .top-title {
        width: 100%;
        height: 60px;
        margin: 0 auto;
        padding: 20px 10px 0px 10px;
        margin-left: 20px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .content {
        width: 100%;
        height: 30px;
        margin: 0 auto;
        padding-top: 20px;
    }

    .text-title {
        width: 100%;
        display: flex;

        justify-content: space-between;
        align-items: center;
        padding: 10px;
    }

    .line-cut-bottom {
        width: 90%;
        height: 2px;
        margin-left: 40px;
        margin-top: 5px;
        background-color: #ccc;
    }

    span {
        font-size: 18px;
    }

    #c_address {
        width: 150px;
        height: 35px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #1979FE;
        color: white;
        margin-right: 6%;
        box-shadow: 2px 2px 8px 1px rgba(0, 0, 0, 0.2);
    }

    .address-text {
        margin-left: 40px;
        margin-bottom: 0px;
    }

    .bi-pin-map-fill {
        color: red;
        font-size: 30px;
        margin-left: 5px;
        margin-right: 5px;
    }

    .modal-content {

        background-color: #DEF2FF;
        display: flex;
        justify-content: center;
    }

    .modal-body {
        width: 100%;
        display: flex;
    }

    .modal-dialog {
        position: absolute;
        left: 25%;
        top: 20%;
        /* right: 50%; */

    }

    #address {
        width: 400px;
    }

    #label-address {
        padding-left: 0px;
    }

    .box-4 {
        margin-top: 20px;
    }

    .address-form {
        display: flex;
    }

    @media screen and (max-width: 576px) {
        .p-form-box {
            background-color: white;
            width: 100%;
            height: 700px;
            margin-top: 120px;
            box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.1);
        }

        .top-title {
            width: 100%;
            height: 60px;
            margin: 0 auto;
            padding: 0px 0px 0px 20px;
            margin-left: 0px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .content {
            width: 100%;
            height: 30px;
            margin: 0 auto;
            padding-top: 20px;
        }

        .text-title {
            width: 100%;
            display: flex;

            justify-content: space-between;
            align-items: center;
            padding: 0px;
        }

        .line-cut-bottom {
            width: 90%;
            height: 2px;
            margin: 0 auto;
            background-color: #ccc;
        }

        span {
            font-size: 18px;
        }

        #c_address {
            width: 150px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1979FE;
            color: white;
            /* right: 0px; */
            /* position: absolute; */
            box-shadow: 2px 2px 8px 1px rgba(0, 0, 0, 0.2);
        }

        .address-text {
            margin-left: 20px;

        }

        .bi-pin-map-fill {
            color: red;
            font-size: 30px;
            margin-left: 5px;
            margin-right: 5px;
        }

        .modal-content {

            background-color: #DEF2FF;
            display: flex;
            justify-content: center;
        }

        .modal-body {
            width: 100%;
            display: flex;
        }

        .modal-dialog {
            width: 90%;
            height: fit-content;
            position: absolute;
            top: 20%;
            left: 3%;
        }

        #address {
            width: 100%;
           
        }

        #label-address {
            padding-left: 0px;
        }

        .box-4 {
            margin-top: 20px;
        }
    }

    @media screen and (min-width: 1200px) {
        .p-form-box {
            background-color: white;
            width: 90%;
            height: 700px;
            margin-top: 120px;
            box-shadow: 2px 2px 8px 4px rgba(0, 0, 0, 0.1);
        }

        .top-title {
            width: 100%;
            height: 60px;
            margin: 0 auto;
            padding: 20px 10px 0px 10px;
            margin-left: 20px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .content {
            width: 100%;
            height: 30px;
            margin: 0 auto;
            padding-top: 20px;
        }

        .text-title {
            width: 100%;
            display: flex;

            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        .line-cut-bottom {
            width: 90%;
            height: 2px;
            margin-left: 40px;
            margin-top: 5px;
            background-color: #ccc;
        }

        span {
            font-size: 18px;
        }

        #c_address {
            width: 150px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1979FE;
            color: white;
            margin-right: 6%;
            box-shadow: 2px 2px 8px 1px rgba(0, 0, 0, 0.2);
        }

        .address-text {
            margin-left: 40px;

        }

        .bi-pin-map-fill {
            color: red;
            font-size: 30px;
            margin-left: 5px;
            margin-right: 5px;
        }

        .modal-content {

            background-color: #DEF2FF;
            display: flex;
            justify-content: center;
        }

        .modal-body {
            width: 100%;
            display: flex;
        }

        .modal-dialog {
            position: absolute;
            left: 40%;
            /* right: 50%; */

        }

        #address {
            width: 400px;
        }

        #label-address {
            padding-left: 0px;
        }

        .box-4 {
            margin-top: 20px;
        }
    }
</style>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
    $('#provinces').change(function() {
        var name_th = $(this).val();
        //  console.log(name_th);
        $.ajax({
            type: "POST",
            url: '../config/ad_db.php',
            data: {
                name_th: name_th,
                function: 'provinces'
            },
            success: function(data) {
                // console.log(data);
                $('#amphures').html(data);
                $('#districts').html(' ');
                $('#zip_code').val(' ');

            }
        });
    });

    $('#amphures').change(function() {
        var name_th = $(this).val();
        console.log(name_th);
        $.ajax({
            type: "POST",
            url: '../config/ad_db.php',
            data: {
                name_th: name_th,
                function: 'amphures'
            },
            success: function(data) {
                // console.log(data);
                $('#districts').html(data);
                $('#zip_code').val(' ');
            }
        });

    });

    $('#districts').change(function() {
        var name_th = $(this).val();
        // console.log(name_th);
        $.ajax({
            type: "POST",
            url: '../config/ad_db.php',
            data: {
                name_th: name_th,
                function: 'districts'
            },
            success: function(data) {
                console.log(data);
                $('#zip_code').val(data);

            }
        });

    });
</script>