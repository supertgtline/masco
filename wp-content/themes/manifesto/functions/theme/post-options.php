<?php

// This is for AJAX fetching of the auto thumbnail preview on the admin post add/edit screens
if(isset($_POST['wpzoom_autothumb']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  header('Content-type: text/plain');
  die(fetch_video_thumbnail_url(stripslashes($_POST['wpzoom_autothumb'])));
}
 

/*-----------------------------------------------------------------------------------*/
/* WPZOOM Custom Functions															 */
/*-----------------------------------------------------------------------------------*/

add_action('admin_head', 'myposttype_admin_css');

	function myposttype_admin_css() {
 		echo '<link type="text/css" rel="stylesheet" href="'.get_bloginfo( 'template_directory').'/functions/admin-style.css" media="screen" />';
 
}


add_action('admin_head-post-new.php', 'wpz_newpost_head', 100);
add_action('admin_head-post.php', 'wpz_newpost_head', 100);
function wpz_newpost_head() {
  ?><style type="text/css">
    #wpzoom_autothumb_preview {
      display: none;
      margin: 0
    }

    #wpzoom_autothumb_preview img {
      display: block;
      margin-bottom: 5px
    }

    #wpzoom_autothumb_preview .howto {
      line-height: 1.4em
    }
  </style>
  <script type="text/javascript">
    jQuery(function($){
      $('<p id="wpzoom_autothumb_preview"><img src=""/><small class="howto"><?php _e('<strong>Automatic Thumbnail</strong><br/>This automatic thumbnail is used when you do not manually upload a Featured Image yourself.', 'wpzoom') ?></small></p>').insertAfter('#wpzoom_post_embed_code');

      $('#wpzoom_autothumb_preview img').load(function(){$('#wpzoom_autothumb_preview').animate({height: 'show', opacity: 'show'}, 500)});

      $('#wpzoom_post_embed_code').bind('input', function(){
        if('' != (val = $.trim($(this).val())))
          $.ajax({
            type: 'post',
            data: {wpzoom_autothumb:val},
            complete: function(xhr,status){
              if('' != (response = $.trim(xhr.responseText)))
                $('#wpzoom_autothumb_preview img').attr('src', '<?php bloginfo('stylesheet_directory') ?>/functions/wpzoom/components//timthumb.php?src=' + encodeURIComponent(response) + '&w=255&zc=1');
              else
                $('#wpzoom_autothumb_preview').animate({height: 'hide', opacity: 'hide'}, 500, function(){$('#wpzoom_autothumb_preview img').removeAttr('src')});
            }
          });
        else
          $('#wpzoom_autothumb_preview').animate({height: 'hide', opacity: 'hide'}, 500, function(){$('#wpzoom_autothumb_preview img').removeAttr('src')});
      }).triggerHandler('input');
    });
  </script><?php
}


function fetch_video_thumbnail_url222($input) {
  $input = htmlspecialchars_decode(trim((stripos($input, '<iframe') !== false || stripos($input, '<embed') !== false) && preg_match('#src="([^"]+)"#i', $input, $match) ? $match[1] : $input), ENT_QUOTES);
  $out = false;

  if(filter_var($input, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) !== false && false !== ($url_parts=parse_url($input)) && (stripos($url_parts['host'], 'youtube.com') !== false || stripos($url_parts['host'], 'youtu.be') !== false || stripos($url_parts['host'], 'vimeo.com') !== false)) {
    $url_query = array();if(isset($url_parts['query']))parse_str($url_parts['query'],$url_query);
    $id = isset($url_query['v']) ? $url_query['v'] : (isset($url_query['clip_id']) ? $url_query['clip_id'] : reset(explode('?', end(array_filter(explode('/', $input))))));

    if(stripos($url_parts['host'], 'youtube.com') !== false || stripos($url_parts['host'], 'youtu.be') !== false) {
      if(false !== ($contents = @file_get_contents("http://gdata.youtube.com/feeds/api/videos/$id?v=2&alt=jsonc"))) {
        $obj = json_decode($contents, true);
        $out = $obj['data']['thumbnail']['hqDefault'];
      }
    } elseif(stripos($url_parts['host'], 'vimeo.com') !== false) {
      if(false !== ($contents = @file_get_contents("http://vimeo.com/api/v2/video/$id.php"))) {
        $obj = unserialize($contents);
        $out = $obj[0]['thumbnail_large'];
      }
    }
  }

  return $out;
}



/*----------------------------------*/
/* Custom Posts Options				*/
/*----------------------------------*/

add_action('admin_menu', 'wpzoom_options_box');

function wpzoom_options_box() {
	add_meta_box('wpzoom_post_template', 'Post Options', 'wpzoom_post_options', 'post', 'side', 'high');
	add_meta_box('wpzoom_post_layout', 'Post Layout', 'wpzoom_post_layout_options', 'post', 'normal', 'high');
}

add_action('save_post', 'custom_add_save');

function custom_add_save($postID){
	// called after a post or page is saved
	if($parent_id = wp_is_post_revision($postID))
	{
	  $postID = $parent_id;
	}
	
	if ($_POST['save'] || $_POST['publish']) {
		if ($_POST['wpzoom_post_template']) {
			update_custom_meta($postID, $_POST['wpzoom_post_template'], 'wpzoom_post_template');
		}

		update_custom_meta($postID, $_POST['wpzoom_is_featured'], 'wpzoom_is_featured');
		update_custom_meta($postID, $_POST['wpzoom_post_template'], 'wpzoom_post_template');
 		update_custom_meta($postID, $_POST['wpzoom_post_embed_code'], 'wpzoom_post_embed_code');
		update_custom_meta($postID, $_POST['wpzoom_post_author'], 'wpzoom_post_author');
	}
}

function update_custom_meta($postID, $newvalue, $field_name) {
	// To create new meta
	if(!get_post_meta($postID, $field_name)){
		add_post_meta($postID, $field_name, $newvalue);
	}else{
		// or to update existing meta
		update_post_meta($postID, $field_name, $newvalue);
	}
	
}

// Custom Post Layouts
function wpzoom_post_layout_options() {
	global $post;
	$postLayouts = array('side-right' => 'Sidebar on the right', 'side-left' => 'Sidebar on the left', 'full' => 'Full Width');
	?>

	<style>
	.RadioClass{  
		display: none;
	} 
	
	.RadioLabelClass {
		margin-right: 10px;
	}
	
	img.layout-select {
		border: solid 4px #c0cdd6;
		border-radius: 5px;
	}
	
	.RadioSelected img.layout-select{
		border: solid 4px #3173b2;
	}
	</style>

	<script type="text/javascript">  
	jQuery(document).ready(
	function($)
	{
		$(".RadioClass").change(function(){  
		    if($(this).is(":checked")){  
		        $(".RadioSelected:not(:checked)").removeClass("RadioSelected");  
		        $(this).next("label").addClass("RadioSelected");  
		    }  
		}); 
	});  
	</script>

	<fieldset>
		<div>
			 
			<p>
			
			<?php
			foreach ($postLayouts as $key => $value)
			{
				?>
				<input id="<?php echo $key; ?>" type="radio" class="RadioClass" name="wpzoom_post_template" value="<?php echo $key; ?>"<?php if (get_post_meta($post->ID, 'wpzoom_post_template', true) == $key) { echo' checked="checked"'; } ?> />
				<label for="<?php echo $key; ?>" class="RadioLabelClass<?php if (get_post_meta($post->ID, 'wpzoom_post_template', true) == $key) { echo' RadioSelected"'; } ?>">
				<img src="<?php echo wpzoom::$wpzoomPath; ?>/assets/images/layout-<?php echo $key; ?>.png" alt="<?php echo $value; ?>" title="<?php echo $value; ?>" class="layout-select" /></label>
			<?php
			} 
			?>

			</p>
			
  		</div>
	</fieldset>
	<?php
}

// Regular Posts Options
function wpzoom_post_options() {
	global $post;
	?>

	<fieldset>


		<p class="wpz_border">
			<?php $isChecked = ( get_post_meta($post->ID, 'wpzoom_is_featured', true) == 1 ? 'checked="checked"' : '' ); // we store checked checkboxes as 1 ?>
			<input type="checkbox" name="wpzoom_is_featured" id="wpzoom_is_featured" value="1" <?php echo $isChecked; ?> /> <label for="wpzoom_is_featured">Feature on Homepage?</label> 
		</p>

		<p class="wpz_border" style="border-bottom:none; padding:0;">
			<strong>Embed Video for Slideshow:</strong><br />
				<textarea style="height: 110px; width: 255px;" name="wpzoom_post_embed_code" id="wpzoom_post_embed_code"><?php echo get_post_meta($post->ID, 'wpzoom_post_embed_code', true); ?></textarea>		
			<span class="description">Insert Embed Code (<em>YouTube, Vimeo, etc.</em>):</span>				 

		</p>

		<p>
			<label for="wpzoom_post_author" >Show info about author:</label><br />
			<select name="wpzoom_post_author" id="wpzoom_post_author">
				<option<?php selected( get_post_meta($post->ID, 'wpzoom_post_author', true), 'Yes' ); ?>>Yes</option>
				<option<?php selected( get_post_meta($post->ID, 'wpzoom_post_author', true), 'No' ); ?>>No</option>
			</select>
			<br />
		</p>

 				 
	</fieldset>
	<?php
	}
?>