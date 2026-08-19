<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ConfSite_Model;
use App\Models\Notice_Model;

class Board extends StdController {

	/**


	 */
	public function index()
	{
		$this->response->redirect( $_ENV['app.furl'].'/board/notice', 'refresh');
		
	}

	public function notice()
	{
		$noticeModel = new Notice_Model();
		$arrNotice = $noticeModel->getNotices();
		$this->load_view_page('board/notice', 'board_notice', LEVEL_ADMIN, [
			'arrNotice' => $arrNotice
		]);		
	}

	public function notice_edit($strNoticeFid)
	{
		$objNotice = null;
		if($strNoticeFid > 0)
		{
			$noticeModel = new Notice_Model();
			$objNotice = $noticeModel->getNoticeByFid($strNoticeFid);					
		}
		$this->load_view_page('board/notice_edit', 'board_notice', LEVEL_ADMIN, [
			'objNotice' => $objNotice
		]);			
	}

	public function message()
	{
		$this->load_view_page('board/message', 'board_message', LEVEL_ADMIN);		
	}

	public function message_edit($strNoticeFid, $strUserFid)
	{
		if (is_login() === false){
			return $this->response->redirect( $_ENV['app.furl'].'/pages/login');
		}
		$objNotice = null;
		$strSenderName = "";
		$strSenderId = "";
		if($strNoticeFid > 0)
		{
			$noticeModel = new Notice_Model();
			$objNotice = $noticeModel->getMessageByFid($strNoticeFid);

			if(!is_null($objNotice) && $objNotice->notice_type == NOTICE_CUSTOMER){
				if($objNotice->notice_read_count == 0)
					$noticeModel->setNoticeRead($objNotice);
				$objSender = $this->modelMember->getInfoByUid($objNotice->notice_mb_uid); 
				if(!is_null($objSender)){
					$strSenderId = $objSender->mb_uid;
					$strSenderName = $objSender->mb_nickname;
				}
			}
		}
		$strUserId = '*';
		$objUser = null;
		if($strUserFid > 0)
			$objUser = $this->modelMember->getInfoByFid($strUserFid);
		if(!is_null($objUser)){
			$strUserId = $objUser->mb_uid;
		} 
		$this->load_view_page('board/message_edit', 'board_message', LEVEL_ADMIN, [
			'objNotice' => $objNotice, 
			'strUserId' => $strUserId,
			'senderName' => $strSenderName,
			'senderId' => $strSenderId
		]);		
	}
}