<?php
session_start();

require_once "functions.php";

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
			
// ID Exist Check
function DataExistedChk($ID){
	$result = queryMysql("select count(*) from user where ID='$ID'");
	if($row = mysqli_fetch_row($result))
		return $row[0];
	else
		return "0";
}

//ID for delete check
function DataDeleteChk($filename) {
	$delcount = 0;
	$sql = queryMysql("SELECT ID FROM user");
	while($row = mysqli_fetch_array($sql)) {
		$result = (string)$row[0];
		$file_read = fopen($filename,"r");
		while($line = fgetcsv($file_read, 1000, ",")) { // 구분자는 , 로 지정
			// 파일 인코딩 모드 검사
			if($current_encoding != 'utf-8')
			    $line00 = iconv('euc-kr','utf-8',trim($line[0])); // ID
			else
			    $line00 = trim($line[0]); // ID
			
			$ID = $line00;
			
			if($result == $ID) break;
		}
		if(!$line && $result!='admin') {
			$del = queryMysql("DELETE FROM user WHERE ID='$result'");
			$del = queryMysql("DELETE FROM codes WHERE ID='$result'");
			$delcount++;
		}
		fclose($file_read);
	}
	return $delcount;
}
			
if(isset($_FILES['file'])) {

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
		
		$fileExt = getExt($realname);
		if ('csv' != $fileExt) {
		    $error = "csv 파일만 등록할 수 있습니다.<br>";
		}
		else {
			$readFile = "userdata.csv";
			
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
			$total_line = 0;
			$newcount = 0;
			$delcount = 0;
			$ok = 0;
			
			$line = fgetcsv($file_read, 1000, ",");	//jump index
			
			while($line = fgetcsv($file_read, 1000, ",")) { // 구분자는 , 로 지정
			    // 파일 인코딩 모드 검사
			    if($current_encoding != 'utf-8') {
			        $line00 = iconv('euc-kr','utf-8',trim($line[0])); // ID
			        $line01 = iconv('euc-kr','utf-8',trim($line[1])); // password
			        $line02 = iconv('euc-kr','utf-8',trim($line[2])); // name
			        $line03 = iconv('euc-kr','utf-8',trim($line[3])); // subject
			    	$line04 = iconv('euc-kr','utf-8',trim($line[4])); // email
					$line05 = iconv('euc-kr','utf-8',trim($line[5])); // year
					$line06 = iconv('euc-kr','utf-8',trim($line[6])); // semester
				} else {
			        $line00 = trim($line[0]); // ID
			        $line01 = trim($line[1]); // password
			        $line02 = trim($line[2]); // name
			        $line03 = trim($line[3]); // subject
					$line04 = trim($line[4]); // email
					$line05 = trim($line[5]); // year
					$line06 = trim($line[6]); // semester
			    }
			
			    $ID = $line00;
			    $password = $line01;
			    $name = $line02;
			    $subject = $line03;
				$email = $line04;
			    $year = $line05;
				$semester = $line06;
				
			    $total_line++;
			
			    // 중복 등록 여부 검사
			    $cnt = DataExistedChk($ID);
			    if($cnt == "0"){
			        $result = queryMysql("INSERT INTO user VALUES('$ID', '$password', '$name', '$subject', '$email', '$year', '$semester')");
			        $newcount++;
			    }
				else {
					$result = queryMysql("UPDATE user SET password='$password', name='$name', email='$email' WHERE ID='$ID'");
				}
				
			    $ok++;
			    if (($ok % 500) == '0') {
			        $msg = "<br><br>$ok 건 저장";
			        flush();
			        sleep(2); //500개 저장할때마다 2초씩 쉰다.
			    }
			} // while 문 종료
			fclose($file_read);
			$delcount = DataDeleteChk($readFile);
			unlink($readFile);  // 업로드 완료후에 파일 삭제 처리
			
			$msg = "<br><br>" . '전체'.number_format($total_line).'건 중 신규'.number_format($newcount).'건, 삭제'.number_format($delcount).'건 등록완료';
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

		<title>Upload Students</title>
		<style>
			body {
				position: relative;
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
			
			#exprogram {
				position: absolute;
				height: 85vh;
				border-right: 0.7px solid;
			}
			
			#logout {
				position: absolute;
				top: 87vh;
			}
			
			.uploadform {
				width: 400px;
			}
			
			.uploadform > div {
				display: flex;
				justify-content: center;
				padding-bottom: 7px;
				align-items: center;
			}
			
			.error {
				color: red;
			}
			
			.expr {
				margin: 40px 5px 0px 0px;
			}
			
			.exit {
				margin-left: 40px;
			}
			
			label {
				flex: 1;
				text-align: left;
			}
		</style>
	</head>
_END;

if(isset($_SESSION['user'])) {
	echo <<<_END
		<body>
			<h2 align='center'>Upload Students</h2>
			<hr size='1' noshade></hr>
			<div id='uploaddiv'>
				<form class='uploadform' action="uploaduser.php" method="post" enctype='multipart/form-data'>
					<div>
						<span class='error'>$error</span>
					</div>
					<div>
						<span>$success</span>
					</div>
					<div>
						<label>파일선택</label>
						<input type="file" name="file" id="file"></input>
					</div>
					<div>
						<label></label>
						<br><br>
						<input data-transition='slide' type='submit'>
					</div>
					<div>
						<span>$msg</span>
					</div>
				</form>
			</div>
			
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
				alert('Please log in to use this page.');
				location.href='login.php';
			</script>
		</body>
	</html>
	_END;
}

?>