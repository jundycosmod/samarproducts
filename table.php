<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <p>
  <?php
  for($i=1;$i<5;$i++){
  ?>
    <label>
      <input type="text" name="text[]" id="textfield" />
    </label>
    </br>
    <?php
  }
	?>
  </p>
  <p>
    <label>
      <input type="submit" name="button" id="button" value="Submit" />
    </label>
  </p>
</form>
<?php
if($_POST && isset($_POST['button'])){
foreach($_POST['text'] as $text)
	{
		echo $text[1]."</br>";
	}
}
?>
</body>
</html>