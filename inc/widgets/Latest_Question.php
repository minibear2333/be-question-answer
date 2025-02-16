<?php

class DWQA_Widgets_Latest_Question extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'classname' => 'dwqa-widget dwqa-latest-questions', 'description' => __( '显示最新问题列表。', 'be-question-answer' ) );
		parent::__construct( 'dwqa-latest-question', __( '最新问题', 'be-question-answer' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$instance = wp_parse_args( $instance, array( 
			'title' => __( '最新问题' , 'be-question-answer' ),
			'number' => 5,
		) );
		
		echo $before_widget;
		echo $before_title;
		echo $instance['title'];
		echo $after_title;
		
		$args = array(
			'posts_per_page'    => $instance['number'],
			'order'             => 'DESC',
			'orderby'           => 'post_date',
			'post_type'         => 'dwqa-question',
			'suppress_filters'  => false,
		);
		$questions = new WP_Query( $args );
		if ( $questions->have_posts() ) {
			echo '<div class="dwqa-popular-questions">';
			echo '<ul>';
			while ( $questions->have_posts() ) { $questions->the_post( );
				echo '<li>';
				echo get_the_author_link();
				if ( isset( $instance['question_date'] ) && $instance['question_date'] ) {
					echo '' . sprintf( esc_html__( '%s 前', 'be-question-answer' ), human_time_diff( get_post_time('U', true, get_the_ID() ) ) ) . ' ';
				}
				echo '<a href="'. get_permalink() .'" class="question-title">';
				the_title();
				echo '</a>';
				echo '</li>';
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
			'question_date' => false
		) );

		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>">标题</label>
		<input type="text" name="<?php echo $this->get_field_name( 'title' ) ?>" id="<?php echo $this->get_field_id( 'title' ) ?>" value="<?php echo $instance['title'] ?>" class="widefat">
		</p>
		<p><label for="<?php echo $this->get_field_id( 'number' ) ?>"><?php _e( 'Number of posts', 'be-question-answer' ) ?></label>
		<input type="text" name="<?php echo $this->get_field_name( 'number' ) ?>" id="<?php echo $this->get_field_id( 'number' ) ?>" value="<?php echo $instance['number'] ?>" class="widefat">
		</p>
		<p>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'question_date' ) ?>" id="<?php echo $this->get_field_id( 'question_date' ) ?>" <?php checked( 'on', $instance['question_date'] ) ?> class="widefat">
			<label for="<?php echo $this->get_field_id( 'question_date' ) ?>"><?php _e( 'Show question date', 'be-question-answer' ) ?></label>
		</p>
		<?php
	}
}

?>