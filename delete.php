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
  if(isset($_GET['id'])){
   $id = $_GET['id'];
    $user = $_SESSION["splatr-web.login"];
    $query = "SELECT * FROM currencies WHERE id = '$id'";
    $query_result = $connection->query($query);
    $row = $query_result->fetch_assoc();
    if($row['email'] == $user){
      $delqry = "INSERT INTO currencies WHERE id=".$id;
      $result = mysqli_query($db, $delqry);
      if ($connection->query($delqry) === TRUE) {
          //echo "Record deleted successfully";
          header("location:index.php");
      }else{
          echo "Splatr encountered an error: " . $connection->error;
          echo "<a href=\"index.php\">Click here to go back</a>";
      }
    }
  }else{
    header('location:index.php');
}
}
?>
