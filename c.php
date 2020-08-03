<?php
    
    putenv("PATH=C:\MinGW\bin");
	$CC="gcc";
	$out="main.exe";
	$filename="main";
	$code=$_POST["codearea"];
	$input=$_POST["input"];
	$runtime_argument=$_POST["rtarg"];
	$filename_code="main.c";
	$filename_in="input.txt";
	$filename_arg="argument.txt";
	$filename_error="error.txt";
	$executable="main.exe";
	$command=$CC." -o ".$filename." ".$filename_code;
	$command_error=$command." 2>".$filename_error;

	if(trim($code)=="")
		die("The code area is empty");
	
	$file_code=fopen($filename_code,"w+");
	fwrite($file_code,$code);
	fclose($file_code);
	$file_in=fopen($filename_in,"w+");
	fwrite($file_in,$input);
	fclose($file_in);
	exec("cacls  $executable /g everyone:f"); 
	exec("cacls  $filename_error /g everyone:f");	

	shell_exec($command_error);
	$error=file_get_contents($filename_error);

	if(trim($error)=="")
	{
		if(trim($input)=="" && trim($runtime_argument) == "")
		{
			$output=shell_exec($out);
		}
		else if(trim($runtime_argument) != "")
		{
			$out = $out." ".$runtime_argument;
			$output=shell_exec($out);
		}
		else
		{
			$out=$out." < ".$filename_in;
			$output=shell_exec($out);
		}
		echo "$output";
	}
	else if(!strpos($error,"error"))
	{
		echo "$error";
		if(trim($input)=="" && trim($runtime_argument) == "")
		{
			$output=shell_exec($out);
		}
		else if(trim($runtime_argument) != "")
		{
			$out = $out." ".$runtime_argument;
			$output=shell_exec($out);
		}
		else
		{
			$out=$out." < ".$filename_in;
			$output=shell_exec($out);
		}
		echo "$output";
	}
	else
	{
		echo "$error";
	}
	exec("del main.c");
	exec("del *.o");
	exec("del *.txt");
	exec("del main.exe");

?>