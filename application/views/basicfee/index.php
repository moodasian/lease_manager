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
	<script src="/resources/js/basicfee.js"></script>
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
	<section id="BasicFeeTitle" class="ls-detail-title">
		<!-- 타이틀 -->
		<div class="ls-detail-title__header">
			<h5><strong>청구관리</strong></h5>
			<h6 style="margin-top: 8px">단가 계산</h6>
			<!-- <h6 style="margin-top: 8px">청구 관리</h6> -->
		</div>

		<form id="BasicFeeTitleSearch" class="ls-detail-title__form" onsubmit="return false;">
			<div class="form-wrap">
				<label for="" class="ls-label">건물명</label>
				<select id="buildingListSelect" class="ls-form">
					<option value="">선택하기</option>
				</select>
			</div>
			<div class="form-wrap">
				<label for="" class="ls-label">구분</label>
				<select id="feeGubunSelect" class="ls-form">
					<option value="">선택하기</option>
					<option value="A">전기</option>
					<option value="B">수도</option>
					<option value="C">가스</option>
					<option value="D">기타</option>
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
	<section id="BasicFeeBody" class="ls-detail-body">

		<button type="button" class="ls-detail-body__download">
			<span class="down-ico"></span>
			엑셀로 다운받기
		</button>

		<!-- 테이블 헤드 -->
		<ul class="ls-detail-body__th">
			<li>귀속년월</li>
			<li>사용기간</li>
			<li>구분</li>
			<li>청구액(원)</li>
			<li>사용량</li>
			<li>단가</li>
			<li>관리</li>
		</ul>

		<!-- 테이블 바디 -->
		<div id="basicFeeList"></div>

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

<!-- 모달 1 - 전기 기본료 관리 -->
<section class="ls-modal elect-setting">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<h6><strong>전기 단가 계산</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;" id="electFormModal" action="#">
				<input type="hidden" id="basicfee_seq_a" value="">
				<!-- 건물명 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeElectName" class="ls-label">건물명</label>
					<input id="LsBasicFeeElectName" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 한전 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeKEPCO" class="ls-label">한전 청구액</label>
					<input id="LsBasicFeeKEPCO" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 공제액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeDeduction" class="ls-label">공제액</label>
					<input id="LsBasicFeeDeduction" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 공제사유 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeDeductionReason" class="ls-label">공제 사유</label>
					<input id="LsBasicFeeDeductionReason" class="ls-form" type="text" placeholder=""/>
				</div>

				<!-- 공제 후 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeDeductionAfterClaim" class="ls-label">공제 후 청구액</label>
					<input id="LsBasicFeeDeductionAfterClaim" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 전기 사용량 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeElectFee" class="ls-label">전기 사용량</label>
					<input id="LsBasicFeeElectFee" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>Kwh</p>
				</div>

				<!-- Kwh당 기본료 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeElectFeeDefault" class="ls-label">Kwh당 단가</label>
					<input id="LsBasicFeeElectFeeDefault" class="ls-form ls-form--p" type="text" placeholder="" readonly  style="text-align:right;" /><p>원</p>
				</div>

				<!-- 전기 사용시간-->
				<div class="ls-form-wrap">
					<label for="LsBasicFeeElectFeeDateRange1" class="ls-label">전기 사용 기간</label>
					<input id="LsBasicFeeElectFeeDateRange1" class="ls-form ls-form--half--p" type="text" placeholder=""/><p>~</p>
					<input id="LsBasicFeeElectFeeDateRange2" class="ls-form ls-form--half--p" type="text" placeholder=""/><p></p>
				</div>

				<!-- 등록/수정하기 -->
				<button id="LsBasicFeeElectButton" class="ls-primary-button--l" onclick="formCheck('A');">
					저장
				</button>

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>




<!-- 모달 2 - 수도 기본료 관리 -->
<section class="ls-modal water-setting">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<h6><strong>수도 단가 계산</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;" id="waterFormModal" action="#">
				<input type="hidden" id="basicfee_seq_b" value="">
				<!-- 건물명 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeWaterName" class="ls-label">건물명</label>
					<input id="LsBasicFeeWaterName" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 수도 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeWaterClaim" class="ls-label">수도 청구액</label>
					<input id="LsBasicFeeWaterClaim" class="ls-form" type="text" placeholder="" style="text-align:right;" />
				</div>

				<!-- 공제액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeWaterDeduction" class="ls-label">공제액</label>
					<input id="LsBasicFeeWaterDeduction" class="ls-form" type="text" placeholder="" style="text-align:right;" />
				</div>

				<!-- 공제사유 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeWaterDeductionReason" class="ls-label">공제 사유</label>
					<input id="LsBasicFeeWaterDeductionReason" class="ls-form" type="text" placeholder=""/>
				</div>

				<!-- 공제 후 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeWaterDeductionAfterClaim" class="ls-label">공제 후 청구액</label>
					<input id="LsBasicFeeWaterDeductionAfterClaim" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 수도 사용량 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeWaterFee" class="ls-label">수도 사용량</label>
					<input id="LsBasicFeeWaterFee" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>톤</p>
				</div>

				<!-- Kwh당 기본료 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeWaterFeeDefault" class="ls-label">톤(ton)당 단가</label>
					<input id="LsBasicFeeWaterFeeDefault" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 수도 사용시간-->
				<div class="ls-form-wrap">
					<label for="LsBasicFeeWaterFeeDateRange1" class="ls-label">수도 사용 기간</label>
					<input id="LsBasicFeeWaterFeeDateRange1" title="수도 사용시간 1" class="ls-form ls-form--half--p" type="text" placeholder=""/><p>~</p>
					<input id="LsBasicFeeWaterFeeDateRange2" title="수도 사용시간 2" class="ls-form ls-form--half--p" type="text" placeholder=""/><p></p>
				</div>

				<!-- 등록/수정하기 -->
				<button id="LsBasicFeeWaterButton" class="ls-primary-button--l" onclick="formCheck('B');">
					저장
				</button>

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>




<!-- 모달 3 - 가스 기본료 관리 -->
<section class="ls-modal gas-setting">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<h6><strong>가스 단가 계산</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;" id="gasFormModal" action="#">
				<input type="hidden" id="basicfee_seq_c" value="">
				<!-- 건물명 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeGasName" class="ls-label">건물명</label>
					<input id="LsBasicFeeGasName" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 가스공사 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeKGC" class="ls-label">가스공사 청구액</label>
					<input id="LsBasicFeeKGC" class="ls-form" type="text" placeholder="" style="text-align:right;" />
				</div>

				<!-- 공제액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeGasDeduction" class="ls-label">공제액</label>
					<input id="LsBasicFeeGasDeduction" class="ls-form" type="text" placeholder="" style="text-align:right;" />
				</div>

				<!-- 공제사유 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeGasDeductionReason" class="ls-label">공제 사유</label>
					<input id="LsBasicFeeGasDeductionReason" class="ls-form" type="text" placeholder=""/>
				</div>

				<!-- 공제 후 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeGasDeductionAfterClaim" class="ls-label">공제 후 청구액</label>
					<input id="LsBasicFeeGasDeductionAfterClaim" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 가스 사용량 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeGasFee" class="ls-label">가스 사용량</label>
					<input id="LsBasicFeeGasFee" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>톤</p>
				</div>

				<!-- Kwh당 기본료 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeGasFeeDefault" class="ls-label">톤(ton)당 단가</label>
					<input id="LsBasicFeeGasFeeDefault" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 가스 사용시간-->
				<div class="ls-form-wrap">
					<label for="LsBasicFeeGasFeeDateRange1" class="ls-label">가스 사용 기간</label>
					<input id="LsBasicFeeGasFeeDateRange1" title="수도 사용시간 1" class="ls-form ls-form--half--p" type="text" placeholder=""/><p>~</p>
					<input id="LsBasicFeeGasFeeDateRange2" title="수도 사용시간 2" class="ls-form ls-form--half--p" type="text" placeholder=""/><p></p>
				</div>

				<!-- 등록/수정하기 -->
				<button id="LsBasicFeeGasButton" class="ls-primary-button--l" onclick="formCheck('C');">
					저장
				</button>

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>




<!-- 모달 4 - 기타 기본료 관리 -->
<section class="ls-modal etc-setting">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<h6><strong>기타 단가 계산</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;" id="etcFormModal" action="#" >
				<input type="hidden" id="basicfee_seq_d" value="">
				<!-- 건물명 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeEtcName" class="ls-label">건물명</label>
					<input id="LsBasicFeeEtcName" class="ls-form" type="text" placeholder="" readonly/>
				</div>

				<!-- 기타 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeEtcClaim" class="ls-label">기타 청구액</label>
					<input id="LsBasicFeeEtcClaim" class="ls-form" type="text" placeholder="" style="text-align:right;" />
				</div>

				<!-- 공제액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeEtcDeduction" class="ls-label">공제액</label>
					<input id="LsBasicFeeEtcDeduction" class="ls-form" type="text" placeholder="" style="text-align:right;" />
				</div>

				<!-- 공제사유 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeEtcDeductionReason" class="ls-label">공제 사유</label>
					<input id="LsBasicFeeEtcDeductionReason" class="ls-form" type="text" placeholder=""/>
				</div>

				<!-- 공제 후 청구액 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeEtcDeductionAfterClaim" class="ls-label">공제 후 청구액</label>
					<input id="LsBasicFeeEtcDeductionAfterClaim" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 객단가 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="LsBasicFeeEtcFeePer" class="ls-label">호실별 단가</label>
					<input id="LsBasicFeeEtcFeePer" class="ls-form ls-form--p" type="text" placeholder="" style="text-align:right;" /><p>원</p>
				</div>

				<!-- 등록/수정하기 -->
				<input type="button" id="LsBasicFeeEtcButton" class="ls-primary-button--l" onclick="formCheck('D');" value="저장">

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>







<script>
	// 전기 기본료 관리 모달
	$('#BasicFeeBody .ls-detail-body__tb .click-button.elect-setting').click( function () {
		$('.ls-modal.elect-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal.elect-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.elect-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});

	// 수도 기본료 관리 모달
	$('#BasicFeeBody .ls-detail-body__tb .click-button.water-setting').click( function () {
		$('.ls-modal.water-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal.water-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.water-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});

	// 가스 기본료 관리 모달
	$('#BasicFeeBody .ls-detail-body__tb .click-button.gas-setting').click( function () {
		$('.ls-modal.gas-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal.gas-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.gas-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});

	// 기타 기본료 관리 모달
	$('#BasicFeeBody .ls-detail-body__tb .click-button.etc-setting').click( function () {
		$('.ls-modal.etc-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal.etc-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.etc-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});
</script>

<script>
	$(document).ready(function(){
		var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi
		//전기
		$('#LsBasicFeeKEPCO').change(function(){

			var claim_fee = $('#LsBasicFeeKEPCO').val();
			var use_amount = $('#LsBasicFeeDeduction').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");
			var basic_fee = parseInt(claim_fee) - parseInt(use_amount);

			$('#LsBasicFeeDeductionAfterClaim').val(basic_fee);

		});

		$('#LsBasicFeeDeduction').change(function(){

			var claim_fee = $('#LsBasicFeeKEPCO').val();
			var use_amount = $('#LsBasicFeeDeduction').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");
			var basic_fee = parseInt(claim_fee) - parseInt(use_amount);

			$('#LsBasicFeeDeductionAfterClaim').val(basic_fee);

		});
		
		//수도
		$('#LsBasicFeeWaterClaim').change(function(){

			var claim_fee = $('#LsBasicFeeWaterClaim').val();
			var use_amount = $('#LsBasicFeeWaterDeduction').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee) - parseInt(use_amount);

			$('#LsBasicFeeWaterDeductionAfterClaim').val(basic_fee);

		});

		$('#LsBasicFeeWaterDeduction').change(function(){

			var claim_fee = $('#LsBasicFeeWaterClaim').val();
			var use_amount = $('#LsBasicFeeWaterDeduction').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee) - parseInt(use_amount);

			$('#LsBasicFeeWaterDeductionAfterClaim').val(basic_fee);

		});
		
		//가스
		$('#LsBasicFeeKGC').change(function(){

			var claim_fee = $('#LsBasicFeeKGC').val();
			var use_amount = $('#LsBasicFeeGasDeduction').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee) - parseInt(use_amount);

			$('#LsBasicFeeGasDeductionAfterClaim').val(basic_fee);

		});

		$('#LsBasicFeeGasDeduction').change(function(){

			var claim_fee = $('#LsBasicFeeKGC').val();
			var use_amount = $('#LsBasicFeeGasDeduction').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee) - parseInt(use_amount);

			$('#LsBasicFeeGasDeductionAfterClaim').val(basic_fee);

		});
		
		
		//기타
		$('#LsBasicFeeEtcClaim').change(function(){

			var claim_fee = $('#LsBasicFeeEtcClaim').val();
			var use_amount = $('#LsBasicFeeEtcDeduction').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee) - parseInt(use_amount);

			$('#LsBasicFeeEtcDeductionAfterClaim').val(basic_fee);

		});

		$('#LsBasicFeeEtcDeduction').change(function(){

			var claim_fee = $('#LsBasicFeeEtcClaim').val();
			var use_amount = $('#LsBasicFeeEtcDeduction').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee) - parseInt(use_amount);

			$('#LsBasicFeeEtcDeductionAfterClaim').val(basic_fee);

		});

		$('#LsBasicFeeEtcDeductionAfterClaim').change(function(){

			$.ajax({
				url:'/BasicFee/getFacilityCount',
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
		})

		

		//전기 기본료 계산
		$('#LsBasicFeeDeductionAfterClaim').change(function(){

			var claim_fee = $('#LsBasicFeeDeductionAfterClaim').val();
			var use_amount = $('#LsBasicFeeElectFee').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee/use_amount);

			$('#LsBasicFeeElectFeeDefault').val(basic_fee);

		});
		$('#LsBasicFeeElectFee').change(function(){

			var claim_fee = $('#LsBasicFeeDeductionAfterClaim').val();
			var use_amount = $('#LsBasicFeeElectFee').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee/use_amount);

			$('#LsBasicFeeElectFeeDefault').val(basic_fee);

		});

		//수도 기본료 계산
		$('#LsBasicFeeWaterDeductionAfterClaim').change(function(){

			var claim_fee = $('#LsBasicFeeWaterDeductionAfterClaim').val();
			var use_amount = $('#LsBasicFeeWaterFee').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee/use_amount);

			$('#LsBasicFeeWaterFeeDefault').val(basic_fee);

		});

		$('#LsBasicFeeWaterFee').change(function(){

			var claim_fee = $('#LsBasicFeeWaterDeductionAfterClaim').val();
			var use_amount = $('#LsBasicFeeWaterFee').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee/use_amount);

			$('#LsBasicFeeWaterFeeDefault').val(basic_fee);

		});

		//가스 기본료 계산
		$('#LsBasicFeeGasDeductionAfterClaim').change(function(){

			var claim_fee = $('#LsBasicFeeGasDeductionAfterClaim').val();
			var use_amount = $('#LsBasicFeeGasFee').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee/use_amount);

			$('#LsBasicFeeGasFeeDefault').val(basic_fee);

		});
		$('#LsBasicFeeGasFee').change(function(){

			var claim_fee = $('#LsBasicFeeGasDeductionAfterClaim').val();
			var use_amount = $('#LsBasicFeeGasFee').val();

			claim_fee = claim_fee.replace(regExp, "");
			use_amount = use_amount.replace(regExp, "");

			var basic_fee = parseInt(claim_fee/use_amount);

			$('#LsBasicFeeGasFeeDefault').val(basic_fee);

		});
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
		,minDate: "-3M" //최소 선택일자(-1D:하루전, -1M:한달전, -1Y:일년전)
		,maxDate: "+3Y" //최대 선택일자(+1D:하루후, -1M:한달후, -1Y:일년후)
	})

	$('#basicFeeDateSELECT').datepicker();

</script>
</body>
</html> 
