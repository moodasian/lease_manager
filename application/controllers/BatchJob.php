<?php


class BatchJob extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('BuildingModel', 'FacilityModel'));
	}

	public function makeBuildingContents(){

		//귀속년도
		$claim_year = date("Y", time());
		$claim_month = date("m", time());

		$claim_year = '2022';
		$claim_month = '09';

		//청구년도
		$bill_ym = date("Y-m", strtotime($claim_year."-".$claim_month." +1 month"));
		$bill_str = explode("-", $bill_ym);
		$bill_year = $bill_str[0];
		$bill_month = $bill_str[1];

		//귀속년 이전달
		$prev_ym = date("Y-m", strtotime($claim_year." -1 month"));
		$prev_str = explode("-", $prev_ym);
		$prev_year = $prev_str[0];
		$prev_month = $prev_str[1];

		//사용시작일
		$use_start_date = date("Y-m-d H:i:s", time());
		$use_end_date = date("Y-m-t H:i:s", time());

		$prev_start_date = date("Y-m-01 00:00:00", strtotime($use_start_date."-1 month"));
		$prev_end_date = date("Y-m-t 23:59:59", strtotime($use_end_date."-1 month"));

		$buildingInfo = $this->BuildingModel->getBuildingInfoAll();

		//기본료 구분 (전기, 수도, 가스, 기타)
		$fee_gubun_arr = ['A', 'B', 'C', 'D'];

		try{
			foreach ($buildingInfo as $key => $val){
				$building_seq = $val['seq'];
				$lm_user_seq = $val['lm_user_seq'];

				for($j=0; $j<count($fee_gubun_arr); $j++){
					//기본료 정보 빈 데이터 insert
					$basic_fee_arr = array(
						'lm_user_seq' => $lm_user_seq,
						'lm_building_seq' => $building_seq,
						'fee_gubun' => $fee_gubun_arr[$j],
						'use_start_date' => $use_start_date,
						'use_end_date' => $use_end_date,
						'claim_year' => $claim_year,
						'claim_month' => $claim_month,
						'bill_year' => $bill_year,
						'bill_month' => $bill_month
					);

					$this->BuildingModel->insertBasicFeeInfo($basic_fee_arr);
					//기본료 공백데이터 insert 끝
				}

				//지출 공백데이터 insert 시작
				$expense_arr = array(
					'lm_user_seq' => $lm_user_seq,
					'lm_building_seq' => $building_seq,
					'lm_expense_year' => $claim_year,
					'lm_expense_month' => $claim_month
				);

				$this->BuildingModel->insertExpenseInfo($expense_arr);
				//지출 공백데이터 insert 끝

				//정산 공백데이터 insert 시작
				$calculation_arr = array(
					'lm_user_seq' => $lm_user_seq,
					'lm_building_seq' => $building_seq,
					'lm_calculation_year' => $claim_year,
					'lm_calculation_month' => $claim_month
				);
				$this->BuildingModel->insertCalculationInfo($calculation_arr);
				//정산 공백데이터 insert 끝


				$this->makeFacilityContents($lm_user_seq, $building_seq);
			}
			print_r("성공");
		}catch(Exception $e){
			print_r("오류발생");
			print_r($e);
		}

	}

	public function makeFacilityContents($lm_user_seq, $building_seq){

		//귀속년도
		$claim_year = date("Y", time());
		$claim_month = date("m", time());
		$claim_year = '2022';
		$claim_month = '09';

		//청구년도
		$bill_ym = date("Y-m", strtotime($claim_year."-".$claim_month." +1 month"));
		$bill_str = explode("-", $bill_ym);
		$bill_year = $bill_str[0];
		$bill_month = $bill_str[1];


		$getRoomInfo = $this->FacilityModel->getFacilityInfoAll($building_seq);

		$gubun_fee = array('A', 'B', 'C', 'D');

		foreach($getRoomInfo as $key => $val){
			$lm_building_seq = $building_seq;
			$lm_room_num = $val['room_num'];

			$insert_bill_array = array(
				'lm_user_seq' => $lm_user_seq,
				'lm_building_seq' => $lm_building_seq,
				'lm_room_num' => $lm_room_num,
				'lm_bill_year' => $bill_year,
				'lm_bill_month' => $bill_month,
				'lm_claim_year' => $claim_year,
				'lm_claim_month' => $claim_month
			);

			$this->FacilityModel->insertBillInfo($insert_bill_array);

			for($k=0; $k<count($gubun_fee); $k++){

				$insert_usagemanage_array = array(
					'lm_user_seq' => $lm_user_seq,
					'lm_usage_gubun' => $gubun_fee[$k],
					'lm_building_seq' => $lm_building_seq,
					'lm_room_num' => $lm_room_num,
					'lm_charge_year' => $claim_year,
					'lm_charge_month' => $claim_month,
					'lm_bill_year' => $bill_year,
					'lm_bill_month' => $bill_month
				);

				$this->FacilityModel->insertUsageManageInfo($insert_usagemanage_array);
			}

		}
	}
}
