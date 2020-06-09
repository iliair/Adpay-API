<?php   include("includes/connection.php");
        include("includes/function.php");   

        $get_method = checkSignSalt($_POST['data']); 
        
        if($get_method['method_name']=="user_video_upload")
        {            

            if( isset($_SERVER['HTTPS'] ) ) {  

              $file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/uploads/';
            }
            else
            {
              $file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/uploads/';
            }

            $path = "uploads/"; //set your folder path
            $video_local=rand(0,99999)."_".str_replace(" ", "-", $_FILES['video_local']['name']);

            $tmp = $_FILES['video_local']['tmp_name'];
            
            if (move_uploaded_file($tmp, $path.$video_local)) 
            { //check if it the file move successfully.
                //echo $video_local;
                $video_url=$file_path.$video_local;
            }
               
              $video_thumbnail=rand(0,99999)."_".$_FILES['video_thumbnail']['name'];
       
              //Main Image
              $tpath1='images/'.$video_thumbnail;        
              $pic1=compress_image($_FILES["video_thumbnail"]["tmp_name"], $tpath1, 80);
         
              //Thumb Image 
              $thumbpath='images/thumbs/'.$video_thumbnail;   
              $thumb_pic1=create_thumb_image($tpath1,$thumbpath,'200','200');   

              $video_id='';

              $user_id =$_POST['user_id'];  

              $data = array( 
                  'user_id'  =>  $user_id,
                  'cat_id'  =>  $_POST['cat_id'],
                  'video_type'  =>  'local',
                  'video_title'  =>  $_POST['video_title'],
                  'video_url'  =>  $video_url,
                  'video_id'  =>  $video_id,
                  'video_layout'  =>  $_POST['video_layout'],
                  'video_thumbnail'  =>  $video_thumbnail,
                  'video_duration'  =>  $_POST['video_duration'],
                  'status'  => 0
                        );      

              $qry = Insert('tbl_video',$data);

 
 
            $set['ANDROID_REWARDS_APP'][] = array('msg'=>'ویدئو با موفقیت آپلود شد!','success'=>1);
    
            header( 'Content-Type: application/json; charset=utf-8' );
              echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
        else
        {
            $get_method = checkSignSalt($_POST['data']); 
        }
 
?>