<?php include("includes/connection.php");
  

  $qry="SELECT * FROM tbl_video where id='".$_GET['video_id']."'";
  $result=mysqli_query($mysqli,$qry);
  $row=mysqli_fetch_assoc($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row['video_title']?></title>
</head>

<body style="text-align:center;background:#f9f9f9">
  <h3 style="font-size:28px;color:#000;font-family:'Poppins', sans-serif;margin:15px 0"><?php echo $row['video_title']?></h3>
  <iframe width="800" height="530" style="border:0" src="<?php echo $row['video_url']?>"></iframe> 
</body>
</html>
