<!DOCTYPE html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<title>임대방 - 임대관리 전문 솔루션</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,viewport-fit=cover">

	<link href="/resources/css/common.css" rel="stylesheet" type="text/css">
	<link href="/resources/css/lease.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- jquery 추가 -->
	<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="/resources/js/arrears.js"></script>
	<script type="text/javascript" src="/resources/js/lease.js"></script>

	<!--제이쿼리 ui css-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!--제이쿼리 js-->
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<!--제이쿼리 ui js-->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<style>
		.ui-datepicker{ font-size: 20px; width: 350px; height:200px;}
		.ui-datepicker select.ui-datepicker-month{ width:30%; font-size: 20px; }
		.ui-datepicker select.ui-datepicker-year{ width:40%; font-size: 20px; }
		.ui-datepicker td span, .ui-datepicker td a {
			padding: 0.6em;
		}
	</style>
</head>
<body>
<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/application/views/common/header.php')
?>
<main>
	<!-- 섹션 타이틀 -->
	<section id="UsageManageTitle" class="ls-detail-title">
		<!-- 타이틀 -->
		<div class="ls-detail-title__header">
			<h5><strong>입금관리</strong></h5>
			<h6 style="margin-top: 8px">체납</h6>
			<!-- <h6 style="margin-top: 8px">청구 관리</h6> -->
		</div>

		<form id="UsageManageTitleSearch" class="ls-detail-title__form" onsubmit="return false;">
			<div class="form-wrap">
				<label for="buildingListSelect" class="ls-label">건물명</label>
				<select id="buildingListSelect" class="ls-form">
					<option value="">선택해주세요</option>
				</select>
			</div>
			<div class="form-wrap">
				<label for="FacilityListSelect" class="ls-label">호실</label>
				<select id="FacilityListSelect" class="ls-form">
					<option>선택해주세요</option>
				</select>
			</div>
			<div class="form-wrap">
				<label for="basicFeeDateSELECT" class="ls-label">년월</label>
				<input id="basicFeeDateSELECT" type="text" class="ls-form" placeholder="" autocomplete="off"/>
			</div>
			<div class="button-wrap">
				<button class="ls-default-button--m" onclick="getPost();">
					<span class="search_ico"></span>
					검색
				</button>
			</div>
		</form>
	</section>


	<!-- 섹션 본문 -->
	<section id="ArrearsBody" class="ls-detail-body">
		<button type="button" class="ls-detail-body__download">
			<span class="down-ico"></span>
			엑셀로 다운받기
		</button>

		<!-- 테이블 헤드 -->
		<ul class="ls-detail-body__th">
			<li>건물명</li>
			<li>호실</li>
			<li>귀속 년월</li>
			<li>현월 청구 합계액</li>
			<li>현월 입금 합계액</li>
			<li>현월 체납액</li>
			<li>전월까지의 체납액</li>
			<li>체납 합계액</li>
			<li>관리</li>
		</ul>

		<!-- 테이블 바디 (복사) -->
		<div id="arrearsList"></div>

		<!-- 넘버 -->
		<div>
			<ul class="ls-detail-body__pagenation">
				<li><a href="#" title="pagenation">맨앞</a></li>
				<li><a class="active" href="#" title="pagenation">1</a></li>
				<li><a href="#" title="pagenation">2</a></li>
				<li><a href="#" title="pagenation">3</a></li>
				<li><a href="#" title="pagenation">...</a></li>
				<li><a href="#" title="pagenation">9</a></li>
				<li><a href="#" title="pagenation">맨끝</a></li>
			</ul>
		</div>


	</section>

</main>


<!-- 모달 -->
<section class="ls-modal arrears-setting" style="max-height:90%;">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<h6><strong>체납 정보 수정</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;">
				<input type="hidden" id="building_seq" value="">
				<input type="hidden" id="lm_year" value="">
				<input type="hidden" id="lm_month" value="">
				<!-- 청구 년월 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsArrearsRoomNum" class="ls-label">호실 번호</label>
					<input id="LsArrearsRoomNum" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 호실 번호 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsArrearsDate" class="ls-label">귀속 년월</label>
					<input id="LsArrearsDate" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 기타비 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsArrearsThisArrears" class="ls-label">현월 체납액</label>
					<input id="LsArrearsThisArrears" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>
				</div>

				<!-- 전기요금 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsArrearsLastArrears" class="ls-label">전월까지의 체납액</label>
					<input id="LsArrearsLastArrears" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" readonly/>
					<p>원</p>
				</div>

				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsArrearsTotalArrears" class="ls-label">체납 합계액</label>
					<input id="LsArrearsTotalArrears" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" />
					<p>원</p>
				</div>

				<!-- 등록/수정하기 -->
				<input id="LsLoginButton" class="ls-primary-button--l" type="button" onclick="formCheck()" value="적용하기">

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>

<script>
	$('#ArrearsBody .ls-detail-body__tb .click-button.arrears-setting').click( function () {
		$('.ls-modal.arrears-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal.arrears-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.arrears-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});
</script>

<script>
	$('#BillBody .ls-detail-body__tb .click-button.bill-setting').click( function () {
		$('.ls-modal.bill-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal.bill-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.bill-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});
</script>

<script>
	$.datepicker.setDefaults({
		dateFormat:'yy-mm'
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

	$('#basicFeeDateSELECT').datepicker();

</script>
</body>
</html>
