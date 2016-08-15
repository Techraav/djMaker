(function($){

	var n = 1;
	var p = $('#event-list').data('nb');
	var nMax = Math.floor(p/5)*5;	
	if($('.events .line:last-child').attr('id') % 5 != 0){
		$('.events .line:last-child li').css('border-bottom', '1px dashed #414141');
	}

	var pages = p%5 == 0 ? p/5 : Math.floor(p/5)*5;
	var page = 1;

	if(p < 6)
	{
		$('.events .previous').addClass('not-visible');
		$('.events .next').addClass('not-visible');
	}else
	{
		$('.events .previous').click(function(){
			$('.not-visible').removeClass('not-visible');
			if(n>5)
			{
				n-=5;
				page--;
			}
			if(n <= 5 || page == 1){
				$(this).addClass('not-visible');
			}

			$('.events .line').addClass('not-displayed');
			for(var i = n; i<n+5; i++){
				$('#'+i).removeClass('not-displayed');
			}
		});

		$('.events .next').click(function(){
			$('.not-visible').removeClass('not-visible');
			if(n < p-5)
			{
				n+=5;
				page++;
			}
			if(n >= nMax || page == pages){
				$(this).addClass('not-visible');
			}

			$('.events .line').addClass('not-displayed');
			for(var i = n; i<n+5; i++){
				$('#'+i).removeClass('not-displayed');
			}
		});	
	}

		

})(jQuery);