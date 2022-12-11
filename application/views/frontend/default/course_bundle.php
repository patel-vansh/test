<!--<section class="category-header-area">-->
<!--    <div class="container-lg">-->
<!--        <div class="row">-->
<!--            <div class="col">-->
<!--                <nav>-->
<!--                    <ol class="breadcrumb">-->
<!--                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>-->
<!--                        <li class="breadcrumb-item active"><?php echo site_phrase('course_bundles'); ?></li>-->
<!--                    </ol>-->
<!--                </nav>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<section class="category-header-area" style="background-image: url('<?php echo base_url('uploads/system/course_page_banner.png'); ?>');">
    <div class="image-placeholder-1"></div>
    <div class="container-lg breadcrumb-container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item display-6 fw-bold">
                <a href="<?php echo site_url('home'); ?>">
                    <?php echo site_phrase('home'); ?>
                </a>
            </li>
            <li class="breadcrumb-item active text-light display-6 fw-bold">
                <?php echo site_phrase('course_bundles'); ?>
            </li>
          </ol>
        </nav>
    </div>
</section>

<section class="category-course-list-area">
    <div class="container">
        <div class="row mx-0 category-filter-box">
            <div class="col-md-6">
                <?php if(isset($search_string)): ?>
                    <span class="text-14px fw-700 text-muted"><?php echo site_phrase('found_number_of_bundles'); ?> : <?php echo count($course_bundles->result_array()); ?></span>
                <?php else: ?>
                    <span class="text-14px fw-700 text-muted"><?php echo site_phrase('showing_on_this_page'); ?> : <?php echo count($course_bundles->result_array()); ?></span>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <form class="" action="<?= site_url('course_bundles/search/query'); ?>" method="get">
                    <div class="input-group bundle-search">
                        <input type="text" name="string" value="<?php if(isset($search_string)) echo $search_string; ?>" class="form-control" placeholder="<?= site_phrase('search_for_bundle'); ?>">
                        <div class="input-group-append">
                            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</section>
    <section class="course-carousel-area"> 
        <div class="container-lg">

        <div class="row justify-content-center">
                <div class="col">
      
                    <!-- Animated page loader -->
                    <div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div>

                    <div class="course-carousel shown-after-loading" style="display: none;">
                        <?php foreach($course_bundles->result_array() as $course_bundle):
                            $instructor_details = $this->user_model->get_all_user($course_bundle['user_id'])->row_array();
                            $course_ids = json_decode($course_bundle['course_ids']);
                            sort($course_ids);
                        ?>
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
<script type="text/javascript">

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
    });
</script>