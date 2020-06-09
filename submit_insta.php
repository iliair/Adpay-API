<?php
require("includes/function.php");
require("language/language.php");
include("includes/connection.php");
$user_id = $_POST['user_id'];
$at = "عضویت در کانال اینستا";
$points = $settings_details['insta_points'];
mysqli_query($mysqli,"UPDATE tbl_users SET total_point= total_point + '".$points."'  WHERE id = '".$user_id."'");
user_reward_activity(0,$user_id,$at,$settings_details['insta_points']);
$insta_qrycheck="DELETE FROM insta_check WHERE user_id=".$user_id."";
mysqli_query($mysqli,$insta_qrycheck);
header("location: insta_check.php");

?>
