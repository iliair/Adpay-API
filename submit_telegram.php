<?php
require("includes/function.php");
require("language/language.php");
include("includes/connection.php");
$user_id = $_POST['user_id'];
$at = "عضویت در کانال تلگرام";
$points = $settings_details['telegram_points'];
mysqli_query($mysqli,"UPDATE tbl_users SET total_point= total_point + '".$points."'  WHERE id = '".$user_id."'");
user_reward_activity(0,$user_id,$at,$settings_details['telegram_points']);
$telegram_qrycheck="DELETE FROM telegram_check WHERE user_id=".$user_id."";
mysqli_query($mysqli,$telegram_qrycheck);
header("location: telegram_check.php");

?>
