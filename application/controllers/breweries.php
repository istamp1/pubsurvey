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

    public function mergeBreweries()
    {
        $breweryId = $this->input->post('breweryId', TRUE);
        $breweryToMergeId = $this->input->post('breweryToMergeId', TRUE);

        echo $breweryId;
        echo $breweryToMergeId;

        // load the Brewery model
        $this->load->model('brewery_model','',TRUE);
        // merge the two breweries
        $this->brewery_model->mergeBreweries($breweryId, $breweryToMergeId);

        // get the brewery details as an array
        $brewery_array = $this->brewery_model->getBrewery_array( $breweryId );
        // load the main view, passing the brewery details
        $this->load->view('breweryBeers_view', $brewery_array[0] );
    }

    public function showBrewery()
    {
        // get the POSTed breweryid
        $breweryid = $this->input->post('breweryid', TRUE);
        // load the Brewery model
        $this->load->model('brewery_model','',TRUE);
        // get the brewery details as an array
        $brewery_array = $this->brewery_model->getBrewery_array( $breweryid );
        // load the main view, passing the brewery details
        $this->load->view('breweryBeers_view', $brewery_array[0] );

//        // get the brewery beers and ciders as an array
//        $brewerybeers_array = $this->brewery_model->getPubBeers_array( $breweryid );
//        // load Beers
//        $this->load->view('breweryBeerHeading_view', array( 'beerCider' => 'Beers' ) );
//        foreach ($brewerybeers_array as $brewerybeer) {
//            if ($brewerybeer['BeerCiderPerry'] == 'B') {
//                // load the view, passing the beer details
//                $this->load->view('breweryBeer_view', array( 'brewerybeer' => $brewerybeer, 'beerCider' => 'Beers' ) );
//            }
//        }
    }

}

/* End of file breweries.php */
/* Location: ./application/controllers/breweries.php */
