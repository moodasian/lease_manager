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
	<script src="/resources/js/calculation.js"></script>
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
	<section id="CalculationTitle" class="ls-detail-title">
		<!-- 타이틀 -->
		<div class="ls-detail-title__header">
			<h5><strong>정산관리</strong></h5>
			<h6 style="margin-top: 8px">정산</h6>
			<!-- <h6 style="margin-top: 8px">청구 관리</h6> -->
		</div>

		<form id="CalculationTitleSearch" class="ls-detail-title__form"  onsubmit="return false;">
			<div class="form-wrap">
				<label for="buildingListSelect" class="ls-label">건물명</label>
				<select id="buildingListSelect" class="ls-form">
					<option value="">선택해주세요</option>
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
	<section id="CalculationBody" class="ls-detail-body">
		<button type="button" class="ls-detail-body__download">
			<span class="down-ico"></span>
			엑셀로 다운받기
		</button>

		<!-- 테이블 헤드 -->
		<ul class="ls-detail-body__th">
			<li>건물명</li>
			<li>정산 월</li>
			<li>지출 총액</li>
			<li>수입 총액</li>
			<li>지출내역서</li>
			<li>관리</li>
		</ul>

		<!-- 테이블 바디 (복사) -->
		<div id="calculationList"></div>


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
<section class="ls-modal calculation-setting">
	<div class="ls-modal-body">

		<div class="modal-body__title" style="margin-bottom:0px;">
			<h6><strong>정산 등록</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>
		<div style="margin-bottom:20px;">
			<span style="font-size:10pt; color:#97310e;">(이전 정산 내역을 불러 온 후 수정해주세요.)</span>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;" action="#">
				<input type="hidden" id="building_seq" value="">
				<input type="hidden" id="lm_year" value="">
				<input type="hidden" id="lm_month" value="">


				<div class="ls-form-wrap">
					<!-- 청구 년월 -->
					<div class="ls-form-wrap half" style="">
						<label for="LsCalculationDate" class="ls-label">이전 정산 내역</label>
						<select id="LsCalculationDate" class="ls-form">
						</select>
					</div>
					<div class="ls-form-wrap half" style="margin-top: 32px; margin-bottom: 40px">
						<button class="ls-default-button--m" onclick="summon_past_vals()">불러오기</button>
					</div>
				</div>

				<!-- 왼쪽 영역 -->
				<div class="ls-form-wrap half">

					<h6 class="ls-form-title">지출 내역</h6>

					<label for="LsCalculationLeft-01" class="ls-label">정산 년월</label>
					<input id="LsCalculationLeft-01" class="ls-form ls-form--p" type="text" placeholder=""/>
					<p></p>

					<label for="LsCalculationLeft-02" class="ls-label">임대료</label>
					<input id="LsCalculationLeft-02" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>

					<label for="LsCalculationLeft-03" class="ls-label">관리비</label>
					<input id="LsCalculationLeft-03" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>

					<label for="LsCalculationLeft-04" class="ls-label">기타비</label>
					<input id="LsCalculationLeft-04" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>

					<label for="LsCalculationLeft-05" class="ls-label" style="margin-top: 50px">지출 총액</label>
					<button class="ls-default-button--m" onclick="sum_vals(1);" style="margin-bottom:15px;">계산하기</button>
					<input id="LsCalculationLeft-05" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>

				</div>


				<!-- 왼쪽 영역 -->
				<div class="ls-form-wrap half">

					<h6 class="ls-form-title">수입 내역</h6>

					<label for="LsCalculationRight-01" class="ls-label">전기 요금</label>
					<input id="LsCalculationRight-01" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>

					<label for="LsCalculationRight-02" class="ls-label">수도 요금</label>
					<input id="LsCalculationRight-02" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>

					<label for="LsCalculationRight-03" class="ls-label">가스 요금</label>
					<input id="LsCalculationRight-03" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>

					<label for="LsCalculationRight-04" class="ls-label">기타 요금</label>
					<input id="LsCalculationRight-04" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>

					<label for="LsCalculationRight-05" class="ls-label" style="margin-top: 40px">수입 총액</label>
					<button class="ls-default-button--m" onclick="sum_vals(2);" style="margin-bottom:15px;">계산하기</button>
					<input id="LsCalculationRight-05" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;"/>
					<p>원</p>

				</div>

				<!-- 등록/수정하기 -->
				<input id="LsCalculationButton" class="ls-primary-button--l" type="button" value="저장" onclick="formCheck()">

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>
<script>
	$('#CalculationBody .ls-detail-body__tb .click-button.calculation-setting').click( function () {
		$('.ls-modal.calculation-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal.calculation-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.calculation-setting').removeClass('open');
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
