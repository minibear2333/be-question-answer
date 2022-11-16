<?php do_action( 'dwqa_before_single_question_content' ); ?>
<div class="dwqa-question-item">
	<div class="dwqa-question-vote" data-nonce="<?php echo wp_create_nonce( '_dwqa_question_vote_nonce' ) ?>" data-post="<?php the_ID(); ?>">
		<a class="dwqa-vote dwqa-vote-up" href="#"></a>
		<span class="dwqa-vote-count"><?php echo dwqa_vote_count() ?></span>
		<a class="dwqa-vote dwqa-vote-down" href="#"></a>
	</div>
	<div class="dwqa-question-meta">
		<?php $user_id = get_post_field( 'post_author', get_the_ID() ) ? get_post_field( 'post_author', get_the_ID() ) : false ?>
		<div class="be-answer-meta">
			<div class="be-answer-meta-author"><?php echo get_the_author(); ?></div>
			<div class="be-answer-meta-user">
				<?php
					if (zm_get_option('cache_avatar')) {
						echo begin_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
					} else {
						echo be_avatar_author();
					}
				?>
			</div>
			<div class="be-answer-meta-time">
				<?php echo dwqa_print_user_badge( $user_id ); ?>提问于<?php echo human_time_diff( get_post_time( 'U', true ) ); ?>前
			</div>
		</div>
		<span class="dwqa-question-actions"><?php dwqa_question_button_action() ?></span>
	</div>
	<div class="dwqa-question-content"><?php the_content(); ?></div>
	<?php do_action('dwqa_after_show_content_question', get_the_ID()); ?>
	<div class="dwqa-question-footer">
		<div class="dwqa-question-meta">
			<?php echo get_the_term_list( get_the_ID(), 'dwqa-question_tag', '<span class="dwqa-question-tag">' . __( 'Question Tags: ', 'be-question-answer' ), ', ', '</span>' ); ?>
			<?php if ( dwqa_current_user_can( 'edit_question', get_the_ID() ) ) : ?>
				<?php if ( dwqa_is_enable_status() ) : ?>
				<div class="dwqa-question-status">
					<select id="dwqa-question-status" class="s-veil" data-nonce="<?php echo wp_create_nonce( '_dwqa_update_privacy_nonce' ) ?>" data-post="<?php the_ID(); ?>">
						<optgroup label="<?php _e( 'Status', 'be-question-answer' ); ?>">
							<option <?php selected( dwqa_question_status(), 'open' ) ?> value="open"><?php _e( 'Open', 'be-question-answer' ) ?></option>
							<option <?php selected( dwqa_question_status(), 'closed' ) ?> value="close"><?php _e( 'Closed', 'be-question-answer' ) ?></option>
							<option <?php selected( dwqa_question_status(), 'resolved' ) ?> value="resolved"><?php _e( 'Resolved', 'be-question-answer' ) ?></option>
						</optgroup>
					</select>
				</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	</div>
	<?php do_action( 'dwqa_before_single_question_comment' ) ?>
	<?php comments_template(); ?>
	<?php do_action( 'dwqa_after_single_question_comment' ) ?>
	<?php do_action( 'dwqa_after_single_question_content' ); ?>
</div>