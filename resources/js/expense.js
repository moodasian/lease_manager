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
			url:'/Expense/getExpenseList',
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
				var arrears_ym = dataItem.lm_expense_year + "." + dataItem.lm_expense_month;
				var lm_year = dataItem.lm_expense_year;
				var lm_month = "'"+dataItem.lm_expense_month+"'";
				var total_expense_fee = dataItem.lm_total_fee;
				var building_seq = dataItem.lm_building_seq;

				html += '<ul class="ls-detail-body__tb" id="data_row" >';
				html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
				html += '<li><strong class="mb-title">지출 정산 년월</strong><span>'+arrears_ym+'</span></li>';
				html += '<li><strong class="mb-title">지출 총액</strong><span>'+total_expense_fee+'</span></li>';
				html += '<li><strong class="mb-title">지출 내역서</strong><button class="click-button expense-setting" type="button" onclick="openmodal('+building_seq+', '+lm_year+', '+lm_month+');">수정</button></li>';
				html += '<li><strong class="mb-title">삭제</strong><button class="click-button delete" type="button" onclick="deleteData('+building_seq+', '+lm_year+', '+lm_month+');">삭제</button></li>';
				html += '</ul>';

			}
		}

		$('#expenseList').html(html);
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

		$('#LsExpenseDate').append(html);
	}

});

function openmodal(building_seq, lm_year, lm_month){

	$.ajax({
		url:'/Expense/getExpenseInfoIndi',
		type:"POST",
		dataType:'JSON',
		data: {building_seq:building_seq, lm_year:lm_year, lm_month:lm_month},
		success:function(data){

			var lm_year = data.lm_expense_year;
			var lm_month = data.lm_expense_month;
			var lm_ym = lm_year+"-"+lm_month

			$('#LsExpenseLeft-01').val(lm_ym);
			$('#LsExpenseLeft-02').val(data.lm_manager_pay);
			$('#LsExpenseLeft-03').val(data.lm_cleaner_pay);
			$('#LsExpenseLeft-04').val(data.lm_etc_pay);
			$('#LsExpenseLeft-05').val(data.lm_maintence_bill);
			$('#LsExpenseLeft-06').val(data.lm_maintence_card);

			$('#LsExpenseRight-01').val(data.lm_fire_management_fee);
			$('#LsExpenseRight-02').val(data.lm_electro_manage_fee);
			$('#LsExpenseRight-03').val(data.lm_ev_fee);
			$('#LsExpenseRight-04').val(data.lm_secure_fee);
			$('#LsExpenseRight-05').val(data.lm_broadcast_fee);
			$('#LsExpenseRight-06').val(data.lm_etc_manage_fee);

			$('#LsExpenseBottom-01').val(data.lm_electro_fee);
			$('#LsExpenseBottom-02').val(data.lm_water_fee);
			$('#LsExpenseBottom-03').val(data.lm_gas_fee);
			$('#LsExpenseBottom-04').val(data.lm_etc_fee);
			$('#LsExpenseBottom-05').val(data.lm_total_fee);

			$('#building_seq').val(building_seq);
			$('#lm_year').val(lm_year);
			$('#lm_month').val(lm_month);

		},
		error:function(e){
			console.log(e);
		}
	});



	$('.ls-modal.expense-setting').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
}

function callPast(){
	var lm_ym = $("#LsExpenseDate option:selected").val();
	var words = lm_ym.split('-');
	var lm_year = words[0];
	var lm_month = words[1];
	var building_seq = $('#building_seq').val();


	$.ajax({
		url: '/Expense/getExpenseInfoIndi',
		type: "POST",
		dataType: 'JSON',
		data: {building_seq: building_seq, lm_year: lm_year, lm_month: lm_month},
		success: function (data) {
			console.log(data);

			var lm_year = data.lm_expense_year;
			var lm_month = data.lm_expense_month;
			var lm_ym = lm_year + "-" + lm_month

			$('#LsExpenseLeft-01').val(lm_ym);
			$('#LsExpenseLeft-02').val(data.lm_manager_pay);
			$('#LsExpenseLeft-03').val(data.lm_cleaner_pay);
			$('#LsExpenseLeft-04').val(data.lm_etc_pay);
			$('#LsExpenseLeft-05').val(data.lm_maintence_bill);
			$('#LsExpenseLeft-06').val(data.lm_maintence_card);

			$('#LsExpenseRight-01').val(data.lm_fire_management_fee);
			$('#LsExpenseRight-02').val(data.lm_electro_manage_fee);
			$('#LsExpenseRight-03').val(data.lm_ev_fee);
			$('#LsExpenseRight-04').val(data.lm_secure_fee);
			$('#LsExpenseRight-05').val(data.lm_broadcast_fee);
			$('#LsExpenseRight-06').val(data.lm_etc_manage_fee);

			$('#LsExpenseBottom-01').val(data.lm_electro_fee);
			$('#LsExpenseBottom-02').val(data.lm_water_fee);
			$('#LsExpenseBottom-03').val(data.lm_gas_fee);
			$('#LsExpenseBottom-04').val(data.lm_etc_fee);
			$('#LsExpenseBottom-05').val(data.lm_total_fee);

			$('#lm_year').val(lm_year);
			$('#lm_month').val(lm_month);

		},
		error: function (e) {
			console.log(e);
		}
	});
}

function formCheck(){

	var lm_ym = $("#LsExpenseLeft-01").val(); //건물명
	var lm_manager_pay = $("#LsExpenseLeft-02").val();  //청구액
	var lm_cleaner_pay = $('#LsExpenseLeft-03').val();
	var lm_etc_pay = $('#LsExpenseLeft-04').val();
	var lm_maintence_bill = $('#LsExpenseLeft-05').val();
	var lm_maintence_card = $('#LsExpenseLeft-06').val();

	var lm_fire_management_fee = $('#LsExpenseRight-01').val();
	var lm_electro_manage_fee = $('#LsExpenseRight-02').val();
	var lm_ev_fee = $('#LsExpenseRight-03').val();
	var lm_secure_fee = $('#LsExpenseRight-04').val();
	var lm_broadcast_fee = $('#LsExpenseRight-05').val();
	var lm_etc_manage_fee = $('#LsExpenseRight-06').val();

	var lm_electro_fee = $('#LsExpenseBottom-01').val();
	var lm_water_fee = $('#LsExpenseBottom-02').val();
	var lm_gas_fee = $('#LsExpenseBottom-03').val();
	var lm_etc_fee = $('#LsExpenseBottom-04').val();
	var lm_total_fee = $('#LsExpenseBottom-05').val();

	var building_seq = $("#building_seq").val();
	var lm_year = $('#lm_year').val();
	var lm_month = $('#lm_month').val();


	lm_manager_pay = lm_manager_pay.split(',').join("");
	lm_cleaner_pay = lm_cleaner_pay.split(',').join("");
	lm_etc_pay = lm_etc_pay.split(',').join("");
	lm_maintence_bill = lm_maintence_bill.split(',').join("");
	lm_maintence_card = lm_maintence_card.split(',').join("");

	lm_fire_management_fee = lm_fire_management_fee.split(',').join("");
	lm_electro_manage_fee = lm_electro_manage_fee.split(',').join("");
	lm_ev_fee = lm_ev_fee.split(',').join("");
	lm_secure_fee = lm_secure_fee.split(',').join("");
	lm_broadcast_fee = lm_broadcast_fee.split(',').join("");
	lm_etc_manage_fee = lm_etc_manage_fee.split(',').join("");

	lm_electro_fee = lm_electro_fee.split(',').join("");
	lm_water_fee = lm_water_fee.split(',').join("");
	lm_gas_fee = lm_gas_fee.split(',').join("");
	lm_etc_fee = lm_etc_fee.split(',').join("");


	var data = {};

	data['building_seq'] = building_seq;
	data['lm_year'] = lm_year;
	data['lm_month'] = lm_month;

	data['lm_manager_pay'] = lm_manager_pay;
	data['lm_cleaner_pay'] = lm_cleaner_pay;
	data['lm_etc_pay'] = lm_etc_pay;
	data['lm_maintence_bill'] = lm_maintence_bill;
	data['lm_maintence_card'] = lm_maintence_card;

	data['lm_fire_management_fee'] = lm_fire_management_fee;
	data['lm_electro_manage_fee'] = lm_electro_manage_fee;
	data['lm_ev_fee'] = lm_ev_fee;
	data['lm_secure_fee'] = lm_secure_fee;
	data['lm_broadcast_fee'] = lm_broadcast_fee;
	data['lm_etc_manage_fee'] = lm_etc_manage_fee;

	data['lm_electro_fee'] = lm_electro_fee;
	data['lm_water_fee'] = lm_water_fee;
	data['lm_gas_fee'] = lm_gas_fee;
	data['lm_etc_fee'] = lm_etc_fee;
	data['lm_total_fee'] = lm_total_fee;

	modExpenseInfo(data);

}

function modExpenseInfo(data){
	$.ajax({
		url:'/Expense/modExpenseInfo',
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

function sum_vals(){

	var lm_manager_pay = $("#LsExpenseLeft-02").val();  //청구액
	var lm_cleaner_pay = $('#LsExpenseLeft-03').val();
	var lm_etc_pay = $('#LsExpenseLeft-04').val();
	var lm_maintence_bill = $('#LsExpenseLeft-05').val();
	var lm_maintence_card = $('#LsExpenseLeft-06').val();

	var lm_fire_management_fee = $('#LsExpenseRight-01').val();
	var lm_electro_manage_fee = $('#LsExpenseRight-02').val();
	var lm_ev_fee = $('#LsExpenseRight-03').val();
	var lm_secure_fee = $('#LsExpenseRight-04').val();
	var lm_broadcast_fee = $('#LsExpenseRight-05').val();
	var lm_etc_manage_fee = $('#LsExpenseRight-06').val();

	var lm_electro_fee = $('#LsExpenseBottom-01').val();
	var lm_water_fee = $('#LsExpenseBottom-02').val();
	var lm_gas_fee = $('#LsExpenseBottom-03').val();
	var lm_etc_fee = $('#LsExpenseBottom-04').val();

	lm_manager_pay = lm_manager_pay.split(',').join("");
	lm_cleaner_pay = lm_cleaner_pay.split(',').join("");
	lm_etc_pay = lm_etc_pay.split(',').join("");
	lm_maintence_bill = lm_maintence_bill.split(',').join("");
	lm_maintence_card = lm_maintence_card.split(',').join("");

	lm_fire_management_fee = lm_fire_management_fee.split(',').join("");
	lm_electro_manage_fee = lm_electro_manage_fee.split(',').join("");
	lm_ev_fee = lm_ev_fee.split(',').join("");
	lm_secure_fee = lm_secure_fee.split(',').join("");
	lm_broadcast_fee = lm_broadcast_fee.split(',').join("");
	lm_etc_manage_fee = lm_etc_manage_fee.split(',').join("");

	lm_electro_fee = lm_electro_fee.split(',').join("");
	lm_water_fee = lm_water_fee.split(',').join("");
	lm_gas_fee = lm_gas_fee.split(',').join("");
	lm_etc_fee = lm_etc_fee.split(',').join("");

	var lm_total_fee =
		parseInt(lm_manager_pay) + parseInt(lm_cleaner_pay)+ parseInt(lm_etc_pay)+ parseInt(lm_maintence_bill)+ parseInt(lm_maintence_card)
		+parseInt(lm_fire_management_fee) +parseInt(lm_electro_manage_fee) +parseInt(lm_ev_fee) +parseInt(lm_secure_fee) + parseInt(lm_broadcast_fee) + parseInt(lm_etc_manage_fee)
		+parseInt(lm_electro_fee) + parseInt(lm_water_fee) + parseInt(lm_gas_fee) + parseInt(lm_etc_fee);

	$('#LsExpenseBottom-05').val(lm_total_fee);
}


function deleteData(building_seq, lm_year, lm_month){

	var data = {};

	data['building_seq'] = building_seq;
	data['lm_year'] = lm_year;
	data['lm_month'] = lm_month;

	$.ajax({
		url:'/Expense/delExpenseInfo',
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
function getPost(){
	var building_seq = $('#buildingListSelect').val();
	var fee_gubun = $('#feeGubunSelect').val();
	var lm_ym = $('#basicFeeDateSELECT').val();
	var words = lm_ym.split('-');
	var lm_year = words[0];
	var lm_month = words[1];


	$.ajax({
		url:'/Expense/getExpenseList',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq, fee_gubun:fee_gubun, lm_year:lm_year, lm_month:lm_month},
		success:function(data){
			renderSelectTable(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderSelectTable(data){
	$('#expenseList').empty();
	var html = '';

	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li style="max-width:1200px; width:100%;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';
	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			var building_name = dataItem.building_name;
			var arrears_ym = dataItem.lm_expense_year + "." + dataItem.lm_expense_month;
			var lm_year = dataItem.lm_expense_year;
			var lm_month = "'"+dataItem.lm_expense_month+"'";
			var total_expense_fee = dataItem.lm_total_fee;
			var building_seq = dataItem.lm_building_seq;

			html += '<ul class="ls-detail-body__tb" id="data_row" >';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
			html += '<li><strong class="mb-title">지출 정산 년월</strong><span>'+arrears_ym+'</span></li>';
			html += '<li><strong class="mb-title">지출 총액</strong><span>'+total_expense_fee+'</span></li>';
			html += '<li><strong class="mb-title">지출 내역서</strong><button class="click-button expense-setting" type="button" onclick="openmodal('+building_seq+', '+lm_year+', '+lm_month+');">수정</button></li>';
			html += '<li><strong class="mb-title">삭제</strong><button class="click-button delete" type="button" onclick="deleteData('+building_seq+', '+lm_year+', '+lm_month+');">삭제</button></li>';
			html += '</ul>';

		}
	}

	$('#expenseList').html(html);
}
