<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Data_model extends MY_Model
{
    protected $table = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function getICOResult()
    {
        $sql = "Select ICOName,StartDate,EndDate,TotalUSD,ProjCatName,Link
                  from tbl_icohistory H
                  left join tbl_project_categories C on H.Category = C.ProjCatID
                  Where TotalUSD > 0 and EndDate <> '0000-00-00'
                  Order By EndDate DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getStatsResult($year)
    {
        $year = $this->db->escape($year);
        $arrResult = [];
        $dateGrouping = 'EndDate';
        $sqlMonth = "SELECT MONTH($dateGrouping) as Month,SUM(TotalUSD) as Total 
                      FROM `tbl_icohistory` 
                      WHERE YEAR($dateGrouping) = $year and ID <> 112 
                      GROUP BY MONTH($dateGrouping) 
                      Order BY MONTH($dateGrouping)";
        $query = $this->db->query($sqlMonth);
        $months = $query->result_array();
        $arrResult['months'] = $months;

        $sqlNumOfICO = "SELECT COUNT(*) as Total FROM `tbl_icohistory` WHERE YEAR($dateGrouping) = $year and ID <> 112";
        $query = $this->db->query($sqlNumOfICO);
        $numOfICO = $query->result_array();
        $arrResult['numOfICO'] = $numOfICO[0]['Total'];

        $sqlTopTen = "SELECT ICOName, TotalUSD as Total, Link  from `tbl_icohistory` WHERE YEAR($dateGrouping) = $year and ID <> 112 Order by TotalUSD DESC LIMIT 10";
        $query = $this->db->query($sqlTopTen);
        $arrResult['topTen'] = $query->result_array();

        $sqlICOCate = "Select ProjCatName,SUM(TotalUSD) as Total from tbl_icohistory H inner join tbl_project_categories C on H.Category = C.ProjCatID WHERE YEAR($dateGrouping) = $year and ID <> 112 and TotalUSD <> 0 GROUP BY Category Order By Total DESC";
        $query = $this->db->query($sqlICOCate);
        $arrResult['icoCats'] = $query->result_array();

        return $arrResult;
    }

    public function getListings($ProjUsers)
    {
        $sql = "SELECT S.SubEventName,PAY.PayID,ProjID,S.SubID,ProjImage,ProjName,S.SubHashCode,ProjICORank,ProjDeleted,S.SubCoinName,S.SubStatus FROM tbl_submissions S
          LEFT JOIN tbl_projects P ON S.SubProjID = P.ProjID
          LEFT JOIN tbl_payments PAY ON S.SubID = PAY.SubID
          WHERE S.SubUsers = ?
          UNION DISTINCT
          SELECT S.SubEventName,PAY.PayID,ProjID,S.SubID,ProjImage,ProjName,S.SubHashCode,ProjICORank,ProjDeleted,S.SubCoinName,S.SubStatus FROM tbl_submissions S
          RIGHT JOIN tbl_projects P ON S.SubProjID = P.ProjID
          LEFT JOIN tbl_payments PAY ON S.SubID = PAY.SubID
          WHERE P.ProjUsers = ?";
        $query = $this->db->query($sql, array($ProjUsers, $ProjUsers));
        return $query->result_array();
    }

    public function getFeaturedPost($projectID)
    {
        $dbBlog = $this->load->database('blog', TRUE);
        $sql = "SELECT P.ID, P.post_date, P.post_title, P.post_name, P.post_content
                  , REPLACE( REPLACE( REPLACE( REPLACE( wpo.option_value, '%year%', DATE_FORMAT(P.post_date,'%Y') ), '%monthnum%', DATE_FORMAT(P.post_date, '%m') ), '%day%', DATE_FORMAT(P.post_date, '%d') ), '%postname%', P.post_name ) AS permalink
                  , (SELECT group_concat(t.name SEPARATOR ', ')
                       FROM wp_terms t
                         LEFT JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id
                         LEFT JOIN wp_term_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                       WHERE tt.taxonomy = 'category' AND P.ID = tr.object_id
                      ) AS category
                FROM wp_posts P
                JOIN wp_options wpo ON wpo.option_name = 'permalink_structure'
                INNER JOIN wp_postmeta PM ON P.ID = PM.post_id
                WHERE P.post_type = 'post' AND P.post_status = 'publish'
                AND PM.meta_key = 'project_id' AND PM.meta_value = ?";
        $query = $dbBlog->query($sql, array($projectID));
        return $query->result_array();
    }
}

?>