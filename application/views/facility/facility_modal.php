<section class="ls-modal">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<div><h6 id="modalTitle"><strong>신규 호실 등록</strong></h6></div>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;" onsubmit="return false;">
				<!-- 시설번호 -->
				<label for="LsFacilityBuildingName" class="ls-label">건물명</label>
				<select id="LsFacilityBuildingName" class="ls-form">
					<option>선택해주세요</option>
				</select>

				<!-- 시설번호 -->
				<label for="LsFacilityNum" class="ls-label">호실번호</label>
				<input id="LsFacilityNum" class="ls-form" type="text" placeholder="호실번호를 입력하세요"/>

				<!-- 면적 -->
				<div class="ls-form-wrap quarter m-all" style="padding-right: 0; display: block;">
					<label for="LsFacilityArea" class="ls-label">면적</label>
					<input id="LsFacilityArea" class="ls-form ls-form--half--p" type="text" placeholder=""/><p>m2</p>
					<input id="LsFacilityAreaPyung" class="ls-form ls-form--half--p" type="text" placeholder=""/><p>평</p>
				</div>

				<p class="ls-form-title">호실 옵션</p>

				<!-- 방 개수 -->
				<label for="LsFacilityRoomCnt" class="ls-label">방 개수</label>
				<input id="LsFacilityRoomCnt" class="ls-form" type="number" placeholder=""/>

				<!-- 베란다 여부 -->
				<div class="ls-form-wrap all m-half" style="margin-bottom: 16px">
					<label for="LsFacilityTerraceNum" class="ls-label">베란다</label>
					<input id="LsFacilityTerraceNum" name="Terrace" class="ls-form" type="radio" placeholder="" value="Y" checked/>
					<label for="LsFacilityTerraceNum">있음</label>
					<input id="LsFacilityTerraceNum2" name="Terrace" class="ls-form" type="radio" placeholder="" value="N"/>
					<label for="LsFacilityTerraceNum2" style="margin-right:0;">없음</label>
				</div>

				<!-- 거실 개수 -->
				<div class="ls-form-wrap third m-half">
					<label for="LsFacilityLivingRoomCnt" class="ls-label">거실 개수</label>
					<input id="LsFacilityLivingRoomCnt" class="ls-form" type="number" placeholder=""/>
				</div>

				<!-- 화장실 개수 -->
				<div class="ls-form-wrap third m-half">
					<label for="LsFacilityBathroomCnt" class="ls-label">화장실 개수</label>
					<input id="LsFacilityBathroomCnt" class="ls-form" type="number" placeholder=""/>
				</div>

				<!-- 창고 개수 -->
				<div class="ls-form-wrap third m-half">
					<label for="LsFacilityGarageCnt" class="ls-label">창고 개수</label>
					<input id="LsFacilityGarageCnt" class="ls-form" type="number" placeholder=""/>
				</div>

				<!-- 시설 내 옵션 -->
				<div class="ls-form-wrap" style="margin-bottom: 24px">
					<label class="ls-label">호실 내 옵션</label>
					<input id="LsFacilityOption01" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="A"/>
					<label for="LsFacilityOption01">TV</label>
					<input id="LsFacilityOption02" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="B"/>
					<label for="LsFacilityOption02">인터넷</label>
					<input id="LsFacilityOption03" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="C"/>
					<label for="LsFacilityOption03">옷장</label>
					<input id="LsFacilityOption04" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="D"/>
					<label for="LsFacilityOption04">세탁기</label>
					<input id="LsFacilityOption05" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="E"/>
					<label for="LsFacilityOption05">건조기</label>
					<input id="LsFacilityOption06" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="F"/>
					<label for="LsFacilityOption06">침대</label>
					<input id="LsFacilityOption07" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="G"/>
					<label for="LsFacilityOption07">전자레인지</label>
					<input id="LsFacilityOption08" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="H"/>
					<label for="LsFacilityOption08">인덕션</label>
					<input id="LsFacilityOption09" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="I"/>
					<label for="LsFacilityOption09">가스레인지</label>
					<input id="LsFacilityOption10" name="FacilityOption" class="ls-form" type="checkbox" placeholder="" value="J"/>
					<label for="LsFacilityOption10">냉장고</label>
				</div>

				<!-- 특이 사항 -->
				<div class="ls-form-wrap">
					<label for="LsFacilityMemo" class="ls-label">특이사항</label>
					<!--                    <textarea></textarea>-->
					<textarea id="LsFacilityMemo" class="ls-form ls-form-textarea" type="number" placeholder=""></textarea>
				</div>

				<!-- 건물 등록 버튼 -->
				<button id="LsLoginButton" class="ls-primary-button--l" onclick="formCheck();">
					<span id="actionButton" style="color:white;">등록</span>
				</button>

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>


<script>
	// 모달 오픈
	$('.ls-detail-title__add-button .ls-primary-button--l').click( function () {
		$('.ls-modal').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});
</script>

<script>
	$(document).ready(function(){
		$('#LsFacilityArea').change(function(){
			var facility_area = $('#LsFacilityArea').val();
			var facility_area_pyung = parseFloat(facility_area/3.306);

			facility_area_pyung = facility_area_pyung.toFixed(2);
			$('#LsFacilityAreaPyung').val(facility_area_pyung);

		});

		getBuildingList();
	});

	function getBuildingList(){
		$.ajax({
			url:'/Facility/getBuildingList',
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
			var building_name = data[i].building_name;
			var building_seq = data[i].building_seq;

			html += '<option value="'+building_seq+'">'+building_name+'</option>';
		}

		$('#LsFacilityBuildingName').append(html);
	}


	function formCheck(){
		//빈값 체크
		var building_seq = $("#buildingListModal option:selected").val();
		var facility_num = $('#LsFacilityNum').val();
		var facility_area = $('#LsFacilityArea').val();
		var facility_area_pyung = $('#LsFacilityAreaPyung').val();
		var facility_room_cnt = $('#LsFacilityRoomCnt').val();
		var facility_veranda_yn = $('input[name="LsVerandaYN"]:checked').val();
		var facility_livingroom_cnt = $('#LsFacilityLivingRoomCnt').val();
		var facility_bathroom_cnt = $('#LsFacilityBathroomCnt').val();
		var facility_garage_cnt = $('#LsFacilityGarageCnt').val();

		var facility_option_arr = [];
		$('input[name="LsFacilityOption"]:checked').each(function(){
			var option = $(this).val();
			facility_option_arr.push(option);
		});

		var facility_option = facility_option_arr.join(',');
		var LsFacilityMemo = $('#LsFacilityMemo').val();

		if(building_seq == ""){
			alert('건물을 선택해주세요.');
			$('#buildingListModal').focus();
			return;
		}

		if(facility_num == ""){
			alert('시설번호를 입력해주세요.');
			$('#LsFacilityNum').focus();
			return;
		}
		if(facility_area == ""){
			alert('면적을 입력해주세요.');
			$('#LsFacilityArea').focus();
			return;
		}
		if(facility_room_cnt == ""){
			alert('시설 개수를 입력해주세요.');
			$('#LsBuildingNum').focus();
			return;
		}
		if(facility_veranda_yn == ""){
			alert('배란다 여부를 선택해주세요.');
			$('#LsBuildingRoomNum').focus();
			return;
		}
		if(facility_livingroom_cnt == ""){
			alert('거실 개수를 입력해주세요.');
			$('#LsFacilityLivingRoomCnt').focus();
			return;
		}
		if(facility_bathroom_cnt == ""){
			alert('화장실 개수를 선택해주세요.');
			$('#LsFacilityBathroomCnt').focus();
			return;
		}
		if(facility_garage_cnt == ""){
			alert('화장실 개수를 선택해주세요.');
			$('#LsFacilityGarageCnt').focus();
			return;
		}

		var data = {};
		data['room_num'] = facility_num;
		data['room_supply_area'] = facility_area;
		data['room_exclusive_area'] = facility_area_pyung;
		data['room_room_cnt'] = facility_room_cnt;
		data['room_veranda_cnt'] = facility_veranda_yn;
		data['room_living_room_cnt'] = facility_livingroom_cnt;
		data['room_bathroom_cnt'] = facility_bathroom_cnt;
		data['room_storage_cnt'] = facility_garage_cnt;
		data['lm_room_option'] = facility_option;
		data['room_etc'] = LsFacilityMemo;
		data['building_seq'] = building_seq;

		registBuildingInfo(data);
	}

	function registBuildingInfo(data){

		$.ajax({
			url:'/Facility/registFacilityInfo',
			type:"POST",
			dataType:'JSON',
			data:data,
			success:function(data){
				console.log(data);
				alert(data.msg);
				location.reload();
			},
			error:function(e){
				console.log(e);
			}
		});
	}
</script>
