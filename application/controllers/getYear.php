<?php
    // set Year
    if(isset($_GET['year'])) {
		// passed in as a parameter
		$year = $_GET['year'];
    } else {
		// not passed in
		if (isset($_SESSION['surveyyear'])) {
			// in $_SESSION
			$year = $_SESSION['surveyyear'];
		} else {
			// set to current year
			$year = date("Y");
		}
    }
    // set $_SESSION variable
    $_SESSION['surveyyear'] = $year;
?>