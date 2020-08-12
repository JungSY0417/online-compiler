<?php
session_start();

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