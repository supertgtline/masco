<?php 
/*-----------------------------------------------------------------------------------*/
/* Initializing Widgetized Areas (Sidebars)																			 */
/*-----------------------------------------------------------------------------------*/
 
/*----------------------------------*/
/* Sidebar							*/
/*----------------------------------*/
 
register_sidebar(array(
    'name'=>'Sidebar',
    'id' => 'sidebar',
	'before_widget' => '<div class="widget %2$s" id="%1$s">',
	'after_widget' => '<div class="cleaner">&nbsp;</div></div>',
	'before_title' => '<div class="title"><h3>',
	'after_title' => '</h3></div><!-- end .title -->',
));

/*----------------------------------*/
/* Homepage widgetized areas		*/
/*----------------------------------*/
 
register_sidebar(array('name'=>'Homepage: Content Widgets',
	'before_widget' => '<div class="widget %2$s" id="%1$s">',
	'after_widget' => '<div class="cleaner">&nbsp;</div></div>',
	'before_title' => '<div class="title"><h3>',
	'after_title' => '</h3></div><!-- end .title -->',
));

?>