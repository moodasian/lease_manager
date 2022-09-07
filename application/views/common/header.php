<header id="ls-header" class="ls-header">
	<div class="ls-header-wrap">
		<div class="left-logo">
			<a href="/" title="메인으로">
				<img style="height:100%;" src="/resources/images/imdaebang_logo.png">
			</a>
		</div>
		<nav class="right-nav">
			<ul>
				<li>
					<a href="./Building">시설/임대관리</a>
					<ul class="drop-down">
						<li><a href="./Building">[건물 등록]</a></li>
						<li><a href="./Facility">[호실 등록]</a></li>
						<li><a href="./Lease">[임차인 등록]</a></li>
					</ul>
				</li>
				<li>
					<a href="./Claim">청구관리</a>
					<ul class="drop-down" >
						<li><a href="./Claim">[임대료 등 청구]</a></li>
						<li><a href="#">[전기료 등 청구]</a></li>
						<li>
							<a href="./BasicFee">
								▶ 단가 계산
							</a>
						</li>
						<li>
							<a href="./UsageManage">
								▶ 사용량 계산
							</a>
						</li>
						<li><a href="./Bill">[청구서]</a></li>
					</ul>
				</li>
				<li>
					<a href="./Credit">입금관리</a>
					<ul class="drop-down">
						<li><a href="./Credit">[입금]</a></li>
						<li><a href="./Arrears">[체납]</a></li>
					</ul>
				</li>
				<li>
					<a href="./Expense">정산관리</a>
					<ul class="drop-down">
						<li><a href="./Expense">[지출]</a></li>
						<li><a href="./Calculation">[정산]</a></li>
						<li><a href="./ExitFacility">[퇴실정산]</a></li>
					</ul>
				</li>
				<li>
					<a href="./Expense">마이페이지</a>
					<ul class="drop-down">
						<!--<li><a href="./Expense">내정보</a></li>-->
						<li><a href="./LogIn/logOutAction">[로그아웃]</a></li>
					</ul>
				</li>
			</ul>
		</nav>

		<button type="button" onclick="" class="right-side"></button>

	</div>


</header>

<section class="ls-header-side">

	<div class="ls-header-side__body">
		<button type="button" class="ls-header-side__close"></button>
		<h6>1. 시설/임차관리</h6>
		<ul>
			<li><a href="/Building" title="건물 관리">[건물 등록]</a></li>
			<li><a href="/Facility" title="호실 관리">[호실 등록]</a></li>
			<li><a href="/Lease" title="임차 관리">[임차인 등록]</a></li>
		</ul>
		<h6>2. 청구관리</h6>
		<ul>
			<li><a href="/Claim" title="임대료 청구 관리">[임대료 등 청구]</a></li>
			<li><a href="#" title="전기세 등 관리">[전기료 등 청구]</a></li>
			<li><a href="/BasicFee" title="기본료 확정">▶ 단가 계산</a></li>
			<li><a href="/UsageManage" title="사용량 확정">▶ 사용량 계산</a></li>
			<li><a href="/Bill" title="청구서">[청구서]</a></li>
		</ul>
		<h6>3. 입금관리</h6>
		<ul>
			<li><a href="/Credit" title="입금">[입금]</a></li>
			<li><a href="/Arrears" title="체납">[체납]</a></li>
		</ul>
		<h6>4. 정산관리</h6>
		<ul>
			<li><a href="/Expense" title="지출">[지출]</a></li>
			<li><a href="/Calculation" title="정산">[정산]</a></li>
			<li><a href="/ExitFacility" title="퇴실정산">[퇴실정산]</a></li>
		</ul>
		<h6>5. 마이페이지</h6>
		<ul>
			<!--<li><a href="/Expense" title="내정보">[내정보]</a></li>-->
			<li><a href="/LogIn/logOutAction" title="로그아웃">[로그아웃]</a></li>
		</ul>
	</div>
	<div class="ls-header-side__fade"></div>

</section>

<script>
	$('.ls-header .right-side').click( function () {
		$('.ls-header-side').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});

	$('.ls-header-side__fade, .ls-header-side__close').click( function () {
		$('.ls-header-side').removeClass('open');
		$("body").css({overflow:'auto'}).unbind('touchmove');
	})
</script>
