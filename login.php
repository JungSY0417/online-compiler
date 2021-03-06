<?php
session_start();

header("Progma:no-cache");
header("Cache-Control:no-cache,must-revalidate");

require_once 'functions.php';

$error = $user = $pass = $subj = "";
$year = date("Y");
$sem = semester();

if(isset($_POST['user']))
{
	$user = sanitizeString($_POST['user']);
	$pass = sanitizeString($_POST['pass']);
	$subj = sanitizeString($_POST['subj']);
	
	if($user == "" || $pass == "" || $subj == "")
		$error = 'Not all fields were entered';
	else
	{
		if($user == 'admin')
			$result = queryMysql("SELECT ID, password, subject FROM user
				WHERE ID='$user' AND password='$pass' AND subject='$subj'");

		else
			$result = queryMysql("SELECT ID, password, subject FROM user
				WHERE ID='$user' AND password='$pass' AND subject='$subj' AND year='$year' AND semester='$sem'");
			
		if($result->num_rows == 0)
		{
			$error = "Invalid login attempt";
		}
		else
		{
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;
			$_SESSION['subj'] = $subj;
			
			$now = new DateTime();
			$_SESSION['start'] = $now->format('Y-m-d H:i:s');
			$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
			
			if($user == "admin")
				die("<script>location.href='uploaduser.php';</script>");	
			else
				die("<script>location.href='CompileAndRun.php';</script>");
		}
	}
}

echo <<<_END
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel='stylesheet' href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
		<title>Login</title>
		<style>
			#logindiv {
				padding: 5px 20px;
				position: absolute;
				top: 50%;
				left: 50%;
				width: 450px;
				height: 250px;
				margin-left: -250px;
				margin-top: -170px;
				
				display: flex;
					flex-direction: column;
					justify-content: center;
					align-items: center;
			}
			
			.loginform {
				width: 300px;
			}
			
			.loginform > div {
				display: flex;
				justify-content: center;
				padding-bottom: 7px;
				align-items: center;
			}
			
			.error {
				color: red;
			}
			
			label {
				flex: 1;
				text-align: left;
			}
			
			a {
				text-align: left;
				text-decoration:none;
			}
			
			input {
				padding: 5px;
			}
		</style>
	</head>
	
	<body>
		<div id='logindiv'>
		<img src="logo.png">
			<form class='loginform' method='post' action='login.php'>
				<div id='cell'>
					<label></label>
					<span class='error'>$error</span>
				</div>
				<div id='cell'>
					<label>Student ID</label>
					<input type='text' maxlength='16' name='user' value='$user'>
				</div>
				<div id='cell'>
					<label>Password</label>
					<input type='password' maxlength='16' name='pass' value='$pass'>
				</div>
				<div id='cell'>
					<label>Subject</label>
					<input type='text' maxlength='20' name='subj' value='$subj'>
				</div>
				<div>
					<a data-transition='slide' href='changepw.php'>비밀번호 변경</a>
					<label></label>
					<input data-transition='slide' type='submit' value='Login'>
				</div>
			</form>
		</div>
	</body>
</html>
_END;

?>