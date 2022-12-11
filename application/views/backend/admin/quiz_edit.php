<?php
$quiz_details = $this->crud_model->get_lessons('lesson', $param2)->row_array();
$sections = $this->crud_model->get_section('course', $param3)->result_array();
?>
<form action="<?php echo site_url('admin/quizes/'.$param3.'/edit/'.$param2); ?>" method="post">
    <div class="form-group">
        <label for="title"><?php echo get_phrase('quiz_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title" value="<?php echo $quiz_details['title']; ?>" required>
    </div>
    <div class="form-group">
        <label for="section_id"><?php echo get_phrase('section'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="section_id" id="section_id" required>
            <?php foreach ($sections as $section): ?>
                <option value="<?php echo $section['id']; ?>" <?php if ($quiz_details['section_id'] == $section['id']): ?>selected<?php endif; ?>><?php echo $section['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="quiz_duration"><?php echo get_phrase('quiz_duration'); ?></label>
        <input type="text" class="form-control" data-toggle='timepicker' data-minute-step="5" name="quiz_duration" id = "quiz_duration" data-show-meridian="false" value="<?php echo $quiz_details['duration']; ?>">
        <small class="badge badge-info"><?php echo get_phrase('if_you_want_to_disable_the_timer,_set_the_duration_to'); ?> 00:00:00.</small>
    </div>

    <div class="form-group">
        <label for="total_marks"><?php echo get_phrase('total_marks'); ?></label>
        <input type="number" min="0" class="form-control" name="total_marks" value="<?php echo json_decode($quiz_details['attachment'], true)['total_marks']; ?>" id = "total_marks">
    </div>
    
    <div class="form-group">
        <label><?php echo get_phrase('instruction'); ?></label>
        <textarea name="summary" class="form-control"><?php echo $quiz_details['summary']; ?></textarea>
    </div>
    <div class="text-center">
        <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function() {
    initSelect2(['#section_id']);
    initTimepicker();
});
</script>
