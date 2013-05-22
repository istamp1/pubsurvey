<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beers extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL:  http://example.com/beers
     */
    public function index()
    {            
//        // load the pub Model    
//        $this->load->model('beer_model', '', TRUE);
//        // use it to get an array of beers    
//        $beers = $this->beer_model->getBeers_array( 'b.BeerID, b.BeerName'
//                                               , '', 'b.BeerName' );
//        // load the beers view    
//        $this->load->view('beers_view', $beers);  
        $this->load->view('beers_view');  
    }
   
}

/* End of file pubs.php */
/* Location: ./application/controllers/beers.php */

