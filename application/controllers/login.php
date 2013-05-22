<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL:  http://example.com/pubs
     */
    
    // Authenticate user using the APT service
    function Authenticate($securityKey, // security key 
                          $username, 	// username of user to authenticate
                          $password 	// claimed password of user
            ) {
        $url = 'https://comms2.aptsolutions.net/cra-comms/users';	// the URL of the authentication service

        // create a CURL channel to call the authentication service
        $ch = curl_init();
        if( !$ch )  { 
            trigger_error( "Failed to start CURL", E_USER_ERROR );        
        }
        
        // REMOVE BEFORE GOING LIVE!!
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 

        $securityKeyParam = urlencode( $securityKey ); 

        // construct the XML request object
        $doc = new SimpleXMLElement( '<Users/>' );
        $doc->addChild( 'UserID', $username );
        $doc->addChild( 'Password', $password ); 
        
        $request = $doc->asXML();
        $requestParam = urlencode( $request );

        // setup a POST
        curl_setopt( $ch, CURLOPT_URL, "$url/?securityKey=$securityKeyParam" );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, "newValues=$requestParam&callMethod=ReadData" );

        // ensure curl_exec returns the result rather than echoing it
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

        // execute the POST method
        $xmlString = curl_exec( $ch );

        if (curl_errno( $ch )) {
            trigger_error( 'CURL error: ' . curl_error( $ch ), E_USER_ERROR );
        }

        curl_close( $ch );

        try{
            $xml = new SimpleXMLElement( $xmlString );
        }
        catch( Exception $e )
        {
            trigger_error( 'Invalid response: ' . $e->getMessage(), E_USER_ERROR );
        }

        if ($xml->getName() != 'Users') {
            trigger_error( 'Invalid response data: ' . $xml->getName(), E_USER_ERROR );
        }

        if( $xml->status == '0' ) {
            // username/password OK
            if( $xml->LapseCode == '' ) { 
                // not lapsed 
            } else { 
                return 'Lapsed'; 
            }
        } else if( $xml->status == '1' ) {
            return 'InvalidUser';	
        } else {
            trigger_error( 'Invalid response value: ' . $xml->status, E_USER_ERROR );
        }

        $name = $xml->Forename1 . ' ' . $xml->Surname;
        $branch = $xml->BranchDesc;

        return array( 'name' => $name
                    , 'branch' => $branch
                    , 'response' => $xmlString );
    }

    function CAMRALogin($number, $password) {  
        
        $securityKey = '';

        $info = $this->Authenticate( $securityKey, $number, $password );

        if( $info == 'InvalidUser' ) {
            echo 'Membership number or password incorrect';
        } else if( $info == 'Lapsed' ) {
            echo 'Membership lapsed';
        } else {   
             return $info['response'];
        }
    }
    
    public function index()
    {
        $uname = $this->input->post('uname');
        $pwd = $this->input->post('password');
        
        $xmlString = '';

        if ($uname) {
            $xmlString = $this->CAMRALogin($uname, $pwd);
        }

        // load the view to log in    
        $viewData = array( "xmlString" => $xmlString); 
        
        $this->load->view('login_view', $viewData);
    }        
}

/* End of file pubs.php */
/* Location: ./application/controllers/pubs.php */