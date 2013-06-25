<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Breweries extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL:  http://example.com/breweries
     */
    public function index()
    {
        // load the pub Model
        $this->load->model('brewery_model', '', TRUE);
        // use it to get an array of breweries
        $breweries = $this->brewery_model->getBreweries_array( 'br.BreweryID, br.BreweryName'
                                               , '', 'br.BreweryName' );
        // add the stats view to the breweries data
        $breweries['stats'] = $this->stats(TRUE);
        // load the breweries view
        $this->load->view('breweries_view', $breweries);
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

}

/* End of file pubs.php */
/* Location: ./application/controllers/breweries.php */
