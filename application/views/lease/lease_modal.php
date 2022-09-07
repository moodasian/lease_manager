<section class="ls-modal lease-manage-setting">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<div><h6 id="modalTitle"><strong>임대 세부내역</strong></h6></div>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;" action="#">
				<input type="hidden" id="building_seq" value="">
				<input type="hidden" id="action_mod" value="">

				<!-- 건물명 -->
				<div class="ls-form-wrap quarter m-all">
					<label for="LsBuildingName" class="ls-label">건물명</label>
					<input id="LsBuildingName" class="ls-form ls-form--p" type="text" placeholder="건물명을 입력해주세요."/>
				</div>

				<!-- 호실 번호 -->
				<div class="ls-form-wrap quarter m-all">
					<label for="LsLeaseNum" class="ls-label">호실 번호</label>
					<input id="LsLeaseNum" class="ls-form ls-form--p" type="text" placeholder="호실 번호를 입력해주세요."/><p>호</p>
				</div>

				<!-- 임차인 성명 -->
				<div class="ls-form-wrap quarter m-all">
					<label for="LsLeaseName" class="ls-label">임차인 성명</label>
					<input id="LsLeaseName" class="ls-form" type="text" placeholder="임차인 성명을 입력해주세요."/>
				</div>

				<!-- 연락처 1 -->
				<div class="ls-form-wrap quarter m-all">
					<label for="LsLeasePhone01" class="ls-label">연락처1</label>
					<input id="LsLeasePhone01" class="ls-form" type="number" placeholder="-를 제외한 연락처를 입력해주세요."/>
				</div>

				<!-- 연락처 2 -->
				<div class="ls-form-wrap quarter m-all">
					<label for="LsLeasePhone02" class="ls-label">연락처2</label>
					<input id="LsLeasePhone02" class="ls-form" type="number" placeholder="-를 제외한 연락처를 입력해주세요."/>
				</div>
				<!--
				<div class="ls-form-wrap">
					<div class="ls-form-wrap third" style="padding-right:0;">
						<label for="LsLeasePhone02-1" class="ls-label">연락처 2</label>
						<input id="LsLeasePhone02-1" class="ls-form ls-form--p phone" type="text" placeholder="" /><p>-</p>
					</div>
					<div class="ls-form-wrap third" style="padding-right:0;">
						<label for="LsLeasePhone02-2" class="ls-label">&nbsp;</label>
						<input id="LsLeasePhone02-2" class="ls-form ls-form--p phone" type="text" placeholder=""/><p>-</p>
					</div>
					<div class="ls-form-wrap third">
						<label for="LsLeasePhone02-3" class="ls-label">&nbsp;</label>
						<input id="LsLeasePhone02-3" class="ls-form phone" type="text" placeholder=""/>
					</div>
				</div>
				-->




				<!-- 보증금 -->
				<div class="ls-form-wrap half">
					<label for="LsLeaseSetting01" class="ls-label">보증금</label>
					<input id="LsLeaseSetting01" class="ls-form" type="text" placeholder="" style="text-align: right;" />
				</div>

				<!-- 입주년 월일 -->
				<div class="ls-form-wrap half">
					<label for="LsLeaseSetting02" class="ls-label">입주년 월일</label>
					<input id="LsLeaseSetting02" class="ls-form" type="text" autocomplete="off" placeholder="yyyy-mm-dd 형식으로 입력해주세요." style="text-align: right;"/>
				</div>

				<!-- 임대 월액 -->
				<div class="ls-form-wrap half">
					<label for="LsLeaseSetting03" class="ls-label">임대 월액</label>
					<input id="LsLeaseSetting03" class="ls-form" type="text" placeholder="" style="text-align: right;"/>
				</div>

				<!-- 계약만료일 -->
				<div class="ls-form-wrap half">
					<label for="LsLeaseSetting04" class="ls-label">계약만료일</label>
					<input id="LsLeaseSetting04" class="ls-form" type="text" autocomplete="off" placeholder="yyyy-mm-dd 형식으로 입력해주세요." style="text-align: right;"/>
				</div>

				<!-- 관리비 -->
				<div class="ls-form-wrap half">
					<label for="LsLeaseSetting05" class="ls-label">관리비</label>
					<input id="LsLeaseSetting05" class="ls-form" type="text" placeholder="" style="text-align: right;"/>
				</div>

				<!-- 변경계약일 -->
				<div class="ls-form-wrap half">
					<label for="LsLeaseSetting06" class="ls-label">변경계약일</label>
					<input id="LsLeaseSetting06" class="ls-form" type="text" autocomplete="off" placeholder="yyyy-mm-dd 형식으로 입력해주세요." style="text-align: right;"/>
				</div>

				<!-- 기타비 -->
				<div class="ls-form-wrap half">
					<label for="LsLeaseSetting07" class="ls-label">기타비</label>
					<input id="LsLeaseSetting07" class="ls-form" type="text" placeholder="" style="text-align: right;"/>
				</div>

				<!-- 변경만료일 -->
				<div class="ls-form-wrap half">
					<label for="LsLeaseSetting08" class="ls-label">변경만료일</label>
					<input id="LsLeaseSetting08" class="ls-form" type="text" autocomplete="off" placeholder="yyyy-mm-dd 형식으로 입력해주세요." style="text-align: right;"/>
				</div>


				<!-- 등록/수정 버튼 -->
				<input id="LsLeaseButton" class="ls-primary-button--l" onclick="formCheck();" value="저장" style="width: 90%; text-align: center; " />

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>

<script>
	$('.ls-modal .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});

	$.datepicker.setDefaults({
		dateFormat:'yy-mm-dd'
		,showOtherMonths: true //빈 공간에 현재월의 앞뒤월의 날짜를 표시
		,showMonthAfterYear:true //년도 먼저 나오고, 뒤에 월 표시
		,changeYear: true //콤보박스에서 년 선택 가능
		,changeMonth: true //콤보박스에서 월 선택 가능
		,yearSuffix: "년" //달력의 년도 부분 뒤에 붙는 텍스트
		,monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'] //달력의 월 부분 텍스트
		,monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'] //달력의 월 부분 Tooltip 텍스트
		,dayNamesMin: ['일','월','화','수','목','금','토'] //달력의 요일 부분 텍스트
		,dayNames: ['일요일','월요일','화요일','수요일','목요일','금요일','토요일'] //달력의 요일 부분 Tooltip 텍스트
		,minDate: "D" //최소 선택일자(-1D:하루전, -1M:한달전, -1Y:일년전)
		,maxDate: "+3Y" //최대 선택일자(+1D:하루후, -1M:한달후, -1Y:일년후)
	})

	$('#LsLeaseSetting02').datepicker();
	$('#LsLeaseSetting04').datepicker();
	$('#LsLeaseSetting06').datepicker();
	$('#LsLeaseSetting08').datepicker();

</script>

<script>
	function formCheck(){
		//빈값 체크

		var building_seq = $('#building_seq').val();
		var building_name = $('#LsBuildingName').val();
		var room_num = $('#LsLeaseNum').val();
		var tenant_name = $('#LsLeaseName').val();
		var tenant_phone = $('#LsLeasePhone01').val();
		var tenant_phone2 = $('#LsLeasePhone02').val();
		var room_deposit = $('#LsLeaseSetting01').val();
		var room_movein_date = $('#LsLeaseSetting02').val();
		var room_monthly_fee = $('#LsLeaseSetting03').val();
		var room_expire_date = $('#LsLeaseSetting04').val();
		var room_maintenance_charge = $('#LsLeaseSetting05').val();
		
		var room_mod_contract_date = $('#LsLeaseSetting06').val();
		var room_etc_fee = $('#LsLeaseSetting07').val();
		var room_mod_expire_date = $('#LsLeaseSetting08').val();

		if(building_name == ""){
			alert('건물명을 입력해주세요.');
			$('#LsBuildingName').focus();
			return;
		}
		if(room_num == ""){
			alert('호실 번호를 입력해주세요.');
			$('#LsLeaseNum').focus();
			return;
		}
		if(tenant_name == ""){
			alert('임차인 성명을 입력해주세요.');
			$('#LsLeaseName').focus();
			return;
		}
		if(tenant_phone == ""){
			alert('임차인 연락처를 입력해주세요.');
			$('#LsLeasePhone01').focus();
			return;
		}
		if(room_deposit == ""){
			alert('보증금을 입력해주세요.');
			$('#LsLeaseSetting01').focus();
			return;
		}
		if(room_movein_date == ""){
			alert('입주년월일을 입력해주세요.');
			$('#LsLeaseSetting02').focus();
			return;
		}
		if(room_monthly_fee == ""){
			alert('임차월액을 입력해주세요.');
			$('#LsLeaseSetting03').focus();
			return;
		}
		if(room_expire_date == ""){
			alert('계약만료일을 입력해주세요.');
			$('#LsLeaseSetting04').focus();
			return;
		}
		if(room_maintenance_charge == ""){
			alert('관리비를 입력해주세요.');
			$('#LsLeaseSetting05').focus();
			return;
		}

		var data = {};
		var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi

		data['building_seq'] = building_seq;
		data['building_name'] = building_name;
		data['room_num'] = room_num;
		data['tenant_name'] = tenant_name;
		data['tenant_phone'] = tenant_phone;
		data['tenant_phone2'] = tenant_phone2;
		data['room_deposit'] = room_deposit.replace(regExp, "");
		data['room_movein_date'] = room_movein_date;
		data['room_monthly_fee'] = room_monthly_fee.replace(regExp, "");
		data['room_expire_date'] = room_expire_date;
		data['room_maintenance_charge'] = room_maintenance_charge.replace(regExp, "");
		data['room_mod_contract_date'] = room_mod_contract_date;
		data['room_etc_fee'] = room_etc_fee.replace(regExp, "");
		data['room_mod_expire_date'] = room_mod_expire_date;

		modLeaseInfo(data);

	}

	function modLeaseInfo(data){
		$.ajax({
			url:'/Lease/modLeaseInfo',
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
