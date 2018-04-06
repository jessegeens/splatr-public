<?php
require_once('dbcred.php');

$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($connection->connect_error) {
  die("Splatr can't connect to the database. <br> Error: " . $connection->connect_error);
}
session_start();
if(!(isset($_SESSION["splatr-web.login"]))){
  echo 'Something went wrong :/<br>Please reload the page.';
} ?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Splatr</title>
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
    integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:700">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <?php include_once("analyticstracking.php");?>
  </head>
  <body>
    <?php require("header.php");?>
     <div class="container-fluid">
    <form class="form-horizontal" method="post" action="addCur.php">
      <div class="form-group">
        <label class="control-label col-sm-2" for="currency">Currency:</label>
        <div class="col-sm-3">
          <select class="form-control" id="currency" name="currency">
            <option value="btc">Bitcoin (btc)</option>
            <option value="bch">Bitcoin Cash (bch)</option>
            <option value="ltc">Litecoin (ltc)</option>
            <option value="eth">Ethereum (eth)</option>
            <option value="xmr">Monero (xmr)</option>
            <option value="zec">Z-Cash (zec)</option>
            <option value="xrp">Ripple (xrp)</option>
          </select>
      </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="amount">Amount:</label>
        <div class="col-sm-3">
          <input type="number" step="any" class="form-control" id="amount" name="amount" placeholder="e.g. 2">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="buyprice">Buy price:</label>
        <div class="col-sm-3">
          <input type="number" class="form-control" step="0.01" id="buyprice" name="buyprice" placeholder="e.g. 50">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">Add</button>
        </div>
      </div>
      </div>
    </form>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <a href="index.php"><button class="btn btn-default">Cancel</button>
      </div>
  </div>
  </body>
</html>
