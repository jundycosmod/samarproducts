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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
unset($missing);
// list expected fields
$expected = array('product_name', 'product_code', 'product_description', 'product_type', 'price', 'uploadedfile');
// set required fields
$required = array('product_name', 'product_code', 'product_description', 'product_type', 'price', 'uploadedfile');
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
if (isset($_POST['product_code'])) {
    $colname_Recordset1 = $_POST['product_code'];
}

$query_Recordset1 = sprintf("SELECT * FROM products WHERE product_code = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysqli_query($akonsudoy, $query_Recordset1) or die(mysqli_error($akonsudoy));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && (empty($missing)) && ($totalRows_Recordset1 < 1)) {
//upload pictures **************
// Where the file is going to be placed 
    $target_path = "images/";

    /* Add the original filename to our target path.  
      Result is "images/filename.extension" */
    $target_path = $target_path . basename($_FILES['uploadedfile']['name']);

    if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
        $product_picture = basename($_FILES['uploadedfile']['name']);
        // echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
        " has been uploaded";
    } else {
        //echo "There was an error uploading the file, please try again!";
    }

    $insertSQL = sprintf("INSERT INTO products (product_code, product_name, product_description, product_type, manufacturer, price, product_picture) VALUES (%s, %s, %s, %s, %s, %s, %s)", GetSQLValueString($_POST['product_code'], "text"), GetSQLValueString($_POST['product_name'], "text"), GetSQLValueString($_POST['product_description'], "text"), GetSQLValueString($_POST['product_type'], "text"), GetSQLValueString($_POST['manufacturer'], "text"), GetSQLValueString($_POST['price'], "int"), GetSQLValueString($product_picture, "text"));

    $Result1 = mysqli_query($akonsudoy, $insertSQL) or die(mysqli_error($akonsudoy));

    $insertGoTo = "add_products.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    //header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Add Product</title>
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
            <img src="images/add_products.png" width="280" height="40" />
<?php
if ($_POST && empty($missing)) {
    ?>
                <p align="center">Product Successfully Added!</p>
                <?php
            } elseif ($_POST && isset($missing)) {
                ?>
                <p align="center" class="style1">ERROR: Failed to add product!</p>
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
                            <input type="text" name="product_name" id="product_name" <?php
            if (!empty($missing) || (isset($totalRows_Recordset1) && $totalRows_Recordset1 != 0)) {
                echo 'value="' . htmlentities($_POST['product_name']) . '"';
            }
            ?>/>
                        </label></td>
                </tr>
                <tr>
                    <td>Product Code:</td>
                    <td><label>
                            <input type="text" name="product_code" id="product_code" <?php
            if (!empty($missing) || (isset($totalRows_Recordset1) && $totalRows_Recordset1 != 0)) {
                echo 'value="' . htmlentities($_POST['product_code']) . '"';
            }
            ?>/>
                        </label></td>
                </tr>
                <tr>
                    <td>Product Description:</td>
                    <td><label>
                            <textarea name="product_description" rows="3" id="product_description" >
                                <?php
                                if (!empty($missing) || (isset($totalRows_Recordset1) && $totalRows_Recordset1 != 0)) {
                                    echo htmlentities($_POST['product_description']);
                                }
                                ?>
                            </textarea>
                        </label></td>
                </tr>
                <tr>
                    <td>Product Type:</td>
                    <td><label>
                            <select name="product_type" id="product_type">
                                <option value="handicrafts" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['product_type'] == "handicrafts") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Handicrafts</option>
                                <option value="furniture" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['product_type'] == "furniture") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Furniture</option>
                                <option value="home decor" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['product_type'] == "home decor") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Home Decor</option>
                                <option value="delicacies" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['product_type'] == "delicacies") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Delicacies</option>
                            </select>
                        </label></td>
                </tr>
                <tr>
                    <td>Manufacturer:</td>
                    <td><label>
                            <select name="manufacturer" id="manufacturer">
                                <option value="Abon Antique Furniture" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Abon Antique Furniture") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Abon Antique Furniture</option>
                                <option value="Aldkianne Home Decor" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Aldkianne Home Decor") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Aldkianne Home Decor</option>
                                <option value="Charitos Delights" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Charitos Delights") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Charitos Delights</option>
                                <option value="D and E Mats and Rattan Crafts" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "D and E Mats and Rattan Crafts") || isset($error_msg)) { ?> selected="selected" <?php } ?>>D and E Mats and Rattan Crafts</option>
                                <option value="Delzas Native Products" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Delzas Native Products") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Delzas Native Products</option>
                                <option value="Gina Colinayos Cakes and Pastries" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Gina Colinayos Cakes and Pastries") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Gina Colinayos Cakes and Pastries</option>
                                <option value="Ludys Sweet Products" <?php if (isset($_POST["MM_insert"]) && (isset($missing) && $_POST['manufacturer'] == "Ludys Sweet Products") || isset($error_msg)) { ?> selected="selected" <?php } ?>>Ludys Sweet Products</option>
                            </select>
                        </label></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><label>
                            <input type="text" name="price" id="price" <?php
                            if (!empty($missing) || (isset($totalRows_Recordset1) && $totalRows_Recordset1 != 0)) {
                                echo 'value="' . htmlentities($_POST['price']) . '"';
                            }
                            ?>/>
                        </label></td>
                </tr>
                <tr>
                    <td>Picture:</td>
                    <td>
                        <input name="uploadedfile" type="file" />	  </td>
                </tr>
                <tr>
                    <td colspan="2"><div align="center">
                            <label>
                                <input type="submit" name="button" id="button" value="add product" />
                            </label>
                        </div></td>
                </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form1" />
        </form>
    </body>
</html>
<?php
mysqli_free_result($Recordset1);
