jQuery(document).ready(function() {
	function isScrolledTo(elem) {
		var docViewTop = jQuery(window).scrollTop(); //num of pixels hidden above current screen
		var docViewBottom = docViewTop + jQuery(window).height();

		var elemTop = jQuery(elem).offset().top; //num of pixels above the elem
		var elemBottom = elemTop + jQuery(elem).height();

		return ((elemTop <= docViewTop));
	}

	function stickThatMenu(sticky,catcher) {
		if(isScrolledTo(sticky)) {
			sticky.addClass('sticky-nav');
			catcher.height(sticky.height());
		} 
		var stopHeight = catcher.offset().top;
		if ( stopHeight > sticky.offset().top) {
			sticky.removeClass('sticky-nav');
			catcher.height(0);
		}
	}

	var catcher = jQuery('#catcher');
	var sticky = jQuery('#sticky');
	
	jQuery(window).scroll(function() {
		stickThatMenu(sticky,catcher);
	});
	jQuery(window).resize(function() {
		stickThatMenu(sticky,catcher);
	});
});
