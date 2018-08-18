<?php require_once('Connections/akonsudoy.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
?>
<?php require_once('Connections/akonsudoy.php'); ?>
<?php 
require_once('Connections/akonsudoy.php'); 
require_once('home2.php');
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
}
if(!isset($_SESSION['user_id'])){
	$_SESSION['user_id'] = 0;
}
if(isset($_GET['action']) && $_GET['action']=="add"){
	
$colname_cart = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_cart = $_SESSION['user_id'];
}
mysql_select_db($database_akonsudoy, $akonsudoy);
$query_cart = sprintf("SELECT * FROM cart WHERE user_id = %s", GetSQLValueString($colname_cart, "int"));
$cart = mysql_query($query_cart, $akonsudoy) or die(mysql_error());
$row_cart = mysql_fetch_assoc($cart);
$totalRows_cart = mysql_num_rows($cart);
mysql_select_db($database_akonsudoy, $akonsudoy);
$query_check_quantity = "SELECT * FROM cart WHERE user_id = '".$_SESSION['user_id']."' AND product_id = '".$_GET['product_id']."'";
$check_quantity = mysql_query($query_check_quantity, $akonsudoy) or die(mysql_error());
$row_check_quantity = mysql_fetch_assoc($check_quantity);
$totalRows_check_quantity = mysql_num_rows($check_quantity);
mysql_free_result($check_quantity);

if($_GET['product_id'] == $row_check_quantity['product_id']){
if($totalRows_check_quantity ==0){
	$row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
	$insertSQL = sprintf("INSERT INTO cart (user_id, product_id, quantity) VALUES (%s, %s, %s)",
                      $_SESSION['user_id'],
                       $_GET['product_id'],
					   $row_check_quantity['quantity']);
  
  mysql_select_db($database_akonsudoy, $akonsudoy);
  $Result1 = mysql_query($insertSQL, $akonsudoy) or die(mysql_error());
}else{
	$row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
	$updateSQL = sprintf("UPDATE cart SET user_id=%s, product_id=%s, quantity=%s WHERE cart_id=%s",
                       $_SESSION['user_id'],
                       $_GET['product_id'],
                       $row_check_quantity['quantity'],
                       $row_check_quantity['cart_id']);
  mysql_select_db($database_akonsudoy, $akonsudoy);
  $Result2 = mysql_query($updateSQL, $akonsudoy) or die(mysql_error());
}
}else{
if($totalRows_check_quantity ==0){
	$row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
	$insertSQL = sprintf("INSERT INTO cart (user_id, product_id, quantity) VALUES (%s, %s, %s)",
                      $_SESSION['user_id'],
                       $_GET['product_id'],
					   $row_check_quantity['quantity']);
  
  mysql_select_db($database_akonsudoy, $akonsudoy);
  $Result1 = mysql_query($insertSQL, $akonsudoy) or die(mysql_error());
}else{
	$row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
	$updateSQL = sprintf("UPDATE cart SET user_id=%s, product_id=%s, quantity=%s WHERE cart_id=%s",
                       $_SESSION['user_id'],
                       $_GET['product_id'],
                       $row_check_quantity['quantity'],
                       $row_check_quantity['cart_id']);
  mysql_select_db($database_akonsudoy, $akonsudoy);
  $Result2 = mysql_query($updateSQL, $akonsudoy) or die(mysql_error());
}	
}
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
}

$maxRows_products = 10;
$pageNum_products = 0;
if (isset($_GET['pageNum_products'])) {
  $pageNum_products = $_GET['pageNum_products'];
}
$startRow_products = $pageNum_products * $maxRows_products;

mysql_select_db($database_akonsudoy, $akonsudoy);
$query_products = "SELECT * FROM products WHERE product_type = '".$_GET['product_type']."'";
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

mysql_select_db($database_akonsudoy, $akonsudoy);
$query_cart = "SELECT * FROM cart WHERE user_id = '".$_SESSION['user_id']."'";
$cart = mysql_query($query_cart, $akonsudoy) or die(mysql_error());
$row_cart = mysql_fetch_assoc($cart);
$totalRows_cart = mysql_num_rows($cart);

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
<title>products</title>
<style type="text/css">
<!--
body,td,th {
	font-size: 12px;
}
-->
</style></head>

<body>
<p>Your Shopping Cart</p>
<p>
<a href="index2.php?page_id=6">
<?php  

if(!isset($_SESSION['user_id']) && isset($_GET['product_id'])){
	$y = 0;
	$quantity = 0;
while($y < 100){
	if($quantity_id[$y] != ""){
	$quantity = $quantity + $quantity_id[$y];
	$y++;
	}else{
	$y = 100;	
	}
}
if($quantity == 0){
	echo "You have no items in your shopping cart";
} else {
	echo "You have ".$quantity." items in your shopping cart";
}	
}else{
$quantity = 0;
do{
	$quantity = $quantity + $row_cart['quantity'];
} while ($row_cart = mysql_fetch_assoc($cart));
if($quantity == 0){
	echo "You have no items in your shopping cart";
} else {
	echo "You have ".$quantity." items in your shopping cart";
}
}
?>
</a></p>
<form id="form1" name="form1" method="post" action="">
<div id="body">
<div class="inner">
                  <?php 
				  $i = 1;
				  do { 
				  $x = $i%2;
				  if($x==0){
					$class = "rightbox";  
				  }else{
					$class = "leftbox";  
				  }
				  ?>
<div class="<?php echo $class; ?>">
<h3><?php echo "Product Name: ".$row_products['product_name']; ?></h3>
<?php echo "Description: ".$row_products['product_description']; ?>
<br />
<img src="images/<?php echo $row_products['product_picture']; ?>" width="140" height="140" alt="photo 1" class="left" />
<b>Price:</b> <b>Php <?php echo $row_products['price']; ?></b>
<br />
<?php echo "Product Code: ".$row_products['product_code']; ?>
<br />
<?php echo "Product Type: ".$row_products['product_type']; ?>
<br />
Made by: <a href="index2.php?page_id=<?php echo $row_products['manufacturer']; ?>"><?php echo $row_products['manufacturer']; ?>
<br /><br /><br />
<p class="readmore"><a href="index2.php?page_id=12&product_id=<?php echo $row_products['product_id']; ?>">details</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index2.php?page_id=7&product_type=<?php echo $row_products['product_type']; ?>&action=add&product_id=<?php echo $row_products['product_id']; ?>">add to cart</p></a>
<div class="clear"></div>
</div><!-- end .leftbox -->
<?php if($x==0){ ?>
<div class="clear br"></div> 
<?php 
	  }
	  $i++;
	  } while ($row_products = mysql_fetch_assoc($products)); ?>
</form>
                 
              </div><!-- end .inner -->
          </div>
          <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, 0, $queryString_products); ?>">First</a> | <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, max(0, $pageNum_products - 1), $queryString_products); ?>">Previous</a> | <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, min($totalPages_products, $pageNum_products + 1), $queryString_products); ?>">Next</a> | <a href="<?php printf("%s?pageNum_products=%d%s", $currentPage, $totalPages_products, $queryString_products); ?>">Last</a>
</body>
</html>
<?php
mysql_free_result($products);
mysql_free_result($cart);
?>
