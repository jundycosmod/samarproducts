<?php require_once('Connections/akonsudoy.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

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
    if (($strUsers == "") && false) { 
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
$currentPage = $_SERVER["PHP_SELF"];

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

if ((isset($_GET['product_id'])) && ($_GET['product_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM products WHERE product_id=%s",
                       GetSQLValueString($_GET['product_id'], "int"));

  mysql_select_db($database_akonsudoy, $akonsudoy);
  $Result1 = mysql_query($deleteSQL, $akonsudoy) or die(mysql_error());

  $deleteGoTo = "admin_product.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
 // header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_products = 10;
$pageNum_products = 0;
if (isset($_GET['pageNum_products'])) {
  $pageNum_products = $_GET['pageNum_products'];
}
$startRow_products = $pageNum_products * $maxRows_products;

mysql_select_db($database_akonsudoy, $akonsudoy);
$query_products = "SELECT * FROM products";
$query_limit_products = sprintf("%s LIMIT %d, %d", $query_products, $startRow_products, $maxRows_products);
$products = mysql_query($query_limit_products, $akonsudoy) or die(mysql_error());
$row_products = mysql_fetch_assoc($products);

if (isset($_GET['totalRows_products'])) {
  $totalRows_products = $_GET['totalRows_products'];
} else {
  $all_products = mysql_query($query_products);
  $totalRows_products = mysql_num_rows($all_products);
}
$totalPages_products = ceil($totalRows_products/$maxRows_products)-1;

$queryString_products = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_products") == false && 
        stristr($param, "totalRows_products") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_products = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_products = sprintf("&totalRows_products=%d%s", $totalRows_products, $queryString_products);
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
.style3 {font-size: 14px}
-->
</style></head>

<body>
<p><img src="images/products_list.png" width="280" height="40" /></p>
<table border="3" align="center" cellpadding="2" cellspacing="2" bordercolor="#0099CC" bgcolor="#FFFFFF">
  <tr>
    <td bgcolor="#FFFFFF"><div align="center" class="style3">
      <div align="center">product_code</div>
    </div></td>
    <td bgcolor="#FFFFFF"><div align="center" class="style3">
      <div align="center">product_name</div>
    </div></td>
    <td bgcolor="#FFFFFF"><div align="center" class="style3">
      <div align="center">product_type</div>
    </div></td>
    <td bgcolor="#FFFFFF"><div align="center" class="style3">
      <div align="center">price</div>
    </div></td>
    <td bgcolor="#FFFFFF"><div align="center" class="style3">
      <div align="center">product_picture</div>
    </div></td>
    <td colspan="2" bgcolor="#FFFFFF"><div align="center" class="style3">
      <div align="center">actions</div>
    </div></td>
  </tr>
  <?php do { ?>
    <tr>
      <td bgcolor="#FFFFFF"><div align="center"><span class="style3"><?php echo $row_products['product_code']; ?></span></div></td>
      <td bgcolor="#FFFFFF"><div align="center"><span class="style3"><?php echo $row_products['product_name']; ?></span></div></td>
      <td bgcolor="#FFFFFF"><div align="center"><span class="style3"><?php echo $row_products['product_type']; ?></span></div></td>
      <td bgcolor="#FFFFFF"><div align="center"><span class="style3"><?php echo $row_products['price']; ?></span></div></td>
      <td bgcolor="#FFFFFF"><div align="center"><img src="images/<?php echo $row_products['product_picture']; ?>" width="30" height="30" /></div></td>
      <td bgcolor="#FFFFFF"><div align="center"><a href="index2.php?page_id=10&action=update&product_id=<?php echo $row_products['product_id']; ?>" class="style3">update</a></div></td>
      <td bgcolor="#FFFFFF"><div align="center"><a href="index2.php?page_id=9&action=delete&product_id=<?php echo $row_products['product_id']; ?>" class="style3">delete</a></div></td>
    </tr>
    <?php } while ($row_products = mysql_fetch_assoc($products)); ?>
</table>
<p>&nbsp;<a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, 0, $queryString_products); ?>">First</a> | <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, max(0, $pageNum_products - 1), $queryString_products); ?>">Previous</a> | <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, min($totalPages_products, $pageNum_products + 1), $queryString_products); ?>">Next</a> | <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, $totalPages_products, $queryString_products); ?>">Last</a></p>
</body>
</html>
<?php
mysql_free_result($products);
?>
