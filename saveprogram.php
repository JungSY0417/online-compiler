<?php
session_start();

$user = $_SESSION['user'];
$subj = $_SESSION['subj'];

require_once 'functions.php';

if(isset($_POST['codearea']))
{
	$same = 0;
	$text = $_POST['codearea'];

	$sql = queryMysql("SELECT code FROM codes WHERE ID='$user' AND subject='$subj'");
	while($row = mysqli_fetch_array($sql)) {
		$result = (string)$row[0];
		if($result == $text)
			$same = 1;
	}
	
	if($same != 1) {
		$text = str_replace("\\", "\\\\", $text);
		$text = str_replace("'", "\'", $text);
		queryMysql("INSERT INTO codes VALUES('$user', '$subj', '$text', '2020', 'sum')");
	}
}

?>