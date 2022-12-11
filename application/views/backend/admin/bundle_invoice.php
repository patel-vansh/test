<!-- start page title -->
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('invoice'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<?php
    $instructor_details = $this->user_model->get_all_user($bundle_payment['bundle_creator_id'])->row_array();
    $user_data = $this->user_model->get_all_user($bundle_payment['user_id'])->row_array();
    $bundle_details = $this->course_bundle_model->get_bundle($bundle_payment['bundle_id'])->row_array();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <!-- Invoice Logo-->
                <div class="clearfix">
                    <div class="float-left mb-3">
                        <img src="<?php echo base_url('uploads/system/'.get_frontend_settings('dark_logo'));?>" alt="" height="18">
                    </div>
                    <div class="float-right">
                        <h4 class="m-0 d-print-none"><?php echo get_phrase("invoice"); ?></h4>
                    </div>
                </div>

                <!-- Invoice Detail-->
                <div class="row">
                    <div class="col-sm-6">

                    </div><!-- end col -->
                    <div class="col-sm-4 offset-sm-2">
                        <div class="mt-3 float-sm-right">
                            <p class="font-13 pb-2">
                                <strong class="float-left mr-3"><?php echo get_phrase("payment_status"); ?>: </strong>
                                <span class="badge badge-success float-right"><?php echo get_phrase("paid"); ?></span>
                            </p>
                            <p class="font-13 pb-2">
                                <strong class="float-left mr-3"><?php echo get_phrase("purchase_date"); ?>: </strong>
                                <span class="float-right"><?= date('d M Y', $bundle_payment['date_added']); ?></span>
                            </p>
                            <?php $expire_date = $bundle_payment['date_added']+$bundle_details['subscription_limit']*86400; ?>
                            <p class="font-13 pb-2">
                                <strong class="float-left mr-3"><?php echo get_phrase("expire_date"); ?>: </strong>
                                <span class="float-right"><?= date('d M Y', $expire_date); ?></span>
                            </p>
                            <p class="font-13 pb-2">
                                <strong class="float-left mr-3"><?php echo get_phrase("subscription_validity"); ?>: </strong>
                                <?php if($expire_date >= strtotime(date('d M Y'))): ?>
                                    <span class="badge badge-success float-right"><?= get_phrase('valid'); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-danger float-right"><?= get_phrase('expired'); ?></span>
                                    <?= date('d M Y'); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

                <div class="row mt-4">
                    <div class="col-sm-4">
                        <h6><?php echo get_phrase("student"); ?></h6>
                        <address>
                            <?php echo $user_data['first_name'].' '.$user_data['last_name']; ?><br>
                            <?php echo $user_data['email']; ?><br>
                        </address>
                    </div> <!-- end col-->

                    <div class="col-sm-4">
                        <h6><?php echo get_phrase("instructor"); ?></h6>
                        <address>
                            <?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?><br>
                            <?php echo $instructor_details['email']; ?><br>
                        </address>
                    </div> <!-- end col-->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mt-4">
                                <thead>
                                    <tr>
                                        <th><?php echo get_phrase("included_courses"); ?></th>
                                        <th class="text-right"><?php echo get_phrase("total"); ?></th>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php foreach(json_decode($bundle_details['course_ids']) as $course_id_of_bundle):
                                                    echo ' - '.$bundle_courses = $this->crud_model->get_course_by_id($course_id_of_bundle)->row('title').'</br>';
                                                endforeach; ?>
                                            </td>
                                            
                                            <td class="text-right">
                                                <?php echo currency($bundle_payment['amount']); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-sm-6">

                        </div> <!-- end col -->
                        <div class="col-sm-6">
                            <div class="float-right mt-3 mt-sm-0">
                                <p><b><?php echo get_phrase("sub_total"); ?>:</b> <span class="float-right">
                                    <?php echo currency($bundle_payment['amount']); ?>
                                </span></p>
                                <h3>
                                    <?php echo currency($bundle_payment['amount']); ?>
                                </h3>
                            </div>
                            <div class="clearfix"></div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->

                    <div class="d-print-none mt-4">
                        <div class="text-right">
                            <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i> <?php echo get_phrase('print'); ?></a>
                        </div>
                    </div>
                    <!-- end buttons -->

                </div> <!-- end card-body-->
            </div> <!-- end card -->
        </div> <!-- end col-->
    </div>
