$(document).ready(function(){

	init();

	function init(){
		loadListData();
		loadBuildingList();
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/BasicFee/getBasicFeeList',
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
				var dataItem = data[i];
				console.log(dataItem);

				var claim_year = dataItem.claim_year;
				var claim_month = dataItem.claim_month;
				var claim_ym = claim_year+"-"+claim_month;
				var fee_gubun = dataItem.fee_gubun;
				var fee_gubun_var = '';
				var fee_gubun_num = '';
				var gubun_usage = "";

				var charge_fee = dataItem.charge_fee_after;
				var use_amount = dataItem.use_amount;
				var use_basicfee_per = dataItem.use_basicfee_per;
				var building_seq = dataItem.lm_building_seq;
				var basicfee_seq = dataItem.seq;

				var data_range = dataItem.use_start_date + "~" + dataItem.use_end_date;

				if(fee_gubun == 'A'){
					fee_gubun_var = '전기';
					fee_gubun_num = 1;
					gubun_usage = 'kw';
				}else if(fee_gubun == 'B'){
					fee_gubun_var = '수도';
					fee_gubun_num = 2;
					gubun_usage = 't';
				}else if(fee_gubun == 'C'){
					fee_gubun_var = '가스';
					fee_gubun_num = 3;
					gubun_usage = 't';
				}else if(fee_gubun == 'D'){
					fee_gubun_var = '기타';
					fee_gubun_num = 4;
					gubun_usage = '';
					use_basicfee_per = '0';

				}


				html += '<ul class="ls-detail-body__tb">';
				html += '<li><strong class="mb-title">귀속년월</strong><span>'+claim_ym+'</span></li>';
				html += '<li><strong class="mb-title">사용기간</strong><span>'+data_range+'</li>';
				html += '<li><strong class="mb-title">구분</strong><span>'+fee_gubun_var+'</li>';
				html += '<li><strong class="mb-title">청구액(원)</strong><span>'+charge_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
				html += '<li><strong class="mb-title">사용량</strong><span>'+use_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
				html += '<li><strong class="mb-title">단가</strong><span>'+use_basicfee_per.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
				html += '<li><strong class="mb-title">관리</strong><button class="click-button elect-setting" type="button" onclick="openmodal('+fee_gubun_num+', '+basicfee_seq+', '+building_seq+')">단가 수정</button></li>';
				html += '</ul>';

			}
		}


		$('#basicFeeList').html(html);
	}
});

function openmodal(fee_gubun_num, basicfee_seq, building_seq){

	$.ajax({
		url:'/BasicFee/getBasicFeeIndi',
		type:"POST",
		dataType:'JSON',
		data:{basicfee_seq:basicfee_seq, building_seq:building_seq},
		success:function(data){

			if(fee_gubun_num == 1){
				$('#LsBasicFeeElectName').val(data.building_name); //건물명
				$('#LsBasicFeeKEPCO').val(data.charge_fee_before.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //한전 청구액
				$('#LsBasicFeeDeduction').val(data.deduct_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //공제액
				$('#LsBasicFeeDeductionReason').val(data.deduct_reason); //공제사유
				$('#LsBasicFeeDeductionAfterClaim').val(data.charge_fee_after.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //공제 후 청구액
				$('#LsBasicFeeElectFee').val(data.use_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //전기사용량
				$('#LsBasicFeeElectFeeDefault').val(data.use_basicfee_per.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //kwh당 기본료
				$('#LsBasicFeeElectFeeDateRange1').val(data.use_start_date); //전기 사용기간 시작일
				$('#LsBasicFeeElectFeeDateRange2').val(data.use_end_date); //전기 사용기간 종료일

				$('#basicfee_seq_a').val(basicfee_seq);
			}else if(fee_gubun_num == 2){
				$('#LsBasicFeeWaterName').val(data.building_name); //건물명
				$('#LsBasicFeeWaterClaim').val(data.charge_fee_before.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //수도 청구액
				$('#LsBasicFeeWaterDeduction').val(data.deduct_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //공제액
				$('#LsBasicFeeWaterDeductionReason').val(data.deduct_reason); //공제사유
				$('#LsBasicFeeWaterDeductionAfterClaim').val(data.charge_fee_after.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //공제 후 청구액
				$('#LsBasicFeeWaterFee').val(data.use_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //수도사용량
				$('#LsBasicFeeWaterFeeDefault').val(data.use_basicfee_per.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //ton당 기본료
				$('#LsBasicFeeWaterFeeDateRange1').val(data.use_start_date); //전기 사용기간 시작일
				$('#LsBasicFeeWaterFeeDateRange2').val(data.use_end_date); //전기 사용기간 종료일

				$('#basicfee_seq_b').val(basicfee_seq);
			}else if(fee_gubun_num == 3){
				$('#LsBasicFeeGasName').val(data.building_name); //건물명
				$('#LsBasicFeeKGC').val(data.charge_fee_before.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //가스 청구액
				$('#LsBasicFeeGasDeduction').val(data.deduct_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //공제액
				$('#LsBasicFeeGasDeductionReason').val(data.deduct_reason); //공제사유
				$('#LsBasicFeeGasDeductionAfterClaim').val(data.charge_fee_after.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //공제 후 청구액
				$('#LsBasicFeeGasFee').val(data.use_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //전기사용량
				$('#LsBasicFeeGasFeeDefault').val(data.use_basicfee_per.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //kwh당 기본료
				$('#LsBasicFeeGasFeeDateRange1').val(data.use_start_date); //전기 사용기간 시작일
				$('#LsBasicFeeGasFeeDateRange2').val(data.use_end_date); //전기 사용기간 종료일

				$('#basicfee_seq_c').val(basicfee_seq);
			}else if(fee_gubun_num == 4){
				$('#LsBasicFeeEtcName').val(data.building_name); //건물명
				$('#LsBasicFeeEtcClaim').val(data.charge_fee_before.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //기타 청구액
				$('#LsBasicFeeEtcDeduction').val(data.deduct_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //공제액
				$('#LsBasicFeeEtcDeductionReason').val(data.deduct_reason); //공제사유
				$('#LsBasicFeeEtcDeductionAfterClaim').val(data.charge_fee_after.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //공제 후 청구액

				$('#basicfee_seq_d').val(basicfee_seq);
			}
		},
		error:function(e){
			console.log(e);
		}
	});

	if(fee_gubun_num == 1){
		$('.ls-modal.elect-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});

		//수도 모달 닫기 
		$('.ls-modal.water-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
		$('#waterFormModal')[0].reset();

		//가스 모달 닫기
		$('.ls-modal.gas-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
		$('#gasFormModal')[0].reset();

		//기타 모달 닫기
		$('.ls-modal.etc-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
		$('#etcFormModal')[0].reset();
	}else if(fee_gubun_num == 2){
		$('.ls-modal.water-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});

		//전기 모달 닫기
		$('.ls-modal.elect-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
		$('#electFormModal')[0].reset();

		//가스 모달 닫기
		$('.ls-modal.gas-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
		$('#gasFormModal')[0].reset();

		//기타 모달 닫기
		$('.ls-modal.etc-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
		$('#etcFormModal')[0].reset();
	}else if(fee_gubun_num == 3){
		$('.ls-modal.gas-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});

		//전기 모달 닫기
		$('.ls-modal.elect-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
		$('#electFormModal')[0].reset();

		//수도 모달 닫기
		$('.ls-modal.water-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
		$('#waterFormModal')[0].reset();

		//기타 모달 닫기
		$('.ls-modal.etc-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
		$('#etcFormModal')[0].reset();
	}else if(fee_gubun_num == 4){
		$('.ls-modal.etc-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});

		//전기 모달 닫기
		$('.ls-modal.elect-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');

		//수도 모달 닫기
		$('.ls-modal.water-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');

		//가스 모달 닫기
		$('.ls-modal.gas-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	}

}

function formCheck(fee_gubun){

	if(fee_gubun == 'A'){
		var building_name = $('#LsBasicFeeElectName').val(); //건물명
		var charge_fee_before = $('#LsBasicFeeKEPCO').val(); //청구액
		var deduct_fee = $('#LsBasicFeeDeduction').val(); //공제액
		var deduct_reason = $('#LsBasicFeeDeductionReason').val(); //공제사유
		var charge_fee_after = $('#LsBasicFeeDeductionAfterClaim').val(); //공제 후 청구액
		var use_amount = $('#LsBasicFeeElectFee').val(); //전기사용량
		var use_basicfee_per = $('#LsBasicFeeElectFeeDefault').val(); //kwh당 기본료
		var use_start_date = $('#LsBasicFeeElectFeeDateRange1').val(); //전기 사용기간 시작일
		var use_end_date = $('#LsBasicFeeElectFeeDateRange2').val(); //전기 사용기간 종료일
		var basicfee_seq = $('#basicfee_seq_a').val();

		if(building_name == ""){
			alert('건물명을 입력해주세요.');
			$('#LsBasicFeeElectName').focus();
			return;
		}
		if(charge_fee_before == ""){
			alert('청구액을 입력해주세요.');
			$('#LsBasicFeeKEPCO').focus();
			return;
		}
		if(deduct_fee == ""){
			alert('공제액을 입력해주세요.');
			$('#LsBasicFeeDeduction').focus();
			return;
		}
		if(deduct_reason == ""){
			alert('공제사유를 입력해주세요.');
			$('#LsBasicFeeDeductionReason').focus();
			return;
		}
		if(charge_fee_after == ""){
			alert('공제 후 청구액을 입력해주세요.');
			$('#LsBasicFeeDeductionAfterClaim').focus();
			return;
		}
		if(use_amount == ""){
			alert('전기 사용량을 입력해주세요.');
			$('#LsBasicFeeElectFee').focus();
			return;
		}
		if(use_basicfee_per == ""){
			alert('기본료를 입력해주세요.');
			$('#LsBasicFeeElectFeeDefault').focus();
			return;
		}
		if(use_start_date == ""){
			alert('사용 기간 시작일을 입력해주세요.');
			$('#LsBasicFeeElectFeeDateRange1').focus();
			return;
		}
		if(use_end_date == ""){
			alert('사용 기간 종료일을 입력해주세요.');
			$('#LsBasicFeeElectFeeDateRange2').focus();
			return;
		}

	}else if(fee_gubun == 'B'){
		var building_name = $('#LsBasicFeeWaterName').val();
		var charge_fee_before = $('#LsBasicFeeWaterClaim').val();
		var deduct_fee = $('#LsBasicFeeWaterDeduction').val(); //공제액
		var deduct_reason = $('#LsBasicFeeWaterDeductionReason').val(); //공제사유
		var charge_fee_after = $('#LsBasicFeeWaterDeductionAfterClaim').val(); //공제 후 청구액
		var use_amount = $('#LsBasicFeeWaterFee').val(); //전기사용량
		var use_basicfee_per = $('#LsBasicFeeWaterFeeDefault').val(); //kwh당 기본료
		var use_start_date = $('#LsBasicFeeWaterFeeDateRange1').val(); //전기 사용기간 시작일
		var use_end_date = $('#LsBasicFeeWaterFeeDateRange2').val(); //전기 사용기간 종료일
		var basicfee_seq = $('#basicfee_seq_b').val();

		if(building_name == ""){
			alert('건물명을 입력해주세요.');
			$('#LsBasicFeeWaterName').focus();
			return;
		}
		if(charge_fee_before == ""){
			alert('청구액을 입력해주세요.');
			$('#LsBasicFeeWaterClaim').focus();
			return;
		}
		if(deduct_fee == ""){
			alert('공제액을 입력해주세요.');
			$('#LsBasicFeeWaterDeduction').focus();
			return;
		}
		if(deduct_reason == ""){
			alert('공제사유를 입력해주세요.');
			$('#LsBasicFeeWaterDeductionReason').focus();
			return;
		}
		if(charge_fee_after == ""){
			alert('공제 후 청구액을 입력해주세요.');
			$('#LsBasicFeeWaterDeductionAfterClaim').focus();
			return;
		}
		if(use_amount == ""){
			alert('수도 사용량을 입력해주세요.');
			$('#LsBasicFeeWaterFee').focus();
			return;
		}
		if(use_basicfee_per == ""){
			alert('단가를 입력해주세요.');
			$('#LsBasicFeeWaterFeeDefault').focus();
			return;
		}
		if(use_start_date == ""){
			alert('사용 기간 시작일을 입력해주세요.');
			$('#LsBasicFeeWaterFeeDateRange1').focus();
			return;
		}
		if(use_end_date == ""){
			alert('사용 기간 종료일을 입력해주세요.');
			$('#LsBasicFeeWaterFeeDateRange2').focus();
			return;
		}


	}else if(fee_gubun == 'C'){
		var building_name = $('#LsBasicFeeGasName').val();
		var charge_fee_before = $('#LsBasicFeeKGC').val();
		var deduct_fee = $('#LsBasicFeeGasDeduction').val(); //공제액
		var deduct_reason = $('#LsBasicFeeGasDeductionReason').val(); //공제사유
		var charge_fee_after = $('#LsBasicFeeGasDeductionAfterClaim').val(); //공제 후 청구액
		var use_amount = $('#LsBasicFeeGasFee').val(); //전기사용량
		var use_basicfee_per = $('#LsBasicFeeGasFeeDefault').val(); //kwh당 기본료
		var use_start_date = $('#LsBasicFeeGasFeeDateRange1').val(); //전기 사용기간 시작일
		var use_end_date = $('#LsBasicFeeGasFeeDateRange2').val(); //전기 사용기간 종료일
		var basicfee_seq = $('#basicfee_seq_c').val();

		if(building_name == ""){
			alert('건물명을 입력해주세요.');
			$('#LsBasicFeeGasName').focus();
			return;
		}
		if(charge_fee_before == ""){
			alert('청구액을 입력해주세요.');
			$('#LsBasicFeeKGC').focus();
			return;
		}
		if(deduct_fee == ""){
			alert('공제액을 입력해주세요.');
			$('#LsBasicFeeGasDeduction').focus();
			return;
		}
		if(deduct_reason == ""){
			alert('공제사유를 입력해주세요.');
			$('#LsBasicFeeGasDeductionReason').focus();
			return;
		}
		if(charge_fee_after == ""){
			alert('공제 후 청구액을 입력해주세요.');
			$('#LsBasicFeeGasDeductionAfterClaim').focus();
			return;
		}
		if(use_amount == ""){
			alert('가스 사용량을 입력해주세요.');
			$('#LsBasicFeeGasFee').focus();
			return;
		}
		if(use_basicfee_per == ""){
			alert('단가를 입력해주세요.');
			$('#LsBasicFeeGasFeeDefault').focus();
			return;
		}
		if(use_start_date == ""){
			alert('사용 기간 시작일을 입력해주세요.');
			$('#LsBasicFeeGasFeeDateRange1').focus();
			return;
		}
		if(use_end_date == ""){
			alert('사용 기간 종료일을 입력해주세요.');
			$('#LsBasicFeeGasFeeDateRange2').focus();
			return;
		}

	}else if(fee_gubun == 'D') {
		var building_name = $('#LsBasicFeeEtcName').val();
		var charge_fee_before = $('#LsBasicFeeEtcClaim').val();
		var deduct_fee = $('#LsBasicFeeEtcDeduction').val(); //공제액
		var deduct_reason = $('#LsBasicFeeEtcDeductionReason').val(); //공제사유
		var charge_fee_after = $('#LsBasicFeeEtcDeductionAfterClaim').val(); //공제 후 청구액
		var use_amount = '0'; //사용량
		var use_basicfee_per = '0'; //kwh당 기본료
		var use_start_date = ''; //전기 사용기간 시작일
		var use_end_date = ''; //전기 사용기간 종료일
		var basicfee_seq = $('#basicfee_seq_d').val();

		if(building_name == ""){
			alert('건물명을 입력해주세요.');
			$('#LsBasicFeeEtcName').focus();
			return;
		}
		if(charge_fee_before == ""){
			alert('청구액을 입력해주세요.');
			$('#LsBasicFeeEtcClaim').focus();
			return;
		}
		if(deduct_fee == ""){
			alert('공제액을 입력해주세요.');
			$('#LsBasicFeeEtcDeduction').focus();
			return;
		}
		if(deduct_reason == ""){
			alert('공제사유를 입력해주세요.');
			$('#LsBasicFeeEtcDeductionReason').focus();
			return;
		}
		if(charge_fee_after == ""){
			alert('공제 후 청구액을 입력해주세요.');
			$('#LsBasicFeeEtcDeductionAfterClaim').focus();
			return;
		}

	}

	var data = {};
	var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi


	data['building_name'] = building_name;
	data['charge_fee_before'] = charge_fee_before.replace(regExp, "");
	data['deduct_fee'] = deduct_fee.replace(regExp, "");
	data['deduct_reason'] = deduct_reason;
	data['charge_fee_after'] = charge_fee_after.replace(regExp, "");
	data['use_amount'] = use_amount.replace(regExp, "");
	data['use_basicfee_per'] = use_basicfee_per.replace(regExp, "");
	data['use_start_date'] = use_start_date;
	data['use_end_date'] = use_end_date;
	data['basicfee_seq'] = basicfee_seq;

	modBasicFeeInfo(data);

}

$(document).ready(function(){
	$('#buildingListSelect').on('change', function(){
		var building_seq = this.value;
		loadFacilityList(building_seq);
	});
})

function modBasicFeeInfo(data){
	$.ajax({
		url:'/BasicFee/modBasicFeeInfo',
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

function loadFacilityList(building_seq){
	$.ajax({
		url:'/Facility/getFacilityInfo',
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

function renderFacilitySelect(data){
	var html = '';
	$('#FacilityListSelect').empty();

	if(data == null){
		html += '<option value="" selected>등록된 시설이 없습니다.</option>';
	}else{
		//html += '<option value="" selected>선택해주세요</option>';
		for(var i=0; i<data.length; i++){
			var room_num = data[i].room_num;
			var lm_room_seq = data[i].lm_room_seq;
			html += '<option value="'+lm_room_seq+'">'+room_num+'호</option>';
		}
	}

	$('#FacilityListSelect').append(html);
}

function getPost(){
	var building_seq = $('#buildingListSelect').val();
	var fee_gubun = $('#feeGubunSelect').val();
	var lm_ym = $('#basicFeeDateSELECT').val();


	$.ajax({
		url:'/BasicFee/getBasicFeeList',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq, fee_gubun:fee_gubun, lm_ym:lm_ym},
		success:function(data){
			renderSelectTable(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderSelectTable(data){
	$('#facilityBody').empty();
	var html = '';
	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li style="max-width:1200px;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';

	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			console.log(dataItem);

			var claim_year = dataItem.claim_year;
			var claim_month = dataItem.claim_month;
			var claim_ym = claim_year+"-"+claim_month;
			var fee_gubun = dataItem.fee_gubun;
			var fee_gubun_var = '';
			var fee_gubun_num = '';
			var gubun_usage = "";

			var charge_fee = dataItem.charge_fee_after;
			var use_amount = dataItem.use_amount;
			var use_basicfee_per = dataItem.use_basicfee_per;
			var building_seq = dataItem.lm_building_seq;
			var basicfee_seq = dataItem.seq;

			var data_range = dataItem.use_start_date + "~" + dataItem.use_end_date;

			if(fee_gubun == 'A'){
				fee_gubun_var = '전기';
				fee_gubun_num = 1;
				gubun_usage = 'kw';
			}else if(fee_gubun == 'B'){
				fee_gubun_var = '수도';
				fee_gubun_num = 2;
				gubun_usage = 't';
			}else if(fee_gubun == 'C'){
				fee_gubun_var = '가스';
				fee_gubun_num = 3;
				gubun_usage = 't';
			}else if(fee_gubun == 'D'){
				fee_gubun_var = '기타';
				fee_gubun_num = 4;
				gubun_usage = '';
				use_basicfee_per = '0';

			}


			html += '<ul class="ls-detail-body__tb">';
			html += '<li><strong class="mb-title">귀속년월</strong><span>'+claim_ym+'</span></li>';
			html += '<li><strong class="mb-title">사용기간</strong><span>'+data_range+'</li>';
			html += '<li><strong class="mb-title">구분</strong><span>'+fee_gubun_var+'</li>';
			html += '<li><strong class="mb-title">청구액(원)</strong><span>'+charge_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
			html += '<li><strong class="mb-title">사용량</strong><span>'+use_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
			html += '<li><strong class="mb-title">단가</strong><span>'+use_basicfee_per.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
			html += '<li><strong class="mb-title">관리</strong><button class="click-button elect-setting" type="button" onclick="openmodal('+fee_gubun_num+', '+basicfee_seq+', '+building_seq+')">단가 수정</button></li>';
			html += '</ul>';

		}
	}


	$('#basicFeeList').html(html);
}
