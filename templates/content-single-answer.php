
<div class="<?php echo dwqa_post_class() ?>">
	<div class="dwqa-answer-vote" data-nonce="<?php echo wp_create_nonce( '_dwqa_answer_vote_nonce' ) ?>" data-post="<?php the_ID(); ?>">
		<a class="dwqa-vote dwqa-vote-up" href="#"></a>
		<span class="dwqa-vote-count"><?php echo dwqa_vote_count() ?></span>
		<a class="dwqa-vote dwqa-vote-down" href="#"></a>
	</div>
	<?php if ( dwqa_current_user_can( 'edit_question', dwqa_get_question_from_answer_id() ) ) : ?>
		<?php $action = dwqa_is_the_best_answer() ? 'dwqa-unvote-best-answer' : 'dwqa-vote-best-answer' ; ?>
		<a class="dwqa-pick-best-answer" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'answer' => get_the_ID(), 'action' => $action ), admin_url( 'admin-ajax.php' ) ), '_dwqa_vote_best_answer' ) ) ?>"><?php _e( 'Best Answer', 'be-question-answer' ) ?></a>
	<?php elseif ( dwqa_is_the_best_answer() ) : ?>
		<span class="dwqa-pick-best-answer"><?php _e( 'Best Answer', 'be-question-answer' ) ?></span>
	<?php endif; ?>
	<div class="dwqa-answer-meta">
		<?php $user_id = get_post_field( 'post_author', get_the_ID() ) ? get_post_field( 'post_author', get_the_ID() ) : 0 ?>
		<?php
			if (zm_get_option('cache_avatar')) {
				$useravatar = begin_avatar( $user_id, 48, '', get_the_author() );
			} else {
				$useravatar = get_avatar( $user_id, 48 );
			}
		?>
		<?php printf( __( '<span class="be-answer-meta">%s<span class="be-answer-meta-user">%s</span> %s<span class="be-answer-meta-time">回答于%s前</span></span>', 'be-question-answer' ), $useravatar, get_the_author(), dwqa_print_user_badge( $user_id ), human_time_diff( get_post_time( 'U', true ) ) ) ?>
		<span class="dwqa-answer-actions"><?php dwqa_answer_button_action(); ?></span>
		<?php if ( 'private' == get_post_status() ) : ?>
			<span><?php _e( '&nbsp;&bull;&nbsp;', 'be-question-answer' ); ?></span>
			<span><?php _e( 'Private', 'be-question-answer' ) ?></span>
		<?php endif; ?>
	</div>
	<div class="dwqa-answer-content"><?php the_content(); ?></div>
	<?php do_action('dwqa_after_show_content_answer', get_the_ID()); ?>
	<?php comments_template(); ?>
</div>
