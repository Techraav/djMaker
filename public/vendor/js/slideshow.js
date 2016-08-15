(function($){     
  	setInterval(function(){ 
    	$(".slideshow ul li:first-child").animate({"margin-left": '-100%'}, 700    , function(){  
        	$(this).css("margin-left", 0).appendTo(".slideshow ul");  
    	});  
  	}, 6000);  
})(jQuery);