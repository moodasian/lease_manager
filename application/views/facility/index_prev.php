<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/application/views/common/header.php')
?>
<!DOCTYPE html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<title>임대방 - 시설관리</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- <link href='<?php include($_SERVER["DOCUMENT_ROOT"].'/application/views/common/css/lease.css') ?>' rel="stylesheet"> -->
	<!-- jquery 추가 -->
	<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="/resources/js/facility.js"></script>
	<link href="/resources/css/common.css" rel="stylesheet" type="text/css">
	<link href="/resources/css/lease.css" rel="stylesheet" type="text/css">
</head>
<body>
<main>
	<!-- 섹션 타이틀 -->
	<section id="FacilityTitle" class="ls-detail-title">
		<!-- 타이틀 -->
		<div class="ls-detail-title__header">
			<h5><strong>시설/임대관리</strong></h5>
			<h6 style="margin-top: 8px">시설 관리</h6>
		</div>

		<!-- 건물등록 버튼 -->
		<div class="ls-detail-title__add-button">
			<button type="button" class="ls-primary-button--l">
				<!-- <span class="material-icons md-24 md-white">add</span> -->
				신규 시설 등록
			</button>
		</div>

		<form id="FacilityTitleSearch" class="ls-detail-title__form" action="#">
			<div class="form-wrap">
				<label for="" class="ls-label">건물명</label>
				<select id="buildingListSelect" class="ls-form">
					<option>선택해주세요</option>
				</select>
			</div>
			<div class="form-wrap">
				<label for="" class="ls-label">호실</label>
				<select id="facilityList" class="ls-form">
					<option>선택해주세요</option>
				</select>
			</div>
			<div class="button-wrap">
				<button class="ls-default-button--m">검색</button>
			</div>
		</form>
	</section>


	<!-- 섹션 본문 -->
	<section id="FacilityBody" class="ls-detail-body">

		<button type="button" class="ls-detail-body__download">
			엑셀로 다운받기
		</button>



		<!-- 테이블 헤드 -->
		<ul class="ls-detail-body__th">
			<li>건물명</li>
			<li>호실</li>
			<li>면적(m2)</li>
			<li>임대여부</li>
			<li>세부정보 (수정)</li>
			<li>삭제</li>
		</ul>

		<!-- 테이블 바디 (복사) -->
		<div id="facilityListBody"></div>



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

<?php include($_SERVER["DOCUMENT_ROOT"].'/application/views/facility/facility_modal.php') ?>
</body>
</html>
