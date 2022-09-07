$(document).ready(function(){
	var pageNum = 1;
	var pagination;

	init();

	function init(){
		loadListData();
		//getPrevclaim();
		loadBuildingList();
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/Bill/getBillList',
			type:"POST",
			dataType:'JSON',
			success:function(data){
				renderTable(data);
			},
			error:function(e){
				console.log(e);
			}
		});
	}

	//리스트 테이블 렌더링
	function renderTable(data){
		var html = '';

		if(data.length == 0){
			html += '<ul class="ls-detail-body__tb">';
			html += '<li style="max-width:1200px;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
			html += '</ul>';
		}else{
			for(var i=0; i<data.length; i++){
				console.log(data);
				var dataItem = data[i];
				var building_name = "'"+dataItem.building_name+"'";
				var building_name_var =  dataItem.building_name;
				var room_num = dataItem.lm_room_num;
				var total_claim_fee = dataItem.total_claim_fee;
				var charge_ym = dataItem.lm_charge_ym;
				var charge_year = dataItem.lm_charge_year;
				var charge_month = "'"+dataItem.lm_charge_month+"'";
				var building_seq = dataItem.lm_building_seq;
				var seq = dataItem.seq;

				html += '<ul class="ls-detail-body__tb" id="data_row" >';
				html += '<li><strong class="mb-title">건물명</strong><span>'+building_name_var+'</span></li>';
				html += '<li><strong class="mb-title">호실</strong><span>'+room_num+'</span></li>';
				html += '<li><strong class="mb-title">청구 합계액</strong><span>'+total_claim_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">청구 년월</strong><span>'+charge_ym+'</span></li>';
				html += '<li><strong class="mb-title">관리</strong><button class="click-button bill-setting" type="button" onclick="openmodal('+building_name+', '+room_num+', '+charge_year+', '+charge_month+', '+building_seq+')" style="margin-left: 5px;">수정</button></li>';
				html += '<li><strong class="mb-title">삭제</strong><button class="click-button delete" type="button" onclick="">삭제</button></li>';
				html += '<li><strong class="mb-title">청구서 발송</strong><button class="click-button" type="button" onclick="sendSMS('+building_name+', '+room_num+', '+charge_year+', '+charge_month+', '+building_seq+')" style="margin-left: 5px;">SMS 발송</button></li>';
				html += '</ul>';

			}
		}

		$('#billList').html(html);
	}
});

function sendSMS(building_name, room_num, charge_year, charge_month, building_seq){

	$.ajax({
		url:'/Bill/sendBillSMS',
		type:"POST",
		dataType:'JSON',
		data: {building_name:building_name, room_num:room_num, charge_year:charge_year, charge_month:charge_month, building_seq:building_seq},
		success:function(data){
			alert(data.msg);
		},
		error:function(e){
			console.log(e);
		}
	});

}

function openmodal(building_name, room_num, charge_year, charge_month, building_seq){

	$.ajax({
		url:'/Bill/getBillInfoIndi',
		type:"POST",
		dataType:'JSON',
		data: {building_name:building_name, room_num:room_num, charge_year:charge_year, charge_month:charge_month, building_seq:building_seq},
		success:function(data){
			console.log(data);

			var bill_date = data.lm_charge_year+"-"+data.lm_charge_month;
			var total_claim_fee = parseInt(data.room_monthly_fee) + parseInt(data.room_maintenance_charge) + parseInt(data.room_etc_fee) + parseInt(data.electro_cost) + parseInt(data.water_cost)
			+ parseInt(data.gas_cost) + parseInt(data.etc_cost) + parseInt(data.total_arrears);

			total_claim_fee = total_claim_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');

			$('#LsBillDate').val(bill_date);
			$('#LsBillRoomNum').val(data.lm_room_num);
			$('#LsBillFee').val(data.room_monthly_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsBillAdminFee').val(data.room_maintenance_charge.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsBillEtc').val(data.room_etc_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsBillElectFee').val(data.electro_cost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsBillWaterFee').val(data.water_cost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsBillGasFee').val(data.gas_cost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsBillEtcFee').val(data.etc_cost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsBillArrears').val(data.total_arrears);

			$('#room_num').val(data.lm_room_num);
			$('#building_seq').val(data.lm_building_seq);
			$('#claim_year').val(data.lm_charge_year);
			$('#claim_month').val(data.lm_charge_month);



			$('#LsBillTotalFee').val(total_claim_fee);

		},
		error:function(e){
			console.log(e);
		}
	});

	$('.ls-modal.bill-setting').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
}

function formCheck(){

	var building_seq = $("#building_seq").val(); //건물명
	var room_num = $("#room_num").val();  //청구액
	var claim_year = $('#claim_year').val();
	var claim_month = $('#claim_month').val();
	var room_monthly_fee = $('#LsBillFee').val();
	var room_maintenance_charge = $('#LsBillAdminFee').val();
	var room_etc_fee = $('#LsBillEtc').val();
	var electro_cost = $('#LsBillElectFee').val();
	var water_cost = $('#LsBillWaterFee').val();
	var gas_cost = $('#LsBillGasFee').val();
	var etc_cost = $('#LsBillEtcFee').val();
	var total_cost = $('#LsBillTotalFee').val();

	var data = {};
	var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi

	data['lm_room_num'] = room_num;
	data['lm_building_seq'] = building_seq;
	data['lm_bill_year'] = claim_year;
	data['lm_bill_month'] = claim_month;
	data['lm_rental_fee'] = room_monthly_fee.replace(regExp, "");
	data['lm_maintenanace_fee'] = room_maintenance_charge.replace(regExp, "");
	data['lm_etc_fee'] = room_etc_fee.replace(regExp, "");
	data['lm_electro_fee'] = electro_cost.replace(regExp, "");
	data['lm_water_fee'] = water_cost.replace(regExp, "");
	data['lm_gas_fee'] = gas_cost.replace(regExp, "");
	data['lm_etc_cost'] = etc_cost.replace(regExp, "");
	data['lm_arrears_fee'] = 0;
	data['lm_total_fee'] = total_cost.replace(regExp, "");


	modBasicFeeInfo(data);

}

function modBasicFeeInfo(data){
	$.ajax({
		url:'/Bill/modBillInfo',
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

	//html += '<option value="" selected>선택해주세요</option>';
	for(var i=0; i<data.length; i++){
		var building_name = data[i].building_name;
		var building_seq = data[i].building_seq;
		html += '<option value="'+building_seq+'">'+building_name+'</option>';
	}

	$('#buildingListSelect').append(html);
}

$(document).ready(function(){
	$('#buildingListSelect').on('change', function(){
		var building_seq = this.value;
		loadFacilityList(building_seq);
	});
});

function loadFacilityList(building_seq){
	$.ajax({
		url:'/Facility/getFacilitySelectList',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq},
		success:function(data){
			console.log(data);
			renderFacilitySelect(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderFacilitySelect(data){
	var html = '';
	$('#FacilityListSelect').empty();

	if(data == null){
		html += '<option value="" selected>등록된 시설이 없습니다.</option>';
	}else{
		html += '<option value="" selected>선택해주세요</option>';
		//html += '<option value="" selected>선택해주세요</option>';
		for(var i=0; i<data.length; i++){
			var room_num = data[i].room_num;
			var lm_room_seq = data[i].lm_room_seq;
			html += '<option value="'+room_num+'">'+room_num+'호</option>';
		}
	}

	$('#FacilityListSelect').append(html);
}


function getPost(){
	var building_seq = $('#buildingListSelect').val();
	var fee_gubun = $('#feeGubunSelect').val();
	var lm_ym = $('#basicFeeDateSELECT').val();
	var words = lm_ym.split('-');
	var lm_year = words[0];
	var lm_month = words[1];
	var room_num = $("#FacilityListSelect").val();


	$.ajax({
		url:'/Bill/getBillList',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq, fee_gubun:fee_gubun, room_num:room_num, lm_year:lm_year, lm_month:lm_month},
		success:function(data){
			renderSelectTable(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderSelectTable(data){
	$('#billList').empty();
	var html = '';
	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li style="max-width:1200px;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';
	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			var building_name = "'"+dataItem.building_name+"'";
			var building_name_var =  dataItem.building_name;
			var room_num = dataItem.lm_room_num;
			var total_claim_fee = dataItem.total_claim_fee;
			var charge_ym = dataItem.lm_charge_ym;
			var charge_year = dataItem.lm_charge_year;
			var charge_month = "'"+dataItem.lm_charge_month+"'";
			var building_seq = dataItem.lm_building_seq;
			var seq = dataItem.seq;

			html += '<ul class="ls-detail-body__tb" id="data_row" >';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name_var+'</span></li>';
			html += '<li><strong class="mb-title">호실</strong><span>'+room_num+'</span></li>';
			html += '<li><strong class="mb-title">청구 합계액</strong><span>'+total_claim_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">청구 년월</strong><span>'+charge_ym+'</span></li>';
			html += '<li><strong class="mb-title">관리</strong><button class="click-button bill-setting" type="button" onclick="openmodal('+building_name+', '+room_num+', '+charge_year+', '+charge_month+', '+building_seq+')" style="margin-left: 5px;">수정</button></li>';
			html += '<li><strong class="mb-title">삭제</strong><button class="click-button delete" type="button" onclick="">삭제</button></li>';
			html += '<li><strong class="mb-title">청구서 발송</strong><button class="click-button" type="button" onclick="sendSMS('+building_name+', '+room_num+', '+charge_year+', '+charge_month+', '+building_seq+')" style="margin-left: 5px;">SMS 발송</button></li>';
			html += '</ul>';

		}
	}

	$('#billList').html(html);
}

function sendSMS_Total(){

	var action_check = confirm('청구서 일괄 발송은 금월 발송 대상 청구서만 발송됩니다. 발송하시겠습니까?');
	var gubun_flag = 'del';

	if(action_check){
		$.ajax({
			url:'/Bill/sendTotalSMS',
			type:"POST",
			dataType:'JSON',
			data:{},
			success:function(data){
				alert(data.msg);
				location.reload();
			},
			error:function(e){
				console.log(e);
			}
		});
	}
}

