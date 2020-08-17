<?php
session_start();

require_once "functions.php";

$user = $_SESSION['user'];
$subj = sanitizeString($_POST['subj']);
$year = date("Y");
$sem = semester();

setlocale(LC_CTYPE, 'ko_KR.eucKR'); //CSV데이타 추출시 한글깨짐방지

$error = $success = $msg = "";

function getExt($filename){
	$ext = substr(strrchr($filename,"."),1);
	$ext = strtolower($ext);
	return $ext;
}
			
function detectFileEncoding($filepath) {
	// 리눅스 기본 기능을 활용한 파일 인코딩 검사
	$output = array();
	exec('file -i ' . $filepath, $output);
	if (isset($output[0])){
		$ex = explode('charset=', $output[0]);
		return isset($ex[1]) ? $ex[1] : null;
	}
	return null;
}

if($_FILES['file']['name'] != "") {

	if($subj == "") {
		$error = "과목명을 입력해주세요";
	}
	else {
	    //if there was an error uploading the file
	    if($_FILES["file"]["error"] > 0) {
	        $error = "Return Code: " . $_FILES["file"]["error"] . "<br>";
	    }
	    else {
	        //Print file details
	        $success = "Uploaded File Name: " . $_FILES["file"]["name"] . "<br><br>";
			
	    	// Edit upload location here
			$tmpname = $_FILES['file']['tmp_name'];
			$realname = $_FILES['file']['name'];
			$subj = $_POST['subj'];
			
			$fileExt = getExt($realname);
			if (!strstr('[c]',$fileExt)) {
			    $error = "c 파일만 등록할 수 있습니다.<br>";
			}
			else {
				$readFile = "example.c";
				
				$errorFile ="errorFile.txt"; 
				
				$TABLENAME ='user'; // 테이블명
				
				if (is_uploaded_file($tmpname)) {
		
				    move_uploaded_file($tmpname,$readFile);
				    @chmod($readFile,0606);
				}
				
				$file_read = fopen($readFile,"r");
				if(!$file_read){
				    $error = "파일을 찾을 수가 없습니다!<br>";
				}
				
				// 파일 인코딩 모드 검사
				$current_encoding = detectFileEncoding($readFile);
				
				$code = fread($file_read,filesize("example.c"));
				
				//이미 있는 프로그램인지 검사
				$same = 0;
				$sql = queryMysql("SELECT code FROM codes WHERE ID='$user' AND subject='$subj' AND year='$year' AND semester='$sem'");
				while($row = mysqli_fetch_array($sql)) {
					$result = (string)$row[0];
					if($result == $code)
						$same = 1;
				}
			
				if($same != 1) {
					$code = str_replace("\\", "\\\\", $code);
					$code = str_replace("'", "\'", $code);
					$result = queryMysql("INSERT INTO codes VALUES('$user', '$subj', '$code', '$year', '$sem')");
					$msg = "프로그램 업로딩 성공";
				}
				else {
					$msg = "이미 있는 프로그램입니다.";
				}
				
				fclose($file_read);
				unlink($readFile);  // 업로드 완료후에 파일 삭제 처리
			}
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
				position: relative;
				font-family: 'Nanum Gothic Coding', monospace;
			}
			
			#uploaddiv {
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
				left: 190px;
			}
			
			.uploadform {
				width: 450px;
			}
			
			.uploadform > div {
				display: flex;
				justify-content: center;
				padding-bottom: 7px;
				align-items: center;
			}
			
			.error {
				position: absolute;
				left: 35%;
				top: 5vh;
				color: red;
			}
			
			.success {
				position: absolute;
				left: 25%;
				top: 0vh;
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
			
			.rspw {
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
			
			.settime {
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
				left: 30%;
				top: 35vh;
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
			
			.submit, #file {
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
			<h2 align='center'>Upload Example Programs</h2>
			<hr size='1' noshade></hr>
			<div id='uploaddiv'>
				<div class='error'>$error</div>
				<div class='success'>$success</div>
				<form class='uploadform' action="uploadexpg.php" method="post" enctype='multipart/form-data'>
					<div>
						<label>예제 프로그램 선택</label>
						<input type="file" name="file" id="file"></input>
					</div>
					<div>
						<label>과목 입력</label>
						<input type="text" name="subj" id="subj"></input>
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
			
			<form id='resetpw' method='POST' action='resetpw.php'>
				<input class='rspw' type='submit' value='비밀번호 초기화'></input>
			</form>
			
			<form id='settimeform' method='POST' action='settime.php'>
				<input class='settime' type='submit' value='마감 날짜 설정'></input>
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