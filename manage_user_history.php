<?php include('includes/header.php'); 
	include("includes/connection.php");
	
    include("includes/function.php");
	include("language/language.php"); 
 
	 	 $users_res=mysqli_query($mysqli,'SELECT * FROM tbl_users WHERE id='.$_GET['user_id'].'');
	   $users_res_row=mysqli_fetch_assoc($users_res);
			 
		 $users_rewards_qry="SELECT * FROM tbl_users_rewards_activity
		 LEFT JOIN tbl_users ON tbl_users_rewards_activity.user_id= tbl_users.id
		 WHERE tbl_users_rewards_activity.status=1 AND tbl_users_rewards_activity.user_id='".$_GET['user_id']."'
		 ORDER BY tbl_users_rewards_activity.id DESC";  
			 
		$users_rewards_result=mysqli_query($mysqli,$users_rewards_qry);
	


  function get_video_info($video_id,$field_name) 
   {
    global $mysqli;

    $qry_video="SELECT * FROM tbl_video WHERE id='".$video_id."' AND status='1'";
    $query1=mysqli_query($mysqli,$qry_video);
    $row_video = mysqli_fetch_array($query1);

    $num_rows1 = mysqli_num_rows($query1);
    
            if ($num_rows1 > 0)
        {     
        return $row_video[$field_name];
      }
      else
      {
        return "";
      }
   }		


$settings_qry="SELECT * FROM tbl_settings where id='1'";
  $settings_result=mysqli_query($mysqli,$settings_qry);
  $settings_row=mysqli_fetch_assoc($settings_result);

$qry_video="SELECT COUNT(*) as num FROM tbl_video WHERE user_id='".$_GET['user_id']."'";
$total_video = mysqli_fetch_array(mysqli_query($mysqli,$qry_video));
$total_video = $total_video['num'];


$qry_users_paid="SELECT SUM(redeem_price) AS num FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users_redeem.user_id='".$_GET['user_id']."' AND tbl_users_redeem.status = 1";
$total_paid = mysqli_fetch_array(mysqli_query($mysqli,$qry_users_paid));
$total_paid = $total_paid['num'];

$qry_users_pending="SELECT SUM(redeem_price) AS num FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users_redeem.user_id='".$_GET['user_id']."' AND tbl_users_redeem.status = 0";
$total_pending = mysqli_fetch_array(mysqli_query($mysqli,$qry_users_pending));
$total_pending = $total_pending['num'];				



  if(isset($_POST['submit']) and isset($_POST['user_id']))
  { 

    $qry = "SELECT * FROM tbl_users WHERE id = '".$_POST['user_id']."'"; 
    $result = mysqli_query($mysqli,$qry);    
    $row = mysqli_fetch_assoc($result);

    if($_FILES['user_image']['name']!="")
        { 
          $file_name= str_replace(" ","-",$_FILES['user_image']['name']);
          $user_image=rand(0,99999)."_".$file_name;
       
           //Main Image
           $tpath1='images/'.$user_image;       
           $pic1=compress_image($_FILES["user_image"]["tmp_name"], $tpath1, 100);
        }   
        else
        {
          $user_image=$row['user_image'];
        }
      
    if($_POST['password']!="")
    {
      $data = array(
      'name'  =>  $_POST['name'],
      'email'  =>  $_POST['email'],
      'password'  =>  $_POST['password'],
      'phone'  =>  $_POST['phone'],
      'user_youtube'  =>  $_POST['user_youtube'],
      'user_instagram'  =>  $_POST['user_instagram'],
      'user_image'  =>  $user_image
      );
    }
    else
    {
      $data = array(
      'name'  =>  $_POST['name'],
      'email'  =>  $_POST['email'],      
      'phone'  =>  $_POST['phone'],
      'user_youtube'  =>  $_POST['user_youtube'],
      'user_instagram'  =>  $_POST['user_instagram'],
      'user_image'  =>  $user_image
      );
    }
 
    
       $user_edit=Update('tbl_users', $data, "WHERE id = '".$_POST['user_id']."'");
      
        $_SESSION['msg']="11";
        header("Location:manage_user_history.php?user_id=".$_POST['user_id']);
        exit;
        
  }

	 
?>
 

<div class="row">
    <div class="col-xs-12 mr_bottom20">
    <div class="card mr_bottom20 mr_top10">
      <div class="page_title_block user_dashboard_item" style="background-color: #333;">
      <div class="user_dashboard_mr_bottom">
        <div class="col-md-10 col-xs-12"> <br>
          <span class="badge badge-success badge-icon">
          <div class="user_profile_img">
            
            <?php if(isset($_GET['user_id']) and $users_res_row['user_image']!="") {?>
           <img type="image" src="images/<?php echo $users_res_row['user_image'];?>" alt="image" />
            <?php }else{?>  
            <img type="image" src="images/user_photo.png" alt="image" />
           <?php } ?>            

          </div>
          <span style="font-size: 16px;"><?php echo $users_res_row['name'];?></span>
          </span> 
          <span class="badge badge-success badge-icon">
          <i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
          <span style="font-size: 16px;"><?php echo $users_res_row['email'];?></span>
          </span> 
          <br><br>
        </div>
        <div class="col-md-2 col-xs-12">
          <div class="search_list">
          <div class="add_btn_primary"> <a href="manage_users.php">برگشت</a> </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="manage_user_history_video.php?user_id=<?php echo $users_res_row['id'];?>" class="card card-banner card-alicerose-light">
        <div class="card-body"> <i class="icon fa fa-film fa-4x"></i>
        <div class="content">
          <div class="title">جمع ویدئو ها</div>
          <div class="value"><span class="sign"></span><?php echo $total_video;?></div>
        </div>
        </div>
        </a> 
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="manage_user_history_pending_points.php?user_id=<?php echo $users_res_row['id'];?>" class="card card-banner card-orange-light">
        <div class="card-body"> <i class="icon fa fa-clock-o fa-4x"></i>
        <div class="content">
          <div class="title">امتیازات در انتظار</div>
          <div class="value"><span class="sign"></span><?php echo $users_res_row['total_point'];?></div>
        </div>
        </div>
        </a> 
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mr_bot60"> <a href="javascript::void();" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fa fa-money fa-4x"></i>
        <div class="content">
          <div class="title">مبلغ در انتظار</div>
          <div class="value"><span class="sign"></span><?php echo $total_pending ? $total_pending : '0';?><span class="sign"><?php echo $settings_row['redeem_currency'];?></span></div>
        </div>
        </div>
        </a> 
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mr_bot60"> <a href="javascript::void();" class="card card-banner card-blue-light">
        <div class="card-body"> <i class="icon fa fa-money fa-4x"></i>
        <div class="content">
          <div class="title">جمع پرداختی</div>
          <div class="value"><span class="sign"></span><?php echo $total_paid ? $total_paid : '0';?><span class="sign"><?php echo $settings_row['redeem_currency'];?></span></div>
        </div>
        </div>
        </a> 
      </div>  
      </div>
      <div class="user_dashboard_info">
      <ul>
        <li><a href="manage_user_history.php?user_id=<?php echo $users_res_row['id'];?>" style="color: #e91e63;">ویرایش اطلاعات</a></li>
        <li><a href="manage_user_history_followers.php?user_id=<?php echo $users_res_row['id'];?>"><?php echo $users_res_row['total_followers'];?> دنبال کننده</a></li>
        <li><a href="manage_user_history_followings.php?user_id=<?php echo $users_res_row['id'];?>"><?php echo $users_res_row['total_following'];?> دنبال شونده</a></li>        
        <li><a href="manage_user_history_withdrawal.php?user_id=<?php echo $users_res_row['id'];?>">درخواست ها</a></li>
        <li><a href="manage_user_history_total_points.php?user_id=<?php echo $users_res_row['id'];?>">تاریخچه تمام امتیازات</a></li>
      </ul>
      </div>
    </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title">ویرایش اطلاعات</div>
        </div>
        </div>
        <div class="clearfix"></div>
        <div class="row mrg-top">
        <div class="col-md-12">
          <div class="col-md-12 col-sm-12"> </div>
        </div>
        </div>
        <div class="card-body mrg_bottom">
        <form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data" >
              <input  type="hidden" name="user_id" value="<?php echo $_GET['user_id'];?>" />

              <div class="section">
                <div class="section-body">
        
        
                  <div class="form-group">
                    <label class="col-md-3 control-label">نام :</label>
                    <div class="col-md-6">
                      <input type="text" name="name" id="name" value="<?php if(isset($_GET['user_id'])){echo $users_res_row['name'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">ایمیل :</label>
                    <div class="col-md-6">
                      <input type="email" name="email" id="email" value="<?php if(isset($_GET['user_id'])){echo $users_res_row['email'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">رمز عبور :</label>
                    <div class="col-md-6">
                      <input type="password" name="password" id="password" value="" class="form-control" <?php if(!isset($_GET['user_id'])){?>required<?php }?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">شماره تلفن :</label>
                    <div class="col-md-6">
                      <input type="text" name="phone" id="phone" value="<?php if(isset($_GET['user_id'])){echo $users_res_row['phone'];}?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">لینک یوتیوب :</label>
                    <div class="col-md-6">
                      <input type="text" name="user_youtube" id="user_youtube" value="<?php if(isset($_GET['user_id'])){echo $users_res_row['user_youtube'];}?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">لینک اینستاگرام :</label>
                    <div class="col-md-6">
                      <input type="text" name="user_instagram" id="user_instagram" value="<?php if(isset($_GET['user_id'])){echo $users_res_row['user_instagram'];}?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">تصویر کاربر :
                      <p class="control-label-help">(رزولوشن توصیه شده: W: 400 * H: 200)</p>
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="user_image" value="fileupload" id="fileupload">
                            
                            <?php if(isset($_GET['user_id']) and $users_res_row['user_image']!="") {?>
                            <div class="fileupload_img"><img type="image" src="images/<?php echo $users_res_row['user_image'];?>" alt="image" style="width: 100px;height: 90px;"/></div> 
                            <?php }else{?>  
                            <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="image" /></div>
                           <?php } ?>
                      </div>
                    </div>
                  </div>
                   
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary">ذخیره</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
        </div>
      </div>
    </div>    
  </div> 



<?php include('includes/footer.php');?>                  