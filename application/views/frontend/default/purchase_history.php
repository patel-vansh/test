<?php include "profile_menus.php"; ?>
<section class="purchase-history-list-area">
    <div class="container">
        <ul class="purchase-history-list">
            <li class="purchase-history-list-header">
                <div class="row">
                    <div class="col-5"> <?php echo site_phrase('purchased_courses'); ?></div>
                    <div class="col-7 hidden-xxs hidden-xs">
                        <div class="row">
                            <div class="col-3"> <?php echo site_phrase('date'); ?> </div>
                            <div class="col-2"> <?php echo site_phrase('price'); ?> </div>
                            <div class="col-4"> <?php echo site_phrase('payment_type'); ?> </div>
                            <div class="col-3"> <?php echo site_phrase('actions'); ?> </div>
                        </div>
                    </div>
                </div>
            </li>
            <?php if (count($purchase_history) > 0):
                foreach($purchase_history as $each_purchase):
                    if ($each_purchase['course_id']) {
                        $course_details = $this->crud_model->get_course_by_id($each_purchase['course_id'])->row_array();
                        $sub_category_details = $this->crud_model->get_sub_category($course_details['sub_category_id'])->row_array();
                    } else {
                        $course_details = $this->crud_model->get_course_bundle_by_id($each_purchase['bundle_id'])->row_array();
                        }?>
                    <li class="purchase-history-items radius-10 mb-2">
                        <div class="row">
                            <div class="col-5">
                                <!--<div class="purchase-history-course-img">-->
                                <!--    <img src="<?php// echo $this->crud_model->get_course_thumbnail_url($each_purchase['course_id']);?>" class="img-fluid">-->
                                <!--</div>-->
                                <a class="purchase-history-course-title" href="<?php
                                        if ($each_purchase['course_id']) {
                                            echo site_url('home/course/'.slugify($course_details['title']).'/'.$course_details['id']);
                                        } else {
                                            echo site_url('bundle_details/' . $course_details['id']. '/' . slugify($course_details['title']));
                                        }?>">
                                            <?php
                                            if ($each_purchase['course_id']) {
                                                echo $course_details['title'] . " (" . $sub_category_details['name'] . ") (Course)";
                                            } else {
                                                echo $course_details['title'] . " (Course Bundle)";
                                            }
                                            ?>
                                </a>
                            </div>
                            <div class="col-7 purchase-history-detail">
                                <div class="row">
                                    <div class="col-3 date">
                                        <?php echo date('D, d-M-Y', $each_purchase['date_added']); ?>
                                    </div>
                                    <div class="col-2 price"><b>
                                        <?php echo currency($each_purchase['amount']); ?>
                                    </b></div>
                                    <div class="col-4 payment-type">
                                        <?php
                                                if ($each_purchase['course_id']) {
                                                    echo ucfirst($each_purchase['payment_type']);
                                                } else {
                                                    echo ucfirst($each_purchase['payment_method']);
                                                }?>
                                    </div>
                                    <div class="col-3">
                                        <a href="<?php
                                                if ($each_purchase['course_id']) {
                                                    echo site_url('home/invoice/'.$each_purchase['id']);
                                                } else {
                                                    echo site_url('home/bundle_invoice/' . $each_purchase['id']);
                                                }?>" target="_blank" class="btn btn-receipt"><?php echo site_phrase('invoice'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <div class="row" style="text-align: center;">
                        <?php echo site_phrase('no_records_found'); ?>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</section>
<?php
  if(addon_status('offline_payment') == 1):
    include "pending_purchase_course_history.php";
  endif;
?>
<nav>
    <?php echo $this->pagination->create_links(); ?>
</nav>