<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class utilities_model extends CI_Model {

    public function getStats_array($year = null) {
		if($year == null) {
			$year = new DateTime();
			$year = $year->format('Y');
		}
         $sql = "SELECT COUNT(*) as 'total'
                      , ( SELECT COUNT(DISTINCT locBeerId)
                            FROM locBeer b JOIN locPubBeer pb ON b.Id = pb.locBeerId
                           WHERE b.beerstyle NOT IN ( 'CI', 'PE' ) AND beerciderperry = 'B'
                             AND pb.SurveyYear = $year
                        ) AS 'unique'
                      , ( SELECT count( DISTINCT pubid ) FROM locPubBeer WHERE SurveyYear = $year
                        ) AS 'surveyedsome'
                      , SUM( IFNULL( pv.norealale, 0 ) ) AS 'surveyednone'
                   FROM pubdb p
                        LEFT JOIN locPubVolunteer pv ON p.pubid = pv.pubid AND $year = pv.surveyyear
                        JOIN userfields u ON p.pubid = u.pubid
                                          AND 'Norwich' = u.pubsurvey
                  WHERE p.ptype = 'P' AND  p.pstat = 'O'
                    AND pv.SurveyYear = $year";
         $query = $this->db->query($sql);
         $row = $query->result_array();
         return $row;
    }

    public function getVolunteers($year) {
	$sql = "SELECT m.*
		  FROM locVolunteerYear l
		       JOIN locMember m ON l.memberno = m.memberno
		 WHERE l.surveyyear = $year 
	         ORDER BY m.MemberName";
	$query = $this->db->query($sql);
	$row = $query->result_array();
	return $row;
    }

    public function getVolunteerEmails($year) {
	$sql = "SELECT GROUP_CONCAT( CAST( contactdetails AS CHAR ) ) 'Bcc'
		  FROM locVolunteerYear l
		       JOIN locMember m ON l.memberno = m.memberno
		 WHERE surveyyear = $year";
	$query = $this->db->query($sql);
	$row = $query->result_array();
	return $row;
    }

    public function getMembers() {
	$sql = "SELECT *
		  FROM locMember l
		 ORDER BY MemberName";
	$query = $this->db->query($sql);
	$rows = $query->result_array();
	return $rows;
    }
}

?>

