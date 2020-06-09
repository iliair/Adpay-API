<?php 	include("includes/connection.php");
		include("includes/header.php");
		
		require("includes/function.php");
		require("language/language.php");
	 
	
  $qry="SELECT * FROM tbl_settings where id='1'";
  $result=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result);


  if(isset($_POST['submit']))
  {

    $img_res=mysqli_query($mysqli,"SELECT * FROM tbl_settings WHERE id='1'");
    $img_row=mysqli_fetch_assoc($img_res);
    

         if($_FILES['app_logo']['name']!="")
         {        

            unlink('images/'.$img_row['app_logo']);   

            $app_logo=$_FILES['app_logo']['name'];
            $pic1=$_FILES['app_logo']['tmp_name'];

            $tpath1='images/'.$app_logo;      
            copy($pic1,$tpath1);


              $data = array(      
              'email_from'  =>  $_POST['email_from'],
              'app_name'  =>  $_POST['app_name'],
              'app_logo'  =>  $app_logo,  
              'app_description'  => addslashes($_POST['app_description']),
              'app_version'  =>  $_POST['app_version'],
              'app_author'  =>  $_POST['app_author'],
              'app_contact'  =>  $_POST['app_contact'],
              'app_email'  =>  $_POST['app_email'],   
              'app_website'  =>  $_POST['app_website'],
              'app_developed_by'  =>  $_POST['app_developed_by']                     

              );

    }
    else
    {
  
                $data = array(
                'email_from'  =>  $_POST['email_from'],
                'app_name'  =>  $_POST['app_name'],
                'app_description'  => addslashes($_POST['app_description']),
                'app_version'  =>  $_POST['app_version'],
                'app_author'  =>  $_POST['app_author'],
                'app_contact'  =>  $_POST['app_contact'],
                'app_email'  =>  $_POST['app_email'],   
                'app_website'  =>  $_POST['app_website'],
                'app_developed_by'  =>  $_POST['app_developed_by']               

                  );

    } 

    $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
  

        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
  
  }
  if(isset($_POST['verify_purchase_submit']))
  {

            $data = array(
                  'envato_buyer_name' => $_POST['envato_buyer_name'],
                  'envato_purchase_code' => $_POST['envato_purchase_code'],
                  'envato_purchased_status' => 1,
                  'package_name' => $_POST['package_name'],
                  'ios_bundle_identifier' => $_POST['ios_bundle_identifier']
                    );
      
            $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");


            $config_file_default    = "includes/app.default";
            $config_file_name       = "api.php";    

            $config_file_path       = $config_file_name;

            $config_file = file_get_contents($config_file_default);
            //$config_file = str_replace("NEWS_APP", 'NEWS_APP_DEMO', $config_file);

            $f = @fopen($config_file_path, "w+");
            
            if(@fwrite($f, $config_file) > 0){

              echo "done";

            }
            
            $admin_url = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';
             verify_data_on_server($envato_buyer->item->id,$envato_buyer->buyer,$_POST['envato_purchase_code'],1,$admin_url);    
            
            $_SESSION['msg']="18";
            header( "Location:settings.php");
            exit;
  }

 
  

  if(isset($_POST['payment_submit']))
  {

        $data = array(
                'payment_method1' => $_POST['payment_method1'],
                'payment_method2' => $_POST['payment_method2'],
                'payment_method3' => $_POST['payment_method3'],
                'payment_method4' => $_POST['payment_method4'],
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
  
 
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
 
  }
 
  if(isset($_POST['watermark_submit']))
  {
        if($_FILES['watermark_image']['name']!="")
         {         

            $watermark_image=$_FILES['watermark_image']['name'];
            $pic1=$_FILES['watermark_image']['tmp_name'];

            $tpath1='images/'.$watermark_image;      
            copy($pic1,$tpath1);

            $data = array(                 
                'watermark_on_off' => $_POST['watermark_on_off'],
                'watermark_image' => $watermark_image
                  );
         }
         else
         {
            $data = array(                 
                'watermark_on_off' => $_POST['watermark_on_off']
                  );
         }

         
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
  
 
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
 
  }

  if(isset($_POST['admob_submit']))
  {

        $data = array(
                'publisher_id'  =>  $_POST['publisher_id'],
                'interstital_ad'  =>  $_POST['interstital_ad'],
                'interstital_ad_id'  =>  $_POST['interstital_ad_id'],
                'interstital_ad_click'  =>  $_POST['interstital_ad_click'],
                'banner_ad'  =>  $_POST['banner_ad'],
                'banner_ad_id'  =>  $_POST['banner_ad_id'],
                'rewarded_video_ads'  =>  $_POST['rewarded_video_ads'],
                'rewarded_video_ads_id'  =>  $_POST['rewarded_video_ads_id'],
                'rewarded_video_click'  =>  $_POST['rewarded_video_click']
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
   
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
  
  }
  
  if(isset($_POST['notification_submit']))
  {

        $data = array(
                'onesignal_app_id' => $_POST['onesignal_app_id'],
                'onesignal_rest_key' => $_POST['onesignal_rest_key'],
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
  
 
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
 
  }

 
  if(isset($_POST['api_submit']))
  {

        $data = array(
                'api_page_limit'  =>  $_POST['api_page_limit'],
                'api_latest_limit'  =>  $_POST['api_latest_limit'],
                'api_cat_order_by'  =>  $_POST['api_cat_order_by'],
                'api_cat_post_order_by'  =>  $_POST['api_cat_post_order_by'],
                'api_all_order_by'  =>  $_POST['api_all_order_by']
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
 

      if ($settings_edit > 0)
      {

        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;

      }   
 
  }


  if(isset($_POST['app_faq_submit']))
  {

        $data = array(
                'app_faq'  =>  addslashes($_POST['app_faq'])
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
 
 
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
    
 
  }

  if(isset($_POST['app_pri_poly']))
  {

        $data = array(
                'app_privacy_policy'  =>  addslashes($_POST['app_privacy_policy']) 
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
 
 
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
    
 
  }


?>
 
	 <div class="row">
      <div class="col-md-12">
        <div class="card">
		  <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">تنظیمات</div>
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
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
               
                <li role="presentation" class="active"><a href="#app_settings" aria-controls="app_settings" role="tab" data-toggle="tab">تنظیمات اپلیکیشن</a></li>  
                <li role="presentation"><a href="#payment_settings" aria-controls="payment_settings" role="tab" data-toggle="tab">حالت پرداخت</a></li> 
                <li role="presentation"><a href="#watermark_settings" aria-controls="watermark_settings" role="tab" data-toggle="tab">مشخصه (watermark)</a></li>
                <li role="presentation"><a href="#notification_settings" aria-controls="notification_settings" role="tab" data-toggle="tab">اعلان</a></li>
                <li role="presentation"><a href="#api_settings" aria-controls="api_settings" role="tab" data-toggle="tab">API</a></li>
                <li role="presentation"><a href="#api_faq" aria-controls="api_faq" role="tab" data-toggle="tab">سوالات متداول</a></li>
                <li role="presentation"><a href="#api_privacy_policy" aria-controls="api_privacy_policy" role="tab" data-toggle="tab">قوانین و مقررات</a></li>
            </ul>
          
           <div class="tab-content">
          
              <div role="tabpanel" class="tab-pane active" id="app_settings">	  
                <form action="" name="settings_from" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                  <label class="col-md-3 control-label">هاست ایمیل :
                      <p class="control-label-help">(توجه: این فیلد آدرس ایمیل فرستنده برای ارسال رمز عبور در صورت فراموشی به ایمیل مورد نظر است. e.g.info@example.com)</p>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="email_from" id="email_from" value="<?php echo $settings_row['email_from'];?>" class="form-control">
                    </div>
                  </div>                   
                  <div class="form-group">
                    <label class="col-md-3 control-label">نام اپلیکیشن : </label>
                    <div class="col-md-6">
                      <input type="text" name="app_name" id="app_name" value="<?php echo $settings_row['app_name'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="col-md-3 control-label">لوگو اپلیکیشن :</label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="app_logo" id="fileupload">
                         
                        	<?php if($settings_row['app_logo']!="") {?>
                        	  <div class="fileupload_img"><img type="image" src="images/<?php echo $settings_row['app_logo'];?>" alt="image" style="width: 100px;height: 100px;" /></div>
                        	<?php } else {?>
                        	  <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="image" /></div>
                        	<?php }?>
                        
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                       <label class="col-md-3 control-label">توضیحات اپلیکیشن :</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_description" id="app_description" class="form-control"><?php echo $settings_row['app_description'];?></textarea>

                      <script>CKEDITOR.replace( 'app_description' );</script>
                    </div>
                  </div>
                  <div class="form-group">&nbsp;</div>                 


                  <div class="form-group">
                         <label class="col-md-3 control-label">ورژن اپلیکیشن :</label>
                    <div class="col-md-6">
                      <input type="text" name="app_version" id="app_version" value="<?php echo $settings_row['app_version'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="col-md-3 control-label">نویسنده :</label>
                    <div class="col-md-6">
                      <input type="text" name="app_author" id="app_author" value="<?php echo $settings_row['app_author'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">شماره تماس :</label>
                    <div class="col-md-6">
                      <input type="text" name="app_contact" id="app_contact" value="<?php echo $settings_row['app_contact'];?>" class="form-control">
                    </div>
                  </div>     
                  <div class="form-group">
                    <label class="col-md-3 control-label">ایمیل :</label>
                    <div class="col-md-6">
                      <input type="text" name="app_email" id="app_email" value="<?php echo $settings_row['app_email'];?>" class="form-control">
                    </div>
                  </div>                 
                   <div class="form-group">
                    <label class="col-md-3 control-label">وب سایت :</label>
                    <div class="col-md-6">
                      <input type="text" name="app_website" id="app_website" value="<?php echo $settings_row['app_website'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">توسعه توسط :</label>
                    <div class="col-md-6">
                      <input type="text" name="app_developed_by" id="app_developed_by" value="<?php echo $settings_row['app_developed_by'];?>" class="form-control">
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
               

               <div role="tabpanel" class="tab-pane" id="payment_settings">
              <form action="" name="payment_settings" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">حالت پرداخت 1 : </label>
                    <div class="col-md-6">
                      <input type="text" name="payment_method1" id="payment_method1" value="<?php echo $settings_row['payment_method1'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">حالت پرداخت 2 :</label>
                    <div class="col-md-6">
                      <input type="text" name="payment_method2" id="payment_method2" value="<?php echo $settings_row['payment_method2'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">حالت پرداخت 3 :</label>
                    <div class="col-md-6">
                      <input type="text" name="payment_method3" id="payment_method3" value="<?php echo $settings_row['payment_method3'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">حالت پرداخت 4 :</label>
                    <div class="col-md-6">
                      <input type="text" name="payment_method4" id="payment_method4" value="<?php echo $settings_row['payment_method4'];?>" class="form-control">
                    </div>
                  </div>
                  <br/>
                  <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp;</label>
                    <div class="col-md-6">
                       <b>توجه: </b> اگر هر حالت پرداختی را خالی بگذارید در اپلیکیشن نمایش داده نمیشود.
                    </div>
                  </div>
                        
                  <div class="form-group">
                  <div class="col-md-9 col-md-offset-3">
                    <button type="submit" name="payment_submit" class="btn btn-primary">ذخیره</button>
                  </div>
                  </div>
                </div>
                </div>
              </form>
            </div> 
            <div role="tabpanel" class="tab-pane" id="watermark_settings">
              <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">مشخصه (watermark) : </label>
                    <div class="col-md-6">
                        <select name="watermark_on_off" id="watermark_on_off" class="select2">
                          <option value="true" <?php if($settings_row['watermark_on_off']=='true'){?>selected<?php }?>>فعال</option>
                          <option value="false" <?php if($settings_row['watermark_on_off']=='false'){?>selected<?php }?>>غیر فعال</option>              
                        </select>                        
                    </div>                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">تصویر مشخصه (watermark):
                      <p class="control-label-help">(رزولوشن توصیه شده: 100x100، 80x80)</p>
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="watermark_image" id="fileupload">
                         
                          <?php if($settings_row['watermark_image']!="") {?>
                            <div class="fileupload_img"><img type="image" src="images/<?php echo $settings_row['watermark_image'];?>" alt="image" style="width: 100px;height: 100px;" /></div>
                          <?php } else {?>
                            <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="image" /></div>
                          <?php }?>
                        
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                  <div class="col-md-9 col-md-offset-3">
                    <button type="submit" name="watermark_submit" class="btn btn-primary">ذخیره</button>
                  </div>
                  </div>
                </div>
                </div>
              </form>
            </div> 
			   
               
			  <div role="tabpanel" class="tab-pane" id="notification_settings">
              <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">OneSignal App ID : </label>
                    <div class="col-md-6">
                      <input type="text" name="onesignal_app_id" id="onesignal_app_id" value="<?php echo $settings_row['onesignal_app_id'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">OneSignal Rest Key : </label>
                    <div class="col-md-6">
                      <input type="text" name="onesignal_rest_key" id="onesignal_rest_key" value="<?php echo $settings_row['onesignal_rest_key'];?>" class="form-control">
                    </div>
                  </div>              
                  <div class="form-group">
                  <div class="col-md-9 col-md-offset-3">
                    <button type="submit" name="notification_submit" class="btn btn-primary">ذخیره</button>
                  </div>
                  </div>
                </div>
                </div>
              </form>
            </div>             
              <div role="tabpanel" class="tab-pane" id="api_settings">   
                <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  
                  <div class="form-group">
                    <div class="col-md-6">
                    </div>
                  </div> <hr/> 
                  <div class="form-group">
                    <label class="col-md-3 control-label">محدودیت صفحه بندی :</label>
                    <div class="col-md-6">
                       
                      <input type="number" name="api_page_limit" id="api_page_limit" value="<?php echo $settings_row['api_page_limit'];?>" class="form-control"> 
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">تعداد ویدئو در آخرین ها : </label>
                    <div class="col-md-6">
                       
                      <input type="number" name="api_latest_limit" id="api_latest_limit" value="<?php echo $settings_row['api_latest_limit'];?>" class="form-control"> 
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">ترتیب لیست دسته بندی به صورت :</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_cat_order_by" id="api_cat_order_by" class="select2">
                          <option value="cid" <?php if($settings_row['api_cat_order_by']=='cid'){?>selected<?php }?>>شماره</option>
                          <option value="category_name" <?php if($settings_row['api_cat_order_by']=='category_name'){?>selected<?php }?>>نام</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">ترتیب دسته بندی ویدئو : </label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_cat_post_order_by" id="api_cat_post_order_by" class="select2">
                          <option value="ASC" <?php if($settings_row['api_cat_post_order_by']=='ASC'){?>selected<?php }?>>صعودی</option>
                          <option value="DESC" <?php if($settings_row['api_cat_post_order_by']=='DESC'){?>selected<?php }?>>نزولی</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">ترتیب نمایش کلی ویدئو ها :</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_all_order_by" id="api_all_order_by" class="select2">
                          <option value="ASC" <?php if($settings_row['api_all_order_by']=='ASC'){?>selected<?php }?>>صعودی</option>
                          <option value="DESC" <?php if($settings_row['api_all_order_by']=='DESC'){?>selected<?php }?>>نزولی</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  
                
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="api_submit" class="btn btn-primary">ذخیره</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 
              <div role="tabpanel" class="tab-pane" id="api_faq">   
                <form action="" name="api_faq" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">سوالات متداول اپلیکیشن :</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_faq" id="app_faq" class="form-control"><?php echo stripslashes($settings_row['app_faq']);?></textarea>

                      <script>CKEDITOR.replace( 'app_faq' );</script>
                    </div>
                  </div>
                  
                  <br>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="app_faq_submit" class="btn btn-primary">ذخیره</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 
              <div role="tabpanel" class="tab-pane" id="api_privacy_policy">   
                <form action="" name="api_privacy_policy" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">قوانین و مقررات اپلیکیشن :</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_privacy_policy" id="privacy_policy" class="form-control"><?php echo stripslashes($settings_row['app_privacy_policy']);?></textarea>

                      <script>CKEDITOR.replace( 'privacy_policy' );</script>
                    </div>
                  </div>
                  
                  <br>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="app_pri_poly" class="btn btn-primary">ذخیره</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 

            </div>   

          </div>
        </div>
      </div>
    </div>

        
<?php include("includes/footer.php");?>       
