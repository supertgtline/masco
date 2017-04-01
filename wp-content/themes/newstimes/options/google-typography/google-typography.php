<?php
/*
Based on: Google Typography
Plugin URI: http://projects.ericalli.com/google-typography/
Author: Eric Alli
Author URI: http://ericalli.com
*/

/**
 * GoogleTypography class
 *
 * @class GoogleTypography	The class that holds the entire Google Typography plugin
 */

class GoogleTypography {

	/**
	 * @var $api_url	The google web font API URL
	 */
	protected $api_url = "https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyCjae0lAeI-4JLvCgxJExjurC4whgoOigA";
	
	/**
	 * @var $fonts_url	The google web font URL
	 */
	protected $fonts_url = "//fonts.googleapis.com/css?family=";
	
	/**
	 * Constructor for the GoogleTypography class
	 *
	 * Sets up all the appropriate hooks and actions
	 * within the plugin.
	 *
	 * @uses register_uninstall_hook()
	 * @uses is_admin()
	 * @uses add_action()
	 *
	 */	
	function __construct() {
		register_activation_hook(__FILE__, array(&$this, 'get_fonts' ));
		
		if ( is_admin() ){
			//add_action('admin_menu', array(&$this, 'admin_menu'));
			add_action('admin_enqueue_scripts', array(&$this, 'admin_scripts'));
			add_action('wp_ajax_get_user_fonts',array(&$this,'ajax_get_user_fonts'));
			add_action('wp_ajax_save_user_fonts',array(&$this,'ajax_save_user_fonts'));
			add_action('wp_ajax_reset_user_fonts',array(&$this,'ajax_reset_user_fonts'));
			add_action('wp_ajax_get_google_fonts',array(&$this,'ajax_get_google_fonts'));
			add_action('wp_ajax_get_google_font_variants',array(&$this,'ajax_get_google_font_variants'));
		} else{
			add_action('wp_head',array(&$this,'build_frontend'));
		}
        
        $this->std_fonts = array(
            "Helvetica, Arial, sans-serif"                          => "Helvetica, Arial, sans-serif",
            "'Arial Black', Gadget, sans-serif"                     => "'Arial Black', Gadget, sans-serif",
            "'Bookman Old Style', serif"                            => "'Bookman Old Style', serif",
            "'Comic Sans MS', cursive"                              => "'Comic Sans MS', cursive",
            "Courier, monospace"                                    => "Courier, monospace",
            "Garamond, serif"                                       => "Garamond, serif",
            "Georgia, serif"                                        => "Georgia, serif",
            "Impact, Charcoal, sans-serif"                          => "Impact, Charcoal, sans-serif",
            "'Lucida Console', Monaco, monospace"                   => "'Lucida Console', Monaco, monospace",
            "'Lucida Sans Unicode', 'Lucida Grande', sans-serif"    => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
            "'MS Sans Serif', Geneva, sans-serif"                   => "'MS Sans Serif', Geneva, sans-serif",
            "'MS Serif', 'New York', sans-serif"                    => "'MS Serif', 'New York', sans-serif",
            "'Palatino Linotype', 'Book Antiqua', Palatino, serif"  => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
            "Tahoma,Geneva, sans-serif"                             => "Tahoma, Geneva, sans-serif",
            "'Times New Roman', Times,serif"                        => "'Times New Roman', Times, serif",
            "'Trebuchet MS', Helvetica, sans-serif"                 => "'Trebuchet MS', Helvetica, sans-serif",
            "Verdana, Geneva, sans-serif"                           => "Verdana, Geneva, sans-serif",    
        );

	}
	
	public static function &init() {
		static $instance = false;

		if ( !$instance ) {
			$instance = new GoogleTypography();
		}

		return $instance;
	}
	
	/**
	 * Initialize admin menu
	 *
	 * @uses add_theme_page()
	 * @uses add_filter()
	 * @uses add_action()
	 *
	 */
	function admin_menu() {
		global $plugin_screen;
		
		$plugin_screen = add_theme_page( 'Theme Typography', 'Theme Typography', 'manage_options', 'typography', array(&$this, 'options_ui'));
		
		add_filter('plugin_action_links', array(&$this, 'plugin_link'), 10, 2);
		add_action('load-'.$plugin_screen, array(&$this, 'help_tab'));
	}
	
	function help_tab() {
		global $plugin_screen;
		
		$screen = get_current_screen();

		if ($screen->id != $plugin_screen)
		    return;
		
		$adding_title					= __('Adding A Collection', 'mythemeshop');
		$adding_content				= '<p>'.__('To add a new font for use on your site. Click the "Add New" button on the top left of the page near the "Google Typography" title.', 'mythemeshop').'</p>';
		$adding_content			 .= '<p>'.__('Once added, a new font row will appear on the page below. Next you can continue to customize your font (more info in the "Customizing" help tab).', 'mythemeshop').'</p>';
		//$adding_content			 .= '<p><a href="https://vimeo.com/67957799" target="_blank">'.__('Watch The Video Tutorial &rarr;', 'mythemeshop').'</a></p>';
		$customizing_title 		= __('Customizing','mythemeshop');
		$customizing_content	= '<p>'.__('Customizing fonts is easy; after adding a new font row you can then customize the following font attributes:', 'mythemeshop').'</p>';
		$customizing_content .= '<ul><li><b>'.__('Preview Text', 'mythemeshop').'</b> - '.__('Used for live previewing your changes. This text does not appear anywhere on your website.', 'mythemeshop').'</li><li><b>Preview Background Color</b> - Allows you to swap between light and dark backgrounds when previewing this font.</li><li><b>'.__('Font Family', 'mythemeshop').'</b> - '.__('The font family to use for this font. Choose from a real-time list of all available Google Fonts.', 'mythemeshop').'</li><li><b>'.__('Font Variant', 'mythemeshop').'</b> - '.__('The variant to use for this font. Note: Each font has it\'s own variant options.', 'mythemeshop').'</li><li><b>'.__('Font Size', 'mythemeshop').'</b> - '.__('The size you would like this font to be.', 'mythemeshop').'</li><li><b>'.__('Font Color', 'mythemeshop').'</b> - '.__('The color you\'d like to use for this font.', 'mythemeshop').'</li><li><b>'.__('CSS Selectors', 'mythemeshop').'</b> - '.__('The HTML tags or CSS selectors you\'d like this font to apply to (more info in the "CSS Selectors" help tab). You can specify multiple selectors separated by comma\'s. Ex: h1, #some_id, .some_class', 'mythemeshop').'</li></ul>';
		$selectors_title 			= __('CSS Selectors','mythemeshop');
		$selectors_content		= '<p>' . __('CSS Selectors are used to hook your font rows into your actual website. Once you\'ve added, customized, and defined CSS selectors for your fonts, Google Typography will automatically insert all the necessary CSS into your website.', 'mythemeshop') . '</p>';
		$selectors_content		= '<p>' . __('Here are some examples of the selectors you can use:', 'mythemeshop') . '</p>';
		$selectors_content	 .= '<ul><li><b>'.__('IDs', 'mythemeshop').':</b> '.__('#selector', 'mythemeshop').'</li><li><b>'.__('Classes:', 'mythemeshop').':</b> '.__('.selector', 'mythemeshop').'</li><li><b>'.__('HTML Tags', 'mythemeshop').':</b> '.__('span', 'mythemeshop').'</ul>';      
		$selectors_content	 .= '<p><b>'.__('Example', 'mythemeshop').':</b> '.__('#selector span.date', 'mythemeshop').'</p>';
		
		$screen->add_help_tab(array(
		    'id'	=> 'adding',
		    'title'	=> $adding_title,
		    'content'	=> $adding_content
		));
		
		$screen->add_help_tab(array(
		    'id'	=> 'customizing',
		    'title'	=> $customizing_title,
		    'content'	=> $customizing_content
		));
		
		$screen->add_help_tab(array(
		    'id'	=> 'selectors',
		    'title'	=> $selectors_title,
		    'content'	=> $selectors_content
		));

	}
	
	/**
	 * Initialize plugin options link
	 *
	 */
	function plugin_link($links, $file) {
		if ( $file == 'google-typography/google-typography.php' ) {
			$links['settings'] = sprintf( '<a href="%s"> %s </a>', admin_url( 'themes.php?page=typography' ), __( 'Settings', 'mythemeshop' ) );
		}
		return $links;
	}
	
	/**
	 * Build the frontend CSS to apply to wp_head()
	 *
	 * @uses get_option()
	 * @uses GoogleTypography::stringify_fonts()
	 *
	 */
	function build_frontend() {
		
		$collections = get_option('google_typography_collections');
		$mts_options = get_option(newstimes);
        
		$import_fonts = array();
		$font_styles = '';
        $uses_google_fonts = false;
        
		if($collections) {
		    $frontend = '';
			foreach($collections as $collection){
			
				if(isset($collection['css_selectors']) && $collection['css_selectors'] != "" && isset($collection['font_variant'])) {
				    
                    $collection['font_family'] = stripslashes($collection['font_family']);
                    // is it a Google font?
                    if (in_array($collection['font_family'], $this->std_fonts)) {
                        $google = false;
                    } else {
                        $google = true;
                        $uses_google_fonts = true;
                    }
                    
                    if ($google) {
                        array_push($import_fonts, array('font_family' => $collection['font_family'], 'font_variant' => $collection['font_variant']));
                    }
                    
					$font_styles .= $collection['css_selectors'] . ' { ';
                    
					if ($google) {
                        $font_styles .= "font-family: '" . $collection['font_family'] . "'" . (empty($collection['backup_font']) ? '' : ', '.$collection['backup_font']) .'; ';
                    } else {
                        // quotes not needed for standard fonts
                        $font_styles .= 'font-family: ' . $collection['font_family'] . '; ';
                    }
                    
					$font_styles .= 'font-weight: ' . $collection['font_variant'] . '; ';
					$font_styles .= 'font-size: ' . $collection['font_size'] . '; ';
					$font_styles .= 'color: ' . $collection['font_color'] . ';';
                    $font_styles .= (empty($collection['additional_css']) ? '' : $collection['additional_css']);
					$font_styles .= " }\n";
			
				}
			}
            
            if ($uses_google_fonts) {
                $subsets = (empty($mts_options['mts_typography_sets']) ? array('latin') : $mts_options['mts_typography_sets']);
                
                $frontend .= '<link href="' . $this->fonts_url . $this->stringify_fonts($import_fonts) .'&amp;subset='.join(',', $subsets).'" rel="stylesheet" type="text/css">';
            }
            
			$frontend .= "\n<style type=\"text/css\">\n";
			$frontend .= $font_styles;
			$frontend .= "</style>\n";
		
			echo $frontend;
		
		}
		
	}

	/**
	 * Concatenate fonts into a format that Google likes
	 *
	 * @uses array_map()
	 * @uses implode()
	 * @return String of fonts and their associated weights
	 *
	 */
	function stringify_fonts($array) {
		
		$array = array_map('unserialize', array_unique(array_map('serialize', $array)));
		
		$fonts = array();
		
		foreach($array as $font){
			$parts = '';
			
			$parts .= str_replace(" ", "+", $font['font_family']);
			if(isset($font['font_variant']) && $font['font_variant'] != '') {
				$parts .= ':' . $font['font_variant'];
			}
			
			$fonts[] = $parts;
		}
		
		return implode('|', $fonts);
	}
	
	/**
	 * Build the admin settings UI
	 *
	 * @uses GoogleTypography::get_fonts()
	 *
	 */
	function options_ui() {
		
        $std_fonts = $this->std_fonts;
        $mts_options = get_option(newstimes);
        
		$title								= __('Theme Typography', 'mythemeshop');
		$loading							= __('Loading Your Collections', 'mythemeshop');
		$add_new        			= __('Add New Collection', 'mythemeshop');
		$reset        				= __('Reset Collections', 'mythemeshop');
		$preview_text   			= __('Type in some text to preview...', 'mythemeshop');
        //$this->preview_text = $preview_text; // to pass it to JS
		$preview_hint   			= __('Preview Background Color', 'mythemeshop');
		//$font_family_title  	= __('Font family...', 'mythemeshop');
        $std_font_family_title  	= __('Standard Fonts', 'mythemeshop');
        $google_font_family_title  	= __('Google Fonts', 'mythemeshop');
		$font_variant_title 	= __('Variant...', 'mythemeshop');
		$font_size_title 			= __('Size...', 'mythemeshop');
        $backup_font_title          = __('Backup font...', 'mythemeshop');
		$css_selectors_title	= __('CSS Selectors (h1, .some_class)', 'mythemeshop');
        $additional_css_title	= __('Additional CSS', 'mythemeshop');
		$delete_button_text   = __('Delete', 'mythemeshop');
		$save_button_text			= __('Save', 'mythemeshop');
		
		$welcome_title 				= __('Theme Typography', 'mythemeshop');
		$welcome_subtitle			= __('Get started in 3 steps.', 'mythemeshop');//.'<a href="https://vimeo.com/67957799" target="_blank">'.__('Watch the video tutorial &#x2192;', 'mythemeshop').'</a>';
		$step_1_title 				= __('1. Pick A Font', 'mythemeshop');
		$step_1_desc					= __('Choose from any of the 600+ Google Fonts.', 'mythemeshop');
		$step_2_title 				= __('2. Customize It', 'mythemeshop');
		$step_2_desc					= __('Pick a size, variant, color and more.', 'mythemeshop');
		$step_3_title 				= __('3. Attach It', 'mythemeshop');
		$step_3_desc					= __('Attach your font to any CSS selector(s).', 'mythemeshop');
		$year									= date("Y");
        $themename = newstimes;
        
        $character_sets_title = __('Character sets', 'mythemeshop');
        $character_sets_desc = __('Choose the character sets you wish to include. Please note that not all sets are available for all fonts.', 'mythemeshop');
		
        if ( empty($mts_options['mts_typography_sets']) || ! is_array( $mts_options['mts_typography_sets'] ) )
            $mts_options['mts_typography_sets'] = array('latin'); // default

        $character_sets = array(
            'latin' => __('Latin', 'mythemeshop'),
            'latin-ext' => __('Latin Extended', 'mythemeshop'),
            'cyrillic' => __('Cyrillic', 'mythemeshop'),
            'cyrillic-ext' => __('Cyrillic Extended', 'mythemeshop'),
            'greek' => __('Greek', 'mythemeshop'),
            'greek-ext' => __('Greek Extended', 'mythemeshop'),
            'vietnamese' => __('Vietnamese', 'mythemeshop'),
            'khmer' => __('Khmer', 'mythemeshop'),
            'devanagari' => __('Devanagari', 'mythemeshop'),
        );
        
        // following could be done with CSS
        $add_new = str_replace(' ', '&nbsp;', $add_new);
        $reset = str_replace(' ', '&nbsp;', $reset);
        
		$fonts = $this->get_fonts();
		$font_families = "";
        $std_font_families = "";
		if (is_array($fonts)) {
    		foreach ($fonts as $font) {
    			$font_family = $font['family'];
    			$font_families .= "<option value=\"$font_family\" data-google=\"true\">$font_family</option>";
    		}
		}
		foreach ($std_fonts as $font_name => $font_value) {
		  $std_font_families .= "<option value=\"$font_value\">$font_name</option>";
        }
        
		$numbers = "";
		foreach (range(10, 120) as $number) {
			$numbers .= "<option value=\"{$number}px\">{$number}px</option>";
		}
		
		if(get_option("google_typography_default")) {
			$reset_link = '<a href="javascript:;" class="add-new-h2 reset_collections">'.$reset.'</a>';
		} else { $reset_link = ''; }
		
		echo <<<EOT
			<h3>
					$title
					<a href="javascript:;" class="add-new-h2 new_collection">$add_new</a>
					$reset_link
			</h3>
            <div class="nhp-opts-section-desc">
                <p class="description">
                    From here, you can control the fonts used on your site. 
                    You can choose from 17 standard font sets, or from the
                    <a href="http://www.google.com/fonts" target="_blank">Google Fonts Library</a> containing 600+ fonts.
                </p>
            </div>
            
            <div id="google_typography" class="wrap">
							
				<div class="loading">
					<span class="spin"></span>
					<!-- <h2>$loading</h2> -->
				</div>
				
				<div class="welcome">
					
					<div class="welcome_title">
						<h1>$welcome_title</h1>
						<p>$welcome_subtitle</p>
					</div>
					<ul class="steps">
						<li class="step_1">
							<h3>$step_1_title</h3>
							<p>$step_1_desc</p>
						</li>
						<li class="step_2">
							<h3>$step_2_title</h3>
							<p>$step_2_desc</p>
						</li>
						<li class="step_3">
							<h3>$step_3_title</h3>
							<p>$step_3_desc</p>
						</li>
					</ul>
				</div>
				
				<div class="template">
				
					<div class="collection">
					
						<div class="font_preview">
							<input type="text" class="preview_text" value="$preview_text" />
							<ul class="preview_color" title="$preview_hint">
								<li><a href="javascript:;" class="light"></a></li>
								<li><a href="javascript:;" class="dark"></a></li>
							</ul>
						</div>
						
						<div class="font_options">
							<div class="left_col">
								<select class="font_family">
									<optgroup label="$std_font_family_title" data-group="standard">
                                        $std_font_families
                                    </optgroup>
                                    <optgroup label="$google_font_family_title" data-group="google">
									   $font_families
                                    </optgroup>
								</select>
								<select class="font_variant">
									<option value="">$font_variant_title</option>
								</select>
								<select class="font_size mts-font-size">
									<option value="">$font_size_title</option>
									$numbers
								</select>
								<input type="text" value="#222222" class="font_color" />
								<input type="text" placeholder="$css_selectors_title" class="css_selectors" />
                                <input type="text" placeholder="$additional_css_title" class="additional_css" />
                                <select class="backup_font">
                                    <option value="">$backup_font_title</option>
                                    $std_font_families
                                </select>
                            </div>
                            
                            <div class="right_col">
								<a href="javascript:;" class="delete_collection dashicons dashicons-dismiss"></a>
								
							</div>
                            
							<div class="clear"></div>
                            
						</div>
                        <!-- Additional CSS -->
                        <style type="text/css" class="additional-css"></style>
					</div>
					
				</div>
				
				<div class="collections"></div>
			</div>	
EOT;
		// settings
        $typography_settings = '<table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <span class="field_title">'.$character_sets_title.'</span>
                        <span class="description">'.$character_sets_desc.'</span>
                    </th>
                    <td>
                        <fieldset>';
        foreach ($character_sets as $val => $name) {
            $typography_settings .= '<label for="mts_typography_sets_'.$val.'">
                                <input type="checkbox" id="mts_typography_sets_'.$val.'" name="'.$themename.'[mts_typography_sets][]" regular-text value="'.$val.'" '.checked( in_array( $val, $mts_options['mts_typography_sets'] ), true, false ).'/> 
                                '.$name.' ('.$val.')
                            </label><br/>';
        }
        
        $typography_settings .= '
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>';
        echo $typography_settings;
	}
	
	/**
	 * Function for retrieving user font collections
	 *
	 *
	 * @uses get_option()
	 * @uses json_encode()
	 * @return JSON object with all user fonts
	 *
	 */
	function ajax_get_user_fonts() {
		
		$collections = get_option('google_typography_collections');
		
		$retrieved = $collections ? true : false;
        
        // associative array won't work
        $collections = array_values($collections);        
		$response = json_encode( array( 'success' => $retrieved, 'collections' => $collections ) );
		
		header( "Content-Type: application/json" );
		echo $response;
		
		exit;
		
	}
	
	/**
	 * Function for saving user font collections
	 *
	 *
	 * @uses update_option()
	 * @uses json_encode()
	 * @return JSON object with all user fonts
	 *
	 */
	function ajax_save_user_fonts() {
		
		$collections = $_REQUEST['collections'];
		
		$collections = update_option('google_typography_collections', $collections);
		
		$response = json_encode( array( 'success' => true, 'collections' => $collections ) );
		
		header( "Content-Type: application/json" );
		echo $response;
		
		exit;
		
	}
	
	/**
	 * Function for resetting user font collections
	 *
	 *
	 * @uses delete_option()
	 * @uses json_encode()
	 * @return JSON object with all user fonts
	 *
	 */
	function ajax_reset_user_fonts() {
		
		delete_option('google_typography_default');
		delete_option('google_typography_collections');
		
		$response = json_encode( array( 'success' => true ) );
		
		header( "Content-Type: application/json" );
		echo $response;
		
		exit;
		
	}
	
	/**
	 * AJAX function for retrieving fonts from Google
	 *
	 *
	 * @uses GoogleTypography::multidimensional_search()
	 * @uses header()
	 * @return JSON object with font data
	 *
	 */
	function ajax_get_google_fonts() {

		$fonts = $this->get_fonts();
		
		header("Content-Type: application/json");
		echo json_encode($fonts);
		
		exit;
	}
	
	/**
	 * AJAX function for retrieving font variants
	 *
	 *
	 * @uses GoogleTypography::multidimensional_search()
	 * @uses header()
	 * @return JSON object with font data
	 *
	 */
	function ajax_get_google_font_variants() { 

		$fonts = $this->get_fonts();
		$font_family = $_GET['font_family'];
		
		$result = $this->multidimensional_search($fonts, array('family' => $font_family));
		
		header("Content-Type: application/json");
		echo json_encode($result['variants']);
		
		exit;
	}
	
	/**
	 * Function for retrieving and saving fonts from Google
	 *
	 *
	 * @uses get_transient()
	 * @uses set_transient()
	 * @uses wp_remote_get()
	 * @uses wp_remote_retrieve_body()
	 * @uses json_decode()
	 * @return JSON object with font data
	 *
	 */
	function get_fonts() {
		$fonts = get_transient( 'google_typography_fonts' );	

		if (false === $fonts)	{

			$request = wp_remote_get($this->api_url, array( 'sslverify' => false ));

			if( is_wp_error( $request ) ) {

			   $error_message = $request->get_error_message();
			
			   echo "Something went wrong: $error_message";

			} else {
				
				$json = wp_remote_retrieve_body($request);

				$data = json_decode($json, TRUE);

				$items = $data['items'];
				
				$i = 0;
				
				foreach ($items as $item) {
					
					$i++;
					
					$variants = array();
					foreach ($item['variants'] as $variant) {
						if(!stripos($variant, "italic") && $variant != "italic") {
							if($variant == "regular") {
								$variants[] = "normal";
							} else {
								$variants[] = $variant;
							}
						}
					}

					$fonts[] = array('uid' => $i, 'family' => $item['family'], 'variants' => $variants);

				}
				
				set_transient( 'google_typography_fonts', $fonts, 60 * 60 * 24 );

			}

		}

		return $fonts;
	}
	
	/**
	 * Function for searching array of fonts
	 *
	 *
	 * @return JSON object with font data
	 *
	 */
	function multidimensional_search($parents, $searched) {
	  if (empty($searched) || empty($parents)) {
	    return false;
	  }

	  foreach($parents as $key => $value) {
	    $exists = true;
	    foreach ($searched as $skey => $svalue) {
	      $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
	    }
	    if($exists){ return $parents[$key]; }
	  }

	  return false;
	}
	
	/**
	 * Enqueue admin styles and scripts
	 *
	 *
	 * @uses wp_register_script()
	 * @uses wp_enqueue_script()
	 * @uses wp_register_style()
	 * @uses wp_enqueue_style()
	 *
	 */
	function admin_scripts() {
		
		//Javascripts
		wp_register_script('google-webfont', 'https://ajax.googleapis.com/ajax/libs/webfont/1.4.2/webfont.js', false);
		wp_register_script('google-typography', get_template_directory_uri() .'/options/google-typography/javascripts/google-typography.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker'));
		wp_localize_script('google-typography', 'googletypography', array(
            'reset_confirm' => __("Are you sure you want to revert back to the default collections? Note: You will lose any custom collections you've created.", 'mythemeshop'),
            'delete_confirm' => __("Are you sure you want to delete this collection?", 'mythemeshop')
        ));
        wp_register_script('chosen2', get_template_directory_uri() .'/options/google-typography/javascripts/jquery.chosen.js', array('jquery'));
		wp_enqueue_script('google-webfont');
		wp_enqueue_script('google-typography');
		wp_enqueue_script('chosen2');                        
		
		// Stylesheets
		wp_register_style('google-typography', get_template_directory_uri() .'/options/google-typography/stylesheets/google-typography.css', false, '1.0.0');
		wp_register_style('chosen', get_template_directory_uri() .'/options/google-typography/stylesheets/chosen.css', false, '1.0.0');
		wp_register_style('google-font', 'http://fonts.googleapis.com/css?family=Lato:300,400');
		wp_enqueue_style('google-typography');
		wp_enqueue_style('chosen');
		wp_enqueue_style('google-font');
		wp_enqueue_style('wp-color-picker');
	}
}

GoogleTypography::init();

/**
 * Function for registering default typography collections 
 *
 *
 * @uses get_option()
 * @uses update_option()
 *
 */
function register_typography($collections) {

	if(!get_option('google_typography_default')) {

		$defaults = array();
		delete_option('google_typography_collections');

		foreach($collections as $key => $collection) {
			array_push($defaults, 
				array_merge(
					array('default' => true), 
					$collection
				)
			);
		}
 
		update_option('google_typography_default', true);
		update_option('google_typography_collections', $defaults);

	} 
}