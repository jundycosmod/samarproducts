<?php require_once('Connections/akonsudoy.php'); ?>
<?php

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
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

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
    $deleteSQL = sprintf("DELETE FROM manufacturers WHERE id=%s", GetSQLValueString($_GET['id'], "int"));

    $Result1 = mysqli_query($akonsudoy, $deleteSQL) or die(mysqli_error($akonsudoy));
}

$query_manufacturer = "SELECT * FROM manufacturers";
$manufacturer = mysqli_query($akonsudoy, $query_manufacturer) or die(mysqli_error($akonsudoy));
$row_manufacturer = mysqli_fetch_assoc($manufacturer);
$totalRows_manufacturer = mysqli_num_rows($manufacturer);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Untitled Document</title>
    </head>

    <body>
        <table border="1" align="center">
            <tr>
                <td>owner</td>
                <td>business_name</td>
                <td colspan="2"><div align="center">Actions</div></td>
            </tr>
            <?php do { ?>
                <tr>
                    <td><?php echo $row_manufacturer['owner']; ?></td>
                    <td><?php echo $row_manufacturer['business_name']; ?></td>
                    <td><a href="index2.php?page_id=14&id=<?php echo $row_manufacturer['id']; ?>">Update</a></td>
                    <td><a href="index2.php?page_id=15&id=<?php echo $row_manufacturer['id']; ?>">Remove</a></td>
                </tr>
            <?php } while ($row_manufacturer = mysqli_fetch_assoc($manufacturer)); ?>
        </table>
    </body>
</html>
<?php
mysqli_free_result($manufacturer);
