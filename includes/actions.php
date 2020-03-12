<?php
/**
 * Configure Wplms_Unattempt_Question_Actions
 *
 * @class       Wplms_Unattempt_Question_Actions
 * @author      VibeThemes
 * @category    Admin
 * @package     Wplms_Unattempt_Question_Actions
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 



class Wplms_Unattempt_Question_Actions{


	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_Unattempt_Question_Actions();
        return self::$instance;
    }

	private function __construct(){
    // for unattempted comment //
   add_action('wplms_quiz_evaluate_question_html',array($this,'show_script_unattempted'),10,2);  //script
   add_action('wplms_quiz_evaluate_per_question_html',array($this,'_wplms_quiz_evaluate_per_question_html_unattempted'),10,4);  // button
		
	}

  function _wplms_quiz_evaluate_per_question_html_unattempted($question_id,$quiz_id,$user_id,$marked_answer_id){
    if(empty($marked_answer_id) && !empty($question_id)){
      echo '<button href="#" class="enable_unattempted_give_marks button" data-question-id='.$question_id.' 
      data-quiz-id='.$quiz_id.'  data-security='.wp_create_nonce('show_form_unattempted_question_marks').' >'.__('Show Form For Unattempt Marking','vibe').'</button>';
    }  
  }
  function show_script_unattempted($quiz_id,$user_id){
    ?>
    <script>
    jQuery(document).ready(function(){
      let user_id = <?php echo $user_id; ?>
      // Ajax call
       jQuery(".quiz_questions").on('click', '.give_marks_unattempted', function() {
        let $this = jQuery(this);
        console.log('submitting unattempted marks');
        let comment_id = $this.data('comment-id');
        let security = $this.data('security');
        var marks = $this.closest('.unattempted_marking').find('.unattempted_marks').val();
        $this.prepend('<i class="fa fa-spinner animated spin"></i>');
        jQuery.ajax({
          type: "POST",
          url: ajaxurl,
          dataType: 'html',
          data: { action: 'save_unattempted_question_marks',
                  comment_id: comment_id,
                  marks: marks,
                  security : security
                },
          cache: false,
          success: function (data) {
              $this.find('i').remove();
              $this.html(data);
          }
        });
      })  
      
      // Hide and Show form
      jQuery('.enable_unattempted_give_marks').click(function(event){
        event.preventDefault(); 
        let $this = jQuery(this);
        let question_id = $this.data('question-id');
        let quiz_id = $this.data('quiz-id');
        let security = $this.data('security');
        console.log('quiz_id',quiz_id)
        console.log('question_id',question_id)
        if(quiz_id && question_id ){
            $this.prepend('<i class="fa fa-spinner animated spin"></i>');
            jQuery.ajax({
              type: "POST",
              url: ajaxurl,
              dataType: 'html',
              data: { 
                action: 'enable_unattempted_give_marks',
                quiz_id: quiz_id,
                question_id : question_id,
                user_id  : user_id,
                security : security
              },
              cache: false,
              success: function (data) {
                  $this.find('i').remove();
                  $this.after(data);
              }
            });
          }
        })
      });
    </script>
    <?php
  }
 

}

Wplms_Unattempt_Question_Actions::init();