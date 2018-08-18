<?php require_once('Connections/akonsudoy.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_password = "-1";
if (isset($_POST['email2'])) {
  $colname_password = $_POST['email2'];
}
mysql_select_db($database_akonsudoy, $akonsudoy);
$query_password = sprintf("SELECT * FROM users WHERE email = %s", GetSQLValueString($colname_password, "text"));
$password = mysql_query($query_password, $akonsudoy) or die(mysql_error());
$row_password = mysql_fetch_assoc($password);
$totalRows_password = mysql_num_rows($password);

if (function_exists('nukeMagicQuotes')) {
  nukeMagicQuotes();
  }

// process the email
if (array_key_exists('send', $_POST)) {
  $to = $_POST['email2']; // use your own email address
  $subject = 'Password from sudoy.com site';
  
  // process the $_POST variables
  $email = $_POST['email2'];
  
  // build the message
  $message = $row_password['password'];

  // limit line length to 70 characters
  $message = wordwrap($message, 70);
  
  // send it  
  $mailSent = mail($to, $subject, $message);
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
body,td,th {
	font-size: 12px;
}
.style1 {color: #FF0000}
-->
</style></head>

<body>
<img src="images/lost_pass.png" width="280" height="40" />
<form id="form1" name="form1" method="post" action="">
  <label>
    <div align="center">
      <?php
		if ($_POST && !$mailSent) {
		?>
    </div>
  </label>
  <p align="center" class="warning style1">Sorry, there was a problem sending your message. Please try later.</p>
  <p align="center" class="warning style1">Possible reason: your host may have disabled the mail() function...</p>
  <div align="center">
    <?php
		  }
		elseif ($_POST && $mailSent) {
		?>
  </div>
  <p align="center"><strong>Your password has been sent to your email.</strong></p>
  <div align="center">
    <?php } ?>
  </div>
    <div align="center">Please enter your email address. Your password will be sent through email.<br />
      Email:
      <input type="text" name="email2" id="email" />
      <input type="submit" name="send" id="button" value="Get Password" />
      <br />
      <br />
    </div>
</form>
</body>
</html>
<?php
mysql_free_result($password);
?>
