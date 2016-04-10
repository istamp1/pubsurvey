<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class brewery_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getBreweries_array( $fields = '*', $search = '', $orderby = 'br.BreweryName' ){
         if( $fields == '' ) { $fields = '*'; }
         $sql = "SELECT ".$fields."
                   FROM locBrewery br";
         if ( $search != '' ) {
             $sql = $sql.' WHERE '.$search;
         }
         if ( $orderby != '' ) {
             $sql = $sql.' ORDER BY '.$orderby;
         }
         $query = $this->db->query($sql);
         $breweries = $query->result_array();
         return array( "breweries" => $breweries );
    }

    public function getBrewery_array( $breweryid ){
         $sql = "SELECT *
                   FROM locBrewery
                  WHERE Id = '$breweryid'";
         $query = $this->db->query($sql);
         $row = $query->result_array();
         return $row;
    }

    public function getBeer_array( $beerid ){
         $sql = "SELECT *
                   FROM locBeer
                  WHERE Id = '$beerid'";
         $query = $this->db->query($sql);
         $row = $query->result_array();
         return $row;
    }

    public function getBreweryBeers_array( $breweryid ) {
        $sql = "SELECT b.Id locBeerId, b.BeerId bisBeerId
                     , b.locBreweryId, b.BeerName, b.ABV, b.BeerStyle, b.BeerCiderPerry
                     , br.BreweryName, bs.StyleDescription
                  FROM locBeer b
                       JOIN locBrewery br ON b.locBreweryID = br.Id
                       LEFT JOIN locBeerStyle bs ON b.BeerStyle = bs.BeerStyle
                 WHERE b.locBreweryID = '$breweryid'
                 ORDER BY br.BreweryName, b.BeerName";
        $query = $this->db->query($sql);
        $brewerybeers = $query->result_array();

        return $brewerybeers;
    }

    public function mergeBreweries( $breweryIdToKeep, $breweryIdToMerge ) {
        // get brewery's beers
        $breweryBeersToMerge = $this->getBreweryBeers_array( $breweryIdToMerge );
        // change the brewery id of eaach to the new brewery
        foreach ($breweryBeersToMerge as $breweryBeerToMerge) {
            $this->updateBeer( $breweryBeerToMerge['locBeerId'], array( 'locBreweryId' => $breweryIdToKeep ) );
        }
        // delete the old brewery
        $this->deleteBrewery($breweryIdToMerge);
    }

    public function updateBeer( $beerId, $row ) {
        if ($beerId == '' or $beerId == 0) {
            return FALSE;
        } else {
            $this->db->where('Id', $beerId );
            $this->db->update('locBeer', $row );
            return TRUE;
        }
    }

    public function deleteBrewery( $breweryId ) {
        $this->db->where('Id', $breweryId );
        $this->db->delete('locBrewery');
        return TRUE;
    }

    public function mergeBeers($mergeBeerId, $intoBeerId) {
        // get brewery's beers
        $this->updatePubBeer( $mergeBeerId, array( 'locBeerId' => $intoBeerId ) ); 
        // delete the old brewery
        $this->deleteBeer($mergeBeerId);
    }

    public function updatePubBeer( $beerId, $row ) {
        if ($beerId == '' or $beerId == 0) {
            return FALSE;
        } else {
            $this->db->where('locBeerId', $beerId );
            $this->db->update('locPubBeer', $row );
            return TRUE;
        }
    }

    public function deleteBeer( $beerId ) {
        $this->db->where('Id', $beerId );
        $this->db->delete('locBeer');
        return TRUE;
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

