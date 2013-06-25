<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pubs extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL:  http://example.com/pubs
     */
    public function index()
    {
        // load the pub model
        $this->load->model('pub_model', '', TRUE);
        // use it to get an array of pubs
        $pubs = $this->pub_model->getPubs_array( 'p.PubID, PubName, PubNameQualifier
                                                 , ifnull( pv.norealale, 0 ) NoRealAle
                                                 , ifnull( pb.BeerCount, 0 ) BeerCount'
                                               , '', 'PubName, PubNameQualifier' );
        // add the stats view to the pubs data
        $pubs['stats'] = $this->stats(TRUE);
        // load the pubs view
        $this->load->view('pubs_view', $pubs);
    }

    public function getStats()
    {
        // load the model
        $this->stats(FALSE);
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

    public function showPub()
    {
        // get the POSTed pubid
        $pubid = $this->input->post('pubid', TRUE);
        // load the Pubs model
        $this->load->model('pub_model','',TRUE);
        // get the pub details as an array
        $pub_array = $this->pub_model->getPub_array( $pubid );
        // load the 'no real ale' view, passing the pub details
        $norealaleview = $this->load->view('pubBeersNoRealAle_view', $pub_array[0], TRUE );
        // load the main view, passing the pub details and the NRA view
        $pub_array[0]['norealaleview'] = $norealaleview;
        $this->load->view('pubBeers_view', $pub_array[0] );

        // get the pub beers and ciders as an array
        $pubbeers_array = $this->pub_model->getPubBeers_array( $pubid );
        // load Beers
        $this->load->view('pubBeerHeading_view', array( 'beerCider' => 'Beers' ) );
        foreach ($pubbeers_array as $pubbeer) {
            if ($pubbeer['BeerCiderPerry'] == 'B') {
                // load the view, passing the beer details
                $this->load->view('pubBeer_view', array( 'pubbeer' => $pubbeer, 'beerCider' => 'Beers' ) );
            }
        }
        $this->load->view('pubBeerFooter_view');
        // load Ciders
        $this->load->view('pubBeerHeading_view', array( 'beerCider' => 'Ciders' ) );
        foreach ($pubbeers_array as $pubbeer) {
            if ($pubbeer['BeerCiderPerry'] != 'B') {
                // load the view, passing the beer details
                $this->load->view('pubBeer_view', array( 'pubbeer' => $pubbeer, 'beerCider' => 'Ciders' ) );
            }
        }
        $this->load->view('pubBeerFooter_view');

        $beerstyles = $this->pub_model->getBeerStyles_array();
        // load the view to add a new beer
        $this->load->view('pubBeerAdd_view', array( 'pubid' => $pubid, 'beerStyles' => $beerstyles ) );
    }

    public function updatePubVolunteer()
    {
        $pubid = $this->input->post('pubid', TRUE);

        $memberNo = $this->input->post('memberno', TRUE);
        $memberName = $this->input->post('membername', TRUE);
        $noRealAle = $this->input->post('norealale', TRUE);

        $row['PubID'] = $pubid;
        $row['SurveyYear'] = '2013';
        if ($memberNo !== FALSE) {
            $memberNo = preg_replace("/[^0-9]+/", "", $memberNo);
            $row['MemberNo'] = $memberNo;
        }
        if ($memberName !== FALSE) {
            $memberName = preg_replace("/[^a-zA-Z ]+/", "", $memberName);
            $row['MemberName'] = ucwords($memberName);
        }
        if ($noRealAle !== FALSE) { $row['NoRealAle'] = $noRealAle; }

        // load the pub model
        $this->load->model('pub_model','',TRUE);
        // update the pub details
        $this->pub_model->updatePubVolunteer( $pubid, $row);
        // rebuild the pub HTML
        //$this->showPub();
        // get the pub details as an array
        $pub_array = $this->pub_model->getPub_array( $pubid );
        // load the 'no real ale' view, passing the pub details
        $this->load->view('pubBeersNoRealAle_view', $pub_array[0] );
    }

    public function addPubBeer()
    {
        // get the POST variables and clean / fix
        $pubID = $this->input->post('pubid', TRUE );
        $beerID = $this->input->post('beerid', TRUE );
        $breweryName = ucwords($this->input->post('breweryname', TRUE ));
        $beerName = ucwords($this->input->post('beername', TRUE ));
        $ABV = $this->input->post('abv', TRUE );
        $beerStyle = $this->input->post('beerstyle', TRUE );
        $dispense = $this->input->post('dispense', TRUE );
        $price = $this->input->post('price', TRUE );

        $breweryName = preg_replace("/[^a-zA-Z0-9 ]+/", "", $breweryName);
        $beerName = preg_replace("/[^a-zA-Z0-9 ]+/", "", $beerName);

        // fix price
        if ($price > 10) {
            $price = $price / 100;
        }
        // load the pub model
        $this->load->model('pub_model','',TRUE);
        // add the brewery, beer, and pub beer
        $beerID = $this->pub_model->addPubBeer( $pubID, $beerID, $breweryName, $beerName, $ABV, $beerStyle, $dispense, $price );

        // create pub beer HTML
        $pubbeers_array = $this->pub_model->getPubBeers_array( $pubID, $beerID );
        $rowNum = 1;
        $this->load->view('pubBeer_view', array( 'pubbeer' => $pubbeers_array[0], 'rowNum' => $rowNum, 'beerCider' => 'Beers' ) );
    }

    public function deletePubBeer()
    {
        $pubid = $this->input->post('pubid');
        $beerid = $this->input->post('beerid');
        // load the pub model
        $this->load->model('pub_model','',TRUE);
        // update the pub details
        $this->pub_model->deletePubBeer( $pubid, $beerid );
    }

    public function findBeers()
    {
        // get the sesrch term
        $search = $this->input->post('search');
        $pubID = $this->input->post('pubid');
        // load the model
        $this->load->model('pub_model','',TRUE);
        // get the pub details as an array
        $beers = $this->pub_model->findBeers_array( $search, $pubID );
        // load the view, passing the pub details
        $this->load->view('findBeers_view', $beers );
    }

}

/* End of file pubs.php */
/* Location: ./application/controllers/pubs.php */