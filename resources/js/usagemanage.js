$(document).ready(function(){

	var pageNum = 1;
	var pagination;
	var DATA_URL = '/Building/getBuildingList';

	function getParams(){
		var data = {'cate': $('.cate').val(), 'date': $('.selectdate').val()};
		return data;
	}


	init();

	function init(){
		loadListData();
		getPrevclaim();
		loadBuildingList();
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/UsageManage/getUsageManageList',
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
				var seq = dataItem.seq;
				var building_name = dataItem.building_name;
				var rooom_num = dataItem.lm_room_num;
				var charge_year = dataItem.lm_charge_year;
				var charge_month = dataItem.lm_charge_month;

				var usage_amount = dataItem.lm_usage_amount;
				var usage_cost = dataItem.lm_usage_cost;
				var usage_meter = dataItem.lm_usage_meter;



				var fee_gubun = dataItem.lm_usage_gubun;
				var fee_gubun_var = '';
				var fee_gubun_num = '';
				var gubun_usage = "";

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
				}


				html += '<ul class="ls-detail-body__tb" id="seq" value="'+seq+'">';
				html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
				html += '<li><strong class="mb-title">호실</strong><span>'+rooom_num+'</li>';
				html += '<li><strong class="mb-title">해당년</strong><span>'+charge_year+'</li>';
				html += '<li><strong class="mb-title">해당월</strong><span>'+charge_month+'</li>';
				html += '<li><strong class="mb-title">구분</strong><span>'+fee_gubun_var+'</li>';
				html += '<li><strong class="mb-title">지침</strong><span>'+usage_meter.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
				html += '<li><strong class="mb-title">사용량</strong><span>'+usage_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
				html += '<li><strong class="mb-title">사용금액</strong><span>'+usage_cost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
				html += '<li><strong class="mb-title">관리</strong><button class="click-button elect-setting" type="button" onclick="openmodal('+seq+')">관리</button></li>';
				html += '</ul>';

			}
		}

		$('#usageManageList').append(html);
	}
});

function openmodal(seq){

	$.ajax({
		url:'/UsageManage/getUsageManageInfoIndi',
		type:"POST",
		dataType:'JSON',
		data: {seq:seq},
		success:function(data){
			var lm_usage_gubun_var = '';
			if(data.lm_usage_gubun == 'A'){
				lm_usage_gubun_var = "전기";
			}else if(data.ln_usage_gubun == 'B'){
				lm_usage_gubun_var = "수도";
			}else if(data.ln_usage_gubun == 'C'){
				lm_usage_gubun_var = "가스";
			}else if(data.ln_usage_gubun == 'D'){
				lm_usage_gubun_var = "기타";
			}
			$('#usagemanage_seq').val(data.seq);
			$('#UsageManageBuildingName').val(data.building_name);
			$('#UsageManageFeeGubun').val(lm_usage_gubun_var);
			$('#UsageManageRoomNum').val(data.lm_room_num);
			$('#UsageManageClaimYear').val(data.lm_charge_year);
			$('#UsageManageClaimMonth').val(data.lm_charge_month);
			$('#UsageManageMeter').val(data.lm_usage_meter.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#UsageManageAmount').val(data.lm_usage_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#UsageManageCost').val(data.lm_usage_cost);
		},
		error:function(e){
			console.log(e);
		}
	});

	$('.ls-modal.usage-modify').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
}

function modifyUsageManageInfo(){
	var seq = $('#usagemanage_seq').val();
	var lm_usage_amount = $('#UsageManageAmount').val();
	var lm_usage_meter = $('#UsageManageMeter').val();
	var lm_usage_cost = $('#UsageManageCost').val();

	$.ajax({
		url:'/UsageManage/modUsageManageIndi',
		type:"POST",
		dataType:'JSON',
		data:{seq:seq, lm_usage_amount:lm_usage_amount, lm_usage_meter:lm_usage_meter, lm_usage_cost:lm_usage_cost},
		success:function(data){
			alert(data.msg);
			location.reload();
		},
		error:function(e){
			console.log(e);
		}
	});
}

function opentotalModal(){

	$('.ls-modal.usage-setting').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});

}

function formCheck(){

	alert("사용량 데이터는 현월 지침으로 설정된 달의 데이터만 수정됩니다.");
	var building_seq = $("#buildingListSelect_modal option:selected").val(); //건물명
	var fee_gubun = $("#feeGubunSelect_modal option:selected").val();  //청구액
	var rows_cnt = $('#rows_cnt').val();
	var claim_ym = $('#thisClaim option:selected').val();
	var split_var = claim_ym.split("-");
	var claim_year = split_var[0];
	var claim_month = split_var[1];

	var claim_ym_prev = $("#prevClaim option:selected").val();
	var split_var_prev = claim_ym_prev.split("-");
	var claim_year_prev = split_var_prev[0];
	var claim_month_prev = split_var_prev[1];

	var data = {};
	var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi

	if(building_seq == ""){
		alert('건물명을 선택해주세요.');
		$('#buildingListSelect').focus();
		return;
	}
	if(fee_gubun == ""){
		alert('구분을 선택해주세요.');
		$('#feeGubunSelect_modal').focus();
		return;
	}

	for(var i=0; i<rows_cnt; i++){
		var room_num = $("#usageRoomNum"+i).val();
		var usage_meter = $("#thisClaimList"+i).val();
		var usage_meter_prev = $("#prevClaimList"+i).val();
		var usage_amount = $("#usageAmount"+i).val(); //호실별 사용량
		var usage_cost = $("#usageCost"+i).val(); //호실별 사용금액

		data['room_num'] = room_num;
		data['usage_amount'] = usage_amount.replace(regExp, "");
		data['usage_cost'] = usage_cost.replace(regExp, "");
		data['building_seq'] = building_seq;
		data['usage_gubun'] = fee_gubun;
		data['usage_meter'] = usage_meter.replace(regExp, "");
		data['usage_meter_prev'] = usage_meter_prev.replace(regExp, "");
		data['claim_year'] = claim_year;
		data['claim_month'] = claim_month;
		data['claim_year_prev'] = claim_year_prev;
		data['claim_month_prev'] = claim_month_prev;

		modBasicFeeInfo(data, rows_cnt, i+1);
	}

}

function modBasicFeeInfo(data, rows_cnt, i){
	$.ajax({
		url:'/UsageManage/modUsageManageInfo',
		type:"POST",
		dataType:'JSON',
		data:data,
		success:function(data){
			if(rows_cnt == i){
				//console.log(data);
				alert(data.msg);
				$('.ls-modal.usage-setting').removeClass('open');
				$("body").css({overflow:'auto'}).unbind('touchmove');
				location.reload();
			}
		},
		error:function(e){
			console.log(e);
		}
	});
}

$(document).ready(function(){
	//건물 select 감지
	$('#buildingListSelect_modal').on('change', function(){
		var building_seq = this.value;
		getPrevclaim();
	});

	$('#buildingListSelect').on('change', function(){
		var building_seq = this.value;
		loadFacilityList(building_seq);
	});

	//전월 지침 select 감지
	$('#prevClaim').on('change', function(){
		renderPrevClaimList();
	});

	//전월 지침 select 감지
	$('#thisClaim').on('change', function(){
		renderThisClaimList();
	});


	$('#feeGubunSelect_modal').on('change', function(){
		var fee_gubun = this.value;
		var fee_var = "";

		if(fee_gubun == 'A'){
			fee_var = "전기지침";
		}else if(fee_gubun == 'B'){
			fee_var = "수도지침";
		}else if(fee_gubun == 'C'){
			fee_var = "가스지침";
		}else if(fee_gubun == 'D'){
			fee_var = "기타지침";
		}

		$('#fee_gubun_var').text(fee_var);
		$('#fee_gubun_var_this').text(fee_var);


		getPrevclaim();
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

function renderUseAmount(){
	var this_ym = $("#thisClaim option:selected").val();
	if(this_ym != "" && this_ym != undefined){
		var words = this_ym.split('-');
		var this_y = words[0];
		var this_m = words[1];
	}else{
		var this_y = "";
		var this_m = "";
	}
	var fee_gubun = $('#feeGubunSelect_modal option:selected').val();
	var building_seq = $('#buildingListSelect option:selected').val();
	var html = '';

	$('#useageList').empty();

	$.ajax({
		url:'/UsageManage/getThisClaimList',
		type:"POST",
		dataType:'JSON',
		data: {this_y:this_y, this_m:this_m, fee_gubun:fee_gubun, building_seq:building_seq},
		success:function(data){
			var dataItems = data.length;
			$('#rows_cnt').val(dataItems);

			for(var i=0; i<dataItems; i++){
				var room_num = $('#prevClaimRoonNum'+i).val();
				var usage_amount_prev = $('#prevClaimList'+i).val();
				var usage_amount_this = $('#thisClaimList'+i).val();

				var usage_amount = usage_amount_this - usage_amount_prev;
				var basicfee = data[i]['basicfee'];
				var use_fee = usage_amount * basicfee;

				html += '<div class="ls-form-wrap third"><input class="ls-form" name="usageRoomNum" id="usageRoomNum'+i+'" value="'+room_num+'" readonly /></div>';
				html += '<div class="ls-form-wrap third"><input class="ls-form" name="usageAmount" id="usageAmount'+i+'" value="'+usage_amount+'"/></div>';
				html += '<div class="ls-form-wrap third"><input class="ls-form" name="usageCost" id="usageCost'+i+'" value="'+use_fee+'"/></div>';
			}

			$('#useageList').append(html);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderUseAmountCal(){
	var this_ym = $("#thisClaim option:selected").val();
	var words = this_ym.split('-');
	var this_y = words[0];
	var this_m = words[1];
	var fee_gubun = $('#feeGubunSelect_modal option:selected').val();
	var building_seq = $('#buildingListSelect_modal option:selected').val();
	var html = '';

	$('#useageList').empty();

	$.ajax({
		url:'/UsageManage/getThisClaimList',
		type:"POST",
		dataType:'JSON',
		data: {this_y:this_y, this_m:this_m, fee_gubun:fee_gubun, building_seq:building_seq},
		success:function(data){
			console.log(data);
			var dataItems = data.length;
			$('#rows_cnt').val(dataItems);

			for(var i=0; i<dataItems; i++){
				var room_num = $('#prevClaimRoonNum'+i).val();
				var usage_amount_prev = $('#prevClaimList'+i).val();
				var usage_amount_this = $('#thisClaimList'+i).val();

				var usage_amount = parseInt(usage_amount_this) - parseInt(usage_amount_prev);
				var basicfee = data[i]['basicfee'];
				var use_fee = usage_amount * basicfee;

				html += '<div class="ls-form-wrap third"><input class="ls-form" name="usageRoomNum" id="usageRoomNum'+i+'" value="'+room_num+'" readonly/></div>';
				html += '<div class="ls-form-wrap third"><input class="ls-form" name="usageAmount" id="usageAmount'+i+'" value="'+usage_amount+'"/></div>';
				html += '<div class="ls-form-wrap third"><input class="ls-form" name="usageCost" id="usageCost'+i+'" value="'+use_fee+'"/></div>';
			}

			$('#useageList').append(html);
			return;
		},
		error:function(e){
			console.log(e);
		}
	});
}


function getPrevclaim(){
	//전월지침, 현월지침 불러오기 초기화
	$('#prevClaim').empty();
	$('#thisClaim').empty();
	$.ajax({
		url:'/UsageManage/getPrevClaimSelect',
		type:"POST",
		dataType:'JSON',
		success:function(data){
			renderSelect(data);
			renderPrevClaimList(data);
			renderThisClaimList(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderPrevClaimList(){
	$('#prevClaimList').empty('');
	var prev_ym = $("#prevClaim option:selected").val();

	var words = (prev_ym||'').split('-');
	var prev_y = words[0];
	var prev_m = words[1];
	var fee_gubun = $('#feeGubunSelect_modal option:selected').val();
	var building_seq = $('#buildingListSelect_modal option:selected').val();
	var html = '';

	$.ajax({
		url:'/UsageManage/getPrevClaimList',
		type:"POST",
		dataType:'JSON',
		data: {prev_y:prev_y, prev_m:prev_m, fee_gubun:fee_gubun, building_seq:building_seq},
		success:function(data){
			console.log(data);
			for(var i=0; i<data.length; i++){
				html += '<div class="ls-form-wrap third"><input class="ls-form" name="prevClaimRoonNum" id="prevClaimRoonNum'+i+'" value="'+data[i].lm_room_num+'" readonly/></div>';
				html += '<div class="ls-form-wrap quarter" style="padding-right:0;"><input class="ls-form" name="prevClaimList" id="prevClaimList'+i+'" value="'+data[i].lm_usage_meter+'"/></div>';
			}

			$('#prevClaimList').append(html);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderThisClaimList(){
	$('#thisClaimList').empty('');
	var this_ym = $("#thisClaim option:selected").val();
	if(this_ym != "" && this_ym != undefined){
		var words = this_ym.split('-');
		var this_y = words[0];
		var this_m = words[1];
	}else{
		var this_y = "";
		var this_m = "";
	}
	var fee_gubun = $('#feeGubunSelect_modal option:selected').val();
	var building_seq = $('#buildingListSelect_modal option:selected').val();
	var html = '';

	$.ajax({
		url:'/UsageManage/getThisClaimList',
		type:"POST",
		dataType:'JSON',
		data: {this_y:this_y, this_m:this_m, fee_gubun:fee_gubun, building_seq:building_seq},
		success:function(data){
			for(var i=0; i<data.length; i++){


				html += '<div class="ls-form-wrap third"><input class="ls-form" name="thisClaimRoonNum" id="thisClaimRoonNum'+i+'" value="'+data[i].lm_room_num+'" readonly/></div>';
				html += '<div class="ls-form-wrap quarter" style="padding-right:0;"><input class="ls-form" name="thisClaimList" id="thisClaimList'+i+'" value="'+data[i].lm_usage_meter+'"/></div>';
			}

			$('#thisClaimList').append(html);

			renderUseAmount();
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderSelect(data){
	var html = '';

	for(var i=0; i<data.length; i++){
		var value = data[i];

		html += '<option value="'+value+'">'+value+'</option>';
	}

	$('#thisClaim').append(html);
	$('#prevClaim').append(html);
}

function loadBuildingList() {
	$.ajax({
		url:'/Facility/getBuildingList',
		type:"POST",
		dataType:'JSON',
		success:function(data){
			//console.log(data);
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
	$('#buildingListSelect_modal').append(html);
}

function getPost(){
	var building_seq = $('#buildingListSelect').val();
	var fee_gubun = $('#feeGubunSelect').val();
	var lm_ym = $('#basicFeeDateSELECT').val();
	var room_num = $('#FacilityListSelect').val();


	$.ajax({
		url:'/UsageManage/getUsageManageList',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq, fee_gubun:fee_gubun, lm_ym:lm_ym, room_num:room_num},
		success:function(data){
			renderSelectTable(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderSelectTable(data){
	$('#usageManageList').empty();
	var html = '';

	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li style="max-width:1200px;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';

	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			var seq = dataItem.seq;
			var building_name = dataItem.building_name;
			var rooom_num = dataItem.lm_room_num;
			var charge_year = dataItem.lm_charge_year;
			var charge_month = dataItem.lm_charge_month;

			var usage_amount = dataItem.lm_usage_amount;
			var usage_cost = dataItem.lm_usage_cost;
			var usage_meter = dataItem.lm_usage_meter;



			var fee_gubun = dataItem.lm_usage_gubun;
			var fee_gubun_var = '';
			var fee_gubun_num = '';
			var gubun_usage = "";

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
			}


			html += '<ul class="ls-detail-body__tb" id="seq" value="'+seq+'">';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
			html += '<li><strong class="mb-title">호실</strong><span>'+rooom_num+'</li>';
			html += '<li><strong class="mb-title">해당년</strong><span>'+charge_year+'</li>';
			html += '<li><strong class="mb-title">해당월</strong><span>'+charge_month+'</li>';
			html += '<li><strong class="mb-title">구분</strong><span>'+fee_gubun_var+'</li>';
			html += '<li><strong class="mb-title">지침</strong><span>'+usage_meter.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
			html += '<li><strong class="mb-title">사용량</strong><span>'+usage_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
			html += '<li><strong class="mb-title">사용금액</strong><span>'+usage_cost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</li>';
			html += '<li><strong class="mb-title">관리</strong><button class="click-button elect-setting" type="button" onclick="openmodal('+seq+')">관리</button></li>';
			html += '</ul>';

		}
	}

	$('#usageManageList').append(html);
}
