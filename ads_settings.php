<?php include("includes/header.php");

	require("includes/function.php");
	require("language/language.php");
	 
	
	$qry="SELECT * FROM tbl_settings where id='1'";
  $result=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result);


	  
	if(isset($_POST['admob_submit']))
  {

        
        if($_POST['banner_ad'])
        {
            $banner_ad="true";
        }
        else
        {
            $banner_ad="false";
        }

        if($_POST['interstital_ad'])
        {
            $interstital_ad="true";
        }
        else
        {
            $interstital_ad="false";
        }

        if($_POST['rewarded_video_ads'])
        {
            $rewarded_video_ads="true";
        }
        else
        {
            $rewarded_video_ads="false";
        }



        $data = array(
                'publisher_id'  =>  $_POST['publisher_id'],
                'interstital_ad'  =>  $interstital_ad,
                'interstital_ad_id'  =>  $_POST['interstital_ad_id'],
                'interstital_ad_click'  =>  $_POST['interstital_ad_click'],
                'banner_ad'  =>  $banner_ad,
                'banner_ad_id'  =>  $_POST['banner_ad_id'],
                'rewarded_video_ads'  => $rewarded_video_ads,
                'rewarded_video_ads_id'  =>  $_POST['rewarded_video_ads_id'],
                'rewarded_video_click'  =>  $_POST['rewarded_video_click']
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
   
        $_SESSION['msg']="11";
        header( "Location:ads_settings.php");
        exit;
  
  }
 


?>
 
<div class="row">
    <div class="col-md-12">
      <div class="card">
    <div class="card-body">
      <div class="rewards_point_page_title">
        <div class="col-md-6 col-xs-12">
        <div class="page_title" style="font-size: 20px;color: #424242;">
          <h3>تنظیمات تبلیغات</h3>
        </div>
        </div>
        <div class="col-md-6 col-xs-12">
          <form action="" name="admob_settings" method="post" class="" enctype="multipart/form-data">
        <div class="form-group" id="publisher_id">          
		  <label class="col-md-4 pt_right field_lable" style="margin-top:0">Publisher ID :-
			<span>
			<a id="button" href="#target-content5" class="lable_tooltip">(?)
			  <span class="tooltip_text">Publisher ID</span>
			</a>
			<div id="target-content5">  
			  <div id="target-inner">
			  <a href="#publisher_id" class="close">X</a>
			  <img src="images/publisher_id.png" alt="publisher_id" />   
			  </div>
			</div>
			</span>
		  </label>
          <div class="col-md-8 pt_right publisher_input">
          <input type="text" name="publisher_id" id="publisher_id" value="<?php echo $settings_row['publisher_id'];?>" class="form-control">
          </div>
        </div>
        </div>
      </div>
    </div>
        <div class="clearfix"></div>
        <div class="card-body pt_top">
          
            <div class="section">
              <div class="section-body">
                <div class="form-group redeem_point_block" style="width:100%;">
                    <div class="col-md-4">
                        <div class="banner_ads_block">
                          <div class="banner_ad_item">
                            <label class="control-label">Banner Ads:-</label>
                            <div class="row toggle_btn">
                              <input type="checkbox" id="checked1" name="banner_ad" value="true" class="cbx hidden" <?php if($settings_row['banner_ad']=='true'){?>checked <?php }?> />
                              <label for="checked1" class="lbl"></label>
                            </div>
                          </div>              
                          <div class="col-md-12">             
                            <div class="form-group" id="#admob_banner_id">                              
                                <p class="field_lable">Banner Ad ID :-
                                <span>
                                <a id="button" href="#target-content1" class="lable_tooltip">(?)
                                  <span class="tooltip_text">Banner Ad ID</span>
                                </a>
                                <div id="target-content1">  
                                  <div id="target-inner">
                                  <a href="#admob_banner_id" class="close">X</a>
                                  <img src="images/admob_banner_id.png" alt="admob_banner_id" />   
                                  </div>
                                </div>
                                </span>
                              </p>
                              <div class="col-md-12">
                                <input type="text" name="banner_ad_id" id="banner_ad_id" value="<?php echo $settings_row['banner_ad_id'];?>" class="form-control">
                              </div>
                            </div>                            
                          </div>
                        </div>
                      </div>
            
                      <div class="col-md-4">
                        <div class="interstital_ads_block">
                          <div class="interstital_ad_item">
                            <label class="control-label">Interstital Ads:-</label>
                            <div class="row toggle_btn">
                              <input type="checkbox" id="checked2" name="interstital_ad" value="true" class="cbx hidden" <?php if($settings_row['interstital_ad']=='true'){?>checked <?php }?>/>
                              <label for="checked2" class="lbl"></label>
                            </div>
                          </div>
                          <div class="col-md-12">             
                            <div class="form-group" id="interstital_ad_id">                              
                                <p class="field_lable">Interstital Ad ID :-
                                <span>
                                <a id="button" href="#target-content2" class="lable_tooltip">(?)
                                  <span class="tooltip_text">Interstital Ad ID</span>
                                </a>
                                <div id="target-content2">  
                                  <div id="target-inner">
                                  <a href="#interstital_ad_id" class="close">X</a>
                                  <img src="images/admob_banner_id.png" alt="admob_banner_id" />   
                                  </div>
                                </div>
                                </span>
                              </p>
                              <div class="col-md-12">
                                <input type="text" name="interstital_ad_id" id="interstital_ad_id" value="<?php echo $settings_row['interstital_ad_id'];?>" class="form-control">
                              </div>
                            </div>
                              <p class="field_lable">Interstital Ad Clicks :-</p>
                              <div class="col-md-12"> 
                                  <input type="text" name="interstital_ad_click" id="interstital_ad_click" value="<?php echo $settings_row['interstital_ad_click'];?>" class="form-control">                                 
                              </div>
                          </div>
                        </div>
                      </div>
            
					<div class="col-md-4">
                        <div class="banner_ads_block">
                          <div class="banner_ad_item">
                            <label class="control-label">Rewarded Video Ads:-</label>
                            <div class="row toggle_btn">
                              <input type="checkbox" id="checked3" name="rewarded_video_ads" value="true" class="cbx hidden" <?php if($settings_row['rewarded_video_ads']=='true'){?>checked <?php }?>/>
                              <label for="checked3" class="lbl"></label>
                            </div>
                          </div>
                          <div class="col-md-12">             
                            <div class="form-group" id="rewarded_video_ad_id">                              
                                <p class="field_lable">Rewarded Video Ad ID :-
                                <span>
                                <a id="button" href="#target-content3" class="lable_tooltip">(?)
                                  <span class="tooltip_text">Rewarded Video Ad ID</span>
                                </a>
                                <div id="target-content3">  
                                  <div id="target-inner">
                                  <a href="#rewarded_video_ad_id" class="close">X</a>
                                  <img src="images/admob_banner_id.png" alt="admob_banner_id" />   
                                  </div>
                                </div>
                                </span>
                              </p>
                              <div class="col-md-12">
                                <input type="text" name="rewarded_video_ads_id" id="rewarded_video_ads_id" value="<?php echo $settings_row['rewarded_video_ads_id'];?>" class="form-control">
                              </div>
                              <p class="field_lable">Rewarded Ad After Activity Clicks :-</p>
                              <div class="col-md-12">                                 
                                  <input type="text" name="rewarded_video_click" id="rewarded_video_click" value="<?php echo $settings_row['rewarded_video_click'];?>" class="form-control">                                 
                              </div>
                            </div>                            
                          </div>
                        </div>
                      </div>                    
                    </div>                
                </div>
            </div>
         
      <div align="center" class="form-group">
              <div class="col-md-12">
                <button type="submit" name="admob_submit" class="btn btn-primary ">Save</button>
              </div>
            </div>
          </form>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    
        
<?php include("includes/footer.php");?>       
