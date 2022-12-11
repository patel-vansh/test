<input type="hidden" name="lesson_type" value="video-url">
<input type="hidden" name="lesson_provider" value="academy_cloud">

<div class="form-group">
    <label for="video_file"> <?php echo get_phrase('video_file'); ?> <small>(<?php echo get_phrase('academy_cloud'); ?>)</small></label>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="cloud_video_file" name="cloud_video_file" onchange="changeTitleOfImageUploader(this)" accept="video/*" required>
            <label class="custom-file-label" for="cloud_video_file"><?php echo get_phrase('select_video_file'); ?></label>
        </div>
    </div>
</div>

<div class="form-group">
    <label><?php echo get_phrase('duration'); ?></label>
    <input type="text" class="form-control" data-toggle='timepicker' data-minute-step="5" name="academy_cloud_video_duration" id = "academy_cloud_video_duration" data-show-meridian="false" value="00:00:00">
</div>

<div class="form-group">
    <label><?php echo get_phrase('thumbnail'); ?> <small>(<?php echo get_phrase('the_image_size_should_be'); ?>: 979 x 551)</small> </label>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail" onchange="changeTitleOfImageUploader(this)" accept="image/*">
            <label class="custom-file-label" for="thumbnail"><?php echo get_phrase('thumbnail'); ?></label>
        </div>
    </div>
</div>

<div class="form-group">
    <label><?php echo get_phrase('caption'); ?>( <?php echo get_phrase('.vtt'); ?> )</label>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="caption" name="caption" onchange="changeTitleOfImageUploader(this)" accept=".vtt">
            <label class="custom-file-label" for="caption"><?php echo get_phrase('choose_your_caption_file'); ?></label>
        </div>
    </div>
</div>