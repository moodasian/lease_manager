$(document).ready(function($){

	init();

	function init(){
		loadListData();
		loadBuildingList();
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/Lease/getLeaseList',
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

	$('#leastListBody').empty();
	var html = '';
	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li><strong class="mb-title">임차인</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';
	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];

			var contact_YN = dataItem.contact_YN;
			var button_var = '등록';
			if(contact_YN == 'Y'){
				contact_YN = '임대중';
				button_var = '수정';
			}else{
				contact_YN = '공실';
				button_var = '등록';
			}
			var building_name = dataItem.building_name;
			var room_num = dataItem.room_num;
			var room_supply_area = dataItem.room_supply_area;
			var building_seq = dataItem.lm_building_seq;
			var tenant_name = dataItem.lm_tenant_name;

			html += '<ul class="ls-detail-body__tb">';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
			html += '<li><strong class="mb-title">호실</strong><span>'+room_num+'</li>';
			html += '<li><strong class="mb-title">면적</strong><span>'+room_supply_area+'</li>';
			html += '<li><strong class="mb-title">임대여부</strong><span>'+contact_YN+'</li>';
			html += '<li><strong class="mb-title">임차인 명</strong><span>'+tenant_name+'</li>';
			html += '<li><strong class="mb-title">관리</strong><button class="click-button lease-manage-setting" type="button" onclick="modifyLeaseInfo('+building_seq+', '+room_num+');">'+button_var+'</button></li>';
			html += '<li><strong class="mb-title">삭제</strong><button class="click-button delete" type="button" onclick="deleteLease('+building_seq+', '+room_num+')" >삭제</button></li>';
			html += '</ul>';
		}
	}

	$("#leastListBody").append(html);
}

function modifyLeaseInfo(building_seq, room_num){

	$('#building_seq').val(building_seq);
	$.ajax({
		url:'/Lease/getLeaseInfoIndi',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq, room_num:room_num},
		success:function(data){

			$('#LsBuildingName').val(data.building_name); //건물명
			$('#LsLeaseNum').val(data.room_num); //호실번호
			$('#LsLeaseName').val(data.lm_tenant_name); //임차인 성명
			$('#LsLeasePhone01').val(data.lm_tenant_phone); //연락처1
			$('#LsLeasePhone02').val(data.lm_tenant_phone2); //연락처2
			$('#LsLeaseSetting01').val(data.room_deposit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //보증금
			$('#LsLeaseSetting02').val(data.room_movein_date); //입주년월일
			$('#LsLeaseSetting03').val(data.room_monthly_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //임차 월액
			$('#LsLeaseSetting04').val(data.room_expire_date); //계약만료일
			$('#LsLeaseSetting05').val(data.room_maintenance_charge.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //관리비
			$('#LsLeaseSetting06').val(data.room_mod_contract_date); //변경계약일
			$('#LsLeaseSetting07').val(data.room_etc_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); //기타비
			$('#LsLeaseSetting08').val(data.room_mod_expire_date); //변경만료일
			$('#action_mod').val('mod');
			$('#building_seq').val(building_seq);
		},
		error:function(e){
			console.log(e);
		}
	});

	//modal open
	$('.ls-modal').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
}

$(document).ready(function(){
	$('#buildingListSelect').on('change', function(){
		var building_seq = this.value;
		loadFacilityList(building_seq);
	});
})

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


function deleteLease(building_seq, room_num){
	var action_check = confirm('정말 삭제하시겠습니까?');

	if(action_check){
		$.ajax({
			url:'/Lease/deleteLeaseInfo',
			type:"POST",
			dataType:'JSON',
			data:{building_seq:building_seq, room_num:room_num},
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
	var building_name = $('#buildingListSelect').val();
	var room_num = $('#FacilityListSelect').val();

	$.ajax({
		url:'/Lease/getLeaseList',
		type:"POST",
		dataType:'JSON',
		data:{building_name:building_name, room_num:room_num},
		success:function(data){
			renderSelectTable(data);
		},
		error:function(e){
			console.log(e);
		}
	});
}

function renderSelectTable(data){
	$('#leastListBody').empty();
	var html = '';
	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li><strong class="mb-title">임차인</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';
	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];

			var contact_YN = dataItem.contact_YN;
			var button_var = '등록';
			if(contact_YN == 'Y'){
				contact_YN = '임대중';
				button_var = '수정';
			}else{
				contact_YN = '공실';
				button_var = '등록';
			}
			var building_name = dataItem.building_name;
			var room_num = dataItem.room_num;
			var room_supply_area = dataItem.room_supply_area;
			var building_seq = dataItem.lm_building_seq;

			html += '<ul class="ls-detail-body__tb">';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
			html += '<li><strong class="mb-title">호실</strong><span>'+room_num+'</li>';
			html += '<li><strong class="mb-title">면적</strong><span>'+room_supply_area+'</li>';
			html += '<li><strong class="mb-title">임대여부</strong><span>'+contact_YN+'</li>';
			html += '<li><strong class="mb-title">관리</strong><button class="click-button lease-manage-setting" type="button" onclick="modifyLeaseInfo('+building_seq+', '+room_num+');">'+button_var+'</button></li>';
			html += '<li><strong class="mb-title">삭제</strong><button class="click-button delete" type="button" onclick="deleteLease('+building_seq+', '+room_num+')" >삭제</button></li>';
			html += '</ul>';
		}
	}

	$("#leastListBody").append(html);
}
