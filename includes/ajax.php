<?php
/**
 * Configure Wplms_Instructor_Quiz_Filters
 *
 * @class       Wplms_Instructor_Quiz_Filters
 * @author      VibeThemes
 * @category    Admin
 * @package     Wplms_Instructor_Quiz_Filters
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 



class Wplms_Unattempt_Question_Ajax{


	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_Unattempt_Question_Ajax();
        return self::$instance;
    }

	private function __construct(){
		// Ajax
		add_action('wp_ajax_enable_unattempted_give_marks',array($this,'enable_unattempted_give_marks'));  //form view
		add_action('wp_ajax_save_unattempted_question_marks',array($this,'save_unattempted_question_marks'));  //save marks
	}

	function enable_unattempted_give_marks(){
		//create fake comment and share give marks form
		if(isset($_POST['security']) && wp_verify_nonce($_POST['security'],'show_form_unattempted_question_marks') && is_user_logged_in()){
			$quiz_id = $_POST['quiz_id'];
			$question_id = $_POST['question_id'];
			$user_id = $_POST['user_id'];
			if(!empty($question_id) && !empty($quiz_id) && !empty($user_id)){
				// copy like bp_course_save_question_quiz_answer //fake comment
				$question_answer_args = apply_filters('bp_course_save_question_quiz_answer',array(
					'comment_post_ID'=>$question_id,
					'user_id'=>$user_id,
					'comment_content'=>apply_filters('enable_unattempted_give_marks_ans',null),
					'comment_date' => current_time('mysql'),
					'comment_approved' => 1,
				));
				global $wpdb;
				$comment_id = $wpdb->get_var("SELECT m.comment_id FROM {$wpdb->comments} as c LEFT JOIN {$wpdb->commentmeta} as m ON c.comment_ID = m.comment_id WHERE c.user_id = $user_id AND c.comment_post_ID = $question_id AND m.meta_key = 'quiz_id' AND m.meta_value = $quiz_id");
				if(!empty($comment_id) && is_numeric($comment_id)){
					$question_answer_args['comment_ID'] = $comment_id;
					wp_update_comment($question_answer_args);
				}else{
					$comment_id = wp_insert_comment($question_answer_args);
					if(!is_wp_error($comment_id)){
						update_comment_meta($comment_id,'quiz_id',$quiz_id);
					}
				}
				// form show
				if(!empty($comment_id)){
					echo '<span class="unattempted_marking">'.__('Marks Obtained','vibe').'<input type="number" value=0 class="form_field unattempted_marks" value=0 placeholder="'.__('Give marks','vibe').'">
					<button class="give_marks_unattempted button" data-comment-id='.$comment_id.'  data-security='.wp_create_nonce('save_unattempted_question_marks').'>'.__('Give marks','vibe').'</button><span></span>
					</span>';
				}
			}	
		}else{
			echo __('Security Failed','vibe');
		}
		die();
	}
	
	function save_unattempted_question_marks(){
		if(isset($_POST['security']) && wp_verify_nonce($_POST['security'],'save_unattempted_question_marks') && is_user_logged_in()){
			$comment_id = $_POST['comment_id'];
			$marks = $_POST['marks'];
			if(is_numeric($comment_id) && is_numeric($marks)){
				update_comment_meta( $comment_id, 'marks',$marks);
				echo __('Updated','vibe');
			}
		}else{
			echo __('Security Failed','vibe');
		}
		die();
    }

	
}

Wplms_Unattempt_Question_Ajax::init();