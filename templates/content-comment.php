<?php global $comment; ?>
<div class="dwqa-comment">
	<div class="dwqa-comment-meta">
		<?php $user = get_user_by( 'id', $comment->user_id ); ?>
		<?php
			if (zm_get_option('cache_avatar')) {
				echo begin_avatar( $comment->comment_author_email, 96, '', get_comment_author() );
			} else {
				echo get_avatar( $comment->user_id, 96 );
			}
		?>

		<div class="beqa-comment-author"><?php echo get_comment_author() ?></div>
		<?php dwqa_print_user_badge( $comment->user_id, true ); ?>
		<div>
			<span><?php printf( _x( '评论于%s前', '%s = human-readable time difference', 'be-question-answer' ), human_time_diff( get_comment_time( 'U', true ) ) ); ?></span>
			<span class="dwqa-comment-actions">
				<?php if ( dwqa_current_user_can( 'edit_comment' ) ) : ?>
					<a href="<?php echo esc_url( add_query_arg( array( 'comment_edit' => $comment->comment_ID ) ) ) ?>"><?php _e( 'Edit', 'be-question-answer' ) ?></a>
				<?php endif; ?>
				<?php if ( dwqa_current_user_can( 'delete_comment' ) ) : ?>
					<a class="dwqa-delete-comment" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'dwqa-action-delete-comment', 'comment_id' => $comment->comment_ID ), admin_url( 'admin-ajax.php' ) ), '_dwqa_delete_comment' ) ?>"><?php _e( 'Delete', 'be-question-answer' ) ?></a>
				<?php endif; ?>
			</span>
		</div>
	</div>
	<?php comment_text(); ?>
</div>
