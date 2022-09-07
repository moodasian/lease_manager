<!DOCTYPE html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<title>임대방 - 임대관리 전문 솔루션</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,viewport-fit=cover">

	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
	<link href="/resources/css/common.css?ver=0" rel="stylesheet" type="text/css">
	<link href="/resources/css/lease.css?ver=0" rel="stylesheet" type="text/css">
	<link href="/resources/css/slick.css?ver=0" rel="stylesheet" type="text/css">
	 
	<!-- jquery 추가 -->
	<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="/resources/js/welcome.js"></script>
	<script type="text/javascript" src="/resources/js/slick.min.js"></script>

</head>
<body class="main">
<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/application/views/common/header.php')
?>
<main>
	<section class="ls-main__slick">
		<div class="ls-main__slick--carousel">
			<div>
				<a href="#" title="slick-01">
					<img src="/resources/images/banner_2.png">
				</a>
			</div>
			<div>
				<a href="#" title="slick-02">
					<img src="/resources/images/banner_3.png">
				</a>
			</div>
			<div>
				<a href="#" title="slick-03">
					<img src="/resources/images/banner_4_1.png">
				</a>
			</div>
		</div>
	</section>

	<section class="ls-main__notice">
		<div class="ls-main__notice--left">
			<h5 class="ls-form-title" style="margin-bottom: 16px" id="admin_notice_register">
				<strong>공지사항</strong>
			</h5>
			<ul class="list-notice" id="NoticeBody">
		</div>


		<div class="ls-main__notice--right">
			<h5 class="ls-form-title" style="margin-bottom: 16px" id="qna_register">
				<strong>Q&A</strong>
			</h5>
			<ul class="list-notice" id="QnABody">
		</div>
	</section>


	<section class="ls-main__board">
		<div class="ls-main__board--left" id="summaryLeft"></div>
		<div class="ls-main__board--left" id="summaryRight"></div>
		<div style="text-align: center; margin-bottom:10px;">
			<input type="button" value="인쇄" onclick="CalPrint();" style="    padding: 5px 15px 5px 15px;
    margin-right: 20px;
    margin-bottom: 10px;
    background-color: #ff5500;
    color: white;
    border: none;
    font-weight: 700;
    border-radius: 8px;
    min-width: 88px;
    min-height: 33px;
    font-size: 14px;">
			<input type="button" value="SMS발송" onclick="sendCalSMS();" style="    padding: 5px 15px 5px 15px;
    margin-right: 20px;
    margin-bottom: 10px;
    background-color: #ff5500;
    color: white;
    border: none;
    font-weight: 700;
    border-radius: 8px;
    min-width: 88px;
    min-height: 33px;
    font-size: 14px;">
		</div>
	</section>
</main>



<!-- 모달 -->
<section class="ls-modal credit-setting">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<h6><strong>공지사항</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form id="file_frm" method="post" enctype="multipart/form-data" style="font-size: 0;">

				<!-- 공지 제목 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="notice_title" class="ls-label">공지 제목</label>
					<input id="notice_title" class="ls-form" type="text" placeholder="" />
				</div>

				<!-- 공지 내용 -->
				<div class="ls-form-wrap" style="padding-right: 0" id="notice_upload">
					<div class="ls-form-wrap half">
						<label for="upload_file" class="ls-label">파일 선택하기</label>
						<input type="file" id="upload_file" class="ls-form" />
						<input type="hidden" id="upload_notice_filename" value="">
					</div>
					<div class="ls-form-wrap half">
						<label for="notice_file" class="ls-label"> 업로드</label>
						<input type="button" class="ls-default-button--m" value="적용" onclick="file_submit()" />
					</div>
				</div>

				<!-- 이미지 -->
				<div class="ls-form-wrap" style="padding-right: 0" id="notice_img">
					<label for="notice_title" class="ls-label">첨부 이미지</label>
					<div id="notice_img" style="max-width:300px;"></div>
				</div>

				<!-- 공지 내용 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="notice_contents" class="ls-label">공지 내용</label>
					<textarea id="notice_contents" class="ls-form" type="text" placeholder=""  style="height: 500px;"></textarea>
				</div>




				<!-- 등록/수정하기 -->
				<input id="LsSubmitButton" class="ls-primary-button--l" type="button" onclick="formCheck()" value="저장" style="display:none;"/>

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>


<!-- 모달 -->
<section class="ls-modal credittwo-setting">
	<div class="ls-modal-body">

		<div class="modal-body__title">
			<h6><strong>Q&A</strong></h6>
			<button class="modal-close" type="button" onclick=""></button>
		</div>

		<div class="modla-body__detail">
			<form style="font-size: 0;">
				<input type="hidden" id="qna_seq" value="">

				<!-- Q&A 제목 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="qna_title" class="ls-label">질문 제목</label>
					<input id="qna_title" class="ls-form" type="text" placeholder="" />
				</div>

				<!-- 공지 내용 -->
				<div class="ls-form-wrap" style="padding-right: 0" id="notice_upload">
					<div class="ls-form-wrap half">
						<label for="upload_file" class="ls-label">파일 선택하기</label>
						<input type="file" id="upload_file_qna" class="ls-form" />
						<input type="hidden" id="upload_qna_filename" value="">
					</div>
					<div class="ls-form-wrap half">
						<label for="qna_file" class="ls-label"> 업로드</label>
						<input type="button" class="ls-default-button--m" value="적용" onclick="file_submit_qna()" />
					</div>
				</div>

				<!-- 이미지 -->
				<div class="ls-form-wrap" style="padding-right: 0" id="notice_img">
					<label for="notice_title" class="ls-label">첨부 이미지</label>
					<div id="qna_img" style="max-width:300px;"></div>
				</div>

				<!-- Q&A 내용 -->
				<div class="ls-form-wrap" style="padding-right: 0">
					<label for="qna_contents" class="ls-label">질문 본문</label>
					<textarea id="qna_contents" class="ls-form" type="text" placeholder=""  style="height:200px;" ></textarea>
				</div>

				<!-- Q&A 답변내용 -->
				<div class="ls-form-wrap" style="padding-right: 0" id="a_reply">
					<label for="qna_reply" class="ls-label">답변</label>
					<textarea id="qna_reply" class="ls-form" type="text" placeholder="" style="height:200px;" ></textarea>
				</div>


				<!-- 등록/수정하기 -->
				<input id="LsSubmitButton" class="ls-primary-button--l" type="button" onclick="formCheck_qna()" value="저장" />

			</form>
		</div>

	</div>
	<div class="ls-modal-fade"></div>

</section>






<script>
	$('#CreditBody .ls-detail-body__tb .click-button.bill-setting').click( function () {
		$('.ls-modal.credit-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
	$('.ls-modal.credit-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.credit-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});
	$('.ls-modal.credittwo-setting .modal-close, .ls-modal-fade').click( function () {
		$('.ls-modal.credittwo-setting').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	});
</script>

	
	  


<script>
	 $(document).ready(function(){
		$('.ls-main__slick--carousel').slick();
	});
</script>


</body>
</html>
