(function(window, $){
	var pageNum = 1;
	var pagination;
	var DATA_URL = '/Facility/getFacilityList';

	init();

	function getParams(){
		var data = {'cate': $('.cate').val(), 'date': $('.selectdate').val()};
		return data;
	}

	function init(){
		loadBuildingList();
		loadListData();
		loadFacilityList();
	}

	function loadFacilityList(){
		var select_option = $('#buildingListSelect').val();
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

		for(var i=0; i<data.length; i++){
			var building_name = data[i].building_name;
			var building_seq = data[i].building_seq;

			html += '<option value="'+building_seq+'">'+building_name+'</option>';
		}

		$('#buildingListSelect').append(html);
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/Facility/getFacilityList',
			type:"POST",
			data:{},
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
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			console.log(dataItem);


			var building_name = dataItem.building_name;
			var room_num = dataItem.room_num;
			var room_supply_area = dataItem.room_supply_area;
			var room_exclusive_area = dataItem.room_exclusive_area;
			var building_seq = dataItem.building_seq;

			var room_contract_YN = dataItem.contract_YN;
			
			if(room_contract_YN == 'N'){
				var room_contract_var = "공실";
			}else{
				var room_contract_var = "입실";
			}

			html += '<ul class="ls-detail-body__tb">';
			html += '<li>'+building_name+'</li>';
			html += '<li>'+room_num+'</li>';
			html += '<li>'+room_supply_area+' (m2)</li>';
			html += '<li>'+room_contract_var+'</li>';
			html += '<li><button type="button" onclick="modifyFacility('+building_seq+', '+room_num+');">수정</button></li>';
			html += '<li><button type="button" onclick="delBuilding('+building_seq+', '+room_num+');">삭제</button></li>';
			html += '</ul>';
		}

		$('#facilityListBody').html(html);
	}




})(window, jQuery);

$(function(){
	$('#buildingListSelect').change(function(){
		/*
		$.ajax({
			url:'/Facility/getFacilityList',
			type:"POST",
			data:{},
			dataType:'JSON',
			success:function(data){

			},
			error:function(e){
				console.log(e);
			}
		});
		 */
	});
});

function modifyFacility(building_seq, room_num){
	$.ajax({
		url:'/Facility/getFacilityInfo',
		type:"POST",
		dataType:'JSON',
		data:{building_seq:building_seq, room_num:room_num},
		success:function(data){

			var veranda_YN = data.room_veranda_cnt;
			$("#buildingListModal").val(data.lm_building_seq).attr("selected", "selected");
			$("#LsFacilityNum").val(data.lm_room_num);
			$("#LsFacilityArea").val(data.room_supply_area);
			$("#LsFacilityAreaPyung").val(data.room_exclusive_area);
			$("#LsFacilityRoomCnt").val(data.room_room_cnt);

			if(veranda_YN == 'Y'){
				$("#LsVerandaY").prop('checked', true);
				$("#LsVerandaN").prop('checked', false);
			}else{
				$("#LsVerandaY").prop('checked', false);
				$("#LsVerandaN").prop('checked', true);
			}
			$("#LsFacilityLivingRoomCnt").val(data.room_living_room_cnt);
			$("#LsFacilityBathroomCnt").val(data.room_bathroom_cnt);
			$("#LsFacilityGarageCnt").val(data.room_storage_cnt);
			
			//체크박스 옵션

			var lm_room_option_arr = data.lm_room_option;
			var lm_room_option = lm_room_option_arr.split(',');

			for(var i in lm_room_option){
				if(lm_room_option[i] == 'A'){
					$("#optionTV").prop('checked', true);
				}
				if(lm_room_option[i] == 'B'){
					$("#optionInternet").prop('checked', true);
				}
				if(lm_room_option[i] == 'C'){
					$("#optionCloset").prop('checked', true);
				}
				if(lm_room_option[i] == 'D'){
					$("#optionRoundry").prop('checked', true);
				}
				if(lm_room_option[i] == 'E'){
					$("#optionDry").prop('checked', true);
				}
				if(lm_room_option[i] == 'F'){
					$("#optionBed").prop('checked', true);
				}
				if(lm_room_option[i] == 'H'){
					$("#optionWave").prop('checked', true);
				}
				if(lm_room_option[i] == 'I'){
					$("#optionInduction").prop('checked', true);
				}
				if(lm_room_option[i] == 'J'){
					$("#optionStove").prop('checked', true);
				}
				if(lm_room_option[i] == 'K'){
					$("#optionRefriger").prop('checked', true);
				}
			}

			$("#LsFacilityMemo").val(data.room_etc);

			$('#actionButton').text('수정');
			$('#modalTitle').text('건물 정보 수정');

			/*
			$('#LsBuildingName').val(data.building_name);
			$('#LsBuildingOwner').val(data.building_owner_name);
			$('#LsBuildingNum').val(data.building_owner_phone);
			$('#LsBuildingRoomNum').val(data.building_room_cnt);
			$('#LsBuildingZip').val(data.building_zipcode);
			$('#LsBuildingAddress').val(data.building_address);
			$('#LsBuildingAddressMore').val(data.building_address_detail);

			$('#actionButton').text('수정');
			$('#modalTitle').text('건물 정보 수정');

			$('#building_seq').val(building_seq);
			$('#action_mod').val('mod');
			*/
		},
		error:function(e){
			console.log(e);
		}
	});

	$('.ls-modal').addClass('open');
	$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
}
