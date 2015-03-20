(function($){
        "use strict";

            /* === SETTING === */
            var themeApp = {
                theme_nav:function(){
                    $("#theme-navigation").tinyNav();
                },
                theme_widget:function(){
                    $('.theme-sidebar .sidebar-title .title-collapse a').click(function(e){
                        e.preventDefault();
                        $(this).parents('.theme-sidebar').children('.sidebar-row').slideToggle('medium');
                    });
                },
                theme_grid:function(){
                    $('.photoset-grid').photosetGrid({
                        highresLinks: true,
                        rel: $('.photoset-grid').attr('data-id'),
                        gutter: '10px',
                        onComplete: function(){
                            $('.photoset-grid').attr('style', '');
                            $('.photoset-grid a').colorbox({
                                photo: true,
                                scalePhotos: true,
                                maxHeight:'100%',
                                maxWidth:'100%'
                            });
                        }
                    });
                },
                theme_featured_project:function(){
                    $('.button-zoom').colorbox({
                        photo: true,
                        scalePhotos: true,
                        maxHeight:'100%',
                        maxWidth:'100%'
                    });  
                },
                theme_animated:function(){
                    $(".featured-project-item ul li").hover(function(){
                        $(".featured-project-caption").addClass("animated pulse");
                    }, function() { 
                        $(".featured-project-caption").removeClass("animated pulse"); 
                    });  
                },
                theme_wow_init:function(){
                    new WOW().init();  
                },
                theme_share_button:function(){
                    $('.theme-posts-info .left ul li a.share-button').click(function(e){
                        e.preventDefault();
                        $(this).parents('li').children('.share-menu').slideToggle('medium');
                    });
                },
                theme_fitvids:function(){
                    $(".theme-posts-video").fitVids();
                },
                
                
                theme_flickr:function(){
                    $('#flickr_widget').jflickrfeed({
                        limit: 5,
                        qstrings: {
                            id: '52617155@N08'
                        },
                        itemTemplate: '<li><img src="{{image_b}}" alt="{Moveone}" /></li>'
                    });
                },
                
                
                
                theme_dribbble:function(){
                    $.getJSON('http://api.dribbble.com/players/envato/shots?per_page=5&callback=?',
                    function(response){
                        var dribbble_var = String(' ');
                            dribbble_var += '<ul class="slides">';

                            for(var i=0; i<response.shots.length; i++){
                                dribbble_var += '<li>';
                                dribbble_var += '<img src="'+response.shots[i].image_url+'" alt="'+response.shots[i].title+'" />';
                                dribbble_var += '</li>';
                            }

                            dribbble_var += '</ul>';
                            dribbble_var += '<div class="clearfix"></div>';

                            $('.dribbble-content').html(dribbble_var);
                    });
                },
                
                
                theme_init:function(){
                    themeApp.theme_nav();
                    themeApp.theme_widget();
                    themeApp.theme_grid();
                    themeApp.theme_featured_project();
                    themeApp.theme_wow_init();
                    themeApp.theme_animated();
                    themeApp.theme_share_button();
                    themeApp.theme_fitvids();
                    themeApp.theme_flickr();
                    themeApp.theme_dribbble();
                }
            }

            /* === INITIALING === */
            $(document).ready(function(){
                themeApp.theme_init();
            });

        }(jQuery));

        // FLEXSLIDER IMAGE SCROLLER
        $(window).load(function(){
            
            $('#theme-slider .flexslider').flexslider({
                animation: "fade",
                directionNav: false,
                controlNav: true,
                slideshow: false,
                useCSS: false,
                keyboard: true,
                slideshowSpeed: 5000
            });
            

            
            $('.key-features-item .flexslider').flexslider({
                animation: "fade",
                directionNav: false,
                controlNav: true,
                slideshow: false,
                useCSS: false,
                keyboard: true,
                slideshowSpeed: 5000
            });
            

            
            $('#sidebar-flickr.flexslider').flexslider({
                animation: "fade",
                directionNav: true,
                controlNav: false,
                slideshow: false,
                useCSS: false,
                keyboard: true,
                slideshowSpeed: 5000
            });
            
            
            
            $('#sidebar-dribbble.flexslider').flexslider({
                animation: "fade",
                directionNav: true,
                controlNav: false,
                slideshow: false,
                useCSS: false,
                keyboard: true,
                slideshowSpeed: 5000
            });
            
        });