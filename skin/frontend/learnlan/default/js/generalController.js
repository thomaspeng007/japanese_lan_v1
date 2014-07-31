$('document').ready(function(){
	// add jiye 2014.05.15
	$('#entory_changepassword').click(function() {
		if ($('#entory_changepassword').is(':checked')) {
			$('#entory_changepassword_area').css("display", "inline-block");
		}else{
			$('#entory_changepassword_area').css("display", "none");
		}
	});
	// add jiye 2014.04.20
    $(".btn_jump_dia").click(function(){
    	$("#tabs_dia").trigger("click");
	});
    $(".btn_jump_voc").click(function(){
    	$("#tabs_voc").trigger("click");
	});
    $(".btn_jump_pra").click(function(){
    	$("#tabs_pra").trigger("click");
	});
    $(".btn_jump_gra").click(function(){
    	$("#tabs_gra").trigger("click");
	});
    
	// add jiye 2014.04.19
    var flg = "close";
    $(".hide_btn").click(function(){ 
        $("main .main01").slideToggle();
        //$("#sidebar").slideToggle();
        //$(".main01").slideToggle();
        
        if(flg == "close"){
            $(this).text("▼開く");
            flg = "open";
        }else{
            $(this).text("▲隠す");
            flg = "close";
        }
    });

    // add jiye 2014.04.19
	$('.box').heightLine();
	
	$('.over').hover(
		function(){
			var src = $(this).attr('src');
			var src_o = src.substr(0, src.lastIndexOf('.')) + '_o' +
			src.substring(src.lastIndexOf('.'));
			$(this).attr('src',src_o);
		},
		function(){
			var src = $(this).attr('src');
			var src = src.substr(0, src.lastIndexOf('.')-2) +
			src.substring(src.lastIndexOf('.'));
			$(this).attr('src',src);
		}
	)
	$("#menu li").hover(function(){
		$("ul",this).show();
	},
	function(){
		$("ul",this).hide();
 	});
	
/*$('.toggle').toggles({click:false});
***/
  
});