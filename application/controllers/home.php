<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/home
     *	- or -
     * 		http://example.com/index.php/home/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        session_start();
        // set Year
		include 'getYear.php';
        // load the pub Model
        $this->load->model('utilities_model', '', TRUE);
        // get stats as an array
        $stats = $this->utilities_model->getStats_array($year);
        // return the stats view
        $stats_view = $this->load->view('pubStats_view', $stats[0], TRUE );
        // load home view
        $this->load->view('home_view', array( 'stats' => $stats_view ) );
    }

    public function volunteerEmails()
    {
        session_start();
        // set Year
		include 'getYear.php';
        // load the pub Model
        $this->load->model('utilities_model', '', TRUE);
        // get emails as a comma-separated list
        $emails = $this->utilities_model->getVolunteerEmails($year);
        // return the emails view
        $this->load->view('emails_view', array( 'emails' => $emails[0] ) );
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */