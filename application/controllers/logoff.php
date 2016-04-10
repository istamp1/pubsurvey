<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logoff extends CI_Controller {
    
    public function index()
    {
//        $selected = strtolower($this->input->get('selected'));
        $selected = 'home';
	
		session_start();
		session_destroy();

		include 'getYear.php';

		// go to page we logged off from
		$this->load->helper('url');
		redirect('../'.$selected);
    }        
}

/* End of file pubs.php */
/* Location: ./application/controllers/pubs.php */