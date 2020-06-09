<?php include("includes/header.php");
	include("includes/connection.php");
	
	require("includes/function.php");
	require("language/language.php");

  define("VIDEO_ADD_POINTS_STATUS",$settings_details['video_add_status']);
	
	   //Get all videos 
  if(isset($_GET['video_status']))
   {
      
     if($_GET['video_status']==2)
     {
        $data_qry="SELECT tbl_category.category_name,tbl_video.* FROM tbl_video
                  LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
                  WHERE tbl_video.user_id!=0 AND tbl_video.video_layout = 'Landscape'
                  ORDER BY tbl_video.id DESC";
     }
     else if ($_GET['video_status']==3) {
        $data_qry="SELECT tbl_category.category_name,tbl_video.* FROM tbl_video
                  LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
                  WHERE tbl_video.user_id!=0 AND tbl_video.video_layout = 'Portrait'
                  ORDER BY tbl_video.id DESC";
     }
     else
     {
         $data_qry="SELECT tbl_category.category_name,tbl_video.* FROM tbl_video
                  LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
                  WHERE tbl_video.user_id!=0 AND tbl_video.status = '".$_GET['video_status']."'
                  ORDER BY tbl_video.id DESC";    
     } 
             
 
     $result=mysqli_query($mysqli,$data_qry);
   }
	 else if(isset($_POST['data_search']))
   {

      $data_qry="SELECT tbl_category.category_name,tbl_users.name,tbl_video.* FROM tbl_video
                  LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
                  LEFT JOIN tbl_users ON tbl_video.user_id= tbl_users.id 
                  WHERE tbl_video.user_id!=0 AND tbl_video.video_title like '%".addslashes($_POST['search_value'])."%' OR tbl_users.name like '%".addslashes($_POST['search_value'])."%' 
                  ORDER BY tbl_video.id";
 
     $result=mysqli_query($mysqli,$data_qry); 

   }
   else
   {
      $tableName="tbl_video";   
      $targetpage = "manage_users_videos.php"; 
      $limit = 12; 
      
      $query = "SELECT COUNT(*) as num FROM $tableName WHERE user_id!=0";
      $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
      $total_pages = $total_pages['num'];
      
      $stages = 3;
      $page=0;
      if(isset($_GET['page'])){
      $page = mysqli_real_escape_string($mysqli,$_GET['page']);
      }
      if($page){
        $start = ($page - 1) * $limit; 
      }else{
        $start = 0; 
        } 
      
     $video_qry="SELECT tbl_category.category_name,tbl_video.* FROM tbl_video
                  LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
                  WHERE user_id!=0
                  ORDER BY tbl_video.id DESC LIMIT $start, $limit";
 
     
	   $result=mysqli_query($mysqli,$video_qry); 
	 
	 }

  if(isset($_GET['video_id']))
  { 
        
    $img_res=mysqli_query($mysqli,'SELECT * FROM tbl_video WHERE id='.$_GET['video_id'].'');
    $img_res_row=mysqli_fetch_assoc($img_res);
           
    if($img_res_row['video_thumbnail']!="")
     {
          unlink('images/thumbs/'.$img_res_row['video_thumbnail']);
          unlink('images/'.$img_res_row['video_thumbnail']);
          unlink('uploads/'.basename($img_res_row['video_url']));

      }
 
    Delete('tbl_video','id='.$_GET['video_id'].'');
    
    $_SESSION['msg']="12";
    header( "Location:manage_users_videos.php");
    exit;
    
  }


  if(isset($_POST['delete_rec']))
  {

    $checkbox = $_POST['post_ids'];
    
    for($i=0;$i<count($checkbox);$i++){
      
      $del_id = $checkbox[$i]; 
     
      $img_res=mysqli_query($mysqli,'SELECT * FROM tbl_video WHERE id='.$del_id.'');
      $img_res_row=mysqli_fetch_assoc($img_res);
             
      if($img_res_row['video_thumbnail']!="")
       {
            unlink('images/thumbs/'.$img_res_row['video_thumbnail']);
            unlink('images/'.$img_res_row['video_thumbnail']);
            unlink('uploads/'.basename($img_res_row['video_url']));

        }
   
      Delete('tbl_video','id='.$del_id.'');
 
    }

    $_SESSION['msg']="12";
    header( "Location:manage_users_videos.php");
    exit;
  }

  //Active and Deactive status
if(isset($_GET['status_deactive_id']))
{
   $data = array('status'  =>  '0');
  
   $edit_status=Update('tbl_video', $data, "WHERE id = '".$_GET['status_deactive_id']."'");
  
   $_SESSION['msg']="14";
   header( "Location:manage_users_videos.php");
   exit;
}
if(isset($_GET['status_active_id']))
{
    $data = array('status'  =>  '1');
    
    $edit_status=Update('tbl_video', $data, "WHERE id = '".$_GET['status_active_id']."'");

     //User Points
     if(VIDEO_ADD_POINTS_STATUS=='true')
     {

        $qry="SELECT * FROM tbl_video where id='".$_GET['status_active_id']."'";
        $result=mysqli_query($mysqli,$qry);
        $row=mysqli_fetch_assoc($result); 

        $user_id =$row['user_id'];

        $qry1 = "SELECT * FROM tbl_users_rewards_activity WHERE  video_id = '".$_GET['status_active_id']."' and user_id = '".$user_id."'";
        $result1 = mysqli_query($mysqli,$qry1);
        $num_rows1 = mysqli_num_rows($result1); 

         $user_video_id=$_GET['status_active_id'];
         $add_video_point=API_USER_VIDEO_ADD; 

        if ($num_rows1 <= 0)
        {
        
          $qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
          $result2 = mysqli_query($mysqli,$qry2);
          $row2=mysqli_fetch_assoc($result2); 

          $user_total_point=$row2['total_point']+$add_video_point;

          $user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");
         
          user_reward_activity($user_video_id,$user_id,"Add Video",$add_video_point);
        }

    }
    
    $_SESSION['msg']="13";   
    header( "Location:manage_users_videos.php");
    exit;
} 

//Active and Deactive featured
  if(isset($_GET['featured_deactive_id']))
  {
    $data = array('featured'  =>  '0');
    
    $edit_status=Update('tbl_video', $data, "WHERE id = '".$_GET['featured_deactive_id']."'");
    
     $_SESSION['msg']="14";
     header( "Location:manage_users_videos.php");
     exit;
  }
  if(isset($_GET['featured_active_id']))
  {
    $data = array('featured'  =>  '1');
    
    $edit_status=Update('tbl_video', $data, "WHERE id = '".$_GET['featured_active_id']."'");
    
    $_SESSION['msg']="13";
     header( "Location:manage_users_videos.php");
     exit;
  } 


    function get_user_info($post_id)
   {
    global $mysqli;

    $query="SELECT * FROM tbl_users WHERE tbl_users.id='".$post_id."'";
     
    $sql = mysqli_query($mysqli,$query);
    $row=mysqli_fetch_assoc($sql);

    return $row['name'];
   }

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript">
  
$(function() {
    $('#video_status').change(function() {
        this.form.submit();
    });
});

</script>
<div class="row"> 
  <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">مدیریت ویدئو های کاربران</div>
            </div>
            <div class="col-md-7 col-xs-12">
              <div class="search_list">
                <div class="search_block">
                  <form method="post" action="">
                  <input class="form-control input-sm" placeholder="جستجو ..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                  <button type="submit" name="data_search" class="btn-search"><i class="fa fa-search"></i></button>
                  </form>  
                </div>            
              </div>
              <form method="GET" name="myform" action="">
                  <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp;</label>
                    <div class="col-md-6">
                      <select name="video_status" id="video_status" class="select2" required>
                        <option value="">--فیلتر--</option>
                        <option value="0" <?php if(isset($_GET['video_status']) AND $_GET['video_status']==0){?>selected<?php }?>>در انتظار تایید</option>
                        <option value="1" <?php if(isset($_GET['video_status']) AND $_GET['video_status']==1){?>selected<?php }?>>تایید شده</option>
                        <option value="2" <?php if(isset($_GET['video_status']) AND $_GET['video_status']==2){?>selected<?php }?>>ویدئو های افقی</option>
                        <option value="3" <?php if(isset($_GET['video_status']) AND $_GET['video_status']==3){?>selected<?php }?>>ویدئو های عمودی</option>
                          
                      </select>
                    </div>
                  </div>
                  </form>
            </div>            
          </div>		  <div class="col-md-5 col-xs-12" style="margin-bottom: -40px;margin-top: -20px;">       
		  <form method="post" action="">  
		  <div class="page_title">           
		  <div class="checkbox">                
		  <input type="checkbox" name="checkall" id="checkall" value="">    
		  <label for="checkall" style="margin-left:10px;">انتخاب همه</label>     
		  <button type="submit" class="btn btn-primary" name="delete_rec" value="delete_wall" onclick="return confirm('آیا میخواهید موارد انتخابی را حذف کنید؟');">حذف</button>                  </div>               </div>            </div>
          <div class="clearfix"></div>
          <div class="row mrg-top">
            <div class="col-md-12">
               
              <div class="col-md-12 col-sm-12">
                <?php if(isset($_SESSION['msg'])){?> 
               	 <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                	<?php echo $client_lang[$_SESSION['msg']] ; ?></a> </div>
                <?php unset($_SESSION['msg']);}?>	
              </div>
            </div>
          </div>
          <div class="col-md-12 mrg-top">
            <div class="row">
              <?php 
			
            $i=0;
            while($row=mysqli_fetch_array($result))
            {

        ?>
              <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="block_wallpaper">
                  <div class="wall_category_block">
                    <h2><?php echo $row['category_name'];?></h2>  

                    <?php if($row['featured']!="0"){?>
                         <a href="manage_users_videos.php?featured_deactive_id=<?php echo $row['id'];?>" data-toggle="tooltip" data-tooltip="حذف از اسلایدر"><div style="color:green;"><i class="fa fa-check-circle"></i></div></a> 
                      <?php }else{?>
                         <a href="manage_users_videos.php?featured_active_id=<?php echo $row['id'];?>" data-toggle="tooltip" data-tooltip="افزودن به اسلایدر"><i class="fa fa-circle"></i></a> 
                      <?php }?>

                      <div class="checkbox" style="float: right;">
                          <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $row['id']; ?>">
                          <label for="checkbox<?php echo $i;?>">
                          </label>
                        </div>

                  </div>
                  <div class="wall_image_title">
                     <p style="font-size: 16px;"><?php echo $row['video_title'];?></p>

                     <p>ارسال شده توسط <a href="manage_user_history.php?user_id=<?php echo $row['user_id'];?>" data-toggle="tooltip" data-tooltip="تاریخچه کاربر" style="color: #fff;"><?php echo get_user_info($row['user_id']);?></a></p>
                    <ul>
                      <li><a href="video_preview.php?video_id=<?php echo $row['id'];?>" target="_blank" data-toggle="tooltip" data-tooltip="مشاهده ویدئو"><i class="fa fa-video-camera"></i></a></li>

                      <li><a href="javascript:void(0)" data-toggle="tooltip" data-tooltip="<?php echo $row['totel_viewer'];?> مشاهده"><i class="fa fa-eye"></i></a></li>                      
                       

                      <li><a href="edit_video.php?video_id=<?php echo $row['id'];?>" data-toggle="tooltip" data-tooltip="ویرایش"><i class="fa fa-edit"></i></a></li>
                      <li><a href="?video_id=<?php echo $row['id'];?>" data-toggle="tooltip" data-tooltip="حذف" onclick="return confirm('آیا میخواهید این مورد را حذف کنید؟');"><i class="fa fa-trash"></i></a></li>

                      <?php if($row['status']!="0"){?>
                       <li><div class="row toggle_btn"><a href="manage_users_videos.php?status_deactive_id=<?php echo $row['id'];?>" data-toggle="tooltip" data-tooltip="غیر فعال کردن"><img src="assets/images/btn_enabled.png" alt="wallpaper_1" /></a></div></li> 

                      <?php }else{?>
                      
                       <li><div class="row toggle_btn"><a href="manage_users_videos.php?status_active_id=<?php echo $row['id'];?>" data-toggle="tooltip" data-tooltip="فعال کردن"><img src="assets/images/btn_disabled.png" alt="wallpaper_1" /></a></div></li> 
                  
                      <?php }?>  

                    </ul>
                  </div>
				  
                  			<span><?php if($row['video_thumbnail']!=""){?>
						<img src="images/<?php echo $row['video_thumbnail'];?>" /></span>                     
						 <?php }else{?>
						<img src="images/default_img.jpg" /></span>                     
                      <?php }?>  
               	 </div>
					</div>
          <?php
            
            $i++;
              }
        ?>     
         
       
      </div>
          </div>
           <div class="col-md-12 col-xs-12">
            <div class="pagination_item_block">
              <nav>
                <?php if(!isset($_POST["data_search"])){ include("pagination.php");}?>               
              </nav>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
	  </div>    
        
<?php include("includes/footer.php");?>       
