<div class="dwqa-questions-archive">
	<?php do_action( 'dwqa_before_questions_archive' ) ?>
		<div class="dwqa-questions-list<?php global $dwqa_general_settings; if ( isset( $dwqa_general_settings['show-status-icon'] ) && $dwqa_general_settings['show-status-icon'] && dwqa_is_enable_status() ) { ?> beqa-questions-list<?php } ?>">
		<?php do_action( 'bp_dwqa_before_questions_list' ) ?>
		<?php if ( dwqa_has_question() ) : ?>
			<?php while ( dwqa_has_question() ) : dwqa_the_question(); ?>
				<?php if ( get_post_status() == 'publish' || ( get_post_status() == 'private' && ( dwqa_current_user_can( 'edit_question', get_the_ID() ) || dwqa_current_user_can( 'manage_question' ) || get_current_user_id() == get_post_field( 'post_author', get_the_ID() ) ) ) ) : ?>
					<?php dwqa_load_template( 'content', 'question' ) ?>
				<?php endif; ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php dwqa_load_template( 'content', 'none' ) ?>
		<?php endif; ?>
		<?php do_action( 'dwqa_after_questions_list' ) ?>
		</div>
		<div class="dwqa-questions-footer">
			<?php dwqa_question_paginate_link() ?>
			<?php if ( dwqa_current_user_can( 'post_question' ) ) : ?>
				<div class="dwqa-ask-question"><a href="<?php echo dwqa_get_ask_link(); ?>"><?php _e( 'Ask Question', 'be-question-answer' ); ?></a></div>
			<?php endif; ?>
		</div>

	<?php do_action( 'dwqa_after_questions_archive' ); ?>
</div>