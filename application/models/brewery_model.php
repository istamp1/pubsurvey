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
                  WHERE BreweryID = '$breweryid'";
         $query = $this->db->query($sql);
         $row = $query->result_array();
         return $row;
    }

    public function getBreweryBeers_array( $breweryid ) {
        $sql = "SELECT b.BeerID
                     , b.BreweryID, b.BeerName, b.ABV, b.BeerStyle, b.BeerCiderPerry
                     , br.BreweryName
                     , bs.StyleDescription
                  FROM locBeer b
                       JOIN locBrewery br ON b.BreweryID = br.BreweryID
                       LEFT JOIN locBeerStyle bs ON b.BeerStyle = bs.BeerStyle
                 WHERE b.BreweryID = '$breweryid'
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
            $this->updateBeer( $breweryBeerToMerge['BeerID'], array( 'BreweryID' => $breweryIdToKeep ) );
        }
        // delete the old brewery
        $this->deleteBrewery($breweryIdToMerge);
    }

    public function mergeBreweryBeers( $beerIdToKeep, $beerIdToMerge ) {
        $this->updatePubBeer( $beerIdToMerge, array( 'BeerID' => $beerIdToKeep ) );
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

    public function deleteBrewery( $breweryId ) {
        $this->db->where('BreweryID', $breweryId );
        $this->db->delete('locBrewery');
        return TRUE;
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

