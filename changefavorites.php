<?php
require_once("dbcred.php");
$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($connection->connect_error) {
  die("Splatr can't connect to the database. <br> Error: " . $connection->connect_error);
}
session_start();
if(!(isset($_SESSION["splatr-web.login"]))){
  echo 'Something went wrong :/<br>Please report this to jesse.geens@gmail.com';
}else{
  $string = "";
  for($i = 0; $i < 7; $i++){
    if(isset($_POST['favoritesselector'.$i])){
      $string = $string + $_POST['favoritesselector'.$i] + ';';
    }else{
      die("Something seems to have gone wrong! Please contact the system administrator: " . SYSADMIN);
    }
  }
  $query = "INSERT INTO userdata (email, favorites)
  VALUES ('$email_db', '$string')";
}
 ?>
