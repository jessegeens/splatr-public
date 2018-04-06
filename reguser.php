<?php
require_once('dbcred.php');
$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($connection->connect_error) {
  die("Splatr can't connect to the database. <br> Error: " . $connection->connect_error);
}

if(isset($_POST['email-reg']) && isset($_POST['email-reg-confirm']) && isset($_POST['password-reg']) && isset($_POST['password-reg-confirm']) && isset($_POST['birthDate-reg']) && isset($_POST['eula-chkbx'])){
	$email = $_POST['email-reg'];
	$emailver = $_POST['email-reg-confirm'];
	$pwd = $_POST['password-reg'];
	$pwdver = $_POST['password-reg-confirm'];
	$birthDate=$_POST['birthDate-reg'];
}
else {
	if(!isset($_POST['birthDate-reg'])){/*echo 'g-recaptcha-response not set';*/header('location:register.php?pwf=2');exit;}
	if(!isset($_POST['email-reg'])){/*echo 'email_reg not set';*/header('location:register.php?pwf=3');exit;}
	if(!isset($_POST['email-reg-confirm'])){/*echo 'email_reg_verify not set';*/header('location:register.php?pwf=4');exit;}
	if(!isset($_POST['password-reg'])){/*echo 'password_reg not set';*/header('location:register.php?pwf=5');exit;}
	if(!isset($_POST['password-reg-confirm'])){/*echo 'password_reg_verify not set';*/header('location:register.php?pwf=6');exit;}
	if(!isset($_POST['eula-chkbx'])){/*echo 'eula_chkbx not set';*/header('location:register.php?pwf=12');exit;}
}

//Checks en verifies POST input
if($email != $emailver){
	header('location:register.php?pwf=7');
	exit;
}

if($pwd != $pwdver){
	header('location:register.php?pwf=8');
	exit;
}

if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
	header('location:register.php?pwf=3');
	exit;
}
    $pwdhash = crypt($pwd, 'splatr-hash-123');
		$email_db =  mysqli_real_escape_string($connection, $email);
		$pwd_db =  mysqli_real_escape_string($connection, $pwdhash);

		$email_exists_checker = "SELECT email FROM userdata WHERE email = '$email'";
		$email_exists_result = $connection->query($email_exists_checker);
		if ($email_exists_result->num_rows === 1) {
			header('location:register.php?pwf=11');
		}

		else{
			$sql = "INSERT INTO userdata (email, password)
			VALUES ('$email_db', '$pwd_db')";

			if ($connection->query($sql) === TRUE) {
   				echo 'Account succesfully created!';
   				header('location:login.php');
			}
			else {
          //echo 'email: ' . $email .'; email-escaped: ' . $email_db;
    			echo "Error: " . $connection->error;
			}

			$connection->close();
		}
?>
