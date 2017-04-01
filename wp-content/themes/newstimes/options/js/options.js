jQuery(document).ready(function(){
	if(jQuery('#last_tab').val() == ''){
		jQuery('.nhp-opts-group-tab:first').slideDown('fast');
		jQuery('#nhp-opts-group-menu li:first').addClass('active');
	}else{
		tabid = jQuery('#last_tab').val();
		jQuery('#'+tabid+'_section_group').slideDown('fast');
		jQuery('#'+tabid+'_section_group_li').addClass('active');
	}        
	
	jQuery('.nhp-opts-group-tab-link-a').click(function(){
		var $this = jQuery(this);
        var relid = jQuery(this).attr('data-rel');
		
		jQuery('#last_tab').val(relid);
		
        jQuery('.nhp-opts-group-tab').hide();
        jQuery('#'+relid+'_section_group').fadeIn(400);
		        
        $this.parent().addClass('active').siblings().removeClass('active');
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        return false;        
	});
	
	if(jQuery('#nhp-opts-save').is(':visible')){
		jQuery('#nhp-opts-save').delay(4000).slideUp('slow');
	}
    function addTypographyData(container, form) {
		container.find(".collections .collection").each(function() {
			form.addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][preview_text]', jQuery(this).find(".preview_text").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][preview_color]', jQuery(this).find(".preview_color li.current a").attr("class"))
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][font_family]', jQuery(this).find(".font_family").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][font_variant]', jQuery(this).find(".font_variant").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][font_size]', jQuery(this).find(".font_size").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][font_color]', jQuery(this).find(".font_color").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][css_selectors]', jQuery(this).find(".css_selectors").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][additional_css]', jQuery(this).find(".additional_css").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][backup_font]', jQuery(this).find(".backup_font").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][default]', jQuery(this).attr("data-default"));
            
            i++;
		});   
    }
    jQuery('#nhp-opts-footer').find('#savechanges').click(function(e) {
        // add typography data via hidden fields before submitting
        if (typography_isloaded) {
            addTypographyData(jQuery('#google_typography'),jQuery('#nhp-opts-form-wrapper'));
        }
        
    });
	
	if(jQuery('#nhp-opts-imported').is(':visible')){
		jQuery('#nhp-opts-imported').delay(4000).slideUp('slow');
	}	
	
    
    var changeWarn = false;
	jQuery('input, textarea, select').change(function(){
        if (!changeWarn) {
		  jQuery('#nhp-opts-save-warn').slideDown('slow');
          changeWarn = true;
        }
	});
	
	jQuery('#nhp-opts-import-code-button').click(function(e){
	    e.preventDefault();
		jQuery('#nhp-opts-import-code-wrapper').toggle().find('#import-code-value').val('');
	});

	jQuery('#nhp-opts-export-code-copy').click(function(e){
	    e.preventDefault();
		jQuery('#nhp-opts-export-code').toggle().select();
	});
    
    // Presets
    jQuery('#presets .preset').each(function() {
        var $this = jQuery(this);
        $this.find('.activate-button').click(function(e) {
            e.preventDefault();
            jQuery('#import-code-value').val($this.find('.preset-data').val());
            jQuery('#nhp-opts-import').click();
        });
    });
    
    // Confirm import
	jQuery('#nhp-opts-import').click(function() {
	   return confirm(nhpopts.import_confirm);
	});
    
    // Confirm reset
    jQuery('input[name="'+nhpopts.opt_name+'[defaults]"]').click(function() {
        return confirm(nhpopts.reset_confirm);
    });
    
    // Floating footer
    var $footer = jQuery('#nhp-opts-footer');
    var $bottom = jQuery('#nhp-opts-bottom');
    
    
    $footer.addClass('floating');
    jQuery(document).on('scroll', function(){
        if ($bottom.isOnScreen()) {
            $footer.removeClass('floating');
        } else {
            $footer.addClass('floating');
        }
    });
    if ($bottom.isOnScreen()) {
        $footer.removeClass('floating');
    }
    
    // Needs JS sizing when position:fixed
    var footer_padding = $footer.innerWidth() - $footer.width();
    function resizeFloatingElements() {
        var w = jQuery('#nhp-opts-form-wrapper').width();
        $footer.width(w - footer_padding);
    };
    resizeFloatingElements();
    
    var resizeTimer;
    jQuery(window).resize(function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(resizeFloatingElements, 100);
    });
    
    // theme translation
    var $translations_container = jQuery('.translate-strings');
    var $translations_toggle = jQuery('#nhp-opts-translate');
    function initTranslation() {
        jQuery.ajax({
			url: ajaxurl, 
			data: {  'action' : 'mts_translation_panel' },
			beforeSend: function() {
				//container.find(".loading").show();
                $translations_container.addClass('loading');
			},
			success: function(data) {
                translation_isloaded = true
				$translations_container.removeClass('loading').html(data);
			}
		});
    }
    var translation_isloaded = false;
    // load on document.ready if needed
    if (! $translations_toggle.is(':checked')) {
        jQuery('#nhp-opts-reset-translations').prop('disabled', true);
    }
    if (jQuery('#last_tab').val() == 'translation_default' && $translations_toggle.is(':checked')) {
		initTranslation();
    }
    jQuery('#translation_default_section_group_li a').click(function() {
        // we clicked on translations tab
        // load if it's enabled
        if (!translation_isloaded && $translations_toggle.is(':checked')) {
            initTranslation();
        }
    });
    $translations_toggle.change(function() {
        if (jQuery(this).is(':checked')) {
            if (!translation_isloaded) {
                initTranslation();
            } else {
                $translations_container.show();
            }
            jQuery('#nhp-opts-reset-translations').prop('disabled', false);
        } else {
            $translations_container.hide();
            jQuery('#nhp-opts-reset-translations').prop('disabled', true);
        }
    });
});

jQuery.fn.isOnScreen = function(){

    var win = jQuery(window);

    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();

    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

};

jQuery.fn.addHiddenField = function(name, value) {
    this.each(function () {
        var input = jQuery("<input>").attr("type", "hidden").attr("id", name).attr("name", name).val(value);
        jQuery(this).append(jQuery(input));
    });
    return this;
};