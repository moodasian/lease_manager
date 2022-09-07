<?php


class Claim extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('ClaimModel', 'UserJoinModel'));
	}

	public function index(){
		//청구관리
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		$this->load->view('/claim/index');
	}

	public function getClaimList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$lm_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : date('Y', time());
		$lm_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : date('m', time());

		$send_data = array(
			'lm_year'=>$lm_year,
			'lm_month'=>$lm_month,
			'building_seq' => $building_seq
		);

		$claimList = $this->ClaimModel->getClaimList($send_data);
		$return_arr = array();

		foreach($claimList as $key => $val){
			$usage_info = "";
			$usage_info = $val['usage_info'];
			$usage_info = explode(",", $usage_info);
			foreach($usage_info as $k => $v){
				$usage_info_str = explode("-", $v);

				if($usage_info_str[0] == '전기'){
					$claimList[$key]['electro_fee'] = $usage_info_str[1];
				}elseif($usage_info_str[0] == '수도'){
					$claimList[$key]['water_fee'] = $usage_info_str[1];
				}elseif($usage_info_str[0] == '가스'){
					$claimList[$key]['gas_fee'] = $usage_info_str[1];
				}elseif($usage_info_str[0] == '기타'){
					$claimList[$key]['etc_fee'] = $usage_info_str[1];
				}
			}
		}

		$return_arr = json_encode($claimList, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}
}
