(function($){
	"use strict";
	$(document).ready(function(){

		/* Scroll to top */
		constrau_scrollUp();
		function constrau_scrollUp(options) {
			
			var defaults = {
				scrollName: 'scrollUp', 
				topDistance: 600, 
				topSpeed: 800, 
				animation: 'fade', 
				animationInSpeed: 200, 
				animationOutSpeed: 200, 
				scrollText: '<i class="fas fa-angle-up"></i>', 
				scrollImg: false, 
				activeOverlay: false 
			};

			var o = $.extend({}, defaults, options),
			scrollId = '#' + o.scrollName;


			$('<a/>', {
				id: o.scrollName,
				href: '#top',
				title: o.scrollText
			}).appendTo('body');


			if (!o.scrollImg) {

				$(scrollId).html(o.scrollText);
			}


			$(scrollId).css({'display': 'none', 'position': 'fixed', 'z-index': '2147483647'});


			if (o.activeOverlay) {
				$("body").append("<div id='" + o.scrollName + "-active'></div>");
				$(scrollId + "-active").css({'position': 'absolute', 'top': o.topDistance + 'px', 'width': '100%', 'border-top': '1px dotted ' + o.activeOverlay, 'z-index': '2147483647'});
			}


			$(window).scroll(function () {
				switch (o.animation) {
					case "fade":
					$(($(window).scrollTop() > o.topDistance) ? $(scrollId).fadeIn(o.animationInSpeed) : $(scrollId).fadeOut(o.animationOutSpeed));
					break;
					case "slide":
					$(($(window).scrollTop() > o.topDistance) ? $(scrollId).slideDown(o.animationInSpeed) : $(scrollId).slideUp(o.animationOutSpeed));
					break;
					default:
					$(($(window).scrollTop() > o.topDistance) ? $(scrollId).show(0) : $(scrollId).hide(0));
				}
			});

			
			$(scrollId).on( "click", function (event) {
				$('html, body').animate({scrollTop: 0}, o.topSpeed);
				event.preventDefault();
			});

		}

		/* Fix empty menu in test_uni_data */
		if( $( '.widget_nav_menu ul li' ).length > 0 ){
			$( '.widget_nav_menu ul li a:empty' ).parent().css('display','none');
		}

		/* Select 2 */
		$('select').select2({ 
			width: '100%'
		});


		

		/* Popup Image - PrettyPhoto */
		if( $("a[data-gal^='prettyPhoto']").length > 0 ){
			$("a[data-gal^='prettyPhoto']").prettyPhoto({hook: 'data-gal', theme: 'facebook',slideshow:5000, autoplay_slideshow:true});
		}
		

		$( '.ovatheme_header_default li.menu-item button.dropdown-toggle').off('click').on( 'click', function() {
			$(this).parent().toggleClass('active_sub');
		});

		$(".comment-reply-link").siblings('i').css('display', 'inline-block');

		$(".categories a").siblings('i').css('display', 'inline-block');
		$(".categories a").siblings('.meta-slash').css('display', 'inline-block');


		/* Caousel Thumbnail Woo */
		if( $('.woo-thumbnails').length > 0 ){
			$('.woo-thumbnails .owl-carousel').each(function(){

				var rtl = false;
				if( $('body').hasClass('rtl') ){
					rtl = true;
				}

				$(this).owlCarousel({
					autoplay: true,
					autoplayHoverPause: true,
					loop: false,
					margin: 20,
					dots: false,
					nav: true,
					vertical: true,
					rtl: rtl,
					responsive: {
						0:    {items: 2},
						479:  {items: 2},
						768:  {items: 3},
						1024: {items: 3}
					}
				});
			});
		}

      if( $('.button_popup').length > 0 ){
         $(".button_popup").videoPopup({
            autoplay: 1,
            controlsColor: 'white',
            showVideoInformations: 0,
            width: 1000,
            customOptions: {
               rel: 0,
               end: 60
            }
         });
      };


   });	

})(jQuery);