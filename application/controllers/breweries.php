<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Breweries extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL:  http://example.com/breweries
     */
    public function index()
    {
        // set Year
		include 'getYear.php';
        // load the Brewery Model
        $this->load->model('brewery_model', '', TRUE);
        // use it to get an array of breweries
        $breweries = $this->brewery_model->getBreweries_array( 'br.Id, br.BreweryCode, br.BreweryName'
                                               , '', 'br.BreweryName' );
        // add the stats view to the breweries data
        $breweries['stats'] = $this->stats(TRUE, $year);
        // load the breweries view
        $this->load->view('breweries_view', $breweries);
    }

    public function stats($asHTML, $year) {
        // load the utilities model
        $this->load->model('utilities_model', '', TRUE);
        // get stats as an array
        $stats = $this->utilities_model->getStats_array($year);
        if ($asHTML) {
            // return the stats view
            return $this->load->view('pubStats_view', $stats[0], TRUE );
        } else {
            // load the stats view
            $this->load->view('pubStats_view', $stats[0] );
            return FALSE;
        }
    }

    public function showBrewery()
    {
        // get the POSTed breweryid
        $breweryId = $this->input->post('breweryid', TRUE);
        $year = $this->input->post('year', TRUE);
	
        // load the Brewery model
        $this->load->model('brewery_model','',TRUE);
	
		$this->_loadBrewery($breweryId, $year);
    }

    private function _loadBrewery($breweryId, $year)
    {
        // get the brewery details as an array
        $brewery_array = $this->brewery_model->getBrewery_array( $breweryId );
        // get an array of breweries
        $breweries = $this->brewery_model->getBreweries_array( 'br.Id, br.BreweryCode, br.BreweryName'
                                               , '', 'br.BreweryName' );
        // create an array to pass to the view
        $return = $brewery_array[0];
        // add the breweries list
        $return['breweries'] = $breweries['breweries'];
        // load the view, passing the brewery details
        $this->load->view('breweryBeers_view', $return );

        $this->load->view('pubBeerHeading_view', array( 'beerCider' => 'Beers' ) );
        // get the brewery beers and ciders as an array
        $brewerybeers_array = $this->brewery_model->getBreweryBeers_array( $breweryId );
        // load Beers
        foreach ($brewerybeers_array as $brewerybeer) {
            // load the view, passing the beer details
            $this->load->view('breweryBeer_view', array( 'brewerybeer' => $brewerybeer, 'brewerybeers' => $brewerybeers_array ) );
        }
    }

    public function mergeBreweries()
    {
        $breweryId = $this->input->post('breweryId', TRUE);
        $breweryToMergeId = $this->input->post('breweryToMergeId', TRUE);

        // load the Brewery model
        $this->load->model('brewery_model', '', TRUE);
	
        // merge the two breweries
        $this->brewery_model->mergeBreweries($breweryId, $breweryToMergeId);
	
        $year = $_SESSION['surveyyear'];
		$this->_loadBrewery($breweryId, $year); 
    }

    public function mergeBeers()
    {
        $breweryId = $this->input->post('breweryId', TRUE);
        $mergeBeerId = $this->input->post('mergeBeerId', TRUE);
        $intoBeerId = $this->input->post('intoBeerId', TRUE);
        $year = $this->input->post('year', TRUE);

        // load the Brewery model
        $this->load->model('brewery_model', '', TRUE);
	
        // merge the two beers
        $this->brewery_model->mergeBeers($mergeBeerId, $intoBeerId);
	
		$this->_loadBrewery($breweryId, $year); 
    }

}

/* End of file breweries.php */
/* Location: ./application/controllers/breweries.php */
