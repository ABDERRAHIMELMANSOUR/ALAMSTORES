(function($){
	"use strict";
	

	$(window).on('elementor/frontend/init', function () {

		
		/* Menu Shrink */
		elementorFrontend.hooks.addAction('frontend/element_ready/ova_menu.default', function(){

			$( '.ova_menu_clasic .ova_openNav' ).on( 'click', function(){
				$( this ).closest('.ova_wrap_nav').find( '.ova_nav' ).removeClass( 'hide' );
				$( this ).closest('.ova_wrap_nav').find( '.ova_nav' ).addClass( 'show' );
				$( '.ova_menu_clasic  .ova_closeCanvas' ).css( 'width', '100%' );

				
				$( 'body' ).css( 'background-color', 'rgba(0,0,0,0.4)' );
				
			});

			$( '.ova_menu_clasic  .ova_closeNav' ).on( 'click', function(){
				$( this ).closest('.ova_wrap_nav').find( '.ova_nav' ).removeClass( 'show' );
				$( this ).closest('.ova_wrap_nav').find( '.ova_nav' ).addClass( 'hide' );
				$( '.ova_closeCanvas' ).css( 'width', '0' );


				
				$( 'body' ).css( 'background-color', 'transparent' );
				
			});

			// Display in mobile
			$( '.ova_menu_clasic li.menu-item button.dropdown-toggle').off('click').on( 'click', function() {
				$(this).parent().toggleClass('active_sub');
			});

			if( $('.ovamenu_shrink').length > 0 && $( 'body' ).data('elementor-device-mode') == 'desktop' ){
				
				if( !$('.show_mask_header').hasClass( 'mask_header_shrink' ) ){
					$( '<div class="show_mask_header mask_header_shrink" style="position: relative; height: 0;"></div>' ).insertAfter('.ovamenu_shrink');
					

				}

				
				var header = $('.ovamenu_shrink');
				var header_shrink_height = header.outerHeight(true);
				

				$(window).scroll(function () {

					var scroll = $(this).scrollTop();

					if (scroll >= header_shrink_height+150 ) {
						header.addClass( 'active_fixed' );
						$('.mask_header_shrink').css('height',header_shrink_height);
					} else {
						header.removeClass('active_fixed');
						$('.mask_header_shrink').css('height','0');
					}
				});
			}

			if( $('.ovamenu_shrink_mobile').length > 0 && $( 'body' ).data('elementor-device-mode') != 'desktop' ){
				
				if( !$('.show_mask_header_mobile').hasClass( 'mask_header_shrink_mobile' ) ){
					$( '<div class="show_mask_header_mobile mask_header_shrink_mobile" style="position: relative; height: 0;"></div>' ).insertAfter('.ovamenu_shrink_mobile');
					
				}
				
				var header = $('.ovamenu_shrink_mobile');
				var header_shrink_height = header.outerHeight(true);
				

				$(window).scroll(function () {

					var scroll = $(this).scrollTop();

					if (scroll >= header_shrink_height+150 ) {
						header.addClass( 'active_fixed' );
						$('.mask_header_shrink_mobile').css('height',header_shrink_height);
					} else {
						header.removeClass('active_fixed');
						$('.mask_header_shrink_mobile').css('height','0');
					}
				});
			}

		});

		/* Search Popup */
		elementorFrontend.hooks.addAction('frontend/element_ready/ova_search_popup.default', function(){
			$( '.wrap_search_constrau_popup i' ).on( 'click', function(){
				$( this ).closest( '.wrap_search_constrau_popup' ).addClass( 'show' );
			});

			$( '.btn_close' ).on( 'click', function(){
				$( this ).closest( '.wrap_search_constrau_popup' ).removeClass( 'show' );

			});
		});
		/* end Search Popup */


		/* Slide Testimonial */
		elementorFrontend.hooks.addAction('frontend/element_ready/ova_blog_slider.default', function(){

			var responsive_value = {
				0: {
					items: 1,
				},
				768:  {
					items: 2,
				},
				991:  {
					items: 3,
				},

			};
			var navText = [
			'<i class="fa fa-angle-left"></i>',
			'<i class="fa fa-angle-right"></i>'
			];
			if ($('.ova-blog-slider').hasClass('version_2')) {
				navText = [
				'<i class="arrow_flaticon-050-left-arrow-7"></i>',
				'<i class="arrow_flaticon-034-right-arrow-7"></i>'
				];
				responsive_value = {
					0:{
						items:1,
					},

					767:{
						items:2
					}
				};
			}

			$(".blog-slider").each(function(){
				var owlsl = $(this) ;
				var owlsl_df = {
					margin: 0, 
					responsive: false, 
					smartSpeed:500,
					autoplay:false,
					autoplayTimeout: 6000,
					items:3,
					loop:true, 
					nav: true, 
					dots: true,
					center:false,
					autoWidth:false,
					thumbs:false, 
					autoplayHoverPause: true,
					slideBy: 1,
				};
				var owlsl_ops = owlsl.data('options') ? owlsl.data('options') : {};
				owlsl_ops = $.extend({}, owlsl_df, owlsl_ops);
				owlsl.owlCarousel({
					autoWidth: owlsl_ops.autoWidth,
					margin: owlsl_ops.margin,
					items: owlsl_ops.items,
					loop: owlsl_ops.loop,
					autoplay: owlsl_ops.autoplay,
					autoplayTimeout: owlsl_ops.autoplayTimeout,
					center: owlsl_ops.center,
					nav: owlsl_ops.nav,
					dots: owlsl_ops.dots,
					thumbs: owlsl_ops.thumbs,
					autoplayHoverPause: owlsl_ops.autoplayHoverPause,
					slideBy: owlsl_ops.slideBy,
					smartSpeed: owlsl_ops.smartSpeed,
					navText: navText,
					responsive: responsive_value,
				});

			});

		});
		// end Slide testimonial

		/* Skill bar */
		elementorFrontend.hooks.addAction('frontend/element_ready/ova_skill_bar.default', function(){
			

				$('.skillbar').appear();

				$(document.body).on( 'appear', '.skillbar', function(){

				
					jQuery(this).find('.skillbar-bar').animate({
						width:jQuery(this).attr('data-percent'),
					},3000);

					jQuery(this).find('.percent').animate({
						left: jQuery(this).attr('data-percent') 
					},{
						duration: 3000,
						step: function( now, fx ){
							var data = Math.round(now);
							$(this).find('.relative span').html(data + '%');
						}

					});
					

				});
			


			
		});
		/* end skill bar */


		elementorFrontend.hooks.addAction('frontend/element_ready/ova_testimonial.default', function(){

			$(".slide-testimonials").each(function(){
				var owlsl = $(this) ;
				var padding_stage = 0;
				var owlsl_ops = owlsl.data('options') ? owlsl.data('options') : {};
				var responsive_value = {
					0:{
						items: 1,
					},
					767: {
						items: 2,
					},
					991:{
						items:3
					}
				};
				if ($('.slide-testimonials').hasClass('version_1')) {
					padding_stage = 0;
					responsive_value = {
						0:{
							items:1,
						},
						767:{
							items:2
						}
					};
				} 

				if ($('.slide-testimonials').hasClass('version_2')) {
					responsive_value = {
						0:{
							items: 1,
						},
						767: {
							items: 2,
						},
						991:{
							items:3
						}
					};
				} 
				if ($('.slide-testimonials').hasClass('version_4')) {
					responsive_value = {
						0:{
							items: 1,
						},
						991: {
							items: 2,
						},
						1024: {
							items: 2,
						}
					};
				} 
				owlsl.owlCarousel({
					autoWidth: owlsl_ops.autoWidth,
					margin: owlsl_ops.margin,
					loop: owlsl_ops.loop,
					autoplay: owlsl_ops.autoplay,
					autoplayTimeout: owlsl_ops.autoplayTimeout,
					center: owlsl_ops.center,
					nav: owlsl_ops.nav,
					dots: owlsl_ops.dots,
					thumbs: owlsl_ops.thumbs,
					autoplayHoverPause: owlsl_ops.autoplayHoverPause,
					slideBy: owlsl_ops.slideBy,
					smartSpeed: owlsl_ops.smartSpeed,
					stagePadding: padding_stage,
					navText:[
					'<i class="fa fa-angle-left" ></i>',
					'<i class="fa fa-angle-right" ></i>'
					],
					responsive: responsive_value,
				});

			});

		});

		/* About Team */

		elementorFrontend.hooks.addAction('frontend/element_ready/ova_about_team.default', function(){

			if( $('.about-team').length > 0 ){  
				$('.about-team .owl-carousel').each(function(){

					var owlsl_ops = $(this).data('options');
					var rtl            = false;
					
					if( $('body').hasClass('rtl') ){
						rtl = true;
					}
					
					$(this).owlCarousel({
						margin: owlsl_ops.margin,
						smartSpeed: owlsl_ops.smartSpeed,
						loop: owlsl_ops.loop, 				        
						autoplay: owlsl_ops.autoplay,
						autoplayTimeout: owlsl_ops.autoplayTimeout,
						autoplayHoverPause: owlsl_ops.autoplayHoverPause,
						dots: owlsl_ops.dots,  
						nav: owlsl_ops.nav,
						slideBy: owlsl_ops.slideBy,
						navText: [owlsl_ops.prev,owlsl_ops.next],
						rtl: rtl,						
						responsive:{
							0:{
								items: owlsl_ops.items_mobile
							},
							768:{
								items: owlsl_ops.items_ipad
							},
							1170:{
								items: owlsl_ops.total_columns_slide,
							}
						}
					});
					
				});

				// $(".view_volunteer").click(function () {
				// 	$(this).parent(".popup_volunteer").children(".content_popup").addClass("show");
				// });
				// $(".btn_close").click(function () {
				// 	$(".content_popup").removeClass("show");
				// });


			}

		});

		/* Video Popup */

		elementorFrontend.hooks.addAction('frontend/element_ready/ova_video_popup.default', function(){

			$(document).ready( function(){
				$(".video").videoPopup({
					autoplay: 1,
					controlsColor: 'white',
					showVideoInformations: 0,
					width: 1000,
					customOptions: {
						rel: 0,
						end: 60
					}
				});
			});

		});

		/* End Video Popup*/

		elementorFrontend.hooks.addAction('frontend/element_ready/ova_position.default', function(){

			$(".position-slider").each(function(){
				var owlsl = $(this) ;
				var padding_stage = 0;
				var owlsl_ops = owlsl.data('options') ? owlsl.data('options') : {};
				
				owlsl.owlCarousel({
					autoWidth: owlsl_ops.autoWidth,
					margin: owlsl_ops.margin,
					loop: owlsl_ops.loop,
					autoplay: owlsl_ops.autoplay,
					autoplayTimeout: owlsl_ops.autoplayTimeout,
					center: owlsl_ops.center,
					dots: owlsl_ops.dots,  
					nav: owlsl_ops.nav,
					thumbs: owlsl_ops.thumbs,
					autoplayHoverPause: owlsl_ops.autoplayHoverPause,
					slideBy: owlsl_ops.slideBy,
					smartSpeed: owlsl_ops.smartSpeed,
					stagePadding: padding_stage,
					navText:[
					'<i class="fa fa-angle-left" ></i>',
					'<i class="fa fa-angle-right" ></i>'
					],
					responsive: {
						0:{
							items: 1,
							nav: false,
						},
						991: {
							items: 2,
							nav: false,
						},
						1024: {
							items: 3,
						}
					},
				});
			});
		});

		/* Video Popup */

		elementorFrontend.hooks.addAction('frontend/element_ready/ova_teamwork.default', function(){
			$(document).ready( function(){
				$(".image-team").videoPopup({
					autoplay: 1,
					controlsColor: 'white',
					showVideoInformations: 0,
					width: 1000,
					customOptions: {
						rel: 0,
						end: 60
					}
				});
			});

		});

		/* End Video Popup*/


		/* Video Popup */

		elementorFrontend.hooks.addAction('frontend/element_ready/ova_process.default', function(){
			$('.ova-process .category ul li:first-child a').addClass('active');
			var data_class_first = $('.ova-process .category ul li:first-child a').attr('data-class');
			$('.ova-process .content .item.' + data_class_first).css('display','flex');
			$('.ova-process .category ul li a').off('click').on( 'click', function() {
				$('.ova-process .category ul li a').removeClass('active');
				$(this).addClass('active');
				var data_class_active = $(this).attr('data-class');
				$('.ova-process .content .item').hide().removeClass('active');
				$('.ova-process .content .item.' + data_class_active).fadeIn(800).addClass('active').css('display','flex');
				$('.ova-process .content .item.active:first i').css('display','none');
				$('.ova-process .content .item.active:last').css('margin-right','0');
				$('.ova-process .content .item.active:first .number').css('border-bottom','1px solid #999999');
			});
		});

		/* End Video Popup*/

		/* Services Slide */
		elementorFrontend.hooks.addAction('frontend/element_ready/ova_services_slide.default', function(){
			$('.wrap_items').each(function(){
				var owl = $(this);
				var data = owl.data("owl_carousel");

				owl.owlCarousel(data);
			});

		});
		/* End Services Slide */

		/* ova our skill */
		elementorFrontend.hooks.addAction('frontend/element_ready/ova_our_skill.default', function(){


			$( ".circle" ).each(function( index ) {
				var circle = $(this);
				var size_value = circle.attr('data-size');
				var color_value = circle.attr('data-color');
				var color_empty = circle.attr('data-empty');
				$(circle).circleProgress({
					value: 0,
					size: size_value,
					emptyFill: color_empty,
				});

			});

			var circles = $('.circle');

			circles.appear({ force_process: true });

			circles.on('appear', function() {
				var circle = $(this);
				var number_value = parseInt(circle.attr('data-number'));
				console.log(number_value);
				var size_value = circle.attr('data-size');
				var color_value = circle.attr('data-color');
				var color_empty = circle.attr('data-empty');
				var time = parseInt(circle.attr('data-time'));
				if (!circle.data('inited')) {
					circle.circleProgress({
						value: (number_value) / 100,
						size: size_value,
						fill: {color:color_value},
						emptyFill: color_empty,
						animation: { duration: time},
					}).on('circle-animation-progress', function(event, progress, stepValue) {
						$(this).find('strong').html(Math.round(100 * stepValue) + '%');
					});
					circle.data('inited', true);
				}
			});

		});
		/* ova our skill */


		/* Ova Slideshow */
		elementorFrontend.hooks.addAction('frontend/element_ready/ova_slideshow.default', function(){
			var prevArrow = $('.ova_slideshow .prev-arrow');
			var nextArrow = $('.ova_slideshow .next-arrow');
			if ($('.ova_slideshow .slide_gallery .wrap_slide').slick) {
				$('.ova_slideshow .slide_gallery .wrap_slide').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false,
					arrows: true,
					fade: true,
					asNavFor: '.ova_slideshow .thumbnail_gallery',
					prevArrow: prevArrow,
					nextArrow: nextArrow,
				});

				$('.ova_slideshow .thumbnail_gallery').slick({
					slidesToShow: 3,
					slidesToScroll: 3,
					asNavFor: '.ova_slideshow .slide_gallery .wrap_slide',
					dots: false,
					arrows: false,
					centerPadding: '0px',
					focusOnSelect: true,
					centerMode: true,
					swipeToSlide: true,
					responsive: [
					{
						breakpoint: 768,
						settings: {
							arrows: false,
							slidesToShow: 3
						}
					},
					]
				});
			}
		});
		/* End Ova Slideshow */

		elementorFrontend.hooks.addAction('frontend/element_ready/ova_slide_item_landing.default', function(){

			$(".slide-item-landing").each(function(){
				var owlsl = $(this) ;
				var padding_stage = 15;
				var owlsl_ops = owlsl.data('options') ? owlsl.data('options') : {};
				
				owlsl.owlCarousel({
					autoWidth: owlsl_ops.autoWidth,
					margin: owlsl_ops.margin,
					loop: owlsl_ops.loop,
					autoplay: owlsl_ops.autoplay,
					autoplayTimeout: owlsl_ops.autoplayTimeout,
					center: owlsl_ops.center,
					dots: owlsl_ops.dots,  
					nav: owlsl_ops.nav,
					thumbs: owlsl_ops.thumbs,
					autoplayHoverPause: owlsl_ops.autoplayHoverPause,
					slideBy: owlsl_ops.slideBy,
					smartSpeed: owlsl_ops.smartSpeed,
					stagePadding: padding_stage,
					navText:[
					'<i class="fa fa-angle-left" ></i>',
					'<i class="fa fa-angle-right" ></i>'
					],
					responsive: {
						0:{
							items: 1,
						},
						767: {
							items: 2,
						},

						991: {
							items: 3,
						},

						1024: {
							items: 4,
						}
					},
				});
			});
		});

	});

})(jQuery);
