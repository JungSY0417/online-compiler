<?php

$dbhost = 'localhost';	//Unlikely to require changing
$dbname = 'oncompile';	//Modify these...
$dbuser = 'root';	//...variables according
$dbpass = 'mysql';	//...to your installation

$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if($connection->connect_error) die("Fatal Error");

function createTable($name, $query)
{
	queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
	echo "Table '$name' created or already exitsts.<br>";
}

function queryMysql($query)
{
	global $connection;
	$result = $connection->query($query);
	if(!$result) die("Fatal Error");
	return $result;
}

function sanitizeString($var)
{
	global $connection;
	$var = strip_tags($var);
	$var = htmlentities($var);
	if(get_magic_quotes_gpc())
		$var = stripslashes($var);
	return $connection->real_escape_string($var);
}

function destroySession()
{
	$_SESSION=array();
	
	if(session_id() != "" || isset($_COOKIE[session_name()]))
		setcookie(session_name(), '', time()-2592000, '/');
		
	session_destroy();
}

function semester() {
	$mon = date("n");

	if($mon == 1 || $mon == 2)
		$sem = 'win';
	else if($mon == 3 || $mon == 4 || $mon == 5)
		$sem = '1';
	else if($mon == 7 || $mon == 8)
		$sem = 'sum';
	else if($mon == 9 || $mon == 10 || $mon == 11)
		$sem = '2';
	else if($mon == 6) {
		if(date("j") <= 15)
			$sem = '1';
		else if(date("j") > 15)
			$sem = 'sum';
	}
	else if($mon == 12) {
		if(date("j") <= 15)
			$sem = '2';
		else if(date("j") > 15)
			$sem = 'win';
	}
	return $sem;
}
?>