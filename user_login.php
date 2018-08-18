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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}
$_SESSION['found_user'] = NULL;
unset($_SESSION['found_user']);
$_SESSION['found_user'] = true;
if (isset($_POST['email']) && isset($_POST['button_Login'])) {
  $loginUsername=$_POST['email'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "access_level";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "index2.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_akonsudoy, $akonsudoy);
  	
  $LoginRS__query=sprintf("SELECT email, password, access_level FROM users WHERE email=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $akonsudoy) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'access_level');
mysql_select_db($database_akonsudoy, $akonsudoy);
$query_user_id = "SELECT * FROM users WHERE email='".$loginUsername."'";
$user_id = mysql_query($query_user_id, $akonsudoy) or die(mysql_error());
$row_user_id = mysql_fetch_assoc($user_id);
$totalRows_user_id = mysql_num_rows($user_id);
mysql_free_result($user_id);   
    //declare two session variables and assign them
	$_SESSION['user_id'] = $row_user_id['user_id'];
	$_SESSION['access_level'] = $row_user_id['access_level'];
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    //header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
	  $_SESSION['found_user'] = false;
	  
    //header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
</head>

  <form ACTION="<?php echo $loginFormAction; ?>" id="loginform" name="form1" method="POST">
<table width="auto" border="0" align="center">
  <tr>
    <td colspan="2">
    <div align="center">
  <?php
  //if ($_SESSION['found_user'] == false) {
	 // echo "ERROR: username and password did not match!";
 // }else{
	 // header("Location: index2.php" );
 // }
  ?>
  </div>
    </td>
    </tr>
  <tr>
    <td>E-mail:</td>
    <td><input type="text" name="email" id="user_login" class="input" value="" size="15" tabindex="10" /></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input type="password" name="password" id="user_pass" class="input" value="" size="15" tabindex="20" /></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <a href="index2.php?action=login">
      <input type="submit" name="button_Login" id="wp-submit" value="Log In" tabindex="100" /></a> </div>

      </td>
    </tr>
  <tr>
    <td colspan="2"><div align="center"><a href="index2.php?page_id=1">Forgot password?</a>
      If you do not have an account yet, <a href="index2.php?page_id=2">please register for FREE</a></div></td>
  </tr>
</table>
</form>
</body>
</html>
