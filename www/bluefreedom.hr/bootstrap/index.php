<?php $email = isset($_GET['email']) ? $_GET['email'] : (isset($_COOKIE['email']) ? $_COOKIE['email'] : ''); ?>
<!DOCTYPE html>
<html lang="en">
    <?php include_once "components/header.php" ?>
    <?php include_once "components/styles.php" ?>
    <link rel="stylesheet" href="css/login.css">
<body>
    <div class="container">
    <div class="auth">
  <div class="auth__header">
    <div class="auth__logo">
      <img height="90" src="components/icons/png.png" alt="">
    </div>
  </div>
  <div class="auth__body">
    <form action="authorisation.php" class="auth__form" autocomplete="off" method="POST">
      <div class="auth__form_body">
        <h3 class="auth__form_title">Sign in</h3>
        <div>
          <div class="form-group">
            <label for="email" class="text-uppercase small">Email :</label>
            <input name="email" id="email" type="email" class="form-control" placeholder="Enter email" value="<?=$email?>">
          </div>
          <div class="form-group">
            <label for="password" class="text-uppercase small">Password :</label>
            <input name="password" id="password" type="password" class="form-control" placeholder="Password">
          </div>
        </div>
      </div>
      <div class="auth__form_actions">
        <button id="logintext" class="btn btn-primary btn-lg btn-block">
        <i id="fingerprinticon" class="fa-solid fa-fingerprint"></i>LOGIN
        </button>
        <div class="mt-2" id="fgtpw">
          <a style="text-decoration: none; color:black;" href="#" class="small text-uppercase">
            Forgot password?
          </a>
        </div>
      </div>
    </form>
  </div>
</div>
    </div>
    <?php include_once "components/footer.php"?>
</body>
<?php include_once "components/scripts.php" ?>
</html>