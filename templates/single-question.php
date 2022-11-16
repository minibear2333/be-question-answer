<div class="dwqa-single-question">
<?php if ( have_posts() ) : ?>
	<?php do_action( 'dwqa_before_single_question' ) ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php if ( !dwqa_is_edit() ) : ?>
			<?php dwqa_load_template( 'content', 'single-question' ) ?>
		<?php else : ?>
			<?php dwqa_load_template( 'content', 'edit' ) ?>
		<?php endif; ?>
	<?php endwhile; ?>
	<?php do_action( 'dwqa_after_single_question' ) ?>
<?php endif; ?>
</div>
