<?php
    $forename = '';
    $branch = '';
    $loggedIn = false;
	
    // set Year
    $year = date("Y");
    if (isset($_SESSION['surveyyear'])) {
        $year = $_SESSION['surveyyear'];
    } else {
        $year = date("Y");
        $_SESSION['surveyyear'] = $year;
    }

    // set xmlStraing
    if (isset($_SESSION['xmlString']) && $_SESSION['xmlString'] != '') {
        $xmlString = $_SESSION['xmlString'];
    } 
    if (isset($xmlString)) { 
//			var_dump($xmlString);
        if ($xmlString != '') { 
			if($xmlString['validLogin']) {
				$forename = $xmlString['forename'];
				$surname = $xmlString['surname'];
				$branch = $xmlString['branch'];
				$membershipNumber = $xmlString['membershipNumber']; 
				$email = (isset($xmlString['email'])) ? $xmlString['email'] : '';
				// set flags
				$loggedIn = true;
				$_SESSION['xmlString'] = $xmlString;
			} else {
				$xmlString = '';
				unset($_SESSION['xmlString']);
			}
        }
    }
?>
