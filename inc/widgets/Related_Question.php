<?php

class DWQA_Widgets_Related_Question extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'classname' => 'dwqa-widget dwqa-related-questions', 'description' => __( '显示与问题相关的问题列表。 仅显示在单个问题页面中', 'be-question-answer' ) );
		parent::__construct( 'dwqa-related-question', __( '相关问题', 'be-question-answer' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$instance = wp_parse_args( $instance, array( 
			'title'  => '相关问题',
			'number' => 5,
		) );
		$post_type = get_post_type();
		if ( is_single() && ( $post_type == 'dwqa-question' || $post_type == 'dwqa-answer' ) ) {

			echo $before_widget;
			echo $before_title;
			echo $instance['title'];
			echo $after_title;
			echo '<div class="related-questions">';
			dwqa_related_question( false, $instance['number'] );
			echo '</div>';
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {

		// update logic goes here
		$updated_instance = $new_instance;
		return $updated_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( $instance, array( 
			'title'	=> '',
			'number' => 5,
		) );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>">标题</label>
		<input type="text" name="<?php echo $this->get_field_name( 'title' ) ?>" id="<?php echo $this->get_field_id( 'title' ) ?>" value="<?php echo sanitize_text_field( $instance['title'] ); ?>" class="widefat">
		</p>
		<p><label for="<?php echo $this->get_field_id( 'number' ) ?>"><?php _e( 'Number of posts', 'be-question-answer' ) ?></label>
		<input type="text" name="<?php echo $this->get_field_name( 'number' ) ?>" id="<?php echo $this->get_field_id( 'number' ) ?>" value="<?php echo intval( $instance['number'] ); ?>" class="widefat">
		</p>
		<?php
	}
}

?>