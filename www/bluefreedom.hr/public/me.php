<?php

session_start();
if(!$_SESSION['auth']){header('location: authorisation.php');}

?>
<!DOCTYPE html>
<html lang="en">
<?php include "components/header.php"?>
<?php include "components/styles.php"?>
<link rel="stylesheet" href="css/user.css">
<body>
    
<?php include "navbar.php"?>

<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div id="content" class="content content-full-width">
            <!-- begin profile -->
            <div class="profile">
               <div class="profile-header">
                  <!-- BEGIN profile-header-cover -->
                  <div class="profile-header-cover"></div>
                  <!-- END profile-header-cover -->
                  <!-- BEGIN profile-header-content -->
                  <div class="profile-header-content">
                     <!-- BEGIN profile-header-img -->
                     <div class="profile-header-img">
                        <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="">
                     </div>
                     <!-- END profile-header-img -->
                     <!-- BEGIN profile-header-info -->
                     <div class="profile-header-info">
                        <h4 class="m-t-10 m-b-5">User</h4>
                        <p class="m-b-10">1,422 Friends</p>
                        <br>
                     </div>
                     <!-- END profile-header-info -->
                  </div>
                  <!-- END profile-header-content -->
                  <!-- BEGIN profile-header-tab -->
                  <ul class="profile-header-tab nav nav-tabs">
                      <li class="nav-item"><a href="#" class="nav-link_">FRIENDS</a></li>
                     <li class="nav-item"><a href="#" class="nav-link_ active show">POSTS</a></li>
                     <li class="nav-item"><a href="#" class="nav-link_">ABOUT</a></li>
                  </ul>
                  <!-- END profile-header-tab -->
               </div>
            </div>
            <!-- end profile -->

            <?php include "components/timeline.php"?>
            
         </div>
      </div>
   </div>
</div>

<?php include "components/footer.php"?>
<?php include "components/scripts.php"?>

</body>
</html>