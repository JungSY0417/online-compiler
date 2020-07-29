<?php

require_once 'functions.php';

if(isset($_POST['code']))
{
	$num = (int)$_POST['code'];
	$sql = queryMysql("SELECT code FROM codes WHERE number=$num");
	$row = $sql->fetch_row();
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