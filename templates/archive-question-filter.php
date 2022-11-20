<?php
global $dwqa_general_settings;
$sort = isset( $_GET['sort'] ) ? esc_html( $_GET['sort'] ) : '';
$filter = isset( $_GET['filter'] ) ? esc_html( $_GET['filter'] ) : 'all';
?>
<div class="dwqa-question-filter">
	<a href="/dwqa-questions.html">所有分类</a>
<?php 
$cats = get_categories( array(
		'type'                     => 'dwqa-question',
		'hide_empty'               => 0,
		'taxonomy'                 => 'dwqa-question'. '_category',
) );
foreach ($cats as $v){
	$is_active='';
	if(strpos($_SERVER['REQUEST_URI'] ,$v->slug) !== false){ 
		$is_active='active'; 
	}
	echo '<a href="/question/category/'.$v->slug.'" class="'.$is_active.'">'.$v->name.'</a>';
}
?>
	<div class="beqa-sort-by">
		<select id="dwqa-sort-by" class="dwqa-sort-by s-veil" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
			<option selected disabled><?php _e( 'Sort by', 'be-question-answer' ); ?></option>
			<option <?php selected( $sort, 'views' ) ?> value="<?php echo esc_url( add_query_arg( array( 'sort' => 'views' ) ) ) ?>"><?php _e( 'Views', 'be-question-answer' ) ?></option>
			<option <?php selected( $sort, 'answers' ) ?> value="<?php echo esc_url( add_query_arg( array( 'sort' => 'answers' ) ) ) ?>"><?php _e( 'Answers', 'be-question-answer' ); ?></option>
			<option <?php selected( $sort, 'votes' ) ?> value="<?php echo esc_url( add_query_arg( array( 'sort' => 'votes' ) ) ) ?>"><?php _e( 'Votes', 'be-question-answer' ) ?></option>
		</select>
	</div>
</div>

<div class="dwqa-question-type">
	<?php if ( !isset( $_GET['user'] ) ) : ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'all' ) ) ) ?>" class="<?php echo 'all' == $filter ? 'active' : '' ?>"><?php _e( 'All', 'be-question-answer' ); ?></a>
		<?php if ( dwqa_is_enable_status() ) : ?>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'open' ) ) ) ?>" class="<?php echo 'open' == $filter ? 'active' : '' ?>"><?php _e( 'Open', 'be-question-answer' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'resolved' ) ) ) ?>" class="<?php echo 'resolved' == $filter ? 'active' : '' ?>"><?php _e( 'Resolved', 'be-question-answer' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'closed' ) ) ) ?>" class="<?php echo 'closed' == $filter ? 'active' : '' ?>"><?php _e( 'Closed', 'be-question-answer' ); ?></a>
		<?php endif; ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'unanswered' ) ) ) ?>" class="<?php echo 'unanswered' == $filter ? 'active' : '' ?>"><?php _e( 'Unanswered', 'be-question-answer' ); ?></a>
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'my-questions' ) ) ) ?>" class="<?php echo 'my-questions' == $filter ? 'active' : '' ?>"><?php _e( 'My questions', 'be-question-answer' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'my-subscribes' ) ) ) ?>" class="<?php echo 'my-subscribes' == $filter ? 'active' : '' ?>"><?php _e( 'My subscribes', 'be-question-answer' ); ?></a>
		<?php endif; ?>
	<?php else : ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'all' ) ) ) ?>" class="<?php echo 'all' == $filter ? 'active' : '' ?>"><?php _e( 'Questions', 'be-question-answer' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'subscribes' ) ) ) ?>" class="<?php echo 'subscribes' == $filter ? 'active' : '' ?>"><?php _e( 'Subscribes', 'be-question-answer' ); ?></a>
	<?php endif; ?>
	
</div>