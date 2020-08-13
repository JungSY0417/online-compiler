<?php
session_start();

require_once 'functions.php';

if(isset($_SESSION['user']))
{
	$start = $_SESSION['start'];
	$IP = $_SESSION['IP'];

	$now = new DateTime();
	$now = $now->format('Y-m-d H:i:s');
	
	$result = queryMysql("INSERT INTO session VALUES('$start', '$IP', '$now')");
	
	destroySession();
	die("<script>location.href='login.php';</script>");
}

?>