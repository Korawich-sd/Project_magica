
<?php
session_start();
require_once 'config/db.php';


if (isset($_POST['signin'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $_SESSION['error'] = 'กรุณากรอกอีเมล';
        header("location: signin.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
        header("location: signin.php");
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
        header("location: signin.php");
    } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 8) {
        $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5-20 ตัวอักษร';
        header("location: signin.php");
    } else {
        try {
            $check_data = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $check_data->bindParam(":email", $email);
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            $check_data_dealer_general = $conn->prepare("SELECT * FROM dealer_general WHERE email = :email");
            $check_data_dealer_general->bindParam(":email", $email);
            $check_data_dealer_general->execute();
            $row1 = $check_data_dealer_general->fetch(PDO::FETCH_ASSOC);

            $check_data_dealer = $conn->prepare("SELECT * FROM dealer WHERE email = :email");
            $check_data_dealer->bindParam(":email", $email);
            $check_data_dealer->execute();
            $row2 = $check_data_dealer->fetch(PDO::FETCH_ASSOC);

            if ($check_data->rowCount() > 0 || $check_data_dealer_general->rowCount() > 0 || $check_data_dealer->rowCount() > 0) {

                if ($email == $row['email'] || $email == $row1['email'] || $email == $row2['email']) {
                    if (password_verify($password, $row['password']) || password_verify($password, $row1['password']) || password_verify($password, $row2['password'])) {
                        if ($row['urole'] == 'admin') {
                            $_SESSION['admin_login'] = $row['id'];
                            header("location: ../magica/admin/admin.php");
                        } else if ($row1['urole'] == 'dealer_general') {
                            $_SESSION['dealer_general_login'] = $row1['id'];
                            header("location: dealer_general/home.php");
                        } else if ($row2['urole'] == 'dealer') {
                            $_SESSION['dealer_login'] = $row2['id'];
                            header("location: dealer/home.php");
                        } else {
                            $_SESSION['user_login'] = $row['id'];
                            header("location: user/home.php");
                        }
                    } else {
                        $_SESSION['error'] = 'รหัสผ่านไม่ถูกต้อง';
                        header("location: signin.php");
                    }
                } else {
                    $_SESSION['error'] = 'อีเมลไม่ถูกต้อง';
                    header("location: signin.php");
                }
            } else {
                $_SESSION['error'] = "ไม่มีข้อมูลในระบบ";
                header("location: signin.php");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
