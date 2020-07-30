<!DOCTYPE html>
<html>
	<head>
		<title>Setting up database</title>
	</head>
	<body>
	
		<h3>Setting up...</h3>
	
<?php
	require_once 'functions.php';
	
	createTable('user', 'ID VARCHAR(10), password VARCHAR(128), name VARCHAR(128), subject VARCHAR(128),
				email VARCHAR(128), year VARCHAR(4), semester VARCHAR(5), PRIMARY KEY(ID)');
	
	createTable('codes', 'ID VARCHAR(10), subject VARCHAR(128), code longtext, number int(11),
				year VARCHAR(4), semester VARCHAR(5), PRIMARY KEY(number)');
				
	queryMysql("INSERT INTO user VALUES('Prof', '12345', 'example', 'internet', 'sdjdr87@gmail.com', '2020', 'sum')");
?>

		<br>...done.
	</body>
</html>