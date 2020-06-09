<html>
<head>
  <link rel="stylesheet" type="text/css" href="header.css">
  <link rel="stylesheet" type="text/css" href="videos.css">
</head>



<?php include("includes/header.php");
	include("includes/connection.php");

	require("includes/function.php");
	require("language/language.php");
  $data = array(
    'user_id' => $_POST['user_id'],
    'user_name' => $_POST['user_name'],
  );



  if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}
$insta_qry="SELECT * FROM insta_check";
$insta_result=mysqli_query($mysqli,$insta_qry);
if (mysqli_num_rows($insta_result) > 0) {
  $x =0;
    // output data of each row
    while($row = mysqli_fetch_assoc($insta_result)) {
      echo '<div class="videoitem">';
      echo ' <p class="videotitle">'. $row['user_name'] .'</p>';
      echo  '<button name="insta_submit" type="submit" form="vidform'.$x.'"  class="watchbutton" >تایید</button>';
      echo '<form name="vidform'.$x.'" id="vidform'.$x.'" action="submit_insta.php" method="post">';
      echo '<input id="vidtitle" type="hidden" name="isfromhere" value="true">';
      echo '<input id="user_id" type="hidden" name="user_id" value="'.$row['user_id'].'">';
      echo '<input id="user_name" type="hidden" name="user_name" value="'.$row['user_name'].'">';
      echo '</form>';
      echo '</div>';
      $x = $x + 1;
    }
} else {
    echo "";
}

  ?>

  </html>
