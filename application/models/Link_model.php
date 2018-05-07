<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Link_model extends MY_Model
{
    protected $table = 'tbl_links';

    public function __construct()
    {
        parent::__construct();
    }

    public function getLinkByPeople($arrPeopleID)
    {
        $sql = "Select Link,LinkTypeImage,LinkTypeName,LinkParentID
                from tbl_links L inner join tbl_linktypes LT on L.LinkType = LT.LinkTypeID
                where LinkParentType = 4 and LinkParentID IN ? Order By LinkTypeSortOrder";

        $query = $this->db->query($sql, array($arrPeopleID));
        return $query->result_array();
    }

    public function getLinkByProject($projectID)
    {
        $this->db->select('LinkType,Link,LinkTypeImage,LinkID');
        $this->db->from($this->table);
        $this->db->join('tbl_linktypes', 'tbl_linktypes.LinkTypeID = tbl_links.LinkType');
        $where = [
            'LinkParentType' => 1,
            'LinkParentID' => $projectID,
        ];
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getProjectWebsiteLink($projectID)
    {
        $sql = "Select LinkID,LinkType,Link from tbl_links where LinkParentType = 1 and LinkParentID = ? and LinkType = 1";

        $query = $this->db->query($sql, array($projectID));
        return $query->row_array();
    }

    public function getProjectLinks($LinkParentID)
    {
        $sql = "Select LinkTypeID,LinkTypeName,LinkTypeImage,LinkID,Link
                  from tbl_linktypes LT
                  left join tbl_links L
                  on LT.LinkTypeID = L.LinkType and L.LinkParentType = 1 and LinkParentID = ?
                  Order By LinkTypeSortOrder";
        $query = $this->db->query($sql, array($LinkParentID));
        return $query->result_array();
    }

    public function updateLink($linkType, $formData, $link)
    {
        $linkParentID = $formData['ProjID'];
        $fieldName = 'Links'.$link['LinkTypeID'];
        $sql = "SELECT * FROM `tbl_links` WHERE `LinkType` = ? AND `LinkParentID` = ?";
        $query = $this->db->query($sql, array($linkType, $linkParentID));
        $result = $query->row_array();

        if (empty($result)) {
            $arrInsert = array(
                'LinkType' => $link['LinkTypeID'],
                'LinkParentType' => 1,
                'LinkParentID' => $formData['ProjID'],
                'Link' => cleanInput($formData[$fieldName])
            );
            if (!empty($formData[$fieldName])) {
                $this->insert($arrInsert);
            }
        } else {
            $arrUpdate = array('Link' => cleanInput($formData[$fieldName]));
            $this->update($arrUpdate, array('LinkParentID' => $formData['ProjID'], 'LinkType' => $link['LinkTypeID']));
        }
    }

    public function getLinkByListing($LinkParentID)
    {
        $sql = "SELECT LinkID from tbl_links WHERE LinkParentID = ? AND LinkType = 10 AND LinkParentType = 4";
        $query = $this->db->query($sql, array($LinkParentID));
        return $query->row_array();
    }

    public function getLinkById($linkId)
    {
        $sql = "Select Link from tbl_links where LinkID = ?";
        $query = $this->db->query($sql, array($linkId));
        return $query->row_array();
    }
}

?>