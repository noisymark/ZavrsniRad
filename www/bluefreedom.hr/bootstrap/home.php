<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once "components/header.php" ?>
    <?php include_once "components/styles.php" ?>
</head>
<body>
<!--BEGIN NAVBAR-->
<?php include "navbar.php" ?>
<!--END NAVBAR-->

<!--BEGIN FEED-->
<section>
  <div class="container my-5 py-5">
    <div class="row d-flex justify-content-center">
      <div class="col-md-12 col-lg-10 col-xl-8">
        <div class="card" id="newscard">
          <div class="card-body p-4">
            <h4 id="newstext" class="text-center mb-4 pb-2">YOUR FEED</h4>

            <div class="row">
              <div class="col">
                
            <?php include "components/objava1.php"?>
            <?php include "components/objava2.php"?>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--END FEED-->
<?php include_once "components/footer.php" ?>
</body>
<?php include_once "components/scripts.php" ?>
</html>