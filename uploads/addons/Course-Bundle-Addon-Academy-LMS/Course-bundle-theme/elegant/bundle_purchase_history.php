<div class="row">
    <div class="col-md-12">
        <ul class="purchase-history-list">
            <li class="purchase-history-list-header border-bottom-0">
                <div class="row">
                    <div class="col-md-12"><h6 class="purchase-history-list-title"> <?php echo site_phrase('bundle_purchase_history'); ?> </h6></div>
                </div>
            </li>
            <?php
            foreach($bundle_purchase_histories->result_array() as $bundle_purchase_history):
                $bundle_details = $this->course_bundle_model->get_bundle($bundle_purchase_history['bundle_id'])->row_array();?>
                <li class="purchase-history-items mb-2 border-bottom-0 border-top">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <a class="purchase-history-course-title" href="<?= site_url('bundle_details/'.$bundle_details['id'].'/'.slugify($bundle_details['title'])); ?>" >
                                <?= $bundle_details['title']; ?>
                            </a>
                        </div>
                        <div class="col-md-12 hidden-xxs hidden-xs">
                            <div class="row">
                                <div class="col-sm-4"> <?php echo site_phrase('date'); ?> </div>
                                <div class="col-sm-3"> <?php echo site_phrase('total_price'); ?> </div>
                                <div class="col-sm-3"> <?php echo site_phrase('payment_type'); ?> </div>
                                <div class="col-sm-2"> <?php echo site_phrase('actions'); ?> </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 purchase-history-detail">
                            <div class="row">
                                <div class="col-sm-4 date">
                                    <?php echo date('D, d-M-Y', $bundle_purchase_history['date_added']); ?>
                                </div>
                                <div class="col-sm-3 price"><b>
                                    <?php echo currency($bundle_purchase_history['amount']); ?>
                                </b></div>
                                <div class="col-sm-3 payment-type">
                                    <?php echo ucfirst($bundle_purchase_history['payment_method']); ?>
                                </div>
                                <div class="col-sm-2">
                                    <a href="<?php echo site_url('home/bundle_invoice/'.$bundle_purchase_history['id']); ?>" target="_blank" class="btn btn-receipt"><?php echo site_phrase('invoice'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>