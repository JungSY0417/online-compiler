<?php
require_once 'functions.php';

header("Progma:no-cache");
header("Cache-Control:no-cache,must-revalidate");

$error = $ID = $name = $pw = $npw = "";
$year = date("Y");
$mon = date("n");

if($mon == 1 || $mon == 2)
	$sem = 'win';
else if($mon == 3 || $mon == 4 || $mon == 5)
	$sem = 1;
else if($mon == 7 || $mon == 8)
	$sem = 'sum';
else if($mon == 9 || $mon == 10 || $mon == 11)
	$sem = 2;
else if($mon == 6) {
	if(date("j") <= 15)
		$sem = 1;
	else if(date("j") > 15)
		$sem = 'sum';
}
else if($mon == 12) {
	if(date("j") <= 15)
		$sem = 2;
	else if(date("j") > 15)
		$sem = 'win';
}

if(isset($_POST['ID']))
{
	$ID = sanitizeString($_POST['ID']);
	$name = sanitizeString($_POST['name']);
	$pw = sanitizeString($_POST['pw']);
	$npw = sanitizeString($_POST['npw']);
	
	if($ID == "" || $name == "" || $pw == "" || $npw == "")
		$error = 'Not all fields were entered';
	else if($pw == $npw)
		$error = '이전에 사용하던 비밀번호는 사용하실 수 없습니다';
	else
	{
		$result = queryMysql("SELECT ID, name, password FROM user
			WHERE ID='$ID' AND name='$name' AND password='$pw' AND year='$year' AND semester='$sem'");
			
		if($result->num_rows == 0)
		{
			$error = "Invalid login attempt";
		}
		else
		{
			$result = queryMysql("UPDATE user SET password='$npw'
				WHERE ID='$ID' AND name='$name' AND password='$pw' AND year='$year' AND semester='$sem'");
			$msg = "<br><br>비밀번호 변경 완료";
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
		<title>Change Password</title>
		<style>
			#changediv {
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
			
			.changeform {
				width: 300px;
			}
			
			.changeform > div {
				display: flex;
				justify-content: center;
				padding-bottom: 7px;
				align-items: center;
			}
			
			.error {
				color: red;
			}
			
			.msg {
				position: absolute;
				left: 45%;
				top: 70vh;
			}
			
			label, a {
				flex: 1;
				text-align: left;
			}
			
			input {
				padding: 5px;
			}
		</style>
	</head>
	<body>
		<div id='changediv'>
			<h2><U>Change Password</U></h2><br><br>
			<form class='changeform' action="changepw.php" method="post">
				<div>
					<span class='error'>$error</span>
				</div>
				<div>
					<label>학번</label>
					<input type="text" name="ID" id="ID"></input>
				</div>
				<div>
					<label>이름</label>
					<input type="text" name="name" id="name"></input>
				</div>
				<div>
					<label>현재 비밀번호</label>
					<input type="password" name="pw" id="pw"></input>
				</div>
				<div>
					<label>새 비밀번호</label>
					<input type="password" name="npw" id="npw"></input>
				</div>
				<div>
					<a data-transition='slide' href='login.php'>로그인</a>
					<input data-transition='slide' type='submit'>
				</div>
			</form>
		</div>
		<div class='msg'>$msg</div>
	</body>
</html>
_END;

?>