<div class="col-lg-9">
    <div class="in-cart-box">
        <div class="title"><?php echo sizeof($this->crud_model->getCartItems()) + sizeof($this->crud_model->getCartBundleItems()) . ' ' . site_phrase('courses_in_cart'); ?></div>
        <div class="">
            <ul class="cart-course-list">
                <?php
                $actual_price = 0;
                $total_price  = 0;
                foreach ($this->crud_model->getCartItems() as $cart_item) :
                    $course_details = $this->crud_model->get_course_by_id($cart_item)->row_array();
                    $instructor_details = $this->user_model->get_all_user($course_details['user_id'])->row_array();
                ?>
                    <li>
                        <div class="cart-course-wrapper box-shadow-5">
                            <div class="image d-none d-md-block">
                                <a href="<?php echo site_url('home/course/' . rawurlencode(slugify($course_details['title'])) . '/' . $course_details['id']); ?>">
                                    <img src="<?php echo $this->crud_model->get_course_thumbnail_url($cart_item); ?>" alt="" class="img-fluid">
                                </a>
                            </div>
                            <div class="details">
                                <a href="<?php echo site_url('home/course/' . rawurlencode(slugify($course_details['title'])) . '/' . $course_details['id']); ?>">
                                    <div class="name"><?php echo $course_details['title']; ?></div>
                                </a>

                                <div class="course-subtitle text-13px mt-2">
                                    <?php echo ellipsis($course_details['short_description'], 80); ?>
                                </div>

                                <div class="floating-user d-inline-block mt-2">
                                    <?php if ($course_details['multi_instructor']):
                                        $instructor_details = $this->user_model->get_multi_instructor_details_with_csv($course_details['user_id']);
                                        $margin = 0;
                                        foreach ($instructor_details as $key => $instructor_detail) { ?>
                                            <img style="margin-left: <?php echo $margin; ?>px;" class="position-absolute cursor-pointer" src="<?php echo $this->user_model->get_user_image_url($instructor_detail['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $instructor_detail['first_name'].' '.$instructor_detail['last_name']; ?>" onclick="event.stopPropagation(); $(location).attr('href', '<?php echo site_url('home/instructor_page/'.$instructor_detail['id']); ?>');">
                                            <?php $margin = $margin+17; ?>
                                        <?php } ?>
                                    <?php else: ?>
                                        <?php $user_details = $this->user_model->get_all_user($course_details['user_id'])->row_array(); ?>
                                        <img class=" cursor-pointer" src="<?php echo $this->user_model->get_user_image_url($user_details['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>" onclick="event.stopPropagation(); $(location).attr('href', '<?php echo site_url('home/instructor_page/'.$user_details['id']); ?>');">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="move-remove text-center">
                                <div id="<?php echo $course_details['id']; ?>" onclick="removeFromCartList(this)"><i class="fas fa-times-circle"></i> <?php echo site_phrase('remove'); ?></div>
                                <!-- <div>Move to Wishlist</div> -->
                            </div>
                            <div class="price">
                                <?php if ($course_details['discount_flag'] == 1) : ?>
                                    <div class="current-price">
                                        <?php
                                        $total_price += $course_details['discounted_price'];
                                        echo currency($course_details['discounted_price']);
                                        ?>
                                    </div>
                                    <div class="original-price">
                                        <?php
                                        $actual_price += $course_details['price'];
                                        echo currency($course_details['price']);
                                        ?>
                                    </div>
                                <?php else : ?>
                                    <div class="current-price">
                                        <?php
                                        $actual_price += $course_details['price'];
                                        $total_price  += $course_details['price'];
                                        echo currency($course_details['price']);
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php foreach($this->crud_model->getCartBundleItems() as $bundle_item):
                    $bundle_details = $this->crud_model->get_course_bundle_by_id($bundle_item)->row_array();
                    $instructor_details = $this->user_model->get_all_user($bundle_details['user_id'])->row_array();?>
                    <li>
                        <div class="cart-course-wrapper">
                            <div class="image d-none d-md-block">
                                <a href="<?php echo site_url('bundle_details/' . $bundle_details['id']) . '/' . rawurlencode(slugify($bundle_details['title'])); ?>">
                                    <img src="<?php echo base_url() ?>/uploads/thumbnails/bundle_thumbnails/bundle_thumbnail.png" alt="" class="img-fluid">
                                </a>
                            </div>
                            <div class="details">
                                <a href="<?php echo site_url('bundle_details/' . $bundle_details['id']) . '/' . rawurlencode(slugify($bundle_details['title'])); ?>">
                                    <div class="name"><?php echo $bundle_details['title']; ?></div>
                                </a>

                                <div class="course-subtitle text-13px mt-2">
                                    <?php echo ellipsis($bundle_details['bundle_details'], 80); ?>
                                </div>

                                <div class="floating-user d-inline-block mt-2">
                                    <?php// if ($course_details['multi_instructor']):
                                        //$instructor_details = $this->user_model->get_multi_instructor_details_with_csv($course_details['user_id']);-->
                                        //$margin = 0;-->
                                        //foreach ($instructor_details as $key => $instructor_detail) { ?>
                                    <!--        <img style="margin-left: <?php// echo $margin; ?>px;" class="position-absolute cursor-pointer" src="<?php// echo $this->user_model->get_user_image_url($instructor_detail['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php// echo $instructor_detail['first_name'].' '.$instructor_detail['last_name']; ?>" onclick="event.stopPropagation(); $(location).attr('href', '<?php //echo site_url('home/instructor_page/'.$instructor_detail['id']); ?>');">-->
                                            <?php// $margin = $margin+17; ?>
                                        <?php// } ?>
                                    <?php// else: ?>
                                        <?php $user_details = $this->user_model->get_all_user($bundle_details['user_id'])->row_array(); ?>
                                        <img class=" cursor-pointer" src="<?php echo $this->user_model->get_user_image_url($user_details['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>" onclick="event.stopPropagation(); $(location).attr('href', '<?php echo site_url('home/instructor_page/'.$user_details['id']); ?>');">
                                    <?php// endif; ?>
                                </div>
                            </div>
                            <div class="move-remove text-center">
                                <div id="<?php echo $bundle_details['id']; ?>" onclick="removeBundleFromCartList(this)"><i class="fas fa-times-circle"></i> <?php echo site_phrase('remove'); ?></div>
                                <!-- <div>Move to Wishlist</div> -->
                            </div>
                            <div class="price">
                                <div class="current-price">
                                    <?php
                                        $total_price += $bundle_details['price'];
                                        echo currency($bundle_details['price']);
                                    ?>
                                </div>
                                <div class="original-price">
                                    <?php
                                        $bundle_original_price = $this->course_bundle_model->get_original_price_of_bundle($bundle_details['id']);
                                        $actual_price += $bundle_original_price;
                                        echo currency($bundle_original_price);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<div class="col-lg-3 pt-1">
    <h5 class="fw-700"><?php echo site_phrase('total'); ?>:</h5>
    <div class="cart-sidebar bg-white radius-10 py-4 px-3 box-shadow-5">
        <?php if (isset($coupon_code) && !empty($coupon_code) && $this->crud_model->check_coupon_validity($coupon_code)) : ?>
            <span id="total_price_of_checking_out" hidden>
                <?php
                $coupon_details = $this->crud_model->get_coupon_details_by_code($coupon_code)->row_array();
                $actual_price = round($total_price, 2);
                
                //including Tax or vat
                if(get_settings('course_selling_tax') > 0):
                    $total_price = $this->crud_model->get_discounted_price_after_applying_coupon($coupon_code);

                    $total_tax = round(($total_price/100) * get_settings('course_selling_tax'), 2);
                    $total_price = round($total_price + ($total_price/100) * get_settings('course_selling_tax'), 2);
                else:
                    $total_price = round($this->crud_model->get_discounted_price_after_applying_coupon($coupon_code), 2);
                endif;


                echo $total_price;
                $this->session->set_userdata('total_price_of_checking_out', $total_price);
                $this->session->set_userdata('applied_coupon', $coupon_code);
                ?>
            </span>
            <div class="total-price"><?php echo currency($total_price); ?></div>
            <div class="total-original-price">
                <span class="original-price">
                    <span class="original-price"><?php echo currency($actual_price); ?></span>
                </span>
                <span class="discount-rate"><?php echo $coupon_details['discount_percentage']; ?>% <?php echo site_phrase('coupon_code_applied'); ?></span>
            </div>
        <?php else : ?>
            <!-- including Tax or vat -->
            <?php
                if(get_settings('course_selling_tax') > 0):
                    $total_tax = round(($total_price/100) * get_settings('course_selling_tax'), 2);
                    $total_price = round($total_price + ($total_price/100) * get_settings('course_selling_tax'), 2);
                else:
                    $total_price = round($total_price, 2);
                endif;
            ?>

            <span id="total_price_of_checking_out" hidden><?php echo $total_price;
                $this->session->set_userdata('total_price_of_checking_out', $total_price); ?>
            </span>
            <div class="total-price"><?php echo currency($total_price); ?></div>
            <div class="total-original-price">
                <span class="original-price">
                    <?php if (isset($course_details) && $course_details['discount_flag'] == 1) : ?>
                        <span class="original-price"><?php echo currency(round($actual_price, 2)); ?></span>
                    <?php endif; ?>
                </span>
                <!-- <span class="discount-rate">95% off</span> -->
            </div>
        <?php endif; ?>


        <!-- including Tax or vat -->
        <?php if(get_settings('course_selling_tax') > 0): ?>
            <small class="d-block mb-3 w-100 text-12px text-uppercase"><b><?php echo currency($total_tax).'</b> ('.get_settings('course_selling_tax').'%) '.get_phrase('TAX_INCLUDED'); ?></small>
        <?php endif; ?>
        

        <!-- including Tax or vat -->
        <?php if(get_settings('course_selling_tax') > 0):
            $total_price = $total_price + ($total_price/100) * get_settings('course_selling_tax');
        endif;?>

        <div class="input-group marge-input-box mb-3">
            <input type="text" class="form-control" placeholder="<?php echo site_phrase('apply_coupon_code'); ?>" id="coupon-code">
            <div class="input-group-append">
                <button class="btn" type="button" onclick="applyCoupon()">
                    <i class="fas fa-spinner fa-pulse hidden" id="spinner"></i>
                    <?php echo site_phrase('apply'); ?>
                </button>
            </div>
        </div>
        <button type="button" class="btn red w-100 radius-10 mb-3" onclick="handleCheckOut()"><?php echo site_phrase('checkout'); ?></button>
    </div>
</div>