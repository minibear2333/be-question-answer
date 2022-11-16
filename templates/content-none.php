<?php if ( ! dwqa_current_user_can( 'read_question' ) ) : ?>
	<div class="dwqa-alert dwqa-alert-info"><?php _e( 'You do not have permission to view questions', 'be-question-answer' ) ?></div>
<?php else : ?>
	<div class="dwqa-alert dwqa-alert-info"><?php _e( 'Sorry, but nothing matched your filter', 'be-question-answer' ) ?></div>
<?php endif; ?>