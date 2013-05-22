<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Breweries extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL:  http://example.com/breweries
     */
    public function index()
    {            
//        // load the pub Model    
//        $this->load->model('brewery_model', '', TRUE);
//        // use it to get an array of breweries    
//        $breweries = $this->brewery_model->getBreweries_array( 'br.BreweryID, br.BreweryName'
//                                               , '', 'br.BreweryName' );
//        // load the breweries view    
//        $this->load->view('breweries_view', $breweries);  
        $this->load->view('breweries_view');  
    }
   
}

/* End of file pubs.php */
/* Location: ./application/controllers/breweries.php */
