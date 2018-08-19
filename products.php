<?php require_once('Connections/akonsudoy.php'); 
require_once('Connections/akonsudoy.php'); 
require_once('home2.php');

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

  $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($GLOBALS['akonsudoy'], $theValue) : mysqli_escape_string($GLOBALS['akonsudoy'], $theValue);

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

$query_cart = sprintf("SELECT * FROM cart WHERE user_id = %s", GetSQLValueString($colname_cart, "int"));
$cart = mysqli_query($akonsudoy, $query_cart) or die(mysqli_error($akonsudoy));
$row_cart = mysqli_fetch_assoc($cart);
$totalRows_cart = mysqli_num_rows($cart);

$query_check_quantity = "SELECT * FROM cart WHERE user_id = '".$_SESSION['user_id']."' AND product_id = '".$_GET['product_id']."'";
$check_quantity = mysqli_query($akonsudoy, $query_check_quantity) or die(mysqli_error($akonsudoy));
$row_check_quantity = mysqli_fetch_assoc($check_quantity);
$totalRows_check_quantity = mysqli_num_rows($check_quantity);
mysqli_free_result($check_quantity);

if($_GET['product_id'] == $row_check_quantity['product_id']){
if($totalRows_check_quantity ==0){
	$row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
	$insertSQL = sprintf("INSERT INTO cart (user_id, product_id, quantity) VALUES (%s, %s, %s)",
                      $_SESSION['user_id'],
                       $_GET['product_id'],
					   $row_check_quantity['quantity']);
  
  $Result1 = mysqli_query($insertSQL, $akonsudoy) or die(mysqli_error($akonsudoy));
}else{
	$row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
	$updateSQL = sprintf("UPDATE cart SET user_id=%s, product_id=%s, quantity=%s WHERE cart_id=%s",
                       $_SESSION['user_id'],
                       $_GET['product_id'],
                       $row_check_quantity['quantity'],
                       $row_check_quantity['cart_id']);

  $Result2 = mysqli_query($akonsudoy, $updateSQL) or die(mysqli_error($akonsudoy));
}
}else{
if($totalRows_check_quantity ==0){
	$row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
	$insertSQL = sprintf("INSERT INTO cart (user_id, product_id, quantity) VALUES (%s, %s, %s)",
                      $_SESSION['user_id'],
                       $_GET['product_id'],
					   $row_check_quantity['quantity']);
 
  $Result1 = mysqli_query($akonsudoy, $insertSQL) or die(mysqli_error());
}else{
	$row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
	$updateSQL = sprintf("UPDATE cart SET user_id=%s, product_id=%s, quantity=%s WHERE cart_id=%s",
                       $_SESSION['user_id'],
                       $_GET['product_id'],
                       $row_check_quantity['quantity'],
                       $row_check_quantity['cart_id']);

  $Result2 = mysqli_query($akonsudoy, $updateSQL) or die(mysqli_error($akonsudoy));
}	
}
}
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($GLOBALS['akonsudoy'], $theValue) : mysqli_escape_string($GLOBALS['akonsudoy'], $theValue);

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

$query_products = "SELECT * FROM products WHERE product_type = 'handicrafts'";
$query_limit_products = sprintf("%s LIMIT %d, %d", $query_products, $startRow_products, $maxRows_products);
$products = mysqli_query($akonsudoy, $query_limit_products) or die(mysqli_error($akonsudoy));
$row_products = mysqli_fetch_assoc($products);

if (isset($_GET['totalRows_products'])) {
  $totalRows_products = $_GET['totalRows_products'];
} else {
  $all_products = mysqli_query($akonsudoy, $query_products);
  $totalRows_products = mysqli_num_rows($all_products);
}
$totalPages_products = ceil($totalRows_products/$maxRows_products)-1;

$maxRows_products2 = 10;
$pageNum_products2 = 0;
if (isset($_GET['pageNum_products2'])) {
  $pageNum_products2 = $_GET['pageNum_products2'];
}
$startRow_products2 = $pageNum_products2 * $maxRows_products2;

$query_products2 = "SELECT * FROM products WHERE product_type = 'furniture'";
$query_limit_products2 = sprintf("%s LIMIT %d, %d", $query_products2, $startRow_products2, $maxRows_products2);
$products2 = mysqli_query($akonsudoy, $query_limit_products2) or die(mysqli_error($akonsudoy));
$row_products2 = mysqli_fetch_assoc($products2);

if (isset($_GET['totalRows_products2'])) {
  $totalRows_products2 = $_GET['totalRows_products2'];
} else {
  $all_products2 = mysqli_query($akonsudoy, $query_products2);
  $totalRows_products2 = mysqli_num_rows($all_products2);
}
$totalPages_products2 = ceil($totalRows_products2/$maxRows_products2)-1;

$maxRows_products3 = 10;
$pageNum_products3 = 0;
if (isset($_GET['pageNum_products3'])) {
  $pageNum_products3 = $_GET['pageNum_products3'];
}
$startRow_products3 = $pageNum_products3 * $maxRows_products3;

$query_products3 = "SELECT * FROM products WHERE product_type = 'home decor'";
$query_limit_products3 = sprintf("%s LIMIT %d, %d", $query_products3, $startRow_products3, $maxRows_products3);
$products3 = mysqli_query($akonsudoy, $query_limit_products3) or die(mysqli_error($akonsudoy));
$row_products3 = mysqli_fetch_assoc($products3);

if (isset($_GET['totalRows_products3'])) {
  $totalRows_products3 = $_GET['totalRows_products3'];
} else {
  $all_products3 = mysqli_query($akonsudoy, $query_products3);
  $totalRows_products3 = mysqli_num_rows($all_products3);
}
$totalPages_products3 = ceil($totalRows_products3/$maxRows_products3)-1;

$maxRows_products4 = 10;
$pageNum_products4 = 0;
if (isset($_GET['pageNum_products4'])) {
  $pageNum_products4 = $_GET['pageNum_products4'];
}
$startRow_products4 = $pageNum_products4 * $maxRows_products4;

$query_products4 = "SELECT * FROM products WHERE product_type = 'delicacies'";
$query_limit_products4 = sprintf("%s LIMIT %d, %d", $query_products4, $startRow_products4, $maxRows_products4);
$products4 = mysqli_query($akonsudoy, $query_limit_products4) or die(mysqli_error());
$row_products4 = mysqli_fetch_assoc($products4);

if (isset($_GET['totalRows_products4'])) {
  $totalRows_products4 = $_GET['totalRows_products4'];
} else {
  $all_products4 = mysqli_query($akonsudoy, $query_products4);
  $totalRows_products4 = mysqli_num_rows($all_products4);
}
$totalPages_products4 = ceil($totalRows_products4/$maxRows_products4)-1;

$query_cart = "SELECT * FROM cart WHERE user_id = '".$_SESSION['user_id']."'";
$cart = mysqli_query($akonsudoy, $query_cart) or die(mysqli_error($akonsudoy));
$row_cart = mysqli_fetch_assoc($cart);
$totalRows_cart = mysqli_num_rows($cart);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>products</title>
</head>

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
} while ($row_cart = mysqli_fetch_assoc($cart));
if($quantity == 0){
	echo "You have no items in your shopping cart";
} else {
	echo "You have ".$quantity." items in your shopping cart";
}
}
?>
</a></p>
<form id="form1" name="form1" method="post" action="">
<img src="images/handicrafts2.png" height="20" width="140"/>
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
<p class="readmore"><a href="index2.php?page_id=12&product_id=<?php echo $row_products['product_id']; ?>">details</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="index2.php?page_id=5&action=add&product_id=<?php echo $row_products['product_id']; ?>">add to cart</a></p>
<?php if($x == 0) {?>
<br />
<p class="readmore"><a href="index2.php?page_id=7&product_type=<?php echo $row_products['product_type']; ?>">view more...</a></p>
<?php  } ?>
<div class="clear"></div>
</div><!-- end .leftbox -->
      <?php 
	  $i++;
	  } while (($row_products = mysqli_fetch_assoc($products)) && ($i < 3)); ?>
      <div class="clear br"></div>
	  <img src="images/furnitures2.png" height="20" width="140"/>
       <?php 
				  $i = 1;
				  do { 
				  $product_type = $row_products2['product_type'];
				  $x = $i%2;
				  if($x==0){
					$class = "rightbox";  
				  }else{
					$class = "leftbox";  
				  }
				  ?>
<div class="<?php echo $class; ?>">
<h3><?php echo "Product Name: ".$row_products2['product_name']; ?></h3>
<?php echo "Description: ".$row_products2['product_description']; ?>
<br />
<img src="images/<?php echo $row_products2['product_picture']; ?>" width="140" height="140" alt="photo 1" class="left" />
<b>Price:</b> <b>Php <?php echo $row_products2['price']; ?></b>
<br />
<?php echo "Product Code: ".$row_products2['product_code']; ?>
<br />
<?php echo "Product Type: ".$row_products2['product_type']; ?>
<br />
Made by: <a href="index2.php?page_id=<?php echo $row_products2['manufacturer']; ?>"><?php echo $row_products2['manufacturer']; ?>
<br /><br /><br />
<p class="readmore"><a href="index2.php?page_id=12&product_id=<?php echo $row_products2['product_id']; ?>">details</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="index2.php?page_id=5&action=add&product_id=<?php echo $row_products2['product_id']; ?>">add to cart</a></p>
<?php if($x == 0) {?>
<br />
<p class="readmore"><a href="index2.php?page_id=7&product_type=<?php echo $row_products2['product_type']; ?>">view more...</a></p>
<?php  } ?>
<div class="clear"></div>
</div><!-- end .leftbox -->
      <?php 
	  $i++;
	  } while (($row_products2 = mysqli_fetch_assoc($products2)) && ($i < 3)); ?>
      <div class="clear br"></div>
	  <img src="images/home2.png" height="20" width="140"/>
       <?php 
				  $i = 1;
				  do { 
				  $product_type = $row_products3['product_type'];
				  $x = $i%2;
				  if($x==0){
					$class = "rightbox";  
				  }else{
					$class = "leftbox";  
				  }
				  ?>
<div class="<?php echo $class; ?>">
<h3><?php echo "Product Name: ".$row_products3['product_name']; ?></h3>
<?php echo "Description: ".$row_products3['product_description']; ?>
<br />
<img src="images/<?php echo $row_products3['product_picture']; ?>" width="140" height="140" alt="photo 1" class="left" />
<b>Price:</b> <b>Php <?php echo $row_products3['price']; ?></b>
<br />
<?php echo "Product Code: ".$row_products3['product_code']; ?>
<br />
<?php echo "Product Type: ".$row_products3['product_type']; ?>
<br />
Made by: <a href="index2.php?page_id=<?php echo $row_products3['manufacturer']; ?>"><?php echo $row_products3['manufacturer']; ?>
<br /><br /><br />
<p class="readmore"><a href="index2.php?page_id=12&product_id=<?php echo $row_products3['product_id']; ?>">details</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index2.php?page_id=5&action=add&product_id=<?php echo $row_products3['product_id']; ?>">add to cart</a></p>
<?php if($x == 0) {?>
<br />
<p class="readmore"><a href="index2.php?page_id=7&product_type=<?php echo $row_products3['product_type']; ?>">view more...</a></p>
<?php  } ?>
<div class="clear"></div>
</div><!-- end .leftbox -->
      <?php 
	  $i++;
	  } while (($row_products3 = mysqli_fetch_assoc($products3)) && ($i < 3)); ?>
      <div class="clear br"></div>
	  <img src="images/delicacies2.png" height="20" width="140"/>
	         <?php 
				  $i = 1;
				  do { 
				  $product_type = $row_products4['product_type'];
				  $x = $i%2;
				  if($x==0){
					$class = "rightbox";  
				  }else{
					$class = "leftbox";  
				  }
				  ?>
<div class="<?php echo $class; ?>">
<h3><?php echo "Product Name: ".$row_products4['product_name']; ?></h3>
<?php echo "Description: ".$row_products4['product_description']; ?>
<br />
<img src="images/<?php echo $row_products4['product_picture']; ?>" width="140" height="140" alt="photo 1" class="left" />
<b>Price:</b> <b>Php <?php echo $row_products4['price']; ?></b>
<br />
<?php echo "Product Code: ".$row_products4['product_code']; ?>
<br />
<?php echo "Product Type: ".$row_products4['product_type']; ?>
<br />
Made by: <a href="index2.php?page_id=<?php echo $row_products4['manufacturer']; ?>"><?php echo $row_products4['manufacturer']; ?>
<br /><br /><br />
<p class="readmore"><a href="index2.php?page_id=12&product_id=<?php echo $row_products4['product_id']; ?>">details</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index2.php?page_id=5&action=add&product_id=<?php echo $row_products4['product_id']; ?>">add to cart</a></p>
<?php if($x == 0) {?>
<br />
<p class="readmore"><a href="index2.php?page_id=7&product_type=<?php echo $row_products4['product_type']; ?>">view more...</a></p>
<?php  } ?>
<div class="clear"></div>
</div><!-- end .leftbox -->
      <?php 
	  $i++;
	  } while (($row_products4 = mysqli_fetch_assoc($products4)) && ($i < 3)); ?>
      <div class="clear br"></div>
</form>
                  
</body>
</html>
<?php
mysqli_free_result($products);
mysqli_free_result($products2);
mysqli_free_result($products3);
mysqli_free_result($cart);
