<?php return array(


/* Theme Admin Menu */
"menu" => array(
    array("id"    => "1",
          "name"  => "General"),
    
    array("id"    => "2",
          "name"  => "Homepage"),
          
	array("id"    => "7",
          "name"  => "Banners"),
),

/* Theme Admin Options */
"id1" => array(
    array("type"  => "preheader",
          "name"  => "Theme Settings"),

    array("name"  => "Color Style",
          "desc"  => "Choose the style that you would like to use.<br />",
          "id"    => "theme_style",
          "options" => array('Default','Dark','Blue','Green','Grey','Orange','Pink','Purple','Red','Yellow'),
          "std"   => "Default",
          "type"  => "select"),

	 array("name"  => "Logo Image",
          "desc"  => "Upload a custom logo image for your site, or you can specify an image URL directly.",
          "id"    => "misc_logo_path",
          "std"   => "",
          "type"  => "upload"),

    array("name"  => "Favicon URL",
          "desc"  => "Upload a favicon image (16&times;16px).",
          "id"    => "misc_favicon",
          "std"   => "",
          "type"  => "upload"),
          
    array("name"  => "Custom Feed URL",
          "desc"  => "Example: <strong>http://feeds.feedburner.com/wpzoom</strong>",
          "id"    => "misc_feedburner",
          "std"   => "",
          "type"  => "text"),

    array("name"  => "Display Social Icons in Top Menu",
          "desc"  => "Leave this checked if you want to display the social icons in the header.",
          "id"    => "social_icons_show",
          "std"   => "on",
          "type"  => "checkbox"), 
 
    array("name"  => "Facebook URL",
          "desc"  => "Example: <strong>http://www.facebook.com/wpzoom</strong>",
          "id"    => "social_icons_facebook",
          "std"   => "",
          "type"  => "text"),

    array("name"  => "Twitter URL",
          "desc"  => "Example: <strong>http://twitter.com/wpzoom</strong>",
          "id"    => "social_icons_twitter",
          "std"   => "",
          "type"  => "text"),

 	array("type"  => "preheader",
          "name"  => "Global Menu Options"),

    array("name"  => "Show top menu",
          "id"    => "menu_top_show",
          "std"   => "on",
          "type"  => "checkbox"),

    array("name"  => "Show top secondary menu",
          "id"    => "menu_top_secondary_show",
          "std"   => "on",
          "type"  => "checkbox"),

    array("name"  => "Show footer menu",
          "id"    => "menu_footer_show",
          "std"   => "on",
          "type"  => "checkbox"),

 	array("type"  => "preheader",
          "name"  => "Posts Archives Options"),
          
    array("name"  => "Excerpt length",
          "desc"  => "Default: <strong>25</strong> (words)",
          "id"    => "excerpt_length",
          "std"   => "25",
          "type"  => "text"),

    array("name"  => "Show Date/time",
          "desc"  => "<strong>Date/Time format</strong> can be changed <a href='options-general.php' target='_blank'>here</a>.",
          "id"    => "display_date",
          "std"   => "on",
          "type"  => "checkbox"),  

    array("name"  => "Show Author",
          "id"    => "display_author",
          "std"   => "on",
          "type"  => "checkbox"),

    array("name"  => "Show Category",
          "id"    => "display_category",
          "std"   => "on",
          "type"  => "checkbox"),           

    array("name"  => "Show Comments Count",
          "id"    => "display_comments",
          "std"   => "on",
          "type"  => "checkbox"), 

	array("type"  => "preheader",
          "name"  => "Single Post Options"),
          
	array("name"  => "Show Date/time",
          "desc"  => "<strong>Date/Time format</strong> can be changed <a href='options-general.php' target='_blank'>here</a>.",
          "id"    => "post_date",
          "std"   => "on",
          "type"  => "checkbox"),  
          
    array("name"  => "Show Category",
          "id"    => "post_category",
          "std"   => "off",
          "type"  => "checkbox"), 
          
    array("name"  => "Show Author",
          "id"    => "post_author",
          "std"   => "on",
          "type"  => "checkbox"),
          
    array("name"  => "Show Tags",
          "id"    => "post_tags",
          "std"   => "on",
          "type"  => "checkbox"),
          
	array("name"  => "Show Social Buttons",
          "id"    => "post_share",
          "std"   => "on",
          "type"  => "checkbox"),
          
    array("name"  => "Show Comments",
          "id"    => "post_comments",
          "std"   => "on",
          "type"  => "checkbox"),

	array("type"  => "preheader",
          "name"  => "Single Page Options"),
          
	array("name"  => "Show Social Buttons",
          "id"    => "page_share",
          "std"   => "on",
          "type"  => "checkbox"),
          
    array("name"  => "Show Comments",
          "id"    => "page_comments",
          "std"   => "on",
          "type"  => "checkbox"),

),

"id2" => array(          

	array("type"  => "preheader",
          "name"  => "Homepage Settings"),

	array("name"  => "Display Recent Posts on Homepage",
          "id"    => "recent_part_enable",
          "std"   => "on",
          "type"  => "checkbox"),
 
	array("name"  => "Exclude categories",
          "desc"  => "Exclude categories from appearing in the Recent Posts block.",
          "id"    => "recent_part_exclude",
          "std"   => "",
          "type"  => "select-category-multi"),

	array("name"  => "Display Featured Posts",
          "desc"  => "Display featured posts at the top of the Homepage.<br />If you have troubles displaying posts in this section, please <a href='http://www.wpzoom.com/documentation/splendid/#featured' target='_blank'>read the documentation</a>.",
          "id"    => "featured_enable",
          "std"   => "on",
          "type"  => "checkbox"),

	array("name"  => "Hide Featured Posts in Recent Posts?",
          "desc"  => "You can use this option if you want to hide posts which are featured on front page from the Recent Posts block, to avoid duplication.",
          "id"    => "hide_featured",
          "std"   => "on",
          "type"  => "checkbox"),

	array("name"  => "Number of posts to display",
          "desc"  => "Choose how many featured posts should be displayed.",
          "id"    => "featured_number",
          "std"   => "5",
          "type"  => "text"),

	array("name"  => "Autoplay (auto-scroll)",
          "desc"  => "Do you want to auto-scroll the slides? If yes, set the time in miliseconds. Ex: 5000 (5 seconds). Leave 0 to disable autoplay.",
          "id"    => "featured_autoplay",
          "std"   => "5000",
          "type"  => "text"),

	array("type"  => "preheader",
          "name"  => "Featured Small Categories (tabs)"),

	array("name"  => "Display Featured Categories on Homepage",
          "desc"  => "Do you want to show featured categories on the homepage? Will appear as tabs, 6 posts per row in every category (tab).",
          "id"    => "featured_cats_show",
          "std"   => "on",
          "type"  => "checkbox"),

	array("name"  => "Posts per Category",
          "desc"  => "How many posts should appear in every \"Featured Category\" on the homepage? Default: 6.",
          "id"    => "featured_categories_posts",
          "std"   => "6",
          "type"  => "text"),

    array("name"  => "Show Date/time",
          "desc"  => "<strong>Date/Time format</strong> can be changed <a href='options-general.php' target='_blank'>here</a>.",
          "id"    => "featured_footer_display_date",
          "std"   => "on",
          "type"  => "checkbox"),  

	array("name"  => "Featured Category 1",
          "desc"  => "Select the category which should appear as #1.",
          "id"    => "featured_category_1",
          "std"   => "",
          "type"  => "select-category"),
          
    array("name"  => "Featured Category 2",
          "desc"  => "Select the category which should appear as #2.",
          "id"    => "featured_category_2",
          "std"   => "",
          "type"  => "select-category"),

	array("name"  => "Featured Category 3",
          "desc"  => "Select the category which should appear as #3.",
          "id"    => "featured_category_3",
          "std"   => "",
          "type"  => "select-category"),
          
    array("name"  => "Featured Category 4",
          "desc"  => "Select the category which should appear as #4.",
          "id"    => "featured_category_4",
          "std"   => "",
          "type"  => "select-category"),

	array("name"  => "Featured Category 5",
          "desc"  => "Select the category which should appear as #5.",
          "id"    => "featured_category_5",
          "std"   => "",
          "type"  => "select-category"),
          
    array("name"  => "Featured Category 6",
          "desc"  => "Select the category which should appear as #6.",
          "id"    => "featured_category_6",
          "std"   => "",
          "type"  => "select-category"),

	array("name"  => "Featured Category 7",
          "desc"  => "Select the category which should appear as #7.",
          "id"    => "featured_category_7",
          "std"   => "",
          "type"  => "select-category"),
          
    array("name"  => "Featured Category 8",
          "desc"  => "Select the category which should appear as #8.",
          "id"    => "featured_category_8",
          "std"   => "",
          "type"  => "select-category"),

	array("name"  => "Featured Category 9",
          "desc"  => "Select the category which should appear as #9.",
          "id"    => "featured_category_9",
          "std"   => "",
          "type"  => "select-category"),
          
    array("name"  => "Featured Category 10",
          "desc"  => "Select the category which should appear as #10.",
          "id"    => "featured_category_10",
          "std"   => "",
          "type"  => "select-category"),

    ),

"id7" => array(

	array("type"  => "preheader",
          "name"  => "Header Ad"),
          
	array("name"  => "Enable ad in header, to the right of the site logo.",
          "id"    => "banner_header_enable",
          "std"   => "off",
          "type"  => "checkbox"),
          
    array("name"  => "HTML Code (Adsense)",
          "desc"  => "Enter complete HTML code for your banner (or Adsense code) or upload an image below.",
          "id"    => "banner_header_html",
          "std"   => "",
          "type"  => "textarea"),
          
	array("name"  => "Upload your image",
          "desc"  => "Upload a banner image or enter the URL of an existing image.",
          "id"    => "banner_header",
          "std"   => "",
          "type"  => "upload"),
          
	array("name"  => "Destination URL",
          "desc"  => "Enter the URL where this banner ad points to.",
          "id"    => "banner_header_url",
          "type"  => "text"),
          
	array("name"  => "Banner Title",
          "desc"  => "Enter the title for this banner which will be used for ALT tag.",
          "id"    => "banner_header_alt",
          "type"  => "text"),

	array("type"  => "preheader",
          "name"  => "Sidebar Top Ad"),
          
	array("name"  => "Enable ad in sidebar, before menu and widgets",
          "id"    => "banner_sidebar_top_enable",
          "std"   => "off",
          "type"  => "checkbox"),
          
    array("name"  => "HTML Code (Adsense)",
          "desc"  => "Enter complete HTML code for your banner (or Adsense code) or upload an image below.",
          "id"    => "banner_sidebar_top_html",
          "std"   => "",
          "type"  => "textarea"),
          
	array("name"  => "Upload your image",
          "desc"  => "Upload a banner image or enter the URL of an existing image.",
          "id"    => "banner_sidebar_top",
          "std"   => "",
          "type"  => "upload"),
          
	array("name"  => "Destination URL",
          "desc"  => "Enter the URL where this banner ad points to.",
          "id"    => "banner_sidebar_top_url",
          "type"  => "text"),
          
	array("name"  => "Banner Title",
          "desc"  => "Enter the title for this banner which will be used for ALT tag.",
          "id"    => "banner_sidebar_top_alt",
          "type"  => "text"),
          
          
	array("type"  => "preheader",
          "name"  => "Sidebar Bottom Ad"),
          
	array("name"  => "Enable ad in sidebar, after menu and widgets",
          "id"    => "banner_sidebar_bottom_enable",
          "std"   => "off",
          "type"  => "checkbox"),
          
    array("name"  => "HTML Code (Adsense)",
          "desc"  => "Enter complete HTML code for your banner (or Adsense code) or upload an image below.",
          "id"    => "banner_sidebar_bottom_html",
          "std"   => "",
          "type"  => "textarea"),
          
	array("name"  => "Upload your image",
          "desc"  => "Upload a banner image or enter the URL of an existing image.",
          "id"    => "banner_sidebar_bottom",
          "std"   => "",
          "type"  => "upload"),
          
	array("name"  => "Destination URL",
          "desc"  => "Enter the URL where this banner ad points to.",
          "id"    => "banner_sidebar_bottom_url",
          "type"  => "text"),
          
	array("name"  => "Banner Title",
          "desc"  => "Enter the title for this banner which will be used for ALT tag.",
          "id"    => "banner_sidebar_bottom_alt",
          "type"  => "text"),

)

/* end return */);