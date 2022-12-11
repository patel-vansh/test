<?php
$heading_data = $this->crud_model->get_question_paper_heading_by_id($param5)->result_array()[0];
?>

<form
    action="<?php echo site_url('user/question_paper_headings/' . $param2 . '/' . $param3 . '/edit' . '/' . $param5); ?>"
    method="post">
    <div class="form-group">
        <label for="heading">
            <?php echo get_phrase('heading'); ?>
        </label>
        <input class="form-control" type="text" name="heading" id="heading" required
            value="<?php echo $heading_data['heading']; ?>">
        <small class="text-muted">
            <?php echo get_phrase('provide_a_heading'); ?>
        </small>
        <br>
        <br>
        <label for="marks">
            <?php echo get_phrase('marks'); ?>
        </label>
        <input class="form-control" type="number" name="marks" id="marks" max="100" min="1"
            value="<?php echo $heading_data['marks']; ?>">
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