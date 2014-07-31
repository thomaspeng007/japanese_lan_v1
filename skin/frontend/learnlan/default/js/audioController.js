$('document').ready(function(){
	// add jiye 2014.04.20
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
        $("#sidebar").slideToggle();
        $(".main01").slideToggle();
        
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

// make thomas ----------------------------------------------------------
	// define the namespace object as we only want to have one global variable
	// We need to do a check before we create the namespace
	// here we use a shorter version
    var sinoApp = sinoApp || {};
    
    //need to use prefix strictly to define the scope of js code for each tab
    //dia_ = dialogue, voc_ = vocabulary, gra_ = grammar, exe_ = exercises, exp_ = expansion
    
    // BELOW CODE FOR DIALOGUE TAB 
    // playlist
    sinoApp.dia_current = 0;
    sinoApp.dia_init = function() {
       audio1 = $('#dia_audio_1');  
       playlist = $('#dia_playlist');
       tracks = playlist.find('li a	');
       len = tracks.length;
       player = audio1[0];  
       player.volume = .50;

       // control playlist play/pause
       playlist.find('#dia_play').click(function (e) {   
          if(player.paused) {
             player.play();
    	     sinoApp.dia_playController("Pause");
    	  }else {
    	     player.pause();
    	     sinoApp.dia_playController("Play");
    	  }
       });

       // control playlist stop
       playlist.find('#dia_stop').click(function (e) {
    	   sinoApp.dia_current = 0;
    	   link = playlist.find('a')[0];
    	   sinoApp.dia_run($(link),player,0);
    	   sinoApp.dia_playController("Play");
       });
       
       player.addEventListener('ended', function (e) {    
          if(sinoApp.dia_current>1000){ 
             sinoApp.dia_current = 0; 
             sinoApp.dia_run($(link),player,0);
          }   
          if(sinoApp.dia_current==1000){ 
             link = playlist.find('a')[0];
             sinoApp.dia_run($(link),player,0);
             sinoApp.dia_playController("Play");          
          }   
    	  sinoApp.dia_current++;
    	  if(sinoApp.dia_current == len){
    		 sinoApp.dia_current = 0;
    	     link = playlist.find('a')[0];
    	     sinoApp.dia_playController("Play");	     
    	  }
 	      else{
    	     link = playlist.find('a')[sinoApp.dia_current];    
    	  }   
    	  //alert(sinoApp.dia_current);   
    	  if(sinoApp.dia_current!=1001){ 	  
    	     sinoApp.dia_run($(link),player,sinoApp.dia_current);    
    	  }    
       });
    };

    sinoApp.dia_run = function(link, player, play) {
       player.src = link.attr('href');
       par = link.parent();
       par.addClass('active').siblings().removeClass('active');  
       player.load();
       if(play!=0){
          player.play();
       }
    };

    // control playlist play/pause based on event
    sinoApp.dia_playController = function (status) {
       if(status=="Play"){
          playlist.find('#dia_play').text("再生 >");
       }else{
    	  playlist.find('#dia_play').text("一時停止 ||");
       }
    }

    // play single sentence
    sinoApp.dia_runSentence = function() {
    	audio1 = $('#dia_audio_1');     
        playlist = $('#dia_playlist');  
        player1 = audio1[0];     
        playlist.find('a').click(function (e) {
           e.preventDefault();   
           sinoApp.dia_playController("Pause");
           sinoApp.dia_current = 1000;
           link1 = playlist.find('a')[0];   
           sinoApp.dia_run($(link1),player1,0);
           link = $(this);    
           sinoApp.dia_run($(link),player1,1);          
        });
    };

    
    // display pinyin on dialogue
    sinoApp.dia_pinyin = function() {
       
       $('#dia_pinyin').click(function (e) {
    	  var $el = $(this);
    	  $el.text($el.text() == "ピンインON" ? "ピンインOFF": "ピンインON");
          if ( $('.dia_pinyin').css('visibility') == 'hidden' )
    $('.dia_pinyin').css('visibility','visible');
  else
    $('.dia_pinyin').css('visibility','hidden');
           
       });
       
    };
   
    // display translation on dialogue
    sinoApp.dia_trans = function() {
       $('#dia_trans').click(function (e) {
      	  var $el = $(this);
      	  $el.text($el.text() == "日本語訳ON" ? "日本語訳OFF": "日本語訳ON");
           // $('.dia_trans').toggle();
          if ( $('.dia_trans').css('visibility') == 'hidden' )
    $('.dia_trans').css('visibility','visible');
  else
    $('.dia_trans').css('visibility','hidden');  
       });
    };
    
    // Prevent vocabulary audio open a new window
    sinoApp.voc_play = function() {
    	 audio2 = $('#dia_audio_2');     
         playlist2 = $('.voc_play');  
         player2 = audio2[0];    
       $('.voc_play').click(function (e) {
    	   e.preventDefault();   
    	   link2 = $(this);    
    	  
    	   player2.volume = .50; 
    	   player2.src = link2.attr('href');
          
    	   player2.load();
         
    	   player2.play();
      
    	   
       });
    };
    
   

    // initialise dia_playlist
    sinoApp.dia_init();
    // initialise dia_ per sentence
    sinoApp.dia_runSentence();

    sinoApp.dia_pinyin();

    sinoApp.dia_trans();
    
    sinoApp.voc_play(); 

    
});