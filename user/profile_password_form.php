<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once "../config/db.php";

if (isset($_SESSION['user_login'])) {
    $user_id = $_SESSION['user_login'];
    $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['submit_password'])) {
    $id = $row['id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $c_new_password = $_POST['c_new_password'];

    if (empty($old_password)) {
        $_SESSION['error_pass'] = 'กรุณากรอกรหัสผ่านเดิม';
        header("location: profile_password.php");
    } else if (empty($new_password)) {
        $_SESSION['error_pass'] = 'กรุณากรอกรหัสผ่านใหม่';
        header("location: profile_password.php");
    } else if (empty($c_new_password)) {
        $_SESSION['error_pass'] = 'กรุณายืนยันรหัสผ่านใหม่';
        header("location: profile_password.php");
    } else if (strlen($_POST['new_password']) > 20 || strlen($_POST['new_password']) < 8) {
        $_SESSION['error_pass'] = 'รหัสผ่านต้องมีความยาวระหว่าง 8-20 ตัวอักษร';
        header("location: profile_password.php");
    } else if ($new_password != $c_new_password) {

        $_SESSION['error_pass'] = 'รหัสผ่านใหม่ไม่ตรงกัน';
        header("location: profile_password.php");
    } else {
        try {
            $check_password = $conn->prepare("SELECT password FROM users WHERE id = :id");
            $check_password->bindParam(":id", $id);
            $check_password->execute();
            $row = $check_password->fetch(PDO::FETCH_ASSOC);

            if (!isset($_SESSION['error_pass'])) {
                if (password_verify($old_password, $row['password'])) {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $sql = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
                    $sql->bindParam(":id", $id);
                    $sql->bindParam(":password", $password_hash);
                    $sql->execute();

                    if ($sql) {
                        echo "<script>alert('เปลี่ยนรหัสผ่านเสร็จสิ้น')</script>";
                        header("refresh:0.0000000001; url=profile_password.php");
                    }
                } else {
                    echo "<script>alert('รหัสผ่านเดิมไม่ตรงกัน')</script>";
                    header("refresh:0.0000000001; url=profile_password.php");
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}


?>

<div class="p-form-box">
    <div class="top-title">
        <div class="text-title">
            <span>เปลี่ยนรหัสผ่าน</span>
        </div>

    </div>
    <div class="line-cut-bottom"></div>
    <div class="content">
        <form action="profile_password_form.php" method="post">
            <div class="form-group">
                <div class="content-form">
                    <div class="one">
                        <div class="mm">
                            <label for="old_password" class="form-label" style="margin-bottom: 0px;">รหัสผ่านเดิม</label>
                            <input type="password" class="form-control" name="old_password" aria-describedby="old_password">
                        </div>
                        <div class="mm">
                            <label for="new_password" class="form-label" style="margin-bottom: 0px;">รหัสผ่านใหม่</label>
                            <input type="password" class="form-control" name="new_password" aria-describedby="new_password">
                        </div>
                        <div class="mm">
                            <label for="c_new_password" class="form-label" style="margin-bottom: 0px;">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" class="form-control" name="c_new_password">
                        </div>
                        <div class="btn-submit">
                            <button type="submit" name="submit_password" class="btn" id="btn-save">บันทึก</button>
                        </div>
                    </div>
                    <div class="two">
                        <p><span style="color:red ;"> ข้อแนะนำในการตั้งรหัสผ่าน*** </span><br>
                            1.รหัสผ่านต้องมีความยาว 8 ตัวอักษรขึ้นไป <br>
                            2.รหัสผ่านควรมีตัวเลข ตัวอักษรหรืออักขระพิเศษ เช่น MGC01@2022 เป็นต้น <br>
                            3.ไม่ควรตั้งรหัสผ่านซ้ำกับรหัสผ่านที่เคยใช้ <br>
                            4.ไม่ควรตั้งรหัสผ่านเป็นข้อมูลส่วนตัว เช่น วันเกิด รหัสบัตรประชาชน เป็นต้น
                        </p>

                    </div>
                </div>


            </div>
        </form>

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
        /* background-color: #aaa; */
        margin: 0 auto;
    }

    .text-title {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        padding: 10px;
    }

    .line-cut-center {
        width: 2px;
        height: 20px;
        background-color: #ccc;
        margin-left: 4px;
        margin-right: 4px;
    }

    .line-cut-bottom {
        width: 90%;
        height: 2px;
        margin: 0px 40px 0px 40px;
        background-color: #ccc;
    }

    span {
        font-size: 18px;
    }

    .content-form {
        display: flex;
        justify-content: flex-start;
        margin-left: 40px;
        margin-top: 20px;
    }

    .btn-submit {
        display: flex;
        justify-content: flex-start;
    }

    .mm {
        margin-right: 60px;
    }

    #btn-save {
        width: 120px;
        height: 40px;
        background-color: #1979FE;
        color: white;
        margin-left: 0px;
        margin-top: 20px;
        box-shadow: none;
    }

    .two {
        width: 50%;
        height: 300px;
        padding: 5px;
        /* position: absolute; */
        /* top: 48%; */
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
            /* background-color: #aaa; */
            margin: 0 auto;
        }

        .text-title {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding: 0px;
        }

        .line-cut-center {
            width: 2px;
            height: 20px;
            background-color: #ccc;
            margin-left: 4px;
            margin-right: 4px;
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

        .content-form {
            display: block;
            justify-content: flex-start;
            padding: 20px;
            margin: 0 auto;
        }

        .btn-submit {
            display: flex;
            justify-content: flex-start;
        }

        .mm {
            margin-right: 60px;
        }

        #btn-save {
            width: 120px;
            height: 40px;
            background-color: #1979FE;
            color: white;
            margin-left: 0px;
            margin-top: 20px;
            margin-bottom: 10px;
            box-shadow: none;
        }

        .two {
            width: 100%;
            height: 300px;
            /* position: absolute; */
            /* top: 48%; */
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
            /* background-color: #aaa; */
            margin: 0 auto;
        }

        .text-title {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding: 10px;
        }

        .line-cut-center {
            width: 2px;
            height: 20px;
            background-color: #ccc;
            margin-left: 4px;
            margin-right: 4px;
        }

        .line-cut-bottom {
            width: 90%;
            height: 2px;
            margin: 0px 40px 0px 40px;
            background-color: #ccc;
        }

        span {
            font-size: 18px;
        }

        .content-form {
            display: flex;
            justify-content: flex-start;
            margin-left: 40px;
            margin-top: 20px;
        }

        .btn-submit {
            display: flex;
            justify-content: flex-start;
        }

        .mm {
            margin-right: 60px;
        }

        #btn-save {
            width: 120px;
            height: 40px;
            background-color: #1979FE;
            color: white;
            margin-left: 0px;
            margin-top: 20px;
            box-shadow: none;
        }

        .two {
            width: 300px;
            height: 300px;
            position: absolute;
            right: 200px;
            top: 28%;
        }
    }
</style>