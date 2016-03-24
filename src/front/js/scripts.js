/*global console:false, jQuery:false, scotchPanel:false, FastClick:false, owlCarousel: false, stick_in_parent:false*/

(function($) {
	'use strict';

	$(function()
	{
		FastClick.attach(document.body);

		$('.slider').owlCarousel({
			loop:true,
			autoplay: true,
			autoplayTimeout:3000
			autoplayHoverPause:true,
			responsive:{
				0:{
					items:1,
					dots:true
				}
			}
		});

		window.dispatchEvent(new Event('resize'));

		$('.toggle-nav').click(function() {
			$('body').toggleClass('side-open');
			$('.toggle-nav').toggleClass('is-active');
		});

		pageWidth();

		$(window).on('resize orientationchange', function() {
			pageWidth();
		});

		function pageWidth () {
			var width = $('.container').width();

			if ( $(window).width() <= 800 ) {
				$('.page').removeAttr('style'); 
				$('.resize').removeAttr('style'); 
				$('.resize-post-header').removeAttr('style'); 
				return;
			}

			var pWidth = width - 315; // 315: Sidebar + 15px margin
			var hWidth = width - 456; // 456: Logo + Sidebar + 15px margin
			var postHeaderWidth = width - 341; // 341: Logo small + Sidebar + 15px margin

			$('.page').css({width:pWidth}); 
			$('.resize').css({width:hWidth});
			$('.resize-post-header').css({width:postHeaderWidth});

			$('.arrow').removeClass('animate');
			$('.modal').removeClass('animate');
			$('.search').removeClass('animate');
		}

		$(document).on('click', '.more', function () {
			$('.modal').toggleClass('animate');
			$('.arrow').toggleClass('animate');
		});

		$(document).on('click', '.show-search', function () {
			$(this).siblings('.search').toggleClass('animate');
		});

		$(document).on('click', '.mobile-search', function () {
			$(this).parent('form').submit();
		});

		$(document).on('click', '.go-top', function () {
			$('html, body').animate({'scrollTop': '0px'}, 1000);
		});

		scrollManager();

		$(document).scroll(function() {
			scrollManager();
		});

		/* jshint ignore:start */
		// $('.sticky').stick_in_parent({
		// 	parent: "body"
		// });
		/* jshint ignore:end */

		function scrollManager (argument) {

			var scroll = $(document).scrollTop();

			$('.arrow').removeClass('animate');
			$('.modal').removeClass('animate');
			$('.search').removeClass('animate');

			if ( scroll >=500 ) {

				if ( ! $('.go-top').hasClass('show') )
					$('.go-top').addClass('show');
			}

			else {
				$('.go-top').removeClass('show');
			}

			if ( $('.header--post').length )
			{
				if ( scroll >=300 && $(window).width() > 500 ) {

					if ( ! $('.header--post').hasClass('animate') )
					{
						$('.header--post').addClass('animate');
						$('.header--main').addClass('animate');
					}
				}

				else {
					$('.header--post').removeClass('animate');
					$('.header--main').removeClass('animate');
				}
			}
		}

		$('.share-buttons a').on('click', function(e) {
			e.preventDefault();
			var url = $(this).attr('href');

			var w = window.open(url,'Share','width=550,height=400');
			return false;
		});
	});


})(jQuery);