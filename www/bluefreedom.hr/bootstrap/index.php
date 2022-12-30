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
    <form class="auth__form" autocomplete="off">
      <div class="auth__form_body">
        <h3 class="auth__form_title">Sign in</h3>
        <div>
          <div class="form-group">
            <label class="text-uppercase small">Email :</label>
            <input type="email" class="form-control" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label id="pw" class="text-uppercase small">Password :</label>
            <input type="password" class="form-control" placeholder="Password">
          </div>
        </div>
      </div>
      <div class="auth__form_actions">
        <button id="logintext" class="btn btn-primary btn-lg btn-block">
          LOGIN
        </button>
        <div class="mt-2">
          <a id="fgtpw" href="#" class="small text-uppercase">
            Forgot password?
          </a>
        </div>
      </div>
    </form>
  </div>
</div>
    </div>
</body>
<?php include_once "components/scripts.php" ?>
</html>