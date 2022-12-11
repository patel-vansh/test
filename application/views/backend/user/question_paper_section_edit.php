<?php
$section_data = $this->crud_model->get_question_paper_section_by_id($param2)->result_array()[0];
?>

<form action="<?php echo site_url('user/question_paper_sections/' . $param3 . '/edit' . '/' . $param2); ?>" method="post">
    <div class="form-group">
        <label for="section_title">
            <?php echo get_phrase('title'); ?>
        </label>
        <input class="form-control" type="text" name="section_title" id="section_title"
            value="<?php echo $section_data['name']; ?>" required>
        <small class="text-muted">
            <?php echo get_phrase('provide_a_section_name'); ?>
        </small>
        <br>
        <br>
        <label for="marks">
            <?php echo get_phrase('marks'); ?>
        </label>
        <input class="form-control" type="number" name="marks" id="marks" max="100" min="1" value="<?php echo $section_data['marks']; ?>">
    </div>
    <div class="text-right">
        <button class="btn btn-success" type="submit" name="button">
            <?php echo get_phrase('update'); ?>
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