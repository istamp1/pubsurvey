<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL:  http://example.com/pubs
     */
    

    function CAMRALogin($number, $password) {  
        
        include("centralAuthentication.inc.php");
		include("centralAuthKey.inc.php");

        $info = Authenticate( $securityKey, $number, $password );
		
        if( !$info['validLogin'] ) {
            echo '<p>Membership number or password incorrect - please visit the <a href="https://members.camra.org.uk/web/guest">CAMRA Members website</a> where you can reset your password<p>';
        }
		return $info;
    }

    public function stats($asHTML) {
        // load the utilities model
        $this->load->model('utilities_model', '', TRUE);
        // get stats as an array
        $stats = $this->utilities_model->getStats_array();
        if ($asHTML) {
            // return the stats view
            return $this->load->view('pubStats_view', $stats[0], TRUE );
        } else {
            // load the stats view
            $this->load->view('pubStats_view', $stats[0] );
            return FALSE;
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
        // add the stats view to the pubs data
        $viewData['stats'] = $this->stats(TRUE);
        
        $this->load->view('login_view', $viewData);
    }        
}

/* End of file pubs.php */
/* Location: ./application/controllers/pubs.php */