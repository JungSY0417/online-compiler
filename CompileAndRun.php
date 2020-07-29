<?php
session_start();

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
				var code = $('#programlist').val();
				$.ajax({
					type: 'POST',
					url: "programlist.php",
					data: {code:code},
					success: function(result) {
						$('#codearea').html(result);
					}
				})
			}
		</script>
	</head>
	
	<body>
		<fieldset>
			<form id='codeform' method='post' action='compile.php'>
				<select class='button' id='programlist' onchange="display()">
					<option value="">Program List</option>
_END;

$j = 1;
$sql = queryMysql("SELECT code FROM codes WHERE number='$j'");
while($row = mysqli_fetch_array($sql)) {
	echo "<option value='$j'>code $j</option>";
	++$j;
	$sql = queryMysql("SELECT code FROM codes WHERE number='$j'");
}

echo <<<_END
				</select>
				<input class='button' type='submit' id='run' value='RUN'></input>
				<button class='button' type='button' onclick="download()">Download Code</button>
				<textarea id='codearea' name='codearea' spellcheck="false" autofocus></textarea>
			</form>
			<script>
				$('#codearea').numberedtextarea();
				$('#codearea').allowTabChar();
				
				$(document).ready(function(){
					$("#run").click(function(){
						$("#resultarea").html("Loading ......");
					});
				});
				
				//wait for page load to initialize script
				$(document).ready(function(){
					//listen for form submission
					$("#codeform").on('submit', function(e){
						//prevent form from submitting and leaving page
						e.preventDefault();
						
						var code = document.getElementById('codearea').value;
						if(code.indexOf('getchar') != -1 ||
						   code.indexOf('getche') != -1 ||
						   code.indexOf('getch') != -1 ||
						   code.indexOf('gets') != -1 ||
						   code.indexOf('scanf') != -1) {
							var input=prompt("Put value for input function.");
						}
								
						// AJAX 
						$.ajax({
							type: "POST", //type of submit
							cache: false, //important or else you might get wrong data returned to you
							url: "compile.php", //destination
				            datatype: "text", //expected data format from process.php
				            data: {"codearea": code, "input": input}, //target your form's data and serialize for a POST
				            success: function(result) { // data is the var which holds the output of your process.php
				
				                // locate the div with #result and fill it with returned data from process.php
								$('#resultarea').html(result);
				            }
				        });
				    });
				});
			</script>
		</fieldset>
		
		<fieldset>
			<textarea id='resultarea' spellcheck="false"></textarea>
		</fieldset>
		
		<form method='post' action='login.php' align='right'>
			<input type='submit' id='exit' value='EXIT'></input>
		</form>
	</body>
</html>
_END;

?>