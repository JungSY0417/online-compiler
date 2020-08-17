<?php
session_start();

require_once 'functions.php';

$subj = $_SESSION['subj'];
$year = date("Y");
$sem = semester();

if(isset($_POST['n']))
{
	$num = (int)$_POST['n'];
	$sql = queryMysql("SELECT code FROM codes WHERE ID='admin' AND subject='$subj' AND year='$year' AND semester='$sem'");
	$i = 0;
	while($i < $num) {
		$row = mysqli_fetch_array($sql);
		$i++;
	}
	$result = (string)$row[0];
	echo $result;
}
else
{
	echo "";
}

?>