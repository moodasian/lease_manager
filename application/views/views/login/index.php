<?php

?>
<!DOCTYPE html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
	<style>
		<?php include($_SERVER["DOCUMENT_ROOT"].'/application/views/common/css/common.css') ?>
		<?php include($_SERVER["DOCUMENT_ROOT"].'/application/views/common/css/lease.css') ?>
	</style>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/application/views/common/header.php') ?>



	
    <main>
        
        <section class="ls-login__section">
            <h6><strong>로그인이 필요합니다.</strong></h6>
            
            <form>
                <!-- 로그인 -->
                <label for="LsLogin" class="ls-label">로그인</label>
                <input id="LsLogin" class="ls-form" type="text" placeholder="아이디를 입력해주세요"/>
            
                
                <!-- 패스워드 -->
                <label for="LsPass" class="ls-label">비밀번호</label>
                <input id="LsPass" class="ls-form" type="password" placeholder="비밀번호를 입력해주세요"/>

                <!-- 로그인 버튼 -->
                <button id="LsLoginButton" class="ls-primary-button--l" type="submit">
                    로그인하기
                </button>
            </form>

            <!-- sns 로그인 -->
            <div class="ls-login__sns">
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
                </from>
            </div>

            <!-- 로그인 기타 링크 -->
            <div class="ls-login__link">
                <a class="ls-login__link__reg" href="#" title="회원가입">아이디가 없다면?</a>
                <a class="ls-login__link__forget" href="#" title="회원가입">ID/PW를 잊으셨다면?</a>
            </div>


        </section>

    </main>



</body>
</html>
