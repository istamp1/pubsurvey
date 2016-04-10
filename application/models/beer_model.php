<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class beer_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getBeers_array( $fields = '*', $search = '', $orderby = 'b.BeerName' ){
         if( $fields == '' ) { $fields = '*'; }
         $sql = "SELECT ".$fields."
                   FROM locBeer b
                        JOIN locBrewery br on b.locBreweryID = br.Id";
         if ( $search != '' ) {
             $sql = $sql.' WHERE '.$search;
         }
         if ( $orderby != '' ) {
             $sql = $sql.' ORDER BY '.$orderby;
         }
         $query = $this->db->query($sql);
         $beers = $query->result_array();
         return array( "beers" => $beers );
    }

    public function getBeer_array( $beerid ){
         $sql = "SELECT b.*
                      , bs.StyleDescription
                   FROM locBeer b
                        LEFT JOIN locBeerStyle bs ON b.BeerStyle = bs.BeerStyle
                  WHERE Id = '$beerid'";
         $query = $this->db->query($sql);
         $row = $query->result_array();
         return $row;
    }

    public function getBeerPubs_array( $beerid, $year ) {
        $sql = "SELECT b.Id, b.BeerName, b.ABV, b.BeerStyle, b.BeerCiderPerry
                     , p.PubName, p.Address
                     , bs.StyleDescription
                  FROM locBeer b
                       JOIN locPubBeer pb ON b.Id = pb.locBeerID
                       JOIN pubdb p ON pb.PubID = p.PubID
                       LEFT JOIN locBeerStyle bs ON b.BeerStyle = bs.BeerStyle
                 WHERE b.Id = '$beerid'
		           AND pb.SurveyYear = $year";
        $sql = $sql." ORDER BY b.BeerName, p.PubName";

        $query = $this->db->query($sql);
        $beerpubs = $query->result_array();

        return $beerpubs;
    }

    public function mergeBeers( $beerIdToKeep, $beerIdToMerge ) {
        // get beer's pubs
        $beerPubsToMerge = $this->getBeerPubs_array( $beerIdToMerge );
        // change the beer id of eaach to the new beer
        foreach ($beerPubsToMerge as $beerPubToMerge) {
            $this->updatePubBeer( $beerPubToMerge['BeerID'], array( 'BeerID' => $beerIdToKeep ) );
        }
        // delete the old beer
        $this->deleteBeer($beerIdToMerge);
    }

    public function updatePubBeer( $beerID, $row ) {
        if ($beerID == '') {
            return FALSE;
        } else {
            $this->db->where('BeerID', $beerID );
            $this->db->update('locPubBeer', $row );
            return TRUE;
        }
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

    public function deleteBeer( $beerId ) {
        $this->db->where('BeerID', $beerId );
        $this->db->delete('locBeer');
        return TRUE;
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>