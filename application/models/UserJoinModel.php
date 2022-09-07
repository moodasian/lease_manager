<?php


class UserJoinModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function checkExistUser($userPhone, $userName){
		$this->load->library('someclass');
		$query = "
			SELECT * FROM lm_user WHERE user_phone = '{$userPhone}' and user_name = '{$userName}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->num_rows();


		return $res;
	}

	public function insertUser($insert_arr){
		$this->load->library('someclass');

		$query = "
			INSERT INTO lm_user(user_id, user_passwd, user_name, user_phone, user_email)
			values ('{$insert_arr['user_id']}', '{$insert_arr['user_passwd']}', '{$insert_arr['user_name']}', '{$insert_arr['user_phone']}', '{$insert_arr['user_email']}')
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

}
