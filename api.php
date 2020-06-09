<?php include("includes/connection.php");
 	  include("includes/function.php");
 	  include("smtp_email.php");

	 include("includes/jdf.php");

    if( isset($_SERVER['HTTPS'] ) ) {

		$file_path = 'https://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';
	}
	else
	{
		$file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';
	}


    define("DOWNLOAD_VIDEO_POINTS",$settings_details['download_video_points']);
    define("REGISTRATION_REWARD_POINTS_STATUS",$settings_details['registration_reward_status']);
    define("APP_REFER_REWARD_POINTS_STATUS",$settings_details['app_refer_reward_status']);
    define("VIDEO_VIEW_POINTS_STATUS",$settings_details['video_views_status']);
    define("VIDEO_ADD_POINTS_STATUS",$settings_details['video_add_status']);
    define("DOWNLOAD_POINTS_STATUS",$settings_details['download_video_points_status']);
    define("MAX_DUP_POINTS",$settings_details['max_dup_points']);
    define("MAX_LIKE_ROUNDS",$settings_details['max_like_rounds']);
    define("MAX_TAPSELL_WATCH",$settings_details['max_tapsell_watch']);
    define("WATCH_ADMOB_POINTS_STATUS",$settings_details['watch_admob_points_status']);
    define("WATCH_TAPSELL_POINTS_STATUS",$settings_details['watch_tapsell_points_status']);
    define("WATCH_ADMOB_POINTS",$settings_details['watch_admob_points']);
    define("WATCH_TAPSELL_POINTS",$settings_details['watch_tapsell_points']);
    define("MAX_DUP_POINTS",$settings_details['max_dup_points']);
    define("MAX_VIDEO_VIEW",$settings_details['max_video_view']);
    define("MAX_ADMOB_WATCH",$settings_details['max_admob_watch']);
    define("INTERVAL",$settings_details['interval_minutes']);





	function createRandomCode()
	 {
		 $chars = "abcdefghijkmnopqrstuvwxyz023456789";
		 srand((double)microtime()*1000000);
		 $i = 0;
		 $pass = '' ;
		 while ($i <= 7)
		 {
		 $num = rand() % 33;
		 $tmp = substr($chars, $num, 1);
		 $pass = $pass . $tmp;
		 $i++;
		 }
		  return $pass;
	  }

	 function cat_video_count($cat_id)
	 {
	 	global $mysqli;

	 	$qry_video="SELECT COUNT(*) as num FROM tbl_video WHERE cat_id='".$cat_id."' AND status='1'";
		$total_video = mysqli_fetch_array(mysqli_query($mysqli,$qry_video));
		$total_video = $total_video['num'];

		return $total_video;
	 }

	 function user_video_count($user_id)
	 {
	 	global $mysqli;

	 	$qry_video="SELECT COUNT(*) as num FROM tbl_video WHERE user_id='".$user_id."'";
		$total_video = mysqli_fetch_array(mysqli_query($mysqli,$qry_video));
		$total_video = $total_video['num'];

		return $total_video;
	 }

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

	 function get_user_info($user_id,$field_name)
	 {
	 	global $mysqli;

	 	$qry_user="SELECT * FROM tbl_users WHERE id='".$user_id."' AND id!='0' AND status='1'";
	 	$query1=mysqli_query($mysqli,$qry_user);
		$row_user = mysqli_fetch_array($query1);

		$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
				return $row_user[$field_name];
			}
			else
			{
				return "";
			}
	 }

	$get_method = checkSignSalt($_POST['data']);


    if($get_method['method_name']=="home_videos")
	{

 		$jsonObj_2= array();

		$query_all="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
		WHERE tbl_video.featured='1' AND tbl_video.status='1' AND tbl_category.status=1 ORDER BY tbl_video.id DESC";

		$sql_all = mysqli_query($mysqli,$query_all)or die(mysqli_error());

		while($data_all = mysqli_fetch_assoc($sql_all))
		{
			$row2['id'] = $data_all['id'];
			$row2['cat_id'] = $data_all['cat_id'];
			$row2['video_type'] = $data_all['video_type'];
			$row2['video_title'] = $data_all['video_title'];
			$row2['video_url'] = $data_all['video_url'];
			$row2['video_id'] = $data_all['video_id'];
			$row2['video_layout'] = $data_all['video_layout'];

			if($data_all['video_type']=='server_url' or $data_all['video_type']=='local')
			{
				$row2['video_thumbnail_b'] = $file_path.'images/'.$data_all['video_thumbnail'];
				$row2['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data_all['video_thumbnail'];
			}
			else
			{
				$row2['video_thumbnail_b'] = $data_all['video_thumbnail'];
				$row2['video_thumbnail_s'] = $data_all['video_thumbnail'];
			}


 			$row2['total_likes'] = $data_all['total_likes'];
 			$row2['totel_viewer'] = $data_all['totel_viewer'];


			$row2['cid'] = $data_all['cid'];
			$row2['category_name'] = $data_all['category_name'];
			$row2['category_image'] = $file_path.'images/'.$data_all['category_image'];
			$row2['category_image_thumb'] = $file_path.'images/thumbs/'.$data_all['category_image'];


		     $query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$data_all['id']."' && device_id='".$get_method['user_id']."' ");
    			$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
    			$row2['already_like']=true;
    		}
    		else
    		{
    			$row2['already_like']=false;
    		}

			array_push($jsonObj_2,$row2);

		}
		$row['featured_video']=$jsonObj_2;

		$jsonObj= array();

		$query="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
		WHERE tbl_video.status='1' AND tbl_video.video_layout='عمودی' AND tbl_category.status=1 ORDER BY tbl_video.id DESC LIMIT 5";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

		while($data = mysqli_fetch_assoc($sql))
		{
			$row1['id'] = $data['id'];
			$row1['cat_id'] = $data['cat_id'];
			$row1['video_type'] = $data['video_type'];
			$row1['video_title'] = $data['video_title'];
			$row1['video_url'] = $data['video_url'];
			$row1['video_id'] = $data['video_id'];
			$row1['video_layout'] = $data['video_layout'];

			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row1['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row1['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row1['video_thumbnail_b'] = $data['video_thumbnail'];
				$row1['video_thumbnail_s'] = $data['video_thumbnail'];
			}


			$row1['total_likes'] = $data['total_likes'];
 			$row1['totel_viewer'] = $data['totel_viewer'];


			$row1['cid'] = $data['cid'];
			$row1['category_name'] = $data['category_name'];
			$row1['category_image'] = $file_path.'images/'.$data['category_image'];
			$row1['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$data['id']."' && device_id='".$get_method['user_id']."' ");
    			$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
    			$row1['already_like']=true;
    		}
    		else
    		{
    			$row1['already_like']=false;
    		}

			array_push($jsonObj,$row1);


		}

		$row['portrait_video']=$jsonObj;



		$set['ANDROID_REWARDS_APP'] = $row;

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="landscape_videos")
 	{

 		$query_rec = "SELECT COUNT(*) as num FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
		WHERE tbl_video.status='1' AND tbl_video.video_layout='افقی'";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

		$page_limit=API_PAGE_LIMIT;

		$limit=($get_method['page']-1) * $page_limit;

 		$video_order_by=API_ALL_VIDEO_ORDER_BY;

		$jsonObj= array();

		$query="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
		WHERE tbl_video.status='1'AND tbl_video.video_layout='افقی' AND tbl_category.status=1 ORDER BY tbl_video.id $video_order_by LIMIT $limit, $page_limit";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['pagination_limit'] = $page_limit;
			$row['total_record'] = $total_pages['num'];

			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];
			$row['video_layout'] = $data['video_layout'];

			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}


			$row['total_likes'] = $data['total_likes'];
  			$row['totel_viewer'] = $data['totel_viewer'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$data['id']."' && device_id='".$get_method['user_id']."' ");
    			$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
    			$row['already_like']=true;
    		}
    		else
    		{
    			$row['already_like']=false;
    		}

			array_push($jsonObj,$row);

		}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
 	else if($get_method['method_name']=="portrait_videos")
 	{

 		$query_rec = "SELECT COUNT(*) as num FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
		WHERE tbl_video.status='1' AND tbl_video.video_layout='عمودی'";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

		$page_limit=API_PAGE_LIMIT;

		$limit=($get_method['page']-1) * $page_limit;

 		$video_order_by=API_ALL_VIDEO_ORDER_BY;

		$jsonObj= array();

		$query="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
		WHERE tbl_video.status='1'AND tbl_video.video_layout='عمودی' AND tbl_category.status=1 ORDER BY tbl_video.id $video_order_by LIMIT $limit, $page_limit";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['pagination_limit'] = $page_limit;
			$row['total_record'] = $total_pages['num'];

			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];
			$row['video_layout'] = $data['video_layout'];

			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}


			$row['total_likes'] = $data['total_likes'];
  			$row['totel_viewer'] = $data['totel_viewer'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$data['id']."' && device_id='".$get_method['user_id']."' ");
    			$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
    			$row['already_like']=true;
    		}
    		else
    		{
    			$row['already_like']=false;
    		}

			array_push($jsonObj,$row);

		}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
    else if($get_method['method_name']=="all_videos")
 	{

 		if($get_method['filter_value']!="")
 		{
	 			$query_rec = "SELECT COUNT(*) as num FROM tbl_video
				LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
				WHERE tbl_video.status='1' AND tbl_category.status=1 AND tbl_video.video_layout='".$get_method['filter_value']."'";
				$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

				$page_limit=API_PAGE_LIMIT;

				$limit=($get_method['page']-1) * $page_limit;

		 		$video_order_by=API_ALL_VIDEO_ORDER_BY;

				$jsonObj= array();

				$query="SELECT * FROM tbl_video
				LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
				WHERE tbl_video.status='1' AND tbl_category.status=1 AND tbl_video.video_layout='".$get_method['filter_value']."' ORDER BY tbl_video.id $video_order_by LIMIT $limit, $page_limit";
				$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
 		}
 		else
 		{
 				$query_rec = "SELECT COUNT(*) as num FROM tbl_video
				LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
				WHERE tbl_video.status='1'";
				$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

				$page_limit=API_PAGE_LIMIT;

				$limit=($get_method['page']-1) * $page_limit;

		 		$video_order_by=API_ALL_VIDEO_ORDER_BY;

				$jsonObj= array();

				$query="SELECT * FROM tbl_video
				LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
				WHERE tbl_video.status='1' AND tbl_category.status=1 ORDER BY tbl_video.id $video_order_by LIMIT $limit, $page_limit";
				$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
 		}



		while($data = mysqli_fetch_assoc($sql))
		{
			$row['pagination_limit'] = $page_limit;
			$row['total_record'] = $total_pages['num'];

			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];
			$row['video_layout'] = $data['video_layout'];

			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}


			$row['total_likes'] = $data['total_likes'];
  			$row['totel_viewer'] = $data['totel_viewer'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$data['id']."' && device_id='".$get_method['user_id']."' ");
    			$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
    			$row['already_like']=true;
    		}
    		else
    		{
    			$row['already_like']=false;
    		}

			array_push($jsonObj,$row);

		}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
	else if($get_method['method_name']=="cat_list")
 	{
		$jsonObj= array();

		$cat_order=API_CAT_ORDER_BY;


		$query="SELECT * FROM tbl_category WHERE tbl_category.status=1 ORDER BY tbl_category.".$cat_order."";
		$sql = mysqli_query($mysqli,$query)or die(mysql_error());

		while($data = mysqli_fetch_assoc($sql))
		{


			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$row['cat_total_video'] = cat_video_count($data['cid']);

			array_push($jsonObj,$row);

		}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;


		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }
 else if($get_method['method_name']=="video_by_cat_id")
 {
 		$cat_id=$get_method['cat_id'];

 		$post_order_by=API_CAT_POST_ORDER_BY;
		$jsonObj= array();

 		if($get_method['filter_value']!="")
 		{
 			$query_rec = "SELECT COUNT(*) as num FROM tbl_video
			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
			where tbl_video.cat_id='".$cat_id."' AND video_layout='".$get_method['filter_value']."' AND tbl_video.status='1' AND tbl_category.status=1";
			$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

			$page_limit=API_PAGE_LIMIT;
			$limit=($get_method['page']-1) * $page_limit;


		    $query="SELECT * FROM tbl_video
			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
			where tbl_video.cat_id='".$cat_id."' AND video_layout='".$get_method['filter_value']."' AND tbl_video.status='1' AND tbl_category.status=1 ORDER BY tbl_video.id ".$post_order_by." LIMIT $limit, $page_limit";
			$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
 		}
 		else
 		{
 			$query_rec = "SELECT COUNT(*) as num FROM tbl_video
			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
			where tbl_video.cat_id='".$cat_id."' AND tbl_video.status='1' AND tbl_category.status=1";
			$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

			$page_limit=API_PAGE_LIMIT;
			$limit=($get_method['page']-1) * $page_limit;


		    $query="SELECT * FROM tbl_video
			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
			where tbl_video.cat_id='".$cat_id."' AND tbl_video.status='1' AND tbl_category.status=1 ORDER BY tbl_video.id ".$post_order_by." LIMIT $limit, $page_limit";
			$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
 		}



		while($data = mysqli_fetch_assoc($sql))
		{
			$row['pagination_limit'] = $page_limit;
			$row['total_record'] = $total_pages['num'];

			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];
			$row['video_layout'] = $data['video_layout'];

			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}


			$row['total_likes'] = $data['total_likes'];
 			$row['totel_viewer'] = $data['totel_viewer'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$data['id']."' && device_id='".$get_method['user_id']."' ");
    		$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
    			$row['already_like']=true;
    		}
    		else
    		{
    			$row['already_like']=false;
    		}

			array_push($jsonObj,$row);

		}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	 else if($get_method['method_name']=="single_video")
 	{

 		$jsonObj= array();

		$query="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
		WHERE tbl_video.id='".$get_method['video_id']."'";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());


		while($data = mysqli_fetch_assoc($sql))
		{


			$row['cat_id'] = $data['cat_id'];
			$row['category_name'] = $data['category_name'];

			$row['id'] = $data['id'];
			$row['video_type'] = $data['video_type'];
 			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] =$data['video_id'];
			$row['video_layout'] = $data['video_layout'];

			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}


			$row['total_likes'] = $data['total_likes'];
 			$row['totel_viewer'] = $data['totel_viewer'];


 			$row['user_id'] = $data['user_id'];
			$row['user_name'] = get_user_info($data['user_id'],'name');

			if(get_user_info($data['user_id'],'user_image')!='')
			{
				$row['user_image'] = $file_path.'images/'.get_user_info($data['user_id'],'user_image');
			}
			else
			{
				$row['user_image'] ='';
			}


			$qry_f1 = "SELECT * FROM tbl_follows WHERE user_id = '".$data['user_id']."' AND follower_id= '".$get_method['user_id']."'";
			$result_f1 = mysqli_query($mysqli,$qry_f1);
			$num_rows_f1 = mysqli_num_rows($result_f1);

	    	if ($num_rows_f1 > 0)
			{

				     $row['already_follow']=true;

			}
			else
			{

	 				$row['already_follow']=false;
			}

			//Related Videos
			$query_2="SELECT * FROM tbl_video
			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
			WHERE tbl_video.cat_id='".$data['cat_id']."' AND tbl_video.video_layout='".$data['video_layout']."' AND tbl_video.status='1' AND tbl_category.status=1 AND tbl_video.id!='".$data['id']."'";

			$sql2 = mysqli_query($mysqli,$query_2)or die(mysqli_error());

 			if($sql2->num_rows > 0)
		   {
 			while($data_2 = mysqli_fetch_assoc($sql2))
			{
				$row2['cat_id'] = $data_2['cat_id'];
				$row2['category_name'] = $data_2['category_name'];

				$row2['id'] = $data_2['id'];
				$row2['video_type'] = $data_2['video_type'];
				$row2['video_title'] = $data_2['video_title'];
				$row2['video_url'] = $data_2['video_url'];
				$row2['video_id'] = $data_2['video_id'];
				$row2['video_layout'] = $data_2['video_layout'];

				if($data_2['video_type']=='server_url' or $data_2['video_type']=='local')
				{
					$row2['video_thumbnail_b'] = $file_path.'images/'.$data_2['video_thumbnail'];
					$row2['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data_2['video_thumbnail'];
				}
				else
				{
					$row2['video_thumbnail_b'] = $data_2['video_thumbnail'];
					$row2['video_thumbnail_s'] = $data_2['video_thumbnail'];
				}


				$row2['total_likes'] = $data_2['total_likes'];
 				$row2['totel_viewer'] = $data_2['totel_viewer'];

 				$query2 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$data_2['id']."' && device_id='".$get_method['user_id']."' ");
    			$num_rows2 = mysqli_num_rows($query2);

	            if ($num_rows2 > 0)
			    {
	    			$row2['already_like']=true;
	    		}
	    		else
	    		{
	    			$row2['already_like']=false;
	    		}

				$related_data[]=$row2;

			}

			  $row['related']=$related_data;

			}
	        else
	        {
	          $row['related']=array();
	        }


	        //Comments
		      $qry3="SELECT * FROM tbl_comments where post_id='".$data['id']."'";
		      $result3=mysqli_query($mysqli,$qry3);

		      if($result3->num_rows > 0)
		      {
		      		while ($row_comments=mysqli_fetch_array($result3)) {

 		      			$row3['user_id'] = $row_comments['user_id'];
						$row3['user_name'] = get_user_info($row_comments['user_id'],'name')?get_user_info($row_comments['user_id'],'name'):$row_comments['user_name'];

						if(get_user_info($row_comments['user_id'],'user_image')!='')
						{
							$row3['user_image'] = $file_path.'images/'.get_user_info($row_comments['user_id'],'user_image');
						}
						else
						{
							$row3['user_image'] ='';
						}
		 		      	$row3['video_id'] = $row_comments['post_id'];
 		 		      	//$row3['user_name'] = $row_comments['user_name'];
		 		      	$row3['comment_text'] = $row_comments['comment_text'];
		 		      	$row3['comment_date'] = jdate('Y/m/d',strtotime($row_comments['dt_rate']));

		 		      	$row['user_comments'][]= $row3;
				      }

		      }
		      else
		      {

		      		$row['user_comments']= array();
		      }


		    $query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$get_method['video_id']."' && device_id='".$get_method['user_id']."' ");
    			$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
    			$row['already_like']=true;
    		}
    		else
    		{
    			$row['already_like']=false;
    		}

			array_push($jsonObj,$row);

			}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;


		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="single_video_view_count")
 	{
 		$video_views=API_VIDEO_VIEWS;

 		$view_qry=mysqli_query($mysqli,"UPDATE tbl_video SET totel_viewer= totel_viewer + 1  WHERE id = '".$get_method['video_id']."'");

 		if(VIDEO_VIEW_POINTS_STATUS=='true')
 		{
 			$qry = "SELECT * FROM tbl_users_rewards_activity WHERE  video_id = '".$get_method['video_id']."' and user_id = '".$get_method['user_id']."' and activity_type = 'مشاهده ویدئو'";
            $result = mysqli_query($mysqli,$qry);
            $num_rows = mysqli_num_rows($result);
            //$row = mysqli_fetch_assoc($result);

            if ($num_rows <= 0)
            {
              if($get_method['user_view'] <= MAX_VIDEO_VIEW){
 				$user_view_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point= total_point + '".$video_views."'  WHERE id = '".$get_method['user_id']."'");
      }
      }

			user_reward_activity($get_method['video_id'],$get_method['user_id'],"مشاهده ویدئو",API_VIDEO_VIEWS);
 		}

		$set['ANDROID_REWARDS_APP'][] = array('msg'=>'مشاهده تعداد!','success'=>1);

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}























  else if($get_method['method_name']=="check_insta")
 	{
    $user_id =$_POST['user_id'];
    $user_name = $_POST['user_name'];
    $data = array(
        'user_id'  =>  $user_id,
        'user_name' => $user_name
              );
    $qwert = "INSERT INTO insta_check (user_id,user_name) VALUES (".$user_id.",'".$user_name."')";
mysqli_query($mysqli,$qwert);

  $set['ANDROID_REWARDS_APP'][] = array('msg'=>'اطلاعات با موفقیت برای بررسی ارسال شد','success'=>1);

  header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  die();
 	}
  else if($get_method['method_name']=="check_telegram")
 	{
    $user_id =$get_method['user_id'];
    $user_name = $get_method['user_name'];
    $data = array(
        'user_id'  =>  $user_id,
        'user_name' => $user_name
              );
    $qwert = "INSERT INTO telegram_check (user_id,user_name) VALUES (".$user_id.",'".$user_name."')";
mysqli_query($mysqli,$qwert);
	
	

  $set['ANDROID_REWARDS_APP'][] = array('msg'=>'اطلاعات با موفقیت برای بررسی ارسال شد','success'=>1);

  header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  die();
 	}












  else if($get_method['method_name']=="tapsell_watch")
 	{
 		$tapsell_views=WATCH_TAPSELL_POINTS;



 		if(WATCH_TAPSELL_POINTS_STATUS=='true')
 		{

              if($get_method['watch_tapsell'] <= MAX_TAPSELL_WATCH){
 				$user_view_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point= total_point + '".$tapsell_views."'  WHERE id = '".$get_method['user_id']."'");
      }


			user_reward_activity("",$get_method['user_id'],"مشاهده آگهی تپسل",WATCH_TAPSELL_POINTS);
 		}

		$set['ANDROID_REWARDS_APP'][] = array('msg'=>'مشاهده تعداد!','success'=>1);

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
  else if($get_method['method_name']=="admob_watch")
 	{
 		$admob_views=WATCH_ADMOB_POINTS;



 		if(WATCH_ADMOB_POINTS_STATUS=='true')
 		{

              if($get_method['watch_admob'] <= MAX_ADMOB_WATCH){
 				$user_view_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point= total_point + '".$admob_views."'  WHERE id = '".$get_method['user_id']."'");
      }


			user_reward_activity("",$get_method['user_id'],"مشاهده آگهی ادموب",WATCH_ADMOB_POINTS);
 		}

		$set['ANDROID_REWARDS_APP'][] = array('msg'=>'مشاهده تعداد!','success'=>1);

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
 	else if($get_method['method_name']=="single_video_download")
 	{
 		$video_download_point=DOWNLOAD_VIDEO_POINTS;

 		if(DOWNLOAD_POINTS_STATUS=='true')
 		{
 			$qry = "SELECT * FROM tbl_users_rewards_activity WHERE  video_id = '".$get_method['video_id']."' and user_id = '".$get_method['user_id']."' and activity_type = 'دانلود ویدئو'";
            $result = mysqli_query($mysqli,$qry);
            $num_rows = mysqli_num_rows($result);
            //$row = mysqli_fetch_assoc($result);

            if ($num_rows <= 0)
            {
              if($get_method['user_dl'] <= MAX_DUP_POINTS){
 				$user_view_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point= total_point + '".$video_download_point."'  WHERE id = '".$get_method['user_id']."'");
 			}}

			user_reward_activity($get_method['video_id'],$get_method['user_id'],"دانلود ویدئو",DOWNLOAD_VIDEO_POINTS);
 		}

		$set['ANDROID_REWARDS_APP'][] = array('msg'=>'دانلود ویدئو!','success'=>1);

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
  else if($get_method['method_name']=="get_ad_interval")
 	{
 		$interval=INTERVAL;



		$set['ANDROID_REWARDS_APP'][] = array('msg'=>"درخواست اینتروال",'success'=>1,'interval'=>$interval);

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
	else if($get_method['method_name']=="search_video")
 	{
 		if($get_method['filter_value']!="")
 		{
 			$query_rec = "SELECT COUNT(*) as num FROM tbl_video
			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
			WHERE tbl_video.status='1' AND tbl_category.status=1 AND tbl_video.video_layout='".$get_method['filter_value']."' AND tbl_video.video_title LIKE '%".$get_method['search_text']."%'";
			$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

			$page_limit=API_PAGE_LIMIT;
			$limit=($get_method['page']-1) * $page_limit;

			$jsonObj= array();

			$query="SELECT * FROM tbl_video
			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
			WHERE tbl_video.status='1' AND tbl_category.status=1 AND tbl_video.video_layout='".$get_method['filter_value']."' AND tbl_video.video_title LIKE '%".$get_method['search_text']."%'  ORDER BY tbl_video.video_title DESC LIMIT $limit, $page_limit";
			$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
 		}
 		else
 		{
 			$query_rec = "SELECT COUNT(*) as num FROM tbl_video
			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
			WHERE tbl_video.status='1' AND tbl_category.status=1 AND tbl_video.video_title LIKE '%".$get_method['search_text']."%'";
			$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

			$page_limit=API_PAGE_LIMIT;
			$limit=($get_method['page']-1) * $page_limit;

			$jsonObj= array();

			$query="SELECT * FROM tbl_video
			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
			WHERE tbl_video.status='1' AND tbl_category.status=1 AND tbl_video.video_title LIKE '%".$get_method['search_text']."%'  ORDER BY tbl_video.video_title DESC LIMIT $limit, $page_limit";
			$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
 		}


		while($data = mysqli_fetch_assoc($sql))
		{
			$row['pagination_limit'] = $page_limit;
			$row['total_record'] = $total_pages['num'];

			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];
			$row['video_layout'] = $data['video_layout'];

			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}


			$row['total_likes'] = $data['total_likes'];
  			$row['totel_viewer'] = $data['totel_viewer'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$data['id']."' && device_id='".$get_method['user_id']."' ");
    			$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
    			$row['already_like']=true;
    		}
    		else
    		{
    			$row['already_like']=false;
    		}

			array_push($jsonObj,$row);

		}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;


		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
 	else if($get_method['method_name']=="user_register_verify_email")
	{

		$qry = "SELECT * FROM tbl_users WHERE email = '".$get_method['email']."' ";
		$result = mysqli_query($mysqli,$qry);
		$row = mysqli_fetch_assoc($result);

		if($row['email']!="")
		{
			$set['ANDROID_REWARDS_APP'][]=array('msg' => "این ایمیل قبلا ثبت شده است!",'success'=>'0');
		}
		else
		{



			$to = $get_method['email'];
			$recipient_name='';
			// subject
			$subject = '[IMPORTANT] '.APP_NAME.' Email Verification Code';

			$message='<div style="background-color: #f9f9f9;" align="center"><br />
					  <table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
					    <tbody>
					      <tr>
					        <td colspan="2" bgcolor="#FFFFFF" align="center"><img src="http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/images/'.APP_LOGO.'" alt="header" width="120"/></td>
					      </tr>
					      <tr>
					        <td width="600" valign="top" bgcolor="#FFFFFF"><br>
					          <table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
					            <tbody>
					              <tr>
					                <td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
					                    <tbody>
					                      <tr>
					                        <td>
					                          <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;">Thank you for using '.APP_NAME.',<br>
					                            Your OTP is: '.$get_method['otp_code'].'</p>
					                          <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px;">Thanks you,<br />
					                            '.APP_NAME.'.</p></td>
					                      </tr>
					                    </tbody>
					                  </table></td>
					              </tr>

					            </tbody>
					          </table></td>
					      </tr>
					      <tr>
					        <td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © '.APP_NAME.'.</td>
					      </tr>
					    </tbody>
					  </table>
					</div>';



			send_email($to,$recipient_name,$subject,$message);


			$set['ANDROID_REWARDS_APP'][]=array('msg' => "یک ایمیل حاوی کد تایید برای شما ارسال گردید.",'success'=>'1');
		}


		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="user_register")
	{

		$registration_reward=API_REGISTRATION_REWARD;

		$qry = "SELECT * FROM tbl_users WHERE   email = '".$get_method['email']."' ";
		$result = mysqli_query($mysqli,$qry);
		$row = mysqli_fetch_assoc($result);

		if($row['email']!="")
		{
			$set['ANDROID_REWARDS_APP'][]=array('msg' => "این ایمیل قبلا ثبت شده است!",'success'=>'0');
		}
		else
		{
  		     $user_code=createRandomCode();

			 $qry1="INSERT INTO tbl_users (`user_type`,`user_code`,`name`,`email`,`password`,`phone`,`status`) VALUES ('Normal','".$user_code."','".$get_method['name']."','".$get_method['email']."','".$get_method['password']."','".$get_method['phone']."','1')";

             $result1=mysqli_query($mysqli,$qry1);
			 $user_id=mysqli_insert_id($mysqli);

			 if(REGISTRATION_REWARD_POINTS_STATUS=='true')
			 {
			 	$view_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point= total_point + '".API_REGISTRATION_REWARD."' WHERE id = '".$user_id."'");

			 	user_reward_activity('',$user_id,"پاداش ثبت نام",API_REGISTRATION_REWARD);
			 }

			 if(isset($get_method['user_refrence_code']))
			{

				 $user_qry="SELECT * FROM tbl_users where user_code='".$get_method['user_refrence_code']."'";
				 $user_result=mysqli_query($mysqli,$user_qry);
				 $user_row=mysqli_fetch_assoc($user_result);

				 if(APP_REFER_REWARD_POINTS_STATUS=='true')
				 {
					 if($user_row['id']!="")
				     {
			         	$view_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point= total_point + '".API_REFER_REWARD."' WHERE id = '".$user_row['id']."'");

			         	$refer_msg_text= "User Refer Rewards - Used by ".$get_method['name'];

				 		user_reward_activity('',$user_row['id'],$refer_msg_text,API_REFER_REWARD);
			  		 	//user_reward_activity('',$user_row['id'],"User Refer Rewards",API_REFER_REWARD);
			  		 }
			  	}

	  	     }

	  	     //Default Admin Follow
	  	   $data_follow = array(
               'user_id' =>0,
               'follower_id'  => $user_id
               );
          $qry_follow = Insert('tbl_follows',$data_follow);

          $user_followers_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_followers=total_followers+1 WHERE id = '0'");
          $user_following_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_following=total_following+1 WHERE id = '".$user_id."'");


			$set['ANDROID_REWARDS_APP'][]=array('msg' => "ثبت نام با موفقیت انجام شد!",'success'=>'1');

		   }

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();


	}
	else if($get_method['method_name']=="user_login")
	{

	    $email = $get_method['email'];
 		$password = $get_method['password'];

	    $qry = "SELECT * FROM tbl_users WHERE  email = '".$email."' and password = '".$password."'";
		$result = mysqli_query($mysqli,$qry);
		$num_rows = mysqli_num_rows($result);
 		$row = mysqli_fetch_assoc($result);

    if ($num_rows > 0)
		{
				if($row['status']==0)
				{
					$set['ANDROID_REWARDS_APP'][]=array('msg' =>'اکانت شما مسدود شده است!','success'=>'0');
				}
				else
				{
					$set['ANDROID_REWARDS_APP'][]=array('user_id' => $row['id'],'name'=>$row['name'],'email'=>$row['email'],'success'=>'1');
				}


		}
		else
		{

 				$set['ANDROID_REWARDS_APP'][]=array('msg' =>'مشکل در ورود!','success'=>'0');
 		}



		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="user_profile")
	{

		$qry = "SELECT * FROM tbl_users WHERE id = '".$get_method['user_id']."'";
		$result = mysqli_query($mysqli,$qry);
		$row = mysqli_fetch_assoc($result);

		//Follower list
		$jsonObj2= array();

	   // $query2="SELECT * FROM tbl_follows where user_id='".$row['id']."'";

	    $query2="SELECT * FROM tbl_follows
		WHERE tbl_follows.user_id='".$row['id']."' ORDER BY tbl_follows.id DESC";

		$sql2 = mysqli_query($mysqli,$query2)or die(mysqli_error());

		while($data2 = mysqli_fetch_assoc($sql2))
		{

			$row2['user_id'] = $data2['follower_id'];
			$row2['user_name'] = get_user_info($data2['follower_id'],'name');

			if(get_user_info($data2['user_id'],'user_image')!='')
			{
				$row2['user_image'] = $file_path.'images/'.get_user_info($data2['follower_id'],'user_image');
			}
			else
			{
				$row2['user_image'] ='';
			}

			array_push($jsonObj2,$row2);

		}

		//Following list
		$jsonObj3= array();

		$query3="SELECT * FROM tbl_follows
		WHERE tbl_follows.follower_id='".$row['id']."' ORDER BY tbl_follows.id DESC";

		$sql3 = mysqli_query($mysqli,$query3)or die(mysqli_error());

		while($data3 = mysqli_fetch_assoc($sql3))
		{

			$row3['user_id'] = $data3['user_id'];
			$row3['user_name'] = get_user_info($data3['user_id'],'name');

			if(get_user_info($data3['user_id'],'user_image')!='')
			{
				$row3['user_image'] = $file_path.'images/'.get_user_info($data3['user_id'],'user_image');
			}
			else
			{
				$row3['user_image'] ='';
			}

			array_push($jsonObj3,$row3);

		}

		if($row['user_image']!='')
		{
			$user_image=$file_path.'images/'.$row['user_image'];
		}
		else
		{
			$user_image='';
		}

		$phone=$row['phone']?$row['phone']:'';
		$user_youtube=$row['user_youtube']?$row['user_youtube']:'';
		$user_instagram=$row['user_instagram']?$row['user_instagram']:'';

		$user_total_video=user_video_count($row['id']);


	    $set['ANDROID_REWARDS_APP'][]=array('user_id' => $row['id'],'name'=>$row['name'],'email'=>$row['email'],'phone'=>$phone,'user_image'=>$user_image,'user_youtube'=>$user_youtube,'user_instagram'=>$user_instagram,'user_code'=>$row['user_code'],'total_point'=>$row['total_point'],'user_total_video'=>$user_total_video,'total_followers'=>$row['total_followers'],'total_following'=>$row['total_following'],'user_followers'=>$jsonObj2,'user_following'=>$jsonObj3,'success'=>'1');




		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="user_profile_update")
	{
		$qry = "SELECT * FROM tbl_users WHERE id = '".$get_method['user_id']."'";
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

		if($get_method['password']!="")
		{
			$user_edit= "UPDATE tbl_users SET name='".$get_method['name']."',email='".$get_method['email']."',password='".$get_method['password']."',phone='".$get_method['phone']."',user_image='".$user_image."',user_youtube='".$get_method['user_youtube']."',user_instagram='".$get_method['user_instagram']."' WHERE id = '".$get_method['user_id']."'";
		}
		else
		{
			$user_edit= "UPDATE tbl_users SET name='".$get_method['name']."',email='".$get_method['email']."',phone='".$get_method['phone']."',user_image='".$user_image."',user_youtube='".$get_method['user_youtube']."',user_instagram='".$get_method['user_instagram']."' WHERE id = '".$get_method['user_id']."'";
		}

   		$user_res = mysqli_query($mysqli,$user_edit);

		$set['ANDROID_REWARDS_APP'][]=array('msg'=>'بروزرسانی شد','success'=>'1');

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="other_user_profile")
	{

		$qry = "SELECT * FROM tbl_users WHERE id = '".$get_method['other_user_id']."'";
		$result = mysqli_query($mysqli,$qry);
		$row = mysqli_fetch_assoc($result);


		//Follower list
		$jsonObj2= array();

	   // $query2="SELECT * FROM tbl_follows where user_id='".$row['id']."'";

	    $query2="SELECT * FROM tbl_follows
		WHERE tbl_follows.user_id='".$row['id']."' ORDER BY tbl_follows.id DESC";

		$sql2 = mysqli_query($mysqli,$query2)or die(mysqli_error());

		while($data2 = mysqli_fetch_assoc($sql2))
		{

			$row2['user_id'] = $data2['follower_id'];
			$row2['user_name'] = get_user_info($data2['follower_id'],'name');

			if(get_user_info($data2['user_id'],'user_image')!='')
			{
				$row2['user_image'] = $file_path.'images/'.get_user_info($data2['follower_id'],'user_image');
			}
			else
			{
				$row2['user_image'] ='';
			}

			array_push($jsonObj2,$row2);

		}

		//Following list
		$jsonObj3= array();

		$query3="SELECT * FROM tbl_follows
		WHERE tbl_follows.follower_id='".$row['id']."' ORDER BY tbl_follows.id DESC";

		$sql3 = mysqli_query($mysqli,$query3)or die(mysqli_error());

		while($data3 = mysqli_fetch_assoc($sql3))
		{

			$row3['user_id'] = $data3['user_id'];
			$row3['user_name'] = get_user_info($data3['user_id'],'name');

			if(get_user_info($data3['user_id'],'user_image')!='')
			{
				$row3['user_image'] = $file_path.'images/'.get_user_info($data3['user_id'],'user_image');
			}
			else
			{
				$row3['user_image'] ='';
			}

			array_push($jsonObj3,$row3);

		}

		if($row['user_image']!='')
		{
			$user_image=$file_path.'images/'.$row['user_image'];
		}
		else
		{
			$user_image='';
		}


		$qry1 = "SELECT * FROM tbl_follows WHERE user_id = '".$get_method['other_user_id']."' AND follower_id= '".$get_method['user_id']."'";
		$result1 = mysqli_query($mysqli,$qry1);
		$num_rows1 = mysqli_num_rows($result1);

    	if ($num_rows1 > 0)
		{

			     $already_follow=true;

		}
		else
		{

 				$already_follow=false;
		}


		$user_youtube=$row['user_youtube']?$row['user_youtube']:'';
		$user_instagram=$row['user_instagram']?$row['user_instagram']:'';

		$user_total_video=user_video_count($row['id']);

	    $set['ANDROID_REWARDS_APP'][]=array('user_id' => $row['id'],'name'=>$row['name'],'email'=>$row['email'],'user_image'=>$user_image,'user_code'=>$row['user_code'],'user_youtube'=>$user_youtube,'user_instagram'=>$user_instagram,'already_follow'=>$already_follow,'user_total_video'=>$user_total_video,'total_followers'=>$row['total_followers'],'total_following'=>$row['total_following'],'user_followers'=>$jsonObj2,'user_following'=>$jsonObj3,'success'=>'1');




		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="user_status")
	{
		$user_id = $get_method['user_id'];

		$qry = "SELECT * FROM tbl_users WHERE status='1' and id = '".$user_id."'";
		$result = mysqli_query($mysqli,$qry);
		$num_rows = mysqli_num_rows($result);
		$row = mysqli_fetch_assoc($result);

    if ($num_rows > 0)
		{

			     $set['ANDROID_REWARDS_APP'][]=array('message' => 'فعال','success'=>'1');

		}
		else
		{

 				$set['ANDROID_REWARDS_APP'][]=array('message' => 'غیر فعال','success'=>'0');
		}



		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	else if($get_method['method_name']=="forgot_pass")
	{


		$qry = "SELECT * FROM tbl_users WHERE email = '".$get_method['email']."'";
		$result = mysqli_query($mysqli,$qry);
		$row = mysqli_fetch_assoc($result);

		if($row['email']!="")
		{

			$to = $get_method['email'];
			$recipient_name=$row['name'];
			// subject
			$subject = '[IMPORTANT] '.APP_NAME.' Forgot Password Information';

			$message='<div style="background-color: #f9f9f9;" align="center"><br />
					  <table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
					    <tbody>
					      <tr>
					        <td colspan="2" bgcolor="#FFFFFF" align="center"><img src="http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/images/'.APP_LOGO.'" alt="header" width="120"/></td>
					      </tr>
					      <tr>
					        <td width="600" valign="top" bgcolor="#FFFFFF"><br>
					          <table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
					            <tbody>
					              <tr>
					                <td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
					                    <tbody>
					                      <tr>
					                        <td><p style="color: #262626; font-size: 28px; margin-top:0px;"><strong>Dear '.$row['name'].'</strong></p>
					                          <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;">Thank you for using '.APP_NAME.',<br>
					                            Your password is: '.$row['password'].'</p>
					                          <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px;">Thanks you,<br />
					                            '.APP_NAME.'.</p></td>
					                      </tr>
					                    </tbody>
					                  </table></td>
					              </tr>

					            </tbody>
					          </table></td>
					      </tr>
					      <tr>
					        <td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © '.APP_NAME.'.</td>
					      </tr>
					    </tbody>
					  </table>
					</div>';


			send_email($to,$recipient_name,$subject,$message);


			$set['ANDROID_REWARDS_APP'][]=array('msg' => "یک ایمیل حاوی رمز عبور شما ارسال گردید!",'success'=>'1');
		}
		else
		{

			$set['ANDROID_REWARDS_APP'][]=array('msg' => "ایمیل در پایگاه داده وجود ندارد!",'success'=>'0');

		}


		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	else if($get_method['method_name']=="user_rewads_point")
	{

	         $jsonObj= array();

			$query="SELECT * FROM tbl_users WHERE  id='".$get_method['user_id']."'";

			$sql = mysqli_query($mysqli,$query);

			while($data = mysqli_fetch_assoc($sql))
			{

		      $row['id'] =$data['id'];
		      $row['total_point'] =$data['total_point'];


	         $wall_query="SELECT * FROM tbl_users_rewards_activity WHERE user_id='".$get_method['user_id']."' AND status=1 ORDER BY id DESC";

			$wall_sql = mysqli_query($mysqli,$wall_query);
			$num_rows = mysqli_num_rows($wall_sql);

				if($num_rows > 0)
				{
					while($wall_data = mysqli_fetch_assoc($wall_sql))
					{

					   $row1['video_id'] =$wall_data['video_id'];
					   $row1['video_title'] =get_video_info($wall_data['video_id'],'video_title');
					   $row1['video_thumbnail'] =$file_path.'images/'.get_video_info($wall_data['video_id'],'video_thumbnail');


					   $row1['user_id'] =$wall_data['user_id'];
					   $row1['activity_type'] =$wall_data['activity_type'];
					   $row1['points'] =$wall_data['points'];
					   $row1['date'] =jdate('Y/m/d', strtotime($wall_data['date']));
					   $row1['time'] =jdate('h:i A', strtotime($wall_data['date']));

						$row['user_rewads_point'][]=$row1;

					}

				}
				else
				{
					$row['user_rewads_point']=array();
				}

				array_push($jsonObj,$row);

			}

			$set['ANDROID_REWARDS_APP']=$jsonObj;

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="user_redeem_points_history")
	{
		$jsonObj= array();

		$wall_query="SELECT * FROM tbl_users_rewards_activity WHERE user_id='".$get_method['user_id']."' AND redeem_id='".$get_method['redeem_id']."' AND status=0 ORDER BY id DESC";

			$wall_sql = mysqli_query($mysqli,$wall_query);

			while($wall_data = mysqli_fetch_assoc($wall_sql))
			{

			   $row1['video_id'] =$wall_data['video_id'];
			   $row1['video_title'] =get_video_info($wall_data['video_id'],'video_title');
			   $row1['video_thumbnail'] =$file_path.'images/'.get_video_info($wall_data['video_id'],'video_thumbnail');

			   $row1['user_id'] =$wall_data['user_id'];
			   $row1['activity_type'] =$wall_data['activity_type'];
			   $row1['points'] =$wall_data['points'];
			   $row1['date'] =jdate('Y/m/d', strtotime($wall_data['date']));


				array_push($jsonObj,$row1);

				}


			$set['ANDROID_REWARDS_APP']=$jsonObj;

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

   }
   else if($get_method['method_name']=="user_redeem_request")
	{
	  $user_id = $get_method['user_id'];
      $user_points = $get_method['user_points'];
      $payment_mode = $get_method['payment_mode'];
      $bank_details = $get_method['bank_details'];

      $redeem_price= ($get_method['user_points']*REDEEM_MONEY)/REDEEM_POINTS;

    	$data = array(
               'user_id' =>$user_id ,
               'user_points'  => $user_points,
               'redeem_price'  => $redeem_price,
               'payment_mode'  => $payment_mode,
               'bank_details'  =>  $bank_details,
               );

     	$qry = Insert('tbl_users_redeem',$data);
      $redeem_id=mysqli_insert_id($mysqli);

      $user_view_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point=0  WHERE id = '".$user_id."'");

      $user_activity_qry=mysqli_query($mysqli,"UPDATE tbl_users_rewards_activity SET redeem_id='".$redeem_id."',status=0  WHERE user_id = '".$user_id."' AND status = '1'");


      $set['ANDROID_REWARDS_APP'][] = array('msg'=>'درخواست تصویه ارسال شد!','success'=>1);


		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

   }
   else if($get_method['method_name']=="user_redeem_history")
	{
		$jsonObj= array();

	    $query="SELECT * FROM tbl_users_redeem
		where tbl_users_redeem.user_id='".$get_method['user_id']."' ORDER BY tbl_users_redeem.id DESC";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['redeem_id'] = $data['id'];
			$row['user_points'] = $data['user_points'];
			$row['redeem_price'] = $data['redeem_price'];
			$row['request_date'] = jdate('Y/m/d',strtotime($data['request_date']));
			$row['status'] = $data['status'];


			array_push($jsonObj,$row);

		}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
   else if($get_method['method_name']=="user_video_list")
   {
		$post_order_by=API_CAT_POST_ORDER_BY;
		$user_id=$get_method['user_id'];

		if($get_method['filter_value']!="")
 		{
 				if($get_method['login_user']=='true')
 				{
 					$query_rec = "SELECT COUNT(*) as num FROM tbl_video
				LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
				WHERE tbl_video.user_id='".$user_id."' AND tbl_video.video_layout='".$get_method['filter_value']."' ORDER BY tbl_video.id ".$post_order_by."";
 				}
 				else
 				{
 					$query_rec = "SELECT COUNT(*) as num FROM tbl_video
				LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
				WHERE tbl_video.user_id='".$user_id."' AND tbl_video.video_layout='".$get_method['filter_value']."' AND tbl_video.status='1' ORDER BY tbl_video.id ".$post_order_by."";
 				}

				$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
				$page_limit=API_PAGE_LIMIT;
				$limit=($get_method['page']-1) * $page_limit;


				$jsonObj= array();

				if($get_method['login_user']=='true')
 				{
				    $query="SELECT * FROM tbl_video
					LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
					WHERE tbl_video.user_id='".$user_id."' AND tbl_video.video_layout='".$get_method['filter_value']."' ORDER BY tbl_video.id ".$post_order_by." LIMIT $limit, $page_limit";
				}
				else
				{
					$query="SELECT * FROM tbl_video
					LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
					WHERE tbl_video.user_id='".$user_id."' AND tbl_video.video_layout='".$get_method['filter_value']."' AND tbl_video.status='1' ORDER BY tbl_video.id ".$post_order_by." LIMIT $limit, $page_limit";
				}

				$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
 		}
 		else
 		{
 				if($get_method['login_user']=='true')
 				{
 					$query_rec = "SELECT COUNT(*) as num FROM tbl_video
					LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
					WHERE tbl_video.user_id='".$user_id."' ORDER BY tbl_video.id ".$post_order_by."";

 				}
 				else
 				{
 					$query_rec = "SELECT COUNT(*) as num FROM tbl_video
					LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
					WHERE tbl_video.user_id='".$user_id."' AND tbl_video.status='1' ORDER BY tbl_video.id ".$post_order_by."";

 				}

				$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
				$page_limit=API_PAGE_LIMIT;
				$limit=($get_method['page']-1) * $page_limit;


				$jsonObj= array();

				if($get_method['login_user']=='true')
 				{
				    $query="SELECT * FROM tbl_video
					LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
					WHERE tbl_video.user_id='".$user_id."' ORDER BY tbl_video.id ".$post_order_by." LIMIT $limit, $page_limit";
				}
				else
				{
					$query="SELECT * FROM tbl_video
					LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
					WHERE tbl_video.user_id='".$user_id."' AND tbl_video.status='1' ORDER BY tbl_video.id ".$post_order_by." LIMIT $limit, $page_limit";
				}
				$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
 		}



		while($data = mysqli_fetch_assoc($sql))
		{
			$row['pagination_limit'] = $page_limit;
			$row['total_record'] = $total_pages['num'];

			$row['id'] = $data['id'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_layout'] = $data['video_layout'];

		 	$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
			$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];


 			$row['total_likes'] = $data['total_likes'];
 			$row['totel_viewer'] = $data['totel_viewer'];

 			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='".$data['id']."' && device_id='".$user_id."' ");
    			$num_rows1 = mysqli_num_rows($query1);

            if ($num_rows1 > 0)
		    {
    			$row['already_like']=true;
    		}
    		else
    		{
    			$row['already_like']=false;
    		}

			array_push($jsonObj,$row);

		}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
   else if($get_method['method_name']=="user_video_delete")
   {

		$user_id=$get_method['user_id'];
		$user_video_id=$get_method['video_id'];


 		$img_res=mysqli_query($mysqli,'SELECT * FROM tbl_video WHERE id='.$user_video_id.' AND user_id='.$user_id.'');
	    $img_res_row=mysqli_fetch_assoc($img_res);

	    if($img_res_row['video_thumbnail']!="")
	     {
	          unlink('images/thumbs/'.$img_res_row['video_thumbnail']);
	          unlink('images/'.$img_res_row['video_thumbnail']);
	          unlink('uploads/'.basename($img_res_row['video_url']));

	      }

	    Delete('tbl_video','id='.$user_video_id.'');

	    $set['ANDROID_REWARDS_APP'][]=array('msg' => "ویدئو با موفقیت حذف گردید!",'success'=>'1');


		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="user_video_like")
	{
		define("LIKE_VIDEO_POINTS",$settings_details['like_video_points']);
        define("LIKE_VIDEO_POINTS_STATUS",$settings_details['like_video_points_status']);

		$device_id = $get_method['device_id'];
    	$like = $get_method['like'];
    	$post_id = $get_method['post_id'];


    	$query1 = mysqli_query($mysqli,"select * from tbl_like where post_id='$post_id' && device_id='$device_id' ");
    	while($data1 = mysqli_fetch_assoc($query1)){
    		$rate_db1[] = $data1;
    	}
    	if(count($rate_db1) == 0 ){
    		     $data = array(
               'post_id' =>$post_id ,
               'device_id'  => $device_id,
               'likes'  =>  $like,
               );

     		$qry = Insert('tbl_like',$data);

					//Total like result

				$query = mysqli_query($mysqli,"select SUM(likes) AS total_likes from tbl_like where post_id='$post_id'");
        $row = mysqli_fetch_assoc($query);
        $total_likes = $row['total_likes'];


				$data = array(
					'total_likes'  =>  $total_likes
				);

		    $category_edit=Update('tbl_video', $data, "WHERE id = '".$post_id."'");

        //Points Coount
        if(LIKE_VIDEO_POINTS_STATUS=='true')
        {
            $video_like_points=LIKE_VIDEO_POINTS;

            $qry = "SELECT * FROM tbl_users_rewards_activity WHERE  video_id = '".$post_id."' and user_id = '".$device_id."' and activity_type = 'لایک ویدئو'";
            $result = mysqli_query($mysqli,$qry);
            $num_rows = mysqli_num_rows($result);
            //$row = mysqli_fetch_assoc($result);

            if ($num_rows <= 0)
            {
              if($get_method['user_like'] < MAX_LIKE_ROUNDS){
 				$user_view_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point= total_point + '".$video_like_points."'  WHERE id = '".$device_id."'");
 			}}

            user_reward_activity($post_id,$device_id,"لایک ویدئو",$video_like_points);
        }


        $set['ANDROID_REWARDS_APP'][]=array('msg' => "لایک شد!",'success'=>'1');

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

    	}else{

        $device_id = $get_method['device_id'];
        $post_id = $get_method['post_id'];

        $query = mysqli_query($mysqli,"select total_likes from tbl_video where id=$post_id");
        $row = mysqli_fetch_assoc($query);
        $total_likes = $row['total_likes']-1;

        $data = array(
          'total_likes'  =>  $total_likes
        );

        $quates_edit=Update('tbl_video', $data, "WHERE id = '".$post_id."'");

        $cat_result=mysqli_query($mysqli,"DELETE FROM `tbl_like` WHERE device_id='$device_id' and post_id='$post_id'");

        //Points Count
        if(LIKE_VIDEO_POINTS_STATUS=='true')
        {
            $video_like_points=LIKE_VIDEO_POINTS;
            $user_view_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point= (total_point - '".$video_like_points."')  WHERE id = '".$device_id."'");
            $cat_result=mysqli_query($mysqli,"DELETE FROM `tbl_users_rewards_activity` WHERE user_id='$device_id' AND video_id='$post_id'AND activity_type='Video Like'");
        }

        		$set['ANDROID_REWARDS_APP'][]=array('msg' => "لغو لایک گردید!",'success'=>'0');

				header( 'Content-Type: application/json; charset=utf-8' );
			    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
				die();

    	}
	}
	else if($get_method['method_name']=="user_video_comment")
	{
 		  $user_id = $get_method['user_id'];
 		  $post_id = $get_method['post_id'];
	      $comment_text = $get_method['comment_text'];

			$qry1="INSERT INTO tbl_comments (`user_id`,`post_id`,`comment_text`) VALUES ('".$user_id."','".$post_id."','".$comment_text."')";
            $result1=mysqli_query($mysqli,$qry1);


        $set['ANDROID_REWARDS_APP'][]=array('msg' => "نظر با موفقیت ثبت شد!",'success'=>'1');


		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="user_follow")
	{
			$user_id = $get_method['user_id'];
	     	$follower_id = $get_method['follower_id'];


	    $qry1 = "SELECT * FROM tbl_follows WHERE user_id = '".$get_method['user_id']."' AND follower_id= '".$get_method['follower_id']."'";
	    $result1 = mysqli_query($mysqli,$qry1);
	    $num_rows1 = mysqli_num_rows($result1);

	    $row1=mysqli_fetch_assoc($result1);

	    if($num_rows1 > 0)
	    {

	        Delete('tbl_follows','id='.$row1['id'].'');

	        $user_followers_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_followers=total_followers-1 WHERE id = '".$user_id."'");

	        $user_following_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_following=total_following-1 WHERE id = '".$follower_id."'");


	         $set['ANDROID_REWARDS_APP'][] = array('msg'=>'لغو دنبال شد!','success'=>1);

	    }
	    else
	    {
	          $data = array(
	               'user_id' =>$user_id ,
	               'follower_id'  => $follower_id
	               );

	          $qry = Insert('tbl_follows',$data);

	          $user_followers_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_followers=total_followers+1 WHERE id = '".$user_id."'");

	          $user_following_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_following=total_following+1 WHERE id = '".$follower_id."'");


	          $set['ANDROID_REWARDS_APP'][] = array('msg'=>'دنبال شد!','success'=>1);
	    }



	      header( 'Content-Type: application/json; charset=utf-8' );
	      echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	      die();
	}
	else if($get_method['method_name']=="user_contact_us")
	{
			$query="SELECT * FROM tbl_settings WHERE id='1'";
		    $sql = mysqli_query($mysqli,$query);
		    $data = mysqli_fetch_assoc($sql);



		    $contact_name = $get_method['contact_name'];
		    $contact_email = $get_method['contact_email'];
		    $contact_msg = $get_method['contact_msg'];

			$qry1="INSERT INTO tbl_contact_list (`contact_name`,`contact_email`,`contact_msg`) VALUES ('".$contact_name."','".$contact_email."','".$contact_msg."')";
            $result1=mysqli_query($mysqli,$qry1);




			$set['ANDROID_REWARDS_APP'][]=array('msg' => "پیام ارسال شد!",'success'=>'1');

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if($get_method['method_name']=="video_report")
  	{
  		$report_type=$get_method['report_type'];
  		$report=$get_method['report_text'];

		if($report)
		{

			$qry1="INSERT INTO tbl_reports (`user_id`,`email`,`video_id`,`type`,`report`) VALUES ('".$get_method['report_user_id']."','".$get_method['report_email']."','".$get_method['report_video_id']."','".$report_type."','".$report."')";



            $result1=mysqli_query($mysqli,$qry1);


			$set['ANDROID_REWARDS_APP'][] = array('msg' => 'گزارش با موفقیت ارسال شد ...','success'=>'1');
		}
		else
		{
			$set['ANDROID_REWARDS_APP'][] = array('msg' => 'لطفا متن گزارش را اضافه کنید','success'=>'0');
		}

  		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
    }
	else if($get_method['method_name']=="app_settings")
	{

		$jsonObj= array();

		$query="SELECT * FROM tbl_settings WHERE id='1'";
		$sql = mysqli_query($mysqli,$query);


		while($data = mysqli_fetch_assoc($sql))
		{
			$row['package_name'] = $data['package_name'];
			$row['app_name'] = $data['app_name'];
			$row['admob_limit'] = $settings_details['max_admob_watch'];
			$row['tapsell_limit'] = $settings_details['max_tapsell_watch'];
			$row['app_logo'] = $data['app_logo'];
			$row['app_version'] = $data['app_version'];
			$row['app_author'] = $data['app_author'];
			$row['app_contact'] = $data['app_contact'];
			$row['app_email'] = $data['app_email'];
			$row['app_website'] = $data['app_website'];
			$row['app_description'] = $data['app_description'];
			$row['app_developed_by'] = $data['app_developed_by'];

			$row['app_faq'] = stripslashes($data['app_faq']);

			$row['app_privacy_policy'] = stripslashes($data['app_privacy_policy']);

 			$row['publisher_id'] = $data['publisher_id'];
 			$row['interstital_ad'] = $data['interstital_ad'];
			$row['interstital_ad_id'] = $data['interstital_ad_id'];
 			$row['banner_ad'] = $data['banner_ad'];
 			$row['banner_ad_id'] = $data['banner_ad_id'];
 			$row['interstital_ad_click'] = $data['interstital_ad_click'];
 			$row['rewarded_video_ads'] = $data['rewarded_video_ads'];
 			$row['rewarded_video_ads_id'] = $data['rewarded_video_ads_id'];
 			$row['rewarded_video_click'] = $data['rewarded_video_click'];


 			$row['redeem_currency'] = $data['redeem_currency'];

 			$row['redeem_points'] = $data['redeem_points'];
 			$row['redeem_money'] = $data['redeem_money'];
 			$row['minimum_redeem_points'] = $data['minimum_redeem_points'];


 			$row['payment_method1'] = $data['payment_method1'] ? $data['payment_method1'] : "";
 			$row['payment_method2'] = $data['payment_method2'] ? $data['payment_method2'] : "";
 			$row['payment_method3'] = $data['payment_method3'] ? $data['payment_method3'] : "";
 			$row['payment_method4'] = $data['payment_method4'] ? $data['payment_method4'] : "";

 			$row['registration_reward_status'] = $data['registration_reward_status'];
 			$row['app_refer_reward_status'] = $data['app_refer_reward_status'];
 			$row['video_views_status'] = $data['video_views_status'];
 			$row['video_add_status'] = $data['video_add_status'];
 			$row['like_video_points_status'] = $data['like_video_points_status'];
 			$row['download_video_points_status'] = $data['download_video_points_status'];


 			$row['watermark_on_off'] = $data['watermark_on_off'];
 			$row['watermark_image'] = $data['watermark_image'] ? $file_path.'images/'.$data['watermark_image'] : "";

			array_push($jsonObj,$row);

		}

		$set['ANDROID_REWARDS_APP'] = $jsonObj;



		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	else
	{
	  		$get_method = checkSignSalt($_POST['data']);
	}


?>
