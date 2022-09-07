$(document).ready(function(){
	var pageNum = 1;
	var pagination;
	var DATA_URL = '/UserJoin/userJoinCheck';

	$('#AllAgree').on('click', function() {
		if ($("#AllAgree").prop("checked")) {
			$("input[name=AgreeOption]").prop("checked", true);
		} else {
			$("input[name=AgreeOption]").prop("checked", false);
		}
	});

	$('#UserJoinAgree').on('click', function() {
		$('.ls-modal.userjoinagree-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});

	$('#PrivacyCollectAgree').on('click', function() {
		$('.ls-modal.privacy-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});

	$('#ThirdPrivacyAgreeDiv').on('click', function() {
		$('.ls-modal.third-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	});
});

function formCheck(){
	var user_id = $('#LsRegID').val();
	var user_pw = $('#LsRegPass').val();
	var user_passwd_confirm = $("#LsRegPassConfirm").val();
	var user_email = $('#LsRegEmail').val();
	var user_name = $('#LsRegName').val();
	var user_phone = $('#LsRegPhone').val();

	if(user_pw !== user_passwd_confirm){
		alert('비밀번호가 일치하지 않습니다.');
		return;
	}


	$.ajax({
		url:"/UserJoin/userJoinCheck",
		type:"POST",
		data: {user_id:user_id, user_pw:user_pw, user_email:user_email, user_name:user_name, user_phone:user_phone},
		dataType:'JSON',
		success:function(data){
			alert(data.msg);
			window.location.href = "/LogIn";
			//location.href('/LogIn');
		},
		error:function(e){
			console.log(e);
		}
	});

}
