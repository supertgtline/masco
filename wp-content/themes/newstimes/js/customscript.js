var $ = jQuery.noConflict();
jQuery.fn.exists = function(callback) {
  var args = [].slice.call(arguments, 1);
  if (this.length) {
    callback.call(this, args);
  }
  return this;
};

/*----------------------------------------------------
/* Mark current day in calendar widget
/*--------------------------------------------------*/
jQuery(document).ready(function() {
	if ( jQuery('#calendar_wrap').length ) {
		jQuery('#calendar_wrap #today').each(function() {
			var $this = jQuery(this),
				dayIndex = $this.index();
			$this.closest('#wp-calendar').find('thead tr th').eq(dayIndex).addClass('today');
		});
	}
});

/*----------------------------------------------------
/* Scroll to top
/*--------------------------------------------------*/
jQuery(document).ready(function() {
	//move-to-top arrow
	jQuery("body").prepend("<div id='move-to-top' class='animate '><i class='fa fa-chevron-up'></i></div>");
	var scrollDes = 'html,body';  
	/*Opera does a strange thing if we use 'html' and 'body' together so my solution is to do the UA sniffing thing*/
	if(navigator.userAgent.match(/opera/i)){
		scrollDes = 'html';
	}
	//show ,hide
	jQuery(window).scroll(function () {
		if (jQuery(this).scrollTop() > 160) {
			jQuery('#move-to-top').addClass('filling').removeClass('hiding');
		} else {
			jQuery('#move-to-top').removeClass('filling').addClass('hiding');
		}
	});
	// scroll to top when click 
	jQuery('#move-to-top').click(function () {
		jQuery(scrollDes).animate({ 
			scrollTop: 0
		},{
			duration :500
		});
	});
});

/*----------------------------------------------------
/* Header Search
/*---------------------------------------------------*/
jQuery(document).ready(function($){
    var $header = $('#header');
    var $input = $header.find('.hideinput, .header-search .fa-search');
	$header.find('.fa-search').hover(function(e){
        $input.addClass('active').focus();
	}, function() {
	   
	});
    $('.header-search .hideinput').click(function(e) {
        e.stopPropagation();
    });
}).click(function(e) {
    $('#header .hideinput, .header-search .fa-search').removeClass('active');
});

jQuery(document).ready(function($){
    $('.header-search .fa-search').click(function(e) {
    	e.preventDefault();
    	e.stopPropagation();
    });
});

/*----------------------------------------------------
/* Responsive Navigation
/*--------------------------------------------------*/
if (mts_customscript.responsive && mts_customscript.nav_menu != 'none') {
	jQuery(document).ready(function($){
		if (mts_customscript.nav_menu == 'both') {
		    var menu_wrapper = $('.secondary-navigation')
		    .clone().attr('class', 'mobile-menu').removeAttr('id')
		    .wrap('<div id="mobile-menu-wrapper" />').parent().hide()
		    .appendTo('body');

	    	$('.primary-navigation > nav > .menu').clone().insertBefore('.mobile-menu .menu');
	    } else {
            var menu_wrapper = $('.'+mts_customscript.nav_menu+'-navigation')
                .clone().attr('class', 'mobile-menu').removeAttr('id')
                .wrap('<div id="mobile-menu-wrapper" />').parent().hide()
                .appendTo('body');
        }

        if ( $('.primary-navigation > nav > .social-links').length && ! $('#mobile-menu-wrapper .social-links').length ) {
	        $('.primary-navigation > nav > .social-links').clone().appendTo('.mobile-menu nav');
	    }

	    $('.toggle-mobile-menu').click(function(e) {
	        e.preventDefault();
	        e.stopPropagation();
	        $('#mobile-menu-wrapper').show();
	        $('body').toggleClass('mobile-menu-active');
	    });

	    // prevent propagation of scroll event to parent
	    $(document).on('DOMMouseScroll mousewheel', '#mobile-menu-wrapper .mobile-menu', function(ev) {
	        var $this = $(this),
	            scrollTop = this.scrollTop,
	            scrollHeight = this.scrollHeight,
	            height = $this.height(),
	            delta = (ev.type == 'DOMMouseScroll' ?
	                ev.originalEvent.detail * -40 :
	                ev.originalEvent.wheelDelta),
	            up = delta > 0;
	    
	        var prevent = function() {
	            ev.stopPropagation();
	            ev.preventDefault();
	            ev.returnValue = false;
	            return false;
	        }
	    
	        if (!up && -delta > scrollHeight - height - scrollTop) {
	            // Scrolling down, but this will take us past the bottom.
	            $this.scrollTop(scrollHeight);
	            return prevent();
	        } else if (up && delta > scrollTop) {
	            // Scrolling up, but this will take us past the top.
	            $this.scrollTop(0);
	            return prevent();
	        }
	    });
	}).on('click', '.main-container-wrap', function() {
	    $('body').removeClass('mobile-menu-active');
	});
}


/*----------------------------------------------------
/*  Dropdown menu
/* ------------------------------------------------- */
jQuery(document).ready(function() { 
	$('#header .menu ul.sub-menu, #header .menu ul.children').hide(); // hides the submenus in mobile menu too
	$('#header .menu li').hover(
		function() {
			$(this).children('ul.sub-menu, ul.children').slideDown('fast');
		}, 
		function() {
			$(this).children('ul.sub-menu, ul.children').hide();
		}
	);
});

/*----------------------------------------------------
/*  Vertical ( widget ) menu
/* ------------------------------------------------- */
jQuery(document).ready(function() { 
	$('.widget_nav_menu ul.sub-menu').hide();
	$('.widget_nav_menu .current-menu-item').last().parents('.menu-item-has-children').addClass('active').children('ul.sub-menu').show();
	$('.widget_nav_menu .menu-item-has-children>a').append('<span class="menu-caret" />');
	$('.menu-caret').click(function(e) {
			e.preventDefault();
			$(this).parent().parent().toggleClass('active').children('ul.sub-menu').slideToggle('fast');
		}
	);
});

/*----------------------------------------------------
/* Scroll to top footer link script
/*--------------------------------------------------*/
jQuery(document).ready(function(){
    jQuery('a[href=#top]').click(function(){
        jQuery('html, body').animate({scrollTop:0}, 'slow');
        return false;
    });
});

/*----------------------------------------------------
/* Social button scripts
/*---------------------------------------------------*/
jQuery(document).ready(function(){
	jQuery.fn.exists = function(callback) {
	  var args = [].slice.call(arguments, 1);
	  if (this.length) {
		callback.call(this, args);
	  }
	  return this;
	};
	(function(d, s) {
	  var js, fjs = d.getElementsByTagName(s)[0], load = function(url, id) {
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.src = url; js.id = id;
		fjs.parentNode.insertBefore(js, fjs);
	  };
	jQuery('span.facebookbtn, .facebook_like').exists(function() {
	  load('//connect.facebook.net/en_US/all.js#xfbml=1', 'fbjssdk');
	});
	jQuery('span.gplusbtn').exists(function() {
	  load('https://apis.google.com/js/plusone.js', 'gplus1js');
	});
	jQuery('span.twitterbtn').exists(function() {
	  load('//platform.twitter.com/widgets.js', 'tweetjs');
	});
	jQuery('span.linkedinbtn').exists(function() {
	  load('//platform.linkedin.com/in.js', 'linkedinjs');
	});
	jQuery('span.pinbtn').exists(function() {
	  load('//assets.pinterest.com/js/pinit.js', 'pinterestjs');
	});
	jQuery('span.stumblebtn').exists(function() {
	  load('//platform.stumbleupon.com/1/widgets.js', 'stumbleuponjs');
	});
	}(document, 'script'));
});