<?php

class newstimes_calendar_widget extends WP_Widget_Calendar {

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div id="calendar_wrap">';
		get_calendar(false);
		echo '</div>';
		echo $after_widget;
	}
}
?>