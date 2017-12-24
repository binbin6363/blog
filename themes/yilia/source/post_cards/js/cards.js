
function init_select(result){
	for (var i = 0; i < result.length; ++i) {
		if (result[i].FCardNum>0 && result[i].FStatus==1) {
			$("#card_id").append("<option value='"+result[i].FCardId+"'>"+result[i].FCardName+"</option>");  //为Select追加一个Option(下拉项)
		}
	}
}

$(function(){

	$.ajax({
		type: 'POST',
		url: 'op_card_info.php',
		data: {"request_type":"query_card_info"},
		success: function(html) {
			result = JSON.parse(html);
			if(result.success){
				init_select(result.res);
			}
		},
		error: function(){
			
		}
	});
	
});