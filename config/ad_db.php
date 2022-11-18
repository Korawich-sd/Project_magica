<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magica";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}



if (isset($_POST['function']) && $_POST['function'] == 'provinces') {
  // echo $_POST['name_th'];
  $name_th = $_POST['name_th'];
  $sql = $conn->query("SELECT * FROM provinces WHERE name_th = '$name_th'");
  $sql->execute();
  $query = $sql->fetch(PDO::FETCH_ASSOC);
  $province_id = $query['id'];
  //echo $province_id;

  $sql_amphures = $conn->query("SELECT * FROM amphures WHERE province_id='$province_id'");
  $sql_amphures->execute();
  $query_amphures = $sql_amphures->fetchAll();

  echo '<option value="" selected disabled>-กรุณาเลือกอำเภอ-</option>';
  foreach ($query_amphures as $value) {
    echo '<option value="' . $value['name_th'] . '">' . $value['name_th'] . '</option>';
  }
}


if (isset($_POST['function']) && $_POST['function'] == 'amphures') {
  // echo $_POST['name_th'];
  $name_th = $_POST['name_th'];
  $sql_amphures = $conn->query("SELECT * FROM amphures WHERE name_th = '$name_th'");
  $sql_amphures->execute();
  $query_amphures = $sql_amphures->fetch(PDO::FETCH_ASSOC);
  $amphures_id = $query_amphures['id'];
  echo $amphures_id;

  $sql_districts = $conn->query("SELECT * FROM districts WHERE amphure_id='$amphures_id'");
  $sql_districts->execute();
  $query_districts = $sql_districts->fetchAll();

  echo '<option value="" selected disabled>-กรุณาเลือกอำเภอ-</option>';
  foreach ($query_districts as $value) {
    echo '<option value="' . $value['name_th'] . '">' . $value['name_th'] . '</option>';
  }
}

if (isset($_POST['function']) && $_POST['function'] == 'districts') {
  // echo $_POST['name_th'];
  $name_th = $_POST['name_th'];
  $sql_districts = $conn->query("SELECT * FROM districts WHERE name_th = '$name_th'");
  $sql_districts->execute();
  $query_districts = $sql_districts->fetch(PDO::FETCH_ASSOC);
  $id = $query_districts['id'];
  //echo $districts_id;

  $sql_zip_code = $conn->query("SELECT * FROM districts WHERE id='$id'");
  $sql_zip_code->execute();
  $query_zip_code = $sql_zip_code->fetch(PDO::FETCH_ASSOC);
  echo $query_zip_code['zip_code'];
}


// if (isset($_POST['function']) && $_POST['function'] == 'districts') {
//   $name_th = $_POST['name_th'];
//   $sql = $conn->query("SELECT * FROM districts WHERE namee_th='$name_th'");
//   $sql->execute();
//   $query3 = $sql->fetch(PDO::FETCH_ASSOC);
//   echo $query3['zip_code'];
//   exit();
// }


//////////

// if (isset($_POST['function']) && $_POST['function'] == 'amphures') {
//   $name_th = $_POST['name_th'];
//   $sql_amphures = $conn->query("SELECT * FROM amphures WHERE name_th='$name_th'");
//   $sql_amphures->execute();
//   $query = $sql_amphures->fetchAll();

//   echo '<option value="" selected disabled>-กรุณาเลือกตำบล-</option>';
//   foreach ($query as $value2) {
//     echo '<option value="' . $value2['id'] . '">' . $value2['name_th'] . '</option>';
//   }
// }







////// ส่งค่าไปแสดง ///////

// if (isset($_POST['function']) && $_POST['function'] == 'districts') {
//   $id = $_POST['id'];
//   $sql = $conn->query("SELECT * FROM districts WHERE id='$id'");
//   $sql->execute();
//   $query3 = $sql->fetch(PDO::FETCH_ASSOC);
//   echo $query3['name_th'];
//   exit();
// }

// if (isset($_POST['function']) && $_POST['function'] == 'districts1') {
//   $id = $_POST['id'];
//   $sql = $conn->query("SELECT * FROM districts WHERE id='$id'");
//   $sql->execute();
//   $query3 = $sql->fetch(PDO::FETCH_ASSOC);
//   echo $query3['zip_code'];
//   exit();
// }

// if (isset($_POST['function']) && $_POST['function'] == 'val_amphures') {
//   $id = $_POST['id'];
//   $sql = $conn->query("SELECT * FROM amphures WHERE id='$id'");
//   $sql->execute();
//   $query3 = $sql->fetch(PDO::FETCH_ASSOC);
//   echo $query3['name_th'];
//   exit();
// }

// if (isset($_POST['function']) && $_POST['function'] == 'val_province') {
//   $id = $_POST['id'];
//   $sql = $conn->query("SELECT * FROM provinces WHERE id='$id'");
//   $sql->execute();
//   $query3 = $sql->fetch(PDO::FETCH_ASSOC);
//   echo $query3['name_th'];
//   exit();
// }
