<?php
session_start();

$user = $_SESSION['user'];
$subj = $_SESSION['subj'];

require_once 'functions.php';

$same = 0;

if(isset($_POST['codearea']))
{
	global $same;
	$text = $_POST['codearea'];
	$sql = queryMysql("SELECT code FROM codes");
	while($row = mysqli_fetch_array($sql)) {
		$result = (string)$row[0];
		if($result == $text) {
			global $same;
			$same = 1;
		}
	}
	
	if($same != 1)
		queryMysql("INSERT INTO codes VALUES('$user', '$subj', '$text', NULL, '2020', 'sum')");
}

if($_FILES["file"]["name"]!="")
{
	include "make.php";
}
else
{
	include("c.php");
}

?>