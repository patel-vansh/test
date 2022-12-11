<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('my_question_papers'); ?>
                    <a href="<?php echo site_url('user/question_paper/add'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_new_question_paper'); ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive-sm mt-4">
                    <table id="basic-datatable" class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase('name'); ?></th>
                                <th><?php echo get_phrase('std'); ?></th>
                                <th><?php echo get_phrase('subject'); ?></th>
                                <th><?php echo get_phrase('marks'); ?></th>
                                <th><?php echo get_phrase('created'); ?></th>
                                <th><?php echo get_phrase('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($question_papers->result_array() as $key => $question_paper) : ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $question_paper['title']?>
                                    </td>
                                    <td><?php echo $question_paper['standard']; ?></td>
                                    <td>
                                        <?php
                                        $subject_details = $this->crud_model->get_question_paper_subjects_by_id($question_paper['subject_id'])->result_array()[0];
                                        echo $subject_details['name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $question_paper['marks']; ?>
                                    </td>
                                    <td>
                                        <?php echo date('d-m-y', intval($question_paper['created_on'])); ?>
                                    </td>
                                    <td>
                                        <div class="dropright dropright">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="<?php echo site_url('user/question_paper/edit/' . $question_paper['id']) ?>"><?php echo get_phrase('edit'); ?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('user/question_paper_actions/delete/' . $question_paper['id']); ?>');"><?php echo get_phrase('delete'); ?></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>