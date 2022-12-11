<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i>
                    <?php echo get_phrase('add_new_question_paper'); ?>
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
                        <h4 class="header-title my-1">
                            <?php echo get_phrase('question_paper_adding_form'); ?>
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo site_url('user/question_papers'); ?>"
                            class="alignToTitle btn btn-outline-secondary btn-rounded btn-sm my-1"> <i
                                class=" mdi mdi-keyboard-backspace"></i>
                            <?php echo get_phrase('back_to_question_papers_list'); ?>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <form class="required-form" action="<?php echo site_url('user/question_paper_actions/add'); ?>"
                            method="post" enctype="multipart/form-data">
                            <div id="basicwizard">

                                <div class="row justify-content-center">
                                    <div class="col-xl-8">

                                        <div class="form-group row mb-3">
                                            <label class="col-md-2 col-form-label" for="title">
                                                <?php echo get_phrase('question_paper_title'); ?><span
                                                    class="required">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="title" name="title"
                                                    placeholder="<?php echo get_phrase('enter_title'); ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-2 col-form-label" for="instruction">
                                                <?php echo get_phrase('instruction'); ?>
                                            </label>
                                            <div class="col-md-10">
                                                <textarea name="instruction" id="instruction"
                                                    class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-2 col-form-label" for="standard">
                                                <?php echo get_phrase('standard'); ?><span class="required">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input type="number" class="form-control" id="standard" name="standard"
                                                    placeholder="<?php echo get_phrase('enter_standard'); ?>" min="1"
                                                    max="12">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-2 col-form-label" for="medium">
                                                <?php echo get_phrase('medium'); ?><span class="required">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <select class="form-control select2" data-toggle="select2" name="medium"
                                                    id="medium">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-2 col-form-label" for="board">
                                                <?php echo get_phrase('board'); ?><span class="required">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <select class="form-control select2" data-toggle="select2" name="board"
                                                    id="board">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-2 col-form-label" for="subject">
                                                <?php echo get_phrase('subject'); ?><span class="required">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <select class="form-control select2" data-toggle="select2"
                                                    name="subject" id="subject">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-2 col-form-label" for="max_time">
                                                <?php echo get_phrase('max_time'); ?><span class="required">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" data-toggle='timepicker'
                                                    data-minute-step="5" name="max_time" id="max_time"
                                                    data-show-meridian="false" value="00:00:00"
                                                    data-show-seconds="false">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-2 col-form-label" for="marks">
                                                <?php echo get_phrase('marks'); ?><span class="required">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input type="number" class="form-control" id="marks" name="marks"
                                                    placeholder="<?php echo get_phrase('enter_marks'); ?>" min="1"
                                                    max="100">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="mb-3 mt-3">
                                                <button type="button" class="btn btn-primary text-center"
                                                    onclick="checkRequiredFieldsForPaperGenerator()">
                                                    <?php echo get_phrase('generate_and_add_questions'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div> <!-- end #progressbarwizard-->
                        </form>
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

    var standard;

    var mediums = null;
    var boards = null;
    var subjects = null;

    var medium_id = 0;
    var board_id = 0;
    var subject_id = 0;

    $(document).ready(function () {
        initSummerNote(['#instruction']);
    });

    document.querySelector("#standard").addEventListener("input", function (e) {
        standard = parseInt($("#standard").val());
        if (standard != NaN && standard >= 13) {
            $("#standard").val('12');
        } else if (standard != NaN && standard <= 0) {
            $("#standard").val('1');
        }
        setOptionToDefault('medium', 'Medium');
        setOptionToDefault('board', 'Board');
        setOptionToDefault('subject', 'Subject');
        getMediumsFromStandard();
    });

    document.querySelector("#marks").addEventListener("input", function (e) {
        $marks = parseInt($("#marks").val());
        if ($marks != NaN && $marks >= 101) {
            $("#marks").val('100');
        } else if ($marks != NaN && $marks <= 0) {
            $("#marks").val('1');
        }
    });

    function setOptionToDefault(id, text) {
        document.getElementById(id).innerHTML = "<option>No " + text + " Selected</option>";
    }

    var blank_outcome = jQuery('#blank_outcome_field').html();
    var blank_requirement = jQuery('#blank_requirement_field').html();

    $('document').ready(function () {
        $('#medium').change(function () {
            current_medium = $(this).val();
            for (let index = 0; index < mediums.length; index++) {
                var medium = mediums[index];
                if (typeof (medium) != undefined) {
                    if (medium.name == current_medium) {
                        medium_id = medium.id;
                        break;
                    }
                }
            }
            if (medium_id != 0) {
                getBoardsFromMedium();
            }
        })
    });

    $('document').ready(function () {
        $('#board').change(function () {
            current_board = $(this).val();
            for (let index = 0; index < boards.length; index++) {
                var board = boards[index];
                if (typeof (board) != undefined) {
                    if (board.name == current_board) {
                        board_id = board.id;
                        break;
                    }
                }
            }
            if (board_id != 0) {
                getSubjectsFromBoard();
            }
        })
    });

    $('document').ready(function () {
        $('#subject').change(function () {
            current_subject = $(this).val();
            for (let index = 0; index < subjects.length; index++) {
                var subject = subjects[index];
                if (typeof (subject) != undefined) {
                    if (subject.name == current_subject) {
                        subject_id = subject.id;
                        break;
                    }
                }
            }
        })
    });

    jQuery(document).ready(function () {
        jQuery('#blank_outcome_field').hide();
        jQuery('#blank_requirement_field').hide();
    });
    function appendOutcome() {
        jQuery('#outcomes_area').append(blank_outcome);
    }
    function removeOutcome(outcomeElem) {
        jQuery(outcomeElem).parent().parent().remove();
    }

    function appendRequirement() {
        jQuery('#requirement_area').append(blank_requirement);
    }
    function removeRequirement(requirementElem) {
        jQuery(requirementElem).parent().parent().remove();
    }

    function getMediumsFromStandard() {
        $.ajax({
            type: "GET",
            url: "<?php echo site_url('user/get_mediums_from_standard/') ?>",
            data: { 'standard': standard },
            success: function (result) {
                mediums = JSON.parse(result);
                if (typeof (mediums) != undefined) {
                    var str = "<option>No Medium Selected</option>"
                    for (var medium of mediums) {
                        str += "<option>" + medium.medium + "</option>";
                    }
                    document.getElementById("medium").innerHTML = str;
                }
            }
        });
    }

    function getBoardsFromMedium() {
        $.ajax({
            type: "GET",
            url: "<?php echo site_url('user/get_boards_from_medium/') ?>",
            data: { 'medium_id': medium_id },
            success: function (result) {
                boards = JSON.parse(result);
                if (typeof (boards) != undefined) {
                    var str = "<option>No Board Selected</option>"
                    for (var board of boards) {
                        str += "<option>" + board.name + "</option>";
                    }
                    document.getElementById("board").innerHTML = str;
                    setOptionToDefault('subject', 'Subject')
                }
            }
        });
    }

    function getSubjectsFromBoard() {
        $.ajax({
            type: "GET",
            url: "<?php echo site_url('user/get_subjects_from_board/') ?>",
            data: { 'medium_id': medium_id, 'board_id': board_id },
            success: function (result) {
                subjects = JSON.parse(result);
                if (typeof (subjects) != undefined) {
                    var str = "<option>No Subject Selected</option>"
                    for (var subject of subjects) {
                        str += "<option>" + subject.name + "</option>";
                    }
                    document.getElementById("subject").innerHTML = str;
                }
            }
        });
    }

    function checkRequiredFieldsForPaperGenerator() {
        var pass = 1;
        $('form.required-form').find('input, select').each(function () {
            if ($(this).prop('required')) {
                if ($(this).val() === "") {
                    pass = 0;
                }
            }
        });

        if (pass === 1) {
            var title = $('#title').val();
            var instruction = $('#instruction').val();
            var max_time = $('#max_time').val();
            var marks = $('#marks').val();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('user/question_paper_actions/add/') ?>",
                data: { 'medium_id': medium_id, 'board_id': board_id, 'subject_id': subject_id, 'standard': standard, 'title': title, 'instruction': instruction, 'max_time': max_time, 'marks': marks },
                success: function (result) {
                    window.location.replace(result);
                }
            });
        } else {
            error_required_field();
        }
    }
</script>

<style media="screen">
    body {
        overflow-x: hidden;
    }
</style>