<?php
session_start();

require_once 'functions.php';

$user = $_SESSION['user'];
$subj = $_SESSION['subj'];
$year = date("Y");
$sem = semester();

if(isset($_POST['n']))
{
	$num = (int)$_POST['n'];
	$sql = queryMysql("SELECT code FROM codes WHERE ID='$user' AND subject='$subj' AND year='$year' AND semester='$sem'");
	$i = 0;
	while($i < $num) {
		$row = mysqli_fetch_array($sql);
		$i++;
	}
	$result = (string)$row[0];
	echo $result;
}
else
{
	echo "";
}

/*AUTO_INCREMENT가 적용된 컬럼값 재정렬하기
  SET @COUNT = 0;

  UPDATE [테이블명] SET [컬럼명] = @COUNT:=@COUNT+1;
*/

?>