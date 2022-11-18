<!-- CSS only -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous"> -->
<!-- JavaScript Bundle with Popper -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script> -->
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

    $txt_card = $conn->query("SELECT * FROM credit WHERE user_id = $user_id");
    $txt_card->execute();
    $row1 =  $txt_card->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['submit_card'])) {
    $id = $row['id'];
    $credit_number = $_POST['cradit_number'];
    $expiry_date =  $_POST['expiry_date'];
    $cvv = $_POST['cvv'];
    $card_name =  $_POST['card_name'];


    if (empty($credit_number)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน')</script>";
        header("refresh:0.0000000000001; url=profile_bank.php");
    } else if (empty($expiry_date)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน')</script>";
        header("refresh:0.0000000000001; url=profile_bank.php");
    } else if (empty($cvv)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน')</script>";
        header("refresh:0.0000000001; url=profile_bank.php");
    } else if (empty($card_name)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน')</script>";
        header("refresh:0.0000000001; url=profile_bank.php");
    } else {
        try {
            if (!isset($_SESSION['error'])) {
                $sql = $conn->prepare("INSERT INTO credit(credit_number, expiry_date, cvv, card_name, user_id)
                                     VALUES(:credit_number, :expiry_date, :cvv, :card_name, :user_id)");
                $sql->bindParam(":credit_number", $credit_number);
                $sql->bindParam(":expiry_date", $expiry_date);
                $sql->bindParam(":cvv", $cvv);
                $sql->bindParam(":card_name", $card_name);
                $sql->bindParam(":user_id", $id);
                $sql->execute();

                if ($sql) {
                    echo "<script>alert('บันทึกข้อมูลเสร็จสิ้น')</script>";
                    header("refresh:0.0000000001; url=profile_bank.php");
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
        <div class="text-title ">
            <span>บัญชีธนาคาร</span>
            <button type="submit" name="update" class="btn" id="c_address" data-bs-toggle="modal" data-bs-target="#userModal">เพิ่มบัตรเครดิต</button>
        </div>

    </div>
    <div class="line-cut-bottom"></div>
    <div class="content">
        <span class="address-text">Visa <?php echo $row1['credit_number'] ?? ''; ?></span>
    </div>
</div>

<div class="modal  fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">บัตรเครดิต</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <img class="img-logo" src="../asset/img/LogoMGC.png" alt=""> -->

                <div class="bg-bank">
                    <div class="text-title-card">
                        <p class="card-credit"><i class="bi bi-credit-card"></i>เพิ่มบัตรเครดิต</p>
                    </div>
                    <form class="s-form" action="profile_bank_form.php" method="POST">
                        <div class="one">
                            <div class="mm">
                                <label for="cradit_number" class="form-label" style="margin-bottom: 0px;">หมายเลขบัตร</label>
                                <input type="text" class="form-control"  inputmode="numeric" pattern="[0-9\s]{13,19}"  maxlength="19" placeholder="xxxx xxxx xxxx xxxx" name="cradit_number" id="cradit_number" aria-describedby="cradit_number">
                            </div>
                        </div>
                        <div class="two">
                            <div class="mm">
                                <label for="expiry_date" class="form-label" style="margin-bottom: 0px;">วันหมดอายุ (ดด/ปป)</label>
                                <input type="tel" id="expiry_date" class="form-control"  placeholder="MM/YY" minlength="5" maxlength="5" name="expiry_date" aria-describedby="expiry_date">
                            </div>
                            <div class="mm">
                                <label for="cvv" class="form-label" style="margin-bottom: 0px;">cvv</label>
                                <input type="text" class="form-control" minlength="3" maxlength="3" placeholder="000" id="cvv" name="cvv" aria-describedby="cvv">
                            </div>
                        </div>
                        <div class="one">
                            <div class="mm">
                                <label for="card_name" class="form-label" style="margin-bottom: 0px;">ชื่อบนบัตร</label>
                                <input type="text" class="form-control" placeholder="ชื่อบนบัตร" id="card_name" name="card_name" aria-describedby="card_name">
                            </div>
                        </div>
                        <div class="btn-submit">
                            <button type="submit" name="submit_card" class="btn" id="btn-save">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .p-form-box {
        background-color: white;
        display: block;
        visibility: hidden;
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

    .modal {
        position: absolute;
        float: left;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .modal-content {
        background-color: white;
        display: flex;
        justify-content: center;
    }

    .modal-body {
        width: 100%;
        display: block;
        justify-content: center;
        align-items: center;
        text-align: center;
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

    .bg-bank {
        width: 400px;
        height: 320px;
        background-color: #E1EDFF;
        margin: 0 auto;
        padding: 15px;
        display: block;
        justify-content: center;
        align-items:center;
        text-align: left;
        border-radius: 10px 10px 10px 10px;
    }

    .text-title-card {
        width: 150px;
        height: 30px;
        background-color: white;
        border: 2px solid #1979FE;
        border-radius: 5px 5px 5px 5px;
        display: flex;
        justify-content: flex-start;
        align-items: center;

    }

    .card-credit {
        margin: 0px;
        padding: 0px;
        display: flex; 
        justify-content: flex-start; 
        align-items: center; 
    }

    .bi-credit-card {
        font-size: 25px;
        margin-left: 5px;
        margin-right: 5px;
        color: orange;
    }

    .s-form {
        display: block;
        justify-content: center;
        align-items: center;
        text-align: left;
        padding: 20px;
        margin: 0 auto;
    }

    .one {
        width: 70%;
        display: block;
    }

    .two {
        display: flex;
    }

    #expiry_date {
        width: 150px;
    }
    #cvv{
        width: 150px;
    }
    #cradit_number{
        width: 330px;
    }
    #card_name{
        width: 330px;
    }
    #btn-save{
        margin-top: 10px;
        width: 120px;
        height: 35px;
        background-color: #248C00;
        color: white;
        box-shadow: none;
    }
</style>