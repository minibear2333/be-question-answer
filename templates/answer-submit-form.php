<div class="dwqa-answer-form">
	<?php do_action( 'dwqa_before_answer_submit_form' ); ?>
	<div class="dwqa-answer-form-title"><?php _e( 'Your Answer', 'be-question-answer' ) ?></div>
	<form name="dwqa-answer-form" id="dwqa-answer-form" method="post" enctype="multipart/form-data">
		<?php dwqa_print_notices(); ?>
		<?php $content = isset( $_POST['answer-content'] ) ? sanitize_text_field( $_POST['answer-content'] ) : ''; ?>
		<?php dwqa_init_tinymce_editor( array( 'content' => $content, 'textarea_name' => 'answer-content', 'id' => 'dwqa-answer-content' ) ) ?>
		<div class="beqa-answer-select">
			<select class="dwqa-select be-qa-select s-veil" name="dwqa-status">
				<optgroup label="<?php _e( 'Who can see this?', 'be-question-answer' ) ?>">
					<option value="publish"><?php _e( 'Public', 'be-question-answer' ) ?></option>
					<option value="private"><?php _e( 'Only Me &amp; Admin', 'be-question-answer' ) ?></option>
				</optgroup>
			</select>
		</div>

		<?php if ( dwqa_current_user_can( 'post_answer' ) && !is_user_logged_in() ) : ?>
		<div>
			<label for="user-email"><?php _e( 'Your Email', 'be-question-answer' ) ?></label>
			<?php $email = isset( $_POST['user-email'] ) ? sanitize_email( $_POST['user-email'] ) : ''; ?>
			<input type="email" class="be-anonymous-email" name="user-email" value="<?php echo $email ?>" >
		</div>
		<div>
			<label for="user-name"><?php _e( 'Your Name', 'be-question-answer' ) ?></label>
			<?php $name = isset( $_POST['user-name'] ) ? esc_html( $_POST['user-name'] ) : ''; ?>
			<input type="text" class="be-anonymous-name" name="user-name" value="<?php echo $name ?>" >
		</div>
		<?php endif; ?>

		<div>
			<?php do_action('dwqa_before_answer_submit_button'); ?>
			<input type="submit" name="submit-answer" class="dwqa-btn dwqa-btn-primary" value="<?php _e( '提交', 'be-question-answer' ) ?>">
			<input type="hidden" name="question_id" value="<?php the_ID(); ?>">
			<input type="hidden" name="dwqa-action" value="add-answer">
			<?php wp_nonce_field( '_dwqa_add_new_answer' ) ?>
			<?php dwqa_load_template( 'captcha', 'form' ); ?>
		</div>
	</form>
	<?php do_action( 'dwqa_after_answer_submit_form' ); ?>
</div>