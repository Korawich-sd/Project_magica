<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require_once '../config/db.php';

echo "<script>
 $(document).ready(function() {
     Swal.fire({
         title: 'success',
         text: 'สั่งซื้อเสร็จสิ้น',
         icon: 'success',
         timer: 10000,
         showConfirmButton: false
     });
 })
</script>";

$order_id = $conn->lastInsertId();

$sql = $conn->prepare("SELECT * FROM orders_detail WHERE orders_detail_id = :order_id");
$sql->bindParam(":order_id", $order_id);
$sql->execute();
$data_id = $sql->fetch(PDO::FETCH_ASSOC);
$last_order_id = $data_id["order_id"];



header("refresh:3; url=dealer_order_detail.php?order_id=$last_order_id");
?>