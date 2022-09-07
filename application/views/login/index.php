<!DOCTYPE html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<title>임대방 - 임대관리 전문 솔루션</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,viewport-fit=cover">

	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- jquery 추가 -->
	<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="/resources/js/userjoin.js.js"></script>
	<script type="text/javascript" src="/resources/js/lease.js"></script>
	<link href="/resources/css/common.css" rel="stylesheet" type="text/css">
	<link href="/resources/css/lease.css" rel="stylesheet" type="text/css">
	<script>
		function loginCheck(){

			var id = $('#LsLogin').val();
			var passwd = $('#LsPass').val();

			if(id && passwd){
				login(id, passwd);
			}else{
				if(id == ""){
					alert("ID를 입력해주세요.");
					$('#LsLogin').focus();
				}else if(passwd == ""){
					alert("비밀번호를 입력해주세요.");
					$('#LsPass').focus();
				}
			}

		}

		function login(id, passwd){
			$.ajax({
				url:"/LogIn/logInAction",
				type:"POST",
				data: {id:id, passwd:passwd},
				dataType:'JSON',
				success:function(data){
					alert(data.msg);
					location.href = './Building';
				},
				error:function(e){
					console.log(e);
				}
			});
		}
	</script>
</head>
<body>
<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/application/views/common/header.php')
?>
<main>

	<section class="ls-login__section">
		<h6><strong>로그인이 필요합니다.</strong></h6>

		<form action="#">
			<!-- 로그인 -->
			<label for="LsLogin" class="ls-label">로그인</label>
			<input id="LsLogin" class="ls-form" type="text" placeholder="아이디를 입력해주세요"/>


			<!-- 패스워드 -->
			<label for="LsPass" class="ls-label">비밀번호</label>
			<input id="LsPass" class="ls-form" type="password" placeholder="비밀번호를 입력해주세요"/>

			<!-- 로그인 버튼 -->
			<button id="LsLoginButton" class="ls-primary-button--l" type="button" onclick="loginCheck()">
				로그인
			</button>

			<div style="    margin-top: 10%;
    text-align: center;">
				<a href="./UserJoin" style="font-size:15px;">회원가입하기</a>
			</div>
		</form>

		<!-- sns 로그인 -->

		<div class="ls-login__sns" style="display:none;">
			<p><strong>SNS 계정으로 로그인하기</strong></p>
			<div class="ls-login__sns__button">
				<!-- 네이버 -->
				<button id="LoginNaver" class="sns-login-button" style="background-color: #53da00;" type="button"></button>
				<!-- 카카오 -->
				<button id="LoginKakao" class="sns-login-button" style="background-color: #fbe62b;" type="button"></button>
				<!-- 페이스 -->
				<button id="LoginFace" class="sns-login-button" style="background-color: #1673ff;" type="button"></button>
				<!-- 애플 -->
				<button id="LoginApple" class="sns-login-button" style="background-color: #474747;" type="button"></button>
			</div>

			<!-- 로그인 기타 링크 -->
			<div class="ls-login__link">
				<a class="ls-login__link__reg" href="/UserJoin" title="회원가입">아이디가 없다면?</a>
				<a class="ls-login__link__forget" href="#" title="회원가입">ID/PW를 잊으셨다면?</a>
			</div>
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


</body>
</html>
