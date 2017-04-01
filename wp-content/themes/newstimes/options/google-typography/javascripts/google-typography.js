	var itemCount = 0;
    var GoogleTypography = function(container, collection, values){

		initialize = function(container, collection) {
			
			var container = jQuery(container);
			var collection = jQuery(collection);
			var preview = jQuery(".font_preview input", collection);
			
            itemCount++;
            collection.addClass('collection-'+itemCount);
            collection.data('itemIndex', itemCount);
            
			// Dropdown styles
			collection.find("select").chosen();
      
			// Colorpicker
			collection.find(".font_color").wpColorPicker({
				change: function(event, ui) {
					preview.css( 'color', ui.color.toString());
				}
			});
      
			// Font attributes
			collection.find(".font_family").on("change", function(e, variant) {
			 var google = false;
             var $selected = jQuery('option:selected', this);
			 if ($selected) {
                 if ($selected.data('google')) {
    			     google = true;
    			 }
             }
             
             previewFontFamily(jQuery(this), collection, preview, variant, google); 
             if (google) {
                collection.find(".backup_font+div").show();
             } else {
                collection.find(".backup_font+div").hide();
             }
            });
			collection.find(".font_variant").on("change", function() { previewFontVariant(jQuery(this), preview); });
			collection.find(".font_size").change(function() { previewFontSize(jQuery(this), preview); });
			collection.find(".preview_color li a").on("click", function() { previewBackgroundColor(jQuery(this), collection); });
            collection.find(".additional_css").on("change", function() { 
                var selector = '.collection-'+collection.data('itemIndex')+' .preview_text';
                collection.find('.additional-css').html(selector+' { '+jQuery(this).val()+' }');
            });
            
			// Save and delete
            // collection.find(".save_collection").on("click", function() { saveCollections(collection, container); });
			collection.find(".delete_collection").on("click", function() { 
				if(confirm(googletypography.delete_confirm)) {
					collection.remove(); 
					//saveCollections(collection, container, false);
					//if(container.find(".collections .collection").length == 0) {
					//	container.find(".welcome").fadeIn();
					//}
				}
			});
			
			collection.on("focus", "input, select, textarea", function(){ setCurrentCollection(container, collection); });
			
			collection.find(".wp-color-result").on("click", function(){ setCurrentCollection(container, collection); });
			
			if(values) {
				loadCollection(values, collection);
			}
      
		};
		
		setCurrentCollection = function(container, collection) {
			
			container.find(".collection").removeClass("current");
			
			collection.addClass("current");
			
		};
    
		previewFontFamily = function(elem, collection, preview, variant, google) {
			var font = jQuery(elem).val();
            if (google) {
                getFontVariants(font, collection, variant, preview);
            } else {
                preview.css('font-family', font).css("opacity", 1);
                var variants = collection.find(".font_variant");
                variants.find("option").remove();
                variants
                    .append('<option value="normal">normal</option>')
                    .append('<option value="700">700</option>')
                    .trigger("change").trigger("liszt:updated");
            }
		};
    
		previewFontVariant = function(elem, preview) {

			preview.css('font-weight', jQuery(elem).val());
      
		};    
    
		previewFontSize = function(elem, preview) {

			jQuery(preview).css('font-size', jQuery(elem).val());
      
		};
    
		previewBackgroundColor = function(elem, collection) {
      
			collection.find(".font_preview .preview_color li").removeClass("current");
			collection.find(".font_preview")
				.removeClass("dark light")
				.addClass(jQuery(elem).attr("class"));
				jQuery(elem).parent().addClass("current");
      
		};
    
		getFontVariants = function(font, collection, selected, preview) {

			var variants = collection.find(".font_variant");
      
			var variant_array = [];

			jQuery.ajax({
				url: ajaxurl,
				data: {
					'action' : 'get_google_font_variants',
					'font_family' : font
				},
				success: function(data) {
					variants.find("option").remove();
    			for(i = 0; i < data.length; ++i) {
						if(selected == data[i]) { 
							var is_selected = "selected"; 
						} else { 
							var is_selected = ""; 
						}
						variants.append('<option value="'+data[i]+'" '+is_selected+'>'+data[i]+'</option>');
						variant_array.push(data[i]);
    			}

					WebFont.load({
						google: {
							families: [font+':'+variant_array.join()]
						},
						loading: function() {
							preview.css("opacity", 0);
						},
						fontactive: function(family, desc) {
							preview.css('font-family', '"'+font+'"').css("opacity", 1);
						}
					});

					variants.trigger("change").trigger("liszt:updated");
    		}
    	});
      
		};
    
		saveCollections = function(collection, container, showLoading) {
      
			var collectionData = new Array();
			i=0;
      
			container.find(".collections .collection").each(function() {

				previewText		= jQuery(this).find(".preview_text").val();
				previewColor	= jQuery(this).find(".preview_color li.current a").attr("class");
				fontFamily		= jQuery(this).find(".font_family").val();
				fontVariant		= jQuery(this).find(".font_variant").val();
				fontSize		= jQuery(this).find(".font_size").val();
				fontColor		= jQuery(this).find(".font_color").val();
				cssSelectors	= jQuery(this).find(".css_selectors").val();
                additionalCSS	= jQuery(this).find(".additional_css").val();
                backupFont      = jQuery(this).find(".backup_font").val();
				isDefault		= jQuery(this).attr("data-default");
        
				collectionData[i] = {
					uid: i+1,
					preview_text: previewText,
					preview_color: previewColor,
					font_family: fontFamily,
					font_variant: fontVariant, 
					font_size: fontSize,
					font_color: fontColor,
					css_selectors: cssSelectors,
                    backup_font: backupFont,
                    additional_css: additionalCSS,
					default: isDefault
				};
  
				i++;
        
			});
			
			jQuery.ajax({
				url: ajaxurl, 
				method: 'post',
				data: {  'action' : 'save_user_fonts',  'collections' : collectionData },
				success: function(data) {
					
					if(showLoading != false) {
						collection.find(".save_collection").removeClass("saving").html("Save");
					}
					
				}
			});
		};
		
		loadCollection = function(values, collection) {

			collection.find(".preview_text").val(values.preview_text.replace("\\", ""));
			collection.find(".preview_color li a[class="+values.preview_color+"]").trigger("click");
			if(values.font_family) {
				collection.find(".font_family option[value='"+values.font_family+"']")
					.attr("selected", "selected")
					.trigger("change", [values.font_variant])
					.trigger("liszt:updated");
			}
            
            // MTS Custom values: additional CSS and Backup font
            if (values.backup_font) {
    			collection.find(".backup_font option[value='"+values.backup_font+"']")
    				.attr("selected", "selected")
    				.trigger("change")
    				.trigger("liszt:updated");
            }
            if (values.additional_css) {
                var selector = '.collection-'+collection.data('itemIndex')+' .preview_text';
                collection.find(".additional_css").val(values.additional_css).trigger('change');
                //collection.find('.additional-css').html(selector+' { '+values.additional_css+' }');
            }
			// fontVariant		= jQuery(this).find(".font_variant").val();
            
            if (values.font_size) {
				collection.find(".font_size option[value="+values.font_size+"]")
					.attr("selected", "selected")
					.trigger("change")
					.trigger("liszt:updated");
            }
            if (values.font_variant) {
                collection.find(".font_variant option[value="+values.font_variant+"]")
					.attr("selected", "selected")
					.trigger("change")
					.trigger("liszt:updated");
            }
			collection.find(".font_color")
				.val(values.font_color)
				.wpColorPicker('color', values.font_color);
			collection.find(".css_selectors").val(values.css_selectors);
			
			collection.attr("data-default", values.default);
			
		};
    
		initialize(container, collection);
    
	}
	
    var typography_isloaded = false;
	// jQuery ready
	jQuery(document).ready(function($) {
    
		var container = $("#google_typography");
		var template = container.find(".template").html();
		
        
        function initTypography() {
            $.ajax({
    			url: ajaxurl, 
    			data: {  'action' : 'get_user_fonts' },
    			beforeSend: function() {
    				container.find(".loading").show();
    				container.find(".collections").hide();
    			},
    			success: function(data) {
                    typography_isloaded = true
    				if(data.collections.length == 0 || data.collections == false) {
    					container.find(".loading").fadeOut("normal", function() {
    						container.find(".welcome").fadeIn();
    					});
    				} else {
    					for (var i=0;i<data.collections.length;i++) {
    						new GoogleTypography(container, $(template).appendTo(".collections"), data.collections[i])
    					}
    					container.find(".loading").fadeOut("normal", function() {
    						container.find(".collections").fadeIn();
    					});
    				}
    				$(".collections").sortable({
    					items: '.collection',
    					containment: ".wrap",
                        handle: '.font_preview'
    				});
    			}
    		});
        }
        
        // Load up
        if ($('#typography_default_section_group_li').hasClass('active') || jQuery('#last_tab').val() == 'typography_default') {
    		initTypography();
        }
        $('#typography_default_section_group_li_a').click(function() {
            if (!typography_isloaded) {
                initTypography();
            }
        });
        
        // Remove preview text
        // Todo: get i18n string from php and use that
        container.on('focus', '.font_preview input', function() {
            var $this = $(this);
            if ($this.val() == 'Type in some text to preview...' && $this.data('init')) {
                $this.val('');
            } else {
                $this.data('init', true);
            }
        }).on('blur', '.font_preview input', function() {
            var $this = $(this);
            if ($this.val() == '') {
                $this.val('Type in some text to preview...');
            }
        });
        
        // Prevent submit by hitting enter
        container.on('keypress', 'input', function(event) { return event.keyCode != 13; });
		
		// Add a new collection
		$('#typography_default_section_group').find(".new_collection").on("click", function(e) { 
            var new_collection;
			new GoogleTypography(container, new_collection = $(template).prependTo(".collections"));
            new_collection.find('.font_family').trigger('change');
			container.find(".collections").show();
			container.find(".collections .collection:first .preview_text").focus();
			container.find(".welcome").hide();
		});
		
		// Reset collections
		$('#typography_default_section_group').find(".reset_collections").on("click", function() {
			if(confirm(googletypography.reset_confirm)) {
				$.ajax({
					url: ajaxurl, 
					method: 'post',
					data: {  'action' : 'reset_user_fonts' },
					success: function(data) {
						if(data.success == true) {
							location.reload();
						}
					}
				});
			}
		});
	});