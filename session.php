<?php

require_once('dbcred.php');

$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($connection->connect_error) {
  die("Splatr kon geen verbinding met de database maken. <br> Error: " . $connection->connect_error);
}

if(isset($_POST['email']) && isset($_POST['password'])){
  $email = strip_tags($_POST['email']);
  $password = strip_tags($_POST['password']);
}else{
  $email = "NA"; $password="NA";
}
//echo 'logged in with '.$email.'; '.$password.';';
$password = crypt($password, 'splatr-hash-123');
$query = "SELECT * FROM userdata WHERE email = '$email' and password = '$password'";
if(mysqli_num_rows(mysqli_query($db, $query)) == 1){
  session_start();
  $_SESSION["splatr-web.login"] = $email;
  if(isset($_POST['stayAlive'])){
    setcookie('splatr-web_login-forever', $email.';'.crypt($email, 'splatr123.hash'), 2147483647);
    echo 'set';
  }
  header("location:index.php");
}else{
  header("location:login.php?ic");
  echo mysqli_num_rows($db, $query);
}

$connection->close();
?>
