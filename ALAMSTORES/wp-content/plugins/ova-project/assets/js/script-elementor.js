(function($){
	"use strict";

	$(window).on('elementor/frontend/init', function () {

      /* Project Slide */
      elementorFrontend.hooks.addAction('frontend/element_ready/ovapo_project_slide.default', function(){
         var slide = $('.ovapo_project_slide .grid ');
         var data = $('.ovapo_project_slide .grid ').data('owl');

         if (slide.length > 0) {
            slide.owlCarousel(
               data
               );
         }
      }); 


      /* Project Grid */
      elementorFrontend.hooks.addAction('frontend/element_ready/ovapo_project_grid.default', function(){

         $('.ovapo_project_grid .button-filter button:first-child').addClass('active');
         var button = $('.ovapo_project_grid .button-filter');
         button.each(function() {
            button.on('click', 'button', function() {
               button.find('.active').removeClass('active');
               $(this).addClass('active');
            });
         });

         button.on('click', 'button', function(e) {
            e.preventDefault();

            var filter = $(this).data('filter');
            var order = $(this).data('order');
            var orderby = $(this).data('orderby');
            var number_post = $(this).data('number_post');
            var column = $(this).data('column');
            var first_term = $(this).data('first_term');
            var term_id_filter_string = $(this).data('term_id_filter_string');
            var show_featured = $(this).data('show_featured');

            $(this).parents('.ovapo_project_grid').find('.wrap_loader').fadeIn(200);

            $.ajax({
               url: ajax_object.ajax_url,
               type: 'POST',
               data: ({
                  action: 'filter_elementor_grid',
                  filter: filter,
                  order: order,
                  orderby: orderby,
                  number_post: number_post,
                  column: column,
                  first_term: first_term,
                  term_id_filter_string: term_id_filter_string,
                  show_featured: show_featured,
               }),
               success: function(response){

                  $('.ovapo_project_grid .wrap_loader').fadeOut(200);

                  var items = $('.ovapo_project_grid .items');

                  items.html( response ).fadeOut(0).fadeIn(500);

               },
            })
         });

      }); 

   });
})(jQuery);
