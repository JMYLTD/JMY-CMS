(function($) {
	"use strict";
	
	$(document).ready(function() {
	
		/*-----------------------------------------------------------------------------------*/
		/*  Home icon in main menu
		/*-----------------------------------------------------------------------------------*/ 
			$('.main-navigation .menu-item-home a').prepend('<i class="fa fa-lg fa-home spaceRight"></i>');
			
		/*-----------------------------------------------------------------------------------*/
		/*  If the Tagcloud widget exist or Edit Comments Link exist
		/*-----------------------------------------------------------------------------------*/ 
			if ( $( '.comment-metadata' ).length ) {
				$('.comment-metadata').addClass('smallPart');
			}
			if ( $( '.comment-list .edit-link' ).length ) {
				$('.comment-list .edit-link').addClass('smallPart');
			}
			
		/*-----------------------------------------------------------------------------------*/
		/*  Make dropdowns functional on focus
		/*-----------------------------------------------------------------------------------*/ 
		$( '.main-navigation' ).find( 'a' ).on( 'focus blur', function() {
			$( this ).parents().toggleClass( 'focus' );
		} );
			
		/*-----------------------------------------------------------------------------------*/
		/*  Top Search Button
		/*-----------------------------------------------------------------------------------*/ 
		$('.top-search').click(function() {
			$('.topSearchForm').slideToggle('fast');
			$(this).toggleClass("active");
			return false;
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
			
		/*-----------------------------------------------------------------------------------*/
		/*  Detect Mobile Browser
		/*-----------------------------------------------------------------------------------*/ 
		if ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		} else {
		
			/*-----------------------------------------------------------------------------------*/
			/*  If menu has submenu
			/*-----------------------------------------------------------------------------------*/ 
				$('.main-navigation').find("li").each(function(){
					if($(this).children("ul").length > 0){
						$(this).append("<span class='indicator'></span>");
					}
				});
			
			/*-----------------------------------------------------------------------------------*/
			/*  Scroll To Top
			/*-----------------------------------------------------------------------------------*/ 
				$(window).scroll(function(){
					if ($(this).scrollTop() > 700) {
						$('#toTop').fadeIn();
					} 
					else {
						$('#toTop').fadeOut();
					}
				}); 
				$('#toTop').click(function(){
					$("html, body").animate({ scrollTop: 0 }, 1000);
					return false;
				});

		}
			
	});
	
})(jQuery);