<?php
session_start();

$user = $_SESSION['user'];
$subj = $_SESSION['subj'];
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

require_once 'functions.php';

if(isset($_POST['codearea']))
{
	$same = 0;
	$text = $_POST['codearea'];

	$sql = queryMysql("SELECT code FROM codes WHERE ID='$user' AND subject='$subj' AND year='$year' AND semester='$sem'");
	while($row = mysqli_fetch_array($sql)) {
		$result = (string)$row[0];
		if($result == $text)
			$same = 1;
	}
	
	if($same != 1) {
		$text = str_replace("\\", "\\\\", $text);
		$text = str_replace("'", "\'", $text);
		queryMysql("INSERT INTO codes VALUES('$user', '$subj', '$text', '$year', '$sem')");
	}
}

?>