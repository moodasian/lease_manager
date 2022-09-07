$(document).ready(function(){
	var pageNum = 1;
	var pagination;

	init();

	function init(){
		loadListData();
		loadSelectList();
		loadBuildingList();
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/Calculation/getCalculationList',
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
				var building_name = dataItem.building_name;
				var building_seq = dataItem.lm_building_seq;

				var lm_year = dataItem.lm_calculation_year;
				var lm_month_var = dataItem.lm_calculation_month;
				var lm_month = "'"+dataItem.lm_calculation_month+"'";

				var lm_ym = lm_year+'-'+lm_month_var;

				var lm_total_expense = dataItem.lm_total_expense;
				var lm_total_income = dataItem.lm_total_income;

				html += '<ul class="ls-detail-body__tb" id="data_row" >';
				html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
				html += '<li><strong class="mb-title">정산년월</strong><span>'+lm_ym+'</span></li>';
				html += '<li><strong class="mb-title">지출 총액</strong><span>'+lm_total_expense+'</span></li>';
				html += '<li><strong class="mb-title">수입 총액</strong><span>'+lm_total_income+'</span></li>';
				html += '<li><strong class="mb-title">지출내역서</strong><span><button class="click-button calculation-setting" type="button" onclick="openmodal('+building_seq+', '+lm_year+', '+lm_month+')">수정</button></span></li>';
				html += '<li><strong class="mb-title">관리</strong><button class="click-button delete" type="button" onclick="delData('+building_seq+', '+lm_year+', '+lm_month+');">삭제</button></li>';

				html += '</ul>';

			}
		}

		$('#calculationList').html(html);
	}

	function loadSelectList(){
		$.ajax({
			url:'/UsageManage/getPrevClaimSelect',
			type:"POST",
			dataType:'JSON',
			success:function(data){
				renderSelect(data);
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

		$('#LsCalculationDate').append(html);
	}

});

function delData(building_seq, lm_year, lm_month){
	$.ajax({
		url:'/Calculation/delCalculationInfo',
		type:"POST",
		dataType:'JSON',
		data: {building_seq:building_seq, lm_year:lm_year, lm_month:lm_month},
		success:function(data){
			alert(data.msg);
			location.reload();
		},
		error:function(e){
			console.log(e);
		}
	});
}

function summon_past_vals(){
	var building_seq = $("#building_seq").val(); //건물명
	var lm_ym = $("#LsCalculationDate option:selected").val();
	var words = lm_ym.split('-');
	var lm_year = words[0];
	var lm_month = words[1];

	$.ajax({
		url:'/Calculation/getCalculationIndi',
		type:"POST",
		dataType:'JSON',
		data: {building_seq:building_seq, lm_year:lm_year, lm_month:lm_month},
		success:function(data){
			var income_year = data.lm_year;
			var income_month = data.lm_month;
			var income_ym = income_year+"-"+income_month;
			var building_seq = data.lm_building_seq;

			$('#LsCalculationLeft-01').val(income_ym);
			$('#LsCalculationLeft-02').val(data.lm_total_rental_fee);
			$('#LsCalculationLeft-03').val(data.lm_total_management_fee);
			$('#LsCalculationLeft-04').val(data.lm_totaL_etc_fee);
			$('#LsCalculationLeft-05').val(data.lm_total_expense);

			$('#LsCalculationRight-01').val(data.lm_total_electro_income);
			$('#LsCalculationRight-02').val(data.lm_total_water_income);
			$('#LsCalculationRight-03').val(data.lm_total_gas_income);
			$('#LsCalculationRight-04').val(data.lm_total_etc_income);
			$('#LsCalculationRight-05').val(data.lm_total_income);

			$('#building_seq').val(building_seq);
			$('#lm_year').val(data.lm_year);
			$('#lm_month').val(data.lm_month);

		},
		error:function(e){
			console.log(e);
		}
	});

}


function openmodal(building_seq, lm_year, lm_month){

	$.ajax({
		url:'/Calculation/getCalculationIndi',
		type:"POST",
		dataType:'JSON',
		data: {building_seq:building_seq, lm_year:lm_year, lm_month:lm_month},
		success:function(data){
			console.log(data);
			var income_year = data.lm_year;
			var income_month = data.lm_month;
			var income_ym = income_year+"-"+income_month;
			var building_seq = data.lm_building_seq;



			$('#LsCalculationLeft-01').val(income_ym);
			$('#LsCalculationLeft-02').val(data.lm_total_rental_fee);
			$('#LsCalculationLeft-03').val(data.lm_total_management_fee);
			$('#LsCalculationLeft-04').val(data.lm_totaL_etc_fee);
			$('#LsCalculationLeft-05').val(data.lm_total_expense);

			$('#LsCalculationRight-01').val(data.lm_total_electro_income);
			$('#LsCalculationRight-02').val(data.lm_total_water_income);
			$('#LsCalculationRight-03').val(data.lm_total_gas_income);
			$('#LsCalculationRight-04').val(data.lm_total_etc_income);
			$('#LsCalculationRight-05').val(data.lm_total_income);

			$('#building_seq').val(building_seq);
			$('#lm_year').val(data.lm_year);
			$('#lm_month').val(data.lm_month);

		},
		error:function(e){
			console.log(e);
		}
	});


	$('.ls-modal.calculation-setting').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
}

function formCheck(){

	var building_seq = $("#building_seq").val(); //건물명
	var lm_year = $("#lm_year").val();
	var lm_month = $("#lm_month").val();

	var lm_total_rental_fee = $('#LsCalculationLeft-02').val();
	var lm_total_management_fee = $('#LsCalculationLeft-03').val();
	var lm_total_etc_fee = $('#LsCalculationLeft-04').val();
	var lm_total_expense = $('#LsCalculationLeft-05').val();

	var lm_total_electro_income = $('#LsCalculationRight-01').val();
	var lm_total_water_income = $('#LsCalculationRight-02').val();
	var lm_total_gas_income = $('#LsCalculationRight-03').val();
	var lm_total_etc_income = $('#LsCalculationRight-04').val();
	var lm_total_income = $('#LsCalculationRight-05').val();

	var data = {};

	data['lm_building_seq'] = building_seq;
	data['lm_year'] = lm_year;
	data['lm_month'] = lm_month;

	data['lm_total_rental_fee'] = lm_total_rental_fee;
	data['lm_total_management_fee'] = lm_total_management_fee;
	data['lm_total_etc_fee'] = lm_total_etc_fee;
	data['lm_total_expense'] = lm_total_expense;

	data['lm_total_electro_income'] = lm_total_electro_income;
	data['lm_total_water_income'] = lm_total_water_income;
	data['lm_total_gas_income'] = lm_total_gas_income;
	data['lm_total_etc_income'] = lm_total_etc_income;
	data['lm_total_income'] = lm_total_income;


	modCalculationInfo(data);

}

function modCalculationInfo(data){

	$.ajax({
		url:'/Calculation/modCalculationInfo',
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

function sum_vals(gubun){
	if(gubun == 1){
		var second = $('#LsCalculationLeft-02').val();
		var third = $('#LsCalculationLeft-03').val();
		var fourth = $('#LsCalculationLeft-04').val();

		var five = parseInt(second) + parseInt(third) + parseInt(fourth);

		$('#LsCalculationLeft-05').val(five);

	}else if(gubun == 2){
		var first = $('#LsCalculationRight-01').val();
		var second = $('#LsCalculationRight-02').val();
		var third = $('#LsCalculationRight-03').val();
		var fourth = $('#LsCalculationRight-04').val();

		var five = parseInt(first) + parseInt(second) + parseInt(third) + parseInt(fourth);

		$('#LsCalculationRight-05').val(five);

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

	//html += '<option value="" selected>선택해주세요</option>';
	for(var i=0; i<data.length; i++){
		var building_name = data[i].building_name;
		var building_seq = data[i].building_seq;
		html += '<option value="'+building_seq+'">'+building_name+'</option>';
	}

	$('#buildingListSelect').append(html);
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
		url:'/Calculation/getCalculationList',
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
	$('#calculationList').empty();
	var html = '';

	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li style="max-width:1200px; width:100%;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';
	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			var building_name = dataItem.building_name;
			var building_seq = dataItem.lm_building_seq;

			var lm_year = dataItem.lm_calculation_year;
			var lm_month_var = dataItem.lm_calculation_month;
			var lm_month = "'"+dataItem.lm_calculation_month+"'";

			var lm_ym = lm_year+'-'+lm_month_var;

			var lm_total_expense = dataItem.lm_total_expense;
			var lm_total_income = dataItem.lm_total_income;

			html += '<ul class="ls-detail-body__tb" id="data_row" >';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
			html += '<li><strong class="mb-title">정산년월</strong><span>'+lm_ym+'</span></li>';
			html += '<li><strong class="mb-title">지출 총액</strong><span>'+lm_total_expense+'</span></li>';
			html += '<li><strong class="mb-title">수입 총액</strong><span>'+lm_total_income+'</span></li>';
			html += '<li><strong class="mb-title">지출내역서</strong><span><button class="click-button calculation-setting" type="button" onclick="openmodal('+building_seq+', '+lm_year+', '+lm_month+')">수정</button></span></li>';
			html += '<li><strong class="mb-title">관리</strong><button class="click-button delete" type="button" onclick="delData('+building_seq+', '+lm_year+', '+lm_month+');">삭제</button></li>';

			html += '</ul>';

		}
	}

	$('#calculationList').html(html);
}
