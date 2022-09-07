<!DOCTYPE html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<title>임대방 - 임대관리 전문 솔루션</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,viewport-fit=cover">

	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
	<link href="/resources/css/common.css?ver=1" rel="stylesheet" type="text/css">
	<link href="/resources/css/lease.css?ver=2" rel="stylesheet" type="text/css">
    
	<!-- jquery 추가 -->
	<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="/resources/js/building.js?ver=1"></script>
	<script type="text/javascript" src="/resources/js/lease.js?ver=1"></script>
</head>
<body>
<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/application/views/common/header.php')
?>
<main>

	<!-- 디테일 타이틀 -->
	<section id="BuildingTitle" class="ls-detail-title">
		<!-- 타이틀 -->
		<div class="ls-detail-title__header">
			<h5><strong>시설/임대관리</strong></h5>
			<h6 style="margin-top: 8px">건물 등록</h6>
		</div>
		<!-- 건물등록 버튼 -->
		<div class="ls-detail-title__add-button">
			<button type="button" class="ls-primary-button--l" onclick="function openModal(){}">
				<span class="ico"></span>
				건물 신규 등록
			</button>
		</div>
		<!-- 건물등록 검색 폼 -->
		<form id="BuildingTitleSearch" class="ls-detail-title__form" onsubmit="return false;">
			<input type="hidden" id="buildingName" value="">
			<input type="hidden" id="ownerName" value="">
			<div class="form-wrap">
				<label for="BuildingTitleInput" class="ls-label">건물명</label>
				<input id="BuildingTitleInput" type="text" class="ls-form" placeholder="건물명을 입력하세요"/>
			</div>
			<div class="form-wrap">
				<label for="BuildingOwnerInput" class="ls-label">건물주명</label>
				<input id="BuildingOwnerInput" type="text" class="ls-form" placeholder="건물주명을 입력하세요"/>
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
	<section id="BuildingBody" class="ls-detail-body">
		<h6 style="margin-bottom: 16px; float: left;">건물 등록 리스트</h6>

		<button type="button" class="ls-detail-body__download">
			<span class="down-ico"></span>
			엑셀로 다운받기
		</button>



		<!-- 테이블 헤드 -->
		<ul class="ls-detail-body__th">
			<li>건물명</li>
			<li>건물주명</li>
			<li>수정</li>
			<li>삭제</li>
			<li>등록일</li>
		</ul>

		<!-- 테이블 바디 (복사) -->
		<div class="" id="buildingBodyTb"></div>

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


<!-- body 하단에 넣어주면 됩니다. -->
<div class="as-grid-body1">
	<ul class="as-grid">
		<li class="as-grid__column col-1"><div></div></li>
		<li class="as-grid__column col-2"><div></div></li>
		<li class="as-grid__column col-3"><div></div></li>
		<li class="as-grid__column col-4"><div></div></li>
		<li class="as-grid__column col-5"><div></div></li>
		<li class="as-grid__column col-6"><div></div></li>
		<li class="as-grid__column col-7"><div></div></li>
		<li class="as-grid__column col-8"><div></div></li>
		<li class="as-grid__column col-9"><div></div></li>
		<li class="as-grid__column col-10"><div></div></li>
		<li class="as-grid__column col-11"><div></div></li>
		<li class="as-grid__column col-12"><div></div></li>
	</ul>
</div>
<!-- // body 하단에 넣어주면 됩니다. // -->



<?php include($_SERVER["DOCUMENT_ROOT"].'/application/views/building/building_modal.php') ?>

<!--    <script type="text/javascript" src="js/jquery_ui.min.js"></script>-->
</body>
</html>
