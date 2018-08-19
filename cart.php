<?php require_once('Connections/akonsudoy.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $deleteSQL = sprintf("DELETE FROM cart WHERE cart_id=%s", $_GET['cart_id']);

    $Result1 = mysqli_query($akonsudoy, $deleteSQL) or die(mysqli_error($akonsudoy));
}
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
$i = 1;
$j = 1;
$x = 1;
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    foreach ($_POST['quantity'] as $quantity) {
        $count[$i] = $quantity;
        $i++;
    }
    foreach ($_POST['cart_id'] as $cart_id) {
        $id[$j] = $cart_id;
        $j++;
    }
    foreach ($_POST['cart_id'] as $cart_id) {
        $updateSQL = sprintf("UPDATE cart SET quantity=%s WHERE cart_id=%s", $count[$x], $id[$x]);

        $Result1 = mysqli_query($akonsudoy, $updateSQL) or die(mysqli_error($akonsudoy));
        $x++;
    }
}

$colname_cart = "-1";
if (isset($_SESSION['user_id'])) {
    $colname_cart = $_SESSION['user_id'];
}
$query_cart = sprintf("SELECT * FROM cart WHERE user_id = %s", GetSQLValueString($colname_cart, "int"));
$cart = mysqli_query($akonsudoy, $query_cart) or die(mysqli_error($akonsudoy));
$row_cart = mysqli_fetch_assoc($cart);
$totalRows_cart = mysqli_num_rows($cart);

if (isset($_GET['action']) && $_GET['action'] == "add") {
    $query_check_quantity = "SELECT * FROM cart WHERE user_id = '" . $_SESSION['user_id'] . "' AND product_id = '" . $_GET['product_id'] . "'";
    $check_quantity = mysqli_query($akonsudoy, $query_check_quantity) or die(mysqli_error($akonsudoy));
    $row_check_quantity = mysqli_fetch_assoc($check_quantity);
    $totalRows_check_quantity = mysql_num_rows($check_quantity);

    if ($_GET['product_id'] == $row_check_quantity['product_id']) {
        if ($totalRows_check_quantity == 0) {
            $row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
            $insertSQL = sprintf("INSERT INTO cart (user_id, product_id, quantity) VALUES (%s, %s, %s)", $_SESSION['user_id'], $_GET['product_id'], $row_check_quantity['quantity']);

            $Result1 = mysqli_query($akonsudoy, $insertSQL) or die(mysqli_error($akonsudoy));
        } else {
            $row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
            $updateSQL = sprintf("UPDATE cart SET user_id=%s, product_id=%s, quantity=%s WHERE cart_id=%s", $_SESSION['user_id'], $_GET['product_id'], $row_check_quantity['quantity'], $row_check_quantity['cart_id']);

            $Result2 = mysqli_query($akonsudoy, $updateSQL) or die(mysqli_error($akonsudoy));
        }
    } else {
        if ($totalRows_check_quantity == 0) {
            $row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
            $insertSQL = sprintf("INSERT INTO cart (user_id, product_id, quantity) VALUES (%s, %s, %s)", $_SESSION['user_id'], $_GET['product_id'], $row_check_quantity['quantity']);


            $Result1 = mysqli_query($akonsudoy, $insertSQ) or die(mysqli_error($akonsudoy));
        } else {
            $row_check_quantity['quantity'] = $row_check_quantity['quantity'] + 1;
            $updateSQL = sprintf("UPDATE cart SET user_id=%s, product_id=%s, quantity=%s WHERE cart_id=%s", $_SESSION['user_id'], $_GET['product_id'], $row_check_quantity['quantity'], $row_check_quantity['cart_id']);

            $Result2 = mysqli_query($akonsudoy, $updateSQL) or die(mysqli_error($akonsudoy));
        }
    }
}
?><style type="text/css">
    <!--
    body,td,th {
        font-size: 12px;
    }
    .style1 {color: #FF0000}
    -->
</style>

<div align="left"><img src="images/cart.png" width="280" height="40" /></div>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <div align="center"><strong>Note: The manufacturer will send to your e-mail address a notification of your orders including the surcharge of the products you purchased!</strong> <br />
<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == 0 && $totalRows_cart > 0) {
    echo "Please register first in order to buy the products of your choice.";
}
?>
        <p>
<?php if ($totalRows_cart > 0) { // Show if recordset not empty ?>
            </p>
            <p>&nbsp; </p>
            <table border="0" cellpadding="2" cellspacing="2">
                <tr>
                    <td><div align="center">          product name        </div></td>
                    <td><div align="center">          product code        </div></td>
                    <td><div align="center">          unit price        </div></td>
                    <td><div align="center">          quantity        </div></td>
                    <td><div align="center">          total        </div></td>
                    <td><div align="center">          actions        </div></td>
                </tr>
            <?php
            $grand_total = 0;
            $id = array();
            $name = array();
            $code = array();
            $price = array();
            $quantity = array();
            $total2 = array();
            $i = 0;
            do {

                $query_products_incart = "SELECT * FROM products WHERE product_id='" . $row_cart['product_id'] . "'";
                $products_incart = mysqli_query($akonsudoy, $query_products_incart) or die(mysqli_error($akonsudoy));
                $row_products_incart = mysqli_fetch_assoc($products_incart);
                $totalRows_products_incart = mysqli_num_rows($products_incart);
                $id[$i] = $row_products_incart['product_id'];
                ?>
                    <tr>
                        <td><?php echo $name[$i] = $row_products_incart['product_name']; ?></td>
                        <td><?php echo $code[$i] = $row_products_incart['product_code']; ?></td>
                        <td><?php echo $price[$i] = $row_products_incart['price']; ?></td>
                        <td><label>
                                Php 
                                <input name="quantity[]" type="text" id="textfield" value="<?php echo $quantity[$i] = $row_cart['quantity']; ?>" />
                                <input type="hidden" name="cart_id[]" id="hiddenField" value="<?php echo $row_cart['cart_id']; ?>"/>
                            </label>        </td>
                        <td><?php
                    $total = $row_cart['quantity'] * $row_products_incart['price'];
                    echo $total2[$i] = $total;
                    ?>        </td>
                        <td><a href="index2.php?page_id=6&action=delete&cart_id=<?php echo $row_cart['cart_id']; ?>">remove</a></td>
                    </tr>
                    <?php
                    $grand_total += $total;
                    $_POST['count'] = $i++;
                } while ($row_cart = mysqli_fetch_assoc($cart));
                mysqli_free_result($cart);
                mysqli_free_result($products_incart);
                ?>
            </table>
        </div>
        <br />
        <div align="center">
            <input type="submit" name="button" id="button" value="Update Cart" />
            <label>
                        <?php if (isset($_SESSION['MM_Username'])) { ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="send" type="submit" id="send" value="Send request to buy" />
                        <?php } ?>
            </label>
        </div>
    </label>
    <div align="center">
        <input type="hidden" name="MM_update" value="form1" />
    </div>
    </form>
    <div align="center">
                <?php
                echo "Grand Total: Php " . $grand_total;
                ?>
<?php
} // Show if recordset not empty 
else {
    echo "You have no items in your shopping cart.";
}
?>
    </br></div>
<?php
$colname_message = "-1";
if (isset($_SESSION['user_id'])) {
    $colname_message = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}

$query_message = sprintf("SELECT * FROM users WHERE user_id = %s", $colname_message);
$message = mysqli_query($akonsudoy, $query_message) or die(mysqli_error($akonsudoy));
$row_message = mysqli_fetch_assoc($message);
$totalRows_message = mysqli_num_rows($message);

//send request
if (function_exists('nukeMagicQuotes')) {
    nukeMagicQuotes();
}
// process the email
if (array_key_exists('send', $_POST)) {
    $to = "admin@samarproducts.co.cc"; // use your admin email address
    $subject = 'Request to buy products from samarproducts.co.cc site';

    // process the $_SESSION variables
    //$email = $_SESSION['MM_Username'];
    // build the message
    $count_down = $_POST['count'];
    $i = 0;
    $message = $row_message['fullname'] . " requested to buy the following:
product name          product code         unit price         quantity          total
";
    do {
  
        $query_total = sprintf("SELECT * FROM products WHERE product_id = '" . $id[$i] . "'");
        $total = mysqli_query($akonsudoy, $query_total) or die(mysqli_error($akonsudoy));
        $row_total = mysqli_fetch_assoc($total);
        $totalRows_total = mysqli_num_rows($total);


        $message = $message . $name[$i] . "          " . $code[$i] . "       Php " . $price[$i] . "          " . $quantity[$i] . "       Php " . $total2[$i] . "
";
        $i++;
        $count_down--;
    } while ($count_down >= 0);

    $message = $message . "Grand Total: Php " . $grand_total . "
Email address: " . $_SESSION['MM_Username'] . "
Contact number: " . $row_message['cnumber'] . "
Address: " . $row_message['address'];
    // limit line length to 70 characters
    $message = wordwrap($message, 1500);

    // send it  
    $mailSent = mail($to, $subject, $message);
    //mysql_free_result($message);

    mysqli_free_result($total);
}
//mysql_free_result($check_quantity);
//mysql_free_result($message);
?>
<div align="center">
    <span class="style3">
<?php
if (isset($_POST['send']) && !$mailSent) {
    ?>
        </span></div>
    <span class="style3">
    </label>
    </span>
    <p align="center" class="warning style1">Sorry, there was a problem sending your message. Please try later.</p>
    <p align="center" class="warning style1">Possible reason: your host may have disabled the mail() function...</p>
    <div align="center" class="style3">
    <?php
} elseif (isset($_POST['send']) && $mailSent) {
    ?>
    </div>
    <p align="center" class="style4"><strong>Your request has been sent to your email.</strong></p>
    <div align="center" class="style3">
<?php } ?>
</div>