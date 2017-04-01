<?php
/*-----------------------------------------------------------------------------------*/
/*  Do not remove these lines, sky will fall on your head.
/*-----------------------------------------------------------------------------------*/
define('newstimes', 'newstimes');
require_once( dirname( __FILE__ ) . '/theme-options.php' );
if ( ! isset( $content_width ) ) $content_width = 1060;

/*-----------------------------------------------------------------------------------*/
/*  Load Options
/*-----------------------------------------------------------------------------------*/
$mts_options = get_option(newstimes);

/*-----------------------------------------------------------------------------------*/
/*  Load Translation Text Domain
/*-----------------------------------------------------------------------------------*/
load_theme_textdomain( 'mythemeshop', get_template_directory().'/lang' );

// Custom translations
if (!empty($mts_options['translate'])) { 
    $mts_translations = get_option('mts_translations_'.newstimes);//$mts_options['translations'];
    function mts_custom_translate( $translated_text, $text, $domain ) {
        if ($domain == 'mythemeshop' || $domain == 'nhp-opts') {
            // get options['translations'][$text] and return value
            global $mts_translations;
            
            if (!empty($mts_translations[$text])) {
                $translated_text = $mts_translations[$text];
            }
        }
        return $translated_text;
    }
    add_filter( 'gettext', 'mts_custom_translate', 20, 3 );
}

if ( function_exists('add_theme_support') ) add_theme_support('automatic-feed-links');

/*-----------------------------------------------------------------------------------*/
/*  Custom menu walker
/*-----------------------------------------------------------------------------------*/
include('functions/nav-menu.php');

/*-----------------------------------------------------------------------------------*/
/*  Disable theme updates from WordPress.org theme repository
/*-----------------------------------------------------------------------------------*/
function mts_disable_theme_update( $r, $url ) {
    if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
        return $r; // Not a theme update request
    $themes = unserialize( $r['body']['themes'] );
    unset( $themes[ get_option( 'template' ) ] );
    unset( $themes[ get_option( 'stylesheet' ) ] );
    $r['body']['themes'] = serialize( $themes );
    return $r;
}
add_filter( 'http_request_args', 'mts_disable_theme_update', 5, 2 );
add_filter( 'auto_update_theme', '__return_false' );

/*-----------------------------------------------------------------------------------*/
/*  Post Thumbnail Support
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) ) { 
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'featured', 390, 200, true ); //featured
    add_image_size( 'featured1', 385, 248, true ); //featured1
    add_image_size( 'featured2', 260, 240, true ); //featured2
    add_image_size( 'featured3', 293, 238, true ); //featured3
    add_image_size( 'featured4', 330, 233, true ); //featured4
    add_image_size( 'featuredfull', 780, 350, true ); //featured full width
    add_image_size( 'related', 115, 115, true ); //related
    add_image_size( 'widgetfull', 345, 250, true ); //sidebar full width
    add_image_size( 'widgetfullwide', 345, 115, true ); //sidebar full width wide
    add_image_size( 'slider', 780, 480, true ); //slider
    add_image_size( 'slider1', 585, 476, true ); //slider1
    add_image_size( 'slider2', 830, 476, true ); //slider2
    add_image_size( 'smallthumb', 80, 55, true ); //ajax search
}

function mts_get_thumbnail_url( $size = 'full' ) {
    global $post;
    if (has_post_thumbnail( $post->ID ) ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
        return $image[0];
    }
    
    // use first attached image
    $images =& get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );
    if (!empty($images)) {
        $image = reset($images);
        $image_data = wp_get_attachment_image_src( $image->ID, $size );
        return $image_data[0];
    }
        
    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) )
        return get_template_directory_uri().'/images/nothumb-'.$size.'.png';
    else
        return '';
}

/*-----------------------------------------------------------------------------------*/
/*  Video Post Format Support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'post-formats', array( 'video' ) );

/*-----------------------------------------------------------------------------------*/
/*  Custom Menu Support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'menus' );
if ( function_exists( 'register_nav_menus' ) ) {
    register_nav_menus(
        array(
            'secondary-menu' => __('Navigation Menu', 'mythemeshop')
        )
    );
}

// Filter wp_nav_menu() to add home icon to menus
add_filter( 'wp_nav_menu_items', 'mts_menu_home_icon', 10, 2 );
function mts_menu_home_icon( $items, $args ) {

    global $mts_options;

    if ( ( $args->theme_location === 'primary-menu' && $mts_options['mts_primary_menu_home_icon'] === '1' ) || ( $args->theme_location === 'secondary-menu' && $mts_options['mts_secondary_menu_home_icon'] === '1' ) ) {

        $home_link = '<li class="home-menu-item menu-item"><a href="' . home_url( '/' ) . '"><i class="fa fa-home"></i></a></li>';

        $items = $home_link . $items;
    }

    return $items;
}

/*-----------------------------------------------------------------------------------*/
/*  Enable Widgetized sidebar and Footer
/*-----------------------------------------------------------------------------------*/
if ( function_exists('register_sidebar') ) {   
    function mts_register_sidebars() {
        $mts_options = get_option(newstimes);
        
        // Default sidebar
        register_sidebar(array(
            'name' => 'Sidebar',
            'description'   => __( 'Default sidebar.', 'mythemeshop' ),
            'id' => 'sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        // Header Ad sidebar
        register_sidebar(array(
            'name' => 'Header Ad',
            'description'   => __( '728x90 Ad Area', 'mythemeshop' ),
            'id' => 'widget-header',
            'before_widget' => '<div id="%1$s" class="widget-header">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        // Top level footer widget areas
        if (!empty($mts_options['mts_top_footer'])) {
            if (empty($mts_options['mts_top_footer_num'])) $mts_options['mts_top_footer_num'] = 4;
            register_sidebars($mts_options['mts_top_footer_num'], array(
                'name' => __('Top Footer %d', 'mythemeshop'),
                'description'   => __( 'Appears at the top of the footer.', 'mythemeshop' ),
                'id' => 'footer-top',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ));
        }
        // Bottom level footer widget areas
        if (!empty($mts_options['mts_bottom_footer'])) {
            if (empty($mts_options['mts_bottom_footer_num'])) $mts_options['mts_bottom_footer_num'] = 3;
            register_sidebars($mts_options['mts_bottom_footer_num'], array(
                'name' => __('Bottom Footer %d', 'mythemeshop'),
                'description'   => __( 'Appears at the bottom of the footer.', 'mythemeshop' ),
                'id' => 'footer-bottom',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ));
        }
        
        // Custom sidebars
        if (!empty($mts_options['mts_custom_sidebars']) && is_array($mts_options['mts_custom_sidebars'])) {
            foreach($mts_options['mts_custom_sidebars'] as $sidebar) {
                if (!empty($sidebar['mts_custom_sidebar_id']) && !empty($sidebar['mts_custom_sidebar_id']) && $sidebar['mts_custom_sidebar_id'] != 'sidebar-') {
                    register_sidebar(array('name' => ''.$sidebar['mts_custom_sidebar_name'].'','id' => ''.sanitize_title(strtolower($sidebar['mts_custom_sidebar_id'])).'','before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
                }
            }
        }
    }
    
    add_action('widgets_init', 'mts_register_sidebars');
}

function mts_custom_sidebar() {
    $mts_options = get_option(newstimes);
    
    // Default sidebar
    $sidebar = 'Sidebar';

    if (is_home() && !empty($mts_options['mts_sidebar_for_home'])) $sidebar = $mts_options['mts_sidebar_for_home']; 
    if (is_single() && !empty($mts_options['mts_sidebar_for_post'])) $sidebar = $mts_options['mts_sidebar_for_post'];
    if (is_page() && !empty($mts_options['mts_sidebar_for_page'])) $sidebar = $mts_options['mts_sidebar_for_page'];
    
    // Archives
    if (is_archive() && !empty($mts_options['mts_sidebar_for_archive'])) $sidebar = $mts_options['mts_sidebar_for_archive'];
    if (is_category() && !empty($mts_options['mts_sidebar_for_category'])) $sidebar = $mts_options['mts_sidebar_for_category'];
    if (is_tag() && !empty($mts_options['mts_sidebar_for_tag'])) $sidebar = $mts_options['mts_sidebar_for_tag'];
    if (is_date() && !empty($mts_options['mts_sidebar_for_date'])) $sidebar = $mts_options['mts_sidebar_for_date'];
    if (is_author() && !empty($mts_options['mts_sidebar_for_author'])) $sidebar = $mts_options['mts_sidebar_for_author'];
    
    // Other
    if (is_search() && !empty($mts_options['mts_sidebar_for_search'])) $sidebar = $mts_options['mts_sidebar_for_search'];
    if (is_404() && !empty($mts_options['mts_sidebar_for_notfound'])) $sidebar = $mts_options['mts_sidebar_for_notfound'];
    
    // Page/post specific custom sidebar
    if (is_page() || is_single()) {
        wp_reset_postdata();
        global $post;
        $custom = get_post_meta($post->ID,'_mts_custom_sidebar',true);
        if (!empty($custom)) $sidebar = $custom;
    }

    return $sidebar;
}

/*-----------------------------------------------------------------------------------*/
/*  Load Widgets & Shortcodes
/*-----------------------------------------------------------------------------------*/
// Add the 125x125 Ad Block Custom Widget
include("functions/widget-ad125.php");

// Add the 300x250 Ad Block Custom Widget
include("functions/widget-ad300.php");

// Add the 728x90 Ad Block Custom Widget
include("functions/widget-ad728.php");

// Add the Latest Tweets Custom Widget
include("functions/widget-tweets.php");

// Add Recent Posts Widget
include("functions/widget-recentposts.php");

// Add Related Posts Widget
include("functions/widget-relatedposts.php");

// Add Author Posts Widget
include("functions/widget-authorposts.php");

// Add Popular Posts Widget
include("functions/widget-popular.php");

// Add Facebook Like box Widget
include("functions/widget-fblikebox.php");

// Add Google Plus box Widget
include("functions/widget-googleplus.php");

// Add Subscribe Widget
include("functions/widget-subscribe.php");

// Add Social Profile Widget
include("functions/widget-social.php");

// Add Category Posts Widget
include("functions/widget-catposts.php");

// Add Category Posts Slider Widget
include("functions/widget-postslider.php");

// Add Welcome message
include("functions/welcome-message.php");

// Theme Functions
include("functions/theme-actions.php");

// Plugin Activation
include("functions/class-tgm-plugin-activation.php");

if ( class_exists( 'wp_review_tab_widget' ) ) {

    add_action( 'widgets_init', 'unregister_wp_review_tab_widget', 15 );
    add_action( 'widgets_init', 'newstimes_review_tab_widget', 1 );
}

function unregister_wp_review_tab_widget() {
    unregister_widget( 'wp_review_tab_widget' );
}
function newstimes_review_tab_widget() {
    include_once( 'functions/widget-review-tab.php' );
    register_widget( 'nt_wp_review_tab_widget' );
}

if ( class_exists( 'wpt_widget' ) ) {

    add_action( 'widgets_init', 'unregister_wp_tab_widget', 15 );
    add_action( 'widgets_init', 'newstimes_tabs_widget', 1 );
}

function unregister_wp_tab_widget() {
    unregister_widget( 'wpt_widget' );
}
function newstimes_tabs_widget() {
    include("functions/widget-tabs.php");
    register_widget( 'newstimes_tabs_widget' );
}

add_action( 'widgets_init', 'mts_replace_wp_calendar_widget', 1 );
function mts_replace_wp_calendar_widget() {
    unregister_widget( 'WP_Widget_Calendar' );
    include("functions/widget-calendar.php");
    register_widget( 'newstimes_calendar_widget' );
}

// AJAX Contact Form - mts_contact_form()
include('functions/contact-form.php');

/*-----------------------------------------------------------------------------------*/
/*  Filters customize wp_title
/*-----------------------------------------------------------------------------------*/
function mts_wp_title( $title, $sep ) {
    global $paged, $page;

    if ( is_feed() )
        return $title;

    // Add the site name.
    $title .= get_bloginfo( 'name' );

    // Add the site description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ( $paged >= 2 || $page >= 2 )
        $title = "$title $sep " . sprintf( __( 'Page %s', 'mythemeshop' ), max( $paged, $page ) );

    return $title;
}
add_filter( 'wp_title', 'mts_wp_title', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/*  Javascsript
/*-----------------------------------------------------------------------------------*/
function mts_add_scripts() {
    $mts_options = get_option(newstimes);

    wp_enqueue_script('jquery');

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    
    wp_register_script('customscript', get_template_directory_uri() . '/js/customscript.js', true);
    if ($mts_options['mts_show_secondary_nav'] == '1') {
        $nav_menu = 'secondary';
    } else {
        $nav_menu = 'none';
    }
    wp_localize_script(
        'customscript',
        'mts_customscript',
        array(
            'responsive' => (empty($mts_options['mts_responsive']) ? false : true),
            'nav_menu' => $nav_menu
        )
    );
    wp_enqueue_script ('customscript');

    // Slider
    wp_register_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js');
    //if($mts_options['mts_featured_slider'] == '1' && !is_singular()) {
        wp_enqueue_script ('flexslider');
    //}

    // Parallax pages and posts
    if (is_singular()) {
        if ( basename( mts_get_post_template() ) == 'singlepost-parallax.php' || basename( get_page_template() ) == 'page-parallax.php' ) {
            wp_register_script ( 'jquery-parallax', get_template_directory_uri() . '/js/parallax.js' );
            wp_enqueue_script ( 'jquery-parallax' );
        }
    }   

    global $is_IE;
    if ($is_IE) {
        wp_register_script ('html5shim', "http://html5shim.googlecode.com/svn/trunk/html5.js");
        wp_enqueue_script ('html5shim');
    }
    
    
}
add_action('wp_enqueue_scripts','mts_add_scripts');
   
function mts_load_footer_scripts() {  
    $mts_options = get_option(newstimes);
    
    //Lightbox
    if($mts_options['mts_lightbox'] == '1') {
        wp_register_script('prettyPhoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', true);
        wp_enqueue_script('prettyPhoto');
    }
    
    //Sticky Nav
    if( $mts_options['mts_sticky_nav'] == '1' &&  $mts_options['mts_show_secondary_nav'] == '1' ) {
        wp_register_script('StickyNav', get_template_directory_uri() . '/js/sticky.js', true);
        wp_enqueue_script('StickyNav');
    }

    // Ajax Load More and Search Results
    wp_register_script('mts_ajax', get_template_directory_uri() . '/js/ajax.js', true);

    if(!empty($mts_options['mts_pagenavigation_type']) && $mts_options['mts_pagenavigation_type'] >= 2 && !is_singular() ) {
        wp_enqueue_script('mts_ajax');
    
        wp_register_script('historyjs', get_template_directory_uri() . '/js/history.js', true);
        wp_enqueue_script('historyjs');
        
        // Add parameters for the JS
        global $wp_query;
        $max = $wp_query->max_num_pages;
        $paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
        $autoload = ($mts_options['mts_pagenavigation_type'] == 3);
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_loadposts',
            array(
                'startPage' => $paged,
                'maxPages' => $max,
                'nextLink' => next_posts($max, false),
                'autoLoad' => $autoload,
                'i18n_loadmore' => __('Load More Posts', 'mythemeshop'),
                'i18n_nomore' => __('No more posts.', 'mythemeshop'),
                'i18n_loading' => __('Loading posts ...', 'mythemeshop')
            )
        );
    }
    if(!empty($mts_options['mts_ajax_search'])) {
        wp_enqueue_script('mts_ajax');
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_search',
            array(
                'url' => admin_url('admin-ajax.php'),
                'ajax_search' => '1'
            )
        );
    }

    if ( is_home() && ! is_paged() ) {
        if( $mts_options['mts_featured_section_1_ajax_filter'] === '1' || $mts_options['mts_featured_section_1_ajax_pagination'] === '1' ) {
            wp_enqueue_script('mts_ajax');
            wp_localize_script(
                'mts_ajax',
                'mts_ajax_fs1',
                array(
                    'mts_nonce' => wp_create_nonce( 'mts_nonce' ),
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'filter' => $mts_options['mts_featured_section_1_ajax_filter'] === '1',
                    'pagination' => $mts_options['mts_featured_section_1_ajax_pagination'] === '1'
                )
            );
        }
        if( $mts_options['mts_featured_section_2_ajax_load_more'] === '1' ) {
            wp_enqueue_script('mts_ajax');
            wp_localize_script(
                'mts_ajax',
                'mts_ajax_fs2',
                array(
                    'mts_nonce_2' => wp_create_nonce( 'mts_nonce_2' ),
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'i18n_loadmore' => __('Load More Posts', 'mythemeshop'),
                    'i18n_nomore' => __('No more posts.', 'mythemeshop'),
                    'i18n_loading' => __('Loading posts ...', 'mythemeshop')
                )
            );
        }
    }

}
add_action('wp_footer', 'mts_load_footer_scripts');

if(!empty($mts_options['mts_ajax_search'])) {
    add_action('wp_ajax_mts_search', 'ajax_mts_search');
    add_action('wp_ajax_nopriv_mts_search', 'ajax_mts_search');
}

function mts_nojs_js_class() {
    echo '<script type="text/javascript">document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/,\'js\');</script>';
}
add_action('wp_head', 'mts_nojs_js_class');

/*-----------------------------------------------------------------------------------*/
/* Enqueue CSS
/*-----------------------------------------------------------------------------------*/
function mts_enqueue_css() {
    $mts_options = get_option(newstimes);

    // Slider
    wp_register_style('flexslider', get_template_directory_uri() . '/css/flexslider.css', 'style');
    //if($mts_options['mts_featured_slider'] == '1' && !is_singular()) {
        wp_enqueue_style('flexslider');
    //}
    
    // Lightbox
    if($mts_options['mts_lightbox'] == '1') {
        wp_register_style('prettyPhoto', get_template_directory_uri() . '/css/prettyPhoto.css', 'style');
        wp_enqueue_style('prettyPhoto');
    }
    
    // Font Awesome
    wp_register_style('fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', 'style');
    wp_enqueue_style('fontawesome');
    
    wp_enqueue_style('stylesheet', get_stylesheet_directory_uri() . '/style.css', 'style');
    
    // Responsive
    if($mts_options['mts_responsive'] == '1') {
        wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', 'style');
    }

    // If WP Shortcode plugin is active, remove its styles and replace it with theme styles
    if ( in_array( 'wp-shortcode/wp-shortcode.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

        wp_deregister_style('mts_wpshortcodes');
        wp_dequeue_style('mts_wpshortcodes');

        wp_register_style('mts_wpshortcodes', get_template_directory_uri() . '/css/wp-shortcode.css', 'style');
        wp_enqueue_style('mts_wpshortcodes');
    }

    $mts_header_bg = '';
    if ($mts_options['mts_header_bg_pattern_upload'] != '') {
        $mts_header_bg = $mts_options['mts_header_bg_pattern_upload'];
    } else {
        if($mts_options['mts_header_bg_pattern'] != '') {
            $mts_header_bg = get_template_directory_uri().'/images/'.$mts_options['mts_header_bg_pattern'].'.png';
        }
    }
    
    $mts_bg = '';
    if ($mts_options['mts_bg_pattern_upload'] != '') {
        $mts_bg = $mts_options['mts_bg_pattern_upload'];
    } else {
        if(!empty($mts_options['mts_bg_pattern'])) {
            $mts_bg = get_template_directory_uri().'/images/'.$mts_options['mts_bg_pattern'].'.png';
        }
    }

    $mts_top_footer_bg = '';
    if ($mts_options['mts_top_footer_bg_pattern_upload'] != '') {
        $mts_top_footer_bg = $mts_options['mts_top_footer_bg_pattern_upload'];
    } else {
        if($mts_options['mts_top_footer_bg_pattern'] != '') {
            $mts_top_footer_bg = get_template_directory_uri().'/images/'.$mts_options['mts_top_footer_bg_pattern'].'.png';
        }
    }
    $mts_bottom_footer_bg = '';
    if ($mts_options['mts_bottom_footer_bg_pattern_upload'] != '') {
        $mts_bottom_footer_bg = $mts_options['mts_bottom_footer_bg_pattern_upload'];
    } else {
        if($mts_options['mts_bottom_footer_bg_pattern'] != '') {
            $mts_bottom_footer_bg = get_template_directory_uri().'/images/'.$mts_options['mts_bottom_footer_bg_pattern'].'.png';
        }
    }

    $mts_sclayout = '';
    $mts_shareit_left = '';
    $mts_shareit_right = '';
    $mts_author = '';
    $mts_header_section = '';
    if (is_page() || is_single()) {
        $mts_sidebar_location = get_post_meta( get_the_ID(), '_mts_sidebar_location', true );
    } else {
        $mts_sidebar_location = '';
    }
    if ($mts_sidebar_location != 'right' && ($mts_options['mts_layout'] == 'sclayout' || $mts_sidebar_location == 'left')) {
        $mts_sclayout = '.article { float: right;}
        .sidebar.c-4-12 { float: left; padding-right: 0; }';
        if($mts_options['mts_social_button_position'] == '3') {
            $mts_shareit_right = '.shareit { margin: 0 752px 0; border-left: 0; }';
        }
    }
    if ($mts_options['mts_header_section2'] == '0') {
        $mts_header_section = '.logo-wrap, .widget-header { display: none; }
        #navigation { border-top: 0; }
        #header { min-height: 47px; }';
    }
    if($mts_options['mts_social_button_position'] == '3') {
        $mts_shareit_left = '.shareit { top: 415px; left: auto; z-index: 0; margin: 0 0 0 -120px; width: 90px; position: fixed; overflow: hidden; padding: 5px; border:none; border-right: 0;}
        .share-item {margin: 2px;}';
    }
    if($mts_options['mts_single_post_layout'] == 'rclayout') {$mts_shareit_left .= '.shareit { margin: 0 0 0 -250px;}';}
    if($mts_options['mts_author_comment'] == '1') {
        $mts_author = '.bypostauthor:after { content: "'.__('Author','mythemeshop').'"; position: absolute; right: 0px; top: 0px; padding: 0px 10px; background: #444; color: #FFF; }';
    }
    $mts_single_layout = '';
    if($mts_options['mts_single_post_layout'] == 'crlayout') {
        $mts_single_layout = '
            .post-single-content-inner { float: left;}
            .singleleft { float: right; margin-right: 0; }';
    }elseif($mts_options['mts_single_post_layout'] == 'cbrlayout' || $mts_options['mts_single_post_layout'] == 'clayout') {
        $mts_single_layout = '
            .post-single-content-inner { width: 100%; }';
    }

    $custom_css = "
        body {background-color:{$mts_options['mts_bg_color']};}
        body {background-image: url({$mts_bg});}
        .main-header {background-color:{$mts_options['mts_header_bg_color']}; background-image: url({$mts_header_bg});}
        footer-carousel-wrap {background-color:{$mts_options['mts_footer_carousel_bg_color']}; }
        footer {background-color:{$mts_options['mts_top_footer_bg_color']}; background-image: url({$mts_top_footer_bg});}
        .bottom-footer-widgets {background-color:{$mts_options['mts_bottom_footer_bg_color']}; background-image: url({$mts_bottom_footer_bg});}
        footer > .copyrights {background-color:{$mts_options['mts_copyrights_bg_color']};}

        nav a#pull,.flex-direction-nav li a,#top-navigation li:hover a, #header nav#top-navigation ul ul li,#navigation .menu,#move-to-top,.mts-subscribe input[type='submit'],input[type='submit'],#commentform input#submit,.contactform #submit,.pagination a,.fs-pagination a,.header-search .ajax-search-results-container,#load-posts a,#fs2_load_more_button,.dark-style .post-data,#wp-calendar td a,#wp-calendar caption,#wp-calendar #prev a:before,#wp-calendar #next a:before, .tagcloud a, #tags-tab-content a {background: {$mts_options['mts_color_scheme']};}
        .slider1 .vertical-small .post-data:after,.featured-section-1-1 .vertical-small .post-data:after,.featured-section-2-1 .vertical-small .post-data:after,.dark-style.vertical-small .post-data:after {border-color: {$mts_options['mts_color_scheme']} transparent;}
        #footer-post-carousel .post-data:after{border-color: transparent {$mts_options['mts_color_scheme']};}
        .header-search #s,nav a.toggle-mobile-menu,#mobile-menu-wrapper,.tab_widget ul.wps_tabs li,#top-navigation .menu ul .current-menu-item > a {background: {$mts_options['mts_color_scheme']} !important;}
        .pace .pace-progress,.mts-subscribe input[type='submit']:hover,#mobile-menu-wrapper ul li a:hover,.breadcrumb .root a,input[type='submit']:hover,#commentform input#submit:hover,.contactform #submit:hover,.flex-direction-nav li a:hover,#move-to-top:hover,.ajax-search-meta .results-link:hover,#navigation li:hover a,#header nav#navigation ul ul li,.header-search .fa-search.active,.widget_nav_menu .menu-item a:hover,.tagcloud a:hover, #tags-tab-content a:hover,.readMore a:hover,.thecategory a,.post-box .review-total-only,.pagination a:hover,#load-posts a:hover, #fs2_load_more_button:hover,.fs-filter-navigation a:hover,.fs-filter-navigation a.current,.slidertitle a,.active > a > .menu-caret,#wp-calendar td a:hover,#wp-calendar #today,#wp-calendar #prev:hover a:before,#wp-calendar #next:hover a:before,  #searchsubmit {background: {$mts_options['mts_color_scheme2']};}
        .home .menu .home-menu-item a,.menu .current-menu-item > a,.widget_wpt .tab_title.selected a,.widget_wp_review_tab .tab_title.selected a {background: {$mts_options['mts_color_scheme2']} !important;}
        #wp-calendar thead th.today {border-bottom-color: {$mts_options['mts_color_scheme2']};}

        a:hover,.title a:hover,.post-data .post-title:hover,.post-title a:hover,.post-info a:hover,.entry-content a,.textwidget a,.reply a,.comm,.fn a,.comment-reply-link, .entry-content .singleleft a:hover {color:{$mts_options['mts_color_scheme2']};}
        .post-box .review-total-only .review-result-wrapper .review-result i {color:{$mts_options['mts_color_scheme2']} !important;}

        {$mts_sclayout}
        {$mts_shareit_left}
        {$mts_shareit_right}
        {$mts_author}
        {$mts_header_section}
        {$mts_single_layout}
        {$mts_options['mts_custom_css']}
            ";
    wp_add_inline_style( 'stylesheet', $custom_css );
    
}
add_action('wp_enqueue_scripts', 'mts_enqueue_css', 99);

/*-----------------------------------------------------------------------------------*/
/*  Filters that allow shortcodes in Text Widgets
/*-----------------------------------------------------------------------------------*/
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');
add_filter('the_content_rss', 'do_shortcode');

/*-----------------------------------------------------------------------------------*/
/*  Custom Comments template
/*-----------------------------------------------------------------------------------*/
function mts_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment; ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment-box">
            <div class="comment-author vcard clearfix">
                <?php echo get_avatar( $comment->comment_author_email, 115 ); ?>
                <?php printf(__('<span class="fn">%s</span>', 'mythemeshop'), get_comment_author_link()); ?> 
                <?php $mts_options = get_option(newstimes); if($mts_options['mts_comment_date'] == '1') { ?>
                    <span class="ago"><?php comment_date(get_option( 'date_format' )); ?></span>
                <?php } ?>
                <span class="comment-meta">
                    <?php edit_comment_link(__('(Edit)', 'mythemeshop'),'  ',''); ?>
                    <?php
                    $args['reply_text'] = '<i class="fa fa-mail-forward"></i> '. __('Reply', 'mythemeshop');
                    comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
                    ?>
                </span>
            </div>
            <?php if ($comment->comment_approved == '0') : ?>
                <em><?php _e('Your comment is awaiting moderation.', 'mythemeshop') ?></em>
                <br />
            <?php endif; ?>
            <div class="commentmetadata">
                <?php comment_text() ?>
            </div>
        </div>
    </li>
<?php }

/*-----------------------------------------------------------------------------------*/
/*  Excerpt
/*-----------------------------------------------------------------------------------*/

// Increase max length
function mts_excerpt_length( $length ) {
    return 100;
}
add_filter( 'excerpt_length', 'mts_excerpt_length', 20 );

// Remove [...] and shortcodes
function mts_custom_excerpt( $output ) {
  return preg_replace( '/\[[^\]]*]/', '', $output );
}
add_filter( 'get_the_excerpt', 'mts_custom_excerpt' );

// Truncate string to x letters/words
function mts_truncate( $str, $length = 40, $units = 'letters', $ellipsis = '&nbsp;&hellip;' ) {
    if ( $units == 'letters' ) {
        if ( mb_strlen( $str ) > $length ) {
            return mb_substr( $str, 0, $length ) . $ellipsis;
        } else {
            return $str;
        }
    } else {
        $words = explode( ' ', $str );
        if ( count( $words ) > $length ) {
            return implode( " ", array_slice( $words, 0, $length ) ) . $ellipsis;
        } else {
            return $str;
        }
    }
}

if ( ! function_exists( 'mts_excerpt' ) ) {
    function mts_excerpt( $limit = 40 ) {
      return mts_truncate( get_the_excerpt(), $limit, 'words' );
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Remove more link from the_content and use custom read more
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_content_more_link', 'mts_remove_more_link', 10, 2 );
function mts_remove_more_link( $more_link, $more_link_text ) {
    return '';
}
// shorthand function to check for more tag in post
function mts_post_has_moretag() {
    global $post;
    return strpos($post->post_content, '<!--more-->');
}

if ( ! function_exists( 'mts_readmore' ) ) {
    function mts_readmore() {
        ?>
        <div class="readMore">
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="nofollow">
                <?php _e('Continue','mythemeshop'); ?>
            </a>
        </div>
        <?php 
    }
}

/*-----------------------------------------------------------------------------------*/
/* nofollow to next/previous links
/*-----------------------------------------------------------------------------------*/
function mts_pagination_add_nofollow($content) {
    return 'rel="nofollow"';
}
add_filter('next_posts_link_attributes', 'mts_pagination_add_nofollow' );
add_filter('previous_posts_link_attributes', 'mts_pagination_add_nofollow' );

/*-----------------------------------------------------------------------------------*/
/* Nofollow to category links
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_category', 'mts_add_nofollow_cat' ); 
function mts_add_nofollow_cat( $text ) {
    $text = str_replace('rel="category tag"', 'rel="nofollow"', $text); return $text;
}

/*-----------------------------------------------------------------------------------*/ 
/* nofollow post author link
/*-----------------------------------------------------------------------------------*/
add_filter('the_author_posts_link', 'mts_nofollow_the_author_posts_link');
function mts_nofollow_the_author_posts_link ($link) {
    return str_replace('<a href=', '<a rel="nofollow" href=',$link); 
}

/*-----------------------------------------------------------------------------------*/ 
/* nofollow to reply links
/*-----------------------------------------------------------------------------------*/
function mts_add_nofollow_to_reply_link( $link ) {
    return str_replace( '")\'>', '")\' rel=\'nofollow\'>', $link );
}
add_filter( 'comment_reply_link', 'mts_add_nofollow_to_reply_link' );

/*-----------------------------------------------------------------------------------*/
/* removes the WordPress version from your header for security
/*-----------------------------------------------------------------------------------*/
function mts_remove_wpversion() {
    return '<!--Theme by MyThemeShop.com-->';
}
add_filter('the_generator', 'mts_remove_wpversion');
    
/*-----------------------------------------------------------------------------------*/
/* Removes Trackbacks from the comment count
/*-----------------------------------------------------------------------------------*/
add_filter('get_comments_number', 'mts_comment_count', 0);
function mts_comment_count( $count ) {
    if ( ! is_admin() ) {
        global $id;
        // Was gettin' warning on php 5
        //$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
        $comments = get_comments('status=approve&post_id=' . $id);
        $comments_by_type = separate_comments($comments);
        return count($comments_by_type['comment']);
    } else {
        return $count;
    }
}

/*-----------------------------------------------------------------------------------*/
/* adds a class to the post if there is a thumbnail
/*-----------------------------------------------------------------------------------*/
function has_thumb_class($classes) {
    global $post;
    if( has_post_thumbnail( $post->ID ) ) { $classes[] = 'has_thumb'; }
        return $classes;
}
add_filter('post_class', 'has_thumb_class');


/*-----------------------------------------------------------------------------------*/ 
/* AJAX Search results
/*-----------------------------------------------------------------------------------*/
function ajax_mts_search() {
    $query = $_REQUEST['q'];//esc_html($_REQUEST['q']);
    // No need to esc as WP_Query escapes data before performing the database query
    $search_query = new WP_Query(array('s' => $query, 'posts_per_page' => 3, 'post_status' => 'publish'));
    //$search_count = new WP_Query(array('s' => $query, 'posts_per_page' => -1));
    //$search_count = $search_count->post_count;
    if (!empty($query) && $search_query->have_posts()) : 
        //echo '<h5>Results for: '. $query.'</h5>';
        echo '<ul class="ajax-search-results">';
        while ($search_query->have_posts()) : $search_query->the_post();
            ?><li>
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('smallthumb',array('title' => '')); ?>
                    <?php the_title(); ?>
                </a>
                <div class="meta">
                    <span class="thetime"><?php the_time('F j, Y'); ?></span>
                </div> <!-- / .meta -->
            </li>   
            <?php
        endwhile;
        echo '</ul>';
        echo '<div class="ajax-search-meta"><a href="'.get_search_link($query).'" class="results-link">Show all results</a></div>';
    else:
        echo '<div class="no-results">'.__('No results found.', 'mythemeshop').'</div>';
    endif;
        
    exit; // required for AJAX in WP
}
/*-----------------------------------------------------------------------------------*/
/* Redirect feed to feedburner
/*-----------------------------------------------------------------------------------*/

if ( $mts_options['mts_feedburner'] != '') {
function mts_rss_feed_redirect() {
    $mts_options = get_option(newstimes);
    global $feed;
    $new_feed = $mts_options['mts_feedburner'];
    if (!is_feed()) {
            return;
    }
    if (preg_match('/feedburner/i', $_SERVER['HTTP_USER_AGENT'])){
            return;
    }
    if ($feed != 'comments-rss2') {
            if (function_exists('status_header')) status_header( 302 );
            header("Location:" . $new_feed);
            header("HTTP/1.1 302 Temporary Redirect");
            exit();
    }
}
add_action('template_redirect', 'mts_rss_feed_redirect');
}

/*-----------------------------------------------------------------------------------*/
/* add <!-- next-page --> button to tinymce
/*-----------------------------------------------------------------------------------*/
add_filter('mce_buttons','wysiwyg_editor');
function wysiwyg_editor($mce_buttons) {
   $pos = array_search('wp_more',$mce_buttons,true);
   if ($pos !== false) {
       $tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
       $tmp_buttons[] = 'wp_page';
       $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
   }
   return $mce_buttons;
}

/*-----------------------------------------------------------------------------------*/
/*  Custom Gravatar Support
/*-----------------------------------------------------------------------------------*/
function mts_custom_gravatar( $avatar_defaults ) {
    $mts_avatar = get_template_directory_uri() . '/images/gravatar.png';
    $avatar_defaults[$mts_avatar] = 'Custom Gravatar (/images/gravatar.png)';
    return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'mts_custom_gravatar' );

/*-----------------------------------------------------------------------------------*/
/*  Sidebar Selection meta box
/*-----------------------------------------------------------------------------------*/
function mts_add_sidebar_metabox() {
    $screens = array('post', 'page');
    foreach ($screens as $screen) {
        add_meta_box(
            'mts_sidebar_metabox',                  // id
            __('Sidebar', 'mythemeshop'),    // title
            'mts_inner_sidebar_metabox',            // callback
            $screen,                                // post_type
            'side',                                 // context (normal, advanced, side)
            'high'                               // priority (high, core, default, low)
                                                    // callback args ($post passed by default)
        );
    }
}
add_action('add_meta_boxes', 'mts_add_sidebar_metabox');


/**
 * Print the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function mts_inner_sidebar_metabox($post) {
    global $wp_registered_sidebars;
    
    // Add an nonce field so we can check for it later.
    wp_nonce_field('mts_inner_sidebar_metabox', 'mts_inner_sidebar_metabox_nonce');
    
    /*
    * Use get_post_meta() to retrieve an existing value
    * from the database and use the value for the form.
    */
    $custom_sidebar = get_post_meta( $post->ID, '_mts_custom_sidebar', true );
    $sidebar_location = get_post_meta( $post->ID, '_mts_sidebar_location', true );

    // Select custom sidebar from dropdown
    echo '<select name="mts_custom_sidebar" style="margin-bottom: 10px;">';
    echo '<option value="" '.selected('', $custom_sidebar).'>Default</option>';
    
    // Exclude built-in sidebars
    $hidden_sidebars = array('sidebar', 'footer-top-1', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'footer-bottom-1', 'footer-bottom-2', 'footer-bottom-3', 'footer-bottom-4');    
    
    foreach ($wp_registered_sidebars as $sidebar) {
        if (!in_array($sidebar['id'], $hidden_sidebars)) {
            echo '<option value="'.esc_attr($sidebar['id']).'" '.selected($sidebar['id'], $custom_sidebar, false).'>'.$sidebar['name'].'</option>';
        }
    }
    echo '</select><br />';
    
    // Select single layout (left/right sidebar)
    echo '<label for="mts_sidebar_location_default" style="display: inline-block; margin-right: 20px;"><input type="radio" name="mts_sidebar_location" id="mts_sidebar_location_default" value=""'.checked('', $sidebar_location, false).'>Default side</label>';
    echo '<label for="mts_sidebar_location_left" style="display: inline-block; margin-right: 20px;"><input type="radio" name="mts_sidebar_location" id="mts_sidebar_location_left" value="left"'.checked('left', $sidebar_location, false).'>Left</label>';
    echo '<label for="mts_sidebar_location_right" style="display: inline-block; margin-right: 20px;"><input type="radio" name="mts_sidebar_location" id="mts_sidebar_location_right" value="right"'.checked('right', $sidebar_location, false).'>Right</label>';
     
    //debug
    global $wp_meta_boxes;
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function mts_save_custom_sidebar( $post_id ) {
    
    /*
    * We need to verify this came from our screen and with proper authorization,
    * because save_post can be triggered at other times.
    */
    
    // Check if our nonce is set.
    if ( ! isset( $_POST['mts_inner_sidebar_metabox_nonce'] ) )
    return $post_id;
    
    $nonce = $_POST['mts_inner_sidebar_metabox_nonce'];
    
    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'mts_inner_sidebar_metabox' ) )
      return $post_id;
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;
    
    // Check the user's permissions.
    if ( 'page' == $_POST['post_type'] ) {
    
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
    
    } else {
    
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }
    
    /* OK, its safe for us to save the data now. */
    
    // Sanitize user input.
    $sidebar_name = sanitize_text_field( $_POST['mts_custom_sidebar'] );
    $sidebar_location = sanitize_text_field( $_POST['mts_sidebar_location'] );
    
    // Update the meta field in the database.
    update_post_meta( $post_id, '_mts_custom_sidebar', $sidebar_name );
    update_post_meta( $post_id, '_mts_sidebar_location', $sidebar_location );
}
add_action( 'save_post', 'mts_save_custom_sidebar' );

/*-----------------------------------------------------------------------------------*/
/*  Post Template Selection meta box
/*-----------------------------------------------------------------------------------*/
function mts_add_posttemplate_metabox() {
    add_meta_box(
        'mts_posttemplate_metabox',         // id
        __('Template', 'mythemeshop'),      // title
        'mts_inner_posttemplate_metabox',   // callback
        'post',                             // post_type
        'side',                             // context (normal, advanced, side)
        'high'                              // priority (high, core, default, low)
    );
}
add_action('add_meta_boxes', 'mts_add_posttemplate_metabox');

/**
 * Print the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function mts_inner_posttemplate_metabox($post) {
    global $wp_registered_sidebars;
    
    // Add an nonce field so we can check for it later.
    wp_nonce_field('mts_inner_posttemplate_metabox', 'mts_inner_posttemplate_metabox_nonce');
    
    /*
    * Use get_post_meta() to retrieve an existing value
    * from the database and use the value for the form.
    */
    $posttemplate = get_post_meta( $post->ID, '_mts_posttemplate', true );

    // Select post template
    echo '<select name="mts_posttemplate" style="margin-bottom: 10px;">';
    echo '<option value="" '.selected('', $posttemplate).'>'.__('Default Post Template', 'mythemeshop').'</option>';
    echo '<option value="parallax" '.selected('parallax', $posttemplate).'>'.__('Parallax Template', 'mythemeshop').'</option>';
    echo '</select><br />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function mts_save_posttemplate( $post_id ) {
    
    /*
    * We need to verify this came from our screen and with proper authorization,
    * because save_post can be triggered at other times.
    */
    
    // Check if our nonce is set.
    if ( ! isset( $_POST['mts_inner_posttemplate_metabox_nonce'] ) )
    return $post_id;
    
    $nonce = $_POST['mts_inner_posttemplate_metabox_nonce'];
    
    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'mts_inner_posttemplate_metabox' ) )
      return $post_id;
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;
    
    // Check the user's permissions.
    if ( 'page' == $_POST['post_type'] ) {
    
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
    
    } else {
    
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }
    
    /* OK, its safe for us to save the data now. */
    
    // Sanitize user input.
    $posttemplate = sanitize_text_field( $_POST['mts_posttemplate'] );
    
    // Update the meta field in the database.
    update_post_meta( $post_id, '_mts_posttemplate', $posttemplate );
}
add_action( 'save_post', 'mts_save_posttemplate' );

// Related function: mts_get_posttemplate( $single_template ) in functions.php

/*-----------------------------------------------------------------------------------*/
/*  Alternative post templates
/*-----------------------------------------------------------------------------------*/
function mts_get_post_template( $default = 'default' ) {
    global $post;
    $single_template = $default;
    $posttemplate = get_post_meta( $post->ID, '_mts_posttemplate', true );
    
    if ( empty( $posttemplate ) || ! is_string( $posttemplate ) )
        return $single_template;
    
    if ( file_exists( dirname( __FILE__ ) . '/singlepost-'.$posttemplate.'.php' ) ) {
        $single_template = dirname( __FILE__ ) . '/singlepost-'.$posttemplate.'.php';
    }
    
    return $single_template;
}
function mts_set_post_template( $single_template ) {
     return mts_get_post_template( $single_template );
}
add_filter( 'single_template', 'mts_set_post_template' );


/*-----------------------------------------------------------------------------------*/
/*  WP Review
/*-----------------------------------------------------------------------------------*/

// Colorize WP Review total using filter
function mts_color_review_total($content, $id, $type, $total) {
    $mts_options = get_option(newstimes);
    $color = $mts_options['mts_color_scheme2'];

    if ($type == 'star') {
        $content = preg_replace('/"review-type-[^"]+"/', '$0 style="color: '.$color.';"', $content);
    } else {
        $content = preg_replace('/"review-type-[^"]+"/', '$0 style="color:#fff;background-color: '.$color.';"', $content);
    }
    return $content;
}
add_filter('wp_review_show_total', 'mts_color_review_total', 10, 4);

// Set default colors for new reviews
function new_default_review_colors($colors) {
    $colors = array(
        'color' => '#e52329',
        'fontcolor' => '#444',
        'bgcolor1' => '#fff',
        'bgcolor2' => '#fff',
        'bordercolor' => '#fff'
    );
  return $colors;
}
add_filter( 'wp_review_default_colors', 'new_default_review_colors' );
 
// Set default location for new reviews
function new_default_review_location($position) {
  $position = 'top';
  return $position;
}
add_filter( 'wp_review_default_location', 'new_default_review_location' );


/*-----------------------------------------------------------------------------------*/
/*  Required theme plugins activation
/*-----------------------------------------------------------------------------------*/
add_action( 'tgmpa_register', 'mts_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 */
function mts_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'      => 'WP Review ',
            'slug'      => 'wp-review',
            'required'  => false,
        ),

        array(
            'name'      => 'WP Shortcode ',
            'slug'      => 'wp-shortcode',
            'required'  => false,
        ),

        array(
            'name'      => 'WP Tab Widget ',
            'slug'      => 'wp-tab-widget',
            'required'  => false,
        ),
        
        array(
            'name'      => 'MyThemeShop Connect ',
            'slug'      => 'mythemeshop-connect',
            'required'  => false,
        ),

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'id'           => 'mts-plugins',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'mts-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'mythemeshop' ),
            'menu_title'                      => __( 'Install Plugins', 'mythemeshop' ),
            'installing'                      => __( 'Installing Plugin: %s', 'mythemeshop' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'mythemeshop' ),
            //'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'mythemeshop' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'mythemeshop' ), // %1$s = plugin name(s).
            //'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'mythemeshop' ), // %1$s = plugin name(s).
            //'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'mythemeshop' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'mythemeshop' ), // %1$s = plugin name(s).
            //'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'mythemeshop' ), // %1$s = plugin name(s).
            //'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'mythemeshop' ), // %1$s = plugin name(s).
            //'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'mythemeshop' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'mythemeshop' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'mythemeshop' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'mythemeshop' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'mythemeshop' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'mythemeshop' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );

}



// Add extra body classes
function newstimes_body_classes( $classes ) {

    global $mts_options;

    if ( $mts_options['mts_show_secondary_nav'] !== '1' ) {
        $classes[] = 'secondary-menu-disabled';
    }

    if ( is_home() && !is_paged() ) {

        if ( ( $mts_options['mts_homepage_fs_layout'] === '1' || $mts_options['mts_homepage_fs_layout'] === '2' ) && $mts_options['mts_featured_slider'] === '1' ) {
                $classes[] = 'featured-section-1-full-width-1';
        }

        if ( $mts_options['mts_homepage_fs_layout'] === '1' && $mts_options['mts_featured_section_1'] === '1' ) {
            $classes[] = 'featured-section-2-full-width-1';
        }
    }

    return $classes;
}
add_filter( 'body_class', 'newstimes_body_classes' );

// Helper function for mixed layouts ( used in the loop )

add_filter( 'post_thumbnail_html', 'mts_post_thumbnail_html', 20, 5 );
function mts_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

    // fallback image
    if ( ! $html ) {

        $html = '<img src="'.get_template_directory_uri().'/images/nothumb-'.$size.'.png" class="attachment-'.$size.' wp-post-image" alt="'.get_the_title($post_id).'">';
    }

    $play_icon_html = '';
    $format = get_post_format( $post_id );
    if ( 'video' === $format ) {
        $play_icon_html  = '<span class="play-icon"><i class="fa fa-play"></i></span>';
    }

    return $html . $play_icon_html;
}

/*------------[ Ajax - featured section 1 ]-------------*/
add_action('wp_ajax_mts_filter_fs1_posts', 'mts_filter_fs1_posts');
add_action('wp_ajax_nopriv_mts_filter_fs1_posts', 'mts_filter_fs1_posts');

/* Function to display category filter and/or pagination buttons */
function mts_f1_ajax_nav( $cats, $filter = true, $pagination = true ) {

    if ( !$filter && !$pagination ) { return; }

    $count_cats = count( $cats );
    ?>
    <div class="fs-filter-navigation">
        <?php if ( $filter &&  $count_cats > 1 ) { ?>
        <ul class="fs-category-filter">
            <li><a href="#" data-cat-id="all" class="fs-category-button current">All</a></li>
            <?php foreach  ( $cats as $cat ) { ?>
            <li><a href="#" data-cat-id="<?php echo $cat->cat_ID; ?>" class="fs-category-button"><?php echo $cat->name; ?></a></li>
            <?php } ?>
        </ul>
        <?php } ?>
        <?php if ( $pagination ) { ?>
        <div class="fs-pagination">
            <a href="#" class="previous"><i class="fa fa-chevron-left"></i></a>
            <a href="#" class="next"><i class="fa fa-chevron-right"></i></a>
        </div>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
<?php
}

/* Posts */
function mts_filter_fs1_posts() {

    if( !isset( $_POST['mts_nonce'] ) || !wp_verify_nonce( $_POST['mts_nonce'], 'mts_nonce' ) ) {
        die('Permission denied');
    }

    $cat_id = $_POST['cat_id'];
    $page = intval($_POST['page']);
    if ($page < 1) $page = 1;

    global $mts_options;

    $homepage_fs_layout = $mts_options['mts_homepage_fs_layout'];// 1, 2, 3

    $fs1_cat = ( $cat_id === 'all' ) ? implode(",", $mts_options['mts_featured_section_1_cat']) : $cat_id;

    // number of posts and thumb size for first "checker dark" layout
    $fs1_post_num = '3';
    $fs1_loop_thumb = ($homepage_fs_layout === '1') ? 'featured' : 'featured2';
        
    $fs1_query = new WP_Query('cat='.$fs1_cat.'&posts_per_page='.$fs1_post_num.'&paged='. $page.'&post_status=publish');
    $fs1_count = 1; if ($fs1_query->have_posts()) :
    ?>
    <input type="hidden" class="page_num" name="page_num" value="<?php echo $page; ?>" />
    <input type="hidden" class="max_pages" name="max_pages" value="<?php echo $fs1_query->max_num_pages; ?>" />
    <?php
    while ($fs1_query->have_posts()) : $fs1_query->the_post(); ?>
        <article class="post-box latestPost vertical-small dark-style clear-none <?php if ( $fs1_count % 3 === 2 ) echo'image-bottom'; ?>">
            <?php if ( $fs1_count % 3 === 2 ) { ?>
                <div class="post-data">
                    <div class="post-data-container">
                        <header>
                            <h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                            <?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
                                <div class="post-info">
                                    <?php if(isset($mts_options['mts_home_headline_meta_info']['date']) == '1') { ?>
                                        <span class="thetime updated"><?php the_time( get_option( 'date_format' ) ); ?></span>
                                    <?php } ?>
                                    <?php if(isset($mts_options['mts_home_headline_meta_info']['comment']) == '1') { ?>
                                        <span class="thecomment"><i class="fa fa-comments"></i> <a rel="nofollow" href="<?php comments_link(); ?>"><?php echo comments_number('0','1','%');?></a></span>
                                    <?php } ?>
                                    <?php if(isset($mts_options['mts_home_headline_meta_info']['author']) == '1') { ?>
                                        <span class="theauthor"><i class="fa fa-user"></i> <?php the_author_posts_link(); ?></span>
                                    <?php } ?>
                                    <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'review-total-only'); ?>
                                </div>
                            <?php } ?>
                        </header>
                        <div class="post-excerpt">
                            <?php echo mts_excerpt(15); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="post-img">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="nofollow">
                    <?php the_post_thumbnail($fs1_loop_thumb,array('title' => '')); ?>
                </a>
            </div>
            <?php if ( $fs1_count % 3 !== 2 ) { ?>
            <div class="post-data">
                <div class="post-data-container">
                    <header>
                        <h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                        <?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
                            <div class="post-info">
                                <?php if(isset($mts_options['mts_home_headline_meta_info']['date']) == '1') { ?>
                                    <span class="thetime updated"><?php the_time( get_option( 'date_format' ) ); ?></span>
                                <?php } ?>
                                <?php if(isset($mts_options['mts_home_headline_meta_info']['comment']) == '1') { ?>
                                    <span class="thecomment"><i class="fa fa-comments"></i> <a rel="nofollow" href="<?php comments_link(); ?>"><?php echo comments_number('0','1','%');?></a></span>
                                <?php } ?>
                                <?php if(isset($mts_options['mts_home_headline_meta_info']['author']) == '1') { ?>
                                    <span class="theauthor"><i class="fa fa-user"></i> <?php the_author_posts_link(); ?></span>
                                <?php } ?>
                                <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'review-total-only'); ?>
                            </div>
                        <?php } ?>
                    </header>
                    <div class="post-excerpt">
                        <?php echo mts_excerpt(15); ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </article><!--.post-box-->
    <?php

    $fs1_count++; endwhile; endif; wp_reset_postdata();

    die();
}

?>