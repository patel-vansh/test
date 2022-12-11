<?php $social_links = json_decode($user_details['social_links'], true);


if (addon_status('affiliate_course')) {

    $CI    = &get_instance();
    $CI->load->model('addons/affiliate_course_model');
    $x = $CI->affiliate_course_model->is_affilator($this->session->userdata('user_id'));
    if ($x == 1)
    {

    $payment_keys = json_decode($user_details['payment_keys'], true);
    $paypal_keys = $payment_keys['paypal'];
    $stripe_keys = $payment_keys['stripe'];
    $razorpay_keys = $payment_keys['razorpay'];
    }
}

?>


<?php include "profile_menus.php"; ?>

<section class="user-dashboard-area">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <div class="user-dashboard-sidebar">
                    <div class="user-box">
                        <img src="<?php echo $this->user_model->get_user_image_url($this->session->userdata('user_id')); ?>" alt="" class="img-fluid">
                        <div class="name">
                            <div class="name"><?php echo $user_details['first_name'] . ' ' . $user_details['last_name']; ?></div>
                        </div>
                    </div>
                    <div class="user-dashboard-menu">
                        <ul>
                            <li class="active mb-3"><a href="<?php echo site_url('home/profile/user_profile'); ?>"> <i class="fas fa-user-circle"></i> <?php echo site_phrase('profile'); ?></a></li>
                            <li class=" mb-3"><a href="<?php echo site_url('home/profile/user_credentials'); ?>"> <i class="fas fa-lock"></i> <?php echo site_phrase('account'); ?></a></li>
                            <li class=" mb-3"><a href="<?php echo site_url('home/profile/user_photo'); ?>"> <i class="fas fa-camera-retro"></i> <?php echo site_phrase('photo'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-9 mt-4 mt-md-0">
                <form class="w-100 bg-white radius-10 p-4" action="<?php echo site_url('home/update_profile/update_basics'); ?>" method="post">
                    <div class="row">
                        <div class="col-12 border-bottom mb-3 pb-3">
                            <h4><?php echo site_phrase('edit_profile'); ?></h4>
                            <p><?php echo site_phrase('add_information_about_yourself_to_share_on_your_profile'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <label class="text-dark fw-600" for="FristName"><?php echo site_phrase('first_name'); ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="first_name" id="FristName" placeholder="<?php echo site_phrase('first_name'); ?>" value="<?php echo $user_details['first_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-dark fw-600" for="FristName"><?php echo site_phrase('last_name'); ?></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="last_name" placeholder="<?php echo site_phrase('last_name'); ?>" value="<?php echo $user_details['last_name']; ?>">
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <?php if ($user_details['is_instructor'] > 0) : ?>
                                <div class="form-group mb-3">
                                    <label class="text-dark fw-600" for="Biography"><?php echo site_phrase('title'); ?></label>
                                    <textarea class="form-control" name="title" placeholder="<?php echo site_phrase('short_title_about_yourself'); ?>"><?php echo $user_details['title']; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="text-dark fw-600" for="skills"><?php echo get_phrase('your_skills'); ?></label>
                                    <input type="text" class=" tagify" id="skills" name="skills" data-role="tagsinput" style="width: 100%;" value="<?php echo $user_details['skills'];  ?>" />
                                    <small class="text-muted"><?php echo get_phrase('write_your_skill_and_click_the_enter_button'); ?></small>
                                </div>

                            <?php endif; ?>

                            <div class="form-group">
                                <label class="text-dark fw-600" for="Biography"><?php echo site_phrase('biography'); ?></label>
                                <textarea class="form-control author-biography-editor" name="biography" id="Biography"><?php echo $user_details['biography']; ?></textarea>
                            </div>

                            <?php if (addon_status('affiliate_course')) :
                                    $CI    = &get_instance();
                                    $CI->load->model('addons/affiliate_course_model');
                                    $x = $CI->affiliate_course_model->is_affilator($this->session->userdata('user_id'));
                                    if ($x == 1):
                                    
                                
                                ?>


                                <div class="tab-pane" id="payment_info">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4><?php echo get_phrase('paypal'); ?></h4>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-3 col-form-label" for="facebook_link"> <?php echo get_phrase('paypal_client_id'); ?></label>
                                                <div class="col-md-9">
                                                    <input type="text" id="paypal_client_id" name="paypal_client_id" class="form-control" value="<?php echo $paypal_keys['production_client_id']; ?>">
                                                    <small><?php echo get_phrase("required_for_getting_payment"); ?></small>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-3 col-form-label" for="facebook_link"> <?php echo get_phrase('paypal_secret_key'); ?></label>
                                                <div class="col-md-9">
                                                    <input type="text" id="paypal_secret_key" name="paypal_secret_key" class="form-control" value="<?php echo $paypal_keys['production_secret_key']; ?>">
                                                    <small><?php echo get_phrase("required_for_getting_payment"); ?></small>
                                                </div>
                                            </div>
                                            <hr>
                                            <h4><?php echo get_phrase('stripe'); ?></h4>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-3 col-form-label" for="stripe_public_key"><?php echo get_phrase('stripe_public_key'); ?></label>
                                                <div class="col-md-9">
                                                    <input type="text" id="stripe_public_key" name="stripe_public_key" class="form-control" value="<?php echo $stripe_keys['public_live_key']; ?>">
                                                    <small><?php echo get_phrase("required_for_getting_payment"); ?></small>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-3 col-form-label" for="stripe_secret_key"><?php echo get_phrase('stripe_secret_key'); ?></label>
                                                <div class="col-md-9">
                                                    <input type="text" id="stripe_secret_key" name="stripe_secret_key" class="form-control" value="<?php echo $stripe_keys['secret_live_key']; ?>">
                                                    <small><?php echo get_phrase("required_for_getting_payment"); ?></small>
                                                </div>
                                            </div>

                                            <hr>
                                            <h4><?php echo get_phrase('razorpay'); ?></h4>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-3 col-form-label" for="key_id"><?php echo get_phrase('key_id'); ?></label>
                                                <div class="col-md-9">
                                                    <input type="text" id="key_id" name="key_id" class="form-control" value="<?php echo $razorpay_keys['key_id']; ?>">
                                                    <small><?php echo get_phrase("required_for_getting_payment"); ?></small>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-3 col-form-label" for="secret_key"><?php echo get_phrase('secret_key'); ?></label>
                                                <div class="col-md-9">
                                                    <input type="text" id="secret_key" name="secret_key" class="form-control" value="<?php echo $razorpay_keys['secret_key']; ?>">
                                                    <small><?php echo get_phrase("required_for_getting_payment"); ?></small>
                                                </div>
                                            </div>


                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>

                                <?php endif; ?>

                            <?php endif; ?>

                            <hr class="my-5 bg-secondary">

                            <label class="text-dark fw-600"><?php echo site_phrase('add_your_twitter_link'); ?></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                <input type="text" class="form-control" maxlength="60" name="twitter_link" placeholder="<?php echo site_phrase('twitter_link'); ?>" value="<?php echo $social_links['twitter']; ?>">
                            </div>


                            <label class="text-dark fw-600"><?php echo site_phrase('add_your_facebook_link'); ?></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                <input type="text" class="form-control" maxlength="60" name="facebook_link" placeholder="<?php echo site_phrase('facebook_link'); ?>" value="<?php echo $social_links['facebook']; ?>">
                            </div>


                            <label class="text-dark fw-600"><?php echo site_phrase('add_your_linkedin_link'); ?></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                <input type="text" class="form-control" maxlength="60" name="linkedin_link" placeholder="<?php echo site_phrase('linkedin_link'); ?>" value="<?php echo $social_links['linkedin']; ?>">
                            </div>
                        </div>

                        <div class="col-12 pt-4">
                            <button class="btn red px-5 py-2 radius-8"><?php echo site_phrase('save'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>



<script type="text/javascript">
    $(function() {
        $(".bootstrap-tag-input").tagsinput('items');
    });
</script>