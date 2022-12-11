<?php $quiz_results = $quiz_results->row_array(); ?>
<?php $user_all_answers = json_decode(strtolower($quiz_results['user_answers']), true); ?>
<?php $correct_answer_question_id = json_decode(strtolower($quiz_results['correct_answers']), true); ?>
<div class="col-12">
    <h4 class="w-100 text-center"><?php echo get_phrase('quize_results'); ?></h4>
    <p class="w-100 text-center mb-1"><?php echo get_phrase('total_marks'); ?> : <?php echo json_decode($lesson_details['attachment'], true)['total_marks']; ?></p>
    <p class="w-100 text-center my-0"><?php echo get_phrase('obtained_marks'); ?> : <?php echo $quiz_results['total_obtained_marks']; ?></p>
</div>


<div class="col-12">
    <?php foreach($quiz_questions->result_array() as $question_number => $quiz_question):
        $question_number++;
        if(array_key_exists($quiz_question['id'], $user_all_answers)){
            $user_answers = $user_all_answers[$quiz_question['id']];
        }else{
            $user_answers = array();
        }
        if($quiz_question['type'] == 'multiple_choice' || $quiz_question['type'] == 'single_choice'): ?>
            <?php $input_type = ($quiz_question['type'] == 'multiple_choice')? 'checkbox' : 'radio'; ?>
        <hr class="bg-secondary">
        <div class="row justify-content-center">
            <div class="col-md-1 pt-1 text-start"><b><?php echo $question_number; ?>.</b></div>
            <div class="col-md-9">
                <?php echo remove_js(htmlspecialchars_decode($quiz_question['title'])); ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-1"></div>
            <div class="col-md-9">
                <?php foreach(json_decode($quiz_question['options'], true) as $key => $option): ?>
                    <?php $key++; ?>
                    <div class="form-group">
                        <input id="option_<?php echo $question_number.'_'.$key; ?>" type="<?php echo $input_type; ?>" value="<?php echo $key; ?>" name="answer[]" <?php if(in_array($key, $user_answers)) echo 'checked'; ?> disabled>
                        <label class="<?php echo $input_type; ?> text-dark" for="option_<?php echo $question_number.'_'.$key; ?>"><?php echo $option; ?></label><br>
                    </div>
                <?php endforeach; ?>

                <?php if(!in_array($quiz_question['id'], $correct_answer_question_id)): ?>
                    <div class="w-100 text-danger fw-bold">
                        <i class="fas fa-times"></i> <?php echo site_phrase('wrong'); ?>!!
                    </div>
                    <div class="w-100 text-success fw-bold">
                        <?php echo site_phrase('correct_answer'); ?> : 
                        <?php
                            foreach(json_decode($quiz_question['correct_answers'], true) as $correct_answer):
                                $correct_answer = $correct_answer - 1;
                                echo json_decode($quiz_question['options'], true)[$correct_answer];
                            endforeach;
                        ?>
                    </div>
                <?php else: ?>
                    <div class="w-100 text-success fw-bold">
                        <i class="fas fa-check"></i> <?php echo site_phrase('correct'); ?>.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif($quiz_question['type'] == 'fill_in_the_blank'): ?>
        <hr class="bg-secondary">
        <div class="row justify-content-center">
            <div class="col-1 pt-1"><b><?php echo $question_number; ?>.</b></div>
            <div class="col-md-9">
                <?php
                $correct_answers = json_decode($quiz_question['correct_answers'], true);
                $question_title = remove_js(htmlspecialchars_decode($quiz_question['title']));
                foreach($correct_answers as $correct_answer):
                    $question_title = str_replace($correct_answer, ' _____ ', $question_title);
                endforeach;
                echo $question_title;
                ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-1"></div>
            <div class="col-md-9">
                <div class="input-group mb-3">
                    <?php $counter = 0; ?>
                    <?php foreach($correct_answers as $key => $word): ?>
                        <?php $word = strtolower($word); ?>
                        <span class="input-group-text"><?php echo ++$key; ?></span>
                        <input type="text" value="<?php if(in_array($word, $user_answers)) echo $user_answers[$counter]; ?>" class="form-control" name="answer[]" disabled>
                        <?php $counter++; ?>
                    <?php endforeach; ?>

                    <?php if(!in_array($quiz_question['id'], $correct_answer_question_id)): ?>
                        <div class="w-100 text-danger fw-bold">
                            <i class="fas fa-times"></i> <?php echo site_phrase('wrong'); ?>!!
                        </div>
                        <div class="w-100 text-success fw-bold">
                            <?php echo site_phrase('correct_answer'); ?> : 
                            <?php
                                foreach(json_decode($quiz_question['correct_answers'], true) as $correct_answer):
                                    echo '<u class="ms-3">'.$correct_answer.'</u>';
                                endforeach;
                            ?>
                        </div>
                    <?php else: ?>
                        <div class="w-100 text-success fw-bold">
                            <i class="fas fa-check"></i> <?php echo site_phrase('correct'); ?>.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php endforeach; ?>
</div>