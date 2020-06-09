<?php  include("includes/header.php");
	include("includes/connection.php");
  
    include("includes/function.php");
	include("language/language.php"); 

	include("includes/jdf.php"); 
	
	 function get_video_info($post_id)
	 {
	 	global $mysqli;

	 	$query="SELECT * FROM tbl_video WHERE tbl_video.id='".$post_id."'";
	 	 
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
		$row=mysqli_fetch_assoc($sql);

		return stripslashes($row['video_title']);
	 }
	 
							$tableName="tbl_comments";		
							$targetpage = "manage_comments.php"; 	
							$limit = 15; 
							
							$query = "SELECT COUNT(*) as num FROM $tableName";
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
							
							
						 $users_qry="SELECT * FROM tbl_comments
						 ORDER BY tbl_comments.id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 
	if(isset($_GET['comment_id']))
	{
		  
		 
		Delete('tbl_comments','id='.$_GET['comment_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:manage_comments.php");
		exit;
	}
	  function get_user_info($user_id)
   {
    global $mysqli;

     
    $user_qry="SELECT * FROM tbl_users where id='".$user_id."'";
    $user_result=mysqli_query($mysqli,$user_qry);
    $user_row=mysqli_fetch_assoc($user_result);

    return $user_row;
   }
 if(isset($_POST['delete_rec']))
  {

    $checkbox = $_POST['post_ids'];
    
    for($i=0;$i<count($checkbox);$i++){
      
      $del_id = $checkbox[$i]; 
     
      Delete('tbl_comments','id='.$del_id.'');
 
    }

    $_SESSION['msg']="12";
    header( "Location:manage_comments.php");
    exit;
  } 
	
	
?>


 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">مدیریت نظرات</div>
            </div>
             
          </div>
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
          <div class="col-md-12 mrg-top manage_comment_btn">

          	<form method="post" action="">
          	<button type="submit" class="btn btn-primary" style="margin-bottom:20px;" name="delete_rec" value="delete_post" onclick="return confirm('آیا میخواهید این نظرات را حذف کنید؟');">حذف</button>	

            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width:40px">
                  	<div class="checkbox">
				    <input type="checkbox" name="checkall" id="checkall" value="">
				    <label for="checkall"></label>
				    </div>					
					همه
				  </th>	
  				  <th>ویدئو</th>
 				  <th>نام</th>		
				  <th style="width:200px">متن نظر</th>
				  <th style="width:120px">تاریخ</th>	 
                  <th class="cat_action_list" style="width:60px">گزینه</th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{
						 
				?>
                <tr>
                   <td> 
        				 
        			<div>
				      <div class="checkbox">
				        <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $users_row['id']; ?>">
				        <label for="checkbox<?php echo $i;?>">
				        </label>
				      </div>
				      
				    </div>
      			   </td>	
  		           <td><?php echo get_video_info($users_row['post_id']);?></td>
 		           <td><?php echo get_user_info($users_row['user_id'])['name'];?></td>
     		       <td><?php echo $users_row['comment_text'];?></td>
                   <td><?php echo jdate('Y/m/d',strtotime($users_row['dt_rate']));?></td>           
                   <td> 
                    <a href="manage_comments.php?comment_id=<?php echo $users_row['id'];?>" onclick="return confirm('آیا میخواهید این نظر را حذف کنید؟');" class="btn btn-default" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></td>
                </tr>
               <?php
						
						$i++;
						}
			   ?>
              </tbody>
            </table>

           </form> 
          </div>
          <div class="col-md-12 col-xs-12">
            <div class="pagination_item_block">
              <nav>
              	<?php if(!isset($_POST["search"])){ include("pagination.php");}?>                 
              </nav>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>     



<?php include('includes/footer.php');?>          