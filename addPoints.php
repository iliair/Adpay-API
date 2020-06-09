<?php

require('include/connections.php');

$username = $_POST['userName'];
$vidpoint = 0;
$userpoint = 0;

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT like_video_points FROM tbl_settings";
$result = $conn ->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $vidpoint = $row['like_video_points'];
    }
}
$sql = "SELECT total_point FROM tbl_users WHERE name = '".$username."' ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $userpoint = $row['total_point'];
    }
}
$newpoint = $userpoint + $vidpoint;


$sql = "INSERT INTO tbl_users (total_point)
VALUES (".$newpoint.")";
$conn->query($sql);


$conn->close();


?>
