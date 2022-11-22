<!-- <link rel="stylesheet" href="../asset/css/web_tablet_style.css"> -->
<link rel="stylesheet" href="../asset/css/web_tablet_style.css?v=<?php echo time(); ?>">
<div class="flex-shrink-0  bg-white" >
        <div class="p-bg-menu" style="padding-left: 0px; padding-right: 0px;">
            <div class="p-sub-bg-menu">
            <img class="p-profile-icon" src="../asset/upload/profile_user/<?php if ($row['m_img'] == null) {echo "profile-icon.png";
                                                            } else {
                                                                echo $row['m_img'];
                                                            }
                                                            ?>" alt="">
                <p class="p-name-menu"><?php echo $row['firstname'] ?> </p>
                <p class="p-sub-name-menu"><i class="bi bi-pencil-fill"></i> แก้ไขข้อมูลส่วนตัว</p>

                <ul class="list-unstyled ps-0">
                    <li class="mb-1">
                        <button class="btn btn-toggle " id="p-btn-color" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            บัญชีของฉัน
                        </button>
                        <div class="collapse show" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small sidebar-flip">
                                <li><a href="../dealer/dealer_profile.php" id="p-list-menu">
                                        <p id="p-list-menu">ข้อมูลของฉัน</p>
                                    </a></li>
                                <li ><a href="../dealer/dealer_profile_bank.php" id="p-list-menu"> 
                                        <p id="p-list-menu">บัญชีธนาคาร</p>
                                    </a></li>
                                <li ><a href="../dealer/dealer_profile_password.php" id="p-list-menu">
                                        <p id="p-list-menu">เปลี่ยนรหัสผ่าน</p>
                                    </a></li>
                                <li ><a href="../dealer/dealer_profile_address.php" id="p-list-menu">
                                        <p id="p-list-menu">ที่อยู่</p>
                                    </a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mb-1">
                       <a href="../dealer/dealer_my_purchase.php"><button class="btn btn-toggle" id="p-btn-color" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                            การซื้อของฉัน
                        </button></a>
                        
                    </li>
                    <li class="border-top my-3"></li>
                </ul>
            </div>
        </div>
    </div>