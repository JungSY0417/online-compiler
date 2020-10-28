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
	
	createTable('codes', 'ID VARCHAR(10), subject VARCHAR(128), code longtext,
				year VARCHAR(4), semester VARCHAR(5), time DATETIME DEFAULT current_timestamp');
			
	createTable('session', 'start datetime, IP VARCHAR(20), end datetime');
	
	createTable('duedate', 'start datetime, end datetime, subject VARCHAR(128)');
		
	queryMysql("INSERT INTO user VALUES('admin', '12345', 'administer', 'admin', NULL, NULL, NULL)");
?>

		<br>...done.
	</body>
</html>