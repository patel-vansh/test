<form action="<?php echo site_url('user/question_paper_sections/' . $param2 . '/add'); ?>" method="post">
    <div class="form-group">
        <label for="section_title">
            <?php echo get_phrase('title'); ?>
        </label>
        <input class="form-control" type="text" name="section_title" id="section_title" required>
        <small class="text-muted">
            <?php echo get_phrase('provide_a_section_name'); ?>
        </small>
        <br>
        <br>
        <label for="marks">
            <?php echo get_phrase('marks'); ?>
        </label>
        <input class="form-control" type="number" name="marks" id="marks" max="100" min="1">
    </div>
    <div class="text-right">
        <button class="btn btn-success" type="submit" name="button">
            <?php echo get_phrase('submit'); ?>
        </button>
    </div>
</form>
<script>
    document.querySelector("#marks").addEventListener("input", function (e) {
        standard = parseInt($("#marks").val());
        if (standard != NaN && standard >= 101) {
            $("#marks").val('100');
        } else if (standard != NaN && standard <= 0) {
            $("#marks").val('1');
        }
    });
</script>