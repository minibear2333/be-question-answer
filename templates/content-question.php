<div class="<?php echo dwqa_post_class(); ?>">
	<div class="dwqa-question-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
	<div class="dwqa-question-meta">
		<?php
			global $post;
			$user_id = get_post_field( 'post_author', get_the_ID() ) ? get_post_field( 'post_author', get_the_ID() ) : false;
			$time = human_time_diff( get_post_time( 'U', true ) );
			$text = __( 'asked', 'be-question-answer' );
			$latest_answer = dwqa_get_latest_answer();
			if ( $latest_answer ) {
				$time = human_time_diff( strtotime( $latest_answer->post_date_gmt ) );
				$text = __( 'answered', 'be-question-answer' );
			}
		?>
		<?php
			if (zm_get_option('cache_avatar')) {
				$useravatar = begin_avatar( $user_id, 48, '', get_the_author() );
			} else {
				$useravatar = get_avatar( $user_id, 48 );
			}
		?>
		<?php printf( __( '%s<span class="beqa-meta-user">%s</span> %s%s前', 'be-question-answer' ), $useravatar, get_the_author(), $text, $time ) ?>
		<?php dwqa_question_print_status() ?>
	</div>

	<div class="dwqa-question-stats">
		<span class="dwqa-answers-count">
			<?php $answers_count = dwqa_question_answers_count(); ?>
			<?php printf( __( '<strong>%1$s</strong> answers', 'be-question-answer' ), $answers_count ); ?>
		</span>
		<span class="dwqa-votes-count">
			<?php $vote_count = dwqa_vote_count() ?>
			<?php printf( __( '<strong>%1$s</strong> votes', 'be-question-answer' ), $vote_count ); ?>
		</span>
		<?php echo get_the_term_list( get_the_ID(), 'dwqa-question_category', '<span class="dwqa-question-category">' . __( '', 'be-question-answer' ), ', ', '</span>' ); ?>
		<span class="dwqa-views-count">
			<?php $views_count = dwqa_question_views_count() ?>
			<?php printf( __( '<strong>%1$s</strong> views', 'be-question-answer' ), $views_count ); ?>
		</span>
	</div>
</div>