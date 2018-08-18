<?php require_once 'Connections/akonsudoy.php';?>
<?php
$user_id = 0;
$deleteSQL = sprintf("DELETE FROM cart WHERE user_id=%s",
	$user_id);

mysqli_select_db($akonsudoy, $database_akonsudoy);
$Result1 = mysqli_query($akonsudoy, $deleteSQL) or die(mysql_error());
header("Location: index2.php");
?>