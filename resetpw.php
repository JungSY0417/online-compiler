<?php
session_start();

header("Progma:no-cache");
header("Cache-Control:no-cache,must-revalidate");

require_once 'functions.php';

$error = $ID = $name = $msg = "";
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
	
	if($ID == "" || $name == "")
		$error = 'Not all fields were entered';
	else
	{
		$result = queryMysql("SELECT ID, name FROM user
			WHERE ID='$ID' AND name='$name' AND year='$year' AND semester='$sem'");
			
		if($result->num_rows == 0)
		{
			$error = "Invalid login attempt";
		}
		else
		{
			$result = queryMysql("UPDATE user SET password='$ID'
				WHERE ID='$ID' AND name='$name' AND year='$year' AND semester='$sem'");
			$msg = "<br><br>비밀번호 초기화 완료";
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

		<title>Upload Example Programs</title>
		<style>
			body {
				position: relative;
			}
			
			#resetdiv {
				padding: 5px 20px;
				position: absolute;
				top: 25vh;
				left: 30%;
				width: 450px;
				height: 250px;

				display: flex;
				flex-direction: column;
				justify-content: center;
				align-items: center;
			}
			
			#uploadstd {
				position: absolute;
				height: 85vh;
				border-right: 0.7px solid;
				background: rgb(25, 0, 130);
			}
			
			#logout {
				position: absolute;
				top: 87vh;
			}
			
			#subj {
				position: absolute;
				left: 170px;
			}
			
			.resetform {
				position: absolute;
				left: 30%;
				top: 15vh;
				width: 250px;
			}
			
			.resetform > div {
				display: flex;
				justify-content: center;
				padding-bottom: 7px;
				align-items: center;
			}
			
			.error {
				color: red;
			}
			
			.std {
				margin: 40px 20px 0px 15px;
				border-radius: 6px;
			}
			
			.expr {
				position: absolute;
				margin: 100px 5px 0px 5px;
				border-radius: 6px;
			}
			
			.msg {
				position: absolute;
				left: 40%;
				top: 35vh;
			}
			
			.exit {
				margin-left: 45px;
				border-radius: 6px;
			}
			
			label {
				flex: 1;
				text-align: left;
			}
		</style>
	</head>
_END;
	
if($_SESSION['user'] == 'admin') {
	echo <<<_END
		<body>
			<h2 align='center'>Reset Password</h2>
			<hr size='1' noshade></hr>
			<div id='resetdiv'>
				<form class='resetform' action="resetpw.php" method="post" enctype='multipart/form-data'>
					<div>
						<span class='error'>$error</span>
					</div>
					<div>
						<span>$success</span>
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
						<label></label>
						<br><br>
						<input data-transition='slide' type='submit'>
					</div>
				</form>
				<div class='msg'>$msg</div>
			</div>
			
			<form id='uploadstd' method='POST' action='uploaduser.php'>
				<input class='std' type='submit' value='학생 정보 업로드'></input>
			</form>
			
			<form id='exprogram' method='POST' action='uploadexpg.php'>
				<input class='expr' type='submit' value='예제프로그램 업로드'></input>
			</form>
			
			<form id='logout' method='POST' action='logout.php' align='right'>
				<input type='submit' class='exit' value='로그아웃'></input>
			</form>
		</body>
	</html>
	_END;
}
else {
	echo <<<_END
		<body>
			<script>
				alert('Please log in as administer to use this page.');
				location.href='login.php';
			</script>
		</body>
	</html>
	_END;
}

?>