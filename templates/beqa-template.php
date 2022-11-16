<?php
/*
Template Name: 问答系统
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<div id="<?php global $wpdb, $post; if ( get_post_meta($post->ID, 'sidebar_l', true) ) { ?>primary-l<?php } else { ?>primary<?php } ?>" class="content-area<?php global $wpdb, $post; if ( get_post_meta( $post->ID, 'no_sidebar', true ) || ( zm_get_option('single_no_sidebar') ) ) { ?> no-sidebar<?php } ?><?php if (zm_get_option('meta_b')) { ?> meta-b<?php } ?>">
	<main id="main" class="site-main<?php if (zm_get_option('p_first') ) { ?> p-em<?php } ?><?php if (get_post_meta($post->ID, 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'template/content', 'page' ); ?>
		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template( '', true ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
	</main>
</div>

<?php if ( !get_post_meta($post->ID, 'no_sidebar', true) ) { ?>
	<?php if ( class_exists( 'DW_Question_Answer' ) ) { ?>
		<?php if (!is_singular( 'dwqa-question' ) ) { ?>
			<?php get_sidebar(); ?>
		<?php } ?>
	<?php } else { ?>
		<?php get_sidebar(); ?>
	<?php } ?>
<?php } ?>
<?php get_footer(); ?>