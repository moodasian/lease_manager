$(document).ready(function(){
	init();

	function init(){
		loadNoticeList();
		loadQnAList();
		loadSummary();
		loadSummaryAccum();
		adminCheck();
	}
	function adminCheck(){
		$.ajax({
			url:'/Welcome/getAdminCheck',
			type:"POST",
			dataType:'JSON',
			success:function(data){
				if(data.user_grade == '1'){
					var html = '';
					html += '<input type="button" class="click-button calculation-setting" value="등록하기" onclick="openmodal('+"9999"+', '+"'regist'"+', '+"'A'"+')">';

					$('#admin_notice_register').append(html);
				}
			},
			error:function(e){
				console.log(e);
			}
		});
	}

	function loadNoticeList() {
		$.ajax({
			url:'/Welcome/getNoticeList',
			type:"POST",
			dataType:'JSON',
			success:function(data){
				renderNoticeList(data);
			},
			error:function(e){
				console.log(e);
			}
		});
	}

	function renderNoticeList(data){
		var html = '';

		for(var i=0; i<5; i++){

			if(data[i] === undefined){
				html += '<li><a href="#" title="" onclick="return false;">등록된 공지사항이 없습니다.</a></li>';

			}else{
				var notice_title = data[i].notice_title;
				html += '<li><a href="#" title="QnA 1" onclick="openmodal('+data[i].seq+', '+"'notice'"+', '+"'B'"+')">'+notice_title+'</a></li>';
			}
		}

		$('#NoticeBody').append(html);
	}

	function loadQnAList(){
		$.ajax({
			url:'/Welcome/getQnAList',
			type:"POST",
			dataType:'JSON',
			success:function(data){
				renderQnAList(data);
			},
			error:function(e){
				console.log(e);
			}
		});
	}

	function renderQnAList(data){
		var html_test = '';
		var html='';

		html_test += '<input type="button" class="click-button calculation-setting" value="등록하기" onclick="openmodal('+"9999"+', '+"'qna_regist'"+', '+"'A'"+')">';

		$('#qna_register').append(html_test);

		for(var i=0; i<5; i++){

			if(data[i] === undefined){
				html += '<li><a href="#" title="" onclick="return false;">등록된 Q&A가 없습니다.</a></li>';
			}else{
				var qna_title = data[i].qna_title;
				html += '<li><a href="#" title="QnA 1" onclick="openmodal('+data[i].seq+', '+"'qna'"+', '+"'B'"+')">'+qna_title+'</a></li>';
			}
		}

		$('#QnABody').append(html);
	}

	function loadSummaryAccum(){
		$.ajax({
			url:'/Welcome/getSummaryInfo_Accum',
			type:"POST",
			dataType:'JSON',
			success:function(data){
				console.log(data);
				var html= '';
				if(data == ''){
					html += '<h4 class="ls-form-title" style="margin-bottom: 20px"><strong>전월 정산 현황</strong></h4>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">전월 청구액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">전월 입금액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">전월 체납액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">전월 지출액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">합계액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
				}else{
					var total_cost = parseInt(data.lm_usage_cost) + parseInt(data.total_income) + parseInt(data.total_arrears) + parseInt(data.total_expense);

					html += '<h4 class="ls-form-title" style="margin-bottom: 20px"><strong>전월 정산 현황</strong></h4>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">전월 청구액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+data.lm_usage_cost+'</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">전월 입금액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+data.total_income+'</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">전월 체납액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+data.total_arrears+'</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">전월 지출액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+data.total_expense+'</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">합계액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+parseInt(total_cost)+'</strong></h6>';
					html += '</div>';
				}


				$('#summaryLeft').append(html);
			},
			error:function(e){
				console.log(e);
			}
		});
	}

	function loadSummary(){
		$.ajax({
			url:'/Welcome/getSummaryInfo_RIGHT',
			type:"POST",
			dataType:'JSON',
			success:function(data){
				var html= '';
				if(data == ''){
					html += '<h4 class="ls-form-title" style="margin-bottom: 20px"><strong>누적 정산 현황</strong></h4>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">누적 청구액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">누적 입금액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">누적 체납액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">누적 지출액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">합계액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">건물 정보를 입력해주세요.</strong></h6>';
					html += '</div>';
				}else{
					var total_cost = parseInt(data.lm_usage_cost) + parseInt(data.total_income) + parseInt(data.total_arrears) + parseInt(data.total_expense);

					html += '<h4 class="ls-form-title" style="margin-bottom: 20px"><strong>누적 정산 현황</strong></h4>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">누적 청구액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+data.lm_usage_cost+'</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">누적 입금액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+data.total_income+'</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">누적 체납액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+data.total_arrears+'</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">누적 지출액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+data.total_expense+'</strong></h6>';
					html += '</div>';
					html += '<div class="board--list" style="margin-bottom:5px;">';
					html += '	<h6 class="list-title"><strong style="font-size: 20px;">합계액</strong></h6>';
					html += '	<h6 class="list-data"><strong style="font-size: 20px;">'+parseInt(total_cost)+'</strong></h6>';
					html += '</div>';
				}

				$('#summaryRight').append(html);
			},
			error:function(e){
				console.log(e);
			}
		});
	}
});

function openmodal(seq, gubun, viewYN){
	$('#notice_img').empty();
	$('#qna_img').empty();
	if(viewYN == 'A'){
		$('#notice_upload').css("display", "block");
	}else{
		$('#notice_upload').css("display", "none");
	}

	if(gubun == 'notice'){

		$.ajax({
			url:'/Welcome/getNoticeIndi',
			type:"POST",
			dataType:'JSON',
			data:{seq:seq},
			success:function(data){

				//$("#notice_upload").css("display", "none");
				var img_src = data.notice_file_root + data.notice_file_name;
				$('#notice_title').val(data.notice_title);
				$('#notice_contents').val(data.notice_content);

				var image = new Image();
				image.style.width="350px";
				image.src = img_src;
				$("#notice_img").append(image);
			},
			error:function(e){
				console.log(e);
			}
		});

		$('.ls-modal.credit-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});


	}else if(gubun == 'qna'){
		$("#qna_seq").val(seq);

		$.ajax({
			url:'/Welcome/getQnAIndi',
			type:"POST",
			dataType:'JSON',
			data:{seq:seq},
			success:function(data){

				var img_src = data.qna_file_root + data.qna_file_name;

				var image = new Image();
				image.style.width="350px";
				image.src = img_src;
				$("#qna_img").append(image);

				if(data.q_title == ''){
					$('#qna_title').val(data.a_title);
				}else{
					$('#qna_title').val(data.q_title);
				}
				$('#qna_contents').val(data.q_contents);
				$('#qna_reply').val(data.a_contents);
			},
			error:function(e){
				console.log(e);
			}
		});


		$('.ls-modal.credittwo-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});


	}else if(gubun == 'regist'){

		$('#notice_title').val('');
		$('#notice_contents').val('');

		$('#LsSubmitButton').css("display", "block");
		$('#notice_title').attr("readonly", false);
		$('#notice_contents').attr("readonly", false);

		$('.ls-modal.credit-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});

	}else if(gubun == 'qna_regist'){

		$('#a_reply').css("display", "none");
		$('#qna_title').val('');
		$('#qna_contents').val('');

		$('.ls-modal.credittwo-setting').addClass('open');
		$("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
	}
}

function formCheck(){

	var notice_title = $("#notice_title").val();
	var notice_contents = $("#notice_contents").val();
	var notice_filename = $('#upload_notice_filename').val();

	if(notice_title == ""){
		alert("공지사항 제목을 입력해주세요");
		$("#notice_title").focus();
	}

	if(notice_contents == ""){
		alert("공지사항 내용을 입력해주세요");
		$("#notice_contents").focus();
	}

	$.ajax({
		url:'/Welcome/registNotice',
		type:"POST",
		dataType:'JSON',
		data:{notice_title:notice_title, notice_contents:notice_contents, notice_filename:notice_filename},
		success:function(data){
			alert(data.msg);
			location.reload();
		},
		error:function(e){
			console.log(e);
		}
	});
}

function formCheck_qna(){
	var qna_title = $("#qna_title").val();
	var qna_contents = $("#qna_contents").val();
	var qna_a_contents = $("#qna_reply").val();
	var qna_seq = $("#qna_seq").val();

	var qna_filename = $('#upload_qna_filename').val();

	if(qna_title == ""){
		alert("질문 제목을 입력해주세요");
		$("#notice_title").focus();
	}

	if(qna_contents == ""){
		alert("질문 내용을 입력해주세요");
		$("#notice_contents").focus();
	}


	if(qna_seq == ""){
		$.ajax({
			url:'/Welcome/registQnA',
			type:"POST",
			dataType:'JSON',
			data:{qna_title:qna_title, qna_contents:qna_contents, qna_filename:qna_filename},
			success:function(data){
				console.log(data);
				alert(data.msg);
				location.reload();
			},
			error:function(e){
				console.log(e);
			}
		});
	}else{
		$.ajax({
			url:'/Welcome/modQnA',
			type:"POST",
			dataType:'JSON',
			data:{qna_title:qna_title, qna_contents:qna_contents, qna_a_contents:qna_a_contents, qna_seq:qna_seq},
			success:function(data){
				alert(data.msg);
				location.reload();
			},
			error:function(e){
				console.log(e);
			}
		});
	}
}

function file_submit(){

	var form = $('#upload_file')[0].files[0];
	var formData = new FormData();

	formData.append('upload_file', form);
	formData.append("message", "ajax로 파일 전송하기");


	$.ajax({
		url:'/Welcome/fileUpload',
		type:'POST',
		enctype:'multipart/form-data',
		processData:false,
		contentType:false,
		data:formData,
		async:false,
		cache:false,
		timeout:600000,
		success:function(data){
			if(data){
				alert("업로드 하였습니다.");
				$('#upload_notice_filename').val(data);
			}else{
				alert("업로드에 실패하였습니다.");
			}
		},
		error:function(e){
			console.log(e);
		}
	});
}

function file_submit_qna(){

	var form = $('#upload_file_qna')[0].files[0];
	var formData = new FormData();

	formData.append('upload_file', form);
	formData.append("message", "ajax로 파일 전송하기");


	$.ajax({
		url:'/Welcome/fileUploadQna',
		type:'POST',
		enctype:'multipart/form-data',
		processData:false,
		contentType:false,
		data:formData,
		async:false,
		cache:false,
		timeout:600000,
		success:function(data){
			if(data){
				alert("업로드 하였습니다.");
				$('#upload_qna_filename').val(data);
			}else{
				alert("업로드에 실패하였습니다.");
			}
		},
		error:function(e){
			console.log(e);
		}
	});
}
