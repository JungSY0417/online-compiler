<?php
session_start();

require_once 'functions.php';

date_default_timezone_set('Asia/Seoul');	//timezone을 한국으로 설정

$user = $_SESSION['user'];
$subj = $_SESSION['subj'];
$year = date("Y");
$sem = semester();

if(isset($_POST['codearea']))
{
	$same = 0;
	$due = 0;
	$text = $_POST['codearea'];
	$now = strtotime(date('Y-m-d H:i:s'));

	$sql = queryMysql("SELECT code FROM codes WHERE ID='$user' AND subject='$subj' AND year='$year' AND semester='$sem'");
	while($row = mysqli_fetch_array($sql)) {
		$result = (string)$row[0];
		if($result == $text) {
			$same = 1;
			break;
		}
	}
	
	$sql = queryMysql("SELECT start, end FROM duedate WHERE subject='$subj'");
	while($row = mysqli_fetch_array($sql)) {
		$start = strtotime($row[0]);
		$end = strtotime($row[1]);
		
		$diff1 = $now - $start;
		$diff2 = $end - $now;
		if($diff1 >= 0 && $diff2 >= 0) {
			$due = 1;
			break;
		}
	}
	
	if($same != 1 && $due != 0) {
		$text = str_replace("\\", "\\\\", $text);
		$text = str_replace("'", "\'", $text);
		$now = date("Y-m-d H:i:s");
		queryMysql("INSERT INTO codes VALUES('$user', '$subj', '$text', '$year', '$sem', '$now')");
		echo "제출 성공";
	}
	elseif($same == 1) {
		echo "이미 제출된 코드입니다";
	}
	elseif($due == 0) {
		echo "제출 기한이 아닙니다";
	}
}

?>