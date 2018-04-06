<?php
session_start();
if(isset($_SESSION["splatr-web.login"])){
  header("locaton:index.php");
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Splatr | log-in</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
    integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <?php include_once("analyticstracking.php");?>
  </head>

  <body><?php require("header.php");?>
    <div class="container">
      <div class="row">
        <div class="Absolute-Center is-Responsive">
          <div id="logo-container"></div>
          <div class="col-sm-12 col-md-10 col-md-offset-1">
            <?php
            if(isset($_GET["ic"])){
              echo '<div class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    You have entered an incorrect e-mail adress/password</div>';}?>
            <form action="session.php" id="loginForm" method="post">
              <div class="form-group input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input class="form-control" type="text" name='email' id='email' placeholder="e-mail adress"/>
              </div>
              <div class="form-group input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input class="form-control" type="password" name='password' id='password' placeholder="password"/>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="stayAlive"> Keep me logged in
                </label>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-def btn-block">Login</button>
              </div>
              <div class="form-group text-center">
                <a href="register.php">Register</a>&nbsp;|&nbsp;<a href="mailto:jesse.geens@gmail.com">Support</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
