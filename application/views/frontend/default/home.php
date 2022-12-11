<section class="home-banner-area" id="home-banner-area" style="background-image: url('<?= base_url("uploads/system/" . get_frontend_settings('banner_image')); ?>'); background-position: center; background-repeat: no-repeat; padding: 100px 0 75px; background-size: auto auto; color: #fff;">
    <div class="container-xl">
        <div class="row">
            <div class="col position-relative">
                <div class="home-banner-wrap">
                    <h2 class="fw-bold"><?php echo site_phrase(get_frontend_settings('banner_title')); ?></h2>
                    <p><?php echo site_phrase(get_frontend_settings('banner_sub_title')); ?></p>
                    <form class="search-box-shadow" action="<?php echo site_url('home/search'); ?>" method="get">
                        <div class="input-group search-box">
                            <input type="text" class="form-control" name="query" placeholder="<?php echo site_phrase('what_do_you_want_to_learn'); ?>?">
                            <div class="input-group-append p-5px bg-white">
                                <button class="btn" type="submit"><?php echo site_phrase('search'); ?> <i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>




<section class="home-fact-area">
    <div class="container-lg">
        <div class="row">
            <?php $courses = $this->crud_model->get_courses(); ?>
            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto mr-auto">
                    <i class="fas fa-bullseye float-start"></i>
                    <div class="text-box">
                        <h4><?php
                            $status_wise_courses = $this->crud_model->get_status_wise_courses();
                            $number_of_courses = $status_wise_courses['active']->num_rows();
                            echo $number_of_courses . ' ' . site_phrase('online_courses'); ?></h4>
                        <p><?php echo site_phrase('explore_a_variety_of_fresh_topics'); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto mr-auto">
                    <i class="fa fa-check float-start"></i>
                    <div class="text-box">
                        <h4><?php echo site_phrase('expert_instruction'); ?></h4>
                        <p><?php echo site_phrase('find_the_right_course_for_you'); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto mr-auto">
                    <i class="fa fa-clock float-start"></i>
                    <div class="text-box">
                        <h4><?php echo site_phrase('lifetime_access'); ?></h4>
                        <p><?php echo site_phrase('learn_on_your_schedule'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--                 Course Bundle Start -->

<!-- Course Bundle Data Fetch Start -->
<?php  
$user_id = $this->session->userdata('user_id');
if($user_id != '')
{ 
    $sql = $this->db->query("SELECT * FROM `bundle_payment` WHERE `user_id`='$user_id'");
    $rows = $sql->num_rows();
    if($rows != 0)
    { 
        $bundle_id = array();
        foreach($sql->result_array() as $roo)
        {
            if (get_bundle_validity($roo['bundle_id'], $user_id) == "valid") {
                $bundle_id[] = $roo['bundle_id'];
            }
        }
    }
    if(!empty($bundle_id))
    {
        $count = count($bundle_id);
        $cids = array();
        for ($i=0; $i < $count ; $i++) 
        { 
            $b_id = $bundle_id[$i];
             
            $s = $this->db->query("SELECT * FROM `course_bundle` WHERE `id`='$b_id'");
            $c_rows = $s->result_array();
            $course_ids = array();
            foreach($c_rows as $crow)
            { 
                $course_ids[] = implode(',',json_decode($crow['course_ids']));
            } 
            $cids[] = implode(',',$course_ids); 
        } 
        $final_courses_id = implode(',',$cids);
        $final_courses_id = explode(',',$final_courses_id);  
       // print_r($final_courses_id);

    }
    else
    {
        $final_courses_id = array();
    }
}
else
{
 $final_courses_id = array();
}
 
?>
<!-- Course Bundle Data Fetch End -->

<?php if ($course_accessibility): ?>
    <section class="course-carousel-area"> 
        <div class="container-lg">
            <h3 class="course-carousel-title text-center my-3">
            <span class="header-underline-2"><?php echo site_phrase('course_bundles'); ?></span>
        </h3>

        <div class="row justify-content-center">
                <div class="col">
      
                    <!-- Animated page loader -->
                    <div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div>

                    <div class="course-carousel shown-after-loading" style="display: none;">
                        <?php $course_bundles = $course_bundles->result_array();
                        $cart_items = $this->crud_model->getCartBundleItems();
                        foreach ($course_bundles as $course_bundle) : ?>
                            <div class="course-box-wrap" style="">
                                <a onclick="return check_action(this);" href="<?php echo site_url('bundle_details/' .$course_bundle['id']) . '/' . rawurlencode(slugify($course_bundle['title'])) ; ?>" class="has-popover">
                                    <div class="course-box" style="top: 14px; border: 1px solid #dedede;">
                                        <div class="course-box" style="right: 7px; bottom: 7px; box-shadow: none; border: 1px solid #dedede">
                                            <div class="course-box" style="right: 7px; bottom: 7px; box-shadow: none; border: 1px solid #dedede">
                                            
                                        
                                        <div class="course-image">
                                            <img src="<?php echo site_url('uploads/thumbnails/bundle_thumbnails/bundle_thumbnail.png'); ?>" alt="" class="img-fluid">
                                        </div>
                                        <div class="course-details">
                                            <h5 class="title"><?php echo $course_bundle['title']; ?></h5>
                                            <div class="rating">
                                                <?php
                                                $total_rating =  $this->course_bundle_model->get_bundle_wise_ratings($course_bundle['id'])->row()->rating;
                                                $number_of_ratings = $this->course_bundle_model->get_bundle_wise_ratings($course_bundle['id'])->num_rows();
                                                if ($number_of_ratings > 0) {
                                                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                                                } else {
                                                    $average_ceil_rating = 0;
                                                }

                                                for ($i = 1; $i < 6; $i++) : ?>
                                                    <?php if ($i <= $average_ceil_rating) : ?>
                                                        <i class="fas fa-star filled"></i>
                                                    <?php else : ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <div class="d-inline-block">
                                                    <span class="text-dark ms-1 text-15px">(<?php echo $average_ceil_rating; ?>)</span>
                                                    <span class="text-dark text-12px text-muted ms-2">(<?php echo $number_of_ratings.' '.site_phrase('reviews'); ?>)</span>
                                                </div>
                                            </div>

                                            <hr class="divider-1">

                                            <div class="d-block">
                                                <div class="floating-user d-inline-block">
                                                    <?php $user_details = $this->user_model->get_all_user($course_bundle['user_id'])->row_array(); ?>
                                                    <img src="<?php echo $this->user_model->get_user_image_url($user_details['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>"  onclick="return check_action(this,'<?php echo site_url('home/instructor_page/'.$user_details['id']); ?>');">
                                                </div>
                                                
                                                    <p class="price text-right d-inline-block float-end"><small><?php echo currency($this->course_bundle_model->get_original_price_of_bundle($course_bundle['id'])); ?></small><?php echo currency($course_bundle['price']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <div class="webui-popover-content">
                                    <div class="course-popover-content">
                                        <div class="last-updated fw-500"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $course_bundle['date_added']); ?></div>

                                        <div class="course-title">
                                            <a class="text-decoration-none text-15px" href="<?php echo site_url('bundle_details/' . $course_bundle['id'] . '/' . rawurlencode(slugify($course_bundle['title']))); ?>"><?php echo $course_bundle['title']; ?></a>
                                        </div>
                                        <div class="course-subtitle"><?php echo $course_bundle['bundle_details']; ?></div>
                                        <div class="popover-btns">
                                            <?php if ($this->course_bundle_model->is_bundle_purchased($this->session->userdata('user_id'), $course_bundle['id'])) : ?>
                                                <div class="purchased">
                                                    <a href="<?php echo site_url('home/my_bundles'); ?>"><?php echo site_phrase('already_purchased'); ?></a>
                                                </div>
                                            <?php else : ?>
                                                <button type="button" class="btn red add-to-cart-btn <?php if (in_array($course_bundle['id'], $cart_items)) echo 'addedToCart'; ?> big-cart-button-<?php echo $course_bundle['id']; ?>" id="<?php echo $course_bundle['id']; ?>" onclick="handleBundleCartItems(this)">
                                                    <?php
                                                    if (in_array($course_bundle['id'], $cart_items))
                                                        echo site_phrase('added_to_cart');
                                                    else
                                                        echo site_phrase('add_to_cart');
                                                    ?>
                                                </button>
                                            <button type="button" class="wishlist-btn <?php if ($this->crud_model->is_bundle_added_to_wishlist($course_bundle['id'])) echo 'active'; ?>" title="Add to wishlist" onclick="handleBundleWishList(this)" id="<?php echo $course_bundle['id']; ?>"><i class="fas fa-heart"></i></button>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include "course_bundle_scripts.php"; ?>
<!--                Course Bundle End -->

    <section class="mb-5 pb-4">
        <div class="container-lg">
            <h3 class="course-carousel-title text-center my-5">
                <span class="header-underline-2"><?php echo site_phrase('top_categories'); ?></span>
            </h3>
    
            <div class="row justify-content-center">
    
                <?php $top_10_categories = $this->crud_model->get_top_categories(12, 'sub_category_id'); ?>
                <?php foreach ($top_10_categories as $top_10_category) : ?>
                    <?php $category_details = $this->crud_model->get_category_details_by_id($top_10_category['sub_category_id'])->row_array(); ?>
                    <div class="col-md-4 col-lg-3 col-xl-3 mb-3 m-md-0 p-0">
                        <a href="<?php echo site_url('home/courses?category=' . $category_details['slug']); ?>" class="top-categories">
                            <div class="category-icon">
                                <i class="<?php echo $category_details['font_awesome_class']; ?>"></i>
                            </div>
                            <div class="category-title">
                                <?php echo $category_details['name']; ?>
                                <p><?php echo $top_10_category['course_number'] . ' ' . site_phrase('courses'); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="course-carousel-area">
        <div class="container-lg">
            <div class="row">
                <div class="col">
                    <h3 class="course-carousel-title text-center my-3">
                        <span class="header-underline-2"><?php echo site_phrase('top_courses'); ?></span>
                    </h3>

                    <!-- page loader -->
                    <div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div>

                    <div class="course-carousel shown-after-loading" style="display: none;">
                        <?php $top_courses = $this->crud_model->get_top_courses()->result_array();
                        $cart_items = $this->crud_model->getCartItems();
                        foreach ($top_courses as $top_course) : ?>
                            <?php
                                $lessons = $this->crud_model->get_lessons('course', $top_course['id']);
                                $course_duration = $this->crud_model->get_total_duration_of_lesson_by_course_id($top_course['id']);
                            ?>
                            <div class="course-box-wrap">
                                <a onclick="return check_action(this);" href="<?php echo site_url('home/course/' . rawurlencode(slugify($top_course['title'])) . '/' . $top_course['id']); ?>" class="has-popover">
                                    <div class="course-box">
                                        <div class="course-image">
                                            <img src="<?php echo $this->crud_model->get_course_thumbnail_url($top_course['id']); ?>" alt="" class="img-fluid">
                                        </div>
                                        <div class="course-details">
                                            <h5 class="title"><?php echo $top_course['title']; ?></h5>
                                            <div class="rating">
                                                <?php
                                                $total_rating =  $this->crud_model->get_ratings('course', $top_course['id'], true)->row()->rating;
                                                $number_of_ratings = $this->crud_model->get_ratings('course', $top_course['id'])->num_rows();
                                                if ($number_of_ratings > 0) {
                                                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                                                } else {
                                                    $average_ceil_rating = 0;
                                                }

                                                for ($i = 1; $i < 6; $i++) : ?>
                                                    <?php if ($i <= $average_ceil_rating) : ?>
                                                        <i class="fas fa-star filled"></i>
                                                    <?php else : ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <div class="d-inline-block">
                                                    <span class="text-dark ms-1 text-15px">(<?php echo $average_ceil_rating; ?>)</span>
                                                    <span class="text-dark text-12px text-muted ms-2">(<?php echo $number_of_ratings.' '.site_phrase('reviews'); ?>)</span>
                                                </div>
                                            </div>
                                            <div class="d-flex text-dark">
                                                <div class="">
                                                    <i class="far fa-clock text-14px"></i>
                                                    <span class="text-muted text-12px"><?php echo $course_duration; ?></span>
                                                </div>
                                                <div class="ms-3">
                                                    <i class="far fa-list-alt text-14px"></i>
                                                    <span class="text-muted text-12px"><?php echo $lessons->num_rows().' '.site_phrase('lectures'); ?></span>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-6">
                                                    <span class="badge badge-sub-warning text-11px"><?php echo site_phrase($top_course['level']); ?></span>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button class="btn-compare-sm" onclick="return check_action(this, '<?php echo site_url('home/compare?course-1=' . rawurlencode(slugify($top_course['title'])) . '&&course-id-1=' . $top_course['id']); ?>');"><i class="fas fa-balance-scale"></i> <?php echo site_phrase('compare'); ?></button>
                                                </div>
                                            </div>

                                            <hr class="divider-1">

                                            <div class="d-block">
                                                <div class="floating-user d-inline-block">
                                                    <?php if ($top_course['multi_instructor']):
                                                        $instructor_details = $this->user_model->get_multi_instructor_details_with_csv($top_course['user_id']);
                                                        $margin = 0;
                                                        foreach ($instructor_details as $key => $instructor_detail) { ?>
                                                            <img style="margin-left: <?php echo $margin; ?>px;" class="position-absolute" src="<?php echo $this->user_model->get_user_image_url($instructor_detail['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $instructor_detail['first_name'].' '.$instructor_detail['last_name']; ?>" onclick="return check_action(this,'<?php echo site_url('home/instructor_page/'.$instructor_detail['id']); ?>');">
                                                            <?php $margin = $margin+17; ?>
                                                        <?php } ?>
                                                    <?php else: ?>
                                                        <?php $user_details = $this->user_model->get_all_user($top_course['user_id'])->row_array(); ?>
                                                        <img src="<?php echo $this->user_model->get_user_image_url($user_details['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>"  onclick="return check_action(this,'<?php echo site_url('home/instructor_page/'.$user_details['id']); ?>');">
                                                    <?php endif; ?>
                                                </div>



                                                <?php if ($top_course['is_free_course'] == 1) : ?>
                                                    <p class="price text-right d-inline-block float-end"><?php echo site_phrase('free'); ?></p>
                                                <?php else : ?>
                                                    <?php if ($top_course['discount_flag'] == 1) : ?>
                                                        <p class="price text-right d-inline-block float-end"><small><?php echo currency($top_course['price']); ?></small><?php echo currency($top_course['discounted_price']); ?></p>
                                                    <?php else : ?>
                                                        <p class="price text-right d-inline-block float-end"><?php echo currency($top_course['price']); ?></p>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <div class="webui-popover-content">
                                    <div class="course-popover-content">
                                        <?php if ($top_course['last_modified'] == "") : ?>
                                            <div class="last-updated fw-500"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $top_course['date_added']); ?></div>
                                        <?php else : ?>
                                            <div class="last-updated"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $top_course['last_modified']); ?></div>
                                        <?php endif; ?>

                                        <div class="course-title">
                                            <a class="text-decoration-none text-15px" href="<?php echo site_url('home/course/' . rawurlencode(slugify($top_course['title'])) . '/' . $top_course['id']); ?>"><?php echo $top_course['title']; ?></a>
                                        </div>
                                        <div class="course-meta">
                                            <?php if ($top_course['course_type'] == 'general') : ?>
                                                <span class=""><i class="fas fa-play-circle"></i>
                                                    <?php echo $this->crud_model->get_lessons('course', $top_course['id'])->num_rows() . ' ' . site_phrase('lessons'); ?>
                                                </span>
                                                <span class=""><i class="far fa-clock"></i>
                                                    <?php echo $course_duration; ?>
                                                </span>
                                            <?php elseif ($top_course['course_type'] == 'scorm') : ?>
                                                <span class="badge bg-light"><?= site_phrase('scorm_course'); ?></span>
                                            <?php endif; ?>
                                            <span class=""><i class="fas fa-closed-captioning"></i><?php echo ucfirst($top_course['language']); ?></span>
                                        </div>
                                        <div class="course-subtitle"><?php echo $top_course['short_description']; ?></div>
                                        <div class="what-will-learn">
                                            <ul>
                                                <?php
                                                $outcomes = json_decode($top_course['outcomes']);
                                                foreach ($outcomes as $outcome) : ?>
                                                    <li><?php echo $outcome; ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="popover-btns">
                                            <?php if (is_purchased($top_course['id'])) : ?>
                                                <div class="purchased">
                                                    <a href="<?php echo site_url('home/my_courses'); ?>"><?php echo site_phrase('already_purchased'); ?></a>
                                                </div>
                                            <?php else : ?>
                                                <?php if ($top_course['is_free_course'] == 1) :
                                                    if ($this->session->userdata('user_login') != 1) {
                                                        $url = "#";
                                                    } else {
                                                        $url = site_url('home/get_enrolled_to_free_course/' . $top_course['id']);
                                                    } ?>
                                                    <a href="<?php echo $url; ?>" class="btn green radius-10" onclick="handleEnrolledButton()"><?php echo site_phrase('get_enrolled'); ?></a>
                                                <?php else : ?>
                                                    <button type="button" class="btn red add-to-cart-btn <?php if (in_array($top_course['id'], $cart_items)) echo 'addedToCart'; ?> big-cart-button-<?php echo $top_course['id']; ?>" id="<?php echo $top_course['id']; ?>" onclick="handleCartItems(this)">
                                                        <?php
                                                        if (in_array($top_course['id'], $cart_items))
                                                            echo site_phrase('added_to_cart');
                                                        else
                                                            echo site_phrase('add_to_cart');
                                                        ?>
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" class="wishlist-btn <?php if ($this->crud_model->is_added_to_wishlist($top_course['id'])) echo 'active'; ?>" title="Add to wishlist" onclick="handleWishList(this)" id="<?php echo $top_course['id']; ?>"><i class="fas fa-heart"></i></button>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="course-carousel-area">
        <div class="container-lg">
            <div class="row">
                <div class="col">
                    <h3 class="course-carousel-title my-3 text-center">
                        <span class="header-underline-2"><?php echo site_phrase('top') . ' 10 ' . site_phrase('latest_courses'); ?>
                    </h3></span>

                    <!-- page loader -->
                    <div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div>

                    <div class="course-carousel shown-after-loading" style="display: none;">
                        <?php
                        $latest_courses = $this->crud_model->get_latest_10_course();
                        foreach ($latest_courses as $latest_course) : ?>
                            <?php
                                $lessons = $this->crud_model->get_lessons('course', $latest_course['id']);
                                $course_duration = $this->crud_model->get_total_duration_of_lesson_by_course_id($latest_course['id']);
                            ?>
                            <div class="course-box-wrap">
                                <a onclick="return check_action(this);" href="<?php echo site_url('home/course/' . rawurlencode(slugify($latest_course['title'])) . '/' . $latest_course['id']); ?>" class="has-popover">
                                    <div class="course-box">
                                        <div class="course-image">
                                            <img src="<?php echo $this->crud_model->get_course_thumbnail_url($latest_course['id']); ?>" alt="" class="img-fluid">
                                        </div>
                                        <div class="course-details">
                                            <h5 class="title"><?php echo $latest_course['title']; ?></h5>
                                            <div class="rating">
                                                <?php
                                                $total_rating =  $this->crud_model->get_ratings('course', $latest_course['id'], true)->row()->rating;
                                                $number_of_ratings = $this->crud_model->get_ratings('course', $latest_course['id'])->num_rows();
                                                if ($number_of_ratings > 0) {
                                                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                                                } else {
                                                    $average_ceil_rating = 0;
                                                }

                                                for ($i = 1; $i < 6; $i++) : ?>
                                                    <?php if ($i <= $average_ceil_rating) : ?>
                                                        <i class="fas fa-star filled"></i>
                                                    <?php else : ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <div class="d-inline-block">
                                                    <span class="text-dark ms-1 text-15px">(<?php echo $average_ceil_rating; ?>)</span>
                                                    <span class="text-dark text-12px text-muted ms-2">(<?php echo $number_of_ratings.' '.site_phrase('reviews'); ?>)</span>
                                                </div>
                                            </div>
                                            <div class="d-flex text-dark">
                                                <div class="">
                                                    <i class="far fa-clock text-14px"></i>
                                                    <span class="text-muted text-12px"><?php echo $course_duration; ?></span>
                                                </div>
                                                <div class="ms-3">
                                                    <i class="far fa-list-alt text-14px"></i>
                                                    <span class="text-muted text-12px"><?php echo $lessons->num_rows().' '.site_phrase('lectures'); ?></span>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-6">
                                                    <span class="badge badge-sub-warning text-11px"><?php echo site_phrase($latest_course['level']); ?></span>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button class="btn-compare-sm" onclick="return check_action(this, '<?php echo site_url('home/compare?course-1=' . rawurlencode(slugify($latest_course['title'])) . '&&course-id-1=' . $latest_course['id']); ?>');"><i class="fas fa-balance-scale"></i> <?php echo site_phrase('compare'); ?></button>
                                                </div>
                                            </div>

                                            <hr class="divider-1">

                                            <div class="d-block">
                                                <div class="floating-user d-inline-block">
                                                    <?php if ($latest_course['multi_instructor']):
                                                        $instructor_details = $this->user_model->get_multi_instructor_details_with_csv($latest_course['user_id']);
                                                        $margin = 0;
                                                        foreach ($instructor_details as $key => $instructor_detail) { ?>
                                                            <img style="margin-left: <?php echo $margin; ?>px;" class="position-absolute" src="<?php echo $this->user_model->get_user_image_url($instructor_detail['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $instructor_detail['first_name'].' '.$instructor_detail['last_name']; ?>" onclick="return check_action(this,'<?php echo site_url('home/instructor_page/'.$instructor_detail['id']); ?>');">
                                                            <?php $margin = $margin+17; ?>
                                                        <?php } ?>
                                                    <?php else: ?>
                                                        <?php $user_details = $this->user_model->get_all_user($latest_course['user_id'])->row_array(); ?>
                                                        <img src="<?php echo $this->user_model->get_user_image_url($user_details['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>"  onclick="return check_action(this,'<?php echo site_url('home/instructor_page/'.$user_details['id']); ?>');">
                                                    <?php endif; ?>
                                                </div>



                                                <?php if ($latest_course['is_free_course'] == 1) : ?>
                                                    <p class="price text-right d-inline-block float-end"><?php echo site_phrase('free'); ?></p>
                                                <?php else : ?>
                                                    <?php if ($latest_course['discount_flag'] == 1) : ?>
                                                        <p class="price text-right d-inline-block float-end"><small><?php echo currency($latest_course['price']); ?></small><?php echo currency($latest_course['discounted_price']); ?></p>
                                                    <?php else : ?>
                                                        <p class="price text-right d-inline-block float-end"><?php echo currency($latest_course['price']); ?></p>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <div class="webui-popover-content">
                                    <div class="course-popover-content">
                                        <?php if ($latest_course['last_modified'] == "") : ?>
                                            <div class="last-updated fw-500"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $latest_course['date_added']); ?></div>
                                        <?php else : ?>
                                            <div class="last-updated"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $latest_course['last_modified']); ?></div>
                                        <?php endif; ?>

                                        <div class="course-title">
                                            <a class="text-decoration-none text-15px" href="<?php echo site_url('home/course/' . rawurlencode(slugify($latest_course['title'])) . '/' . $latest_course['id']); ?>"><?php echo $latest_course['title']; ?></a>
                                        </div>
                                        <div class="course-meta">
                                            <?php if ($latest_course['course_type'] == 'general') : ?>
                                                <span class=""><i class="fas fa-play-circle"></i>
                                                    <?php echo $this->crud_model->get_lessons('course', $latest_course['id'])->num_rows() . ' ' . site_phrase('lessons'); ?>
                                                </span>
                                                <span class=""><i class="far fa-clock"></i>
                                                    <?php echo $course_duration; ?>
                                                </span>
                                            <?php elseif ($latest_course['course_type'] == 'scorm') : ?>
                                                <span class="badge bg-light"><?= site_phrase('scorm_course'); ?></span>
                                            <?php endif; ?>
                                            <span class=""><i class="fas fa-closed-captioning"></i><?php echo ucfirst($latest_course['language']); ?></span>
                                        </div>
                                        <div class="course-subtitle"><?php echo $latest_course['short_description']; ?></div>
                                        <div class="what-will-learn">
                                            <ul>
                                                <?php
                                                $outcomes = json_decode($latest_course['outcomes']);
                                                foreach ($outcomes as $outcome) : ?>
                                                    <li><?php echo $outcome; ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="popover-btns">
                                            <?php if (is_purchased($latest_course['id']) || in_array($latest_course['id'], $final_courses_id)) : ?>
                                                <div class="purchased">
                                                    <a href="<?php echo site_url('home/my_courses'); ?>"><?php echo site_phrase('already_purchased'); ?></a>
                                                </div>
                                            <?php else : ?>
                                                <?php if ($latest_course['is_free_course'] == 1) :
                                                    if ($this->session->userdata('user_login') != 1) {
                                                        $url = "#";
                                                    } else {
                                                        $url = site_url('home/get_enrolled_to_free_course/' . $latest_course['id']);
                                                    } ?>
                                                    <a href="<?php echo $url; ?>" class="btn green radius-10" onclick="handleEnrolledButton()"><?php echo site_phrase('get_enrolled'); ?></a>
                                                <?php else : ?>
                                                    <button type="button" class="btn red add-to-cart-btn <?php if (in_array($latest_course['id'], $cart_items)) echo 'addedToCart'; ?> big-cart-button-<?php echo $latest_course['id']; ?>" id="<?php echo $latest_course['id']; ?>" onclick="handleCartItems(this)">
                                                        <?php
                                                        if (in_array($latest_course['id'], $cart_items))
                                                            echo site_phrase('added_to_cart');
                                                        else
                                                            echo site_phrase('add_to_cart');
                                                        ?>
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" class="wishlist-btn <?php if ($this->crud_model->is_added_to_wishlist($latest_course['id'])) echo 'active'; ?>" title="Add to wishlist" onclick="handleWishList(this)" id="<?php echo $latest_course['id']; ?>"><i class="fas fa-heart"></i></button>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endif; ?>

<?php $top_instructor_ids = $this->crud_model->get_top_instructor(10); ?>
<?php if(count($top_instructor_ids) > 0): ?>
    <section class="featured-instructor">
        <div class="container-lg">
            <div class="row">
                <div class="col">
                    <h3 class="text-center mb-3">
                        <span class="header-underline-2"><?php echo site_phrase('featured_instructor'); ?></span>
                    </h3>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-7 ">
                    <div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div>
                    <div class="top-istructor-slick shown-after-loading" style="display: none;">
                        <?php foreach($top_instructor_ids as $top_instructor_id): ?>
                            <?php $top_instructor = $this->user_model->get_all_user($top_instructor_id['creator'])->row_array(); ?>
                            <div class="d-xl-flex text-center text-md-start radius-10 p-0 bg-sub-primary pt-4 pt-xl-0 position-relative mx-2">
                                <div class="top-instructor-img me-auto ms-auto ms-xl-0">
                                    <a href="<?php echo site_url('home/instructor_page/'.$top_instructor['id']); ?>">
                                        <img src="<?php echo $this->user_model->get_user_image_url($top_instructor['id']); ?>" width="100%">
                                    </a>
                                </div>
                                <div class="top-instructor-details ps-3 pe-3 pe-xl-5 py-3 py-xl-0 text-center text-xl-start">
                                    <a class="text-decoration-none" href="<?php echo site_url('home/instructor_page/'.$top_instructor['id']); ?>">
                                        <h4 class="mb-1 fw-700"><?php echo $top_instructor['first_name'].' '.$top_instructor['last_name']; ?></h4>
                                        <span class="fw-500 text-muted text-14px ellipsis-line-5 mb-3"><?php echo $top_instructor['title']; ?></span>

                                        <div class="rating">
                                          <div class="d-inline-block mb-2">
                                            <span class="text-dark fw-800 text-muted ms-1 text-13px"><?php echo $this->crud_model->get_instructor_wise_course_ratings($top_instructor['id'], 'course')->num_rows() . ' ' . site_phrase('reviews'); ?></span>
                                            |
                                            <span class="text-dark fw-800 text-13px text-muted mx-1">
                                              <?php $course_ids = $this->crud_model->get_instructor_wise_courses($top_instructor['id'], 'simple_array');
                                              $this->db->select('user_id');
                                              $this->db->distinct();
                                              $this->db->where_in('course_id', $course_ids);
                                              echo $this->db->get('enrol')->num_rows() . ' ' . site_phrase('students'); ?>
                                            </span>
                                            |
                                            <span class="text-dark fw-800 text-14px text-muted">
                                              <?php echo $this->crud_model->get_instructor_wise_courses($top_instructor['id'])->num_rows() . ' ' . site_phrase('courses'); ?>
                                            </span>
                                          </div>
                                        </div>

                                        <?php $skills = explode(',', $top_instructor['skills']); ?>
                                        <?php foreach($skills as $skill): ?>
                                          <span class="badge bg-primary text-12px my-1 py-2"><?php echo $skill; ?></span>
                                        <?php endforeach; ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>


<?php $latest_blogs = $this->crud_model->get_latest_blogs(3); ?>
<?php if(get_frontend_settings('blog_visibility_on_the_home_page') && $latest_blogs->num_rows() > 0): ?>
    <section class="section-blog pt-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h3 class="text-center mb-3">
                        <span class="header-underline-2"><?php echo site_phrase('latest_blogs'); ?></span>
                    </h3>
                </div>
                <?php foreach($latest_blogs->result_array() as $latest_blog): ?>
                    <?php $user_details = $this->user_model->get_all_user($latest_blog['user_id'])->row_array(); ?>

                    <div class="col-md-6 col-lg-4">
                        <a href="<?php echo site_url('blog/details/'.slugify($latest_blog['title']).'/'.$latest_blog['blog_id']); ?>">
                            <div class="card-blog">
                                <?php
                                    $blog_thumbnail = 'uploads/blog/thumbnail/'.$latest_blog['thumbnail'];
                                    if(!file_exists($blog_thumbnail) || !is_file($blog_thumbnail)):
                                        $blog_thumbnail = base_url('uploads/blog/thumbnail/placeholder.png');
                                    endif;
                                ?>
                                <div class="card-blog-body">
                                    <div class="blog-thumbnail" style="background-image: url('<?php echo $blog_thumbnail; ?>');"></div>
                                    <div class="blog-placeholder">
                                        <font face="Rasa" size="10"><h4 class="cart-blog-title"><?php echo $latest_blog['title']; ?></h4></font>

                                        <div class="d-flex">
                                            <div class="blog-info border-end">
                                                <?php echo get_past_time($latest_blog['added_date']); ?>
                                            </div>
                                            <div class="blog-info">
                                                <?php echo site_phrase('by'); ?> <!-- <?php echo site_url('home/instructor_page/'.$latest_blog['user_id']); ?> --><?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>
                                            </div>
                                            <div class="blog-info border-start">
                                                <?php echo $this->crud_model->get_blog_categories($latest_blog['blog_category_id'])->row('title'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>

                <div class="col-12">
                    <a class="float-end btn btn-outline-secondary px-3 fw-600" href="<?php echo site_url('blogs'); ?>"><?php echo site_phrase('view_all'); ?></a>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>


<div class="container-xl mt-5">
    <div class="row justify-content-evenly py-3 mb-4">
      <div class="col-md-6 <?php if (get_settings('allow_instructor') != 1) echo 'w-100'; ?> mt-3 mt-md-0">
        <div class="become-user-label bg-sub-primary mt-3" style="background-image: url('<?php echo site_url('assets/frontend/default/img/join-as-student.png'); ?>')">
          <h3 class="pb-4 fw-700 w-50"><?php echo site_phrase('join_now_to_start_learning'); ?></h3>
          <a class="btn btn-primary px-4 py-2" href="<?php echo site_url('sign_up'); ?>"><?php echo site_phrase('get_started'); ?></a>
        </div>
      </div>
      <?php if (get_settings('allow_instructor') == 1) : ?>
          <div class="col-md-6">
            <div class="become-user-label bg-sub-danger mt-3" style="background-image: url('<?php echo site_url('assets/frontend/default/img/join-as-instructor.png'); ?>')">
                <h3 class="pb-4 fw-700 w-50"><?php echo site_phrase('become_a_new_instructor'); ?></h3>
                <?php if($this->session->userdata('user_id')): ?>
                    <a class="btn btn-danger px-4 py-2" href="<?php echo site_url('user/become_an_instructor'); ?>"><?php echo site_phrase('join_now'); ?></a>
                <?php else: ?>
                    <a class="btn btn-danger px-4 py-2" href="<?php echo site_url('sign_up'); ?>"><?php echo site_phrase('join_now'); ?></a>
                <?php endif; ?>
            </div>
          </div>
      <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    function handleWishList(elem) {

        $.ajax({
            url: '<?php echo site_url('home/handleWishList'); ?>',
            type: 'POST',
            data: {
                course_id: elem.id
            },
            success: function(response) {
                if (!response) {
                    window.location.replace("<?php echo site_url('login'); ?>");
                } else {
                    if ($(elem).hasClass('active')) {
                        $(elem).removeClass('active')
                    } else {
                        $(elem).addClass('active')
                    }
                    $('#wishlist_items').html(response);
                }
            }
        });
    }
    
    function handleBundleWishList(elem) {

        $.ajax({
            url: '<?php echo site_url('home/handleBundleWishList'); ?>',
            type: 'POST',
            data: {
                bundle_id: elem.id
            },
            success: function(response) {
                if (!response) {
                    window.location.replace("<?php echo site_url('login'); ?>");
                } else {
                    if ($(elem).hasClass('active')) {
                        $(elem).removeClass('active')
                    } else {
                        $(elem).addClass('active')
                    }
                    $('#wishlist_items').html(response);
                }
            }
        });
    }

    function handleCartItems(elem) {
        url1 = '<?php echo site_url('home/handleCartItems'); ?>';
        url2 = '<?php echo site_url('home/refreshWishList'); ?>';
        $.ajax({
            url: url1,
            type: 'POST',
            data: {
                course_id: elem.id
            },
            success: function(response) {
                if (!response) {
                    window.location.replace("<?php echo site_url('login'); ?>");
                } else {
                    $('#cart_items').html(response);
                    if ($(elem).hasClass('addedToCart')) {
                        $('.big-cart-button-' + elem.id).removeClass('addedToCart')
                        $('.big-cart-button-' + elem.id).text("<?php echo site_phrase('add_to_cart'); ?>");
                    } else {
                        $('.big-cart-button-' + elem.id).addClass('addedToCart')
                        $('.big-cart-button-' + elem.id).text("<?php echo site_phrase('added_to_cart'); ?>");
                    }
                    $.ajax({
                        url: url2,
                        type: 'POST',
                        success: function(response) {
                            $('#wishlist_items').html(response);
                        }
                    });
                }
            }
        });
    }
    
    function handleBundleCartItems(elem) {
        url1 = '<?php echo site_url('home/handleCartBundleItems'); ?>';
        url2 = '<?php echo site_url('home/refreshWishList'); ?>';
        $.ajax({
            url: url1,
            type: 'POST',
            data: {
                bundle_id: elem.id
            },
            success: function(response) {
                if (!response) {
                    window.location.replace("<?php echo site_url('login'); ?>");
                } else {
                    $('#cart_items').html(response);
                    if ($(elem).hasClass('addedToCart')) {
                        $('.big-cart-button-' + elem.id).removeClass('addedToCart')
                        $('.big-cart-button-' + elem.id).text("<?php echo site_phrase('add_to_cart'); ?>");
                    } else {
                        $('.big-cart-button-' + elem.id).addClass('addedToCart')
                        $('.big-cart-button-' + elem.id).text("<?php echo site_phrase('added_to_cart'); ?>");
                    }
                    $.ajax({
                        url: url2,
                        type: 'POST',
                        success: function(response) {
                            $('#wishlist_items').html(response);
                        }
                    });
                }
            }
        });
    }

    function handleEnrolledButton() {
        $.ajax({
            url: '<?php echo site_url('home/isLoggedIn'); ?>',
            success: function(response) {
                if (!response) {
                    window.location.replace("<?php echo site_url('login'); ?>");
                }
            }
        });
    }

    $(document).ready(function() {
        if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            if ($(window).width() >= 840) {
                $('a.has-popover').webuiPopover({
                    trigger: 'hover',
                    animation: 'pop',
                    placement: 'horizontal',
                    delay: {
                        show: 500,
                        hide: null
                    },
                    width: 330
                });
            } else {
                $('a.has-popover').webuiPopover({
                    trigger: 'hover',
                    animation: 'pop',
                    placement: 'vertical',
                    delay: {
                        show: 100,
                        hide: null
                    },
                    width: 335
                });
            }
        }

        if ($(".course-carousel")[0]) {
            $(".course-carousel").slick({
                dots: false,
                infinite: false,
                speed: 300,
                slidesToShow: 5,
                slidesToScroll: 1,
                swipe: false,
                touchMove: false,
                responsive: [
                    { breakpoint: 840, settings: { slidesToShow: 3, slidesToScroll: 3, }, },
                    { breakpoint: 620, settings: { slidesToShow: 2, slidesToScroll: 2, }, },
                    { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1, }, },
                ],
            });
        }

        if ($(".top-istructor-slick")[0]) {
            $(".top-istructor-slick").slick({
                dots: false
            });
        }
    });
</script>