<div class="dwqa-answers">
	<?php do_action( 'dwqa_before_answers' ) ?>
	<?php if ( dwqa_has_answers() ) : ?>
	<div class="dwqa-answers-title">答案 <span class="dwqa-answers-count"><?php echo dwqa_question_answers_count( get_the_ID() ); ?></span></div>
	<div class="dwqa-answers-list">
		<?php do_action( 'dwqa_before_answers_list' ) ?>
			<?php while ( dwqa_has_answers() ) : dwqa_the_answers(); ?>
				<?php $question_id = dwqa_get_post_parent_id( get_the_ID() ); ?>
				<?php if ( ( 'private' == get_post_status() && ( dwqa_current_user_can( 'edit_answer', get_the_ID() ) || dwqa_current_user_can( 'edit_question', $question_id ) ) ) || 'publish' == get_post_status() ) : ?>
					<?php dwqa_load_template( 'content', 'single-answer' ); ?>
				<?php endif; ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php do_action( 'dwqa_after_answers_list' ) ?>
	</div>
	<?php endif; ?>
        <?php if ( !is_user_logged_in() ): ?>
                          <div class="dwqa-ask-question"><a href="/wp-login.php" target="_blank">登陆后回答</a></div>
        <?php else : ?>
				<?php if ( dwqa_current_user_can( 'post_answer' ) && !dwqa_is_closed( get_the_ID() ) ) : ?>
				<?php dwqa_load_template( 'answer', 'submit-form' ) ?>
			<?php endif; ?>
	    <?php endif; ?>
	    <?php do_action( 'dwqa_after_answers' ); ?>
	<div class="clear"></div>
</div>
