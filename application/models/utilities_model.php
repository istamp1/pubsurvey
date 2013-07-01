<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class utilities_model extends CI_Model {

    public function getStats_array() {
         $sql = "SELECT count(*) as 'total'
                      , ( SELECT count(*) FROM locBeer WHERE beerstyle NOT IN ( 'CI', 'PE' ) ) as 'unique'
                      , ( SELECT count( DISTINCT pubid ) FROM locPubBeer ) as 'surveyedsome'
                      , sum( IFNULL( pv.norealale, 0 ) ) as 'surveyednone'
                   FROM pubdb p
                        LEFT JOIN locPubVolunteer pv ON p.pubid = pv.pubid
                        JOIN userfields u ON p.pubid = u.pubid
                                          AND 'Norwich' = u.pubsurvey
                  WHERE p.ptype = 'P' AND  p.pstat = 'O'";
         $query = $this->db->query($sql);
         $row = $query->result_array();
         return $row;
    }
}

?>

