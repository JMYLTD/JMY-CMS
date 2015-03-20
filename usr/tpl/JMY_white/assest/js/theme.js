(function($) {
	"use strict";
	
	$(document).ready(function() {
	
		/*-----------------------------------------------------------------------------------*/
		/*  Top Search Button
		/*-----------------------------------------------------------------------------------*/ 
		$('.top-search').click(function() {
			$('.topSearchForm').slideToggle('fast');
			$(this).toggleClass("active");
			return false;
		});
		
		$('.main-navigation').find("li").each(function(){
			if(jQuery(this).children("ul").length > 0){
				jQuery(this).append("<span class='indicator'></span>");
			}
		});	
		
		/*-----------------------------------------------------------------------------------*/
		/*  Overlay Effect for Featured Image
		/*-----------------------------------------------------------------------------------*/ 	
			$(".overlay-img").hover(function () {
				$(this).stop().animate({
					opacity: .5
				}, 300);
			},
			function () {
				$(this).stop().animate({
					opacity: 0
				}, 300);
			});

	});
	
})(jQuery);