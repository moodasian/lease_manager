$(document).ready(function(){
	var pageNum = 1;
	var pagination;

	init();

	function init(){
		loadListData();
		loadBuildingList();
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/Arrears/getArrearsList',
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
			html += '<li style="max-width:1200px; width:100%;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
			html += '</ul>';
		}else{
			for(var i=0; i<data.length; i++){
				//console.log(data);

				var dataItem = data[i];
				var building_name = dataItem.building_name;
				var arrears_ym = dataItem.lm_year + "." + dataItem.lm_month;
				var room_num = dataItem.lm_room_num;
				var total_claim_fee = dataItem.total_claim_fee;
				var total_income = dataItem.total_income;
				var this_arrears = dataItem.this_arrears;
				var prev_arrears = dataItem.prev_arrears;
				var total_arrears = dataItem.total_arrears;
				var building_seq = dataItem.lm_building_seq;
				var lm_year = "'"+dataItem.lm_year+"'";
				var lm_month = "'"+dataItem.lm_month+"'";

				html += '<ul class="ls-detail-body__tb" id="data_row" >';
				html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
				html += '<li><strong class="mb-title">호실</strong><span>'+room_num+'</span></li>';
				html += '<li><strong class="mb-title">귀속 년월</strong><span>'+arrears_ym+'</span></li>';
				html += '<li><strong class="mb-title">현월 청구 합계액</strong><span>'+total_claim_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">현월 입금 합계액</strong><span>'+total_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">현월 체납액</strong><span>'+this_arrears.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">전월까지의 체납액</strong><span>'+prev_arrears.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">체납합계액</strong><span>'+total_arrears.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">관리</strong><button class="click-button bill-setting" type="button" onclick="openmodal('+building_seq+', '+room_num+', '+lm_year+', '+lm_month+')" style="margin-left: 5px;">수정</button></li>';
				html += '</ul>';

			}
		}

		$('#arrearsList').append(html);
	}

});

$(document).ready(function(){
	$('#LsArrearsThisArrears').on('change', function(){
		var total_arrears = 0;
		var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi

		var this_arrears = $('#LsArrearsThisArrears').val();
		this_arrears = this_arrears.replace(regExp, "");
		var prev_arrears = $('#LsArrearsLastArrears').val();
		prev_arrears = prev_arrears.replace(regExp, "");

		total_arrears = parseInt(this_arrears) + parseInt(prev_arrears);

		$('#LsArrearsTotalArrears').val(total_arrears);
	});

});


function openmodal(building_seq, room_num, arrears_year, arrears_month){

	$.ajax({
		url:'/Arrears/getArrearsInfoIndi',
		type:"POST",
		dataType:'JSON',
		data: {building_seq:building_seq, room_num:room_num, arrears_year:arrears_year, arrears_month:arrears_month},
		success:function(data){
			//console.log(data);
			var lm_year = data.lm_year;
			var lm_month = data.lm_month;
			var lm_ym = lm_year+"-"+lm_month;
			var building_seq = data.lm_building_seq;
			var room_num = data.lm_room_num;
			var prev_arrears = data.prev_arrears;
			var total_arrears = data.total_arrears;

			$('#LsArrearsRoomNum').val(room_num);
			$('#LsArrearsDate').val(lm_ym);
			$('#LsArrearsThisArrears').val(data.this_arrears);
			$('#LsArrearsLastArrears').val(prev_arrears);
			$('#building_seq').val(building_seq);
			$('#lm_year').val(lm_year);
			$('#lm_month').val(lm_month);
			$('#LsArrearsTotalArrears').val(total_arrears);
		},
		error:function(e){
			console.log(e);
		}
	});


	$('.ls-modal.arrears-setting').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
}

function formCheck(){

	var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi

	var building_seq = $("#building_seq").val(); //건물명
	var room_num = $("#LsArrearsRoomNum").val();  //청구액
	var lm_year = $('#lm_year').val();
	var lm_month = $('#lm_month').val();
	var this_arrears = $('#LsArrearsThisArrears').val();
	var prev_arrears = $('#LsArrearsLastArrears').val();
	var total_arrears = $("#LsArrearsTotalArrears").val();

	var data = {};

	data['lm_room_num'] = room_num;
	data['lm_building_seq'] = building_seq;
	data['lm_year'] = lm_year;
	data['lm_month'] = lm_month;
	data['this_arrears'] = this_arrears.replace(regExp, "");
	data['prev_arrears'] = prev_arrears.replace(regExp, "");
	data['lm_total_arrears'] = total_arrears.replace(regExp, "");
	modBasicFeeInfo(data);

}

function modBasicFeeInfo(data){
	$.ajax({
		url:'/Arrears/modArrearsInfo',
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
		url:'/Arrears/getArrearsList',
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
	var html = '';

	$('#arrearsList').empty();
	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li style="max-width:1200px; width:100%;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';
	}else{
		for(var i=0; i<data.length; i++){
			//console.log(data);

			var dataItem = data[i];
			var building_name = dataItem.building_name;
			var arrears_ym = dataItem.lm_year + "." + dataItem.lm_month;
			var room_num = dataItem.lm_room_num;
			var total_claim_fee = dataItem.total_claim_fee;
			var total_income = dataItem.total_income;
			var this_arrears = dataItem.this_arrears;
			var prev_arrears = dataItem.prev_arrears;
			var total_arrears = dataItem.total_arrears;
			var building_seq = dataItem.lm_building_seq;
			var lm_year = "'"+dataItem.lm_year+"'";
			var lm_month = "'"+dataItem.lm_month+"'";

			html += '<ul class="ls-detail-body__tb" id="data_row" >';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
			html += '<li><strong class="mb-title">호실</strong><span>'+room_num+'</span></li>';
			html += '<li><strong class="mb-title">귀속 년월</strong><span>'+arrears_ym+'</span></li>';
			html += '<li><strong class="mb-title">현월 청구 합계액</strong><span>'+total_claim_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">현월 입금 합계액</strong><span>'+total_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">현월 체납액</strong><span>'+this_arrears.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">전월까지의 체납액</strong><span>'+prev_arrears.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">체납합계액</strong><span>'+total_arrears.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">관리</strong><button class="click-button bill-setting" type="button" onclick="openmodal('+building_seq+', '+room_num+', '+lm_year+', '+lm_month+')" style="margin-left: 5px;">수정</button></li>';
			html += '</ul>';

		}
	}

	$('#arrearsList').html(html);
}
