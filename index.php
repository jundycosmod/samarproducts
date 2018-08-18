<?php require_once('Connections/akonsudoy.php'); ?>
<?php
$user_id = 0;
$deleteSQL = sprintf("DELETE FROM cart WHERE user_id=%s",
                       $user_id);

mysql_select_db($database_akonsudoy, $akonsudoy);
$Result1 = mysql_query($deleteSQL, $akonsudoy) or die(mysql_error());
header("Location: index2.php");
?>