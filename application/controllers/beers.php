<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beers extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL:  http://example.com/beers
     */
    public function index()
    {
        // load the Beer Model
        $this->load->model('beer_model', '', TRUE);
        // use it to get an array of beers
        $beers = $this->beer_model->getBeers_array( 'b.BeerID, b.BeerName'
                                               , '', 'b.BeerName' );
        // add the stats view to the breweries data
        $breweries['stats'] = $this->stats(TRUE);
        // load the beers view
        $this->load->view('beers_view', $beers);
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

    public function showBeer()
    {
        // get the POSTed beerid
        $beerid = $this->input->post('beerid', TRUE);
        // load the Brewery model
        $this->load->model('beer_model','',TRUE);
        // get the beer details as an array
        $beer_array = $this->beer_model->getBeer_array( $beerid );
        // load the main view, passing the beer details
        $this->load->view('beerPubs_view', $beer_array[0] );
    }

}

/* End of file pubs.php */
/* Location: ./application/controllers/beers.php */

