<?php require_once('Connections/akonsudoy.php'); ?>
<?php
unset($msg);
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO manufacturers (logo, location, description, owner, business_name, address, zip_code, phone, cellphone, email) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['logo'], "text"),
                       GetSQLValueString($_POST['location'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['owner'], "text"),
                       GetSQLValueString($_POST['business_name'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['zip_code'], "int"),
                       GetSQLValueString($_POST['phone'], "int"),
                       GetSQLValueString($_POST['cellphone'], "int"),
                       GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_akonsudoy, $akonsudoy);
  $Result1 = mysql_query($insertSQL, $akonsudoy) or die(mysql_error());
}else{
$msg = "ERROR: Failed to insert the data.";
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<div align="center">
<?php
if(empty($msg) && isset($_POST["MM_insert"])){
echo "Manufacturer was successfully added!";
}elseif(isset($msg) && isset($_POST["MM_insert"])){
echo $msg;
}
?>
</div>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="auto" border="0" align="center">
    <tr>
      <td><div align="right">Business Name:</div></td>
      <td><label>
        <input name="business_name" type="text" id="business_name" />
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Description:</div></td>
      <td><label>
        <textarea name="description" id="description"></textarea>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Address:</div></td>
      <td><label>
        <input name="address" type="text" id="address" />
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Zip code:</div></td>
      <td><label>
        <input name="zip_code" type="text" id="zip_code" />
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Owner:</div></td>
      <td><label>
        <input name="owner" type="text" id="owner" />
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Phone Number </div></td>
      <td><label>
        <input name="phone" type="text" id="phone" />
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Cellphone Number:</div></td>
      <td><label>
        <input name="cellphone" type="text" id="cellphone" />
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Email:</div></td>
      <td><label>
        <input name="email" type="text" id="email" />
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Logo of Business: </div></td>
      <td><input name="logo" type="file" id="logo" /></td>
    </tr>
    <tr>
      <td><div align="right">Location:</div></td>
      <td><input name="location" type="file" id="location" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="center">
        <label>
        <input type="submit" name="Submit" value="Submit" />
        </label>
</div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
