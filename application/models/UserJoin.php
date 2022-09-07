<?php


class UserJoin extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function checkExistUser($userPhone, $userName){
		$this->load->library('someclass');

		$userPhone = $this->someclass->AES_Decode($userPhone);
		$userName = $this->someclass->AES_Decode($userName);


		print_r($userPhone);
		exit();
	}

}
