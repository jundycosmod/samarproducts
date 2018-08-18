<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
//$hostname_akonsudoy = "mysql4.freehostia.com";
//$database_akonsudoy = "donlap_sudoy";
//$username_akonsudoy = "donlap_sudoy";
//$password_akonsudoy = "sudoy";
//$hostname_akonsudoy = "localhost";
//$database_akonsudoy = "akonsudoy";
//$username_akonsudoy = "root";
//$password_akonsudoy = "";
$hostname_akonsudoy = "127.0.0.1";
$database_akonsudoy = "akonsudoy";
$username_akonsudoy = "root";
$password_akonsudoy = "";
$akonsudoy = mysqli_connect($hostname_akonsudoy, $username_akonsudoy, $password_akonsudoy) or trigger_error(mysql_error(), E_USER_ERROR);
?>