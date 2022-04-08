<?php

$servername = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "mydb";

 $conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
};

if( $_GET["fruit_name"]) {
    
    $fruit_name = $_GET["fruit_name"];
    $sql = "SELECT `fruit_name` FROM `fruit` WHERE `fruit_name`='$fruit_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo $row['fruit_name'];
      }
    }
    else {
      echo '0 results';
    }
    $conn->close();
    exit();
 }
?>