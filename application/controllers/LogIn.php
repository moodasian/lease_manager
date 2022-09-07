<?php


class LogIn extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LogInModel');
	}

	public function index(){
		$this->load->view('/login/index');
	}

	public function test(){
		print_r("AA");
	}

	public function checkSession(){
		session_start();
		print_r($_SESSION);
	}

	public function logInAction(){
		$this->load->library('someclass');

		$id = $_REQUEST['id'];
		$passwd = $_REQUEST['passwd'];
		$passwd = $this->someclass->AES_Encode($passwd);

		try{
			$send_arr = array(
				'user_id'=>$id,
				'user_passwd'=>$passwd
			);

			$loginCheck = $this->LogInModel->checkUserLogin($send_arr);
			if(empty($loginCheck[0])){
				$return_arr = array(
					'result' => true,
					'msg' => '가입되지 않은 계정입니다.'
				);
			}else{
				$userSeq = $loginCheck[0]['seq'];

				session_start();
				$_SESSION['user_id'] = $id;
				$_SESSION['user_seq'] = $userSeq;

				$return_arr = array(
					'result' => true,
					'msg' => '정상적으로 로그인 되었습니다.'
				);
			}
		}catch (Exception $e){
			$return_arr = array(
				'result' => false,
				'msg' => '로그인에 실패하였습니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function logOutAction(){
		$this->load->library('someclass');
		session_start();

		try{
			session_destroy();

			$return_arr = array(
				'result' => true,
				'msg' => '정상적으로 로그아웃 되었습니다.'
			);
		}catch (Exception $e){
			$return_arr = array(
				'result' => false,
				'msg' => '로그아웃에 실패하였습니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}
}
