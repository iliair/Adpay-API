<?php //error_reporting(0);

/**
 * Copyright 2017 Viaviweb.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
 

#Admin Login
function adminUser($username, $password){
    
    global $mysqli;

    $sql = "SELECT id,username FROM tbl_admin where username = '".$username."' and password = '".md5($password)."'";       
    $result = mysqli_query($mysqli,$sql);
    $num_rows = mysqli_num_rows($result);
     
    if ($num_rows > 0){
        while ($row = mysqli_fetch_array($result)){
            echo $_SESSION['ADMIN_ID'] = $row['id'];
                        echo $_SESSION['ADMIN_USERNAME'] = $row['username'];
                                      
        return true; 
        }
    }
    
}


# Insert Data 
function Insert($table, $data){

    global $mysqli;
    //print_r($data);

    $fields = array_keys( $data );  
    $values = array_map( array($mysqli, 'real_escape_string'), array_values( $data ) );
    
   //echo "INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');";
   //exit;  
    mysqli_query($mysqli, "INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');") or die( mysqli_error($mysqli) );

}

// Update Data, Where clause is left optional
function Update($table_name, $form_data, $where_clause='')
{   
    global $mysqli;
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // start the actual SQL statement
    $sql = "UPDATE ".$table_name." SET ";

    // loop and build the column /
    $sets = array();
    foreach($form_data as $column => $value)
    {
         $sets[] = "`".$column."` = '".$value."'";
    }
    $sql .= implode(', ', $sets);

    // append the where statement
    $sql .= $whereSQL;
         
    // run and return the query result
    return mysqli_query($mysqli,$sql);
}

 
//Delete Data, the where clause is left optional incase the user wants to delete every row!
function Delete($table_name, $where_clause='')
{   
    global $mysqli;
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add keyword
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // build the query
    $sql = "DELETE FROM ".$table_name.$whereSQL;
     
    // run and return the query result resource
    return mysqli_query($mysqli,$sql);
}  
 
//GCM function
function Send_GCM_msg($registration_id,$data)
{
    $data1['data']=$data;
 
    $url = 'https://fcm.googleapis.com/fcm/send';
  
    $registatoin_ids = array($registration_id);
     // $message = array($data);
   
         $fields = array(
             'registration_ids' => $registatoin_ids,
             'data' => $data1,
         );
  
         $headers = array(
             'Authorization: key='.APP_GCM_KEY.'',
             'Content-Type: application/json'
         );
         // Open connection
         $ch = curl_init();
  
         // Set the url, number of POST vars, POST data
         curl_setopt($ch, CURLOPT_URL, $url);
  
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
         // Disabling SSL Certificate support temporarly
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
  
         // Execute post
         $result = curl_exec($ch);
         if ($result === FALSE) {
             die('Curl failed: ' . curl_error($ch));
         }
  
         // Close connection
         curl_close($ch);
       //echo $result;exit;
}


//Image compress
function compress_image($source_url, $destination_url, $quality) 
{

    $info = getimagesize($source_url);

        if ($info['mime'] == 'image/jpeg')
              $image = imagecreatefromjpeg($source_url);

        elseif ($info['mime'] == 'image/gif')
              $image = imagecreatefromgif($source_url);

      elseif ($info['mime'] == 'image/png')
              $image = imagecreatefrompng($source_url);

        imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}

//Create Thumb Image
function create_thumb_image($target_folder ='',$thumb_folder = '', $thumb_width = '',$thumb_height = '')
 {  
     //folder path setup
         $target_path = $target_folder;
         $thumb_path = $thumb_folder;  
          

         $thumbnail = $thumb_path;
         $upload_image = $target_path;

            list($width,$height) = getimagesize($upload_image);
            $thumb_create = imagecreatetruecolor($thumb_width,$thumb_height);
            switch($file_ext){
                case 'jpg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;
                case 'jpeg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;
                case 'png':
                    $source = imagecreatefrompng($upload_image);
                    break;
                case 'gif':
                    $source = imagecreatefromgif($upload_image);
                     break;
                default:
                    $source = imagecreatefromjpeg($upload_image);
            }
       imagecopyresized($thumb_create, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width,$height);
            switch($file_ext){
                case 'jpg' || 'jpeg':
                    imagejpeg($thumb_create,$thumbnail,80);
                    break;
                case 'png':
                    imagepng($thumb_create,$thumbnail,80);
                    break;
                case 'gif':
                    imagegif($thumb_create,$thumbnail,80);
                     break;
                default:
                    imagejpeg($thumb_create,$thumbnail,80);
            }
 }

 function user_reward_activity($video_id="",$user_id,$activity_type,$rewards_points)
    {
        global $mysqli;
    
      if($video_id!="" AND $user_id!="" AND $activity_type!="")
      {
            $qry = "SELECT * FROM tbl_users_rewards_activity WHERE  video_id = '".$video_id."' and user_id = '".$user_id."' and activity_type = '".$activity_type."'";
            $result = mysqli_query($mysqli,$qry);
            $num_rows = mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            
            if ($num_rows <= 0)
                {                      
                     $data = array( 
                     'video_id'  =>  $video_id,
                     'user_id'  =>  $user_id,
                     'activity_type'  =>  $activity_type,
                     'points'  =>  $rewards_points
                      );
                  
                     $qry = Insert('tbl_users_rewards_activity',$data);

                     return  $qry; 
                    
                } 
      }      
      else
      {
            $data = array(                    
                     'user_id'  =>  $user_id,
                     'activity_type'  =>  $activity_type,
                     'points'  =>  $rewards_points
                      );
                  
             $qry = Insert('tbl_users_rewards_activity',$data);

              return  $qry; 
      }

        
                 
    }

function checkSignSalt($data_info){

        $key="viaviweb";

        $data_json = $data_info;

        $data_arr = json_decode(urldecode(base64_decode($data_json)),true);


        if($data_arr['sign'] == '' && $data_arr['salt'] == '' ){
            //$data['data'] = array("status" => -1, "message" => "Invalid sign salt.");
        
            $set['ANDROID_REWARDS_APP'] = array("status" => -1, "message" => "ورود نامعتبر!");
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            exit();


        }else{
            
            $data_arr['salt'];    
            
            $md5_salt=md5($key.$data_arr['salt']);

            if($data_arr['sign']!=$md5_salt){

                //$data['data'] = array("status" => -1, "message" => "Invalid sign salt.");
                $set['ANDROID_REWARDS_APP'] = array("status" => -1, "message" => "ورود نامعتبر!");   
                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                exit();
            }
        }
        
        return $data_arr;
        
    }

function verify_envato_purchase_code($product_code)
{ 
  
$url = "https://api.envato.com/v3/market/author/sale?code=".$product_code;
$curl = curl_init($url);


$personal_token = "M8tF6z8lzZBBkmZt4xm3dU4lw7Rlbrwp";
$header = array();
$header[] = 'Authorization: Bearer '.$personal_token;
$header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
$header[] = 'timeout: 20';
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

$envatoRes = curl_exec($curl);
curl_close($curl);
$envatoRes = json_decode($envatoRes);
 

 return $envatoRes;


//echo $envatoRes->buyer;

 /*if (isset($envatoRes->item->name)) {   
    $data = " - VERIFIED ({$envatoRes->item->name}) (Supported: {$result})";
} else {  
    $data= " FAILED";
} 

echo $data;*/
 
}

function verify_data_on_server($product_id,$buyer_name,$purchase_code,$purchased_status,$admin_url,$package_name)
{  

     

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"http://www.secureapp.viaviweb.in/verified_user.php");
    curl_setopt($ch, CURLOPT_POST, true);
    //curl_setopt($ch, CURLOPT_POSTFIELDS,"envato_product_id=1&envato_buyer_name=2&envato_purchase_code=2&envato_purchased_status=3&buyer_admin_url=4"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array('envato_product_id' => $product_id,'envato_buyer_name' => $buyer_name,'envato_purchase_code' => $purchase_code,'envato_purchased_status' => $purchased_status,'buyer_admin_url' => $admin_url,'package_name' => $package_name)));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);

     
   if ($server_output == "success") { echo "done"; } else { echo "fail!";}     
}


?>