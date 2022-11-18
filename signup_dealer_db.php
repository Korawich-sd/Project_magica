<!-- ไม่ใช้ไฟล์นี้แล้ว -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<?php
session_start();
error_reporting(0);
require_once 'config/db.php';

if (isset($_POST['signup_dealer'])) {
    $_SESSION['firstname'] = $_POST['firstname'];
    $_SESSION['lastname'] = $_POST['lastname'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['tel'] = $_POST['tel'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['c_password'] = $_POST['c_password'];
    $_SESSION['line_Id'] = $_POST['line_Id'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['districts'] = $_POST['districts'];
    $_SESSION['amphures'] = $_POST['amphures'];
    $_SESSION['provinces'] = $_POST['provinces'];
    $_SESSION['zip_code'] = $_POST['zip_code'];
    $_SESSION['Org_name'] = $_POST['Org_name'];
    $_SESSION['license_type'] = $_POST['license_type'];
    $_SESSION['Id_card'] = $_POST['Id_card'];

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    $line_Id = $_POST['line_Id'];
    $addressed =  $_POST['address'];
    $districts = $_POST['districts'];
    $amphures =  $_POST['amphures'];
    $provinces = $_POST['provinces'];
    $zip_code = $_POST['zip_code'];
    $address = $_POST['address'] . ' ' . $_POST['districts'] . ' ' . $_POST['amphures'] . ' ' . $_POST['provinces'] . ' ' . $_POST['zip_code'];
    $position = $_POST['position'];
    $urole = 'dealer';
    $Org_name = $_POST['Org_name'];
    $license_type = $_POST['license_type'];
    $Id_card = $_POST['Id_card'];
    $license_number = $_POST['license_number'];
    $cb = $_POST['cb'];


    if (empty($firstname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อ';
        header("location: signup_dealer.php");
    } else if (empty($lastname)) {
        $_SESSION['error'] = 'กรุณากรอกนามสกุล';
        header("location: signup_dealer.php");
    } else if (empty($email)) {
        $_SESSION['error'] = 'กรุณากรอกอีเมล';
        header("location: signup_dealer.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
        header("location: signup_dealer.php");
    } else if (strlen($_POST['tel']) < 10) {
        $_SESSION['error'] = 'กรุณาหมายเลขโทรศัพท์ให้ถูกต้อง';
        header("location: signup_dealer.php");
    } else if (strlen($_POST['tel']) > 10) {
        $_SESSION['error'] = 'กรุณาหมายเลขโทรศัพท์ให้ถูกต้อง';
        header("location: signup_dealer.php");
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
        header("location: signup_dealer.php");
    } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 8) {
        $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 8-20 ตัวอักษร';
        header("location: signup_dealer.php");
    } else if (empty($c_password)) {
        $_SESSION['error'] = 'กรุณายืนยันรหัสผ่าน';
        header("location: signup_dealer.php");
    } else if ($password != $c_password) {
        $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
        header("location: signup_dealer.php");
    } else if (empty($line_Id)) {
        $_SESSION['error'] = 'กรุณากรอกไลน์ไอดี';
        header("location: signup_dealer.php");
    } else if (empty($addressed)) {
        $_SESSION['error'] = 'กรุณากรอกที่อยู่';
        header("location: signup_dealer.php");
    } else if (empty($provinces)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลที่อยู่ให้ครบถ้วน';
        header("location: signup_dealer.php");
    } else if (empty($amphures)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลที่อยู่ให้ครบถ้วน';
        header("location: signup_dealer.php");
    } else if (empty($districts)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลที่อยู่ให้ครบถ้วน';
        header("location: signup_dealer.php");
    } else if (empty($position)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลที่อยู่ให้ครบถ้วน';
        header("location: signup_dealer.php");
    }else if (empty($Org_name)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อสถานประกอบการ';
        header("location: signup_dealer.php");
    }else if (empty($license_type)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลประเภทใบอนุญาต';
        header("location: signup_dealer.php");
    }else if (empty($license_number)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลเลขที่ใบอนุญาต';
        header("location: signup_dealer.php");
    }else if (empty($Id_card)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูลที่อยู่ให้ครบถ้วน';
        header("location: signup_dealer.php");
    }
     else if (empty($cb)) {
        $_SESSION['error'] = 'กรุณายอมรับ ข้อกำหนดและเงื่อนไข';
        header("location: signup_dealer.php");
    } else {
        try {
            $check_email = $conn->prepare("SELECT email FROM dealer WHERE email = :email");
            $check_email->bindParam(":email", $email);
            $check_email->execute();
            $row = $check_email->fetch(PDO::FETCH_ASSOC);
            //var_dump($row);

            if ($row['email'] == $email) {
                $_SESSION['warning'] = "มีอีเมลนี้อยู่ในระบบแล้ว <a href='signin.php'>คลิกที่นี่เพื่อเข้าสู่ระบบ</a> ";
                // echo var_dump($row);
                header("location: signup_dealer.php");
            } else if (!isset($_SESSION['error'])) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO dealer(firstname, lastname, email, tel, password, line_Id, address, Org_name, license_type, Id_card, license_number ,urole,level)
                                            VALUES(:firstname, :lastname, :email, :tel, :password, :line_Id, :address, :Org_name, :license_type, :Id_card, :license_number , :urole, :level)");
                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":tel", $tel);
                $stmt->bindParam(":password", $passwordHash);
                $stmt->bindParam(":line_Id", $line_Id);
                $stmt->bindParam(":address", $address);
                $stmt->bindParam(":Org_name", $Org_name);
                $stmt->bindParam(":license_type", $license_type);
                $stmt->bindParam(":Id_card", $Id_card);
                $stmt->bindParam(":license_number", $license_number);
                $stmt->bindParam(":urole", $urole);
                $stmt->bindParam(":level", $position);
                $stmt->execute();
              //  $_SESSION['success'] = "สมัครสมาชิกเรียบร้อยแล้ว! <a href='signin.php' class='alert-link'>คลิกที่นี่</a> เพื่อเข้าสู่ระบบ";

                if ($stmt) {
                    echo "<script>
                    $(document).ready(function() {
                        Swal.fire({
                            text: 'สมัครสมาชิกเรียบร้อยแล้ว',
                            icon: 'success',
                            timer: 15000,
                            showConfirmButton: false
                        });
                    })
                    </script>";
                    session_destroy();
                    header("refresh:3; url=signin.php");
                } else {
                    echo "<script>
                    $(document).ready(function() {
                        Swal.fire({
                            text: 'มีบางอย่างผิดพลาด',
                            icon: 'error',
                            timer: 15000,
                            showConfirmButton: false
                        });
                    })
                    </script>";
                }
            }
            else {
                $_SESSION['error'] = "มีบางอย่างผิดพลาด";
                header("location: signup_dealer.php");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
