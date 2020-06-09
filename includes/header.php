<?php include("includes/connection.php");
      include("includes/session_check.php");

      //Get file name
      $currentFile = $_SERVER["SCRIPT_NAME"];
      $parts = Explode('/', $currentFile);
      $currentFile = $parts[count($parts) - 1];


?>
<!DOCTYPE html>
<html>
<head>
<meta name="author" content="">
<meta name="description" content="">
<meta http-equiv="Content-Type"content="text/html;charset=UTF-8"/>
<meta name="viewport"content="width=device-width, initial-scale=1.0">
<title><?php echo APP_NAME;?></title>
<link rel="stylesheet" type="text/css" href="assets/css/vendor.css">
<link rel="stylesheet" type="text/css" href="assets/css/flat-admin.css">

<link rel="stylesheet" type="text/css" href="assets/css/style-fa.css">
<!-- Theme -->
<link rel="stylesheet" type="text/css" href="assets/css/theme/blue-sky.css">
<link rel="stylesheet" type="text/css" href="assets/css/theme/blue.css">
<link rel="stylesheet" type="text/css" href="assets/css/theme/red.css">
<link rel="stylesheet" type="text/css" href="assets/css/theme/yellow.css">

 <script src="assets/ckeditor/ckeditor.js"></script>

</head>
<body>
<div class="app app-default">
  <aside class="app-sidebar" id="sidebar">
    <div class="sidebar-header"> <a class="sidebar-brand" href="home.php"><img src="images/<?php echo APP_LOGO;?>" alt="app logo" /></a>
      <button type="button" class="sidebar-toggle"> <i class="fa fa-times"></i> </button>
    </div>
    <div class="sidebar-menu">
      <ul class="sidebar-nav">
        <li <?php if($currentFile=="home.php"){?>class="active"<?php }?>> <a href="home.php">
          <div class="icon"> <i class="fa fa-dashboard" aria-hidden="true"></i> </div>
          <div class="title">داشبورد</div>
          </a>
        </li>
        <li <?php if($currentFile=="manage_category.php" or $currentFile=="add_category.php"){?>class="active"<?php }?>> <a href="manage_category.php">
          <div class="icon"> <i class="fa fa-sitemap" aria-hidden="true"></i> </div>
          <div class="title">دسته بندی ها</div>
          </a>
        </li>

        <li <?php if($currentFile=="manage_videos.php" or $currentFile=="add_video.php" or $currentFile=="edit_video.php"){?>class="active"<?php }?>> <a href="manage_videos.php">
          <div class="icon"> <i class="fa fa-film" aria-hidden="true"></i> </div>
          <div class="title">ویدئو های من</div>
          </a>
        </li>
        <li <?php if($currentFile=="manage_users_videos.php"){?>class="active"<?php }?>> <a href="manage_users_videos.php">
          <div class="icon"> <i class="fa fa-film" aria-hidden="true"></i> </div>
          <div class="title">ویدئو های کاربران</div>
          </a>
        </li>

        <li <?php if($currentFile=="manage_comments.php"){?>class="active"<?php }?>> <a href="manage_comments.php">
          <div class="icon"> <i class="fa fa-comments" aria-hidden="true"></i> </div>
          <div class="title">نظرات</div>
          </a>
        </li>
        <li <?php if($currentFile=="manage_reports.php"){?>class="active"<?php }?>> <a href="manage_reports.php">
          <div class="icon"> <i class="fa fa-bug" aria-hidden="true"></i> </div>
          <div class="title">گزارشات</div>
          </a>
        </li>
        <li <?php if($currentFile=="manage_users.php" or $currentFile=="add_user.php"){?>class="active"<?php }?>> <a href="manage_users.php">
          <div class="icon"> <i class="fa fa-users" aria-hidden="true"></i> </div>
          <div class="title">کاربران</div>
          </a>
        </li>
        <li <?php if($currentFile=="manage_contact_list.php"){?>class="active"<?php }?>> <a href="manage_contact_list.php">
          <div class="icon"> <i class="fa fa-envelope" aria-hidden="true"></i> </div>
          <div class="title">پیام های کاربران</div>
          </a>
        </li>
        <li <?php if($currentFile=="rewards_points.php"){?>class="active"<?php }?>> <a href="rewards_points.php">
          <div class="icon"> <i class="fa fa-dollar" aria-hidden="true"></i> </div>
          <div class="title">امتیازات و پاداش ها</div>
          </a>
        </li>
        <li <?php if($currentFile=="telegram_check.php"){?>class="active"<?php }?>> <a href="telegram_check.php">
          <div class="icon"> <i class="fa fa-dollar" aria-hidden="true"></i> </div>
          <div class="title">چک کردن تلگرام</div>
          </a>
        </li>
        <li <?php if($currentFile=="insta_check.php"){?>class="active"<?php }?>> <a href="insta_check.php">
          <div class="icon"> <i class="fa fa-dollar" aria-hidden="true"></i> </div>
          <div class="title">چک کردن اینستاگرام</div>
          </a>
        </li>
		<li <?php if($currentFile=="manage_transaction.php"){?>class="active"<?php }?>> <a href="manage_transaction.php">
          <div class="icon"> <i class="fa fa-list" aria-hidden="true"></i> </div>
          <div class="title">پرداخت ها</div>
          </a>
        </li>
		 <li <?php if($currentFile=="send_notification.php"){?>class="active"<?php }?>> <a href="send_notification.php">
          <div class="icon"> <i class="fa fa-send" aria-hidden="true"></i> </div>
          <div class="title">اعلان</div>
          </a>
        </li>
		<!--
        <li <?php if($currentFile=="ads_settings.php"){?>class="active"<?php }?>> <a href="ads_settings.php">
          <div class="icon"> <i class="fa fa-buysellads" aria-hidden="true"></i> </div>
          <div class="title">تنظیمات تبلیغات</div>
          </a>
        </li>
		-->
        <li <?php if($currentFile=="settings.php"){?>class="active"<?php }?>> <a href="settings.php">
          <div class="icon"> <i class="fa fa-cog" aria-hidden="true"></i> </div>
          <div class="title">تنظیمات</div>
          </a>
        </li>


      </ul>
    </div>

  </aside>
  <div class="app-container">
    <nav class="navbar navbar-default" id="navbar">
      <div class="container-fluid">
        <div class="navbar-collapse collapse in">
          <ul class="nav navbar-nav navbar-mobile">
            <li>
              <button type="button" class="sidebar-toggle"> <i class="fa fa-bars"></i> </button>
            </li>
            <li class="logo"> <a class="navbar-brand" href="#"><?php echo APP_NAME;?></a> </li>
            <li>
              <button type="button" class="navbar-toggle">
                <?php if(PROFILE_IMG){?>
                  <img class="profile-img" src="images/<?php echo PROFILE_IMG;?>">
                <?php }else{?>
                  <img class="profile-img" src="assets/images/profile.png">
                <?php }?>

              </button>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-left">
            <li class="navbar-title"><?php echo APP_NAME;?></li>

          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown profile"> <a href="profile.php" class="dropdown-toggle" data-toggle="dropdown"> <?php if(PROFILE_IMG){?>
                  <img class="profile-img" src="images/<?php echo PROFILE_IMG;?>">
                <?php }else{?>
                  <img class="profile-img" src="assets/images/profile.png">
                <?php }?>
              <div class="title">پروفایل</div>
              </a>
              <div class="dropdown-menu">
                <div class="profile-info">
                  <h4 class="username">حساب مدیریت</h4>
                </div>
                <ul class="action">
                  <li><a href="profile.php">پروفایل</a></li>
                  <li><a href="logout.php">خروج از حساب</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
