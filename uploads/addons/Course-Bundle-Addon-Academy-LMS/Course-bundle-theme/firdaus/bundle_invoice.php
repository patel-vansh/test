<section id="hero_in" class="courses d-print-none">
    <div class="banner-img" style="background: url(<?= base_url("uploads/system/".get_frontend_settings('banner_image')); ?>) center center no-repeat;"></div>
    <div class="wrapper">
        <div class="container">
            <h1 class="fadeInUp"><span></span><?php echo site_phrase('bundle_invoice'); ?></h1>
        </div>
    </div>
</section>

<section class="purchase-history-list-area">
    <div class="container mb-5">
        <div class="row">
            <div class="col">
                <div class="ml-auto">
                    <div class="bg-eceff4-p-1-5rem">
                        <table class="w-100">
                            <tr>
                                <td>
                                    <img src="<?php echo base_url('uploads/system/'.get_frontend_settings('dark_logo'));?>" class="d-inline-block" height="40">
                                </td>
                                <td class="text-right text-22 strong"><?php echo strtoupper(site_phrase('invoice')); ?></td>
                            </tr>
                        </table>
                        <table class="w-100">
                            <tr>
                                <td class="strong text-1-2-rem"><?php echo get_settings('system_name'); ?></td>
                                <td class="text-right"></td>
                            </tr>
                            <tr>
                                <td class="gry-color small"><?php echo get_settings('system_email'); ?></td>
                                <td class="text-right"></td>
                            </tr>
                            <tr>
                                <td class="gry-color small"><?php echo get_settings('address'); ?></td>
                                <td class="text-right small"><span class="gry-color small"><?php echo site_phrase('payment_method'); ?>:</span> <span class="strong"><?php echo ucfirst($bundle_payment['payment_method']); ?></span></td>
                            </tr>
                            <tr>
                                <td class="gry-color small"><?php echo site_phrase('phone'); ?>: <?php echo get_settings('phone'); ?></td>
                                <td class="text-right small"><span class="gry-color small"><?php echo site_phrase('purchase_date'); ?>:</span> <span class=" strong"><?php echo date('D, d-M-Y', $bundle_payment['date_added']); ?></span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-right small">
                                    <span class="gry-color small"><?php echo site_phrase('expire_date'); ?>:</span>
                                    <span class=" strong">
                                        <?php $expire_date = $bundle_payment['date_added']+$bundle_details['subscription_limit']*86400; ?>
                                        <?= date('d M Y', $expire_date); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-right small">
                                    <span class="gry-color small"><?php echo site_phrase('subscription_status'); ?>:</span>
                                    <?php if($expire_date >= strtotime(date('d M Y'))): ?>
                                        <span class="ml-2 badge badge-success float-right"><?= get_phrase('valid'); ?></span>
                                    <?php else: ?>
                                        <span class="ml-2 badge badge-danger float-right"><?= get_phrase('expired'); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="invoice-border"></div>
                    <div class="p-1-5-rem">
                        <table>
                            <tr><td class="strong small gry-color"><?php echo site_phrase('bill_to'); ?>:</td></tr>
                            <tr><td class="strong"><?php echo $student_details['first_name'].' '.$student_details['last_name']; ?></td></tr>
                            <tr><td class="gry-color small"><?php echo site_phrase('email'); ?>: <?php echo $student_details['email']; ?></td></tr>
                        </table>
                    </div>
                    <div style="padding: 0px 24px;">
                        <table class="padding text-left small border-bottom w-100">
                            <thead>
                                <tr class="gry-color bg-eceff4">
                                    <th width="50%"><?php echo site_phrase('included_courses'); ?></th>
                                    <th width="15%"><?php echo site_phrase('instructor'); ?></th>
                                    <th width="20%" class="text-right"><?php echo site_phrase('amount'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="strong">
                                <tr class="">
                                    <td>
                                        <?php foreach(json_decode($bundle_details['course_ids']) as $course_id_of_bundle):
                                            echo ' - '.$bundle_courses = $this->crud_model->get_course_by_id($course_id_of_bundle)->row('title').'</br>';
                                        endforeach; ?>
                                    </td>
                                    <td class="gry-color"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></td>
                                    <td class="text-right"><?php echo currency($bundle_payment['amount']); ?></td>
                                </tr>
                                <tr class="border-top mt-5">
                                    <td></td>
                                    <td class="gry-color"> <strong><?php echo site_phrase('sub_total'); ?>:</strong> </td>
                                    <td class="text-right"><strong><?php echo currency($bundle_payment['amount']); ?></strong></td>
                                </tr>
                                <tr class="">
                                    <td></td>
                                    <td class="gry-color strong"><strong><?php echo site_phrase('grand_total'); ?></strong>:</td>
                                    <td class="text-right"><strong><?php echo currency($bundle_payment['amount']); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-print-none mb-2">
            <a href="javascript:window.print()" class="btn btn-info float-right mt-4"><?php echo site_phrase('print_invoice'); ?></a>
        </div>
    </div>
</section>
