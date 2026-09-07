(function($){
	"use strict";

   /* Single Slice Text Categories */
   $( function() {
      var text =  $('.single_project .content .categories .info_detail, .single_project_middle .content .categories .info_detail').text().trim();
      var text_replace = text.substring(0, text.length - 1);
      $('.single_project .content .categories .info_detail, .single_project_middle .content .categories .info_detail').text(text_replace);
   });

   /* Fix Social */
   $( function() {
      $('.single_project .icon_share').on('click', function(){
         $(this).parent().find('.share-social-icons').toggle();
      });
   });

   /* Single Slide */
   $( function() {
      var prevArrow = $('.single_project .gallery .prev-arrow');
      var nextArrow = $('.single_project .gallery .next-arrow');
      if ($('.single_project .wrap_slide').slick) {
         $('.single_project .wrap_slide').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: false,
            arrows: true,
            fade: true,
            asNavFor: '.single_project .thumbnail_gallery',
            prevArrow: prevArrow,
            nextArrow: nextArrow,
         });

         $('.single_project .thumbnail_gallery').slick({
            slidesToShow: 5,
            slidesToScroll: 3,
            asNavFor: '.single_project .wrap_slide',
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


   /* Single Middle Slide */
   $( function() {
      var prevArrow = $('.single_project_middle .gallery .prev-arrow');
      var nextArrow = $('.single_project_middle .gallery .next-arrow');
      var prevArrowTablet = $('.single_project_middle .gallery .prev-arrow-tablet');
      var nextArrowTablet = $('.single_project_middle .gallery .next-arrow-tablet');
      if ($('.single_project_middle .wrap_slide').slick) {
         $('.single_project_middle .wrap_slide').slick({
            slidesToShow: 2,
            vertical: true,
            verticalSwiping: true,
            arrows: true,
            prevArrow: prevArrow,
            nextArrow: nextArrow,
            responsive: [
            {
               breakpoint: 768,
               settings: {
                  arrows: true,
                  vertical: false,
                  verticalSwiping: false,
                  slidesToShow: 3,
                  prevArrow: prevArrowTablet,
                  nextArrow: nextArrowTablet,
               }
            },
            {
               breakpoint: 480,
               settings: {
                  arrows: true,
                  vertical: false,
                  verticalSwiping: false,
                  slidesToShow: 1,
                  prevArrow: prevArrowTablet,
                  nextArrow: nextArrowTablet,
               }
            }
            ]
         });
      }
   });


   /* Archive Project */
   $( function() {
      var button = $('.archive_project_default .filter-button-group, .archive_project_compact .filter-button-group, .archive_project_full .filter-button-group');

      button.each(function(i, buttonGroup) {
         var buttonGroup = $(buttonGroup);

         buttonGroup.on('click', 'button', function() {
            buttonGroup.find('.active').removeClass('active');
            $(this).addClass('active');
         });
      });
   });


   /* Ajax Load More Archive Default */
   $( function() {
      var load_more = $('.archive_project_default .load_more');

      load_more.on('click', function(e){
         e.preventDefault();

         var paged = $(this).attr('data-paged');
         var category = $(this).attr('data-category');

         $('.loader').show();
         $('.load_more').hide();
         $('.no_load_more').hide();

         $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: ({
               action: 'load_more_posts_default',
               category: category,
               paged: paged,
            }),
            success: function(response){
               var grid = $('.archive_project_default .grid');
               var paged_new = parseInt(paged) +1;
               $('.load_more').attr('data-paged', paged_new);

               if (response.trim() != '') {
                  var item = $(response).hide().fadeIn(300); 
                  grid.append( item );
                  $('.load_more').show();
                  $('.loader').hide();
               } else {
                  $('.load_more').hide();
                  $('.loader').hide();
                  $('.no_load_more').show();
               }
            }
         });
      });
   });


   /* Ajax Load More Archive Compact */
   $( function() {
      var load_more = $('.archive_project_compact .load_more');

      load_more.on('click', function(e){
         e.preventDefault();

         var paged = $(this).attr('data-paged');
         var category = $(this).attr('data-category');

         $('.loader').show();
         $('.load_more').hide();
         $('.no_load_more').hide();

         $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: ({
               action: 'load_more_posts_compact',
               category: category,
               paged: paged,
            }),
            success: function(response){
               var grid = $('.archive_project_compact .grid');
               var paged_new = parseInt(paged) +1;
               $('.load_more').attr('data-paged', paged_new);

               if (response.trim() != '') {
                  var item = $(response).hide().fadeIn(300); 
                  grid.append( item );
                  $('.load_more').show();
                  $('.loader').hide();
               } else {
                  $('.load_more').hide();
                  $('.loader').hide();
                  $('.no_load_more').show();
               }
            }
         });
      });
   });


   /* Ajax Load More Archive Full */
   $( function() {
      var load_more = $('.archive_project_full .load_more');

      load_more.on('click', function(e){
         e.preventDefault();

         var paged = $(this).attr('data-paged');
         var category = $(this).attr('data-category');

         $('.loader').show();
         $('.load_more').hide();
         $('.no_load_more').hide();

         $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: ({
               action: 'load_more_posts_full',
               category: category,
               paged: paged,
            }),
            success: function(response){
               var grid = $('.archive_project_full .grid');
               var paged_new = parseInt(paged) +1;
               $('.load_more').attr('data-paged', paged_new);

               if (response.trim() != '') {
                  var item = $(response).hide().fadeIn(300); 
                  grid.append( item );
                  $('.load_more').show();
                  $('.loader').hide();
               } else {
                  $('.load_more').hide();
                  $('.loader').hide();
                  $('.no_load_more').show();
               }
            }
         });
      });
   });


   /* Ajax Filter Archive Default */
   $( function() {
      var button = $('.archive_project_default .filter-button-group');
      button.on('click', 'button', function() {
         var filter = $(this).data('filter');

         var $this = $(this).parents('.archive_project_default');

         var paged = $this.find('.load_more').attr('data-paged', '2');

         var paged = $this.find('.load_more').data('paged');

         $this.find('.load_more').attr('data-category', filter);

         $this.find('.wrap_loader_filter').fadeIn(200);
         $this.find('.load_more').hide();
         $this.find('.no_load_more').hide();

         $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: ({
               action: 'filter_archive_default',
               filter: filter,
               paged: paged,
            }),
            success: function(response){

               $this.find('.wrap_loader_filter').fadeOut(100);

               var grid = $('.archive_project_default .grid');

               grid.html( response ).fadeOut(0).fadeIn(500);

               $this.find('.load_more').show();

            },
         });
      });
   });


   /* Ajax Filter Archive Compact */
   $( function() {
      var button = $('.archive_project_compact .filter-button-group');
      button.on('click', 'button', function() {

         var filter = $(this).data('filter');

         var $this = $(this).parents('.archive_project_compact');

         var paged = $this.find('.load_more').attr('data-paged', '2');

         var paged = $this.find('.load_more').data('paged');
         
         $this.find('.load_more').attr('data-category', filter);

         $this.find('.wrap_loader_filter').fadeIn(200);
         $this.find('.load_more').hide();
         $this.find('.no_load_more').hide();

         $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: ({
               action: 'filter_archive_compact',
               filter: filter,
               paged: paged,
            }),
            success: function(response){

               $this.find('.wrap_loader_filter').fadeOut(100);

               var grid = $('.archive_project_compact .grid');

               grid.html( response ).fadeOut(0).fadeIn(500);

               $this.find('.load_more').show();
            },
         });
      });
   });


   /* Ajax Filter Archive Full */
   $( function() {
      var button = $('.archive_project_full .filter-button-group');
      button.on('click', 'button', function() {

         var filter = $(this).data('filter');

         var $this = $(this).parents('.archive_project_full');

         var paged = $this.find('.load_more').attr('data-paged', '2');

         var paged = $this.find('.load_more').data('paged');
         
         $this.find('.load_more').attr('data-category', filter);

         $this.find('.wrap_loader_filter').fadeIn(200);
         $this.find('.load_more').hide();
         $this.find('.no_load_more').hide();

         $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: ({
               action: 'filter_archive_full',
               filter: filter,
               paged: paged,
            }),
            success: function(response){

               $this.find('.wrap_loader_filter').fadeOut(100);

               var grid = $('.archive_project_full .grid');

               grid.html( response ).fadeOut(0).fadeIn(500);

               $this.find('.load_more').show();
            },
         });
      });
   });


   /* Ajax Archive Project Category */
   $( function() {
      var load_more = $('.archive_project_cat .load_more');

      load_more.on('click', function(e){
         e.preventDefault();

         var paged = $(this).attr('data-paged');
         var category = $(this).attr('data-category');

         $('.loader').show();
         $('.load_more').hide();
         $('.no_load_more').hide();

         $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: ({
               action: 'load_more_posts_category',
               category: category,
               paged: paged,
            }),
            success: function(response){
               var grid = $('.archive_project_cat .grid');
               var paged_new = parseInt(paged) +1;
               $('.load_more').attr('data-paged', paged_new);

               if (response.trim() != '') {
                  var item = $(response).hide().fadeIn(300); 
                  grid.append( item );
                  $('.load_more').show();
                  $('.loader').hide();
               } else {
                  $('.load_more').hide();
                  $('.loader').hide();
                  $('.no_load_more').show();
               }

            }
         });
      });
   });

})(jQuery);