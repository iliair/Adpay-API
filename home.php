<?php include("includes/header.php");

$qry_cat="SELECT COUNT(*) as num FROM tbl_category";
$total_category= mysqli_fetch_array(mysqli_query($mysqli,$qry_cat));
$total_category = $total_category['num'];

$qry_video="SELECT COUNT(*) as num FROM tbl_video";
$total_video = mysqli_fetch_array(mysqli_query($mysqli,$qry_video));
$total_video = $total_video['num'];


$qry_users="SELECT COUNT(*) as num FROM tbl_users WHERE id!=0";
$total_users = mysqli_fetch_array(mysqli_query($mysqli,$qry_users));
$total_users = $total_users['num'];

$qry_comments="SELECT COUNT(*) as num FROM tbl_comments";
$total_comments = mysqli_fetch_array(mysqli_query($mysqli,$qry_comments));
$total_comments = $total_comments['num'];


$qry="SELECT * FROM tbl_settings where id='1'";
  $result=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result);


$qry_users_paid="SELECT SUM(redeem_price) AS num FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users_redeem.status = 1";
$total_paid = mysqli_fetch_array(mysqli_query($mysqli,$qry_users_paid));
$total_paid = $total_paid['num'];

$qry_users_pending="SELECT SUM(redeem_price) AS num FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users_redeem.status = 0";
$total_pending = mysqli_fetch_array(mysqli_query($mysqli,$qry_users_pending));
$total_pending = $total_pending['num'];
 

?>       



    <div class="row">
 
      <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_category.php" class="card card-banner card-green-light">
        <div class="card-body"> <i class="icon fa fa-sitemap fa-4x"></i>
          <div class="content">
            <div class="title">دسته بندی ها</div>
            <div class="value"><span class="sign"></span><?php echo $total_category;?></div>
          </div>
        </div>
        </a> 
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_videos.php" class="card card-banner card-alicerose-light">
        <div class="card-body"> <i class="icon fa fa-film fa-4x"></i>
          <div class="content">
            <div class="title">ویدئو ها</div>
            <div class="value"><span class="sign"></span><?php echo $total_video;?></div>
          </div>
        </div>
        </a> 
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_users.php" class="card card-banner card-blue-light">
        <div class="card-body"> <i class="icon fa fa-users fa-4x"></i>
          <div class="content">
            <div class="title">کاربران</div>
            <div class="value"><span class="sign"></span><?php echo $total_users;?></div>
          </div>
        </div>
        </a> </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mr_bot60"> 
          <a href="manage_comments.php" class="card card-banner card-pink-light">
        <div class="card-body"> <i class="icon fa fa-comments fa-4x"></i>
          <div class="content">
            <div class="title">نظرات</div>
            <div class="value"><span class="sign"></span><?php echo $total_comments;?></div>
          </div>
        </div>
        </a> 
    </div> 

    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mr_bot60"> 
          <a href="manage_transaction.php" class="card card-banner card-orange-light">
        <div class="card-body"> <i class="icon fa fa-list fa-4x"></i>
          <div class="content">
            <div class="title">پرداخت شده</div>
            <div class="value"><span class="sign"></span><?php echo $total_paid ? $total_paid : '0';?> <span class="sign"><?php echo $settings_row['redeem_currency'];?></span></div>
          </div>
        </div>
        </a> 
    </div>

    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mr_bot60"> 
          <a href="manage_transaction.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fa fa-list fa-4x"></i>
          <div class="content">
            <div class="title">در انتظار</div>
            <div class="value"><span class="sign"></span><?php echo $total_pending ? $total_pending : '0';?> <span class="sign"><?php echo $settings_row['redeem_currency'];?></span></div>
          </div>
        </div>
        </a> 
    </div> 
     
    </div>

        
<?php include("includes/footer.php");?>       
