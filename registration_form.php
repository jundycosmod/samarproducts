<?php require_once('Connections/akonsudoy.php'); ?>
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

$colname_query_email = "-1";
if (isset($_POST['email'])) {
    $colname_query_email = $_POST['email'];
}

$query_query_email = sprintf("SELECT email FROM users WHERE email = %s", GetSQLValueString($colname_query_email, "text"));
$query_email = mysqli_query($akonsudoy, $query_query_email) or die(mysqli_error($akonsudoy));
$row_query_email = mysqli_fetch_assoc($query_email);
$totalRows_query_email = mysqli_num_rows($query_email);
unset($missing);
// list expected fields
$expected = array('email', 'send', 'password1', 'password2', 'fullname', 'gender', 'age', 'pnumber', 'cnumber', 'address', 'city');
// set required fields
$required = array('email', 'send', 'password1', 'password2', 'fullname', 'gender', 'age', 'cnumber', 'address', 'city');
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
$error_msg = NULL;
if (isset($_POST["MM_insert"])) {
    if ((preg_match("/@/i", $_POST['email']) > 0)) {

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && ($_POST['password1'] != "") && ($_POST['password1'] == $_POST['password2']) && (empty($missing)) && ($totalRows_query_email < 1)) {
            $insertSQL = sprintf("INSERT INTO users (email, send, password, fullname, gender, age, pnumber, cnumber, address, city, access_level) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", GetSQLValueString($_POST['email'], "text"), GetSQLValueString($_POST['send'], "int"), GetSQLValueString($_POST['password1'], "text"), GetSQLValueString($_POST['fullname'], "text"), GetSQLValueString($_POST['gender'], "text"), GetSQLValueString($_POST['age'], "int"), GetSQLValueString($_POST['pnumber'], "text"), GetSQLValueString($_POST['cnumber'], "text"), GetSQLValueString($_POST['address'], "text"), GetSQLValueString($_POST['city'], "text"), GetSQLValueString($_POST['access_level'], "int"));

            $Result1 = mysqli_query($akonsudoy, $insertSQL) or die(mysqli_error($akonsudoy));

            $insertGoTo = "registration_form.php";
            if (isset($_SERVER['QUERY_STRING'])) {
                $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
                $insertGoTo .= $_SERVER['QUERY_STRING'];
            }
            //header(sprintf("Location: %s", $insertGoTo));
            unset($missing);
        }
    } elseif (isset($_POST['email'])) {
        $error_msg = "ERROR: Invalid email address!";
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Register</title>
        <style type="text/css">
            <!--
            #form1 div .warning {
                color: #F00;
            }
            -->
        </style>
        <link href="style.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            <!--
            body,td,th {
                font-size: 12px;
            }
            .style1 {color: #FF0000}
            -->
        </style></head>

    <body>
        <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
            <p align="left"><img src="images/register.png" width="280" height="40" /></p>
            <table width="auto" border="0" align="center">
                <tr>
                    <td colspan="3">
                        <p align="center" class="warning style1">
                            <?php
                            if ($error_msg != NULL) {
                                echo $error_msg;
                            }
                            if ($_POST && isset($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0)) {
                                ?>
                                <br />
                                Please take a look at the item(s) indicated.</p>
                            <div align="center">
                                <?php
                            } elseif ($_POST) {
                                ?>
                            </div>
                            <p align="center" class="no_error">You are successfully registered!</p>
                            <div align="center">
                                <?php
                            }
                            ?>
                        </div></td>
                </tr>
                <tr>
                    <td><div align="right">Email Address:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                <?php if (isset($missing) && in_array('email', $missing)) { ?>
                                    <span class="style1">
                                        <span class="style1">Please enter your Email Address.</span>
                                        <?php
                                    }if (isset($totalRows_query_email) && $totalRows_query_email != 0) {
                                        ?>
                                        <span class="style1"> Email already in use.    </span>
                                    <?php } ?>
                                </span></span> <br />
                                <input type="text" name="email" id="email" <?php
                                if (!empty($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0) || isset($error_msg)) {
                                    echo 'value="' . htmlentities($_POST['email']) . '"';
                                }
                                ?>/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Receive updates from sudoy.com?</div></td>
                    <td><p align="left">
                            <input name="send" type="radio" id="send_0" value="1"
                                   checked="checked" 
                                   <?php
                                   if (isset($_POST["MM_insert"])) {
                                       if ((isset($missing) && $_POST['send'] == 1) || isset($error_msg)) {
                                           ?>
                                       <?php }
                                   }
                                   ?> />
                            Yes<br />
                        </p></td>
                    <td><input type="radio" name="send" value="2" id="send_1" 
                        <?php
                        if (isset($_POST["MM_insert"])) {
                            if ((isset($missing) && $_POST['send'] == 0) || isset($error_msg)) {
                                ?>
                                       checked="checked"
    <?php }
}
?>/>
                        No</td>
                </tr>
                <tr>
                    <td><div align="right">Password:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                    <?php if (isset($missing) && in_array('password1', $missing)) { ?>
                                    <span class="warning style1">Please enter your password.</span>
                                    <span class="style1">
                                    <?php }if ($_POST && ($_POST['password1'] != $_POST['password2'])) {
                                        ?>
                                        Two passwords don't match.</span>
                                    <span class="style1">
                                    <?php
                                }
                                ?>
                                </span>        <br />
                                <input type="password" name="password1" id="password1" <?php
                                if (!empty($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0) || isset($error_msg)) {
                                    echo 'value="' . htmlentities($_POST['password1']) . '"';
                                }
                                ?>/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Confirm Password:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                <?php if (isset($missing) && in_array('password2', $missing)) { ?>
                                    <span class="warning style1">Please confirm your password.</span>
                                    <?php
                                }
                                ?>
                                <br />
                                <input type="password" name="password2" id="password2" <?php
                                if (!empty($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0) || isset($error_msg)) {
                                    echo 'value="' . htmlentities($_POST['password2']) . '"';
                                }
                                ?>/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Full Name:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                    <?php if (isset($missing) && in_array('fullname', $missing)) { ?>
                                    <span class="warning style1">Please enter your full name.</span>
                                    <span class="style1">
    <?php
}
?>
                                    <br />
                                </span>
                                <input type="text" name="fullname" id="fullname" <?php
                                if (!empty($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0) || isset($error_msg)) {
                                    echo 'value="' . htmlentities($_POST['fullname']) . '"';
                                }
?>/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Gender:</div></td>
                    <td colspan="2">
                        <div align="left">
                            <table width="200" border="0">
                                <tr>
                                    <td>
                                        <label>
                                            <input name="gender" type="radio" id="" value="M"
                                                   checked="CHECKED"
                                                   <?php
                                                   if (isset($_POST["MM_insert"])) {
                                                       if ((isset($missing) && $_POST['gender'] == "M") || isset($error_msg)) {
                                                           ?>
                                                <?php }
                                            }
                                            ?>/>
                                            Male</label></td>
                                    <td><label>
                                            <input type="radio" name="gender" value="F" id="" 
<?php
if (isset($_POST["MM_insert"])) {
    if ((isset($missing) && $_POST['gender'] == "F") || isset($error_msg)) {
        ?>
                                                           checked="CHECKED"
    <?php }
}
?>/>
                                            Female</label></td>
                                </tr>
                            </table>
                        </div></td>
                </tr>
                <tr>
                    <td><div align="right">Age:</div></td>
                    <td colspan="2"><label>
                            <span class="style1">
                                <div align="left">
                            <?php if (isset($missing) && in_array('age', $missing)) { ?>
                                        <span class="style1">Please enter your age.</span></span>
                                       <?php
                                   }
                                   ?>
                            <br />
                            <input type="text" name="age" id="age" <?php
                                   if (!empty($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0) || isset($error_msg)) {
                                       echo 'value="' . htmlentities($_POST['age']) . '"';
                                   }
                                   ?>/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Phone Number:</div></td>
                    <td colspan="2"><label>
                            <span class="style1">
                                <div align="left">
                                    <?php if (isset($missing) && in_array('pnumber', $missing)) { ?>
                                        <span class="style1">Please enter your phone number.</span>
                                               <?php
                                           }
                                           ?>
                                    <br />
                                    <input type="text" name="pnumber" id="pnumber" <?php
                                           if (!empty($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0) || isset($error_msg)) {
                                               echo 'value="' . htmlentities($_POST['pnumber']) . '"';
                                           }
                                           ?>/>
                            </span> </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Cellphone Number:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                <?php if (isset($missing) && in_array('cnumber', $missing)) { ?>
                                    <span class="warning style1">Please enter your cellphone number.</span>
                                           <?php
                                       }
                                       ?>
                                <br />
                                <input type="text" name="cnumber" id="cnumber" <?php
                                       if (!empty($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0) || isset($error_msg)) {
                                           echo 'value="' . htmlentities($_POST['cnumber']) . '"';
                                       }
                                       ?>/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Home Address:</div></td>
                    <td colspan="2"><label>
                            <span class="style1">
                                <div align="left">
                            <?php if (isset($missing) && in_array('address', $missing)) { ?>
                                        <span class="style1">Please enter your home address.</span></span>
                                       <?php
                                   }
                                   ?>
                            <br />
                            <input type="text" name="address" id="address" /<?php
                                   if (!empty($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0) || isset($error_msg)) {
                                       echo 'value="' . htmlentities($_POST['address']) . '"';
                                   }
                                   ?>>
                                </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">City/Municipality:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                <?php if (isset($missing) && in_array('city', $missing)) { ?>
                                    <span class="warning style1">Please enter the city/municipality you live.</span>
                                           <?php
                                       }
                                       ?>
                                <br />
                                <input type="text" name="city" id="city" <?php
                                       if (!empty($missing) || ($_POST && ($_POST['password1'] != $_POST['password2'])) || (isset($totalRows_query_email) && $totalRows_query_email != 0) || isset($error_msg)) {
                                           echo 'value="' . htmlentities($_POST['city']) . '"';
                                       }
                                       ?>/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td colspan="3"><div align="center">
                            <input type="hidden" name="access_level" id="hiddenField" value="1"/>
                            <input type="submit" name="button" id="button" value="Submit" />
                        </div></td>
                </tr>
            </table>
            <p>&nbsp;</p>
            <input type="hidden" name="MM_insert" value="form1" />
        </form>
    </body>
</html>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && ($_POST['password1'] == $_POST['password2']) && (empty($missing))) {
    mysqli_free_result($query_email);
}
unset($missing);
