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
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/Building/getBuildingList',
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
			html += '<li><strong class="mb-title">건물명</strong><span>등록된 정보가 없습니다.</span></li>';
			html += '</ul>';
		}else{
			for(var i=0; i<data.length; i++){
				var dataItem = data[i];
				var building_name = dataItem.building_name;
				var owner_name = dataItem.building_owner_name;
				var regdate = dataItem.regdate;
				var building_seq = dataItem.seq;

				html += '<ul class="ls-detail-body__tb">';
				html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
				html += '<li><strong class="mb-title">건물주명</strong><span>'+owner_name+'</span></li>';
				html += '<li><strong class="mb-title">수정</strong><button class="click-button" type="button" onclick="modifyBuilding('+building_seq+');">수정</button></li>';
				html += '<li><strong class="mb-title">삭제</strong><button class="click-button delete" type="button" onclick="delBuilding('+building_seq+');">삭제</button></li>';
				html += '<li><strong class="mb-title">등록일</strong><span>'+regdate+'</li>';
				html += '</ul>';
			}
		}

		$('#buildingBodyTb').html(html);
	}

});

function modifyBuilding(building_seq){
	$.ajax({
		url:'/Building/getBuildingInfo',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq},
		success:function(data){

			$('#LsBuildingName').val(data.building_name);
			$('#LsBuildingOwner').val(data.building_owner_name);
			$('#LsBuildingNum').val(data.building_owner_phone);
			$('#LsBuildingRoomNum').val(data.building_room_cnt);
			$('#LsBuildingZip').val(data.building_zipcode);
			$('#LsBuildingAddress').val(data.building_address);
			$('#LsBuildingAddressMore').val(data.building_address_detail);

			$('#actionButton').text('수정');
			$('#modalTitle').text('건물 정보 수정');

			if(data.building_bill_type == 'A'){
				$('#LsBuildingBillTypeA').attr("checked", true);
				$('#LsBuildingBillTypeB').attr("checked", false);
			}else{
				$('#LsBuildingBillTypeA').attr("checked", false);
				$('#LsBuildingBillTypeB').attr("checked", true);
			}

			$('#building_seq').val(building_seq);
			$('#action_mod').val('mod');
		},
		error:function(e){
			console.log(e);
		}
	});

	//modal open
	$('.ls-modal').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
}

function delBuilding(building_seq){
	var action_check = confirm('정말 삭제하시겠습니까?');
	var gubun_flag = 'del';

	if(action_check){
		$.ajax({
			url:'/Building/modifyBuildingInfo',
			type:"POST",
			dataType:'JSON',
			data:{gubun_flag:gubun_flag, building_seq:building_seq},
			success:function(data){
				alert(data.msg);
				location.reload();
			},
			error:function(e){
				console.log(e);
			}
		});
	}else{
		return;
	}
}

function getPost(){
	var building_name = $('#BuildingTitleInput').val();
	var building_owner_name = $('#BuildingOwnerInput').val();
	console.log("Test");

	$.ajax({
		url:'/Building/getBuildingList',
		type:"POST",
		dataType:'JSON',
		data:{building_name:building_name, building_owner_name:building_owner_name},
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
	if(data.length == 0){
		html += '<ul class="ls-detail-body__tb">';
		html += '<li><strong class="mb-title">건물명</strong><span>등록된 정보가 없습니다.</span></li>';
		html += '</ul>';
	}else{
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			var building_name = dataItem.building_name;
			var building_owner_name = dataItem.building_owner_name;
			var regdate = dataItem.regdate;
			var building_seq = dataItem.seq;

			html += '<ul class="ls-detail-body__tb">';
			html += '<li><strong class="mb-title">건물명</strong><span>'+building_name+'</span></li>';
			html += '<li><strong class="mb-title">건물주명</strong><span>'+building_owner_name+'</span></li>';
			html += '<li><strong class="mb-title">수정</strong><button class="click-button" type="button" onclick="modifyBuilding('+building_seq+');">수정</button></li>';
			html += '<li><strong class="mb-title">삭제</strong><button class="click-button delete" type="button" onclick="delBuilding('+building_seq+');">삭제</button></li>';
			html += '<li><strong class="mb-title">등록일</strong><span>'+regdate+'</li>';
			html += '</ul>';
		}
	}

	$('#buildingBodyTb').html(html);
}
