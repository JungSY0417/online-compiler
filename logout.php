<?php
session_start();

require_once 'functions.php';

if(isset($_SESSION['user']))
{
	destroySession();
	die("<script>location.href='login.php';</script>");
}

?>