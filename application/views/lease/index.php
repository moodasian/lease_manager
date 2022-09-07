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
	<script type="text/javascript" src="/resources/js/lease_manage.js"></script>
	<script type="text/javascript" src="/resources/js/lease.js"></script>

	<!--제이쿼리 ui css-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!--제이쿼리 js-->
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<!--제이쿼리 ui js-->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<style>
		.ui-datepicker{ font-size: 20px; width: 300px; height:200px;}
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
	<section id="LeaseTitle" class="ls-detail-title">
		<!-- 타이틀 -->
		<div class="ls-detail-title__header">
			<h5><strong>시설/임대관리</strong></h5>
			<h6 style="margin-top: 8px">임차인 등록</h6>
		</div>

		<form id="LeaseTitleSearch" class="ls-detail-title__form" onsubmit="return false;">
			<input type="hidden" id="buildingName" value="">
			<input type="hidden" id="room_num" value="">
			<div class="form-wrap">
				<label for="" class="ls-label">건물명</label>
				<select id="buildingListSelect" class="ls-form">
					<option>선택하기</option>
				</select>
			</div>
			<div class="form-wrap">
				<label for="" class="ls-label">호실</label>
				<select id="FacilityListSelect" class="ls-form">
					<option>선택하기</option>
				</select>
			</div>
			<div class="button-wrap">
				<button class="ls-default-button--m" onclick="getPost();">
					<div class="search_ico"></div>
					검색
				</button>
			</div>
		</form>
	</section>


	<!-- 섹션 본문 -->
	<section id="LeaseBody" class="ls-detail-body">

		<button type="button" class="ls-detail-body__download">
			<span class="down-ico"></span>
			엑셀로 다운받기
		</button>



		<!-- 테이블 헤드 -->
		<ul class="ls-detail-body__th">
			<li>건물명</li>
			<li>호실</li>
			<li>면적(m2)</li>
			<li>임대여부</li>
			<li>임차인 명</li>
			<li>세부정보 (수정)</li>
			<li>삭제</li>
		</ul>

		<div id="leastListBody"></div>
<!--		<div class="ls-detail-body__tb" ></div>-->



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

<?php include($_SERVER["DOCUMENT_ROOT"].'/application/views/lease/lease_modal.php') ?>

</body>
</html>
