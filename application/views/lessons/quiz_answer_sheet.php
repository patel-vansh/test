<?php foreach($quiz_questions->result_array() as $question_number => $quiz_question): ?>
<?php $question_number++; ?>
<?php if($quiz_question['type'] == 'multiple_choice' || $quiz_question['type'] == 'single_choice'): ?>
	<form class="ajaxFormSubmission" id="submitForm<?php echo $question_number; ?>" action="<?php echo site_url('user/submit_quiz_answer/'.$quiz_question['quiz_id'].'/'.$quiz_question['id'].'/'.$quiz_question['type']); ?>" method="post" enctype="multipart/form-data">
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
						<input onchange="submit_quiz_answer('submitForm<?php echo $question_number; ?>');" id="option_<?php echo $question_number.'_'.$key; ?>" type="<?php echo $input_type; ?>" value="<?php echo $key; ?>" name="answer[]">
						<label class="<?php echo $input_type; ?> text-dark" for="option_<?php echo $question_number.'_'.$key; ?>"><?php echo $option; ?></label><br>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</form>
<?php elseif($quiz_question['type'] == 'fill_in_the_blank'): ?>
	<form class="ajaxFormSubmission" id="submitForm<?php echo $question_number; ?>" action="<?php echo site_url('user/submit_quiz_answer/'.$quiz_question['quiz_id'].'/'.$quiz_question['id'].'/'.$quiz_question['type']); ?>" method="post" enctype="multipart/form-data">
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
					<?php foreach($correct_answers as $key => $word): ?>
						<span class="input-group-text"><?php echo ++$key; ?></span>
						<input type="text" onblur="submit_quiz_answer('submitForm<?php echo $question_number; ?>');" class="form-control" name="answer[]">
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</form>
<?php endif; ?>
<?php endforeach; ?>

<form class="ajaxFormSubmission text-center" action="<?php echo site_url('user/finish_quize_submission/'.$quiz_id); ?>" method="post" enctype="multipart/form-data">
	<button id="quizSubmissionBtn" type="submit" class="btn red mt-4 px-5"><?php echo site_phrase('submit'); ?></button>
</form>


<script type="text/javascript">
	function submit_quiz_answer(formId){
		$("#"+formId).submit();
	}

    $(function() {
	    $('.ajaxFormSubmission').ajaxForm({
	        beforeSend: function() {
	            var percentVal = '0%';
	        },
	        uploadProgress: function(event, position, total, percentComplete) {
	            var percentVal = percentComplete + '%';
	        },
	        complete: function(xhr) {
	        	var jsonResponse = JSON.parse(xhr.responseText);
	        	if(jsonResponse.status == 'submit'){
	        		location.reload();
	        	}
	        },
	        error: function()
	        {
	            //You can write here your js error message
	        }
	    });
	});
</script>