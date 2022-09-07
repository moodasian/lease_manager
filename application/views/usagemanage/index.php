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
	<script src="/resources/js/usagemanage.js"></script>
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
			<h5><strong>청구관리</strong></h5>
			<h6 style="margin-top: 8px">사용량 계산</h6>
			<!-- <h6 style="margin-top: 8px">청구 관리</h6> -->
		</div>



		<form id="UsageManageTitleSearch" class="ls-detail-title__form" onsubmit="return false;">
			<div class="form-wrap" style="width: 15%;">
				<label for="" class="ls-label">건물명</label>
				<select id="buildingListSelect" class="ls-form">
					<option>선택하기</option>
				</select>
			</div>
			<div class="form-wrap" style="width: 15%;">
				<label for="" class="ls-label">호실</label>
				<select id="FacilityListSelect" class="ls-form">
					<option value="">선택하기</option>
				</select>
			</div>
			<div class="form-wrap" style="width: 15%;">
				<label for="" class="ls-label">지침 구분</label>
				<select id="feeGubunSelect" class="ls-form">
					<option value="">선택하기</option>
					<option value="A">전기</option>
					<option value="B">수도</option>
					<option value="C">가스</option>
					<option value="D">기타</option>
				</select>
			</div>
			<div class="form-wrap" style="width: 15%;">
				<label for="basicFeeDateSELECT" class="ls-label">년월</label>
				<input id="basicFeeDateSELECT" type="text" class="ls-form" placeholder="" autocomplete="off" />
			</div>
			<div class="button-wrap" style="width: 30%; margin-left:4%;">
				<button class="ls-default-button--m" onclick="getPost();">
					<span class="search_ico"></span>
					검색
				</button>
			</div>
		</form>



	</section>


	<!-- 섹션 본문 -->
	<section id="UsageManageBody" class="ls-detail-body">

		<button type="button" class="ls-detail-body__download" style="float:left;">
			<span class="down-ico"></span>
			엑셀로 다운받기
		</button>

		<div class="ls-detail-title__add-button">
			<button type="button" class="ls-primary-button--l" style="max-width: 560px; width: 33.33%; float: right" onclick="opentotalModal()">
				<span class="ico"></span>
				사용량 일괄 등록
			</button>
		</div>

		<!-- 테이블 헤드 -->
		<ul class="ls-detail-body__th">
			<li>건물명</li>
			<li>호실</li>
			<li>해당년</li>
			<li>해당월</li>
			<li>구분</li>
			<li>지침</li>
			<li>사용량</li>
			<li>사용금액</li>
			<li>관리</li>
		</ul>

		<!-- 테이블 바디 (복사) -->
		<div id="usageManageList"></div>

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

<!-- 모달 사용량 등록(수정) -->
<section class="ls-modal usage-setting">
	<div class="ls-modal-body" style="max-width:1200px;">

		<div class="modal-body__title">
			<h6><strong>건물 사용량 지침 관리</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;" action="#">
				<!-- <h5>***수정중!!</h5> -->
				<div class="ls-form-wrap quarter">
					<label class="ls-label">건물</label>
					<select id="buildingListSelect_modal" class="ls-form">
						<option value="">선택하기</option>
					</select>
				</div>
				
				<div class="ls-form-wrap quarter">
					<label class="ls-label">구분</label>
					<select id="feeGubunSelect_modal" class="ls-form">
						<option value="">선택하기</option>
						<option value="A" selected>전기</option>
						<option value="B">수도</option>
						<option value="C">가스</option>
					</select>
				</div>


				<!-- 전월 지침 -->
				<div class="ls-form-wrap half m-all" style="margin-top:24px;">
					<h6 class="ls-form-title">전월 지침</h6>

					<label class="ls-label">불러오기</label>
					<select class="ls-form" id="prevClaim">
					</select>


					<div class="ls-form-wrap" style="max-height:250px; overflow:scroll;">
						<div class="ls-form-wrap third">
							<label class="ls-label">호실</label>
						</div>
						<div class="ls-form-wrap quarter" style="padding-right:0;">
							<label class="ls-label" id="fee_gubun_var">전기지침</label>
						</div>

						<div id="prevClaimList">

						</div>
					</div>
				</div>


				<!-- 현월 지침 -->
				<div class="ls-form-wrap half m-all" style="margin-top:24px;">
					<h6 class="ls-form-title">현월 지침</h6>

					<label class="ls-label">입력하기</label>
					<select class="ls-form" id="thisClaim">
					</select>

					<div class="ls-form-wrap" style="max-height:250px; overflow:scroll;">
						<div class="ls-form-wrap third">
							<label class="ls-label">호실</label>
						</div>
						<div class="ls-form-wrap quarter" style="padding-right:0;">
							<label class="ls-label" id="fee_gubun_var_this">전기지침</label>
						</div>
						<div id="thisClaimList"></div>
					</div>
				</div>


				<!-- 등록/수정하기 -->
				<input type="button" id="calculate_start" class="ls-primary-button--l"  onclick="renderUseAmountCal()" value="사용량 계산"/>
				<!-- 객실별 사용량 -->
				<div class="ls-form-wrap" style="margin-top:24px;">
					<h6 class="ls-form-title" style="margin-bottom:10px;">호실별 사용량</h6>
					<h2 class="ls-form-title" style="border-bottom:0px; font-size:15px; margin-top:0px; color:darkred;">
						※ 호실별 사용량은 `(현월 지침 - 전월지침) * 단가`로 계산됩니다.
					</h2>
					<input type="hidden" id="rows_cnt" value="" />

						<div class="ls-form-wrap third">
							<label class="ls-label">호실</label>
						</div>
						<div class="ls-form-wrap third">
							<label class="ls-label">사용량</label>
						</div>
						<div class="ls-form-wrap third">
							<label class="ls-label">사용금액</label>
						</div>

						<div id="useageList" style="max-height:250px; overflow:scroll;">
						</div>

				</div>



				<!-- 등록/수정하기 -->
				<input type="button" id="usageManageSubmitButton" class="ls-primary-button--l" onclick="formCheck();" value="저장"/>

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>

<section class="ls-modal usage-modify">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<h6><strong>사용량 개별 관리</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;" id="electFormModal" action="#">
				<input type="hidden" id="usagemanage_seq" value="">
				<!-- 건물명 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="UsageManageBuildingName" class="ls-label">건물명</label>
					<input id="UsageManageBuildingName" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="UsageManageFeeGubun" class="ls-label">구분</label>
					<input id="UsageManageFeeGubun" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 한전 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="UsageManageRoomNum" class="ls-label">호실</label>
					<input id="UsageManageRoomNum" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 공제액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="UsageManageClaimYear" class="ls-label">해당년</label>
					<input id="UsageManageClaimYear" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 공제사유 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="UsageManageClaimMonth" class="ls-label">해당월</label>
					<input id="UsageManageClaimMonth" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 공제 후 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="UsageManageMeter" class="ls-label">지침</label>
					<input id="UsageManageMeter" class="ls-form ls-form--p" type="number" placeholder="" style="text-align:right;" />
				</div>

				<!-- 전기 사용량 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="UsageManageAmount" class="ls-label">사용량</label>
					<input id="UsageManageAmount" class="ls-form ls-form--p" type="number" placeholder="" style="text-align:right;" />
				</div>

				<!-- 전기 사용시간-->
				<div class="ls-form-wrap">
					<label for="UsageManageCost" class="ls-label">사용금액</label>
					<input id="UsageManageCost" class="ls-form ls-form--p" type="number" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 등록/수정하기 -->
				<button id="LsUsageManageIndi" class="ls-primary-button--l" onclick="modifyUsageManageInfo();">
					수정하기
				</button>

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>

<script>
	// 전기 기본료 관리 모달
	$('#UsageManageBody .ls-detail-body__tb .click-button.usage-setting').click( function () {
		$('.ls-modal.usage-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal.usage-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.usage-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});

	$('.ls-modal.usage-modify .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.usage-modify').removeClass('open');
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
		,minDate: "-1M" //최소 선택일자(-1D:하루전, -1M:한달전, -1Y:일년전)
		,maxDate: "+3Y" //최대 선택일자(+1D:하루후, -1M:한달후, -1Y:일년후)
	})

	$('#basicFeeDateSELECT').datepicker();

</script>

</body>
</html>
