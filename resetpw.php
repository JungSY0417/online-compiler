<?php
session_start();

header("Progma:no-cache");
header("Cache-Control:no-cache,must-revalidate");

require_once 'functions.php';

$error = $ID = $name = $msg = "";
$year = date("Y");
$sem = semester();

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
		<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic+Coding:wght@700&display=swap" rel="stylesheet">

		<title>Upload Example Programs</title>
		<style>
			body {
				margin: 0;
				padding: 0;
				position: relative;
				font-family: 'Nanum Gothic Coding', monospace;
			}
			
			#title {
				position: fixed;
				width: 100%;
				background-color: white;
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
				position: fixed;
				top: 13vh;
				height: 87vh;
				border-right: 0.7px solid;
				background: #4F86C6;
			}
			
			#exprogram, #resetpw, #settimeform, #submittedform, #logout {
				height: 5px;
				width: 170px;
				position: fixed;
				top: 13vh;
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
				width: 170px;
				margin: 40px 10px 0px 5px;
				padding: 2px;
				border: 1px solid #353866;
				border-radius: 6px;
				background-color: white;
				color: #353866;
				font-family: 'Nanum Gothic Coding', monospace;
			}
			
			.expr {
				width: 170px;
				position: absolute;
				margin: 100px 5px 0px 5px;
				padding: 2px;
				border: 1px solid #353866;
				border-radius: 6px;
				background-color: white;
				color: #353866;
				font-family: 'Nanum Gothic Coding', monospace;
			}
			
			.rspw {
				width: 170px;
				position: absolute;
				margin: 160px 5px 0px 5px;
				padding: 2px;
				border: 1px solid #353866;
				border-radius: 6px;
				background-color: white;
				color: #353866;
				font-family: 'Nanum Gothic Coding', monospace;
			}
			
			.settime {
				width: 170px;
				position: absolute;
				margin: 220px 5px 0px 5px;
				padding: 2px;
				border: 1px solid #353866;
				border-radius: 6px;
				background-color: white;
				color: #353866;
				font-family: 'Nanum Gothic Coding', monospace;
			}
			
			.submitted {
				width: 170px;
				position: absolute;
				margin: 280px 5px 0px 5px;
				padding: 2px;
				border: 1px solid #353866;
				border-radius: 6px;
				background-color: white;
				color: #353866;
				font-family: 'Nanum Gothic Coding', monospace;
			}
			
			.msg {
				position: absolute;
				left: 40%;
				top: 35vh;
			}
			
			.exit {
				width: 170px;
				margin: 480px 5px 0px 5px;
				padding: 2px;
				border: 1px solid #353866;
				border-radius: 6px;
				background-color: white;
				color: #353866;
				font-family: 'Nanum Gothic Coding', monospace;
			}
			
			.submit {
				padding: 6px;
				font-family: 'Nanum Gothic Coding', monospace;
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
			<div id='title'>
				<h2 align='center'>Reset Password</h2>
				<hr size='1' noshade></hr>
			</div>
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
						<input class='submit' type='submit'>
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
			
			<form id='resetpw' method='POST' action='resetpw.php'>
				<input class='rspw' type='submit' value='비밀번호 초기화'></input>
			</form>
			
			<form id='settimeform' method='POST' action='settime.php'>
				<input class='settime' type='submit' value='마감 날짜 설정'></input>
			</form>
			
			<form id='submittedform' method='POST' action='submittedfile.php'>
				<input class='submitted' type='submit' value='제출 프로그램 보기'></input>
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