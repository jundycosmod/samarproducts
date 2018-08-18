<?php require_once('Connections/akonsudoy.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index2.php?page_id=2";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}
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

mysql_select_db($database_akonsudoy, $akonsudoy);
$query_change_pass = "SELECT user_id, email, password FROM users WHERE user_id='".$_SESSION['user_id']."'";
$change_pass = mysql_query($query_change_pass, $akonsudoy) or die(mysql_error());
$row_change_pass = mysql_fetch_assoc($change_pass);
$totalRows_change_pass = mysql_num_rows($change_pass);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1") && ($_POST['oldpass'] == $row_change_pass['password']) && ($_POST['newpass'] == $_POST['conpass'])) {
  $updateSQL = sprintf("UPDATE users SET email=%s, password=%s WHERE user_id=%s",
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['newpass'], "text"),
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_akonsudoy, $akonsudoy);
  $Result1 = mysql_query($updateSQL, $akonsudoy) or die(mysql_error());

  $updateGoTo = "home2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  //header(sprintf("Location: %s", $updateGoTo));
  unset($error_msg);
}elseif((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1") && $_POST['oldpass'] != $row_change_pass['password']){
	$error_msg = "incorrect old password.";
}elseif((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1") && $_POST['newpass'] != $_POST['conpass']){
	$error_msg = "please retype your new password and confirm it correctly.";
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Change Password</title>
<style type="text/css">
<!--
body,td,th {
	font-size: 12px;
}
.style1 {color: #FF0000}
-->
</style></head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
<img src="images/change_pass.png" width="280" height="40" />
<br />
<br />
<?php
if(isset($error_msg)){
	echo "<div align='center' class=\"style1\">".$error_msg."</div>";
}
?>
<br />
  <table width="200" border="0" align="center">
    <tr>
      <td>E-mail:</td>
      <td><label>
        <input name="email" type="text" id="email" value="<?php echo $row_change_pass['email']; ?>" readonly="readonly"/>
      </label>
      <input type="hidden" name="user_id" id="user_id" value="<?php echo $row_change_pass['user_id']; ?>"/>
      </td>
    </tr>
    <tr>
      <td>Old Password:</td>
      <td><label>
        <input type="password" name="oldpass" id="oldpass" />
      </label></td>
    </tr>
    <tr>
      <td>New Password:</td>
      <td><label>
        <input type="password" name="newpass" id="newpass" />
      </label></td>
    </tr>
    <tr>
      <td>Confirm Password:</td>
      <td><label>
        <input type="password" name="conpass" id="conpass" />
      </label></td>
    </tr>
    <tr>
      <td colspan="2"><label>
        <div align="center">
          <input type="submit" name="button" id="button" value="Submit" />
        </div>
      </label></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
</form>
</body>
</html>
<?php
mysql_free_result($change_pass);
?>
