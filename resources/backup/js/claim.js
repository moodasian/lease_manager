(function(window, $){
	var pageNum = 1;
	var pagination;
	var DATA_URL = '/Building/getBuildingList';

	function getParams(){
		var data = {'cate': $('.cate').val(), 'date': $('.selectdate').val()};
		return data;
	}
	init();

	function init(){
		loadListData();
	}

	//리스트 호출
	function loadListData(){
		$.ajax({
			url:'/Building/getBuildingList',
			type:"POST",
			dataType:'JSON',
			success:function(data){
				renderTable(data);
			},
			error:function(e){
				console.log(e);
			}
		});
	}

	//리스트 테이블 렌더링
	function renderTable(data){
		var html = '';
		for(var i=0; i<data.length; i++){
			var dataItem = data[i];
			var building_name = dataItem.building_name;
			var owner_name = dataItem.building_owner_name;
			var regdate = dataItem.regdate;
			var building_seq = dataItem.building_seq;

			html += '<ul class="ls-detail-body__tb">';
			html += '<li>'+building_name+'</li>';
			html += '<li>'+owner_name+'</li>';
			html += '<li>'+'수정'+'</li>';
			html += '<li>'+'삭제'+'</li>';
			html += '<li>'+regdate+'</li>';
			html += '</ul>';
		}

		$('#test').html(html);
	}

	function registBuilding(){

	}


})(window, jQuery);
