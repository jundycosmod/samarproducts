<div id="footer">
    <p>&copy;
        <?php
		ini_set('date.timezone', 'Europe/London');
		$startYear = 2006;
		$thisYear = date('Y');
		if ($startYear == $thisYear) {
		  echo $startYear;
		  }
		else {
		  echo "{$startYear}-{$thisYear}";
		  }
		?>
        David Powers</p>
</div>
