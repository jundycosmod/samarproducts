<?php require_once('Connections/akonsudoy.php'); ?>
<?php
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
$colname_update_manufacturer = "-1";
if (isset($_GET['id'])) {
  $colname_update_manufacturer = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

$query_update_manufacturer = sprintf("SELECT * FROM manufacturers WHERE id = %s", $colname_update_manufacturer);
$update_manufacturer = mysqli_query($akonsudoy, $query_update_manufacturer) or die(mysqli_error($akonsudoy));
$row_update_manufacturer = mysqli_fetch_assoc($update_manufacturer);
$totalRows_update_manufacturer = mysqli_num_rows($update_manufacturer);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//upload pictures **************
// Where the file is going to be placed 
$target_path = "images/";

/* Add the original filename to our target path.  
Result is "images/filename.extension" */
$target_path = $target_path . basename( $_FILES['logo']['name']); 

if(move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
	$product_picture =  basename( $_FILES['logo']['name']);
    echo "The file ".  basename( $_FILES['logo']['name']). " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}	
// Where the file is going to be placed 
$target_path = "images/";

/* Add the original filename to our target path.  
Result is "images/filename.extension" */
$target_path = $target_path . basename( $_FILES['location']['name']); 

if(move_uploaded_file($_FILES['location']['tmp_name'], $target_path)) {
	$product_picture =  basename( $_FILES['location']['name']);
    echo "The file ".  basename( $_FILES['location']['name']). " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}	

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
if(!isset($_FILES['logo'])){
	$product_picture = $row_update_manufacturer['location'];
}else{
	$product_picture = $_FILES['location'];
}
if(!isset($_FILES['location'])){
	$product_picture2 = $row_update_manufacturer['location'];
}else{
	$product_picture2 = $_FILES['location'];
}
}
  $updateSQL = sprintf("UPDATE manufacturers SET description=%s, logo=%s, location=%s, owner=%s, business_name=%s, address=%s, zip_code=%s, phone=%s, cellphone=%s, email=%s WHERE id=%s",
                       GetSQLValueString($_POST['description'], "text"),
					   GetSQLValueString($product_picture, "text"),
					   GetSQLValueString($product_picture2, "text"),
                       GetSQLValueString($_POST['owner'], "text"),
                       GetSQLValueString($_POST['business_name'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['zip_code'], "int"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['cellphone'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  $Result1 = mysqli_query($akonsudoy, $updateSQL) or die(mysqli_error($akonsudoy));
}
$colname_update_manufacturer = "-1";
if (isset($_GET['id'])) {
  $colname_update_manufacturer = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

$query_update_manufacturer = sprintf("SELECT * FROM manufacturers WHERE id = %s", $colname_update_manufacturer);
$update_manufacturer = mysqli_query($akonsudoy, $query_update_manufacturer) or die(mysqli_error($akonsudoy));
$row_update_manufacturer = mysqli_fetch_assoc($update_manufacturer);
$totalRows_update_manufacturer = mysqli_num_rows($update_manufacturer);
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
<form id="form1" name="form1" method="POST" enctype="multipart/form-data" action="<?php echo $editFormAction; ?>">
  <input type="hidden" name="id" value="<?php echo $row_update_manufacturer['id']; ?>"/>
  <table width="auto" border="0" align="center">
    <tr>
      <td><div align="right">Business Name:</div></td>
      <td><label>
        <input name="business_name" type="text" id="business_name" value="<?php echo $row_update_manufacturer['business_name']; ?>"/>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Description:</div></td>
      <td><label>
        <textarea name="description" id="description"><?php
echo htmlentities($row_update_manufacturer['description']);
 ?></textarea>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Address:</div></td>
      <td><label>
        <input name="address" type="text" id="address" value="<?php echo $row_update_manufacturer['address']; ?>"/>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Zip code:</div></td>
      <td><label>
        <input name="zip_code" type="text" id="zip_code" value="<?php echo $row_update_manufacturer['zip_code']; ?>"/>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Owner:</div></td>
      <td><label>
        <input name="owner" type="text" id="owner" value="<?php echo $row_update_manufacturer['owner']; ?>"/>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Phone Number </div></td>
      <td><label>
        <input name="phone" type="text" id="phone" value="<?php echo $row_update_manufacturer['phone']; ?>"/>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Cellphone Number:</div></td>
      <td><label>
        <input name="cellphone" type="text" id="cellphone" value="<?php echo $row_update_manufacturer['cellphone']; ?>"/>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">Email:</div></td>
      <td><label>
        <input name="email" type="text" id="email" value="<?php echo $row_update_manufacturer['email']; ?>"/>
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	    <p><img src="images/<?php echo $row_update_manufacturer['logo']; ?>" width="30" height="30" /></p>	    </td>
    </tr>
    <tr>
      <td><div align="right">Logo of Business: </div></td>
      <td><input name="logo" type="file" id="logo" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	    <p><img src="images/<?php echo $row_update_manufacturer['location']; ?>" width="30" height="30" /></p>
	    </td>
    </tr>
    <tr>
      <td><div align="right">Location:</div></td>
      <td><input name="location" type="file" id="location" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="center">
        <label>
          
          <div align="center">
            <input type="submit" name="Submit" value="Submit" />
          </div>
        </label>
      </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>
</html>
<?php
mysqli_free_result($update_manufacturer);
