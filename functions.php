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

?>