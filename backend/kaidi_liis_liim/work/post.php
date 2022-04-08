<?php
$servername = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "mydb";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
};


$sql = "CALL insertIntoMultipleTables (?, ?)";
    
if (!$stmt = $conn->prepare($sql))
    die('Query failed: (' . $conn->errno . ') ' . $conn->error);

    if (!$stmt->bind_param('ss',$_POST['tree_name'],$_POST['fruit_name']))
    die('Bind Param failed: (' . $conn->errno . ') ' . $conn->error);

    if (!$stmt->execute())
        die('Insert Error ' . $conn->error);

    echo "Record added";
 $stmt->close();
 $conn->close();

?>