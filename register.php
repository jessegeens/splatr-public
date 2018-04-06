<?php
session_start();
if(isset($_SESSION["splatr-web.login"])){
  header("locaton:index.php");
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Splatr | register</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
    integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <?php include_once("analyticstracking.php");?>
  </head>

  <body><?php require("header.php"); ?>
    <div class="container">
            <form class="form-horizontal" role="form" action="reguser.php" method="post">
                <h2>Register your splatr account</h2>
                <div class="form-group">
                    <label for="firstName" class="col-sm-3 control-label">Full Name</label>
                    <div class="col-sm-9">
                        <input type="text" id="firstName" placeholder="Full Name" class="form-control" autofocus>
                        <!--<span class="help-block">Last Name, First Name, eg.: Smith, Harry</span>-->
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" id="email-reg" name="email-reg" placeholder="E-mail" class="form-control">
                    </div>
                    <label for="email-confirm" class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <input type="email" id="email-reg-confirm" name="email-reg-confirm" placeholder="Confirm e-mail" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-9">
                        <input type="password" id="password-reg" name="password-reg" placeholder="Password" class="form-control">
                    </div>
                    <label for="password-confirm" class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <input type="password" id="password-reg-confirm" name="password-reg-confirm" placeholder="Confirm password" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="birthDate" class="col-sm-3 control-label">Date of Birth</label>
                    <div class="col-sm-9">
                        <input type="date" id="birthDate-reg" name="birthDate-reg" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="eula-chkbx" name="eula-chkbx">I accept the <a href="tos.php">Terms of Service</a>
                            </label>
                        </div>
                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                </div>
            </form> <!-- /form -->
        </div> <!-- ./container -->
  </body>
</html>
