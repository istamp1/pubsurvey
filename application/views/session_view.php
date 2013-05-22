<?php 
    $forename = '';
    $branch = '';
    if (isset($_SESSION['xmlString'])) {
        $xmlString = $_SESSION['xmlString'];
    } 
    if (isset($xmlString)) { 
        if ($xmlString != '') { 
            $xml = new SimpleXMLElement($xmlString);
            $forename = $xml->Forename1;
            $branch = $xml->Branch;
            $_SESSION['xmlString'] = $xmlString;
        }
    }
?>
