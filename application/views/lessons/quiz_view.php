<?php
    $user_id = (isset($user_id) && $user_id > 0) ? $user_id : $this->session->userdata('user_id');
    $quiz_id = $lesson_details['id'];
    $is_course_instructor = $this->crud_model->is_course_instructor($course_details['id'], $user_id);
    if($this->session->userdata('admin_login')){
        $is_course_instructor = 1;
    }

    $quiz_submission_checker = $this->user_model->quiz_submission_checker($lesson_details['id']);
    $quiz_questions = $this->crud_model->get_quiz_questions($lesson_details['id']);


    if($is_course_instructor == true){
        $quiz_results = $this->db->get_where('quiz_results', array('quiz_id' => $lesson_details['id']));
    }else{
        $quiz_results = $this->db->get_where('quiz_results', array('quiz_id' => $lesson_details['id'], 'user_id' => $user_id));
    }

    if($quiz_results->num_rows() > 0 && $is_course_instructor == 0){
        $available_time = (time_to_seconds($lesson_details['duration']) + $quiz_results->row('date_added')) - time();
    }else{
        $available_time = time_to_seconds($lesson_details['duration']);
    }

    $timer = time_to_seconds($lesson_details['duration']);
?>


<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/lessons/flipclock-timer/flipclock.css'); ?>">
<script type="text/javascript" src="<?php echo site_url('assets/lessons/flipclock-timer/flipclock.min.js'); ?>"></script>


<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-8">
                <h5 class="d-md-flex w-100"><?php echo $lesson_details['title']; ?></h5>
            </div>
            <div class="col-md-4 fw-bold text-md-end">
                <span class="text-muted"><?php echo get_phrase('total_questions').': '.$quiz_questions->num_rows(); ?></span>
                <span class="text-muted">|</span>
                <span class="text-muted"><?php echo get_phrase('total_marks').': '.json_decode($lesson_details['attachment'], true)['total_marks']; ?></span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row justify-content-center">
            
            <?php if($quiz_submission_checker == 'submitted'): ?>
                    <?php include 'quiz_result.php'; ?>
            <?php else: ?>
                <!-- if timer enabled -->
                <?php if($timer > 0): ?>
                    <div class="col-12">
                        <h5 class="w-100 mb-4 text-center"><?php echo get_phrase('quize_time'); ?></h5>
                    </div>
                    <div class="col-auto" style="position: sticky; top: -12px; z-index: 99;">
                        <div class="clock"></div>
                    </div>
                <?php endif; ?>

                <div class="col-12"></div>
                <?php if($is_course_instructor == 1): ?>
                    <div class="col-md-6 mt-4">
                        <p class="text-center fw-bold text-danger"><?php echo get_phrase('total_participant_students'); ?> : <?php echo $quiz_results->num_rows(); ?></p>
                        <div class="form-group">
                            <span class="text-muted"><?php echo site_phrase('participant_students'); ?></span>
                            <select onchange="viewAnswerSheet(this.value, )" class="form-control" name="participant_students">
                                <option value=""><?php echo site_phrase('select_student'); ?></option>
                                <?php
                                foreach($quiz_results->result_array() as $participant_student):
                                    $student_details = $this->user_model->get_all_user($participant_student['user_id'])->row_array();
                                ?>
                                    <option value="<?php echo $participant_student['quiz_result_id']; ?>"><?php echo $student_details['first_name'].' '.$student_details['last_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted"><?php echo site_phrase('select_a_student_to_view_the_answer_sheet'); ?></small>
                        </div>
                    </div>
                    <div class="col-12 pt-4" id="viewAnswerSheet"></div>
                <?php else: ?>
                    <!-- if timer enabled -->
                    <?php if($timer > 0): ?>
                        <div class="col-12 text-center pt-3">
                            <?php if($quiz_submission_checker == 'on_progress'): ?>
                                <script type="text/javascript">setTimeout(function(){startQuiz();}, 1500);</script>
                            <?php else: ?>
                                <button class="btn red" id="quiz-start-brn" onclick="startQuiz(this)"><?php echo get_phrase('start_quiz'); ?></button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="col-12" id="quiz_answer_sheet">
                            <?php include "quiz_answer_sheet.php"; ?>
                        </div>

                        <script type="text/javascript">setTimeout(function(){startQuiz();}, 1500);</script>
                    <?php endif; ?>

                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var clock = $('.clock').FlipClock({
        clockFace: 'HourlyCounter',
        autoStart: false,
        callbacks: {
          stop: function() {
            $('#quizSubmissionBtn').click();
          }
        }
    });
    // set time
    clock.setTime("<?php echo $available_time; ?>");
    // countdown mode
    clock.setCountdown(true);

    function startQuiz(e){
        $.ajax({
            url: "<?php echo site_url('user/start_quiz/'.$lesson_details['id']); ?>",
            type: 'post',
            success: function(response){
                $('#quiz_answer_sheet').html(response);
            }
        });
        // start the clock
        clock.start();
        $(e).hide();
    }

    function viewAnswerSheet(quiz_result_id){
        $.ajax({
            url: "<?php echo site_url('home/view_answer_sheet/'); ?>/"+quiz_result_id,
            type: 'post',
            success: function(response){
                $('#viewAnswerSheet').html(response);
            }
        });
    }
</script>