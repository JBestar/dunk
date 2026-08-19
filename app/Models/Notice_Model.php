<?php
namespace App\Models;
use CodeIgniter\Model;

class Notice_Model extends Model {
	
	protected $table ='board_notice';
    protected $returnType = 'object'; 
    protected $allowedFields = [
        'notice_type', 
        'notice_title', 
        'notice_content', 
        'notice_answer', 
        'notice_mb_uid', 
        'notice_emp_fid', 
        'notice_read_count', 
        'notice_time_create', 
        'notice_state_active', 
        'notice_state_delete', 
        'notice_client_delete',
    ];
    protected $primaryKey = 'notice_fid';

    public function getNotices(){
        return $this->where([
            'notice_type' => NOTICE_BOARD,
            'notice_state_delete' => STATE_DISABLE,
        ])->orderBy('notice_fid', 'DESC')
        ->findAll();
    }

    public function getNoticeByFid($strNoticeFid){
        return $this->where([
            'notice_type '=> NOTICE_BOARD, 
            'notice_state_delete'=> STATE_DISABLE, 
            'notice_fid'=>$strNoticeFid
        ])->first();
    }

    public function updateNoticeByFid($arrUpdateData){
        return $this->builder()->updateBatch($arrUpdateData, 'notice_fid');
    }

    
    public function updateNoticeByEmpFid($arrData){

        $objMessage = $this->getMessageByFid($arrData['notice_fid']);

        if(is_null($objMessage)) return false;

        if($objMessage->notice_mb_uid != '*' || $objMessage->notice_type != NOTICE_MSG)
          return false;

        if (array_key_exists("notice_title", $arrData))
          $this->builder()->set('notice_title', $arrData['notice_title']);

        if (array_key_exists("notice_content", $arrData))
          $this->builder()->set('notice_content', $arrData['notice_content']);

        if (array_key_exists("notice_content_cn", $arrData))
          $this->builder()->set('notice_content_cn', $arrData['notice_content_cn']);

        if (array_key_exists("notice_state_delete", $arrData))
          $this->builder()->set('notice_state_delete', $arrData['notice_state_delete']);

        if (array_key_exists("notice_state_active", $arrData))
          $this->builder()->set('notice_state_active', $arrData['notice_state_active']);


        $this->builder()->where('notice_emp_fid', $objMessage->notice_fid);
        $this->builder()->where('notice_type', NOTICE_MSG_ALL);
        
        return $this->builder()->update();

    
    }   

    function addNoticeBatch($arrBatchData){
        if(count($arrBatchData) < 1) 
            return false;
        return $this->builder()->insertBatch($arrBatchData);    
    } 


    function addNotice($arrData){
       
        $this->builder()->set('notice_type', $arrData['notice_type']);
        $this->builder()->set('notice_title', $arrData['notice_title']);
        $this->builder()->set('notice_content', removeHostUrl($arrData['notice_content']));
        $this->builder()->set('notice_state_active', $arrData['notice_state_active']);
        $this->builder()->set('notice_mb_uid', $arrData['notice_mb_uid']);
        $this->builder()->set('notice_time_create', 'NOW()', false);
        if (array_key_exists("notice_emp_fid", $arrData))
            $this->builder()->set('notice_emp_fid', $arrData['notice_emp_fid']);
        if (array_key_exists("notice_read_count", $arrData))
            $this->builder()->set('notice_read_count', $arrData['notice_read_count']);
        if (array_key_exists("notice_content_cn", $arrData))
            $this->builder()->set('notice_content_cn', removeHostUrl($arrData['notice_content_cn']));

       $this->builder()->insert();
       return $this->db->insertID();
    }


    public function getMessageByFid($strNoticeFid){
        return $this->where([
            'notice_state_delete'=>STATE_DISABLE, 
            'notice_fid'=>$strNoticeFid,
        ])->first();
    }


    public function setNoticeRead($objNotice){
        $this->builder()->set('notice_read_count', 1);

        $this->builder()->where('notice_fid', $objNotice->notice_fid);
        $this->builder()->where('notice_type', NOTICE_CUSTOMER);
        
        return $this->builder()->update();
    }


    function searchMessage($arrReqData)
    {
        $tbMember = "member";

        $strSql = "SELECT notice_search.*, mb_nickname FROM ( ";
        $strSql .= "SELECT *  FROM ".$this->table;

        if($arrReqData['notice_type'] == 1 ){                           //수신쪽지

          $strSql.=" WHERE notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_CUSTOMER."'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = ".$this->db->escape($arrReqData['notice_mb_uid']);            

        } else if($arrReqData['notice_type'] == 2 ){                    //발송쪽지
          $strSql.=" WHERE notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_MSG."'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = ".$this->db->escape($arrReqData['notice_mb_uid']);

        } else {                                                        //전체
          $strSql.=" WHERE (notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_CUSTOMER."'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = ".$this->db->escape($arrReqData['notice_mb_uid']);

          $strSql.=") OR (notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_MSG."' ";            
          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = ".$this->db->escape($arrReqData['notice_mb_uid']);
          $strSql.=") ";
        }

        $nStartRow = ($arrReqData['page']-1) * $arrReqData['count'] ;
        $strSql.=" ORDER BY notice_time_create DESC LIMIT ".$nStartRow.", ".$arrReqData['count'];
        $strSql.=" ) notice_search LEFT JOIN ".$tbMember." ON ".$tbMember.".mb_uid = notice_search.notice_mb_uid "; 
        $strSql.=" ORDER BY notice_time_create DESC";

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        
        return $result; 

    }


    function searchMessageCnt($arrReqData)
    {
        $strSql = "SELECT count(*) as count  FROM ".$this->table;
        

        if($arrReqData['notice_type'] == 1 ){

          $strSql.=" WHERE notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_CUSTOMER."'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = ".$this->db->escape($arrReqData['notice_mb_uid']);            

        } else if($arrReqData['notice_type'] == 2 ){
          $strSql.=" WHERE notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_MSG."'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = ".$this->db->escape($arrReqData['notice_mb_uid']);

        } else {
          $strSql.=" WHERE (notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_CUSTOMER."'  ";

          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = ".$this->db->escape($arrReqData['notice_mb_uid']);

          $strSql.=") OR (notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_MSG."' ";            
          if(strlen($arrReqData['notice_mb_uid']) > 0 )
              $strSql.=" AND notice_mb_uid = ".$this->db->escape($arrReqData['notice_mb_uid']);
          $strSql.=") ";
        }

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result; 
    }

    function getNewMessageCnt(){
        $strSql = "SELECT count(*) as count  FROM ".$this->table;
        $strSql.=" WHERE notice_state_delete = '".STATE_DISABLE."' AND notice_type = '".NOTICE_CUSTOMER."'  ";
        $strSql.=" AND notice_read_count = '0' ";  
     
        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result->count;  
    }

}
