<?php include("includes/header.php");
	include("includes/connection.php");
  
    include("includes/function.php");
	include("language/language.php"); 

 	require_once("thumbnail_images.class.php");
	 
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
	 
	 
	if(isset($_POST['submit']) and isset($_GET['add']))
	{		
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
        	$user_image='';
        }


			$data = array(
			'user_type'=>'Normal',	
			'user_code'  =>createRandomCode(),
			'name'  =>  $_POST['name'],
			'email'  =>  $_POST['email'],
			'password'  =>  $_POST['password'],
			'phone'  =>  $_POST['phone'],
			'user_image'  =>  $user_image
			);	

			$qry = Insert('tbl_users',$data);

			$_SESSION['msg']="10";
			header("location:manage_users.php");	 
			exit;
		
	}
	
	if(isset($_GET['user_id']))
	{
			 
			$user_qry="SELECT * FROM tbl_users where id='".$_GET['user_id']."'";
			$user_result=mysqli_query($mysqli,$user_qry);
			$user_row=mysqli_fetch_assoc($user_result);
		
	}
	
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
				header("Location:add_user.php?user_id=".$_POST['user_id']);
				exit;
			 
	 
	}
	
	
?>
 	

 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php if(isset($_GET['user_id'])){?>ویرایش<?php }else{?>اضافه کردن<?php }?> کاربر</div>
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
          <div class="card-body mrg_bottom"> 
            <form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data" >
            	<input  type="hidden" name="user_id" value="<?php echo $_GET['user_id'];?>" />

              <div class="section">
                <div class="section-body">
				
				
                  <div class="form-group">
                    <label class="col-md-3 control-label">نام : </label>
                    <div class="col-md-6">
                      <input type="text" name="name" id="name" value="<?php if(isset($_GET['user_id'])){echo $user_row['name'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">ایمیل : </label>
                    <div class="col-md-6">
                      <input type="email" name="email" id="email" value="<?php if(isset($_GET['user_id'])){echo $user_row['email'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">رمز عبور :</label>
                    <div class="col-md-6">
                      <input type="password" name="password" id="password" value="" class="form-control" <?php if(!isset($_GET['user_id'])){?>required<?php }?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">شماره تلفن : </label>
                    <div class="col-md-6">
                      <input type="text" name="phone" id="phone" value="<?php if(isset($_GET['user_id'])){echo $user_row['phone'];}?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">لینک یوتیوب : </label>
                    <div class="col-md-6">
                      <input type="text" name="user_youtube" id="user_youtube" value="<?php if(isset($_GET['user_id'])){echo $user_row['user_youtube'];}?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">لینک اینستاگرام :</label>
                    <div class="col-md-6">
                      <input type="text" name="user_instagram" id="user_instagram" value="<?php if(isset($_GET['user_id'])){echo $user_row['user_instagram'];}?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">تصویر کاربر :
                      <p class="control-label-help">(رزولوشن توصیه شده: W: 400 * H: 200)</p>
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="user_image" value="fileupload" id="fileupload">
                            
                            <?php if(isset($_GET['user_id']) and $user_row['user_image']!="") {?>
                            <div class="fileupload_img"><img type="image" src="images/<?php echo $user_row['user_image'];?>" alt="image" style="width: 100px;height: 90px;"/></div>	
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