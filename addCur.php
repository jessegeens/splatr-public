<?php
require_once("dbcred.php");

$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($connection->connect_error) {
  die("Splatr can't connect to the database. <br> Error: " . $connection->connect_error);
}
session_start();
if(!(isset($_SESSION["splatr-web.login"]))){
  echo 'Something went wrong :/<br>Please report this to ' . SYSADMIN;
}else{
  $user = $_SESSION["splatr-web.login"];
  $currency = strip_tags($_POST['currency']);
  $amount = strip_tags($_POST['amount']);
  $buyPrice = strip_tags($_POST['buyprice']);
  $addqry = "INSERT INTO currencies (email, currency, amount, buyPrice) VALUES ('$user', '$currency', '$amount', '$buyPrice')";
  if ($connection->query($addqry) === TRUE) {
      header("location:index.php");
  }else{
      echo "Splatr encountered an error: " . $connection->error;
      echo "<br><a href=\"index.php\">Click here to go back</a>";
  }
}
?>
