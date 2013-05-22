<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pub_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getPubs_array( $fields, $search, $orderby ){        
         if( $fields == '' ) { $fields = '*'; }         
         $sql = "SELECT ".$fields."
                   FROM pubdb p 
                        LEFT JOIN locPubVolunteer pv ON p.pubid = pv.pubid
                        LEFT JOIN ( SELECT pubid, count(*) BeerCount FROM locPubBeer GROUP BY pubid) pb ON p.pubid = pb.pubid
                        JOIN userfields u ON p.pubid = u.pubid 
                                         AND 'Norwich' = u.pubsurvey";
         if ( $search != '' ) {
             $sql = $sql.' WHERE '.$search;
             $sql = $sql.'   AND p.ptype = \'P\' AND  p.pstat = \'O\'';
         } else {
             $sql = $sql.' WHERE p.ptype = \'P\' AND  p.pstat = \'O\'';
         }
         if ( $orderby != '' ) {
             $sql = $sql.' ORDER BY '.$orderby;
         }                       
         $query = $this->db->query($sql);
         $pubs = $query->result_array();         
         return array( "pubs" => $pubs );
    }

    public function getPub_array( $pubid ){        
         $sql = "SELECT p.*, u.NoThe, IFNULL( pb.beercount, 0 ) BeerCount
                      , IFNULL( pv.norealale, 0 ) NoRealAle
                      , IFNULL( pv.MemberNo, 0 ) MemberNo
                      , IFNULL( pv.MemberName, '' ) MemberName
                   FROM pubdb p 
                        LEFT JOIN locPubVolunteer pv ON p.pubid = pv.pubid
                        LEFT JOIN ( SELECT pubid, count(*) BeerCount 
                                      FROM locPubBeer GROUP BY pubid) pb ON p.pubid = pb.pubid
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
    
    public function getPubBeers_array( $pubid, $beerID = 0 ) {
        $sql = "SELECT pb.PubID, pb.BeerID, pb.Dispense, d.DispenseDescription, pb.Price
                     , b.BreweryID, b.BeerName, b.ABV, b.BeerStyle, b.BeerCiderPerry
                     , br.BreweryName
                     , bs.StyleDescription
                  FROM locPubBeer pb
                       JOIN locBeer b ON pb.BeerID = b.BeerID
                       JOIN locBrewery br ON b.BreweryID = br.BreweryID
                       LEFT JOIN locBeerStyle bs ON b.BeerStyle = bs.BeerStyle
                       JOIN locDispense d ON pb.Dispense = d.Dispense 
                 WHERE pb.PubID = '$pubid'";
        // add Beer ID if specified
        if ($beerID != 0) { $sql = $sql." AND pb.BeerID = $beerID"; }
        $sql = $sql." ORDER BY br.BreweryName, b.BeerName";  
        
        $query = $this->db->query($sql);
        $pubbeers = $query->result_array();     
        
        return $pubbeers;
    }
    
    public function findBeers_array( $search, $pubID ) { 
        $sql = "SELECT b.BeerID
                     , CONCAT( br.BreweryName, ', ', b.BeerName, ', ', b.ABV, '%'
                             , ', ', IF( b.BeerCiderPerry = 'B' 
                                       , b.BeerStyle
                                       , IF( b.BeerCiderPerry = 'C'
                                           , 'CI'
                                           , 'PE' )
                                       ) 
                             ) 'Beer'
                     , count(*) AS 'PubCount'
                  FROM locBeer b 
                       JOIN locBrewery br ON b.BreweryID = br.BreweryID
                       JOIN locPubBeer pb ON b.BeerID = pb.BeerID 
                       LEFT JOIN locPubBeer pbThis ON '$pubID' = pbThis.PubID AND b.BeerID = pbThis.BeerID 
                 WHERE ( UCASE( br.BreweryName ) LIKE UCASE(  '%$search%' )
                         OR UCASE( b.BeerName ) LIKE UCASE( '%$search%' ) )
                   AND pbThis.PubID IS NULL 
                 GROUP BY b.BeerID
                 ORDER BY 3 DESC
                 LIMIT 10";  
//        var_dump($sql); 
         $query = $this->db->query($sql);
         $beers = $query->result_array();     
        //var_dump($beers);  
         return array( "beers" => $beers );
    }
    
    public function addPubBeer( $pubID, $beerID, $breweryName, $beerName, $ABV, $beerStyle, $dispense, $price ) {
        if ($beerID == '') {
            $breweryID = $this->addBreweryByName( $breweryName );             
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
            $beerID = $this->addBeerByNameAndBrewery( $beerCiderPerry, $beerName, $breweryID, $ABV, $beerStyle );
        } else {
            // update ABV and Style
            $this->updateBeer( $beerID, array( 'ABV' => $ABV, 'BeerStyle' => $beerStyle ) );  
        }
        // add beer to pub
        $row = array( 'PubID' => $pubID, 'SurveyYear' => '2013'
                    , 'BeerID' => $beerID, 'Dispense' => $dispense, 'Price' => $price ); 
        $this->db->insert('locPubBeer', $row );
        // and ensure No Real Ale flag not set 
        $row = array( 'PubID' => $pubID, 'NoRealAle' => 0 );
        $this->updatePubVolunteer( $pubID, $row );  
        return $beerID; 
    }     
    
    public function updateBeer( $beerID, $row ) {
        if ($beerID == '') {
            return FALSE;
        } else {
            $this->db->where('BeerID', $beerID );        
            $this->db->update('locBeer', $row );  
            return TRUE;
        } 
    }
    
    public function addPubVolunteer( $row ) {  
        return $this->db->insert('locPubVolunteer', $row );   
    }
    
    public function updatePubVolunteer( $pubID, $row ) { 
        if ($pubID == '') {
            return FALSE;
        } else {    
            $sql = "SELECT *
                      FROM locPubVolunteer
                     WHERE PubID = '$pubID'";         
            $query = $this->db->query($sql);         
            $rows = $query->result_array(); 
            if (count($rows) > 0 ) {
                $this->db->where('PubID', $pubID );    
                $this->db->update('locPubVolunteer', $row );  
            } else {
                $newrow = array( 'PubID' => $pubID, 'SurveyYear' => '2013'
                               , 'MemberNo' => 0, 'MemberName' => '', 'NoRealAle' => 0);                
//                var_dump($row);
                $row = array_merge($newrow, $row);
//                var_dump($row);
                $this->db->insert('locPubVolunteer', $row );  
            }
            return TRUE;   
        } 
    }
    
    public function deletePubBeer( $pubid, $beerid ) {
        $this->db->where('PubID', $pubid );        
        $this->db->where('BeerID', $beerid );  
        $this->db->delete('locPubBeer');
        return TRUE;
    }
    
    public function addBreweryByName( $breweryName ) {       
         $rows = $this->getBreweryByName_array( $breweryName );
         if (count($rows) == 0) {
            $newrow = array( 'BreweryName' => $breweryName );
            $this->db->insert('locBrewery', $newrow );
            $rows = $this->getBreweryByName_array( $breweryName );
         };
         $breweryID = $rows[0]['BreweryID']; 
         return $breweryID;    
    }
    
    public function getBreweryByName_array( $breweryName ) {       
         $sql = "SELECT *
                   FROM locBrewery
                  WHERE BreweryName = '$breweryName'";         
         $query = $this->db->query($sql);         
         $rows = $query->result_array();
         return $rows;    
    }
    
    public function addBeerByNameAndBrewery( $beerCiderPerry, $beerName, $breweryID, $ABV, $beerStyle ) {  
        // get beer
        $rows = $this->getBeerByNameAndBrewery_array( $beerName, $breweryID ); 
        if (count($rows) == 0) {
            // doesn't exist so add it
            $newrow = array( 'BreweryID' => $breweryID, 'BeerCiderPerry' => $beerCiderPerry, 'BeerName' => $beerName, 'ABV' => $ABV, 'BeerStyle' => $beerStyle );
            $this->db->insert('locBeer', $newrow );
            // get beerID
            $rows = $this->getBeerByNameAndBrewery_array( $beerName, $breweryID ); 
            $beerID = $rows[0]['BeerID'];  
        } else {
            // exists - update ABV and Style
            $beerID = $rows[0]['BeerID'];  
            $this->updateBeer( $beerID, array( 'ABV' => $ABV, 'BeerStyle' => $beerStyle ) );  
        };
        return $beerID;    
    }
    
    public function getBeerByNameAndBrewery_array( $beerName, $breweryID ) {       
         $sql = "SELECT *
                   FROM locBeer
                  WHERE BreweryID = $breweryID
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

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
