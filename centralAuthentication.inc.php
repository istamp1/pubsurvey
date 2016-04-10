<?php
// Authenticate user using the CAMRA central authentication service

// Attempts to authenticate the supplied credentials with the 
// CAMRA central authentication system.
// Inputs:
//  - securityKey - a valid access key for the authentication system.
//  - username    - the username used in the login attempt.
//                  This will be a membership number.
//  - password    - the password used in the login attempt.
// Returns:
//  An array containing the following items:
//  - checkSuccessful  - whether the API call succeeded. This will be
//                       true if yes, false if some error occurred.
//  - validLogin       - whether the supplied credentials were correct.
//                       This will be true if yes, false if no.
//  - branch           - The user's branch.
//  - membershipNumber - The user's membership number.
//  - forename         - The user's first name.
//  - surname          - The users surname.
// Branch, membershipNumber, forename and surname will be empty strings
// if the API call failed or the supplied credentials are not valid.
function Authenticate(
            $securityKey,   // security key
            $username,      // username of user to authenticate
            $password       // claimed password of user
)
{
    include_once("edit/logging.inc.php");

    // URL of the central authentication service (XML version)
    $url = 'https://api.camra.org.uk/index.php/api/branch/auth_3/format/xml';

    // The results of the authentication attempt
    $authResult = Array('checkSuccessful' => false,
                        'validLogin' => false,
                        'branch' => '',
                        'membershipNumber' => '',
                        'forename' => '',
                        'surname' => '',
                        'email' => '');

    // create CURL channel
    $ch = curl_init();
    if( !$ch ) {
        $authResult['checkSuccessful'] = false;
        error_log( "curl_init failed." );
        return $authResult;
    }

    // set up a POST
    $postFields = Array('KEY' => $securityKey,
                        'memno' => $username,
                        'pass' => $password);

    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields );
    // ensure curl_exec returns the result rather than echoing it
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    // hack for servers with bad certificates (TODO: do we still need this?)
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);

    // execute the POST method
    $xmlString = curl_exec( $ch );

    if( curl_errno( $ch ) ) {
        $msg = "CURL Error ".curl_errno($ch)." authenticating: ".curl_error( $ch );
        error_log($msg);
        sql_log($msg);

        // TODO: Decide whether to move/delete this. It doesn't really belong here.
		// send email if first today
		// get date yyyy-mm-dd
		$dstr = date("Y-m-d");
		$fp = @fopen('APTmsg.txt', 'w+');
		$mdate = @fread($fp);
		if($dstr != $mdate) {
			// first error today
			@fwrite($fp, $dstr);
			@fclose($fp);
			$subject = "Unable to connect to CAMRA authentication";
			$message = "curl failed to connect to server ".date("Y-m-d H:i");
			include("mailheader.inc.php");
			mail($to,$subject,$message,$headers);
		}

        $authResult['checkSuccessful'] = false;
        return $authResult;
    }
    curl_close( $ch );

    // Retrieve response
    try
    {
        $responseXML = new SimpleXMLElement($xmlString);

        // Look for an API key error response
        $APIError = (string) $responseXML->error;
        if ($APIError == 'Invalid API Key.' ||
            $APIError == 'This API key does not have enough permissions.')
        {
            error_log("There is a problem with the API key: $APIError");
            $authResult['checkSuccessful'] = false;
        }
        // Look for an authentication failure response
        else if ( (string) $responseXML->Error == 'The membership number and/or password supplied are incorrect.' )
        {
            $authResult['checkSuccessful'] = true;
            $authResult['validLogin'] = false;
        }
        // Look for an authentication success response
        else if ( (string) $responseXML->MembershipNumber == $username )
        {
            $authResult['checkSuccessful'] = true;
            $authResult['validLogin'] = true;
            $authResult['branch'] = (string) $responseXML->Branch;
            $authResult['membershipNumber'] = (string) $responseXML->MembershipNumber;
            $authResult['forename'] = (string) $responseXML->Forename;
            $authResult['surname'] = (string) $responseXML->Surname;
            $authResult['email'] = (string) $responseXML->Email;

            // Check we were able to retrieve the data?
        }
    }
    catch (Exception $e)
    {
        $authResult['checkSuccessful'] = false;
        error_log( "Error raised when trying to parse auth API response as XML: " . $e.GetMessage() );
    }

    return $authResult;
}
