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
        $beers = $this->beer_model->getBeers_array( 'br.BreweryName, b.BeerID, b.BeerName'
                                               , '', 'br.BreweryName, b.BeerName' );
        // add the stats view to the beers data
        $beers['stats'] = $this->stats(TRUE);
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
        // load the Beer model
        $this->load->model('beer_model','',TRUE);
        // get the beer details as an array
        $beer_array = $this->beer_model->getBeer_array( $beerid );
        // get an array of beers
//        $beers = $this->beer_model->getBeers_array( 'br.BeerID, br.BeerName'
//                                               , '', 'br.BeerName' );
        // create an array to pass to the view
        $return = $beer_array[0];
        // add the beers list
//        $return['beers'] = $beers['beers'];
        // load the view, passing the beer details
        $this->load->view('beerPubs_view', $return );

        $this->load->view('beerPubHeading_view', array( 'beerCider' => 'Beers' ) );
        // get the beer pubs as an array
        $beerpubs_array = $this->beer_model->getBeerPubs_array( $beerid );
        // load Beers
        foreach ($beerpubs_array as $beerpub) {
            // load the view, passing the beer details
            $this->load->view('beerPub_view', array( 'beerpub' => $beerpub ) );
        }
    }

    public function updateBeer()
    {
        $beerid = $this->input->post('beerId', TRUE);
        $beerName = $this->input->post('beerName', TRUE);

        // load the beer model
        $this->load->model('beer_model', '', TRUE);
        // update the beer
        $this->beer_model->updatebeer($beerid, array( 'beerName' => $beerName ) );
        
        // get the beer details as an array
        $beer_array = $this->beer_model->getBeer_array( $beerid );
        // get an array of beers
//        $beers = $this->beer_model->getBeers_array( 'br.BeerID, br.BeerName'
//                                               , '', 'br.BeerName' );
        // create an array to pass to the view
        $return = $beer_array[0];
        // add the beers list
//        $return['beers'] = $beers['beers'];
        // load the view, passing the beer details
        $this->load->view('beerPubs_view', $return );

        $this->load->view('beerPubHeading_view', array( 'beerCider' => 'Beers' ) );
        // get the beer pubs as an array
        $beerpubs_array = $this->beer_model->getBeerPubs_array( $beerid );
        // load Beers
        foreach ($beerpubs_array as $beerpub) {
            // load the view, passing the beer details
            $this->load->view('beerPub_view', array( 'beerpub' => $beerpub ) );
        }
    }

}

/* End of file pubs.php */
/* Location: ./application/controllers/beers.php */

