<?php
// $param2 IS FOR COURSE ID AND $param3 IS FOR LESSON TYPE
$course_details = $this->crud_model->get_course_by_id($param2)->row_array();
$sections = $this->crud_model->get_section('course', $param2)->result_array();
?>
<!-- SHOWING THE LESSON TYPE IN AN ALERT VIEW -->
<div class="alert alert-info" role="alert">
    <?php echo get_phrase("lesson_type"); ?> :
    <strong>
        <?php
        if ($param3 == 'html5') {
            echo get_phrase("video_url").' [.mp4]';
        }elseif ($param3 == 'video') {
            echo get_phrase("video_file");
        }elseif ($param3 == 'youtube' || $param3 == 'academy_cloud' || $param3 == 'vimeo') {
            echo get_phrase($param3).' '.get_phrase("video");
        }else{
            echo get_phrase($param3);
        }
        ?>.
    </strong>

    <strong><a href="#" class="ml-1" data-toggle="modal" data-dismiss="modal" onclick="showAjaxModal('<?php echo site_url('modal/popup/lesson_types/'.$param2.'/'.$param3); ?>', '<?php echo get_phrase('add_new_lesson'); ?>')"><?php echo get_phrase("change"); ?></a></strong>
</div>

<!-- ACTUAL LESSON ADDING FORM -->
<form class="ajaxFormSubmission" action="<?php echo site_url('user/lessons/'.$param2.'/add'); ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="course_id" value="<?php echo $param2; ?>">
    <div class="form-group">
        <label><?php echo get_phrase('title'); ?></label>
        <input type="text" name = "title" class="form-control" required>
    </div>

    <div class="form-group">
        <label><?php echo get_phrase('section'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="section_id" required>
            <?php foreach ($sections as $section): ?>
                <option value="<?php echo $section['id']; ?>"><?php echo $section['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($param3 == 'youtube'): include('youtube_type_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'academy_cloud'): include('academy_cloud_type_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'vimeo'): include('vimeo_type_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'html5'): include('html5_type_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'video'): include('video_type_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'amazon-s3' && addon_status('amazon-s3')): include('amazon_s3_type_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'google_drive_video'): include('google_drive_video_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'document'): include('document_type_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'text'): include('text_type_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'image'): include('image_type_lesson_add.php'); endif; ?>
    <?php if ($param3 == 'iframe'): include('iframe_type_lesson_add.php'); endif; ?>

    <div class="form-group">
        <label><?php echo get_phrase('summary'); ?></label>
        <textarea name="summary" id="lesson_summary" class="form-control"></textarea>
    </div>

    <div class="form-group">
        <label><?php echo get_phrase('do_you_want_to_keep_it_free_as_a_preview_lesson'); ?>?</label>
        <br>
        <input type="checkbox" name="free_lesson" id="free_lesson" value="1">
        <label for="free_lesson"><?php echo get_phrase('mark_as_free_lesson'); ?></label>
    </div>

    <div class="text-center">
        <button class = "btn btn-success w-100 formSubmissionBtn" type="submit" name="button"><?php echo get_phrase('add_lesson'); ?></button>
    </div>
</form>

<script type="text/javascript">

$(document).ready(function() {
    initSummerNote(['#lesson_summary']);
    initSelect2(['#section_id','#lesson_type', '#lesson_provider', '#lesson_provider_for_mobile_application']);
    initTimepicker();

    // HIDING THE SEARCHBOX FROM SELECT2
    $('select').select2({
        minimumResultsForSearch: -1
    });
});
function ajax_get_video_details(video_url) {
    $('#perloader').show();
    if(checkURLValidity(video_url)){
        $.ajax({
            url: '<?php echo site_url('user/ajax_get_video_details');?>',
            type : 'POST',
            data : {video_url : video_url},
            success: function(response)
            {
                jQuery('#duration').val(response);
                $('#perloader').hide();
                $('#invalid_url').hide();
            }
        });
    }else {
        $('#invalid_url').show();
        $('#perloader').hide();
        jQuery('#duration').val('');

    }
}

function checkURLValidity(video_url) {
    var youtubePregMatch = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    var vimeoPregMatch = /^(http\:\/\/|https\:\/\/)?(www\.)?(vimeo\.com\/)([0-9]+)$/;
    if (video_url.match(youtubePregMatch)) {
        return true;
    }
    else if (vimeoPregMatch.test(video_url)) {
        return true;
    }
    else {
        return false;
    }
}


$(function() {
    var formSubmissionBtn = $('.formSubmissionBtn');
    var formSubmissionBtnTxt = $(formSubmissionBtn).html();
    //The form of submission to RailTeam js is defined here.(Form class or ID)
    $('.ajaxFormSubmission').ajaxForm({
        beforeSend: function() {
            var percentVal = '0%';
            $(formSubmissionBtn).html('<?php echo get_phrase('uploading'); ?>... '+percentVal);
            $(formSubmissionBtn).attr('disabled', true);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            if(percentComplete < 100){
                $(formSubmissionBtn).html('<?php echo get_phrase('uploading'); ?>... '+percentVal);
            }else{
                $(formSubmissionBtn).html('<?php echo get_phrase('please_wait'); ?>... '+percentVal);
            }
        },
        complete: function(xhr) {
            var response = xhr.responseText;
            console.log(response);

            setTimeout(function(){

                if (response) {
                    response = JSON.parse(response);

                    if(typeof response.error != "undefined"){
                        error_notify(response.error);
                        $(formSubmissionBtn).attr('disabled', false);
                        $(formSubmissionBtn).html(formSubmissionBtnTxt);
                    }

                    if(typeof response.success != "undefined"){
                        success_notify(response.success);
                    }

                    if(typeof response.redirect != "undefined"){
                        window.location.href = response.redirect;
                    }

                    if(typeof response.reload != "undefined"){
                        location.reload();
                    }
                }

                //set_js_flashdata('<?php echo site_url('home/set_flashdata_for_js/flash_message/your_video_file_uploaded_succesfully') ?>');
            }, 500);
        },
        error: function()
        {
            //You can write here your js error message
        }
    });
});
</script>
