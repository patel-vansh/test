<?php
    //$param2 = question id and $param3 = quiz id
    $question_details = $this->crud_model->get_quiz_question_by_id($param2)->row_array();
    $question_type = $question_details['type'];
?>
<form class="pb-5" action="<?php echo site_url('user/quiz_questions/'.$question_details['quiz_id'].'/edit/'.$param2); ?>" method="post" id = 'mcq_form'>

    <div class="form-group">
        <label for="question_title"><?php echo get_phrase('write_your_question'); ?></label>
        <textarea name="title" id="question_title" class="form-control"><?php echo $question_details['title']; ?></textarea>
    </div>

    <div class="form-group">
        <label for="title"><?php echo get_phrase('question_type'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="question_type" id="question_type" onchange="quiz_fields_type_wize(this)" required>
            <option value=""><?php echo get_phrase('select_question_type'); ?></option>
            <option value="multiple_choice" <?php if($question_details['type'] == 'multiple_choice')echo 'selected'; ?>><?php echo get_phrase('multiple_choice'); ?></option>
            <option value="single_choice" <?php if($question_details['type'] == 'single_choice')echo 'selected'; ?>><?php echo get_phrase('single_choice').' '.get_phrase('and').' true/false'; ?></option>
            <!-- <option value="plain_text" <?php if($question_details['type'] == 'plain_text')echo 'selected'; ?>><?php echo get_phrase('plain_text'); ?></option> -->
            <option value="fill_in_the_blank" <?php if($question_details['type'] == 'fill_in_the_blank')echo 'selected'; ?>><?php echo get_phrase('fill_in_the_blank'); ?></option>
        </select>
    </div>


    <div id="quiz_fields_type_wize">
        <?php include "quiz_fields_type_wize.php"; ?>
    </div>


    <div class="text-center pt-3">
        <button class = "btn btn-success" id = "submitButton" type="button" name="button"><?php echo get_phrase('submit_quiz_question'); ?></button>
    </div>
</form>
<script type="text/javascript">
    function quiz_fields_type_wize(e){
        var question_type = $('#question_type').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('user/quiz_fields_type_wize'); ?>",
            data: {question_type : question_type},
            success: function(response){
                jQuery('#quiz_fields_type_wize').html(response);
            }
        });
    }


$('#submitButton').click( function(event) {
    $.ajax({
        url: '<?php echo site_url('user/quiz_questions/'.$question_details['quiz_id'].'/edit/'.$param2); ?>',
        type: 'post',
        data: $('form#mcq_form').serialize(),
        success: function(response) {
            console.log(response);
           if (response == 1) {
               success_notify('<?php echo get_phrase('question_has_been_added'); ?>');
               showLargeModal('<?php echo site_url('modal/popup/quiz_questions/'.$question_details['quiz_id']); ?>', '<?php echo get_phrase('manage_quiz_questions'); ?>');
           }else {
               error_notify(response);
           }
         }
    });
    
});

$(function(){
    initSummerNote(['#question_title']);
    $('.select2').select2();
});
</script>
