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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?"))
        $MM_qsChar = "&";
    if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
        $MM_referrer .= "?" . $QUERY_STRING;
    $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: " . $MM_restrictGoTo);
    exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
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
$colname_Recordset1 = "-1";
if (isset($_GET['product_id'])) {
    $colname_Recordset1 = $_GET['product_id'];
}

$query_Recordset1 = sprintf("SELECT * FROM products WHERE product_id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysqli_query($akonsudoy, $query_Recordset1) or die(mysqli_error($akonsudoy));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    if (!isset($_FILES['uploadfile'])) {
        $product_picture = $row_Recordset1['product_picture'];
    } else {
        $product_picture = $_FILES['uploadfile'];
    }
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//upload pictures **************
// Where the file is going to be placed 
    $target_path = "images/";

    /* Add the original filename to our target path.  
      Result is "images/filename.extension" */
    $target_path = $target_path . basename($_FILES['uploadedfile']['name']);

    if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
        $product_picture = basename($_FILES['uploadedfile']['name']);
        //echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
        " has been uploaded";
    } else {
        //echo "There was an error uploading the file, please try again!";
    }

    $updateSQL = sprintf("UPDATE products SET product_code=%s, product_name=%s, product_description=%s, product_type=%s, manufacturer=%s, price=%s, product_picture=%s WHERE product_id=%s", GetSQLValueString($_POST['product_code'], "text"), GetSQLValueString($_POST['product_name'], "text"), GetSQLValueString($_POST['product_description'], "text"), GetSQLValueString($_POST['product_type'], "text"), GetSQLValueString($_POST['manufacturer'], "text"), GetSQLValueString($_POST['price'], "double"), GetSQLValueString($product_picture, "text"), GetSQLValueString($_POST['product_id'], "int"));

    $Result1 = mysqli_query($akonsudoy, $updateSQL) or die(mysqli_error($akonsudoy));
}

unset($missing);
// list expected fields
$expected = array('product_name', 'product_code', 'product_description', 'product_type', 'price');
// set required fields
$required = array('product_name', 'product_code', 'product_description', 'product_type', 'price');
// create empty array for any missing fields
$missing = array();
// process the $_POST variables
foreach ($_POST as $key => $value) {
// assign to temporary variable and strip whitespace if not an array
    $temp = is_array($value) ? $value : trim($value);
// if empty and required, add to $missing array
    if (empty($temp) && in_array($key, $required)) {
        array_push($missing, $key);
    }
// otherwise, assign to a variable of the same name as $key
    elseif (in_array($key, $expected)) {
        ${$key} = $temp;
    }
}
$colname_Recordset1 = "-1";
if (isset($_GET['product_id'])) {
    $colname_Recordset1 = $_GET['product_id'];
}

$query_Recordset1 = sprintf("SELECT * FROM products WHERE product_id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysqli_query($akonsudoy, $query_Recordset1) or die(mysqli_error($akonsudoy));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
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
        <form id="form1" name="form1" method="POST" enctype="multipart/form-data" action="<?php echo $editFormAction; ?>">
            <img src="images/update_product.png" width="280" height="40" />
            <?php
            if ($_POST && empty($missing) && (isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
                ?>
                <p align="center">Product Successfully Updated!</p>
                <?php
            } elseif ($_POST && !empty($missing)) {
                ?>
                <p align="center" class="style1">ERROR: Failed to update product!</p>
                <p>
                    <?php
                }
                ?>
            </p>
            <p>&nbsp; </p>
            <table width="200" border="0" align="center">
                <tr>
                    <td>Product Name:</td>
                    <td><label>
                            <input type="text" name="product_name" id="product_name" value="<?php echo $row_Recordset1['product_name']; ?>" />
                            <input type="hidden" name="product_id" id="hiddenField" value="<?php echo $row_Recordset1['product_id']; ?>" />
                        </label></td>
                </tr>
                <tr>
                    <td>Product Code:</td>
                    <td><label>
                            <input type="text" name="product_code" id="product_code" value="<?php echo $row_Recordset1['product_code']; ?>"/>
                        </label></td>
                </tr>
                <tr>
                    <td>Product Description:</td>
                    <td><label>
                            <textarea name="product_description" rows="3" id="product_description" >
                                <?php
                                echo htmlentities($row_Recordset1['product_description']);
                                ?>
                            </textarea>
                        </label></td>
                </tr>
                <tr>
                    <td>Product Type:</td>
                    <td><label>
                            <select name="product_type" id="product_type">
                                <option value="handicrafts" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['product_type'] == "handicrafts") || isset($error_msg) || $row_Recordset1['product_type'] == "handicrafts") { ?> selected="selected" <?php } ?>>Handicrafts</option>
                                <option value="furniture" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['product_type'] == "furniture") || isset($error_msg) || $row_Recordset1['product_type'] == "furniture") { ?> selected="selected" <?php } ?>>Furniture</option>
                                <option value="home decor" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['product_type'] == "home decor") || isset($error_msg) || $row_Recordset1['product_type'] == "home decor") { ?> selected="selected" <?php } ?>>Home Decor</option>
                                <option value="delicacies" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['product_type'] == "delicacies") || isset($error_msg) || $row_Recordset1['product_type'] == "delicacies") { ?> selected="selected" <?php } ?>>Delicacies</option>
                            </select>
                        </label></td>
                </tr>
                <tr>
                    <td>Manufacturer:</td>
                    <td><label>
                            <select name="manufacturer" id="manufacturer">
                                <option value="Abon Antique Furniture" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Abon Antique Furniture") || isset($error_msg) || $row_Recordset1['manufacturer'] == "Abon Antique Furniture") { ?> selected="selected" <?php } ?>>Abon Antique Furniture</option>
                                <option value="Aldkianne Home Decor" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Aldkianne Home Decor") || isset($error_msg) || $row_Recordset1['manufacturer'] == "Aldkianne Home Decor") { ?> selected="selected" <?php } ?>>Aldkianne Home Decor</option>
                                <option value="Charitos Delights" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Charitos Delights") || isset($error_msg) || $row_Recordset1['manufacturer'] == "Charitos Delights") { ?> selected="selected" <?php } ?>>Charitos Delights</option>
                                <option value="D and E Mats and Rattan Crafts" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "D and E Mats and Rattan Crafts") || isset($error_msg) || $row_Recordset1['manufacturer'] == "D and E Mats and Rattan Crafts") { ?> selected="selected" <?php } ?>>D and E Mats and Rattan Crafts</option>
                                <option value="Delzas Native Products" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Delzas Native Products") || isset($error_msg) || $row_Recordset1['manufacturer'] == "Delzas Native Products") { ?> selected="selected" <?php } ?>>Delzas Native Products</option>
                                <option value="Gina Colinayos Cakes and Pastries" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Gina Colinayos Cakes and Pastries") || isset($error_msg) || $row_Recordset1['manufacturer'] == "Gina Colinayos Cakes and Pastries") { ?> selected="selected" <?php } ?>>Gina Colinayos Cakes and Pastries</option>
                                <option value="Ludys Sweet Products" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Ludys Sweet Products") || isset($error_msg) || $row_Recordset1['manufacturer'] == "Ludys Sweet Products") { ?> selected="selected" <?php } ?>>Ludys Sweet Products</option>
                            </select>
                        </label></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><label>
                            <input type="text" name="price" id="price" value="<?php echo $row_Recordset1['price']; ?>"/>
                        </label></td>
                </tr>
                <tr>
                    <td>Picture:</td>
                    <td>
                        <img src="images/<?php echo $row_Recordset1['product_picture']; ?>" width="30" height="30" />
                        <input name="uploadedfile" type="file" />

                    </td>
                </tr>
                <tr>
                    <td colspan="2"><div align="center">
                            <label>
                                <input type="submit" name="button" id="button" value="update product" />
                            </label>
                        </div></td>
                </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1" />
        </form>
    </body>
</html>
<?php
mysqli_free_result($Recordset1);
