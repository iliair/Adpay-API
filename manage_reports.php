<?php include("includes/header.php");

  require("includes/function.php");
  require("language/language.php");
  
  function get_user_info($user_id)
   {
    global $mysqli;

     
    $user_qry="SELECT * FROM tbl_users where id='".$user_id."'";
    $user_result=mysqli_query($mysqli,$user_qry);
    $user_row=mysqli_fetch_assoc($user_result);

    return $user_row;
   }
    
  // Get page data
  $tableName="tbl_reports";    
  $targetpage = "manage_reports.php";  
  $limit = 15; 
  
  $query = "SELECT COUNT(*) as num FROM $tableName LEFT JOIN tbl_video ON tbl_reports.video_id= tbl_video.id ORDER BY tbl_reports.id";
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


   $qry="SELECT tbl_reports.*,tbl_video.video_title FROM tbl_reports
LEFT JOIN tbl_video ON tbl_reports.video_id= tbl_video.id ORDER BY tbl_reports.id DESC LIMIT $start, $limit";   
  $result=mysqli_query($mysqli,$qry);
 
  
  if(isset($_GET['report_id']))
  {
    Delete('tbl_reports','id='.$_GET['report_id'].'');

    $_SESSION['msg']="12";
    header( "Location:manage_reports.php");
    exit;
     
  } 


  if(isset($_POST['delete_rec']))
  {

    $checkbox = $_POST['post_ids'];
    
    for($i=0;$i<count($checkbox);$i++){
      
      $del_id = $checkbox[$i]; 
     
      Delete('tbl_reports','id='.$del_id.'');
 
    }

    $_SESSION['msg']="12";
    header( "Location:manage_reports.php");
    exit;
  } 
   
?>
    <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">مدیریت گزارشات تخلف</div>
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
          <div class="col-md-12 mrg-top manage_report_btn">

            <form method="post" action="">
            <button type="submit" class="btn btn-primary" style="margin-bottom:20px;" name="delete_rec" value="delete_post" onclick="return confirm('آیا میخواهید این موارد را حذف کنید؟');">حذف</button>

            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width:40px">
                    <div class="checkbox">
                    <input type="checkbox" name="checkall" id="checkall" value="">
                    <label for="checkall"></label>
                    </div>          					همه
                  </th> 
                  <th style="width:120px;">نام</th>
                  <th style="width:140px;">ایمیل</th>
                  <th style="width:190px;">ویدئوی گزارش شده</th>
                  <th style="width:120px;">نوع تخلف</th>
                  <th style="width:130px;">متن گزارش</th> 
                  <th class="cat_action_list" style="width: 40px;">گزینه</th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($row=mysqli_fetch_array($result))
						{
						 
				?>
                <tr>
                  <td> 
                 
                  <div>
                  <div class="checkbox">
                    <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $row['id']; ?>">
                    <label for="checkbox<?php echo $i;?>">
                    </label>
                  </div>
                  
                </div>
               </td>
                  <td><?php echo get_user_info($row['user_id'])['name'];?></td>
                  <td><?php if(get_user_info($row['user_id'])['email']){ echo get_user_info($row['user_id'])['email'];}else{ echo $row['email']; } ;?></td>                  
                  <td><?php echo $row['video_title'];?></td>
                  <td><?php echo $row['type'];?></td>
                  <td style="width: 120px;"><p><?php echo $row['report'];?></p></td>                  
                  <td>
                    <a href="?report_id=<?php echo $row['id'];?>" onclick="return confirm('آیا میخواهید این گزارش را حذف کنید؟');" class="btn btn-default" class="btn btn-default" data-toggle="tooltip" data-tooltip="حذف"><i class="fa fa-trash"></i></a></td>
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
               
        
<?php include("includes/footer.php");?>       
