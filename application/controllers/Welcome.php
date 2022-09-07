<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('IndexModel');
	}

	public function index()
	{
		session_start();
		//include($_SERVER["DOCUMENT_ROOT"].'/application/views/common/header.php');
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		$this->load->view('welcome_message');
	}

	public function getNoticeList(){
		$this->load->library('someclass');

		$noticeList = $this->IndexModel->getNoticeList();

		$noticeList = json_encode($noticeList, JSON_UNESCAPED_UNICODE);

		echo $noticeList;
	}

	public function getQnAList(){
		$this->load->library('someclass');

		$qnaList = $this->IndexModel->getQnAList();

		$qnaList = json_encode($qnaList, JSON_UNESCAPED_UNICODE);

		echo $qnaList;
	}

	public function getAdminCheck(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$adminInfo = $this->IndexModel->getAdminInfo($userId);

		$return_arr = json_encode($adminInfo[0], JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getNoticeIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$seq = (!empty($_REQUEST['seq'])) ? $_REQUEST['seq'] : '';

		$noticeInfo = $this->IndexModel->getNoticeInfoIndi($seq);


		$return_arr = json_encode($noticeInfo[0], JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getQnAIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$seq = (!empty($_REQUEST['seq'])) ? $_REQUEST['seq'] : '';

		$qnaInfo = $this->IndexModel->getQnAInfoIndi($seq);
		$return_arr = array();
		foreach($qnaInfo as $key => $val){
			if($val['qna_reply_status'] == '0'){
				$return_arr['q_title'] = $val['qna_title'];
				$return_arr['q_contents'] = $val['qna_content'];

			}else{
				$return_arr['a_title'] = $val['qna_title'];
				$return_arr['a_contents'] = $val['qna_content'];
			}

			$return_arr['qna_file_root'] = $val['qna_file_root'];
			$return_arr['qna_file_name'] = $val['qna_file_name'];
		}
		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;

	}

	public function registNotice(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$notice_title = (!empty($_REQUEST['notice_title'])) ? $_REQUEST['notice_title'] : '';
		$notice_contents = (!empty($_REQUEST['notice_contents'])) ? $_REQUEST['notice_contents'] : '';
		$notice_filename = (!empty($_REQUEST['notice_filename'])) ? $_REQUEST['notice_filename'] : '';


		try{
			$res = $this->IndexModel->registNotice($notice_title, $notice_contents, $notice_filename);

			if($res === true){
				$return_arr = array(
					'result' => true,
					'msg' => '성공적으로 등록되었습니다.'
				);
			}
		}catch (Exception $e){
			$return_arr = array(
				'result' => false,
				'msg' => '오류가 발생했습니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function registQnA(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$qna_title = (!empty($_REQUEST['qna_title'])) ? $_REQUEST['qna_title'] : '';
		$qna_contents = (!empty($_REQUEST['qna_contents'])) ? $_REQUEST['qna_contents'] : '';
		$upload_qna_filename = (!empty($_REQUEST['qna_filename'])) ? $_REQUEST['qna_filename'] : '';

		try{
			$res = $this->IndexModel->registQnA($qna_title, $qna_contents, $upload_qna_filename);

			if($res === true){
				$return_arr = array(
					'result' => true,
					'msg' => '성공적으로 등록되었습니다.'
				);
			}
		}catch (Exception $e){
			$return_arr = array(
				'result' => false,
				'msg' => '오류가 발생했습니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function modQnA(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$qna_title = (!empty($_REQUEST['qna_title'])) ? $_REQUEST['qna_title'] : '';
		$qna_contents = (!empty($_REQUEST['qna_contents'])) ? $_REQUEST['qna_contents'] : '';
		$qna_a_contents = (!empty($_REQUEST['qna_a_contents'])) ? $_REQUEST['qna_a_contents'] : '';
		$qna_seq = (!empty($_REQUEST['qna_seq'])) ? $_REQUEST['qna_seq'] : '';

		try{
			$res = $this->IndexModel->modQnA($qna_seq, $qna_title, $qna_contents, $qna_a_contents);

			$checkAnswerExist = $this->IndexModel->checkAnswer($qna_seq);

			if(!empty($checkAnswerExist[0])){
				//update
				$this->IndexModel->updateA($qna_seq, $qna_a_contents);

			}else{
				//insert
				$this->IndexModel->updateStatus($qna_seq);
				$this->IndexModel->insertA($qna_seq, $qna_a_contents);
			}

			if($res === true){
				$return_arr = array(
					'result' => true,
					'msg' => '성공적으로 반영되었습니다.'
				);
			}
		}catch (Exception $e){
			$return_arr = array(
				'result' => false,
				'msg' => '오류가 발생했습니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getSummaryInfo_RIGHT(){
		$this->load->library('someclass');
		session_start();

		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = $this->IndexModel->getBuildingSeq($userSeq);
		if(!empty($building_seq)){
			$building_seq = $building_seq[0]['seq'];

			$summaryRightInfo = $this->IndexModel->getBuildingSummaryRight($building_seq);
		}else{
			$summaryRightInfo[0] = array();
		}

		$return_arr = json_encode($summaryRightInfo[0], JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}


	public function getSummaryInfo_Accum(){
		$this->load->library('someclass');
		session_start();

		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		//귀속년도
		$claim_year = date("Y", time());
		$claim_month = date("m", time());

		//청구년도
		$bill_ym = date("Y-m", strtotime($claim_year." -1 month"));
		$bill_str = explode("-", $bill_ym);
		$bill_year = $bill_str[0];
		$bill_month = $bill_str[1];

		$building_seq = $this->IndexModel->getBuildingSeq($userSeq);
		if(!empty($building_seq)){
			$building_seq = $building_seq[0]['seq'];

			$summaryRightInfo = $this->IndexModel->getBuildingSummaryAccum($bill_year, $bill_month, $building_seq);
		}else{
			$summaryRightInfo[0] = array();
		}

		$return_arr = json_encode($summaryRightInfo[0], JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function fileUpload(){

		try{
			if($_FILES['upload_file']['size'] > 0){
				$file_type = $_FILES['upload_file']['type'];
				$file_type = str_replace("image/", "", $file_type);

				$file_name = 'notice_'.mt_rand(1, 10000)."_".time().".".$file_type;
				$file_tmp_name = $_FILES['upload_file']['tmp_name'];
				$save_filename = $_SERVER['DOCUMENT_ROOT']."/uploads";

				//$file_upload = move_uploaded_file($file_tmp_name, $save_filename);
				//$file_upload = move_uploaded_file($_FILES['upload_file']['tmp_name'], '/var/www/html/lease_manager/uploads/'.$_FILES['upload_file']['name']);
				move_uploaded_file($_FILES['upload_file']['tmp_name'], '/var/www/html/lease_manager/uploads/'.$file_name);
			}

			$return = $file_name;

		}catch(Exception $e){
			$return = '';
		}

		//$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return;
	}

	public function fileUploadQna(){

		try{
			if($_FILES['upload_file']['size'] > 0){
				$file_type = $_FILES['upload_file']['type'];
				$file_type = str_replace("image/", "", $file_type);

				$file_name = 'qna_'.mt_rand(1, 10000)."_".time().".".$file_type;
				$file_tmp_name = $_FILES['upload_file']['tmp_name'];
				$save_filename = $_SERVER['DOCUMENT_ROOT']."/uploads";

				//$file_upload = move_uploaded_file($file_tmp_name, $save_filename);
				//$file_upload = move_uploaded_file($_FILES['upload_file']['tmp_name'], '/var/www/html/lease_manager/uploads/'.$_FILES['upload_file']['name']);
				move_uploaded_file($_FILES['upload_file']['tmp_name'], '/var/www/html/lease_manager/uploads/'.$file_name);
			}

			$return = $file_name;

		}catch(Exception $e){
			$return = '';
		}

		//$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return;
	}
}
