<?php include('includes/header.php'); 
	include("includes/connection.php");
	
    include("includes/function.php");
	include("language/language.php"); 

	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_users WHERE tbl_users.id!=0 AND tbl_users.name like '%".addslashes($_POST['search_value'])."%' OR tbl_users.email like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_users.id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {
	 
							$tableName="tbl_users";		
							$targetpage = "manage_users.php"; 	
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
							
							
						 $users_qry="SELECT * FROM tbl_users
						 WHERE tbl_users.id!=0 ORDER BY tbl_users.id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['user_id']))
	{ 
		Delete('tbl_video','user_id='.$_GET['user_id'].'');  
		Delete('tbl_comments','user_id='.$_GET['user_id'].''); 
		Delete('tbl_users_redeem','user_id='.$_GET['user_id'].''); 
		Delete('tbl_users_rewards_activity','user_id='.$_GET['user_id'].''); 
		Delete('tbl_follows','user_id='.$_GET['user_id'].'');
		Delete('tbl_follows','follower_id='.$_GET['user_id'].'');

		Delete('tbl_users','id='.$_GET['user_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:manage_users.php");
		exit;
	}


	if(isset($_POST['delete_rec']))
	{

	    $checkbox = $_POST['post_ids'];
	    
	    for($i=0;$i<count($checkbox);$i++){
	      
	      $del_id = $checkbox[$i]; 
	      
	        Delete('tbl_video','user_id='.$del_id.'');  
			Delete('tbl_comments','user_id='.$del_id.''); 
			Delete('tbl_users_redeem','user_id='.$del_id.''); 
			Delete('tbl_users_rewards_activity','user_id='.$del_id.''); 

	      Delete('tbl_users','id='.$del_id.'');
	 
	    }

	    $_SESSION['msg']="12";
	    header( "Location:manage_users.php");
	    exit;
	}
	
	//Active and Deactive status
	if(isset($_GET['status_deactive_id']))
	{
		$data = array('status'  =>  '0');
		
		$edit_status=Update('tbl_users', $data, "WHERE id = '".$_GET['status_deactive_id']."'");
		
		 $_SESSION['msg']="14";
		 header( "Location:manage_users.php");
		 exit;
	}
	if(isset($_GET['status_active_id']))
	{
		$data = array('status'  =>  '1');
		
		$edit_status=Update('tbl_users', $data, "WHERE id = '".$_GET['status_active_id']."'");
		
		$_SESSION['msg']="13";
		 header( "Location:manage_users.php");
		 exit;
	}
	
	
?>


 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">مدیریت کاربران</div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="جستجو ..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                    <div class="add_btn_primary"> <a href="add_user.php?add">اضافه کردن کاربر</a> </div>
                  </div>
                  
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
          <div class="col-md-12 mrg-top manage_user_btn">
            <form method="post" action="">
          	<button type="submit" class="btn btn-primary" style="margin-bottom:20px;" name="delete_rec" value="delete_post" onclick="return confirm('آیا خواهید موارد انتخاب شده را حذف کنید؟');">حذف</button>

            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width:30px">
                  	<div class="checkbox">
				    <input type="checkbox" name="checkall" id="checkall" value="">
				    <label for="checkall"></label>
				    </div>					
					همه
				  </th>		
                  <th style="width:110px">نام</th>						 
				  <th style="width:240px">ایمیل</th>
				  <th style="width:110px">شماره تلفن</th>
				  <th style="width:30px">امتیاز</th>				  
				  <th style="width:100px">وضعیت</th>	 
                  <th style="width:170px">گزینه ها</th>
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
                   <td><?php echo $users_row['name'];?></td>
		           <td><?php echo $users_row['email'];?></td>   
		           <td><?php echo $users_row['phone'];?></td>
		           <td><?php echo $users_row['total_point'];?></td>		           
		           <td>
		          		<?php if($users_row['status']!="0"){?>
		              <a href="manage_users.php?status_deactive_id=<?php echo $users_row['id'];?>" title="تغییر وضعیت"><span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>غیر فعال کردن</span></span></a>

		              <?php }else{?>
		              <a href="manage_users.php?status_active_id=<?php echo $users_row['id'];?>" title="تغییر وضعیت"><span class="badge badge-danger badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>فعال کردن </span></span></a>
		              <?php }?>
              		</td>
                   <td>
                   	<a href="manage_user_history.php?user_id=<?php echo $users_row['id'];?>" class="btn btn-success" data-toggle="tooltip" data-tooltip="تاریخچه کاربر"><i class="fa fa-history"></i></a>
                   	<a href="add_user.php?user_id=<?php echo $users_row['id'];?>" class="btn btn-primary" data-toggle="tooltip" data-tooltip="ویرایش"><i class="fa fa-edit"></i></a>
                    <a href="manage_users.php?user_id=<?php echo $users_row['id'];?>" onclick="return confirm('آیا میخواهید این کاربر را حذف کنید؟');" class="btn btn-default" data-toggle="tooltip" data-tooltip="حذف"><i class="fa fa-trash"></i></a></td>
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