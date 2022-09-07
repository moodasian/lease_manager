<section class="ls-modal">
<div class="ls-modal-body">

	<div class="modal-body__title">
		<div><h6 id="modalTitle"><strong>건물 신규 등록</strong></h6></div>
		<button class="modal-close" type="button" onclick=""></button>
	</div>

	<div class="modla-body__detail">
		<form style="font-size: 0;" action="#" id="buildingOpenModal">
			<input type="hidden" id="building_seq" value="">
			<input type="hidden" id="action_mod" value="">
			<!-- 건물명 -->
			<label for="LsBuildingName" class="ls-label">건물명</label>
			<input id="LsBuildingName" class="ls-form" type="text" placeholder="건물명을 입력하세요"/>

			<!-- 건물주명 -->
			<label for="LsBuildingOwner" class="ls-label">건물주명</label>
			<input id="LsBuildingOwner" class="ls-form" type="text" placeholder="건물주명을 입력하세요"/>

			<!-- 건물주 연락처 -->
			<label for="LsBuildingNum" class="ls-label">건물주 연락처</label>
			<input id="LsBuildingNum" class="ls-form" type="number" placeholder="건물주 연락처를 입력하세요"/>

			<!-- 객실 개수 -->
			<label for="LsBuildingRoomNum" class="ls-label">호실 개수</label>
			<input id="LsBuildingRoomNum" class="ls-form" type="number" placeholder="호실 개수를 입력하세요"/>

			<!-- 청구 타입 -->
			<label for="LsBuildingBillTypeA" class="ls-label">청구 타입</label>
			<div style="margin-bottom:15px;">
				<span style="font-size:15px; color:#b13000; font-weight: bold;"> ※ A타입은 각 시설 계약일의 다음날 청구서가 자동 발송됩니다.</span>
			</div>
			<div style="margin-bottom:15px;">
				<span style="font-size:15px; color:#b13000; font-weight: bold;"> ※ B타입은 전기료 등의 사용량 계산이 완료된 후 수동으로 발송해야합니다.</span>
			</div>
			<div>
				<input id="LsBuildingBillTypeA" name="BuildingBillType" class="ls-form" type="radio" placeholder="" value="A" checked/>
				<label for="LsBuildingBillTypeA">A                (임대료, 관리비, 기타비만 청구 )</label>
			</div>
			<div>
				<input id="LsBuildingBillTypeB" name="BuildingBillType" class="ls-form" type="radio" placeholder="" value="B"/>
				<label for="LsBuildingBillTypeB" style="margin-right:0;">B  ("A" + 전기, 수도, 가스 등 요금을 포함하여 청구)</label>
			</div>

			<!-- 건물주소 -->
			<label for="LsBuildingZip" class="ls-label" style="margin-top: 20px;">건물 주소</label>
			<input id="LsBuildingZip" class="ls-form zip" type="text" placeholder="우편번호"/>
			<button id="LsBuildingZipSearch" type="button" class="ls-default-button--m zip-button" onclick="sample6_execDaumPostcode();">주소 검색하기</button>
			<input id="LsBuildingAddress" class="ls-form address" type="text" placeholder="주소를 입력하세요"/>
			<input id="LsBuildingAddressMore" class="ls-form" type="text" placeholder="상세 주소를 입력하세요"/>


			<!-- 회원가입 버튼 -->
			<input type="button" class="ls-primary-button--l" onclick="formCheck()" value="저장" style="background-color:#ff5500" />
		</form>
	</div>

</div>
<div class="ls-modal-fade"></div>

</section>




<script>
// 모달 오픈
$('.ls-detail-title__add-button .ls-primary-button--l').click( function () {
	$('#buildingOpenModal')[0].reset();
    $('.ls-modal').addClass('open');
    $("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
});
$('.ls-modal .modal-close, .ls-modal-fade').click( function () {
    $('.ls-modal').removeClass('open');
    $("body").css({overflow:'auto'}).unbind('touchmove');
});
</script>


<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
	function sample6_execDaumPostcode() {
		new daum.Postcode({
			oncomplete: function(data) {
				// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

				// 각 주소의 노출 규칙에 따라 주소를 조합한다.
				// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
				var addr = ''; // 주소 변수
				var extraAddr = ''; // 참고항목 변수

				//사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
				if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
					addr = data.roadAddress;
				} else { // 사용자가 지번 주소를 선택했을 경우(J)
					addr = data.jibunAddress;
				}

				// 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
				if(data.userSelectedType === 'R'){
					// 법정동명이 있을 경우 추가한다. (법정리는 제외)
					// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
					if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
						extraAddr += data.bname;
					}
					// 건물명이 있고, 공동주택일 경우 추가한다.
					if(data.buildingName !== '' && data.apartment === 'Y'){
						extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
					}
					// 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
					if(extraAddr !== ''){
						extraAddr = ' (' + extraAddr + ')';
					}
					// 조합된 참고항목을 해당 필드에 넣는다.
					//document.getElementById("LsBuildingAddress").value = extraAddr;
					$('#LsBuildingAddress').val(extraAddr);

				} else {
					$('#LsBuildingAddress').val('');
					//document.getElementById("LsBuildingAddress").value = '';
				}

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				$('#LsBuildingZip').val(data.zonecode);
				$('#LsBuildingAddress').val(addr);
				$('#LsBuildingAddressMore').focus();

				//document.getElementById('LsBuildingZip').value = data.zonecode;
				//document.getElementById("LsBuildingAddress").value = addr;
				// 커서를 상세주소 필드로 이동한다.
				//document.getElementById("LsBuildingAddressMore").focus();
				//console.log(data);
				//return;
			}
		}).open();
	}

	function formCheck(){
		//빈값 체크
		var building_name = $('#LsBuildingName').val();
		var owner_name = $('#LsBuildingOwner').val();
		var owner_phone = $('#LsBuildingNum').val();
		var room_cnt = $('#LsBuildingRoomNum').val();
		var zip_code = $('#LsBuildingZip').val();
		var building_address = $('#LsBuildingAddress').val();
		var building_address_detail = $('#LsBuildingAddressMore').val();
		var action_mode = $('#action_mod').val();
		var building_seq = $('#building_seq').val();
		var building_bill_type = $('input[name=BuildingBillType]:checked').val();


		if(building_name == ""){
			alert('건물명을 입력해주세요.');
			$('#LsBuildingName').focus();
			return;
		}
		if(owner_name == ""){
			alert('건물주명을 입력해주세요.');
			$('#LsBuildingOwner').focus();
			return;
		}
		if(owner_phone == ""){
			alert('건물주 연락처를 입력해주세요.');
			$('#LsBuildingNum').focus();
			return;
		}
		if(room_cnt == ""){
			alert('시설 개수를 입력해주세요.');
			$('#LsBuildingRoomNum').focus();
			return;
		}
		if(building_name == "" || building_address == ""){
			alert('건물 주소를 입력해주세요.');
			$('#LsBuildingAddress').focus();
			return;
		}

		var data = {};
		data['building_name'] = building_name;
		data['building_owner_name'] = owner_name;
		data['building_owner_phone'] = owner_phone;
		data['building_room_cnt'] = room_cnt;
		data['building_zipcode'] = zip_code;
		data['building_address'] = building_address;
		data['building_address_detail'] = building_address_detail;
		data['building_seq'] = building_seq;
		data['gubun_flag'] = action_mode;
		data['building_bill_type'] = building_bill_type;

		if(action_mode == 'mod'){
			modBuildingInfo(data);
		}else{
			registBuildingInfo(data);
		}

	}

	function registBuildingInfo(data){

		$.ajax({
			url:'/Building/registBuildingInfo',
			type:"POST",
			dataType:'JSON',
			data:data,
			success:function(data){
				//console.log(data);
				alert(data.msg);
				location.reload();
			},
			error:function(e){
				console.log(e);
			}
		});
	}

	function modBuildingInfo(data){
		$.ajax({
			url:'/Building/modifyBuildingInfo',
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
</script>
