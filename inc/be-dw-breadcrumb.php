<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
if ( class_exists( 'DW_Question_Answer' ) ) {
	function be_dw_breadcrumb() {
		global $dwqa_general_settings; $title; $search; $author; $output;
		$title = get_the_title( $dwqa_general_settings['pages']['archive-question'] );
		$search = isset( $_GET['qs'] ) ? esc_html( $_GET['qs'] ) : false;
		$author = isset( $_GET['user'] ) ? esc_html( $_GET['user'] ) : false;
		$output = '';
		if ( !is_singular( 'dwqa-question' ) ) {
			$term = get_query_var( 'dwqa-question_category' ) ? get_query_var( 'dwqa-question_category' ) : ( get_query_var( 'dwqa-question_tag' ) ? get_query_var( 'dwqa-question_tag' ) : false );
			$term = get_term_by( 'slug', $term, get_query_var( 'taxonomy' ) );
		} else {

			$term = wp_get_post_terms( get_the_ID(), 'dwqa-question_category' );
			if ( $term ) {
				$term = $term[0];
			}
		}

		if ( is_singular( 'dwqa-question' ) || $search || $author || $term ) {
			$output .= '<div class="breadcrumb">';
		}

		if ( $term || is_singular( 'dwqa-question' ) || $search || $author ) {
			if ( !$author ) {
				$output .= '<a href="'. get_permalink( $dwqa_general_settings['pages']['archive-question'] ) .'"></a>';
				$output .= '<span class="seat"></span><a href="' . home_url('/') . '" rel="bookmark">'. __( '首页', 'begin' ) .'</a><i class="be be-arrowright"></i>';
				$output .= '<a href="'. get_permalink( $dwqa_general_settings['pages']['archive-question'] ) .'">' . $title . '</a>';
			}
		}

		global $tax_name;
		if ( $term ) {
			$output .= '<i class="be be-arrowright"></i>';
			if ( is_singular( 'dwqa-question' ) ) {
				$output .= '<a href="'. esc_url( get_term_link( $term, get_query_var( 'taxonomy' ) ) ) .'">' . $tax_name . '' . $term->name . '</a>';
			} else {
				$output .= '<span class="dwqa-current">' . $tax_name . '' . $term->name . '</span>';
			}
		}

		if ( $search ) {
			$output .= '<i class="be be-arrowright"></i>';
			$output .= sprintf( '<span class="dwqa-current">%s%s</span>', __( '搜索', 'begin' ),'<i class="be be-arrowright"></i>'. rawurldecode( $search ));
		}

		if ( $author ) {
			$output .= '<span class="seat"></span><a href="' . home_url('/') . '" rel="bookmark">'. __( '首页', 'begin' ) .'</a><i class="be be-arrowright"></i>';
			$output .= '<a href="'. get_permalink( $dwqa_general_settings['pages']['archive-question'] ) .'">' . $title . '</a><i class="be be-arrowright"></i>';
			$output .= sprintf( '<span class="dwqa-current">%s%s</span>', __( '作者', 'begin' ),'<i class="be be-arrowright"></i>'. rawurldecode( $author ) );
		}

		if ( is_singular( 'dwqa-question' ) ) {
			$output .= '<i class="be be-arrowright"></i>';
			if ( !dwqa_is_edit() ) {

				if ( wp_is_mobile() ) {
					$output .= '<span class="dwqa-current">正文</span>';
				} else {
					$output .= '<span class="dwqa-current">' . get_the_title() . '</span>';
				}
			} else {
				$output .= '<a href="'. get_permalink() .'">'. get_the_title() .'</a>';
				$output .= '<i class="be be-arrowright"></i>';
				$output .= '<span class="dwqa-current">'. __( '编辑', 'begin' ) .'</span>';
			}
		}
		if ( is_singular( 'dwqa-question' ) || $search || $author || $term ) {
			$output .= '</div>';
		}
		echo $output;
	}

	function be_dw_cat_breadcrumb() {
		global $dwqa_general_settings; $title; $search; $author; $output;
		$title = get_the_title( $dwqa_general_settings['pages']['archive-question'] );
		$output = '';
		if ( !is_singular( 'dwqa-question' ) ) {
			$term = get_query_var( 'dwqa-question_category' ) ? get_query_var( 'dwqa-question_category' ) : ( get_query_var( 'dwqa-question_tag' ) ? get_query_var( 'dwqa-question_tag' ) : false );
			$term = get_term_by( 'slug', $term, get_query_var( 'taxonomy' ) );
		} else {
			$term = wp_get_post_terms( get_the_ID(), 'dwqa-question_category' );
			if ( $term ) {
				$term = $term[0];
			}
		}

		global $tax_name;
		if ( $term ) {
			if ( !is_singular( 'dwqa-question' ) ) {
			$output .= '<a href="'. get_permalink( $dwqa_general_settings['pages']['archive-question'] ) .'">' . $title . '</a><i class="be be-arrowright"></i>';
			$output .= '' . $tax_name . '' . $term->name . '';
			}
		}
		return $output;
	}
}