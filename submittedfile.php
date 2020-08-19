<?php
session_start();

require_once "functions.php";

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
				z-index: 10;
			}
			
			#setdiv {
				padding: 5px 20px;
				position: absolute;
				top: 15vh;
				left: 20%;
				width: 70%;

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
				top: 40vh;
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
			
			table {
		        width: 100%;
		    }
			
		    th, td {
		    	padding: 10px;
		    	border-bottom: 1px solid #dadada;
		    }
			
			a {
				text-decoration:none;
			}
		</style>
	</head>
_END;
	
if($_SESSION['user'] == 'admin') {
	echo <<<_END
		<body>
			<div id='title'>
				<h2 align=center>Show Submitted File</h2>
				<hr size='1' noshade></hr>
			</div>
			<div id='setdiv'>
				<div>
					<span class='error'>$error</span>
				</div>
	_END;
	if(isset($_GET['subj']) && isset($_GET['time'])) {
		echo <<<_TABLE
					<table>
						<thead>
							<tr>
								<th>ID</th>
								<th>code</th>
							</tr>
						</thead>
						<tbody>
			_TABLE;
		$subj = $_GET['subj'];
		$time = explode('~', $_GET['time']);
		$sql = queryMysql("SELECT DISTINCT ID FROM codes WHERE time>='$time[0]' AND time<='$time[1]' AND subject='$subj' AND ID!='admin'");
		while($row = mysqli_fetch_array($sql)) {
			$ID = $row[0];
			$result = queryMysql("SELECT code FROM codes WHERE ID='$ID' AND subject='$subj' AND time>='$time[0]' AND time<='$time[1]' ORDER BY time DESC limit 1");
			$rows = mysqli_fetch_array($result);
			$code = htmlspecialchars($rows[0]);
			echo "<tr><td>$ID</td><td><pre>$code</pre></td></tr>";
		}
		echo "</tbody></table>";
		$sql->close();
	}
	elseif(isset($_GET['subj'])) {
		echo "<table><thead><tr><th>시작시간</th><th>종료시간</th></tr></thead><tbody>";
		$subj = $_GET['subj'];
		$sql = queryMysql("SELECT start, end FROM duedate WHERE subject='$subj'");
		while($row = mysqli_fetch_array($sql)) {
			$start = $row[0];
			$end = $row[1];
			$time = $start . '&' . $end;
			echo "<tr><td><a href='submittedfile.php?subj=$subj&time=$start~$end'>$start</a></td><td><a href='submittedfile.php?subj=$subj&time=$start~$end'>$start</a></td></tr>";
		}
		echo "</tbody></table>";
		$sql->close();
	}
	else {
		echo "<table><thead><tr><th>과목</th></tr></thead><tbody>";
		$sql = queryMysql("SELECT DISTINCT subject FROM duedate");
		while ($row = mysqli_fetch_array($sql))
		{
			$subj = $row[0];
			echo "<tr><td><a href='submittedfile.php?subj=$subj'>$subj</a></td></tr>";
		}
		echo "</tbody></table>";
		$sql->close();
	}
	echo <<<_END
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
			
			<form id='logout' method='POST' action='logout.php'>
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