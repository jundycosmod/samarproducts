<?php
require_once('Connections/akonsudoy.php');
$colname_product_details = "-1";
if (isset($_GET['product_id'])) {
    $colname_product_details = (get_magic_quotes_gpc()) ? $_GET['product_id'] : addslashes($_GET['product_id']);
}
$query_product_details = sprintf("SELECT * FROM products WHERE product_id = %s", $colname_product_details);
$product_details = mysqli_query($akonsudoy, $query_product_details) or die(mysqli_error($akonsudoy));
$row_product_details = mysqli_fetch_assoc($product_details);
$totalRows_product_details = mysqli_num_rows($product_details);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Untitled Document</title>
    </head>

    <body>
        <div id="body">
            <div class="inner">
                <p><img src="images/details.png" width="280" height="40" /></p>
                <form id="form1" name="form1" method="post" action="">
                    <?php
                    $i = 1;
                    do {
                        $x = $i % 2;
                        if ($x == 0) {
                            $class = "rightbox";
                        } else {
                            $class = "leftbox";
                        }
                        ?>
                        <div class="<?php echo $class; ?>">
                            <img src="images/<?php echo $row_product_details['product_picture']; ?>" width="350" height="350" alt="photo 1" class="left" />
                            <h3><?php echo "Product Name: " . $row_product_details['product_name']; ?></h3>
                            <?php echo "Description: " . $row_product_details['product_description']; ?>
                            <br />
                            <b>Price:</b> <b>Php <?php echo $row_product_details['price']; ?></b>
                            <br />
                            <?php echo "Product Code: " . $row_product_details['product_code']; ?>
                            <br />
                            <?php echo "Product Type: " . $row_product_details['product_type']; ?>
                            <br />
                            Made by: <a href="index2.php?page_id=<?php echo $row_product_details['manufacturer']; ?>"><?php echo $row_product_details['manufacturer']; ?>
                                <br /><br /><br />
                                <p class="readmore"><a href="index2.php?page_id=7&product_id=<?php echo $row_product_details['product_id']; ?>&product_type=<?php echo $row_product_details['product_type']; ?>&action=add">add to cart</p></a>
                            <div class="clear"></div>
                        </div><!-- end .leftbox -->
                        <?php if ($x == 0) { ?>
                            <div class="clear br"></div> 
                            <?php
                        }
                        $i++;
                    } while ($row_product_details = mysqli_fetch_assoc($product_details));
                    ?>
                </form>

            </div><!-- end .inner -->
        </div>
    </body>
</html>
<?php
mysqli_free_result($product_details);
