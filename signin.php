<?php
session_start();
require_once 'config/db.php';
?>


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../magica/asset/css/web_tablet_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../magica/asset/css/mobile_style.css?v=<?php echo time(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - MAGICA</title>
    <link rel="shortcut icon" href="../magica/asset/img/icon-web.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>



<!-- //// Content //// -->
<div class="container-fluid" style="padding: 0px; font-family: 'Kanit', sans-serif ;">
    <div class="login-bg">
        <div class="login-bg-box">
            <a href="../magica/">
                <div class="login-btn-back">
                    <i class="bi bi-caret-left"></i> back
                </div>
            </a>
            <div class="error-msg">
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php  } ?>
                <?php if (isset($_SESSION['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php  } ?>
            </div>
            <div class="container" style="padding-left: 0px; padding-right: 0px;">
                <div class=" row pos">
                    <div class="login-box1" id="login-box1">
                        <img src="/magica/asset/img/img-modal-login.png" id="login-img-box" alt="">
                    </div>
                    <div class="login-box2" id="login-box2">

                        <div class="modal-logo-mgc mt-4 mb-2 ">
                            <img class="mgc-login-logo" src="/magica/asset/img/LogoMGC.png" alt="">
                        </div>

                        <span class="login-text"><b>เข้าสู่ระบบ</b></span>

                        <form action="signin_db.php" method="post">
                            <div class="mb-3 mt-2" style="display: flex; justify-content: center;">
                                <div class="input-group" id="login-email">
                                    <div class="input-group-text" id="input-group-text">
                                        <i class="bi bi-person-circle" id="bi-person-circle"></i>
                                    </div>
                                    <input type="email" id="email" class="form-control" name="email" aria-describedby="email" placeholder="อีเมล" style="border-left: none; padding-left: 3px; border-radius: 0px 25px 25px 0px;">
                                </div>
                            </div>
                            <div class="mb-3 mt-2" style="display: flex; justify-content: center;">
                                <div class="input-group" id="login-email">
                                    <div class="input-group-text" id="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </div>
                                    <input type="password" id="password" class="form-control" name="password" placeholder="รหัสผ่าน" style="border-left: none; padding-left: 3px; border-radius: 0px 25px 25px 0px;">
                                </div>
                            </div>
                            <div class="mb3 login-btn-submit">
                                <button type="submit" id="signin" name="signin" class="btn">เข้าสู่ระบบ</button>
                            </div>
                            <div class="mt-3" id="login-text1">
                                <span id="login-txt1">หากคุณยังไม่มีบัญชีผู้ใช้งาน </span>
                            </div>
                           <div class="mt-2 regis-but">
                               <a href="signup.php">  <button type="button" id="regis-now" style="border: none; background: none;color: #014EB8;">สมัครสมาชิกเพื่อเข้าใช้งาน</button>
                           </a> </div>
                            <div class="mt-2 regis-but">
                                <button type="button" id="regis-now" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="border: none; background: none;color: #014EB8;">สมัครตัวแทนจำหน่าย</button>
                            </div>
                            <!-- <div class="login-text2">
                                <span>หรือ</span>
                            </div> -->
                            <!-- <div style="display: flex; justify-content: center; align-items: center;">
                                <a href=""><img class="login-fb" src="/magica/asset/img/icon-fb.png" alt=""></a>
                                <a href=""><img class="login-gg" src="/magica/asset/img/icon-google.png" alt=""></a>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="padding: 0px; font-family: 'Kanit', sans-serif ;">
    <div class="modal-dialog" id="modal-dialog">
        <div class="modal-content" id="w-modal">
            <div class="modal-header" style="display: flex;position: relative; justify-content: center; text-align: center;">
                <button type="button" data-bs-dismiss="modal" style="border: none; background: none;"><i class="bi bi-arrow-left-circle"></i> </button>
                <h3 class="modal-title" id="staticBackdropLabel"><img class="img-logo" src="../magica/asset/img/LogoMGC.png" alt=""></h3>

            </div>
            <div class="modal-body ">
                <div class="bg-modal">
                    <!-- <div class="bg-user">
                        <a href="../magica/signup.php">
                            <div class="bg-circle">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </a>
                        <p class="user-text">สมาชิกทั่วไป <br>(สำหรับบุคคลทั่วไป)</p>
                    </div>
                    <div class="line-center-modal"></div> -->
                    <div class="bg-derler">
                        <a href="../magica/signup_dealer_general.php">
                            <div class="bg-circle">
                                <i class="bi bi-person-workspace"></i>

                            </div>
                        </a>
                        <p class="dealer-text"> ตัวแทนจำหน่าย <br>(สำหรับบุคคลทั่วไป)</p>
                    </div>
                    <div class="line-center-modal"></div>
                    <div class="bg-derler">
                        <a href="../magica/signup_dealer.php">
                            <div class="bg-circle">
                                <i class="bi bi-person-workspace"></i>
                            </div>
                        </a>
                        <p class="dealer-text-1">ตัวแทนจำหน่าย <br> (สำหรับร้านยา,คลินิค,เภสัชกร)</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button> -->
            </div>
        </div>
    </div>
</div>




<!-- //// Content //// -->

<script src="/magica/action.js"></script>