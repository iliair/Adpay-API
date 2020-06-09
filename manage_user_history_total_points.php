<?php include('includes/header.php'); 
	include("includes/connection.php");
	
    include("includes/function.php");
	include("language/language.php"); 
  	 include("includes/jdf.php"); 
	 	 $users_res=mysqli_query($mysqli,'SELECT * FROM tbl_users WHERE id='.$_GET['user_id'].'');
	   $users_res_row=mysqli_fetch_assoc($users_res);
			 
		 $users_rewards_qry="SELECT * FROM tbl_users_rewards_activity
		 LEFT JOIN tbl_users ON tbl_users_rewards_activity.user_id= tbl_users.id
		 WHERE tbl_users_rewards_activity.user_id='".$_GET['user_id']."'
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
        <li><a href="manage_user_history.php?user_id=<?php echo $users_res_row['id'];?>">ویرایش اطلاعات</a></li>
        <li><a href="manage_user_history_followers.php?user_id=<?php echo $users_res_row['id'];?>"><?php echo $users_res_row['total_followers'];?> دنبال کننده</a></li>
        <li><a href="manage_user_history_followings.php?user_id=<?php echo $users_res_row['id'];?>"><?php echo $users_res_row['total_following'];?> دنبال شونده</a></li>        
        <li><a href="manage_user_history_withdrawal.php?user_id=<?php echo $users_res_row['id'];?>">درخواست ها</a></li>
        <li><a href="manage_user_history_total_points.php?user_id=<?php echo $users_res_row['id'];?>" style="color: #e91e63;">تاریخچه تمام امتیازات</a></li>
      </ul>
      </div>
    </div>
    </div>
        <div class="col-xs-12">
      <div class="card">
        <div class="card-header">
        تاریخچه تمام امتیازات
        </div>
        <div class="card-body no-padding">
          <table class="datatable table table-striped primary" cellspacing="0" width="100%">
    <thead>
        <tr>
            <tr>
              <th>عنوان ویدئو</th>             
              <th>نوع فعالیت</th>
              <th>امتیاز</th>
              <th>تاریخ</th>  
           </tr>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=0;
        while($users_row=mysqli_fetch_array($users_rewards_result))
        {
         
    ?>
        <tr>
           <td><?php echo get_video_info($users_row['video_id'],'video_title');?></td>
           <td><?php echo $users_row['activity_type'];?></td>   
           <td><?php echo $users_row['points'];?></td>                
           <td>
               <span class="badge badge-danger badge-icon"><i class="fa fa-clock-o" aria-hidden="true"></i><span><?php echo jdate('Y/m/d', strtotime($users_row['date'])).' - '.jdate('h:i A', strtotime($users_row['date']));?> </span></span>
          </td>
           
        </tr>
       <?php
        
        $i++;
        }
     ?>
         
    </tbody>
</table>
        </div>
      </div>
    </div>    
  </div> 



<?php include('includes/footer.php');?>                  