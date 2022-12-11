<?php
$question_paper_details = $this->crud_model->get_question_paper_by_id($question_paper_id)->row_array();

$sections = array();
foreach ($sections_id as $section_id) {
    $sections[] = $this->crud_model->get_question_paper_section_by_id($section_id)->result_array()[0];
}
usort($sections, function ($param1, $param2) {
    return strcmp($param1['sequence'], $param2['sequence']);
});
?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i>
                    <?php echo get_phrase('edit') . ': ' . $question_paper_details['title']; ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="javascript:void(0)" class="btn btn-outline-primary btn-rounded btn-sm ml-1 my-1"
                            onclick="showAjaxModal('<?php echo site_url('modal/popup/question_paper_section_add/' . $question_paper_id); ?>', '<?php echo get_phrase('add_new_section'); ?>')"><i
                                class="mdi mdi-plus"></i>
                            <?php echo get_phrase('add_section'); ?>
                        </a>
                        <?php if (count($sections) > 1): ?>
                        <a href="javascript:void(0)" class="btn btn-outline-primary btn-rounded btn-sm ml-1 my-1"
                            onclick="showAjaxModal('<?php echo site_url('modal/popup/question_paper_section_sort/' . $question_paper_id); ?>', '<?php echo get_phrase('sort_sections'); ?>')"><i
                                class="mdi mdi-sort"></i>
                            <?php echo get_phrase('sort_sections'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo site_url('user/question_paper_preview/' . $question_paper_id); ?>"
                            class="alignToTitle btn btn-outline-secondary btn-rounded btn-sm ml-1 my-1" target="_blank">
                            <?php echo get_phrase('preview'); ?> <i class="mdi mdi-arrow-right"></i>
                        </a>
                        <a href="<?php echo site_url('user/question_papers'); ?>"
                            class="alignToTitle btn btn-outline-secondary btn-rounded btn-sm my-1"> <i
                                class=" mdi mdi-keyboard-backspace"></i>
                            <?php echo get_phrase('back_to_question_papers_list'); ?>
                        </a>
                    </div>
                </div>

                <div class="row my-3">
                    <div class="col-xl-12">
                        <div class="row justify-content-center">
                            <div class="col-xl-8">
                                <div class="row">
                                    <?php
                                    foreach ($sections as $key => $section):
                                        $section_data = $section;
                                        $headings = $this->crud_model->get_question_paper_headings_of_section($section_data['id'])->result_array();
                                        ?>
                                    <div class="col-xl-12">
                                        <div class="card bg-light text-seconday on-hover-action mb-5"
                                            id="section-<?php echo $section_data['id']; ?>">
                                            <div class="card-body">
                                                <h5 class="card-title" style="min-height: 20px;">
                                                    <div class="row">
                                                        <div class="col-md-4"></div>
                                                        <div class="col-md-4 text-center" style="margin-top: 8px;">
                                                            <?php echo $section_data['name']; ?>
                                                            <span class="font-weight-light ml-1">
                                                                <?php echo '[' . $section_data['marks'] . ' marks]'; ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-md-4 text-right"
                                                            id="widgets-of-section-<?php echo $section_data['id']; ?>">
                                                            <button type="button"
                                                                class="btn btn-outline-primary btn-rounded btn-sm ml-1"
                                                                name="button"
                                                                onclick="showAjaxModal('<?php echo site_url('modal/popup/question_paper_section_edit/' . $section['id'] . '/' . $question_paper_id); ?>', '<?php echo get_phrase('update_section'); ?>')"><i
                                                                    class="mdi mdi-pencil-outline"></i></button>
                                                            <button type="button"
                                                                class="btn btn-outline-danger btn-rounded btn-sm ml-1"
                                                                name="button"
                                                                onclick="confirm_modal('<?php echo site_url('user/question_paper_sections/' . $question_paper_id . '/delete' . '/' . $section['id']); ?>');"><i
                                                                    class="mdi mdi-window-close"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3 ml-2">
                                                        <button type="button"
                                                            class="btn btn-outline-success btn-rounded btn-sm"
                                                            name="button"
                                                            onclick="showLargeModal('<?php echo site_url('modal/popup/question_paper_heading_add/' . $section['id'] . '/' . $question_paper_id); ?>', '<?php echo get_phrase('add_heading'); ?>')"><i
                                                                class="mdi mdi-plus"></i>
                                                            <?php echo get_phrase('add_heading') ?>
                                                        </button>
                                                        <?php if (count($headings) > 1): ?>
                                                        <button type="button"
                                                            class="btn btn-outline-info btn-rounded btn-sm ml-1"
                                                            name="button"
                                                            onclick="showLargeModal('<?php echo site_url('modal/popup/question_paper_heading_sort/' . $section['id']); ?>', '<?php echo get_phrase('sort_headings'); ?>')"><i
                                                                class="mdi mdi-sort"></i>
                                                            <?php echo get_phrase('sort_headings'); ?>
                                                        </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </h5>
                                                <div class="clearfix"></div>
                                                <?php
                                        foreach ($headings as $index => $heading): ?>
                                                <div class="col-md-12">
                                                    <!-- Portlet card -->
                                                    <div class="card text-secondary on-hover-action mb-2"
                                                        id="<?php echo 'heading-' . $heading['id']; ?>">
                                                        <div class="card-body thinner-card-body">
                                                            <div class="card-widgets"
                                                                id="widgets-of-heading-<?php echo $heading['id']; ?>">
                                                                <a href="javascript:;"
                                                                    onclick="showAjaxModal('<?php echo site_url('modal/popup/question_paper_heading_edit/' . $section['id'] . '/' . $question_paper_id . '/edit' . '/' . $heading['id']); ?>', '<?php echo get_phrase('update_heading'); ?>')"
                                                                    data-toggle="tooltip"
                                                                    title="<?php echo get_phrase('edit'); ?>"><i
                                                                        class="mdi mdi-pencil-outline"></i></a>
                                                                <a href="javascript:;"
                                                                    onclick="confirm_modal('<?php echo site_url('user/question_paper_headings/' . $section['id'] . '/' . $question_paper_id . '/delete' . '/' . $heading['id']); ?>');"
                                                                    data-toggle="tooltip"
                                                                    title="<?php echo get_phrase('delete'); ?>"><i
                                                                        class="mdi mdi-window-close"></i></a>
                                                            </div>

                                                        </div>
                                                        <div class="row mr-1 ml-1">
                                                            <h5 class="col-md-10">
                                                                <?php echo $heading['heading']; ?>
                                                            </h5>
                                                            <h5 class="col-md-2 text-right">
                                                                <span class="font-weight-light">
                                                                    <?php echo '[' . $heading['marks'] . ']'; ?>
                                                                </span>
                                                            </h5>
                                                        </div>
                                                        <div class="row mb-2 mt-1 ml-4">
                                                            <button type="button"
                                                                class="btn btn-outline-success btn-rounded btn-sm"
                                                                name="button"
                                                                onclick="showLargeModal('<?php echo site_url('modal/popup/sort_lesson/' . $section['id']); ?>', '<?php echo get_phrase('add'); ?>')"><i
                                                                    class="mdi mdi-plus"></i>
                                                                <?php echo get_phrase('add_question') ?>
                                                            </button>
                                                        </div>
                                                    </div> <!-- end card-->
                                                </div>
                                                <?php endforeach; ?>
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end row-->
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
</div>


<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {
        var scrollpos = sessionStorage.getItem('scrollpos');
        if (scrollpos) {
            window.scrollTo(0, scrollpos);
            sessionStorage.removeItem('scrollpos');
        }
    });

    window.addEventListener("beforeunload", function (e) {
        sessionStorage.setItem('scrollpos', window.scrollY);
    });

    document.querySelector("#standard").addEventListener("input", function (e) {
        standard = parseInt($("#standard").val());
        if (standard != NaN && standard >= 13) {
            $("#standard").val('12');
        } else if (standard != NaN && standard <= 0) {
            $("#standard").val('1');
        }
    });
</script>

<style media="screen">
    body {
        overflow-x: hidden;
    }
</style>