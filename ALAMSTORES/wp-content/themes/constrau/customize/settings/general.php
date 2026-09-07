<?php 
$general_css = '';


/* Primary Font */
$default_primary_font = json_decode( constrau_default_primary_font() );
$primary_font = json_decode( get_theme_mod( 'primary_font' ) ) ? json_decode( get_theme_mod( 'primary_font' ) ) : $default_primary_font;
$primary_font_family = $primary_font->font;


/* General Typo */
$general_font_size = get_theme_mod( 'general_font_size', '15px' );
$general_line_height = get_theme_mod( 'general_line_height', '25px' );
$general_letter_space = get_theme_mod( 'general_letter_space', '0px' );
$general_color = get_theme_mod( 'general_color', '#666' );


/* Primary Color */
$primary_color = get_theme_mod( 'primary_color', '#fed501' );
$primary_color_rgb = constrau_hex2rgb($primary_color)[0].', '.constrau_hex2rgb($primary_color)[1].', '.constrau_hex2rgb($primary_color)[2];

/* Second Font */
$default_second_font = json_decode( constrau_default_second_font() );
$second_font = json_decode( get_theme_mod( 'second_font' ) ) ? json_decode( get_theme_mod( 'second_font' ) ) : $default_second_font;
$second_font_family = $second_font->font;


$general_css .= <<<CSS

body{
	font-family: {$primary_font_family};
	font-weight: 400;
	font-size: {$general_font_size};
	line-height: {$general_line_height};
	letter-spacing: {$general_letter_space};
	color: {$general_color};
}
p{
	color: {$general_color};
	line-height: {$general_line_height};
}

h1,h2,h3,h4,h5,h6,.second_font, .nav_comment_text, .cart-collaterals .cart_totals h2, .cart-collaterals .cart_totals .shop_table tbody tr.cart-subtotal th, .cart-collaterals .cart_totals .shop_table tbody tr.shipping th, .cart-collaterals .cart_totals .shop_table tbody tr.order-total th, .woocommerce-cart-form .shop_table tbody tr td.product-name a, .woocommerce .widget_shopping_cart .total strong, .woocommerce.widget_shopping_cart .total strong,.sidebar .widget ul li a, .sidebar .widget.recent-posts-widget-with-thumbnails ul li a .rpwwt-post-title, .content_comments .comments .number-comments, .according-constrau .elementor-accordion .elementor-accordion-item .elementor-tab-title a,.ova_nav .dropdown-menu > li > a, .ova_nav ul.menu > li > a, .footer_link .elementor-text-editor ul li a,.contact_info .elementor-text-editor ul li, .contact_info .elementor-text-editor ul li a
{
	font-family: {$second_font_family};
}

/*** Blog ***/
article.post-wrap .post-footer .post-readmore-constrau a,
.sidebar .widget.widget_custom_html .ova_search form .search button,
.sidebar .widget.recent-posts-widget-with-thumbnails ul li:before,
.pagination-wrapper .blog_pagination .pagination li.active a,
.pagination-wrapper .blog_pagination .pagination li a:hover,
.carousel-indicators li.active,
.single-post article.post-wrap .post-body .qoute-post-constrau:before,
.content_comments .comments .comment-respond .comment-form .form-submit input#submit
{
	background-color: {$primary_color};
}

.sidebar .widget h4.widget-title,
.carousel-indicators li
{
	border-color: {$primary_color};
}

.sidebar .widget.recent-posts-widget-with-thumbnails ul li .rpwwt-post-date:before,
.single-post article.post-wrap .post-body .qoute-post-constrau i,
.single-post article.post-wrap .pagination-detail a:hover,
.single-post article.post-wrap .pagination-detail a:hover i:before
{
	color: {$primary_color};
}


.ova-heading .sub-title:before,
.ova-heading .sub-title:after,
.ova-heading .sub-title span:before,
.ova-heading .sub-title span:after
{
	background-color: {$primary_color};
}


/*** Menu ***/
.ova_nav .dropdown-menu
{
	background-color: {$primary_color};
}

.ova_nav ul.menu > li.active > a,
.ova_nav ul.menu > li > a:hover
{
	color: {$primary_color};

}

/*** Project ***/
.archive_project_default .button-group button.active,
.archive_project_default .button-group button:hover,
.archive_project_default .wrap_load_more .load_more:hover,
.archive_project_compact .button-group button.active,
.archive_project_compact .button-group button:hover,
.archive_project_compact .wrap_load_more .load_more:hover,
.archive_project_full .wrap_load_more .load_more:hover,
.archive_project_full .content .grid .grid-item .wrap_item .info .title:hover,
.archive_project_cat .wrap_load_more .load_more:hover,
.archive_project_cat .button-group button.active,
.archive_project_cat .button-group button:hover,
.ovapo_project_slide .grid .grid-item .info .title:hover,
.ovapo_project_slide .grid .owl-nav button:hover i:before,
.ovapo_project_grid .button-filter button.active,
.ovapo_project_grid .button-filter button:hover,
.ovapo_project_grid .items .item .info .title:hover,
.single_project .content .detail table .icon_detail,
.single_project .gallery .slide_gallery .prev-arrow:hover:before,
.single_project .gallery .slide_gallery .next-arrow:hover:before,
.single_project .bottom .nav-prev a:hover,
.single_project .bottom .nav-next a:hover,
.single_project .bottom .center i:hover,
.single_project .intro .social ul.share-social-icons li a:hover,
.single_project_middle .top .content .social ul.share-social-icons li a:hover,
.single_project_middle .bottom .nav-prev a:hover,
.single_project_middle .bottom .nav-next a:hover,
.single_project_middle .bottom .center i:hover,
.single_project_middle .top .gallery .slide_gallery .prev-arrow:hover:before,
.single_project_middle .top .gallery .slide_gallery .next-arrow:hover:before
{
	color: {$primary_color};
}

.archive_project_default .wrap_load_more .loader circle,
.archive_project_default .loader_filter circle,
.archive_project_compact .wrap_load_more .loader circle,
.archive_project_compact .loader_filter circle,
.archive_project_full .wrap_load_more .loader circle,
.archive_project_full .loader_filter circle,
.archive_project_cat .wrap_load_more .loader circle,
.ovapo_project_grid .wrap_loader .loader circle
{
	stroke: {$primary_color};
}

.archive_project_default .wrap_load_more .load_more,
.archive_project_compact .wrap_load_more .load_more,
.archive_project_cat .wrap_load_more .load_more,
.archive_project_full .wrap_load_more .load_more,
.archive_project_full .button-group button.active,
.archive_project_full .button-group button:hover,
.archive_project_full .button-group button .count,
.single_project .content .desc .heading_desc:after,
.single_project .content .detail .heading_detail:after
{
	background-color: {$primary_color};
}

.archive_project_default .wrap_load_more .load_more,
.archive_project_compact .wrap_load_more .load_more,
.archive_project_full .wrap_load_more .load_more,
.archive_project_full .button-group button,
.archive_project_full .content .grid .grid-item .wrap_item .info .cat,
.archive_project_cat .wrap_load_more .load_more,
.ovapo_project_slide .grid .grid-item .info .cat,
.ovapo_project_slide .grid .owl-nav button:hover,
.ovapo_project_grid .items .item .info .cat
{
	border-color: {$primary_color}!important;
}


/*** Services Sidebar ***/
.services_sidebar .recent_project li .content .title:hover,
.services_sidebar .brochures .title
{
	color: {$primary_color};
}

.services_sidebar .brochures .button_sidebar
{
	background-color: {$primary_color};
}


/*** Slideshow Elementor ***/
.ova_slideshow .slide_gallery .prev-arrow:hover:before,
.ova_slideshow .slide_gallery .next-arrow:hover:before
{
	color: {$primary_color};
}


/*** Services Elementor ***/
.ova_services_grid .type_2 .icon,
.ova_services_image .type_3 .content .icon,
.ova_services_slide .type_2 .owl-prev:hover i:before,
.ova_services_slide .type_2 .owl-next:hover i:before,
.ova_services_slide .type_1 .owl-nav .owl-next:hover i:before,
.ova_services_slide .type_1 .owl-nav .owl-prev:hover i:before
{
	color: {$primary_color};
}

.ova_services_grid .type_2:hover,
.ova_services_slide .type_1 .item .content:after,
.ova_services_grid .type_2:hover
{
	background-color: {$primary_color};
}

.ova_services_slide .type_1 .owl-nav .owl-next:hover,
.ova_services_slide .type_1 .owl-nav .owl-prev:hover
{
	border-color: {$primary_color};
}


/*** Fix ***/
.news-letter-form-2 .ova-ct-mailchimp .form input[type=submit],
.title_footer .elementor-heading-title:after,
.footer_link.style4 .elementor-text-editor ul li:hover:before
{
	background-color: {$primary_color};
}

.footer_link.style2 .elementor-text-editor ul li a:hover,
.footer_link.style3 .elementor-text-editor ul li a:hover,
.footer_link.style4 .elementor-text-editor ul li:hover a
{
	color: {$primary_color};
}


/*** About Team Elementor ***/
.about-team.version_3 .ova-volunteer .ova-content:after
{
	background-color: {$primary_color};
}
.about-team .ova-volunteer .ova-media:after {
	background-color: rgba({$primary_color_rgb}, 0.7);
}

.about-team .ova-volunteer .ova-content {
	background-color: rgba({$primary_color_rgb}, 0.9);
}

.about-team .owl-nav .owl-prev:hover,
.about-team .owl-nav .owl-next:hover 
{
	border-color: {$primary_color}!important;
}

.about-team .owl-nav .owl-prev:hover i,
.about-team .owl-nav .owl-next:hover i ,
.about-team.version_3 .ova-volunteer .ova-content .name a:hover,
.about-team.version_3 .ova-volunteer .ova-content .list-con ul li a:hover i ,
.about-team.version_4 .ova-volunteer .ova-content .name a:hover
{
	color: {$primary_color}!important;
}


/*** Contact Info ***/
/***
.contact_info .elementor-text-editor ul li:hover,
.contact_info .elementor-text-editor ul li:hover a 
{
	color: {$primary_color};
}
.contact_info .elementor-text-editor ul li:hover:before {
	background-color: {$primary_color};
}
***/

/*** Search Popup ***/
.wrap_search_constrau_popup .search_constrau_popup .search-form .search-submit:hover 
{
	background-color: {$primary_color};
}

.wrap_search_constrau_popup i:hover {
	color: {$primary_color};
}


/*** Slide Revo ***/
.tp-bullets.custom .tp-bullet.selected,
.video_slideshow .button_popup:hover,
.video_slideshow .button_popup:hover:before
{
	background-color: {$primary_color};
}

.tp-bullets.custom .tp-bullet {
	border-color: {$primary_color};
}


/*** Contact Us ***/
.ova_contact_us.style3 .text-line .line-info-2 a
{
	color: {$primary_color};
}

/*** menu cart ***/
.constrau-cart-wrapper .cart-total .items,
.constrau-cart-wrapper .constrau_minicart .woocommerce-mini-cart__buttons .button:first-child,
.constrau-cart-wrapper .constrau_minicart .woocommerce-mini-cart__buttons .button.wc-forward.checkout:hover
{
	background-color: {$primary_color};
}


/*** about team work ***/
.ova-teamwork .ova-media .button-video .image-team i,
.ova-teamwork .content .title a:hover,
.ova-teamwork .content .text_button:hover
/* .about-team .ova-volunteer .ova-content .name a:hover, 
.about-team .ova-volunteer .ova-content .list-con ul li a:hover i */
{
	color: {$primary_color};
}

.ova-teamwork .ova-media .button-video .image-team:hover:before,
.ova-teamwork .ova-media .button-video .image-team:hover i
{
	background-color: {$primary_color};
}


/*** process  element ***/
.ova-process .category ul li a:hover, 
.ova-process .category ul li a.active
{
	background: {$primary_color};
}

.ova-process .category ul li a:hover, 
.ova-process .category ul li a.active 
{
	border-color: {$primary_color};
}

/*** Heading ***/
.ova-heading .button a
{
	background: {$primary_color};
}

/*** testimonial ***/
.ova-testimonial .slide-testimonials.version_1 .client_info .info .star i.active,
.ova-testimonial .slide-testimonials.version_2 .client_info .info .star i.active,
.ova-testimonial .slide-testimonials.version_3 .client_info .info .star i.active
{
	color: {$primary_color};
}

.ova-testimonial .slide-testimonials .owl-dots .owl-dot span
{
	border-color: {$primary_color} !important;
}

.ova-testimonial .slide-testimonials .owl-dots .owl-dot.active span
{
	background-color: {$primary_color};
}

/*** blog slider ***/
.ova-blog-slider .blog-slider .item-blog .content .readmore a
{
	background-color: {$primary_color};
}
.ova-blog-slider .blog-slider .owl-dots .owl-dot.active span
{
	background-color: {$primary_color} !important;
}
.ova-blog-slider .blog-slider .owl-dots .owl-dot span
{
	border-color: {$primary_color} !important;
}

/*** blog element ***/
.ova-blog .item-blog .content .post-meta-blog i
{
	color: {$primary_color};
}

.ova-blog-slider .blog-slider .item-blog .content .post-meta-blog i,
.ova-blog-slider .blog-slider .item-blog .content .post-meta-blog a:hover,
.ova-blog-slider .blog-slider .item-blog .content .title h3 a:hover
{
	color: {$primary_color};
}


/*** Contact form ***/
.contact-1 input[type=submit],
.contact-3 input[type=submit],
.contact-4 > p input[type=submit],
.contact-6 > p input[type=submit]
{
	background-color: {$primary_color};
}

.contact-2 input[type=submit]:hover,
.contact-5 input[type=submit]:hover
{
	color: {$primary_color};
}

/*** overlay section ***/
.overlay-section-constrau .elementor-background-overlay 
{
	background-color: {$primary_color};
}
/** bg section **/
.bg-section-constrau {
	background-color: {$primary_color};
}

/*** layout bg ***/
.layout-bg-contrau .elementor-column-wrap
{
	background-color: {$primary_color};
}

/*** skill bar ***/
.ova-skill-bar .cove-killbar .skillbar-bar
{
	background-color: {$primary_color};
}

.ova-ct-mailchimp .form input[type=submit]
{
	background-color: {$primary_color};
}

.video-player-work .video-inside-work .video:hover,
.video-player-work .video-inside-work .video:hover:before,
.video-player-work.version_2 .video-inside-work .video:hover,
.video-player-work.version_2 .video-inside-work .video:hover:before,
.video-player-work.version_2 .video-inside-work .video:hover:after,
.video-player-work.border_conner .content:after,
.video-player-work.border_conner .content:before
{
	background-color: {$primary_color};
}

/*** feature v2 ***/
.ova-feature-v2 .wp-content .icon span
{
	background-color: {$primary_color};
}

/*** contact v2 ***/
.ova-contact-v-2:before
{
	background-color: {$primary_color};
}

/*** title heading ***/
.ova-title .elementor-heading-title:after
{
	background-color: {$primary_color};
}

/** Industry Solution ***/
.ova-industry-solution .content-industry .item .icon span
{
	background-color: {$primary_color};
}


/*** according ***/
.according-constrau .elementor-accordion .elementor-accordion-item .elementor-tab-title .elementor-accordion-icon i,
.according-constrau.v2 .elementor-accordion .elementor-accordion-item .elementor-tab-title.elementor-active
{
	background-color: {$primary_color};
}

/*** counter ***/
.ova-icon-counter i:before,
.ova-constrau-counter-1 .elementor-counter .elementor-counter-number,
.ova-constrau-counter-1 .elementor-counter .elementor-counter-number-suffix
{
	color: {$primary_color};
}

/** feature **/
.ova-feature .content:hover .text_button:hover
{
	color: {$primary_color};
}

/** contact **/
.ova-contact.version_2 .ova-title .title:after
{
	background-color: {$primary_color};
}


/** position **/
.ova-position .item .wp-content .content .title a:hover
{
	color: {$primary_color};
}
.ova-position .item:hover .content,
.ova-position .item:hover .desc,
.ova-position .owl-dots .owl-dot span
{
	border-color: {$primary_color} !important;
}
.ova-position .owl-dots .owl-dot.active span
{
	background-color: {$primary_color}!important;
}

/*** history ***/
.ova-image-history .elementor-widget-container .elementor-image:before,
.ova-history .wp-item .wp-year .sub-title
{
	border-color: {$primary_color}!important;
}

.ova-history .wp-item .wp-content .title p:after
{
	border-left-color: {$primary_color}!important;
}

.ova-history .wp-item .wp-content .title p,
.ova-history .wp-item .wp-year .sub-title .ova-point,
.ova-history .wp-item .wp-year .sub-title .ova-point span:before,
.ova-history .wp-item .wp-year .sub-title .ova-point span:after
{
	background-color: {$primary_color}!important;
}

/** shop **/
.woocommerce-cart .woocommerce .woocommerce-cart-form .shop_table tbody tr td.actions .coupon button.button,
.woocommerce-cart .woocommerce .woocommerce-cart-form .shop_table tbody tr td.actions button.button,
.woocommerce.single-product .product .cart .single_add_to_cart_button,
.woocommerce-pagination ul.page-numbers li .page-numbers.current,
.woocommerce.single-product .product .woocommerce-Reviews #review_form_wrapper .form-submit input#submit,
.woocommerce.single-product .product .woo-thumbnails .owl-carousel .item:hover
{
	background-color: {$primary_color};
}
.woocommerce-cart .woocommerce .woocommerce-cart-form .shop_table tbody tr td.actions .coupon button.button,
.woocommerce ul.products li.product:hover .woocommerce-loop-product__link img
{
	border-color: {$primary_color}!important;
}

.woocommerce-checkout .checkout #customer_details .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper .form-row label .required,
.woocommerce ul.products li.product .price,
.woocommerce.single-product .product .woo-thumbnails button.owl-prev:hover,
.woocommerce.single-product .product .woo-thumbnails button.owl-next:hover
{
	color: {$primary_color};
}

/** button **/
.constrau-btn-color-type-1 .elementor-button-link.elementor-button
{
	color:  {$primary_color};
}
.constrau-btn-color-type-1 .elementor-button-link:hover
{
	background-color: {$primary_color}!important;
}
.constrau-btn-color-type-1 .elementor-button-link
{
	border-color: {$primary_color};
}

.constrau-btn-color-type-2 .elementor-button-link
{
	background-color: {$primary_color};
}
.constrau-btn-color-type-2 .elementor-button-link:hover
{
	color:  {$primary_color}!important;
}

/*** contact info ***/
.ova_contact_us.style2 i
{
	color:  {$primary_color};
}

CSS;

return $general_css;