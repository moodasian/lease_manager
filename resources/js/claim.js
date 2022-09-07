$(document).ready(function(){

	init();

	function init(){
		loadListData();
		loadBuildingList();
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/Claim/getClaimList',
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
});

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
			var arrears_ym = dataItem.lm_charge_year + "." + dataItem.lm_charge_month;


			html += '<ul class="ls-detail-body__tb" id="data_row" >';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
			html += '<li><strong class="mb-title">호실</strong><span>'+dataItem.lm_room_num+'</span></li>';
			html += '<li><strong class="mb-title">임대료</strong><span>'+dataItem.room_monthly_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">관리비</strong><span>'+dataItem.room_maintenance_charge.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">기타비</strong><span>'+dataItem.room_etc_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">전기료</strong><span>'+dataItem.electro_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">수도료</strong><span>'+dataItem.water_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">가스료</strong><span>'+dataItem.gas_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">기타료</strong><span>'+dataItem.etc_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">년월</strong><span>'+arrears_ym+'</span></li>';
			html += '</ul>';

		}
	}

	$('#ClaimList').append(html);
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
	var lm_ym = $('#basicFeeDateSELECT').val();
	var words = lm_ym.split('-');
	var lm_year = words[0];
	var lm_month = words[1];


	$.ajax({
		url:'/Claim/getClaimList',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq, lm_year:lm_year, lm_month:lm_month},
		success:function(data){
			renderSelectTable(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}


function renderSelectTable(data){
	$('#ClaimList').empty();
	var html = '';
	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li style="max-width:1200px; width:100%;"><strong class="mb-title">등록된 정보가 없습니다.</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';
	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			var building_name = dataItem.building_name;
			var arrears_ym = dataItem.lm_charge_year + "." + dataItem.lm_charge_month;


			html += '<ul class="ls-detail-body__tb" id="data_row" >';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
			html += '<li><strong class="mb-title">호실</strong><span>'+dataItem.lm_room_num+'</span></li>';
			html += '<li><strong class="mb-title">임대료</strong><span>'+dataItem.room_monthly_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">관리비</strong><span>'+dataItem.room_maintenance_charge.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">기타비</strong><span>'+dataItem.room_etc_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">전기료</strong><span>'+dataItem.electro_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">수도료</strong><span>'+dataItem.water_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">가스료</strong><span>'+dataItem.gas_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">기타료</strong><span>'+dataItem.etc_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+'</span></li>';
			html += '<li><strong class="mb-title">년월</strong><span>'+arrears_ym+'</span></li>';
			html += '</ul>';

		}
	}

	$('#ClaimList').append(html);
}
