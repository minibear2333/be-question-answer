<?php  

class DWQA_Notifications {

	public function __construct() {

		if(get_option('dwqa_enable_email_delay')){
			add_action('dwqa_new_question_notify', array( $this, 'new_question_notify' ), 10, 2);
			add_action('dwqa_new_answer_notify', array( $this, 'new_answer_notify' ), 10, 2);
			add_action('dwqa_new_comment_notify', array( $this, 'new_comment_notify' ), 10, 2);

			add_action( 'dwqa_add_question', array( $this, 'dwqa_queue_add_question' ), 10, 2 );
			add_action( 'dwqa_add_answer', array( $this, 'dwqa_queue_add_answer' ), 10, 2 );
			add_action( 'dwqa_add_comment', array( $this, 'dwqa_queue_insert_comment' ), 10, 2 );
		}else{
			add_action( 'dwqa_add_question', array( $this, 'new_question_notify' ), 10, 2 );
			add_action( 'dwqa_add_comment', array( $this, 'new_comment_notify' ), 10, 2 );
			add_action( 'dwqa_add_answer', array( $this, 'new_answer_notify' ), 10, 2 );
		}
		
	}
	
	public function dwqa_queue_add_question($question_id, $user_id){
		wp_schedule_single_event( time() + 5, 'dwqa_new_question_notify', array($question_id, $user_id) );
	}
	public function dwqa_queue_add_answer($answer_id, $question_id){
		wp_schedule_single_event( time() + 5, 'dwqa_new_answer_notify', array($answer_id, $question_id) );
	}
	public function dwqa_queue_insert_comment($comment_id, $comment){
		wp_schedule_single_event( time() + 5, 'dwqa_new_comment_notify', array($comment_id, $comment) );
	}
	
	public function new_question_notify( $question_id, $user_id ) {
		// receivers
		$admin_email = $this->get_admin_email();

		$enabled = get_option( 'dwqa_subscrible_enable_new_question_notification', 1 );
		if ( ! $enabled ) {
			return false;
		}
		$question = get_post( $question_id );
		if ( ! $question ) {
			return false;
		}

		$subject = get_option( 'dwqa_subscrible_new_question_email_subject' );
		if ( ! $subject ) {
			$subject = __( 'A new question was posted on {site_name}', 'be-question-answer' );
		}
		$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $subject );
		$subject = str_replace( '{question_title}', $question->post_title, $subject );
		$subject = str_replace( '{question_id}', $question->ID, $subject );
		$subject = str_replace( '{username}', get_the_author_meta( 'display_name', $user_id ), $subject );
		
		$message = dwqa_get_mail_template( 'dwqa_subscrible_new_question_email', 'new-question' );
		if ( ! $message ) {
			return false;
		}
		// Replacement
		
		$admin = get_user_by( 'email', $admin_email[0] );
		if ( $admin ) {
			$message = str_replace( '{admin}', get_the_author_meta( 'display_name', $admin->ID ), $message );
		}
		//sender
		$message = str_replace( '{user_avatar}', get_avatar( $user_id, '60' ), $message );
		$message = str_replace( '{user_link}', dwqa_get_author_link( $user_id ), $message );
		$message = str_replace( '{username}', get_the_author_meta( 'display_name', $user_id ), $message );
		//question
		$message = str_replace( '{question_link}', get_permalink( $question_id ), $message );
		$message = str_replace( '{question_title}', $question->post_title, $message );
		$message = str_replace( '{question_content}', $question->post_content, $message );
		// Site info
		$logo = get_option( 'dwqa_subscrible_email_logo', '' );
		$logo = $logo ? '<img src="' . $logo . '" alt="' . get_bloginfo( 'name' ) . '" style="max-width: 100%; height: auto;" />' : '';
		$message = str_replace( '{site_logo}', $logo, $message );
		$message = str_replace( '{site_name}', get_bloginfo( 'name' ), $message );
		$message = str_replace( '{site_description}', get_bloginfo( 'description' ), $message );
		$message = str_replace( '{site_url}', site_url(), $message );
		
		// start send out email
		foreach( $admin_email as $to ) {
			if ( is_email( $to ) )
				$sended = wp_mail( sanitize_email( $to ), $subject, $message );
		}
	}

	public function new_answer_notify( $answer_id, $question_id ) {
		// print_r( $answer_id ); die;
		if ( 'dwqa-answer' != get_post_type( $answer_id ) ) {
			return false;
		}

		if ( 'dwqa-question' != get_post_type( $question_id ) ) {
			return false;
		}

		// default value
		$site_name = get_bloginfo( 'name' );
		$question_title = get_the_title( $question_id );
		$answer_content = get_post_field( 'post_content', $answer_id );
		$question_link = get_permalink( $question_id );
		$answer_link = trailingslashit( $question_link ) . '#answer-' . $answer_id;
		$site_description = get_bloginfo( 'description' );
		$site_url = site_url();
		$enable_send_copy = get_option( 'dwqa_subscrible_send_copy_to_admin' );
		$admin_email = get_bloginfo( 'admin_email' );
		$site_logo = get_option( 'dwqa_subscrible_email_logo', '' );
		$site_logo = $site_logo ? '<img src="' . $site_logo . '" alt="' . get_bloginfo( 'name' ) . '" style="max-width: 100%; height: auto;" />' : '';

		// for answer
		$answer_is_anonymous = dwqa_is_anonymous( $answer_id );
		if ( $answer_is_anonymous ) {
			$user_answer_id = 0;
			$user_answer_display_name = get_post_meta( $answer_id, '_dwqa_anonymous_name', true );
			$user_answer_display_name = $user_answer_display_name ? sanitize_text_field( $user_answer_display_name ) : __( 'Anonymous', 'be-question-answer' );
			$user_answer_email = get_post_meta( $answer_id, '_dwqa_anonymous_email', true );
			$user_answer_email = $user_answer_email ? sanitize_email( $user_answer_email ) : false;
		} else {
			$user_answer_id = get_post_field( 'post_author', $answer_id );
			$user_answer_display_name = get_the_author_meta( 'display_name', $user_answer_id );
			$user_answer_email = get_the_author_meta( 'user_email', $user_answer_id );
		}

		if ( $user_answer_email ) {
			$user_answer_avatar = get_avatar( $user_answer_email, 60 );
		} else {
			$user_answer_avatar = get_avatar( $user_answer_id, 60 );
		}
		
		// for question
		$question_is_anonymous = dwqa_is_anonymous( $question_id );
		if ( $question_is_anonymous ) {
			$user_question_id = 0;
			$user_question_display_name = get_post_meta( $question_id, '_dwqa_anonymous_name', true );
			$user_question_display_name = $user_question_display_name ? sanitize_text_field( $user_question_display_name ) : __( 'Anonymous', 'be-question-answer' );
			$user_question_email = get_post_meta( $question_id, '_dwqa_anonymous_email', true );
			$user_question_email = $user_question_email ? sanitize_email( $user_question_email ) : false;
		} else {
			$user_question_id = get_post_field( 'post_author', $question_id );
			$user_question_display_name = get_the_author_meta( 'display_name', $user_question_id );
			$user_question_email = get_the_author_meta( 'user_email', $user_question_id );
		}

		if ( $user_question_email ) {
			$user_question_avatar = get_avatar( $user_question_email, 60 );
		} else {
			$user_question_avatar = get_avatar( $user_question_id, 60 );
		}

		// start send to question author
		// 回答者和提问者不是一个人的情况下才发邮件
		$answer_notify_for_question_enabled = get_option( 'dwqa_subscrible_enable_new_answer_notification', 1 );
		if ( $user_question_email && $answer_notify_for_question_enabled && absint( $user_answer_id ) != absint( $user_question_id ) ) {
			$subject = get_option( 'dwqa_subscrible_new_answer_email_subject', __( '[{site_name}] A new answer for "{question_title}" was posted on {site_name}', 'be-question-answer' ) );
			$subject = str_replace( '{site_name}', esc_html( $site_name ), $subject );
			$subject = str_replace( '{question_title}', $question_title, $subject );
			$subject = str_replace( '{question_id}', absint( $question_id ), $subject );
			$subject = str_replace( '{username}', esc_html( $user_question_display_name ), $subject );
			$subject = str_replace( '{answer_author}', esc_html( $user_answer_display_name ), $subject );

			$message = dwqa_get_mail_template( 'dwqa_subscrible_new_answer_email', 'new-answer' );
			$message = apply_filters( 'dwqa_get_new_answer_email_to_author_message', $message, $question_id, $answer_id );
			if ( !$message ) {
				return false;
			}

			$message = str_replace( '{answer_avatar}', $user_answer_avatar, $message );
			$message = str_replace( '{answer_author}', esc_html( $user_answer_display_name ), $message );
			$message = str_replace( '{question_link}', esc_url( $question_link ), $message );
			$message = str_replace( '{question_author}', esc_html( $user_question_display_name ), $message );
			$message = str_replace( '{answer_link}', esc_url( $answer_link ), $message );
			$message = str_replace( '{question_title}', $question_title, $message );
			$message = str_replace( '{answer_content}', wp_kses_post( $answer_content ), $message );
			$message = str_replace( '{site_logo}', $site_logo, $message );
			$message = str_replace( '{site_name}', esc_html( $site_name ), $message );
			$message = str_replace( '{site_description}', esc_html( $site_description ), $message );
			$message = str_replace( '{site_url}', esc_url( $site_url ), $message );

			$sender = wp_mail( $user_question_email, $subject, $message );
			if ( $enable_send_copy ) {
				$sender = wp_mail( $admin_email, '【抄送管理员】'.$subject, $message );
			}	
		}

		// 发送给关注者逻辑（TODO 为什么管理员没加入到关注者列表呢？没看到这块代码）
		// get all follower email lists
		$followers = get_post_meta( $question_id, '_dwqa_followers' );
		$followers_email = array();
		if ( !empty( $followers ) && is_array( $followers ) ) {
			foreach( $followers as $follower ) {
				if ( is_numeric( $follower ) ) {
					// prevent send to answer author and question author
					if ( absint( $follower ) == $user_answer_id || absint( $follower ) == $user_question_id ) continue;
					// get user email has registered
					$followers_email[] = get_the_author_meta( 'user_email', $follower );
				} else {
					// prevent send to question author and answer author
					if ( sanitize_email( $user_answer_email ) == sanitize_email( $follower ) || sanitize_email( $user_question_email ) == sanitize_email( $follower ) ) continue;
					// get anonymous email
					$followers_email[] = sanitize_email( $follower );
				}
			}
		}

		// start send to followers
		$answer_notify_enabled = get_option( 'dwqa_subscrible_enable_new_answer_followers_notification', 1 );
		if ( $answer_notify_enabled && !empty( $followers_email ) && is_array( $followers_email ) && 'private' != get_post_status( $answer_id ) ) {
			$subject = get_option( 'dwqa_subscrible_new_answer_followers_email_subject', __( '[{site_name}] You have a new answer for your followed question', 'be-question-answer' ) );
			$subject = str_replace( '{site_name}', esc_html( $site_name ), $subject );
			$subject = str_replace( '{question_title}', $question_title, $subject );
			$subject = str_replace( '{answer_author}', esc_html( $user_answer_display_name ), $subject );

			$message = dwqa_get_mail_template( 'dwqa_subscrible_new_answer_followers_email', 'new-answer-followers' );
			$message = apply_filters( 'dwqa_get_new_answer_email_to_followers_message', $message, $answer_id, $question_id );

			if ( !$message ) {
				return false;
			}

			$message = str_replace( '您好 {follower},', '', $message );
			$message = str_replace( '{answer_author}', esc_html( $user_answer_display_name ), $message );
			$message = str_replace( '{question_link}', esc_url( $question_link ), $message );
			$message = str_replace( '{answer_link}', esc_url( $answer_link ), $message );
			$message = str_replace( '{question_title}', $question_title, $message );
			$message = str_replace( '{answer_content}', wp_kses_post( $answer_content ), $message );
			$message = str_replace( '{answer_avatar}', $user_answer_avatar, $message );
			$message = str_replace( '{site_logo}', $site_logo, $message );
			$message = str_replace( '{site_name}', esc_html( $site_name ), $message );
			$message = str_replace( '{site_description}', esc_html( $site_description ), $message );
			$message = str_replace( '{site_url}', esc_url( $site_url ), $message );
			
			$emails = array_merge( $followers_email, $admin_email );
			$followers_email = array_unique($followers_email);
			$sender = wp_mail( $followers_email, $subject, $message );
			if ( $enable_send_copy ) {
				$sender = wp_mail( $admin_email, '【抄送管理员】'.$subject, $message );
			}			
		}

		
	}

	public function new_comment_notify( $comment_id, $comment ) {
		$parent = get_post_type( $comment->comment_post_ID );
		

		//Admin email
		$admin_email = get_bloginfo( 'admin_email' );
		$enable_send_copy = get_option( 'dwqa_subscrible_send_copy_to_admin' );

		if ( 1 != $comment->comment_approved ) { 
			return false;
		}
		if  ( 'dwqa-question' != $parent && 'dwqa-answer' != $parent ) {
			return false;
		}
		if ( $parent == 'dwqa-question' ) {
			$enabled = get_option( 'dwqa_subscrible_enable_new_comment_question_notification', 1 );
		} elseif ( $parent == 'dwqa-answer' ) {
			$enabled = get_option( 'dwqa_subscrible_enable_new_comment_answer_notification', 1 );
		}
	
		if ( ! $enabled ) {
			return false;
		}

		// 父节点
		$post_parent = get_post( $comment->comment_post_ID );

		// 判断父节点用户是否是匿名用户且没有输入正确的邮箱，如果是那就没必要发邮件了
		if ( dwqa_is_anonymous( $comment->comment_post_ID ) ) {
			$post_parent_email = get_post_meta( $comment->comment_post_ID, '_dwqa_anonymous_email', true );
			if ( ! is_email( $post_parent_email ) ) {
				return false;
			}
		} else {
			// 如果评论者不是答案和问题的作者自动添加关注
			if ( $post_parent->post_author != $comment->user_id ) {
				if ( ! dwqa_is_followed( $post_parent->ID, $comment->user_id ) ) {
					add_post_meta( $post_parent->ID, '_dwqa_followers', $comment->user_id );
				}
			}
			// 父用户邮箱
			$post_parent_email = get_the_author_meta( 'user_email', $post_parent->post_author );
		}

		// 构建邮件主题，内容，格式等必要信息
		if ( $parent == 'dwqa-question' ) {
			$message = dwqa_get_mail_template( 'dwqa_subscrible_new_comment_question_email', 'new-comment-question' );    
			$subject = get_option( 'dwqa_subscrible_new_comment_question_email_subject',__( '[{site_name}] You have a new comment for question {question_title}', 'be-question-answer' ) );
			$message = str_replace( '{question_author}', get_the_author_meta( 'display_name', $post_parent->post_author ), $message );
			$question = $post_parent;
		} else {
			$message = dwqa_get_mail_template( 'dwqa_subscrible_new_comment_answer_email', 'new-comment-answer' );
			$subject = get_option( 'dwqa_subscrible_new_comment_answer_email_subject',__( '[{site_name}] You have a new comment for answer', 'be-question-answer' ) );
			$message = str_replace( '{answer_author}', get_the_author_meta( 'display_name', $post_parent->post_author ), $message );
			$question_id = dwqa_get_post_parent_id( $post_parent->ID );
			$question = get_post( $question_id );
		}
		$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $subject );
		$subject = str_replace( '{question_title}', $question->post_title, $subject );
		$subject = str_replace( '{question_id}', $question->ID, $subject );
		$subject = str_replace( '{username}',get_the_author_meta( 'display_name', $comment->user_id ), $subject );

		if ( ! $message ) {
			return false;
		}
		// logo replace
		$logo = get_option( 'dwqa_subscrible_email_logo','' );
		$logo = $logo ? '<img src="'.$logo.'" alt="'.get_bloginfo( 'name' ).'" style="max-width: 100%; height: auto;" />' : '';
		$subject = str_replace( '{comment_author}', get_the_author_meta( 'display_name', $comment->user_id ), $subject );
		$message = str_replace( '{site_logo}', $logo, $message );
		$message = str_replace( '{question_link}', get_permalink( $question->ID ), $message );
		$message = str_replace( '{comment_link}', get_permalink( $question->ID ) . '#comment-' . $comment_id, $message );
		$message = str_replace( '{question_title}', $question->post_title, $message );
		$message = str_replace( '{comment_author_avatar}', get_avatar( $comment->user_id, '60' ), $message );
		$message = str_replace( '{comment_author_link}', dwqa_get_author_link( $comment->user_id ), $message );
		$message = str_replace( '{comment_author}', get_the_author_meta( 'display_name', $comment->user_id ), $message );
		$message = str_replace( '{comment_content}', $comment->comment_content, $message );
		$message = str_replace( '{site_name}', get_bloginfo( 'name' ), $message );
		$message = str_replace( '{site_description}', get_bloginfo( 'description' ), $message );
		$message = str_replace( '{site_url}', site_url(), $message );
		
		// 发送通知给父节点用户（如果提交评论的和父节点用户不是一个人）
		if ( $post_parent->post_author != $comment->user_id ) {
			wp_mail( $post_parent_email, $subject, $message );
			// 抄送副本给管理员
			if ( $enable_send_copy) {
				wp_mail( $admin_email, '【抄送管理员】'.$subject, $message );
			}
		}

		// 判断要不要发给关注者
		if ( $parent == 'dwqa-question' ) {
			$enable_notify = get_option( 'dwqa_subscrible_enable_new_comment_question_followers_notify', true );
		} else {
			$enable_notify = get_option( 'dwqa_subscrible_enable_new_comment_answer_followers_notification', true );
		}
		// 判断是否打开关注者通知
		if ( !$enable_notify ) {
			return false;
		}

		//如果没有关注者，或者拿到错误的关注者列表就返回
		$followers = get_post_meta( $post_parent->ID, '_dwqa_followers' );
		if ( empty( $followers ) || !is_array( $followers ) ) {
			return false;
		}
		$followers = array_unique($followers);
		// 构建关注者接收的邮件内容
		$comment_email = get_the_author_meta( 'user_email', $comment->user_id );

		if ( $parent == 'dwqa-question' ) {
			$message_to_follower = dwqa_get_mail_template( 'dwqa_subscrible_new_comment_question_followers_email', 'new-comment-question' );    
			$follow_subject = get_option( 'dwqa_subscrible_new_comment_question_followers_email_subject',__( '[{site_name}] You have a new comment for question {question_title}', 'be-question-answer' )  );
			$message_to_follower = str_replace( '{question_author}', get_the_author_meta( 'display_name', $post_parent->post_author ), $message_to_follower );
			$question = $post_parent;
		} else {
			$message_to_follower = dwqa_get_mail_template( 'dwqa_subscrible_new_comment_answer_followers_email', 'new-comment-answer' );
			$follow_subject = get_option( 'dwqa_subscrible_new_comment_answer_followers_email_subject',__( '[{site_name}] You have a new comment for answer', 'be-question-answer' )  );
			$message_to_follower = str_replace( '{answer_author}', get_the_author_meta( 'display_name', $post_parent->post_author ), $message_to_follower );
		}
		$follow_subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $follow_subject );
		$follow_subject = str_replace( '{question_title}', $question->post_title, $follow_subject );
		$follow_subject = str_replace( '{question_id}', $question->ID, $follow_subject );
		$follow_subject = str_replace( '{username}',get_the_author_meta( 'display_name', $comment->user_id ), $follow_subject );

		$follow_subject = str_replace( '{comment_author}', get_the_author_meta( 'display_name', $comment->user_id ), $follow_subject );
		$message_to_follower = str_replace( '{site_logo}', $logo, $message_to_follower );
		$message_to_follower = str_replace( '{question_link}', get_permalink( $question->ID ), $message_to_follower );
		$comment_link = get_permalink( $question->ID ) . '#comment-' . $comment_id;
		$message_to_follower = str_replace( '{comment_link}', $comment_link, $message_to_follower );
		$message_to_follower = str_replace( '{question_title}', $question->post_title, $message_to_follower );
		$message_to_follower = str_replace( '{comment_author_avatar}', get_avatar( $comment->user_id, '60' ), $message_to_follower );
		$message_to_follower = str_replace( '{comment_author_link}', dwqa_get_author_link( $comment->user_id ), $message_to_follower );
		$message_to_follower = str_replace( '{comment_author}', get_the_author_meta( 'display_name', $comment->user_id ), $message_to_follower );
		$message_to_follower = str_replace( '{comment_content}', $comment->comment_content, $message_to_follower );
		$message_to_follower = str_replace( '{site_name}', get_bloginfo( 'name' ), $message_to_follower );
		$message_to_follower = str_replace( '{site_description}', get_bloginfo( 'description' ), $message_to_follower );
		$message_to_follower = str_replace( '{site_url}', site_url(), $message_to_follower );

		foreach ( $followers as $follower ) {
			$follower = (int) $follower;
			$user_data = get_user_by( 'id', $follower );
			if ( $user_data ) {
				$follow_email = $user_data->user_email;
				$follower_name = $user_data->display_name;

				// 如果当前关注者邮箱不存在或关注者是创建者（上面已经给创建者发过邮件了）或关注者是评论者本人就不发邮件
				if ( !$follow_email || $follower == $post_parent->post_author || $follower == $comment->user_id ) {
					return false;
				}
				$message_to_each_follower = str_replace( '{follower}', $follower_name, $message_to_follower );
				$test = wp_mail( $follow_email, $follow_subject, $message_to_each_follower );
				if ( $enable_send_copy ) {
					wp_mail( $admin_email, '【抄送管理员】'.$follow_subject, $message_to_each_follower );
				}
			}
		}
	}
	
	public function get_admin_email( $type = 'question' ){
		switch ($type) {
			case 'answer':
				$admin_email = get_option( 'dwqa_subscrible_new_answer_forward', '' );
				break;
			case 'comment-question':
				$admin_email = get_option( 'dwqa_subscrible_new_comment_question_forward', '' );
				break;
			case 'comment-answer':
				$admin_email = get_option( 'dwqa_subscrible_new_comment_answer_forward', '' );
				break;
			case 'question':
			default:
				$admin_email = get_option( 'dwqa_subscrible_sendto_address', '' );
				break;
		}
		$emails = preg_split('/\r\n|\r|\n/', $admin_email );
		$emails = array_merge( $emails, array( get_bloginfo( 'admin_email' ) ) );
		$emails = array_unique($emails);
		return $emails;
	}

	public function get_from_address() {
		$from_email = get_option( 'dwqa_subscrible_from_address', get_bloginfo( 'admin_email' ) );

		if ( empty( $from_email ) ) {
			$from_email = get_bloginfo( 'admin_email' );
		}

		return sanitize_email( $from_email );
	}

	public function get_from_name() {
		$name = get_option( 'dwqa_subscrible_from_name', get_bloginfo( 'name' ) );

		if ( empty( $name ) ) {
			$name = get_bloginfo( 'name' );
		}

		return $name;
	}

	public function get_content_type() {
		return apply_filters( 'dwqa_notifications_get_content_type', 'text/html' );
	}
}


?>