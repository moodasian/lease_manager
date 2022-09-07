(function(window, $){
	var pageNum = 1;
	var pagination;

	init();

	function init(){
		loadBuildingList();
	}


})(window, jQuery);

function sum_vals(){


	var lm_room_deposit = $("#LsExitFacility-02").val(); //보증금
	var lm_monthly_fee = $("#LsExitFacility-03").val(); //월세
	var lm_management_fee = $("#LsExitFacility-04").val(); //관리비

	var lm_etc_fee = $("#LsExitFacility-05").val(); //기타비
	var lm_electro_cost = $("#LsExitFacility-06").val(); //전기료
	var lm_water_cost = $("#LsExitFacility-07").val(); //수도료
	var lm_gas_cost = $("#LsExitFacility-08").val(); //가스료
	var lm_etc_cost = $("#LsExitFacility-09").val(); //기타료

	var lm_total_arrears = $("#LsExitFacility-10").val(); //총 체납액
	var lm_brokerage_fee = $('#LsExitFacility-12').val(); //중개료
	var lm_exit_cleaning_cost = $("#LsExitFacility-14").val(); //퇴실청소료

	var lm_total_refund = 0;

	lm_total_refund = Number(lm_room_deposit) + Number(lm_monthly_fee) + Number(lm_management_fee) + Number(lm_etc_fee);

	lm_total_refund = Number(lm_total_refund) + Number(lm_electro_cost) + Number(lm_water_cost) + Number(lm_gas_cost) + Number(lm_etc_cost);

	lm_total_refund = Number(lm_total_refund) + Number(lm_total_arrears) + Number(lm_brokerage_fee) + Number(lm_exit_cleaning_cost);
		//+ parseInt(lm_electro_cost) + parseInt(lm_water_cost) + parseInt(lm_gas_cost) + parseInt(lm_etc_cost) + parseInt(lm_total_arrears) + parseInt(lm_brokerage_fee) + parseInt(lm_exit_cleaning_cost);

	$('#LsExitFacility-21').val(lm_total_refund);
}

function solution(absolutes, signs) {
	let result = 0;
	for(let i = 0; i < absolutes.length; i++) {
		if(signs[i] > 0) result+= absolutes[i];
		else result-=absolutes[i];
	}
	return result;
}

function sendSMS(){
	var building_seq = $("#LsExitFacility-01 option:selected").val();
	var lm_room_num = $("#LsExitFacility-11 option:selected").val();
	if(lm_room_num == ""){
		alert('호실을 선택해주세요');
	}else{
		var lm_room_deposit = $("#LsExitFacility-02").val(); //보증금
		var lm_monthly_fee = $("#LsExitFacility-03").val(); //월세
		var lm_management_fee = $("#LsExitFacility-04").val(); //관리비
		var lm_etc_fee = $("#LsExitFacility-05").val(); //기타비

		var lm_electro_cost = $("#LsExitFacility-06").val(); //전기료
		var lm_water_cost = $("#LsExitFacility-07").val(); //수도료
		var lm_gas_cost = $("#LsExitFacility-08").val(); //가스료
		var lm_etc_cost = $("#LsExitFacility-09").val(); //기타료

		var lm_total_arrears = $("#LsExitFacility-10").val(); //총 체납액
		var lm_brokerage_fee = $("#LsExitFacility-12").val(); //중개료
		var lm_exit_cleaning_cost = $("#LsExitFacility-14").val(); //퇴실청소료
		var total_cost = $("#LsExitFacility-21").val(); //환불총액

		var data = {};

		data['building_seq'] = building_seq;
		data['lm_room_num'] = lm_room_num;
		data['lm_room_deposit'] = lm_room_deposit;
		data['lm_monthly_fee'] = lm_monthly_fee;
		data['lm_management_fee'] = lm_management_fee;
		data['lm_etc_fee'] = lm_etc_fee;
		data['lm_electro_cost'] = lm_electro_cost;
		data['lm_water_cost'] = lm_water_cost;
		data['lm_gas_cost'] = lm_gas_cost;
		data['lm_etc_cost'] = lm_etc_cost;
		data['lm_brokerage_fee'] = lm_brokerage_fee;
		data['lm_exit_cleaning_cost'] = lm_exit_cleaning_cost;
		data['lm_total_arrears'] = lm_total_arrears;
		data['lm_total_cost'] = total_cost;

		$.ajax({
			url:'/ExitFacility/sendExitFacilitySMS',
			type:"POST",
			dataType:'JSON',
			data: data,
			success:function(data){
				alert(data.msg);
			},
			error:function(e){
				console.log(e);
			}
		});
	}

}
function loadBuildingList() {
	$.ajax({
		url:'/Facility/getBuildingList',
		type:"POST",
		dataType:'JSON',
		success:function(data){
			renderBuildingSelect(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderBuildingSelect(data){
	var html = '';

	for(var i=0; i<data.length; i++){
		var building_name = data[i].building_name;
		var building_seq = data[i].building_seq;
		html += '<option value="'+building_seq+'">'+building_name+'</option>';
	}

	$('#LsExitFacility-01').append(html);
}

$(document).ready(function(){
	$('#LsExitFacility-01').on('change', function(){
		var building_seq = this.value;
		loadFacilityList(building_seq);
	});
})

function loadFacilityList(building_seq){
	$.ajax({
		url:'/Facility/getFacilitySelect',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq},
		success:function(data){
			renderFacilitySelect(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderFacilitySelect(data){
	var html = '';
	$('#LsExitFacility-11').empty();

	if(data == null){
		html += '<option value="" selected>등록된 시설이 없습니다.</option>';
	}else{
		//html += '<option value="" selected>선택해주세요</option>';
		for(var i=0; i<data.length; i++){
			var room_num = data[i].room_num;
			html += '<option value="'+room_num+'">'+room_num+'호</option>';
		}
	}

	$('#LsExitFacility-11').append(html);
}

function formCheck2(){

	var building_seq = $("#LsExitFacility-01 option:selected").val();
	var lm_room_num = $("#LsExitFacility-11 option:selected").val();
	var lm_room_deposit = $("#LsExitFacility-02").val();
	var lm_monthly_fee = $("#LsExitFacility-03").val();
	var lm_management_fee = $("#LsExitFacility-04").val();
	var lm_etc_fee = $("#LsExitFacility-05").val();
	var lm_electro_cost = $("#LsExitFacility-06").val();
	var lm_water_cost = $("#LsExitFacility-07").val();
	var lm_gas_cost = $("#LsExitFacility-08").val();
	var lm_etc_cost = $("#LsExitFacility-09").val();

	var lm_brokerage_fee = $("#LsExitFacility-10").val();
	var lm_deposit_pm = $("#LsExitFacility-12").val(); //보증금가감액
	var lm_monthly_fee_unpaid = $("#LsExitFacility-13").val(); //임차료 미납액
	var lm_management_fee_unpaid = $("#LsExitFacility-14").val(); //관리비 미납액
	var lm_etc_fee_unpaid = $("#LsExitFacility-15").val(); //기타비 미납액
	var lm_electro_cost_unpaid = $("#LsExitFacility-16").val(); //전기요금 미납액
	var lm_water_cost_unpaid = $("#LsExitFacility-17").val(); //
	var lm_gas_cost_unpaid = $("#LsExitFacility-18").val();
	var lm_etc_cost_unpaid = $("#LsExitFacility-19").val();
	var lm_exit_cleaning_cost = $("#LsExitFacility-20").val();

	var total_cost = $("#LsExitFacility-21").val();

	var data = {};

	data['building_seq'] = building_seq;
	data['lm_room_num'] = lm_room_num;
	data['lm_room_deposit'] = lm_room_deposit;
	data['lm_monthly_fee'] = lm_monthly_fee;
	data['lm_management_fee'] = lm_management_fee;
	data['lm_etc_fee'] = lm_etc_fee;
	data['lm_electro_cost'] = lm_electro_cost;
	data['lm_water_cost'] = lm_water_cost;
	data['lm_gas_cost'] = lm_gas_cost;
	data['lm_etc_cost'] = lm_etc_cost;

	data['lm_brokerage_fee'] = lm_brokerage_fee;
	data['lm_deposit_pm'] = lm_deposit_pm;
	data['lm_monthly_fee_unpaid'] = lm_monthly_fee_unpaid;
	data['lm_management_fee_unpaid'] = lm_management_fee_unpaid;
	data['lm_etc_fee_unpaid'] = lm_etc_fee_unpaid;
	data['lm_electro_cost_unpaid'] = lm_electro_cost_unpaid;
	data['lm_water_cost_unpaid'] = lm_water_cost_unpaid;
	data['lm_gas_cost_unpaid'] = lm_gas_cost_unpaid;
	data['lm_etc_cost_unpaid'] = lm_etc_cost_unpaid;
	data['lm_exit_cleaning_cost'] = lm_exit_cleaning_cost;

	data['lm_total_cost'] = total_cost;


	modExitFacilityInfo(data);

}

function modExitFacilityInfo(data){

	$.ajax({
		url:'/ExitFacility/insertExitFacilityDoc',
		type:"POST",
		dataType:'JSON',
		data:data,
		success:function(data){
			alert(data.msg);
			location.reload();
		},
		error:function(e){
			console.log(e);
		}
	});
}
