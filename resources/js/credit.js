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
			url:'/Credit/getCreditList',
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
				var dataItem = data[i];
				var building_name = "'"+dataItem.building_name+"'";
				var building_name_var = dataItem.building_name;
				var room_num = dataItem.lm_room_num;
				var first_income = dataItem.first_income;
				var second_income = dataItem.second_income;
				var third_income = dataItem.third_income;
				var total_income = dataItem.total_income;
				var income_year = dataItem.income_year;
				var income_month = dataItem.income_month;
				var income_month_var = "'"+income_month+"'";
				var income_ym = income_year + "." + income_month;

				html += '<ul class="ls-detail-body__tb" id="data_row" >';
				html += '<li><strong class="mb-title">건물명</strong><span>'+building_name_var+'</span></li>';
				html += '<li><strong class="mb-title">호실</strong><span>'+room_num+'</span></li>';
				html += '<li><strong class="mb-title">입금합계액</strong><span>'+total_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">입금액1</strong><span>'+first_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">입금액2</strong><span>'+second_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">입금액3</strong><span>'+third_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
				html += '<li><strong class="mb-title">귀속년월</strong><span>'+income_ym+'</span></li>';
				html += '<li><strong class="mb-title">관리</strong><button class="click-button bill-setting" type="button" onclick="openmodal('+building_name+', '+room_num+', '+income_year+', '+income_month_var+')" style="margin-left: 5px;">수정</button></li>';
				html += '</ul>';

			}
		}

		$('#creditList').html(html);
	}
});



$(document).ready(function(){
	$('#LsCreditFirstIncome').on('change', function(){
		var first_income = $('#LsCreditFirstIncome').val();
		var second_income = $('#LsCreditSecondIncome').val();
		var third_income = $('#LsCreditThirdIncome').val();

		var total_income = parseInt(first_income) + parseInt(second_income) + parseInt(third_income);
		$('#LsCreditTotalIncome').val(total_income);
	});

	$('#LsCreditSecondIncome').on('change', function(){
		var first_income = $('#LsCreditFirstIncome').val();
		var second_income = $('#LsCreditSecondIncome').val();
		var third_income = $('#LsCreditThirdIncome').val();

		var total_income = parseInt(first_income) + parseInt(second_income) + parseInt(third_income);
		$('#LsCreditTotalIncome').val(total_income);
	});

	//LsCreditThirdIncome
	$('#LsCreditThirdIncome').on('change', function(){
		var first_income = $('#LsCreditFirstIncome').val();
		var second_income = $('#LsCreditSecondIncome').val();
		var third_income = $('#LsCreditThirdIncome').val();

		var total_income = parseInt(first_income) + parseInt(second_income) + parseInt(third_income);
		$('#LsCreditTotalIncome').val(total_income);
	});
});


function openmodal(building_name, room_num, credit_year, credit_month){

	$.ajax({
		url:'/Credit/getCreditInfoIndi',
		type:"POST",
		dataType:'JSON',
		data: {building_name:building_name, room_num:room_num, credit_year:credit_year, credit_month:credit_month},
		success:function(data){

			var income_year = data.income_year;
			var income_month = data.income_month;
			var income_ym = income_year+"-"+income_month;
			var building_seq = data.lm_building_seq;

			$('#LsCreditBuildingName').val(data.building_name);
			$('#LsCreditRoomNum').val(data.lm_room_num);
			$('#LsCreditYm').val(income_ym);
			$('#LsCreditFirstIncome').val(data.first_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsCreditSecondIncome').val(data.second_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsCreditThirdIncome').val(data.third_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#LsCreditTotalIncome').val(data.total_income.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$('#building_seq').val(building_seq);
			$('#room_num').val(data.lm_room_num);
			$('#income_year').val(data.income_year);
			$('#income_month').val(data.income_month);
		},
		error:function(e){
			console.log(e);
		}
	});


	$('.ls-modal.credit-setting').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
}

function formCheck(){

	var building_seq = $("#building_seq").val(); //건물명
	var room_num = $("#room_num").val();  //청구액
	var claim_year = $('#claim_year').val();
	var claim_month = $('#claim_month').val();
	var building_name = $('#LsCreditBuildingName').val();
	var credit_ym = $('#LsCreditYm').val();
	var first_income = $('#LsCreditFirstIncome').val();
	var second_income = $('#LsCreditSecondIncome').val();
	var third_income = $('#LsCreditThirdIncome').val();
	var total_income = $('#LsCreditTotalIncome').val();

	var data = {};
	var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi

	data['lm_room_num'] = room_num;
	data['lm_building_seq'] = building_seq;
	data['income_year'] = claim_year;
	data['income_month'] = claim_month;
	data['first_income'] = first_income.replace(regExp, "");
	data['second_income'] = second_income.replace(regExp, "");
	data['third_income'] = third_income.replace(regExp, "");
	data['total_income'] = total_income.replace(regExp, "");
	data['building_name'] = building_name;
	data['credit_ym'] = credit_ym;


	modBasicFeeInfo(data);

}

function modBasicFeeInfo(data){

	$.ajax({
		url:'/Credit/modCreditInfo',
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
	var lm_ym = $('#basicFeeDateSELECT').val();
	var words = lm_ym.split('-');
	var lm_year = words[0];
	var lm_month = words[1];
	var room_num = $("#FacilityListSelect").val();


	$.ajax({
		url:'/Credit/getCreditList',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq, room_num:room_num, lm_year:lm_year, lm_month:lm_month},
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
		html += '<li style="max-width:1200px; width:100%;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';
	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			var building_name = "'"+dataItem.building_name+"'";
			var building_name_var = dataItem.building_name;
			var room_num = dataItem.lm_room_num;
			var first_income = dataItem.first_income;
			var second_income = dataItem.second_income;
			var third_income = dataItem.third_income;
			var total_income = dataItem.total_income;
			var income_year = dataItem.income_year;
			var income_month = dataItem.income_month;
			var income_month_var = "'"+income_month+"'";
			var income_ym = income_year + "." + income_month;

			html += '<ul class="ls-detail-body__tb" id="data_row" >';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name_var+'</span></li>';
			html += '<li><strong class="mb-title">호실</strong><span>'+room_num+'</span></li>';
			html += '<li><strong class="mb-title">입금합계액</strong><span>'+total_income.replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">입금액1</strong><span>'+first_income.replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">입금액2</strong><span>'+second_income.replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">입금액3</strong><span>'+third_income.replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">귀속년월</strong><span>'+income_ym+'</span></li>';
			html += '<li><strong class="mb-title">관리</strong><button class="click-button bill-setting" type="button" onclick="openmodal('+building_name+', '+room_num+', '+income_year+', '+income_month_var+')" style="margin-left: 5px;">수정</button></li>';
			html += '</ul>';

		}
	}

	$('#creditList').html(html);
}
