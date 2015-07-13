(function($) {
	"use strict";
	
	$(window).load(function() {
		/*-----------------------------------------------------------------------------------*/
		/*  Fade effect preloader
		/*-----------------------------------------------------------------------------------*/ 
			if ( $( '.preloader' ).length ) {
				$('.preloader').fadeOut(800);
			}
	});
	
	$(document).ready(function() {
		
		/*-----------------------------------------------------------------------------------*/
		/*  Home icon in main menu
		/*-----------------------------------------------------------------------------------*/ 
			$('.main-navigation .menu-item-home > a').prepend('<i class="fa fa-home spaceRight"></i>');
	
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
			
		/*-----------------------------------------------------------------------------------*/
		/*  Related Posts
		/*-----------------------------------------------------------------------------------*/ 
			$("#owl-related").owlCarousel({
				items : 2,
				navigation : true,
				navigationText: ["<i class='fa fa-lg fa-angle-left'></i>","<i class='fa fa-lg fa-angle-right'></i>"],
				autoPlay: true,
				stopOnHover: true,
				pagination: false
			});
			
		/*-----------------------------------------------------------------------------------*/
		/*  Toggle Shortcode
		/*-----------------------------------------------------------------------------------*/ 
			$(".toggle_container").hide();
			$("h5.trigger").click(function(){
				$(this).toggleClass("active").next().slideToggle("fast");
				return false;
			});

		/*-----------------------------------------------------------------------------------*/
		/*  Tabs Shortcode 
		/*-----------------------------------------------------------------------------------*/ 
			$('.tab-semplicementepro-tabs li').click(function(){
				if($(this).find(".tab-semplicementepro-link").attr("class") != "tab-semplicementepro-link"){
					switch_tabs($(this));
				}
			});
				
			function switch_tabs(obj) {
				obj.parent().parent().find('.tab-semplicementepro-tab-content').hide();
				obj.parent().find('li').removeClass("active");
				var id = obj.find("a", 0).attr("rel");
				$('#'+id).fadeIn();
				obj.addClass("active");
			}
			
		/*-----------------------------------------------------------------------------------*/
		/*  Combo Widget
		/*-----------------------------------------------------------------------------------*/ 
		if ($(".tabsTitle")){
			$(".tab_content").hide();
			$(".tab_content:first").show(); 

			$("ul.tabs li").click(function() {
				$("ul.tabs li").removeClass("active");
				$(this).addClass("active");
				$(".tab_content").hide();
				var activeTab = $(this).attr("rel"); 
				$("#"+activeTab).fadeIn(); 
			});
		}	
		
		/*-----------------------------------------------------------------------------------*/
		/*  Instagram Widget
		/*-----------------------------------------------------------------------------------*/ 
			$("#instagram-pics-big").owlCarousel({
				singleItem: true,
				navigation : true,
				navigationText: ["<i class='fa fa-lg fa-angle-left'></i>","<i class='fa fa-lg fa-angle-right'></i>"],
				autoPlay: true,
				stopOnHover: true,
				pagination: false
			});
			
		/*-----------------------------------------------------------------------------------*/
		/*  Detect Mobile Browser
		/*-----------------------------------------------------------------------------------*/ 
		if ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		} else {
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
				
			/*-----------------------------------------------------------------------------------*/
			/*  Tooltip
			/*-----------------------------------------------------------------------------------*/ 
				$('.post-navigation .meta-nav, .tagcloud a, .title-author a, .widget-title a, .newsPic a, .single-instagram-pic a, .socialWidget a, .thePostFormat a').powerTip({
					placement: 'n',
					fadeInTime: 0
				});
				
				$('.socialLine a, .theShare a').powerTip({
					placement: 's',
					fadeInTime: 0
				});
				
				$('.single-instagram-pic-big a').powerTip({
					placement: 'se-alt',
					fadeInTime: 0
				});
		}

	});
	
})(jQuery);