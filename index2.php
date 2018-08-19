<?php require_once('Connections/akonsudoy.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
    session_start();
}
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
    //to fully log out a visitor we need to clear the session varialbles
    $_SESSION['MM_Username'] = NULL;
    $_SESSION['user_id'] = NULL;
    $_SESSION['MM_UserGroup'] = NULL;
    $_SESSION['PrevUrl'] = NULL;
    $_SESSION['access_level'] = NULL;
    unset($_SESSION['MM_Username']);
    unset($_SESSION['user_id']);
    unset($_SESSION['access_level']);
    unset($_SESSION['MM_UserGroup']);
    unset($_SESSION['PrevUrl']);

    header("Location: index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Samar Products</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="wrapper">
            <div id="inner">
                <div id="header">
                    <h1><img src="images/logo.gif" width="519" height="63" alt="Samar Products" /></h1>
                    <div id="nav">
                        <a href="index2.php">home</a> | <a href="index2.php?page_id=6">view cart</a> | <a href="index2.php?page_id=5">products</a> | <a href="index2.php?page_id=11">service list</a> 
                        <?php
                        if (isset($_SESSION['access_level']) && $_SESSION['access_level'] == 0) {
                            ?>
                            | <a href="index2.php?page_id=8">add product</a> | <a href="index2.php?page_id=9">edit product</a>
                            <?php
                        }
                        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0) {
                            ?>
                            | <a href="<?php echo $logoutAction ?>">logout</a>
                            <?php
                        }
                        ?>
                    </div>
                    <!-- end nav -->
                    <img src="images/header_1.jpg" width="896" height="145" alt="" />
                    <img src="images/header_2.jpg" width="896" height="48" alt="" />
                </div><!-- end header -->
                <dl id="browse">
                    <dt>Full Product Type Lists</dt>
                    <dd class="first"><a href="index2.php?page_id=7&product_type=handicrafts">Handicrafts</a></dd>
                    <dd><a href="index2.php?page_id=7&product_type=furniture">Furniture</a></dd>
                    <dd><a href="index2.php?page_id=7&product_type=home decor">Home Decor</a></dd>
                    <dd><a href="index2.php?page_id=7&product_type=delicacies">Delicacies</a></dd>
                    <dt>Members Login</dt>
                    <dd class="searchform">
                        <?php
                        if (!(isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0) && !isset($_GET['action'])) {
                            include("user_login.php");
                        } elseif (!isset($_SESSION['user_id']) && isset($_GET['action']) && $_GET['action'] == "login") {
                            echo "<div align=\"center\">ERROR: username and password did not match!</div>";
                            include("user_login.php");
                        } elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 0) {
                            include("user_login.php");
                        } else {
                            $colname_fullname = "-1";
                            if (isset($_SESSION['MM_Username'])) {
                                $colname_fullname = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
                            }

                            $query_fullname = sprintf("SELECT fullname FROM users WHERE email = '%s'", $colname_fullname);
                            $fullname = mysqli_query($akonsudoy, $query_fullname) or die(mysqli_error($akonsudoy));
                            $row_fullname = mysqli_fetch_assoc($fullname);
                            $totalRows_fullname = mysqli_num_rows($fullname);
                            mysqli_free_result($fullname);
                            echo "<div align=\"center\">Good day! " . $row_fullname['fullname'] . ". <a href=\"index2.php?page_id=3\">your profile</a>";
                            echo "<a href=\"index2.php?page_id=4\">change password</a></div>";
                        }
                        ?>
                        <div align="center"><dt>CONTACT US</dt>
                            <br />
                            DTI Office
                            <br />
                            Samar Provincial Office
                            <br />
                            Catbalogan,City
                            <br />
                            Tel./fax No. (055) 251-2196
                            <br />
                            Email: dtisamar@yahoo.com
                            <br />
                            <br />
                        </div>
                    </dd>
                </dl>
                <div id="body">
                    <div class="inner">
                        <div id="content_main">
<?php
if (!isset($_GET['page_id'])) {
    include("web_description.php");
} elseif ($_GET['page_id'] == 1) {
    include("lost_password.php");
} elseif ($_GET['page_id'] == 2) {
    include("registration_form.php");
} elseif ($_GET['page_id'] == 3) {
    include("update_profile.php");
} elseif ($_GET['page_id'] == 4) {
    include("change_password.php");
} elseif ($_GET['page_id'] == 5) {
    include("products.php");
} elseif ($_GET['page_id'] == 6) {
    include("cart.php");
} elseif ($_GET['page_id'] == 7) {
    include("products_categorized.php");
} elseif ($_GET['page_id'] == 8) {
    include("add_products.php");
} elseif ($_GET['page_id'] == 9) {
    include("admin_product.php");
} elseif ($_GET['page_id'] == 10) {
    include("update_product.php");
} elseif ($_GET['page_id'] == 11) {
    include("service_list.php");
} elseif ($_GET['page_id'] == 12) {
    include("product_details.php");
} elseif ($_GET['page_id'] == 13) {
    include("add_manufacturer.php");
} elseif ($_GET['page_id'] == 14) {
    include("update_manufacturer.php");
} elseif ($_GET['page_id'] == 15) {
    include("manufacturer_list.php");
} elseif ($_GET['page_id'] == 16) {
    include("copmplete_registration.php");
} elseif (isset($_GET['page_id'])) {
    include("" . $_GET['page_id'] . ".php");
}
?>
                        </div>
                    </div>
                </div>
                <!-- end body -->

                <div class="clear"></div>
                <div id="footer">
                    Created by <a href="#">Jundy Cosmod</a>

                </div><!-- end footer -->
            </div><!-- end inner -->
        </div><!-- end wrapper -->
    </body>
</html>
<?php
//mysql_free_result($fullname);
?>
