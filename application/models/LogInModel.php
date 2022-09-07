<?php


class LogInModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function checkUserLogin($get_arr){

		$query = "
		SELECT * FROM lm_user where user_id = '{$get_arr['user_id']}' and user_passwd = '{$get_arr['user_passwd']}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}
}
