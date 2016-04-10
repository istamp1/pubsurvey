<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pubs extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL:  http://example.com/pubs
     */
    public function index()
    {
        session_start();
		
		include 'getYear.php';
        // load the pub model
        $this->load->model('pub_model', '', TRUE);
        // use it to get an array of pubs
        $pubs = $this->pub_model->getPubs_array( 'p.PubID, PubName, PubNameQualifier
                                                 , ifnull( pv.norealale, 0 ) NoRealAle
                                                 , ifnull( pb.BeerCount, 0 ) BeerCount'
                                               , '', 'PubName, PubNameQualifier'
                                               , $year);
        // add the stats view to the pubs data
        $pubs['stats'] = $this->stats(TRUE, $year);
        // load the pubs view
        $this->load->view('pubs_view', $pubs);
    }

    public function mapPubs()
    {
        session_start(); 
		include 'getYear.php';
		// set filter(s)
		$allocated = '';
		if(isset($_GET['allocated'])) {
			$allocated = $_GET['allocated'];
			if($allocated == 'yes') {
			$allocated = 'pv.pubid is not null';
			} else {
			$allocated = 'pv.pubid is null';
			}
		}
        // load the pub model
        $this->load->model('pub_model', '', TRUE);
        // use it to get an array of pubs
        $pubs = $this->pub_model->getPubs_array( 
				"p.PubID, PubName, ifnull( m.membername, 'nobody' ) Volunteer
				, ifnull( pv.norealale, 0 ) NoRealAle
			    , Lat, Longt, BeerCount"
			, $allocated, ''
			, $year);
		// create an array of InfoWindow views
		$data['pubs'] = array();
		foreach($pubs['pubs'] as $pub) {
			$infoWindow = $this->load->view('pubInfoWindow_view', $pub, TRUE);
			$pubArray = array( 'html' => $infoWindow
						 , 'Lat' => $pub['Lat']
						 , 'Longt' => $pub['Longt']);
			$data['pubs'][] = $pubArray;
		}
        // add the stats view to the pubs data
        $data['stats'] = $this->stats(TRUE, $year);
        // load the pubs map view
        $this->load->view('pubsMap_view', $data);
    }

    public function getStats()
    {
        $year = $this->input->post('year', TRUE);
        // load the model
        $this->stats(FALSE, $year);
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

    public function showPub()
    {
        // get the POSTed pubid and Year
        $pubid = $this->input->post('pubid', TRUE);
        $year = $this->input->post('year', TRUE);
		
        // load the Pubs and Utilities models
        $this->load->model('pub_model','',TRUE);
        $this->load->model('utilities_model','',TRUE);
		
        // get the pub details as an array
        $pub_array = $this->pub_model->getPub_array( $pubid, $year );
		
        // get the members as an array
        $member_array = $this->utilities_model->getVolunteers( $year );
		
        // load the 'no real ale' view, passing the pub details
        $norealaleview = $this->load->view('pubBeersNoRealAle_view', $pub_array[0], TRUE );
		
        // load the main view, passing the pub details and the NRA view
        $pub_array[0]['norealaleview'] = $norealaleview;
        $pub_array[0]['members'] = $member_array;
        $this->load->view('pubBeers_view', $pub_array[0] );

        // get the pub beers and ciders as an array
        $pubbeers_array = $this->pub_model->getPubBeers_array( $pubid, $year );
		
		// get beer styles array
        $beerstyles = $this->pub_model->getBeerStyles_array();
	
        // load Beers
        $this->load->view('pubBeerHeading_view', array( 'beerCider' => 'Beers' ) );
        foreach ($pubbeers_array as $pubbeer) {
            if ($pubbeer['BeerCiderPerry'] == 'B') {
                // load the view, passing the beer details
                $this->load->view('pubBeer_view', array( 'pubbeer' => $pubbeer, 'beerCider' => 'Beers', 'beerStyles' => $beerstyles ) );
            }
        }
        $this->load->view('pubBeerFooter_view');
		
        // load Ciders
        $this->load->view('pubBeerHeading_view', array( 'beerCider' => 'Ciders' ) );
        foreach ($pubbeers_array as $pubbeer) {
            if ($pubbeer['BeerCiderPerry'] != 'B') {
                // load the view, passing the beer details
                $this->load->view('pubBeer_view', array( 'pubbeer' => $pubbeer, 'beerCider' => 'Ciders', 'beerStyles' => $beerstyles ) );
            }
        }
        $this->load->view('pubBeerFooter_view');

        // load the view to add a new beer
        $this->load->view('pubBeerAdd_view', array( 'pubid' => $pubid, 'beerStyles' => $beerstyles ) );
    }

    public function updatePubVolunteer()
    {
        $pubid = $this->input->post('pubid', TRUE);
        $year = $this->input->post('year', TRUE);

        $memberNo = $this->input->post('memberno', TRUE);
        $memberName = $this->input->post('membername', TRUE);
        $noRealAle = $this->input->post('norealale', TRUE);

        $row['PubID'] = $pubid;
        $row['SurveyYear'] = $year;
        if ($memberNo !== FALSE) {
			$memberNo = preg_replace("/[^0-9]+/", "", $memberNo);
			$row['MemberNo'] = $memberNo;
        }
        if ($memberName !== FALSE) {
            $memberName = preg_replace("/[^a-zA-Z ]+/", "", $memberName);
			if ($memberName !== '') {
				$row['MemberName'] = ucwords($memberName);
			}
        }
        if ($noRealAle !== FALSE) { $row['NoRealAle'] = $noRealAle; }

        // load the pub model
        $this->load->model('pub_model','',TRUE);
        // update the pub details
        $this->pub_model->updatePubVolunteer( $pubid, $year, $row);
        // rebuild the pub HTML
        $this->showPub();
        // get the pub details as an array
//        $pub_array = $this->pub_model->getPub_array( $pubid, $year );
//        // load the 'no real ale' view, passing the pub details
//        $this->load->view('pubBeersNoRealAle_view', $pub_array[0] );
    }

    public function addPubBeer()
    {
        // get the POST variables and clean / fix
        $pubID = $this->input->post('pubid', TRUE );
        $year = $this->input->post('year', TRUE );
        $beerID = $this->input->post('beerid', TRUE );
        $isBIS = $this->input->post('isbis', TRUE ); 
        $breweryName = ucwords($this->input->post('breweryname', TRUE ));
        $beerName = ucwords($this->input->post('beername', TRUE ));
        $ABV = $this->input->post('abv', TRUE );
        $beerStyle = $this->input->post('beerstyle', TRUE );
        $dispense = $this->input->post('dispense', TRUE );
        $price = $this->input->post('price', TRUE );

        $breweryName = preg_replace("/[^a-zA-Z0-9 ]+/", "", $breweryName);
        $beerName = preg_replace("/[^a-zA-Z0-9 ]+/", "", $beerName);

        // fix price
        if ($price > 20) {
            $price = $price / 100;
        }
        // load the pub model
        $this->load->model('pub_model','',TRUE);
        // add the brewery, beer, and pub beer
        $beerID = $this->pub_model->addPubBeer( $pubID, $year, $beerID, $isBIS, $breweryName, $beerName, $ABV, $beerStyle, $dispense, $price );

        // create pub beer HTML
        $pubbeers_array = $this->pub_model->getPubBeers_array( $pubID, $year, $beerID ); 
        $beerstyles = $this->pub_model->getBeerStyles_array();
        $this->load->view('pubBeer_view', array( 'pubbeer' => $pubbeers_array[0], 'beerCider' => 'Beers', 'beerStyles' => $beerstyles ) );
    }

    public function updatePubBeer()
    {
        $pubid = $this->input->post('pubid', TRUE );
        $year = $this->input->post('year', TRUE );
        $beerid = $this->input->post('beerid', TRUE );
        $brewery = $this->input->post('brewery', TRUE );
        $beer = $this->input->post('beer', TRUE );
        $abv = $this->input->post('abv', TRUE );
        $beerstyle = $this->input->post('beerstyle', TRUE );
        $price = $this->input->post('price', TRUE );
        $dispense = $this->input->post('dispense', TRUE );
        // load the pub model
        $this->load->model('pub_model','',TRUE);
        // update the pub details
        $this->pub_model->updatePubBeer( $pubid, $year, $beerid, $brewery, $beer, $abv, $beerstyle, $price, $dispense );

        // create pub beer HTML
        $pubbeers_array = $this->pub_model->getPubBeers_array( $pubid, $year, $beerid ); 
        $beerstyles = $this->pub_model->getBeerStyles_array();
        $this->load->view('pubBeer_view', array( 'pubbeer' => $pubbeers_array[0], 'beerCider' => 'Beers', 'beerStyles' => $beerstyles ) );
    }

    public function deletePubBeer()
    {
        $pubid = $this->input->post('pubid');
        $year = $this->input->post('year', TRUE );
        $beerid = $this->input->post('beerid');
        // load the pub model
        $this->load->model('pub_model','',TRUE);
        // update the pub details
        $this->pub_model->deletePubBeer( $pubid, $year, $beerid );
    }

    public function findBeers()
    {
        // get the sesrch term
        $search = $this->input->post('search');
        $pubID = $this->input->post('pubid');
        $year = $this->input->post('year', TRUE );
        // load the model
        $this->load->model('pub_model','',TRUE);
        // get the pub details as an array
        $beers = $this->pub_model->findBeers_array( $search, $pubID, $year );
        // load the view, passing the pub details
        $this->load->view('findBeers_view', $beers );
    }

}

/* End of file pubs.php */
/* Location: ./application/controllers/pubs.php */