var control_timeout, footerHeight;

$(document).ready(function(){
	//$("html").niceScroll({ autohidemode: false });
	$('#menu').localScroll({hash:true, onAfterFirst:function(){$('html, body').scrollTo( {top:'-=25px'}, 'fast' );}});
	$('#about-menu').localScroll({hash:true, onAfterFirst:function(){$('html, body').scrollTo( {top:'-=25px'}, 'fast' );}});
	$('.logo').localScroll({hash:true});	
	$('.read-on').localScroll({hash:true});

	// 拉取明信片寄送明细
	$.ajax({
		type: 'POST',
		url: 'save_to_db.php',
		data: $('#query_form').serialize(),
		success: function(html) {
			$("#query_form").html(html);
		},
		error: function(){
			show_error_msg();
		}
	});

	// 拉取剩余明信片信息
	$.ajax({
		type: 'POST',
		url: 'save_to_db.php',
		data: {"request_type":"query_left"},
		success: function(html) {
			result = JSON.parse(html);
			if (result.success) {
				num = result['res'];
				$("#left_card").html("<h3>彬彬总共有明信片" + num['TotalNum'] + "张，剩余" + num['LeftNum'] + "张可寄送" + "</h3>");
				$("#left_card").show();
			}
		},
		error: function(){
			show_error_msg();
		}
	});
	
	is_ipad = navigator.userAgent.toLowerCase().indexOf('ipad') > -1;
	if(is_ipad){
		$('.menu-dropdown').removeClass('hidden');
		$('#menu').addClass('hidden');
	}
	else{
		$('.menu-dropdown').addClass('hidden');
		$('#menu').removeClass('hidden');
	}
	$('#submit').click(function(event){
		event.preventDefault();
		if (valename($('#recv_addr').val()) && $('#recv_name').val()!=''){
			$('html, body').scrollTo( $('#contact'), 'fast' );
			$.ajax({
				type: 'POST',
				url: 'save_to_db.php',
				data: $('#contact_form').serialize(),
				success: function(html) {
					result = JSON.parse(html);
					if(result.success)
					{
						var msg = '<h1>寄送请求发送成功</h1>';
						$('#client_name').html($('#send_name').attr('value'));
						$('#thanks').show();
					} else {
						show_error_msg(result.msg);
					}
				
				},
				error: function(){
					show_error_msg('寄送请求发送失败');
				}
			});
		} else {
			show_error_msg('寄送请求发送失败');
		}
	});

	init_scroll();

	//control_timeout = setTimeout("no_run()",800);

	$('.menu-btn').click(function(){
		$('html, body').animate({ scrollTop: $(document).height() }, 'fast');
	});
	
	//paddFooter();
	
});

$(window).resize(function(){
	init_scroll ();	
	$('#footer').css('padding-bottom','0');
	paddFooter();
});


function paddFooter(){
	if ($(window).width()<=590) {
		topHeight = getDivHeight('top') ;
		footerHeight = getDivHeight('footer');
		$('#footer').css('padding-bottom', ($(window).height() - footerHeight) - topHeight + 'px');
	}
	else{
		$('#footer').css('padding-bottom','0');
	}
}
function init_scroll () {

	if($(window).width()>=768){
		nScroll = 4;
		nVisible = 4;
	}else{
		if(($(window).width()<768) && ($(window).width()>=480)){
			nScroll = 2;
			nVisible = 2;
		}else{
			nScroll = 1;
			nVisible = 1;
		}
	}

	$('#slide-wrapper ul').carouFredSel({
		responsive: true,
		width: '100%',
		height: '71px',
		scroll: nScroll,
		items: {
			width: 192,
			visible: nVisible
		},
		swipe: {
			onMouse: true,
			onTouch: true
		},
		pagination  : "#foo2_pag"

	});

}

function no_run(){
	clearTimeout(control_timeout);
}

function valemail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function valename(name) {
	if (name != '') {
		return true;
	} else {
		return false;
	}
}

function show_error_msg(msg='') {
	$("#show_result").html(msg);
	//$('#ajax-message').css('display','block').html('明信片寄送请求失败，请稍后重试!');	
}
function getDivHeight(objName) {
    return boxHeight = document.getElementById(objName).clientHeight;
}