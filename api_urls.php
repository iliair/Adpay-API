<?php include("includes/header.php");

    if( isset($_SERVER['HTTPS'] ) ) {  

    $file_path = 'https://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/api.php';
  }
  else
  {
    $file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/api.php';
  }
    
   
?>
<div class="row">
      <div class="col-sm-12 col-xs-12">
     	 	<div class="card">
		        <div class="card-header">
		          Example API urls
		        </div>
       			    <div class="card-body no-padding">
         		
         			 <pre><code class="html">
                <br><b>API URL</b> <?php echo $file_path;?>

                <br><b>Home Videos</b> (Method: home_videos)(Parameter: user_id)
                <br><b>Landscape Videos</b> (Method: landscape_videos)(Parameter: user_id,page)
                <br><b>Portrait Videos</b> (Method: portrait_videos)(Parameter: user_id,page)
                <br><b>All videos</b> (Method: all_videos)(Parameter: user_id,page,filter_value)(Filter: Landscape OR Portrait)
                <br><b>Category List</b> (Method: cat_list)
                <br><b>Videos list by Cat ID</b> (Method: video_by_cat_id)(Parameter: cat_id,user_id,page,filter_value)(Filter: Landscape OR Portrait)
                <br><b>Single Video</b> (Method: single_video)(Parameter: video_id,user_id)
                <br><b>Single Video View Count</b> (Method: single_video_view_count)(Parameter: video_id,user_id)
                <br><b>Single Video Download</b> (Method: single_video_download)(Parameter: video_id,user_id)
                <br><b>Search Video</b> (Method: search_video)(Parameter: search_text,user_id,page,filter_value)(Filter: Landscape OR Portrait)
                <br><b>User Email Verify</b> (Method: user_register_verify_email)(Parameter: email,otp_code)
                <br><b>User Register</b> (Method: user_register)(Parameter: name,email,password,phone,user_refrence_code)
                <br><b>User Login</b> (Method: user_login)(Parameter: email,password)
                <br><b>User Profile</b> (Method: user_profile)(Parameter: user_id)
                <br><b>User Profile Update</b> (Method: user_profile_update)(Parameter: user_id,name,email,password,phone,user_image,user_youtube,user_instagram)
                <br><b>Other User Profile</b> (Method: other_user_profile)(Parameter: other_user_id,user_id)
                <br><b>User Status</b> (Method: user_status)(Parameter: user_id)
                <br><b>Forgot Password</b> (Method: forgot_pass)(Parameter: email)
                <br><b>User Rewards Points</b> (Method: user_rewads_point)(Parameter: user_id)
                <br><b>User Redeem Points History</b> (Method: user_redeem_points_history)(Parameter: user_id,redeem_id)
                <br><b>User Redeem Request</b> (Method: user_redeem_request)(Parameter: user_id,user_points,payment_mode,bank_details)
                <br><b>User Redeem History</b> (Method: user_redeem_history)(Parameter: user_id)
                <br><b>User Video Upload</b>(File:api_video_upload.php) (Method: user_video_upload)(Post Parameter: user_id,cat_id,video_title,video_duration,video_description,video_local,video_layout,video_thumbnail)
                <br><b>User Video List</b> (Method: user_video_list)(Parameter: user_id,login_user,page,filter_value)(Filter: Landscape OR Portrait , login_user: true OR false)
                <br><b>User Video Delete</b> (Method: user_video_delete)(Parameter: user_id,video_id)
                <br><b>User Video Like</b> (Method: user_video_like)(Parameter: like,device_id,post_id)
                <br><b>User Video Comment</b> (Method: user_video_comment)(Parameter: comment_text,user_id,post_id)
                <br><b>User Follow</b> (Method: user_follow)(Parameter: user_id,follower_id)
                <br><b>User Contact Us</b> (Method: user_contact_us)(Parameter: contact_email,contact_name,contact_msg)
                <br><b>Report Video</b> (Method: video_report)(Parameter: report_user_id,report_email,report_video_id,report_type,report_text)
                <br><b>App Details</b> (Method: app_settings)   
 
                 
       				</div>
          	</div>
        </div>
</div>
    <br/>
    <div class="clearfix"></div>
        
<?php include("includes/footer.php");?>       
