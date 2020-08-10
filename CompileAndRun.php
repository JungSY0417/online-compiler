<?php
session_start();

$user = $_SESSION['user'];
$subj = $_SESSION['subj'];

require_once 'functions.php';

echo <<<_END
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel='stylesheet' href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">
		<link rel='stylesheet' href="styles.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
		<script src='downloadcode.js'></script>
		<script src='compiler-jQuery.js'></script>
		<script src='linenumber.js'></script>
		
		<title>Online Compiler</title>
		<script>
			function display() {
				var n = $('#programlist').val();
				$.ajax({
					type: 'POST',
					url: "programlist.php",
					data: {"n":n},
					success: function(result) {
						$('#codearea').html(result);
					}
				})
			}
			
			function showex() {
				var n = $('#exampleprog').val();
				$.ajax({
					type: 'POST',
					url: "showexample.php",
					data: {"n":n},
					success: function(result) {
						$('#codearea').html(result);
					}
				})
			}
		</script>
	</head>
_END;

if(isset($_SESSION['user'])) {
	echo <<<_END
		<body>
			<fieldset>
				<form id='codeform' method='post' action='compile.php'>
					<select class='button' id='exampleprog' onchange="showex()">
						<option value="">Example Programs</option>
	_END;
	
	$j = 1;
	$sql = queryMysql("SELECT code FROM codes WHERE ID='admin' AND subject='$subj'");
	while($row = mysqli_fetch_array($sql)) {
		echo "<option value='$j'>Example $j</option>";
		++$j;
	}
	
	echo <<<_END
					</select>
					<select class='button' id='programlist' onchange="display()">
						<option value="">Program List</option>
	_END;
	
	$j = 1;
	$sql = queryMysql("SELECT code FROM codes WHERE ID='$user' AND subject='$subj'");
	while($row = mysqli_fetch_array($sql)) {
		echo "<option value='$j'>code $j</option>";
		++$j;
	}
	
	echo <<<_END
					</select>
					<input class='button' type='submit' id='run' value='RUN'></input>
					<button class='button' type='button' onclick="download()">Download Code</button>
					<textarea id='codearea' name='codearea' spellcheck="false" autofocus></textarea>
	
					<legend id='inputlegend' style='display:none'>Input</legend>
					<textarea id='inputarea' name='inputarea' spellcheck='false' style='display:none; width: 100%; height: 15vh; box-sizing: border-box;'></textarea>
					<button type='button' id='push' style='display:none'></button>
				</form>
					
				<script>
					$('#codearea').numberedtextarea();
					$('#codearea').allowTabChar();
					
					document.getElementById('inputarea').addEventListener('keydown',function(event){
	       				if(event.keyCode == 13){
	        				event.preventDefault();
	            			document.getElementById('push').click();
	        			}
	    			});
					
					$(document).ready(function(){
						$("#run").click(function(){
							$("#resultarea").html("Loading ......");
							
							$.ajax({
								type: "POST",
								url: "saveprogram.php",
								data: {"codearea": document.getElementById('codearea').value},
							});
						});
					});
					
					//wait for page load to initialize script
					$(document).ready(function(){
						//listen for form submission
						$("#codeform").on('submit', function(e){
							//prevent form from submitting and leaving page
							e.preventDefault();
									
							var code = document.getElementById('codearea').value;
							var code1 = code.replace(/\/\/(.)*/g,'');
							code1 = code1.replace(/\/\*(.)*(\\n)*\*\//g,'');
													
							if(code1.indexOf('getchar') != -1 ||
							   code1.indexOf('getche') != -1 ||
							   code1.indexOf('getch') != -1 ||
							   code1.indexOf('gets') != -1 ||
							   code1.indexOf('scanf') != -1) {
								document.getElementById('inputarea').style.display = "block";
								document.getElementById('inputlegend').style.display = "block";
								document.getElementById('inputarea').focus();
								
								$("#push").click(function(){
									$.ajax({
										type: "POST", //type of submit
										cache: false, //important or else you might get wrong data returned to you
										url: "compile.php", //destination
								        datatype: "text", //expected data format from process.php
								        data: {"codearea": code1, "input": document.getElementById('inputarea').value}, //target your form's data and serialize for a POST
								        success: function(result) { // data is the var which holds the output of your process.php
								
								        	// locate the div with #result and fill it with returned data from process.php
											$('#resultarea').html(result);
								        }
								    });
								})
							}
						
							else if(code1.indexOf('argc') != -1) {
								document.getElementById('inputarea').style.display = "block";
								document.getElementById('inputlegend').style.display = "block";
								
								$("#push").click(function(){
									$.ajax({
										type: "POST", //type of submit
										cache: false, //important or else you might get wrong data returned to you
										url: "compile.php", //destination
								        datatype: "text", //expected data format from process.php
								        data: {"codearea": code1, "rtarg": document.getElementById('inputarea').value}, //target your form's data and serialize for a POST
								        success: function(result) { // data is the var which holds the output of your process.php
								
								        	// locate the div with #result and fill it with returned data from process.php
											$('#resultarea').html(result);
								        }
								    });
								})
							}
							else {
								document.getElementById('inputarea').style.display = "none";
								document.getElementById('inputlegend').style.display = "none";
								document.getElementById('inputarea').value="";
								
								$.ajax({
									type: "POST", //type of submit
									cache: false, //important or else you might get wrong data returned to you
									url: "compile.php", //destination
							        datatype: "text", //expected data format from process.php
							        data: {"codearea": code}, //target your form's data and serialize for a POST
							        success: function(result) { // data is the var which holds the output of your process.php
							
							                // locate the div with #result and fill it with returned data from process.php
										$('#resultarea').html(result);
									}
								});
							}
					    });
					});
				</script>
			</fieldset>
			
			<fieldset>
				<textarea id='resultarea' spellcheck="false" readonly></textarea>
			</fieldset>
			
			<form method='POST' action='logout.php' align='right'>
				<input type='submit' id='exit' value='EXIT'></input>
			</form>
		</body>
	</html>
	_END;
}

else {
	echo <<<_END
		<body>
			<script>
				alert('Please log in to use this page.');
				location.href='login.php';
			</script>
		</body>
	</html>
	_END;
}

?>