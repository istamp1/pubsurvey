<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pub_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getPubs_array( $fields, $search, $orderby, $year ){
         if( $fields == '' ) { $fields = '*'; }
         $sql = "SELECT ".$fields."
                   FROM pubdb p
                        LEFT JOIN locPubVolunteer pv ON p.pubid = pv.pubid AND $year = pv.surveyyear
						LEFT JOIN locMember m ON pv.memberno = m.memberno
                        LEFT JOIN ( SELECT pubid, count(*) BeerCount
                                      FROM locPubBeer
                                     WHERE surveyyear = $year
                                     GROUP BY pubid ) pb ON p.pubid = pb.pubid
                        JOIN userfields u ON p.pubid = u.pubid
                                         AND 'Norwich' = u.pubsurvey
						JOIN locSurvey s ON $year = s.surveyyear
				  WHERE ( p.pstat = 'O' OR s.surveydate < DATE(NOW()) ) ";
         if ( $search != '' ) {
             $sql = $sql.' AND '.$search;
         }
         if ( $orderby != '' ) {
             $sql = $sql.' ORDER BY '.$orderby;
         }
//	 var_dump($sql);
//	 die;
         $query = $this->db->query($sql);
         $pubs = $query->result_array();
         return array( "pubs" => $pubs );
    }

    public function getPub_array( $pubid, $year ){
         $sql = "SELECT p.*, u.NoThe, IFNULL( pb.beercount, 0 ) BeerCount
                      , IFNULL( pv.norealale, 0 ) NoRealAle
                      , IFNULL( pv.MemberNo, 0 ) MemberNo
                      , IFNULL( m.MemberName, IFNULL( pv.MemberName, '' ) ) MemberName
		      , IFNULL( m.ContactDetails, '' ) MemberEmail
                   FROM pubdb p
                        LEFT JOIN locPubVolunteer pv ON p.pubid = pv.pubid AND $year = pv.surveyyear
                        LEFT JOIN locMember m ON pv.MemberNo = m.MemberNo
                        LEFT JOIN ( SELECT pubid, count(*) BeerCount
                                      FROM locPubBeer
                                     WHERE surveyyear = $year
                                     GROUP BY pubid ) pb ON p.pubid = pb.pubid
                        JOIN userfields u ON p.pubid = u.pubid
                  WHERE p.pubid = '$pubid'";
         $query = $this->db->query($sql);
         $row = $query->result_array();
         return $row;
    }

    public function getBeerStyles_array() {
        $sql = "SELECT *
                  FROM locBeerStyle
                 ORDER BY StyleDescription";
         $query = $this->db->query($sql);
         $styles = $query->result_array();
         return $styles;
    }

    public function getPubBeers_array( $pubid, $year, $beerID = 0 ) {
        $sql = "SELECT pb.PubID, b.Id BeerId, pb.Dispense, d.DispenseDescription, pb.Price
                     , br.Id BreweryId, b.BeerName, b.ABV, b.BeerStyle, b.BeerCiderPerry
                     , br.BreweryName, bs.StyleDescription, IF( b.BeerId = 0, 0, 1 ) isBIS
                  FROM locPubBeer pb
                       JOIN locBeer b ON pb.locBeerId = b.Id
                       JOIN locBrewery br ON b.locBreweryId = br.Id
                       LEFT JOIN locBeerStyle bs ON b.BeerStyle = bs.BeerStyle
                       JOIN locDispense d ON pb.Dispense = d.Dispense
                 WHERE pb.PubID = '$pubid' AND pb.surveyyear = $year";
        // add Beer ID if specified
        if ($beerID != 0) { $sql = $sql." AND pb.locBeerId = $beerID"; }
        $sql = $sql." ORDER BY br.BreweryName, b.BeerName";

        $query = $this->db->query($sql);
        $pubbeers = $query->result_array();

        return $pubbeers;
    }

    public function findBeers_array( $search, $pubID, $year ) {
	$sql="SELECT *, SUM(Found) Pubs
		   FROM (
              SELECT 1 as BIS, CAST(b.beerid AS CHAR) Id
                   , CAST(br.BreweryName AS CHAR) BreweryName, b.BeerName, CAST(b.beerabv AS CHAR) ABV
                   , 'B' AS BeerCiderPerry, lb.BeerStyle, IF( pb.locBeerId IS NULL, 0, 1 ) Found
                  FROM beers b
                       JOIN brewery br ON b.brewerycode = br.brewerycode
                       LEFT JOIN locBeer lb ON b.beerid = lb.BeerId
                       LEFT JOIN locPubBeer pb ON lb.Id = pb.locBeerId AND $year = pb.SurveyYear
                       LEFT JOIN locPubBeer pbThis ON '$pubID' = pbThis.PubID
                                                  AND $year = pbThis.SurveyYear
                                                  AND lb.Id = pbThis.locBeerID
                 WHERE UCASE( CONCAT( br.BreweryName, b.BeerName ) ) LIKE UCASE( REPLACE( '%$search%', ' ', '%' ) )
                   AND pbThis.PubID IS NULL
              UNION ALL
              SELECT 0 as BIS, CAST(b.Id AS CHAR) Id
                   , CAST(br.BreweryName AS CHAR) BreweryName, b.BeerName, CAST(b.ABV AS CHAR)
                   , b.BeerCiderPerry, b.BeerStyle, IF( pb.locBeerId IS NULL, 0, 1 )
                FROM locBeer b
                     JOIN locBrewery br ON b.locBreweryId = br.Id
                     LEFT JOIN locPubBeer pb ON b.Id = pb.locBeerId AND $year = pb.SurveyYear
                     LEFT JOIN locPubBeer pbThis ON '$pubID' = pbThis.PubID
                                                AND $year = pbThis.SurveyYear
                                                AND b.Id = pbThis.locBeerID
               WHERE UCASE( CONCAT( br.BreweryName, b.BeerName ) ) LIKE UCASE( REPLACE( '%$search%', ' ', '%' ) )
                 AND pbThis.PubID IS NULL
		 AND b.BeerId = 0
			) SQ
            GROUP BY 1, 2
            ORDER BY 9 DESC, 3, 4
            LIMIT 20";
		
//		var_dump($sql);
//		die;

         $query = $this->db->query($sql);
		 $beers = $query->result_array();
         return array( "beers" => $beers );
    }

    public function addPubBeer( $pubID, $year, $beerID, $isBIS, $breweryName, $beerName, $ABV, $beerStyle, $dispense, $price ) {
//	var_dump(array( $pubID, $year, $beerID, $isBIS, $breweryName
//	    , $beerName, $ABV, $beerStyle, $dispense, $price ));
//	die;
	if ($beerID == '' or $isBIS == 1) {
	    // new beer - add to 'loc' tables
	    $tableName = 'locBrewery';
	    if($isBIS == 1) {
		    $tableName = 'brewery';
	    }
            $breweryID = $this->addBreweryByName( $breweryName, $tableName );
            switch ($beerStyle) {
                case "CI":
                    $beerCiderPerry = 'C';
                    $beerStyle = '';
                    break;
                case "PE":
                    $beerCiderPerry = 'P';
                    $beerStyle = '';
                    break;
                default:
                   $beerCiderPerry = 'B';
            }
           $beerID = $this->addBeerByNameAndBrewery( $beerID, $isBIS, $beerCiderPerry, $beerName, $breweryID, $ABV, $beerStyle );
        } else {
	    // existing  'local' beer - update ABV and Style
	    $this->updateBeer( $beerID, array( 'ABV' => $ABV, 'BeerStyle' => $beerStyle ) );
        }

        // add beer to pub
        $row = array( 'PubID' => $pubID, 'SurveyYear' => $year
                    , 'locBeerID' => $beerID, 'Dispense' => $dispense, 'Price' => $price );
        $this->db->insert('locPubBeer', $row );

        // and ensure No Real Ale flag not set
        $row = array( 'PubID' => $pubID, 'NoRealAle' => 0 );
        $this->updatePubVolunteer( $pubID, $year, $row );
        return $beerID;
    }

    public function updateBrewery( $breweryId, $row ) {
        if ($breweryId == '' or $breweryId =0) {
            return false;
        } else {
            $this->db->where('Id', $breweryId );
            $this->db->update('locBrewery', $row );
            return true;
        }
    }

    public function updateBeer( $beerID, $row ) {
        if ($beerID == '') {
            return false;
        } else {
            $this->db->where('Id', $beerID );
            $this->db->update('locBeer', $row );
            return true;
        }
    }

    public function addPubVolunteer( $row ) {
        return $this->db->insert('locPubVolunteer', $row );
    }

    public function updatePubVolunteer( $pubID, $year, $row ) {
        if ($pubID == '') {
            return FALSE;
        } else {
			if(isset($row['MemberName']) and isset($row['MemberNo'])) {
				if($row['MemberName'] != '' and $row['MemberNo'] != 0) {
					$memberno = $row['MemberNo'];
					$sql = "SELECT *
							FROM locMember
						   WHERE MemberNo = $memberno";
					$query = $this->db->query($sql);
					$rows = $query->result_array();
					if (count($rows) > 0 ) {
						$this->db->where('MemberNo', $memberno );
						$this->db->update('locMember', array( 'MemberName' => $row['MemberName']) );
					} else {
						$newrow = array( 'MemberNo' => $memberno, 'MemberName' => $row['MemberName']);
						$this->db->insert('locMember', $newrow );
					}
				}
			}

			if(isset($row['MemberNo'])) {
				if($row['MemberNo'] != 0) {
					$memberno = $row['MemberNo'];
					$sql = "SELECT *
							FROM locVolunteerYear
						   WHERE MemberNo = $memberno AND SurveyYear = $year";
					$query = $this->db->query($sql);
					$rows = $query->result_array();
					if (count($rows) == 0 ) {
						$newrow = array( 'MemberNo' => $memberno, 'SurveyYear' => $year);
						$this->db->insert('locVolunteerYear', $newrow );
					}
				}
			}

            $sql = "SELECT *
                      FROM locPubVolunteer
                     WHERE PubID = '$pubID' AND SurveyYear = $year";
            $query = $this->db->query($sql);
            $rows = $query->result_array();
            if (count($rows) > 0 ) {
                $this->db->where('PubID', $pubID );
                $this->db->where('SurveyYear', $year );
                $this->db->update('locPubVolunteer', $row );
            } else {
                $newrow = array( 'PubID' => $pubID, 'SurveyYear' => $year
                               , 'MemberNo' => 0, 'MemberName' => '', 'NoRealAle' => 0);
                $row = array_merge($newrow, $row);
                $this->db->insert('locPubVolunteer', $row );
            }

            return TRUE;
        }
    }

    public function updatePubBeer( $pubId, $year, $beerId, $breweryName, $beerName, $abv, $beerStyle, $price, $dispense ) {
	
//	var_dump(array($pubId,$year,$beerId,$breweryName,$beerName,$abv,$beerStyle,$price,$dispense)); 
	
	$beer = $this->getBeerByID_array($beerId);
	if(count($beer) == 0) { return false; }
	
	// beer exists 
	if($beer[0]['BeerId'] == 0) {
	    // not a BIS beer - OK to update beer / brewery
	    $locBreweryId = $beer[0]['locBreweryId'];
	    $brewery = $this->getBreweryById_array($locBreweryId);
	    if(count($brewery) > 0) {
		$breweryCode = $brewery[0]['BreweryCode'];
		$oldBreweryName = $brewery[0]['BreweryName'];
		if($breweryCode == '' 
		and $breweryName != $oldBreweryName) {
		    // no Brewery Code, so OK to update BreweryName
		    $row = array('BreweryName' => $breweryName);
		    $this->db->where('Id', $locBreweryId );
		    $this->db->update('locBrewery', $row);
		}
	    }
	    // update beer
	    $row = array( 'BeerName' => $beerName, 'ABV' => $abv, 'BeerStyle' => $beerStyle );
	    $this->db->where('Id', $beerId );
	    $this->db->update('locBeer', $row);
	}
	
	$row = array( 'Price' => $price, 'Dispense' => $dispense );
        $this->db->where('PubID', $pubId );
        $this->db->where('SurveyYear', $year );
        $this->db->where('locBeerId', $beerId );
        $this->db->update('locPubBeer', $row);
	
        return true;
	
    }

    public function deletePubBeer( $pubid, $year, $beerid ) {
        $this->db->where('PubID', $pubid );
        $this->db->where('SurveyYear', $year );
        $this->db->where('locBeerId', $beerid );
        $this->db->delete('locPubBeer');
        return true;
    }

    public function addBreweryByName( $breweryName, $tableName ) {
	$breweryCode = '';
	if($tableName == 'brewery') {
	    // search BIS table first to get BreweryCode
	    $rows = $this->getBreweryByName_array( $breweryName, 'brewery' );
	    if (count($rows) > 0) {
		$breweryCode = $rows[0]['brewerycode'];
	    }
	}
	// check whether already exists
	$rows = $this->getBreweryByName_array( $breweryName, 'locBrewery' );
	if (count($rows) == 0) {
	    // doesn't exist 
	    $newrow = array( 'BreweryCode' => $breweryCode, 'BreweryName' => $breweryName );
	    $this->db->insert( 'locBrewery', $newrow );
	    $rows = $this->getBreweryByName_array( $breweryName, 'locBrewery' );
	};
	$breweryID = $rows[0]['Id'];
	return $breweryID;
    }

    public function getBreweryById_array( $breweryId ) {
         $sql = "SELECT *
                   FROM locBrewery
                  WHERE Id = $breweryId";
         $query = $this->db->query($sql);
         $rows = $query->result_array();
         return $rows;
    }

    public function getBreweryByName_array( $breweryName, $tableName ) {
         $sql = "SELECT *
                   FROM $tableName
                  WHERE BreweryName = '$breweryName'";
         $query = $this->db->query($sql);
         $rows = $query->result_array();
         return $rows;
    }

    public function getBreweryByCode_array( $breweryCode ) {
         $sql = "SELECT *
                   FROM locBrewery
                  WHERE BreweryCode = '$breweryCode'";
         $query = $this->db->query($sql);
         $rows = $query->result_array();
         return $rows;
    }

    public function addBeerByNameAndBrewery( $beerID, $isBIS, $beerCiderPerry, $beerName, $breweryID, $ABV, $beerStyle ) {
        // get beer
	if($isBIS == 1) {
	    $rows = $this->getBeerByBeerId_array( $beerID );
	} else {
	    $rows = $this->getBeerByNameAndBreweryID_array( $beerName, $breweryID );
	}
	// check whether exists
        if (count($rows) == 0) {
            // doesn't exist so add it
	    if($beerID == '') { $beerID = '0'; }
            $newrow = array( 'locBreweryID' => $breweryID, 'BeerId' => $beerID
			, 'BeerCiderPerry' => $beerCiderPerry, 'BeerName' => $beerName
		        , 'ABV' => $ABV, 'BeerStyle' => $beerStyle );
            $this->db->insert('locBeer', $newrow );
            // get beerID
	    if($isBIS == 1) {
		$rows = $this->getBeerByBeerId_array( $beerID );
	    } else {
		$rows = $this->getBeerByNameAndBreweryID_array( $beerName, $breweryID );
	    }
            $beerID = $rows[0]['Id'];
        } else {
            // exists - update ABV and Style
            $beerID = $rows[0]['Id'];
            $this->updateBeer( $beerID, array( 'ABV' => $ABV, 'BeerStyle' => $beerStyle ) );
        };
        return $beerID;
    }

    public function getBeerByBeerId_array( $beerID ) {
         $sql = "SELECT *
                   FROM locBeer
                  WHERE BeerID = $beerID";
         $query = $this->db->query($sql);
         $rows = $query->result_array();
         return $rows;
    }

    public function getBeerById_array( $id ) {
         $sql = "SELECT *
                   FROM locBeer
                  WHERE Id = $id";
         $query = $this->db->query($sql);
         $rows = $query->result_array();
         return $rows;
    }

    public function getBeerByNameAndBreweryID_array( $beerName, $breweryID ) {
         $sql = "SELECT *
                   FROM locBeer
                  WHERE locBreweryID = $breweryID
                    AND BeerName = '$beerName'";
         $query = $this->db->query($sql);
         $rows = $query->result_array();
         return $rows;
    }

    public function updatePub( $pubid, $data ){
        $this->db->where('PubID', $pubid );
        $this->db->update('pubdb XXX', $data );
        return TRUE;
    }
}

?>
