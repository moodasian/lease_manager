<?php


class UserJoin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserJoinModel');
	}

	public function index(){
		$this->load->view('/userjoin/index');
	}

	public function userJoinCheck(){
		//회원가입

		$this->load->library('someclass');

		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : 'test';
		$userPasswd = (!empty($_REQUEST['user_passwd'])) ? $_REQUEST['user_passwd'] : '1234';
		$userPasswd_re = (!empty($_REQUEST['user_passwd_re'])) ? $_REQUEST['user_passwd_re'] : '1234';
		$userName = (!empty($_REQUEST['user_name'])) ? $_REQUEST['user_name'] : '배영일';
		$userPhone = (!empty($_REQUEST['user_phone'])) ? $_REQUEST['user_phone'] : '01082346334';
		$userEmail = (!empty($_REQUEST['user_email'])) ? $_REQUEST['user_email'] : 'qoduddlf1234@naver.com';

		$userPasswd = $this->someclass->AES_Encode($userPasswd);
		$userName = $this->someclass->AES_Encode($userName);
		$userPhone = $this->someclass->AES_Encode($userPhone);

		$checkExistUser = $this->UserJoinModel->checkExistUser($userPhone, $userName);

		if(!empty($checkExistUser)){
			$return_msg = array(
				'result' => false,
				'msg' => "이미 가입된 계정입니다."
			);
		}else{
			$insert_arr = array(
				'user_id' => $userId,
				'user_passwd' => $userPasswd,
				'user_name' => $userName,
				'user_phone' => $userPhone,
				'user_email' => $userEmail
			);

			$insert_result = $this->UserJoinModel->insertUser($insert_arr);

			if($insert_result === true){
				$return_msg = array(
					'result' => true,
					'msg' => "가입이 완료되었습니다."
				);
			}else{
				$return_msg = array(
					'result' => false,
					'msg' => "오류 발생"
				);
			}
		}

		$return_msg = json_encode($return_msg, JSON_UNESCAPED_UNICODE);

		echo $return_msg;
	}
}
