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

    var selected_standard = "All";
    var selected_medium = "All";
    var selected_board = "All";

    var standards = null;
    var mediums = null;
    var boards = null;

    function standard_changed(standard) {
        selected_standard = standard;
        changeMediums();
        changeBoards();
    }

    function medium_changed(medium) {
        selected_medium = medium;
        changeStandards();
        changeBoards();
    }

    function board_changed(board) {
        selected_board = board;
        changeStandards();
        changeMediums();
    }

    function changeStandards() {
        $.ajax({
            type: "GET",
            url: "<?php echo site_url('user/get_standards_for_question_bank/') ?>",
            data: { 'medium': selected_medium, 'board': selected_board },
            success: function (result) {
                standards = JSON.parse(result);
                var previous_selection = document.getElementById('standard').value;
                if (typeof(standards) != undefined) {
                    var str = "<option>All</option>";
                    for (let index = 0; index < standards.length; index++) {
                        var standard = standards[index];
                        str += "<option";
                        if (previous_selection == standard) {
                            str += " selected>" + standard + "</option>";
                        } else {
                            str += ">" + standard + "</option>";
                        }
                    }
                    document.getElementById('standard').innerHTML = str;
                }
            }
        });
    }

    function changeMediums() {
        $.ajax({
            type: "GET",
            url: "<?php echo site_url('user/get_mediums_for_question_bank/') ?>",
            data: { 'standard': selected_standard, 'board': selected_board },
            success: function (result) {
                mediums = JSON.parse(result);
                var previous_selection = document.getElementById('medium').value;
                if (typeof(mediums) != undefined) {
                    var str = "<option>All</option>";
                    for (let index = 0; index < mediums.length; index++) {
                        var medium = mediums[index];
                        str += "<option";
                        if (previous_selection == medium) {
                            str += " selected>" + medium + "</option>";
                        } else {
                            str += ">" + medium + "</option>";
                        }
                    }
                    document.getElementById('medium').innerHTML = str;
                }
            }
        });
    }

    function changeBoards() {
        $.ajax({
            type: "GET",
            url: "<?php echo site_url('user/get_boards_for_question_bank/') ?>",
            data: { 'standard': selected_standard, 'medium': selected_medium },
            success: function (result) {
                boards = JSON.parse(result);
                var previous_selection = document.getElementById('board').value;
                if (typeof(boards) != undefined) {
                    var str = "<option>All</option>";
                    for (let index = 0; index < boards.length; index++) {
                        var board = boards[index];
                        str += "<option";
                        if (previous_selection == board) {
                            str += " selected>" + board + "</option>";
                        } else {
                            str += ">" + board + "</option>";
                        }
                    }
                    document.getElementById('board').innerHTML = str;
                }
            }
        });
    }
</script>

<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i>
                    <?php echo get_phrase('question_bank'); ?>
                    <!-- <a href="<?php //echo site_url('user/question_paper/add'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php //echo get_phrase('add_new_question'); ?></a> -->
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('questions_list'); ?></h4>
                <form class="row justify-content-center" action="<?php echo site_url('user/question_bank'); ?>" method="get">
                    <!-- Question Paper Standards -->
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="standard">
                                <?php echo get_phrase('standards'); ?>
                            </label>
                            <select class="form-control select2" data-toggle="select2" name="standard" id="standard" onchange="standard_changed(this.value)">
                                <option value="<?php echo 'all'; ?>">
                                    <?php echo get_phrase('all'); ?>
                                </option>
                                <?php foreach ($standards as $standard): ?>
                                <option value="<?php echo $standard; ?>">
                                    <?php echo $standard; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Question Paper Mediums -->
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="medium">
                                <?php echo get_phrase('mediums'); ?>
                            </label>
                            <select class="form-control select2" data-toggle="select2" name="medium" id='medium' onchange="medium_changed(this.value)">
                                <option value="all">
                                    <?php echo get_phrase('all'); ?>
                                </option>
                                <?php foreach ($mediums as $medium): ?>
                                <option value="<?php echo $medium; ?>">
                                    <?php echo $medium; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Question Paper Boards -->
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="board">
                                <?php echo get_phrase('boards'); ?>
                            </label>
                            <select class="form-control select2" data-toggle="select2" name="board" id='board' onchange="board_changed(this.value)">
                                <option value="all">
                                    <?php echo get_phrase('all'); ?>
                                </option>
                                <?php foreach ($boards as $board): ?>
                                <option value="<?php echo $board; ?>">
                                    <?php echo $board; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <label for=".." class="text-white">..</label>
                        <button type="submit" class="btn btn-primary btn-block" name="button">
                            <?php echo get_phrase('filter'); ?>
                        </button>
                    </div>
                    </form>

                    <div class="table-responsive-sm mt-4">
                        <?php if (count($courses) > 0): ?>
                        <table id="course-datatable" class="table table-striped dt-responsive nowrap" width="100%"
                            data-page-length='25'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <?php echo get_phrase('title'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('category'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('lesson_and_section'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('enrolled_student'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('status'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('price'); ?>
                                    </th>
                                    <th>
                                        <?php echo get_phrase('actions'); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $key => $course):
                                $instructor_details = $this->user_model->get_all_user($course['user_id'])->row_array();
                                $category_details = $this->crud_model->get_category_details_by_id($course['sub_category_id'])->row_array();
                                $sections = $this->crud_model->get_section('course', $course['id']);
                                $lessons = $this->crud_model->get_lessons('course', $course['id']);
                                $enroll_history = $this->crud_model->enrol_history($course['id']);
                                ?>
                                <tr>
                                    <td>
                                        <?php echo ++$key; ?>
                                    </td>
                                    <td>
                                        <strong><a
                                                href="<?php echo site_url('user/course_form/course_edit/' . $course['id']); ?>">
                                                <?php echo ellipsis($course['title']); ?>
                                            </a></strong><br>
                                        <small class="text-muted">
                                            <?php echo get_phrase('instructor') . ': <b>' . $instructor_details['first_name'] . ' ' . $instructor_details['last_name'] . '</b>'; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-dark-lighten">
                                            <?php echo $category_details['name']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($course['course_type'] == 'scorm'): ?>
                                        <span class="badge badge-info-lighten">
                                            <?= $course['course_type']; ?>
                                        </span>
                                        <?php elseif ($course['course_type'] == 'general'): ?>
                                        <small class="text-muted">
                                            <?php echo '<b>' . get_phrase('total_section') . '</b>: ' . $sections->num_rows(); ?>
                                        </small><br>
                                        <small class="text-muted">
                                            <?php echo '<b>' . get_phrase('total_lesson') . '</b>: ' . $lessons->num_rows(); ?>
                                        </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo '<b>' . get_phrase('total_enrolment') . '</b>: ' . $enroll_history->num_rows(); ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($course['status'] == 'pending'): ?>
                                        <i class="mdi mdi-circle text-warning" style="font-size: 19px;"
                                            data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="<?php echo get_phrase($course['status']); ?>"></i>
                                        <?php elseif ($course['status'] == 'active'): ?>
                                        <i class="mdi mdi-circle text-success" style="font-size: 19px;"
                                            data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="<?php echo get_phrase($course['status']); ?>"></i>
                                        <?php elseif ($course['status'] == 'draft'): ?>
                                        <i class="mdi mdi-circle text-secondary" style="font-size: 19px;"
                                            data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="<?php echo get_phrase($course['status']); ?>"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($course['is_free_course'] == null): ?>
                                        <?php if ($course['discount_flag'] == 1): ?>
                                        <span class="badge badge-dark-lighten">
                                            <?php echo currency($course['discounted_price']); ?>
                                        </span>
                                        <?php else: ?>
                                        <span class="badge badge-dark-lighten">
                                            <?php echo currency($course['price']); ?>
                                        </span>
                                        <?php endif; ?>
                                        <?php elseif ($course['is_free_course'] == 1): ?>
                                        <span class="badge badge-success-lighten">
                                            <?php echo get_phrase('free'); ?>
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="dropright dropright">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-rounded btn-icon"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="<?php echo site_url('home/course/' . rawurlencode(slugify($course['title'])) . '/' . $course['id']); ?>"
                                                        target="_blank">
                                                        <?php echo get_phrase('view_course_on_frontend'); ?>
                                                    </a></li>
                                                <li><a class="dropdown-item"
                                                        href="<?php echo site_url('user/course_form/course_edit/' . $course['id']); ?>">
                                                        <?php echo get_phrase('edit_this_course'); ?>
                                                    </a></li>
                                                <?php if ($course['course_type'] != 'scorm'): ?>
                                                <li><a class="dropdown-item"
                                                        href="<?php echo site_url('user/course_form/course_edit/' . $course['id']); ?>">
                                                        <?php echo get_phrase('section_and_lesson'); ?>
                                                    </a></li>
                                                <?php endif; ?>
                                                <?php if ($course['status'] == 'active' || $course['status'] == 'pending'): ?>
                                                <li><a class="dropdown-item" href="#"
                                                        onclick="confirm_modal('<?php echo site_url('user/course_actions/draft/' . $course['id']); ?>');">
                                                        <?php echo get_phrase('mark_as_drafted'); ?>
                                                    </a></li>
                                                <?php else: ?>
                                                <li><a class="dropdown-item" href="#"
                                                        onclick="confirm_modal('<?php echo site_url('user/course_actions/publish/' . $course['id']); ?>');">
                                                        <?php echo get_phrase('publish_this_course'); ?>
                                                    </a></li>
                                                <?php endif; ?>
                                                <li><a class="dropdown-item" href="#"
                                                        onclick="confirm_modal('<?php echo site_url('user/course_actions/delete/' . $course['id']); ?>');">
                                                        <?php echo get_phrase('delete_this_course'); ?>
                                                    </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                        <?php if (count($courses) == 0): ?>
                        <div class="img-fluid w-100 text-center">
                            <img style="opacity: 1; width: 100px;"
                                src="<?php echo base_url('assets/backend/images/file-search.svg'); ?>"><br>
                            <?php echo get_phrase('no_data_found'); ?>
                        </div>
                        <?php endif; ?>
                    </div>
            </div>
        </div>
    </div>
</div>