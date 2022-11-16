<?php if ( dwqa_current_user_can( 'post_question' ) ) : ?>
	<?php do_action( 'dwqa_before_question_submit_form' ); ?>
	<form method="post" class="dwqa-content-edit-form" enctype="multipart/form-data">
		<div class="beqa-title">
			<label for="question_title"><?php _e( 'Title', 'be-question-answer' ) ?></label>
			<?php $title = isset( $_POST['question-title'] ) ? sanitize_title( $_POST['question-title'] ) : ''; ?>
			<input type="text" data-nonce="<?php echo wp_create_nonce( '_dwqa_filter_nonce' ) ?>" id="question-title" class="dah bk" name="question-title" value="<?php echo $title ?>" tabindex="1">
		</div>
		<?php $content = isset( $_POST['question-content'] ) ? sanitize_text_field( $_POST['question-content'] ) : ''; ?>
		<div><?php dwqa_init_tinymce_editor( array( 'content' => $content, 'textarea_name' => 'question-content', 'id' => 'question-content' ) ) ?></div>
		<?php global $dwqa_general_settings; ?>
		<?php if ( isset( $dwqa_general_settings['enable-private-question'] ) && $dwqa_general_settings['enable-private-question'] ) : ?>
		<div class="beqa-select">
			<label for="question-status"><?php _e( 'Status', 'be-question-answer' ) ?></label>
			<select class="dwqa-select be-qa-select s-veil" id="question-status" name="question-status">
				<optgroup label="<?php _e( 'Who can see this?', 'be-question-answer' ) ?>">
					<option value="publish"><?php _e( 'Public', 'be-question-answer' ) ?></option>
					<option value="private"><?php _e( 'Only Me &amp; Admin', 'be-question-answer' ) ?></option>
				</optgroup>
			</select>
		</div>
		<?php endif; ?>
		<div class="beqa-select">
			<label for="question-category"><?php _e( 'Category', 'be-question-answer' ) ?></label>
			<?php
				wp_dropdown_categories( array(
					'name'          => 'question-category',
					'id'            => 'question-category',
					'class'         => 's-veil',
					'taxonomy'      => 'dwqa-question_category',
					'show_option_none' => '',
					'hide_empty'    => 0,
					'quicktags'     => array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close' ),
					'selected'      => isset( $_POST['question-category'] ) ? sanitize_text_field( $_POST['question-category'] ) : false,
				) );
			?>
		</div>
		<div>
			<label for="question-tag"><?php _e( 'Tag', 'be-question-answer' ) ?></label>
			<?php $tags = isset( $_POST['question-tag'] ) ? sanitize_text_field( $_POST['question-tag'] ) : ''; ?>
			<input type="text" class="be-question-tag dah" name="question-tag" value="<?php echo $tags ?>" >
		</div>
		<?php if ( dwqa_current_user_can( 'post_question' ) && !is_user_logged_in() ) : ?>
		<div>
			<label for="_dwqa_anonymous_email"><?php _e( 'Your Email', 'be-question-answer' ) ?></label>
			<?php $email = isset( $_POST['_dwqa_anonymous_email'] ) ? sanitize_email( $_POST['_dwqa_anonymous_email'] ) : ''; ?>
			<input type="email" class="be-anonymous-email" name="_dwqa_anonymous_email" value="<?php echo $email ?>" >
		</div>
		<div>
			<label for="_dwqa_anonymous_name"><?php _e( 'Your Name', 'be-question-answer' ) ?></label>
			<?php $name = isset( $_POST['_dwqa_anonymous_name'] ) ? sanitize_text_field( $_POST['_dwqa_anonymous_name'] ) : ''; ?>
			<input type="text" class="be-anonymous-name" name="_dwqa_anonymous_name" value="<?php echo $name ?>" >
		</div>
		<?php endif; ?>
		<?php wp_nonce_field( '_dwqa_submit_question' ) ?>
		<?php do_action('dwqa_before_question_submit_button'); ?>
		<input type="submit" class="question-submit dah" name="dwqa-question-submit" value="<?php _e( '提交', 'be-question-answer' ) ?>" >
		<?php dwqa_load_template( 'captcha', 'form' ); ?>
	</form>
	<?php do_action( 'dwqa_after_question_submit_form' ); ?>
<?php else : ?>
	<div class="alert"><?php _e( 'You do not have permission to submit a question','be-question-answer' ) ?></div>
<?php endif; ?>