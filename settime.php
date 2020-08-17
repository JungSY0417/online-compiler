<?php
session_start();

require_once "functions.php";

$error = $msg = "";

if(isset($_POST['starttime'])) {
	$same = 0;
	
	$stime = str_replace("T", " ", $_POST['starttime']);
	$etime = str_replace("T", " ", $_POST['duetime']);
	$subj = sanitizeString($_POST['subj']);
	
	$sql = queryMysql("SELECT start, end FROM duedate WHERE subject='$subj'");
	while($row = mysqli_fetch_array($sql)) {
		$start = substr($row[0], 0, 16);
		$end = substr($row[1], 0, 16);
		if($stime == $start && $etime == $end) {
			$same = 1;
			break;
		}
	}

	if($stime != "" && $etime != "" && $same != 1) {
		$result = queryMysql("INSERT INTO duedate VALUES('$stime', '$etime', '$subj')");
		$msg = "마감시간 설정 완료";
	}
	elseif($same == 1)
		$error = "이미 설정된 마감시간입니다";
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
				position: relative;
				font-family: 'Nanum Gothic Coding', monospace;
			}
			
			#setdiv {
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
				background: #4F86C6;
			}
			
			#logout {
				position: absolute;
				top: 87vh;
			}
			
			#subj {
				position: absolute;
				left: 85px;
				width: 237px;
			}
			
			.setform {
				position: absolute;
				left: 20%;
				top: 15vh;
				width: 330px;
			}
			
			.setform > div {
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
			
			.msg {
				position: absolute;
				left: 40%;
				top: 40vh;
			}
			
			.exit {
				width: 170px;
				margin-left: 5px;
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
			<h2 align='center'>Set Submit Time</h2>
			<hr size='1' noshade></hr>
			<div id='setdiv'>
				<form class='setform' action="settime.php" method="post" enctype='multipart/form-data'>
					<div>
						<span class='error'>$error</span>
					</div>
					<div>
						<span>$success</span>
					</div>
					<div>
						<label>시작 날짜</label>
						<input type="datetime-local" name="starttime" id="starttime"></input>
					</div>
					<div>
						<label>마감 날짜</label>
						<input type="datetime-local" name="duetime" id="duetime"></input>
					</div>
					<div>
						<label>과목 입력</label>
						<input type="text" name="subj" id="subj"></input>
					</div>
					<div>
						<label></label>
						<br><br>
						<input class='submit' type='submit'></input>
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