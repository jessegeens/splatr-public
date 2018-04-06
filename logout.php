<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_SERVER', 'mysql.ulyssis.org');
define('DB_USERNAME', 'jessegeens');
define('DB_PASSWORD', 'zeus9887ID');
define('DB_DATABASE', 'jessegeens_splatr');

$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($connection->connect_error) {
  die("Splatr can't connect to the database. <br> Error: " . $connection->connect_error);
}
session_start();
if(isset($_COOKIE['splatr-web_login-forever'])){
  setcookie("splatr-web_login-forever", "", 1);
  unset($_COOKIE['splatr-web_login-forever']);
  echo 'cookie unset';
}
if(!(isset($_SESSION["splatr-web.login"]))){
  header("location:index.php");
}else{
  $_SESSION = array();
  if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
  }
  session_destroy();
  echo 'session destroyed';
  header("location:login.php");
}
