// JavaScript Document

(function($){

$(window).load(function() {    
	$("#status").fadeOut(); // will first fade out the loading animation
	$("#preloader").delay(400).fadeOut("slow"); // will fade out the white DIV that covers the website.
});

$(document).ready(function(){
	
    $('.deploy-submenu').click(function(){
        $(this).parent().find('.submenu').slideToggle(250);
        $(this).toggleClass('active-submenu');
        return false;
    });
        
    $('.show-navigation').click(function(){
       $('.navigation-wrapper').toggleClass('active-navigation');
        $('.navigation-background').toggleClass('active-background');
    });    
    
    $('.hide-navigation, .navigation-hide, .navigation-background').click(function(){
       $('.navigation-wrapper').toggleClass('active-navigation');
        $('.navigation-background').toggleClass('active-background');
    });
    
    //Countdown Timer
	var endDate = "June 7, 2015 15:03:25";
	
	$('.countdown').countdown({
	  date: endDate,
	  render: function(data) {
		$(this.el).html(
		"<div class='countdown-box box-years'><div class='countdown-years'>" + this.leadingZeros(data.years, 2) + 
		"</div><span>years</span></div><div class='countdown-box box-days'><div class='countdown-days'>" + this.leadingZeros(data.days, 2) + 
		"</div><span>days</span></div><div class='countdown-box box-hours'><div class='countdown-hours'>" + this.leadingZeros(data.hours, 2) + 
		"</div><span>hours</span></div><div class='countdown-box box-minutes'><div class='countdown-minutes'>" + this.leadingZeros(data.min, 2) + 
		"</div><span>min</span></div><div class='countdown-box box-seconds'><div class='countdown-seconds'>" + this.leadingZeros(data.sec, 2) + 
		"</div><span>sec</span></div>");
	  }
	});
	
	//Animate.css scroll to begin animation //	
	
	var wow = new WOW(
	  {
		boxClass:     'animate',      // animated element css class (default is wow)
		animateClass: 'animated', // animation css class (default is animated)
		offset:       0,          // distance to the element when triggering the animation (default is 0)
		mobile:       true,        // trigger animations on mobile devices (true is default)
	  }
	);
	wow.init();
    
    //Coverpage
    
	var coverpage_height = 0;
	
	function initiate_coverpages(){
		coverpage_height =  $(window).height();
		$('.coverpage').css({ height: coverpage_height-70 });	
	};
	
	initiate_coverpages();
	
	$(window).resize(function() {
		initiate_coverpages();
	});
		
	//Go up
	
	$('.footer-up').click(function() {
		$('body,html').animate({
			scrollTop:0
		}, 800, 'easeOutExpo');
		return false;
	});
	
	//Menu 
	
	$('.deploy-navigation').click(function(){
		$('.page-navigation').toggleClass('page-navigation-active');
		$('.close-navigation').show(0);
		$(this).hide(0);
		return false;
	});
		
	$('.close-navigation').click(function(){
		$('.page-navigation').toggleClass('page-navigation-active');
		$('.deploy-navigation').show(0);
		$(this).hide(0);
		return false;
	});
	
	$('.page-navigation a').hover(function(){
		$(this).find('i:first-child').toggleClass('hover-icon');		
	});
	
	$('.submenu-deploy').click(function(){
		$(this).parent().find('.submenu-items').toggleClass('submenu-items-active');
		$(this).find('.fa-angle-down').toggleClass('deploy-navigation-active');
		return false;
	});
    
	//Portfolio//
	$('.adaptive-one-activate').click(function() {
		$('.portfolio-adaptive').removeClass('adaptive-three');
		$('.portfolio-adaptive').removeClass('adaptive-two');
		$('.portfolio-adaptive').addClass('adaptive-one');
		$(this).addClass('active-adaptive-style');
		$('.adaptive-two-activate, .adaptive-three-activate').removeClass('active-adaptive-style');
		return false;
	});
	
	$('.adaptive-two-activate').click(function() {
		$('.portfolio-adaptive').removeClass('adaptive-three');
		$('.portfolio-adaptive').addClass('adaptive-two');
		$('.portfolio-adaptive').removeClass('adaptive-one');	
		$(this).addClass('active-adaptive-style');	
		$('.adaptive-three-activate, .adaptive-one-activate').removeClass('active-adaptive-style');
		return false;
	});
	
	$('.adaptive-three-activate').click(function() {
		$('.portfolio-adaptive').addClass('adaptive-three');
		$('.portfolio-adaptive').removeClass('adaptive-two');
		$('.portfolio-adaptive').removeClass('adaptive-one');
		$(this).addClass('active-adaptive-style');	
		$('.adaptive-two-activate, .adaptive-one-activate').removeClass('active-adaptive-style');
		return false;
	});
    
	//Checkboxes
	
	$('.checkbox-one').click(function(){
		$(this).toggleClass('checkbox-one-checked');
		return false;
	});
	$('.checkbox-two').click(function(){
		$(this).toggleClass('checkbox-two-checked');
		return false;
	});
	$('.checkbox-three').click(function(){
		$(this).toggleClass('checkbox-three-checked');
		return false;
	});	
	$('.radio-one').click(function(){
		$(this).toggleClass('radio-one-checked');
		return false;
	});	
	$('.radio-two').click(function(){
		$(this).toggleClass('radio-two-checked');
		return false;
	});
	
	//Notifications
		
	$('.tap-dismiss-notification').click(function(){
		$(this).fadeOut();
		return false;
	});
	
	$('.close-big-notification').click(function(){
		$(this).parent().fadeOut();
		return false;
	});
	
	//Tabs 
	
	$('.tab-but-1').click(function(){
		$('.tab-but').removeClass('tab-active');
		$('.tab-but-1').addClass('tab-active');
		$('.tab-content').hide(100);
		$('.tab-content-1').show(100);	
		return false;	
	});
	
	$('.tab-but-2').click(function(){
		$('.tab-but').removeClass('tab-active');
		$('.tab-but-2').addClass('tab-active');
		$('.tab-content').hide(100);
		$('.tab-content-2').show(100);
		return false;		
	});	
	
	$('.tab-but-3').click(function(){
		$('.tab-but').removeClass('tab-active');
		$('.tab-but-3').addClass('tab-active');
		$('.tab-content').hide(100);
		$('.tab-content-3').show(100);	
		return false;	
	});	
	
	$('.tab-but-4').click(function(){
		$('.tab-but').removeClass('tab-active');
		$('.tab-but-4').addClass('tab-active');
		$('.tab-content').hide(100);
		$('.tab-content-4').show(100);
		return false;		
	});	

	$('.tab-but-5').click(function(){
		$('.tab-but').removeClass('tab-active');
		$('.tab-but-5').addClass('tab-active');
		$('.tab-content').hide(100);
		$('.tab-content-5').show(100);	
		return false;	
	});	
	
	//Toggles
	
	$('.deploy-toggle-1').click(function(){
		$(this).parent().find('.toggle-content').toggle(100);
		$(this).toggleClass('toggle-1-active');
		return false;
	});
	
	$('.deploy-toggle-2').click(function(){
		$(this).parent().find('.toggle-content').toggle(100);
		$(this).toggleClass('toggle-2-active');
		return false;
	});
	
	$('.deploy-toggle-3').click(function(){
		$(this).parent().find('.toggle-content').toggle(100);
		$(this).find('em strong').toggleClass('toggle-3-active-ball');
		$(this).find('em').toggleClass('toggle-3-active-background');
		return false;
	});
	
	//Submenu Nav
	
	$('.submenu-nav-deploy').click(function(){
		$(this).toggleClass('submenu-nav-deploy-active');
		$(this).parent().find('.submenu-nav-items').toggle(100);
		return false;
	});
	
	//Sliding Door
	
	$('.sliding-door-top').click(function(){
		$(this).animate({
			left:'101%'
		}, 500, 'easeInOutExpo');
		return false;
	});
	
	$('.sliding-door-bottom a em').click(function(){
		$(this).parent().parent().parent().find('.sliding-door-top').animate({
			left:'0px'
		}, 500, 'easeOutBounce');
		return false
		
	});
	
    
    //Switches//
    
    $('.switch-1').click(function(){
       $(this).toggleClass('switch-1-on'); 
        return false;
    });
    
    $('.switch-2').click(function(){
       $(this).toggleClass('switch-2-on'); 
        return false;
    });
    
    $('.switch-3').click(function(){
       $(this).toggleClass('switch-3-on'); 
        return false;
    });
    
    $('.switch, .switch-icon').click(function(){
        $(this).parent().find('.switch-box-content').slideToggle(200); 
        $(this).parent().find('.switch-box-subtitle').slideToggle(200);
    });

	
	/////////////////////////////////////////////////////////////////////////////////////////////
	//Detect user agent for known mobile devices and show hide elements for each specific element
	/////////////////////////////////////////////////////////////////////////////////////////////
	
	var isiPhone = 		navigator.userAgent.toLowerCase().indexOf("iphone");
	var isiPad = 		navigator.userAgent.toLowerCase().indexOf("ipad");
	var isiPod = 		navigator.userAgent.toLowerCase().indexOf("ipod");
	var isiAndroid = 	navigator.userAgent.toLowerCase().indexOf("android");
	
	if(isiPhone > -1) 	 {		 $('.ipod-detected').hide();		 $('.ipad-detected').hide();		 $('.iphone-detected').show();		 $('.android-detected').hide();	 }
	if(isiPad > -1)	 {		 	 $('.ipod-detected').hide();		 $('.ipad-detected').show();		 $('.iphone-detected').hide();		 $('.android-detected').hide();	 }
	if(isiPod > -1)	 {		 	 $('.ipod-detected').show();		 $('.ipad-detected').hide();		 $('.iphone-detected').hide();		 $('.android-detected').hide();	 }   
	if(isiAndroid > -1) {		 $('.ipod-detected').hide();		 $('.ipad-detected').hide();		 $('.iphone-detected').hide();		 $('.android-detected').show();	 }  

	if(isiPhone > -1) 	 {		$('.footer-menu').addClass('ios-footer-menu'); }
	if(isiPad > -1)	 {		 	$('.footer-menu').addClass('ios-footer-menu'); }
	if(isiPod > -1)	 {		 	$('.footer-menu').addClass('ios-footer-menu'); }   
	if(isiAndroid > -1) {		$('.footer-menu').addClass('android-footer-menu'); }  
    
	
	//Detect if iOS WebApp Engaged and permit navigation without deploying Safari
	(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")
	
	var owl = $(".slider-controls");
	owl.owlCarousel({
		//Basic Stuff
		singleItem:true,
		slideSpeed : 250,
		paginationSpeed : 250,
		rewindSpeed : 250,
		pagination:false,
		
		autoPlay : true,
	});
	
	// Custom Navigation Events
	$(".next-slider").click(function(){
	  owl.trigger('owl.next');
	  return false;
	});
	$(".prev-slider").click(function(){
	  owl.trigger('owl.prev');
	  return false;
	});
			
	var owlStaffControls = $(".staff-slider");
	owlStaffControls.owlCarousel({
		//Basic Stuff
		items : 3,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [980,3],
		itemsTablet: [768,2],
		itemsTabletSmall: [480,1],
		itemsMobile : [370,1],
		singleItem : false,
		itemsScaleUp : false,
		slideSpeed : 250,
		paginationSpeed : 250,
		rewindSpeed : 250,
		pagination:false,
		autoPlay : false,
		autoHeight: true,
	});
	
	$(".next-staff").click(function(){
	  owlStaffControls.trigger('owl.next');
	  return false;
	});
	$(".prev-staff").click(function(){
	  owlStaffControls.trigger('owl.prev');
	  return false;
	});
	
	var owlQuoteSlider = $(".quote-slider");
	owlQuoteSlider.owlCarousel({
		items : 2,
		itemsDesktop : [1199,2],
		itemsDesktopSmall : [980,2],
		itemsTablet: [768,2],
		itemsTabletSmall: [480,1],
		itemsMobile : [370,1],
		singleItem : false,
		itemsScaleUp : false,
		slideSpeed : 250,
		paginationSpeed : 250,
		rewindSpeed : 250,
		pagination:false,
		autoPlay : false,
		autoHeight: true,
	});
	
	$(".next-quote").click(function(){
	  owlQuoteSlider.trigger('owl.next');
	  return false;
	});
	$(".prev-quote").click(function(){
	  owlQuoteSlider.trigger('owl.prev');
	  return false;
	});
	
	/////////////////
	//Image Gallery//
	/////////////////
	$(".swipebox").swipebox({
		useCSS : true, // false will force the use of jQuery for animations
		hideBarsDelay : 3000 // 0 to always show caption and action bar
	});
	
	$(".wide-gallery-item").swipebox({
		useCSS : true, // false will force the use of jQuery for animations
		hideBarsDelay : 3000 // 0 to always show caption and action bar
	});
	
  var time = 7; // time in seconds
 
  var $progressBar,
      $bar, 
      $elem, 
      isPause, 
      tick,
      percentTime;
	  
 
    //Init the carousel
    $(".homepage-slider").owlCarousel({
      slideSpeed : 500,
      paginationSpeed : 500,
      singleItem : true,
	  pagination:false,
      afterInit : progressBar,
      afterMove : moved,
      startDragging : pauseOnDragging
    });
 
    //Init progressBar where elem is $("#owl-demo")
    function progressBar(elem){
      $elem = elem;
      //build progress bar elements
      buildProgressBar();
      //start counting
      start();
    }
 
    //create div#progressBar and div#bar then prepend to $("#owl-demo")
    function buildProgressBar(){
      $progressBar = $("<div>",{
        id:"progressBar"
      });
      $bar = $("<div>",{
        id:"bar"
      });
      $progressBar.append($bar).prependTo($elem);
    }
 
    function start() {
      //reset timer
      percentTime = 0;
      isPause = false;
      //run interval every 0.01 second
      tick = setInterval(interval, 10);
    };
 
    function interval() {
      if(isPause === false){
        percentTime += 1 / time;
        $bar.css({
           width: percentTime+"%"
         });
        //if percentTime is equal or greater than 100
        if(percentTime >= 100){
          //slide to next item 
          $elem.trigger('owl.next')
        }
      }
    }
 
    //pause while dragging 
    function pauseOnDragging(){
      isPause = true;
    }
 
    //moved callback
    function moved(){
      //clear interval
      clearTimeout(tick);
      //start again
      start();
    }


	// Custom Navigation Events
	$(".next-home").click(function(){
	  $(".homepage-slider").trigger('owl.next');
	  return false;
	});
	$(".prev-home").click(function(){
	  $(".homepage-slider").trigger('owl.prev');
	  return false;
	});	


});

}(jQuery));