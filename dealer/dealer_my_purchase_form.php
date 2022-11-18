<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once "../config/db.php";

if (isset($_SESSION['dealer_login'])) {
    $user_id = $_SESSION['dealer_login'];
    $stmt = $conn->query("SELECT * FROM dealer WHERE id = $user_id");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

$order = $conn->prepare("SELECT * FROM orders WHERE cus_id = $user_id");
$order->execute();
$row_order = $order->fetchAll();


?>

<link rel="stylesheet" href="../asset/css/web_tablet_style.css?v=<?php echo time(); ?>">

<div class="p-form-box">
    <div class="top-title">
        <div class="text-title">
            <span id="txt-my-pur">การซื้อของฉัน</span>
        </div>

    </div>
    <div class="line-cut-bottom"></div>
    <div class="content-mypurchase">
        <?php
        foreach (array_reverse($row_order) as $row_order) { ?>
            <div class="bg-mypurchase">
                <div class="my-pur-order-id">
                    <span>เลขที่คำสั่งซื้อ <?= $row_order["order_Id"] ?></span>
                </div>
                <div class="bg-sub-mypurchase">
                    <?php
                    $order_detail = $conn->prepare("SELECT * FROM orders_detail WHERE order_id = :order_id");
                    $order_detail->bindParam(":order_id", $row_order['order_Id']);
                    $order_detail->execute();
                    $row_order_detail = $order_detail->fetchAll();

                    ?>
                    <table class="table table-bordered table-order-style">
                        <thead>

                            <tr>
                                <th scope="col">รูปภาพ</th>
                                <th scope="col">ชื่อสินค้า</th>
                                <th scope="col">จำนวน</th>
                                <th scope="col">ราคา</th>

                            </tr>
                        </thead>
                        <?php
                        for ($i = 0; $i < count($row_order_detail); $i++) {  ?>
                            <tbody class="align-middle txt-order-mypur ">
                                <tr>
                                    <th class="tab-center">
                                        <?php
                                        // $aaa[] = $row_order_detail[$i]["p_name"];
                                        $p_img = $conn->prepare("SELECT * FROM product WHERE p_name = :p_name");
                                        $p_img->bindParam(":p_name", $row_order_detail[$i]["p_name"]);
                                        $p_img->execute();
                                        $row_p_img[] = $p_img->fetchAll();
                                        ?>

                                        <img width="60px" src="../asset/upload/product/<?= $row_order_detail[$i]["p_img"] ?>" alt="">
                                    </th>
                                    <td><?= $row_order_detail[$i]["p_name"] ?></td>
                                    <td class="tab-center"><?= $row_order_detail[$i]["qty"] ?></td>
                                    <td class="tab-center">฿<?= number_format($row_order_detail[$i]["p_price"], 2)  ?></td>
                                </tr>
                            </tbody>

                        <?php  }
                        ?>

                    </table>

                </div>
                <div class="detail-mypur">
                    <div class="txt-detail-mypur">
                        <span>ยอดคำสั่งซื้อทั้งหมด : ฿<?= number_format($row_order["total_price"],2)  ?></span>
                        <span>เลขพัสดุ : <?= $row_order["parcel_code"] ?></span>
                        <span>รูปแบบการชำระเงิน : <?= $row_order["payment_method"] ?></span>
                        <span>จัดส่งโดย : <?= $row_order["shipping_company"] ?></span>
                        <span>สถานะคำสั่งซื้อ : <?php
                                                if ($row_order["status_order"] == 1) {
                                                    echo "<span class='txt-status1-order-mypur'>รออนุมัติคำสั่งซื้อ</span>";
                                                } else if ($row_order["status_order"] == 2) {
                                                    echo "<span class='txt-status2-order-mypur'>อนุมัติคำสั่งซื้อแล้ว</span>";
                                                }
                                                ?> </span>
                    </div>
                    <div class="btn-detail-mypur">
                        <!-- <button class="btn-sumorder-mypur" type="button" data-bs-toggle="modal" data-bs-target="#sum-order-mypur">สรุปคำสั่งซื้อ</button> -->
                        <a href="https://maayoung.com/" target="_blank" rel="noopener noreferrer"><button class="btn-sumorder-mypur" type="button">ติดตามสถานะการจัดส่งที่นี่</button></a>
                    </div>
                </div>
            </div>


        <?php }
        ?>
    </div>
</div>