<?php 
function dwqa_question_status_box_html( $post ){
		$meta = get_post_meta( $post->ID, '_dwqa_status', true );
		$meta = $meta ? $meta : 'open';
	?>
	<p>
		<label for="dwqa-question-status">
			<?php _e( 'Status','be-question-answer' ) ?><br>&nbsp;
			<select name="dwqa-question-status" id="dwqa-question-status" class="widefat">
				<option <?php selected( $meta, 'open' ); ?> value="open"><?php _e( 'Open','be-question-answer' ) ?></option>
				<option <?php selected( $meta, 'pending' ); ?> value="pending"><?php _e( 'Pending','be-question-answer' ) ?></option>
				<option <?php selected( $meta, 'resolved' ); ?> value="resolved"><?php _e( 'Resolved','be-question-answer' ) ?></option>
				<option <?php selected( $meta, 're-open' ); ?> value="re-open"><?php _e( 'Re-Open','be-question-answer' ) ?></option>
				<option <?php selected( $meta, 'closed' ); ?> value="closed"><?php _e( 'Closed','be-question-answer' ) ?></option>
			</select>
		</label>
	</p>    
	<p>
		<label for="dwqa-question-sticky">
			<?php _e( 'Sticky','be-question-answer' ); ?><br><br>&nbsp;
			<?php
				$sticky_questions = get_option( 'dwqa_sticky_questions', array() );
			?>
			<input <?php checked( true, in_array( $post->ID, $sticky_questions ), true ); ?> type="checkbox" name="dwqa-question-sticky" id="dwqa-question-sticky" value="1" ><span class="description"><?php _e( 'Pin question to top of archive page.','be-question-answer' ); ?></span>
		</label>
	</p>
	<?php
}

class DWQA_Metaboxes {
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'answers_metabox' ) );
		add_filter( 'postbox_classes_dwqa-question_dwqa-answers', array( $this, 'add_css_class_metabox' ) );
		add_action( 'admin_init', array( $this, 'add_status_metabox' ) );
		add_action( 'save_post', array( $this, 'question_status_save' ) );
	}

	//Add a metabox that was used for display list of answers of a questions
	public function answers_metabox(){
		add_meta_box( 'dwqa-answers', __( 'Answers','be-question-answer' ), array( $this, 'metabox_answers_list' ), 'dwqa-question' );
	}

	/**
	 * generate html for metabox that was used for display list of answers of a questions
	 */
	public function metabox_answers_list(){
		$answer_list_table = new DWQA_Answer_List_Table();
		$answer_list_table->display();
	}

	public function add_css_class_metabox( $classes ){
		$classes[] = 'dwqa-answer-list';
		return $classes;
	}
	/**
	 * Add metabox for question status meta data
	 * @return void
	 */
	public function add_status_metabox(){
		add_meta_box( 'dwqa-post-status', __( 'Question Meta Data','be-question-answer' ), 'dwqa_question_status_box_html', 'dwqa-question', 'side', 'high' );
	}

	public function question_status_save( $post_id ){
		if ( ! wp_is_post_revision( $post_id ) ) {
			if ( isset( $_POST['dwqa-question-status'] ) ) {
				update_post_meta( $post_id, '_dwqa_status', esc_html( $_POST['dwqa-question-status'] ) );
			}
			if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {

				$sticky_questions = get_option( 'dwqa_sticky_questions', array() );
				if ( isset( $_POST['dwqa-question-sticky'] ) && sanitize_text_field( $_POST['dwqa-question-sticky'] ) ) {
					if ( ! in_array( $post_id, $sticky_questions ) ) {
						$sticky_questions[] = $post_id;
						update_option( 'dwqa_sticky_questions', $sticky_questions );
					}
				} else {
					if ( in_array( $post_id, $sticky_questions ) ) {
						if ( ($key = array_search( $post_id, $sticky_questions ) ) !== false ) {
							unset( $sticky_questions[$key] );
						}
						update_option( 'dwqa_sticky_questions', $sticky_questions );
					}
				}
			}
		}
	}
}

?>