<?php require_once('Connections/akonsudoy.php'); ?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
        if (($strUsers == "") && true) {
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
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
    session_start();
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf("UPDATE users SET email=%s, send=%s, fullname=%s, gender=%s, age=%s, pnumber=%s, cnumber=%s, address=%s, city=%s, access_level=%s WHERE user_id=%s", GetSQLValueString($_POST['email'], "text"), GetSQLValueString($_POST['send'], "int"), GetSQLValueString($_POST['fullname'], "text"), GetSQLValueString($_POST['gender'], "text"), GetSQLValueString($_POST['age'], "int"), GetSQLValueString($_POST['pnumber'], "text"), GetSQLValueString($_POST['cnumber'], "text"), GetSQLValueString($_POST['address'], "text"), GetSQLValueString($_POST['city'], "text"), GetSQLValueString($_POST['access_level'], "int"), GetSQLValueString($_POST['user_id'], "int"));

    $Result1 = mysqli_query($akonsudoy, $updateSQL) or die(mysqlu_error($akonsudoy));
}
unset($missing);
// list expected fields
$expected = array('email', 'send', 'fullname', 'gender', 'age', 'pnumber', 'cnumber', 'address', 'city');
// set required fields
$required = array('email', 'send', 'fullname', 'gender', 'age', 'cnumber', 'address', 'city');
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
$colname_profile = "-1";
if (isset($_SESSION['user_id'])) {
    $colname_profile = $_SESSION['user_id'];
}

$query_profile = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_profile, "int"));
$profile = mysqli_query($akonsudoy, $query_profile) or die(mysqli_error($akonsudoy));
$row_profile = mysqli_fetch_assoc($profile);
$totalRows_profile = mysqli_num_rows($profile);
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
        <p><img src="images/update_profile.png" width="280" height="40" /></p>
        <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
            <table width="auto" border="0" align="center">
                <tr>
                    <td colspan="3"><div align="center">
                            <?php
                            if ($_POST && empty($missing)) {
                                ?>
                            </div>
                            <p align="center" class="no_error">Your profile successfully updated!</p>
                            <div align="center">
                                <?php
                            } elseif ($_POST && !empty($missing)) {
                                ?>
                            </div>
                            <p align="center" class="warning style1">Please complete the missing item(s) indicated.</p>

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
                                    <span class="style1"><span class="style1"> Please enter your Email Address.            </span>
                                    <?php } ?>
                                </span> <br />
                                <input type="text" name="email" id="email" value="<?php echo $row_profile['email']; ?>" readonly="true"/>
                                <input type="hidden" name="user_id" id="hiddenField2" value="<?php echo $row_profile['user_id']; ?>" />
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Receive updates from sudoy.com?</div></td>
                    <td><p align="left">
                            <input name="send" type="radio" id="send_0" value="1"
                            <?php if ($row_profile['send'] == 1) { ?>
                                       checked="checked" 
                                   <?php } ?> />
                            Yes<br />
                        </p></td>
                    <td><input type="radio" name="send" value="2" id="send_1" 
                        <?php if ($row_profile['send'] == 2) { ?>
                                   checked="checked"
                               <?php } ?>/>
                        No</td>
                </tr>
                <tr>
                    <td><div align="right">Full Name:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                <?php if (isset($missing) && in_array('fullname', $missing)) { ?>
                                    <span class="warning style1">Please enter your full name.</span>
                                    <?php
                                }
                                ?>
                                <br />
                                <input type="text" name="fullname" id="fullname" value="<?php echo $row_profile['fullname']; ?>"/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Gender:</div></td>
                    <td colspan="2"><div align="left">
                            <table width="200" border="0">
                                <tr>
                                    <td><label>
                                            <input name="gender" type="radio" id="gender" value="M"
                                            <?php if ($row_profile['gender'] == "M") { ?>
                                                       checked="checked"
                                                   <?php } ?>/>
                                            Male</label></td>
                                    <td><label>
                                            <input type="radio" name="gender" value="F" id="gender" 
                                            <?php if ($row_profile['gender'] == "F") { ?>
                                                       checked="checked"
                                                   <?php } ?>/>
                                            Female</label></td>
                                </tr>
                            </table>
                        </div></td>
                </tr>
                <tr>
                    <td><div align="right">Age:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                <?php if (isset($missing) && in_array('age', $missing)) { ?>
                                    <span class="warning style1">Please enter your age.</span>
                                    <?php
                                }
                                ?>
                                <br />
                                <input type="text" name="age" id="age" value="<?php echo $row_profile['age']; ?>"/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Phone Number:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                <?php if (isset($missing) && in_array('pnumber', $missing)) { ?>
                                    <span class="warning style1">Please enter your phone number.</span>
                                    <?php
                                }
                                ?>
                                <br />
                                <input type="text" name="pnumber" id="pnumber" value="<?php echo $row_profile['pnumber']; ?>"/>
                            </div>
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
                                <input type="text" name="cnumber" id="cnumber" value="<?php echo $row_profile['cnumber']; ?>"/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td><div align="right">Home Address:</div></td>
                    <td colspan="2"><label>
                            <div align="left">
                                <?php if (isset($missing) && in_array('address', $missing)) { ?>
                                    <span class="warning style1">Please enter your home address.</span>
                                    <?php
                                }
                                ?>
                                <br />
                                <input type="text" name="address" id="address" value="<?php echo $row_profile['address']; ?>"/>
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
                                <input type="text" name="city" id="city" value="<?php echo $row_profile['city']; ?>"/>
                            </div>
                        </label></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div align="center">
                            <input type="hidden" name="access_level" id="hiddenField" value="<?php echo $row_profile['access_level']; ?>"/>        
                            <input type="submit" name="button" id="button" value="Update Profile" />      
                        </div></td>
                </tr>
            </table>
            <p>
                <input type="hidden" name="MM_update" value="form1" />
            </p>
        </form>
    </body>
</html>
<?php
mysqli_free_result($profile);

