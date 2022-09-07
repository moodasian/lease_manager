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
	<script src="/resources/js/exitfacility.js"></script>
	<script type="text/javascript" src="/resources/js/lease.js"></script>
</head>
<body>
<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/application/views/common/header.php')
?>


<main>

    <!-- 섹션 타이틀 -->
    <section id="ExitFacilityTitle" class="ls-detail-title">
        <!-- 타이틀 -->
        <div class="ls-detail-title__header" style="margin-bottom:48px">
            <h5><strong>정산관리</strong></h5>
            <h6 style="margin-top: 8px">퇴실정산</h6>
            <!-- <h6 style="margin-top: 8px">청구 관리</h6> -->
        </div>


    
        <form class="ls-form-wrap  m-all" style="font-size:0; padding: 32px 0 32px; border-top: 1px solid #e6e7e9;">
           
            <!-- 왼쪽 폼 영역 -->
            <div class="ls-form-wrap half m-all left">

                <!-- 건물 명 -->
                <label for="LsExitFacility-01" class="ls-label">건물</label>
				<select id="LsExitFacility-01" class="ls-form">
					<option value="">선택해주세요</option>
				</select>

				<!-- 호실 -->
				<label for="LsExitFacility-11" class="ls-label">호실</label>
				<select id="LsExitFacility-11" class="ls-form">
					<option value="">선택해주세요</option>
				</select>

			</div>


			<!-- 오른쪽 폼 영역 -->
			<div class="ls-form-wrap half m-all right">

                <!-- 보증금 -->
                <label for="LsExitFacility-02" class="ls-label">보증금</label>
                <input id="LsExitFacility-02" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>

                <!-- 월 임차료 -->
                <label for="LsExitFacility-03" class="ls-label">임대료</label>
                <input id="LsExitFacility-03" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>

                <!-- 관리비 -->
                <label for="LsExitFacility-04" class="ls-label">관리비</label>
                <input id="LsExitFacility-04" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>

                <!-- 기타비 -->
                <label for="LsExitFacility-05" class="ls-label">기타비</label>
                <input id="LsExitFacility-05" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>

                <!-- 전기요금 -->
                <label for="LsExitFacility-06" class="ls-label" style="margin-top: 48px;">전기요금</label>
                <input id="LsExitFacility-06" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>
                
                <!-- 수도요금 -->
                <label for="LsExitFacility-07" class="ls-label">수도요금</label>
                <input id="LsExitFacility-07" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>
                
                <!-- 가스요금 -->
                <label for="LsExitFacility-08" class="ls-label">가스요금</label>
                <input id="LsExitFacility-08" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>
                
                <!-- 기타요금 -->
                <label for="LsExitFacility-09" class="ls-label">기타요금</label>
                <input id="LsExitFacility-09" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>

                <!-- 체납액 -->
                <label for="LsExitFacility-10" class="ls-label">체납액</label>
                <input id="LsExitFacility-10" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>

				<!-- 중개수수료 -->
				<label for="LsExitFacility-12" class="ls-label">중개 수수료</label>
				<input id="LsExitFacility-12" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>

				<!-- 퇴실청소비 -->
				<label for="LsExitFacility-14" class="ls-label">퇴실청소비</label>
				<input id="LsExitFacility-14" name="exitfacility_val" class="ls-form ls-form--p" type="number" placeholder=""/>
				<p>원</p>

				<!-- 환불총액 -->
				<label for="LsExitFacility-21" class="ls-label" style="color: #FF5500;">환불총액</label>
				<input type="button" class="ls-default-button--m" onclick="sum_vals();" style="margin-top:10px;" value="총합 계산"/>
				<input id="LsExitFacility-21" class="ls-form ls-form--p" type="number" placeholder="" style="margin-top:10px;" />
				<p>원</p>


				<div class="ls-form-wrap third" style="margin-top: 48px;">
					<button type="button" class="ls-default-button--m" onclick="window.print();">인쇄</button>
				</div>

				<div class="ls-form-wrap third" style="margin-top: 48px;">
					<input type="button" class="ls-default-button--m" onclick="sendSMS()" value="SMS 발송" />
				</div>

				<div class="ls-form-wrap third" style="margin-top: 48px;">
					<input type="button" class="ls-default-button--m" onclick="formCheck2()" value="저장" style="background-color:#ff5500" />
				</div>

			</div>

		</form>

		<!-- <div class="ls-form-wrap third m-all"></div> -->
   

    </section>

</main>


<?php include($_SERVER["DOCUMENT_ROOT"].'/application/views/facility/facility_modal.php') ?>
</body>
</html>
