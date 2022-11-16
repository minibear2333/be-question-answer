<?php

class DWQA_Widgets_Closed_Question extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'classname' => 'be-qa-widget dwqa-closed-questions', 'description' => __( '显示已关闭的问题列表。', 'be-question-answer' ) );
		parent::__construct( 'dwqa-closed-question', __( '已关闭的问题', 'be-question-answer' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$instance = wp_parse_args( $instance, array( 
			'title' => __( '已关闭的提问', 'be-question-answer' ),
			'number' => 5,
		) );
		
		echo $before_widget;
		echo $before_title;
		echo $instance['title'];
		echo $after_title;
		$args = array(
			'post_type' => 'dwqa-question',
			'posts_per_page' => $instance['number'],
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => '_dwqa_status',
					'compare' => '=',
					'value' => 'resolved',
				),
				array(
					'key' => '_dwqa_status',
					'compare' => '=',
					'value' => 'closed',
				),
			),
		);
		$questions = new WP_Query( $args );
		if ( $questions->have_posts() ) {
			echo '<div class="dwqa-popular-questions">';
			echo '<ul>';
			while ( $questions->have_posts() ) { $questions->the_post( );
				echo '
				<li><a href="'.get_permalink( ).'" class="question-title">'.get_the_title( ).'</a> '.__( 'asked by' , 'be-question-answer' ).' '. get_the_author_link( );
				'</li>';
			}   
			echo '</ul>';
			echo '</div>';
		}
		wp_reset_query( );
		wp_reset_postdata( );
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		// update logic goes here
		$updated_instance = $new_instance;
		return $updated_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( $instance, array( 
			'title' => '',
			'number' => 5,
		) );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>">标题</label>
		<input type="text" name="<?php echo $this->get_field_name( 'title' ) ?>" id="<?php echo $this->get_field_id( 'title' ) ?>" value="<?php echo $instance['title'] ?>" class="widefat">
		</p>
		<p><label for="<?php echo $this->get_field_id( 'number' ) ?>"><?php _e( 'Number of posts', 'be-question-answer' ) ?></label>
		<input type="text" name="<?php echo $this->get_field_name( 'number' ) ?>" id="<?php echo $this->get_field_id( 'number' ) ?>" value="<?php echo $instance['number'] ?>" class="widefat">
		</p>
		<?php
	}
}
?>