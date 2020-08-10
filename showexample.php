<?php
session_start();

$subj = $_SESSION['subj'];

require_once 'functions.php';

if(isset($_POST['n']))
{
	$num = (int)$_POST['n'];
	$sql = queryMysql("SELECT code FROM codes WHERE ID='admin' AND subject='$subj'");
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