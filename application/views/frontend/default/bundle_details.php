<?php
if (file_exists('uploads/course_bundle/banner/' . $bundle_details['banner'])) :
	$bundle_banner = base_url('uploads/course_bundle/banner/' . $bundle_details['banner']);
else :
	$bundle_banner = base_url('uploads/course_bundle/banner/thumbnail.png');
endif;


//Bundle Rating
$ratings = $this->course_bundle_model->get_bundle_wise_ratings($bundle_details['id']);
$bundle_total_rating = $this->course_bundle_model->sum_of_bundle_rating($bundle_details['id']);
if ($ratings->num_rows() > 0) {
	$bundle_average_ceil_rating = ceil($bundle_total_rating / $ratings->num_rows());
} else {
	$bundle_average_ceil_rating = 0;
}
$bundle_original_price = $this->course_bundle_model->get_original_price_of_bundle($bundle_details['id']);
?>

<section class="course-header-area">
  <div class="container">
    <div class="row align-items-end">
      <div class="col-lg-8">
        <div class="course-header-wrap">
          <h1 class="title"><?php echo $bundle_details['title']; ?></h1>
          <p class="subtitle"><?php echo $bundle_details['short_description']; ?></p>
          <div class="rating-row">
            <?php
            for ($i = 1; $i < 6; $i++) : ?>
              <?php if ($i <= $bundle_average_ceil_rating) : ?>
                <i class="fas fa-star filled" style="color: #f5c85b;"></i>
              <?php else : ?>
                <i class="fas fa-star"></i>
              <?php endif; ?>
            <?php endfor; ?>
            <span class="d-inline-block average-rating"><?php echo $bundle_average_ceil_rating; ?></span><span>(<?php echo $ratings->num_rows() . ' ' . site_phrase('ratings'); ?>)</span>
            <span class="enrolled-num">
              <?php
              $number_of_enrolments = $this->course_bundle_model->get_number_of_enrolled_student($bundle_details['id']);
				echo $number_of_enrolments . ' ' . site_phrase('students_enrolled');
              ?>
            </span>
          </div>
          <div class="created-row">
            <span class="created-by">
              <?php echo site_phrase('created_by'); ?>
              <?php if ($bundle_details['multi_instructor']) : ?>
                <?php $instructors = $this->user_model->get_multi_instructor_details_with_csv($bundle_details['user_id']); ?>
                <?php foreach ($instructors as $key => $instructor) : ?>
                  <a class="text-14px fw-600 text-decoration-none" href="<?php echo site_url('home/instructor_page/' . $instructor['id']); ?>"><?php echo $instructor['first_name'] . ' ' . $instructor['last_name']; ?></a>
                  <?php echo $key + 1 == count($instructors) ? '' : ', '; ?>
                <?php endforeach; ?>
              <?php else : ?>
                <a class="text-14px fw-600 text-decoration-none" href="<?php echo site_url('home/instructor_page/' . $bundle_details['user_id']); ?>"><?php echo $instructor_details['first_name'] . ' ' . $instructor_details['last_name']; ?></a>
              <?php endif; ?>
            </span>
            <br>
            <?php if ($bundle_details['last_modified'] > 0) : ?>
              <span class="last-updated-date d-inline-block mt-2"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $bundle_details['last_modified']); ?></span>
            <?php else : ?>
              <span class="last-updated-date d-inline-block mt-3"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $bundle_details['date_added']); ?></span>
            <?php endif; ?>
            
          </div>
        </div>
      </div>
      <div class="col-lg-4">

      </div>
    </div>
  </div>
</section>

<section class="course-content-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 order-last order-lg-first radius-10 mt-4 bg-white p-30-40">

        <h3 class="my-3 pt-3"><?= site_phrase('included_subjects'); ?></h3>
				<div class="row">
					<?php foreach (json_decode($bundle_details['course_ids']) as $key => $course_id) :
						$this->db->where('id', $course_id);
						$this->db->where('status', 'active');
						$course = $this->db->get('course')->row_array();
						if ($course == null) continue;

						//course ratings
						$total_rating =  $this->crud_model->get_ratings('course', $course['id'], true)->row()->rating;
						$number_of_ratings = $this->crud_model->get_ratings('course', $course['id'])->num_rows();
						if ($number_of_ratings > 0) {
							$average_ceil_rating = ceil($total_rating / $number_of_ratings);
						} else {
							$average_ceil_rating = 0;
						}
					?>

						<div class="col-md-6 col-lg-4 col-xl-3 p-0">
							<div class="course-box-wrap">
								<a href="<?php echo site_url('home/course/' . rawurlencode(slugify($course['title'])) . '/' . $course['id']); ?>">
									<div class="course-box course-bundle-box">
										<div class="course-image">
											<img src="<?php echo $this->crud_model->get_course_thumbnail_url($course['id']); ?>" alt="" class="img-fluid">
										</div>
										<div class="course-details">
											<div class="title text-muted m-0"><?php echo $course['title']; ?></div>
											<small class="text-dark text-muted">
												<?php echo $instructor_details['first_name'] . ' ' . $instructor_details['last_name']; ?>
											</small>

											<!--Price-->
											<?php if ($course['is_free_course'] == 1) : ?>
												<span class="price d-block float-right"><?php echo site_phrase('free'); ?></span>
											<?php else : ?>
												<?php if ($course['discount_flag'] == 1) : ?>
													<span class="price d-block float-right"><small><?php echo currency($course['price']); ?></small><?php echo currency($course['discounted_price']); ?></span>
												<?php else : ?>
													<span class="price d-block float-right"><?php echo currency($course['price']); ?></span>
												<?php endif; ?>
											<?php endif; ?>
											<!--End Price-->

											<div class="rating">
												<?php for ($i = 1; $i <= 5; $i++) : ?>
													<?php if ($i <= $average_ceil_rating) : ?>
														<i class="fas fa-star filled text-warning"></i>
													<?php else : ?>
														<i class="fas fa-star"></i>
													<?php endif; ?>
												<?php endfor; ?>
											</div>
											<div class="course-meta course-more-details pb-3">
												<small class="text-muted"><i class="fas fa-play-circle"></i>
													<?php
													$number_of_lessons = $this->crud_model->get_lessons('course', $course['id'])->num_rows();
													echo $number_of_lessons . ' ' . site_phrase('lessons');
													?>
												</small>
												<br>
												<small class="text-muted"><i class="far fa-clock"></i>
													<?php echo $this->crud_model->get_total_duration_of_lesson_by_course_id($course['id']); ?>
												</small>
											</div>
										</div>
									</div>
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				
		<div class="description-box view-more-parent w-100">
					<div class="view-more" onclick="viewMore(this,'hide')">+ <?php echo site_phrase('view_more'); ?></div>
					<h3><?php echo site_phrase('description'); ?></h3>
					<div class="description-content-wrap">
						<div class="description-content">
							<?php echo $bundle_details['bundle_details']; ?>
						</div>
					</div>
				</div>

        <div class="about-instructor-box">
          <div class="about-instructor-title">
            <?php echo site_phrase('about_instructor'); ?>
          </div>
          <?php if ($course_details['multi_instructor']) : ?>
            <?php $instructors = $this->user_model->get_multi_instructor_details_with_csv($course_details['user_id']); ?>
            <?php foreach ($instructors as $key => $instructor) : ?>
              <?php if($key > 0)echo "<hr>"; ?>

              <div class="row justify-content-center mb-3">
                <div class="col-md-4 top-instructor-img w-sm-100">
                  <a href="<?php echo site_url('home/instructor_page/'.$instructor['id']); ?>">
                    <img src="<?php echo $this->user_model->get_user_image_url($instructor['id']); ?>" width="100%">
                  </a>
                </div>
                <div class="col-md-8 py-0 px-3 text-center text-md-start">
                    <h4 class="mb-1 fw-600 "><a class="text-decoration-none" href="<?php echo site_url('home/instructor_page/'.$instructor['id']); ?>"><?php echo $instructor['first_name'].' '.$instructor['last_name']; ?></a></h4>
                    <p class="fw-500 text-14px w-100 "><?php echo $instructor['title']; ?></p>
                    <div class="rating ">
                      <div class="d-inline-block mb-2">
                        <span class="text-dark fw-800 text-muted ms-1 text-13px"><?php echo $this->crud_model->get_instructor_wise_course_ratings($instructor['id'], 'course')->num_rows().' '.site_phrase('reviews'); ?></span>
                        |
                        <span class="text-dark fw-800 text-13px text-muted mx-1">
                            <?php $course_ids = $this->crud_model->get_instructor_wise_courses($instructor['id'], 'simple_array');
                          $this->db->select('user_id');
                          $this->db->distinct();
                          $this->db->where_in('course_id', $course_ids);
                          echo $this->db->get('enrol')->num_rows().' '.site_phrase('students'); ?>
                        </span>
                        |
                        <span class="text-dark fw-800 text-14px text-muted">
                            <?php echo $this->crud_model->get_instructor_wise_courses($instructor['id'])->num_rows().' '.site_phrase('courses'); ?>
                        </span>
                      </div>
                    </div>
                    <?php $skills = explode(',', $instructor['skills']); ?>
                    <?php foreach($skills as $skill): ?>
                      <span class="badge badge-sub-warning text-12px my-1 py-2"><?php echo $skill; ?></span>
                    <?php endforeach; ?>

                    
                    <div class="description ">
                      <?php echo ellipsis(strip_tags($instructor['biography']), 180); ?>
                    </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <div class="row justify-content-center">
                <div class="col-md-4 top-instructor-img w-sm-100">
                  <a href="<?php echo site_url('home/instructor_page/'.$instructor_details['id']); ?>">
                    <img class="radius-10" src="<?php echo $this->user_model->get_user_image_url($instructor_details['id']); ?>" width="100%">
                  </a>
                </div>
                <div class="col-md-8 py-0 px-3 text-center text-md-start">
                    <h4 class="mb-1 fw-600 v"><a class="text-decoration-none" href="<?php echo site_url('home/instructor_page/'.$instructor_details['id']); ?>"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></a></h4>
                    <p class="fw-500 text-14px w-100"><?php echo $instructor_details['title']; ?></p>
                    <div class="rating">
                      <div class="d-inline-block mb-2">
                        <span class="text-dark fw-800 text-muted ms-1 text-13px"><?php echo $this->crud_model->get_instructor_wise_course_ratings($instructor_details['id'], 'course')->num_rows().' '.site_phrase('reviews'); ?></span>
                        |
                        <span class="text-dark fw-800 text-13px text-muted mx-1">
                            <?php $course_ids = $this->crud_model->get_instructor_wise_courses($instructor_details['id'], 'simple_array');
                          $this->db->select('user_id');
                          $this->db->distinct();
                          $this->db->where_in('course_id', $course_ids);
                          echo $this->db->get('enrol')->num_rows().' '.site_phrase('students'); ?>
                        </span>
                        |
                        <span class="text-dark fw-800 text-14px text-muted">
                            <?php echo $this->crud_model->get_instructor_wise_courses($instructor_details['id'])->num_rows().' '.site_phrase('courses'); ?>
                        </span>
                      </div>
                    </div>
                    <?php $skills = explode(',', $instructor_details['skills']); ?>
                    <?php foreach($skills as $skill): ?>
                      <span class="badge badge-sub-warning text-12px my-1 py-2"><?php echo $skill; ?></span>
                    <?php endforeach; ?>

                    
                    <div class="description">
                      <?php echo ellipsis(strip_tags($instructor_details['biography']), 180); ?>
                    </div>
                </div>
            </div>
          <?php endif; ?>
        </div>

        <div class="student-feedback-box mt-5 pb-3">
          <div class="student-feedback-title">
            <?php echo site_phrase('student_feedback'); ?>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="average-rating ms-auto me-auto float-md-start mb-sm-4">
                <div class="num">
                  <?php echo $bundle_average_ceil_rating;?>
                </div>
                <div class="rating">
                  <?php for ($i = 1; $i < 6; $i++) : ?>
                    <?php if ($i <= $bundle_average_ceil_rating) : ?>
                      <i class="fas fa-star filled" style="color: #f5c85b;"></i>
                    <?php else : ?>
                      <i class="fas fa-star" style="color: #abb0bb;"></i>
                    <?php endif; ?>
                  <?php endfor; ?>
                </div>
                <div class="title text-15px fw-700"><?php echo $ratings->num_rows(); ?> <?php echo site_phrase('reviews'); ?></div>
              </div>
              <div class="individual-rating">
                <ul>
                  <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <li>
                      <div>
                        <span class="rating">
                          <?php for ($j = 1; $j <= (5 - $i); $j++) : ?>
                            <i class="fas fa-star"></i>
                          <?php endfor; ?>
                          <?php for ($j = 1; $j <= $i; $j++) : ?>
                            <i class="fas fa-star filled"></i>
                          <?php endfor; ?>

                        </span>
                      </div>
                      <div class="progress ms-2 mt-1">
                        <div class="progress-bar" style="width: <?php echo $this->course_bundle_model->get_bundle_percentage_of_specific_rating($bundle_details['id'], $i); ?>%"></div>
                      </div>
                      <span class="d-inline-block ps-2 text-15px fw-500">
                        (<?php echo $this->course_bundle_model->get_bundle_specific_rating($bundle_details['id'], $i); ?>)
                      </span>
                    </li>
                  <?php endfor; ?>
                </ul>
              </div>
            </div>
          </div>

          <div class="reviews mt-5">
            <h3><?php echo site_phrase('reviews'); ?></h3>
            <ul>
              <?php
              foreach ($ratings->result_array() as $rating) :
              ?>
                <li>
                  <div class="row">
                    <div class="col-auto">
                      <div class="reviewer-details clearfix">
                        <div class="reviewer-img">
                          <img src="<?php echo $this->user_model->get_user_image_url($rating['user_id']); ?>" alt="">
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <div class="review-time">
                        <div class="reviewer-name fw-500">
                          <?php
                          $user_details = $this->user_model->get_user($rating['user_id'])->row_array();
                          echo $user_details['first_name'] . ' ' . $user_details['last_name'];
                          ?>
                        </div>
                        <!-- <div class="time text-11px text-muted">
                          <?php echo date('d/m/Y', $rating['date_added']); ?>
                        </div> -->
                      </div>
                      <div class="review-details">
                        <div class="rating">
                          <?php
                          for ($i = 1; $i < 6; $i++) : ?>
                            <?php if ($i <= $rating['rating']) : ?>
                              <i class="fas fa-star filled" style="color: #f5c85b;"></i>
                            <?php else : ?>
                              <i class="fas fa-star" style="color: #abb0bb;"></i>
                            <?php endif; ?>
                          <?php endfor; ?>
                        </div>
                        <div class="review-text text-13px">
                          <?php echo $rating['review']; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-lg-4 order-first order-lg-last">
        <div class="course-sidebar natural">
          <?php if ($course_details['video_url'] != "") : ?>
            <div class="preview-video-box">
              <a data-bs-toggle="modal" data-bs-target="#CoursePreviewModal">
                <img src="<?php echo $this->crud_model->get_course_thumbnail_url($course_details['id']); ?>" alt="" class="w-100">
                <span class="preview-text"><?php echo site_phrase('preview_this_course'); ?></span>
                <span class="play-btn"></span>
              </a>
            </div>
          <?php endif; ?>
          <div class="course-sidebar-text-box">
            <div class="subscription text-center">
                <a class="badge-sub-warning text-decoration-none fw-600 hover-shadow-1 d-inline-block" style="margin-bottom: 10px;"></i><span style="color: #cc7a1c;font-weight: bold">Subscription </span> - <?php echo $bundle_details['subscription_limit']; ?> Days</a>
            </div>
            <div class="price text-center">
              <?php if ($bundle_details['price'] == 0) : ?>
                <span class="original-price"><?php echo currency($bundle_original_price) ?></span>
                <span class="current-price"><span class="current-price">Free</span></span>
              <?php else : ?>
                  <span class="original-price"><?php echo currency($bundle_original_price) ?></span>
                  <span class="current-price"><span class="current-price"><?php echo currency($bundle_details['price']); ?></span></span>
              <?php endif; ?>
            </div>

            <?php if (get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == "valid") : ?>
                <div class="buy-btns">
                    <a class="btn btn-buy-now active" id="<?php echo $bundle_details['id']; ?>" href="<?php echo site_url('home/my_bundles'); ?>"><?php echo site_phrase('already_purchased'); ?></a>
                </div>
            <?php elseif (get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == "expire") : ?>
                <!--<div class="btn btn-buy-now">-->
                <!--    <a href="<?php echo site_url('course_bundles/buy/' . $bundle_details['id']); ?>"><?php echo site_phrase('renew'); ?></a>-->
                <!--</div>-->
                <div class="buy-btns">
                    <button class="btn btn-buy" type="button" id="<?php echo $bundle_details['id']; ?>" onclick="location.href = '<?php echo site_url('course_bundles/buy/' . $bundle_details['id']);?>';"><?php echo site_phrase('renew'); ?></button>
                </div>
            <?php else: ?>
                <div class="buy-btns">
                   <button class="btn btn-buy" type="button" id="course_<?php echo $bundle_details['id']; ?>" onclick="handleBuyNow(this)"><?php echo site_phrase('buy_now'); ?></button>
                   <?php if (in_array($bundle_details['id'], $this->crud_model->getCartBundleItems())) : ?>
                    <button class="btn btn-buy-now active" type="button" id="<?php echo $bundle_details['id']; ?>" onclick="handleCartBundleItems(this)"><?php echo site_phrase('added_to_cart'); ?></button>
                  <?php else : ?>
                    <button class="btn btn-buy-now" type="button" id="<?php echo $bundle_details['id']; ?>" onclick="handleCartBundleItems(this)"><?php echo site_phrase('add_to_cart'); ?></button>
                  <?php endif; ?>
                </div>
            <?php endif; ?>


            <div class="includes">
              <div class="title"><b><?php echo site_phrase('includes'); ?>:</b></div>
              <ul>
                <?php if ($course_details['course_type'] == 'general') : ?>
                  <li><i class="far fa-file-video"></i>
                    <?php
                    echo $this->crud_model->get_total_duration_of_lesson_by_course_id($course_details['id']) . ' ' . site_phrase('on_demand_videos');
                    ?>
                  </li>
                  <li><i class="far fa-file"></i><?php echo $this->crud_model->get_lessons('course', $course_details['id'])->num_rows() . ' ' . site_phrase('lessons'); ?></li>
                  <li><i class="fas fa-mobile-alt"></i><?php echo site_phrase('access_on_mobile_and_tv'); ?></li>
                <?php elseif ($course_details['course_type'] == 'scorm') : ?>
                  <li><i class="far fa-file-video"></i><?php echo site_phrase('scorm_course'); ?></li>
                  <li><i class="fas fa-mobile-alt"></i><?php echo site_phrase('access_on_laptop_and_tv'); ?></li>
                <?php endif; ?>
                <li><i class="far fa-compass"></i><?php echo site_phrase('full_lifetime_access'); ?></li>
                <li class="text-center pt-3">
                  <a class="badge-sub-warning text-decoration-none fw-600 hover-shadow-1 d-inline-block" href="<?php echo site_url('home/compare?course-1=' . rawurlencode(slugify($course_details['title'])) . '&&course-id-1=' . $course_details['id']); ?>"><i class="fas fa-balance-scale"></i> <?php echo site_phrase('compare_this_course_with_other'); ?></a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal -->
<?php if ($course_details['video_url'] != "") :
  $provider = "";
  $video_details = array();
  if ($course_details['course_overview_provider'] == "html5") {
    $provider = 'html5';
  } else {
    $video_details = $this->video_model->getVideoDetails($course_details['video_url']);
    $provider = $video_details['provider'];
  }
?>
  <div class="modal fade" id="CoursePreviewModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content course-preview-modal">
        <div class="modal-header">
          <h5 class="modal-title"><span><?php echo site_phrase('course_preview') ?>:</span><?php echo $course_details['title']; ?></h5>
          <button type="button" class="close" data-bs-dismiss="modal" onclick="pausePreview()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="course-preview-video-wrap">
            <div class="embed-responsive embed-responsive-16by9">
              <?php if (strtolower(strtolower($provider)) == 'youtube') : ?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">

                <div class="plyr__video-embed" id="player">
                  <iframe height="500" src="<?php echo $course_details['video_url']; ?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>

                <script src="<?php echo base_url(); ?>assets/global/plyr/plyr.js"></script>
                <script>
                  const player = new Plyr('#player');
                </script>
                <!------------- PLYR.IO ------------>
              <?php elseif (strtolower($provider) == 'vimeo') : ?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">
                <div class="plyr__video-embed" id="player">
                  <iframe height="500" src="https://player.vimeo.com/video/<?php echo $video_details['video_id']; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>

                <script src="<?php echo base_url(); ?>assets/global/plyr/plyr.js"></script>
                <script>
                  const player = new Plyr('#player');
                </script>
                <!------------- PLYR.IO ------------>
              <?php else : ?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">
                <video poster="<?php echo $this->crud_model->get_course_thumbnail_url($course_details['id']); ?>" id="player" playsinline controls>
                  <?php if (get_video_extension($course_details['video_url']) == 'mp4') : ?>
                    <source src="<?php echo $course_details['video_url']; ?>" type="video/mp4">
                  <?php elseif (get_video_extension($course_details['video_url']) == 'webm') : ?>
                    <source src="<?php echo $course_details['video_url']; ?>" type="video/webm">
                  <?php else : ?>
                    <h4><?php site_phrase('video_url_is_not_supported'); ?></h4>
                  <?php endif; ?>
                </video>

                <style media="screen">
                  .plyr__video-wrapper {
                    height: 450px;
                  }
                </style>

                <script src="<?php echo base_url(); ?>assets/global/plyr/plyr.js"></script>
                <script>
                  const player = new Plyr('#player');
                </script>
                <!------------- PLYR.IO ------------>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
<!-- Modal -->

<style media="screen">
  .embed-responsive-16by9::before {
    padding-top: 0px;
  }
</style>
<script type="text/javascript">
  function handleCartBundleItems(elem) {
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
            if ($(elem).hasClass('active')) {
              $(elem).removeClass('active')
              $(elem).text("<?php echo site_phrase('add_to_cart'); ?>");
            } else {
              $(elem).addClass('active');
              $(elem).addClass('active');
              $(elem).text("<?php echo site_phrase('added_to_cart'); ?>");
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

  function handleBuyNow(elem) {

    url1 = '<?php echo site_url('home/handleCartBundleItemForBuyNowButton'); ?>';
    url2 = '<?php echo site_url('home/refreshWishList'); ?>';
    urlToRedirect = '<?php echo site_url('home/shopping_cart'); ?>';
    var explodedArray = elem.id.split("_");
    var course_id = explodedArray[1];

    $.ajax({
      url: url1,
      type: 'POST',
      data: {
        bundle_id: course_id
      },
      success: function(response) {
          if (!response) {
          window.location.replace("<?php echo site_url('login'); ?>");
        } else {
            $('#cart_items').html(response);
            $.ajax({
              url: url2,
              type: 'POST',
              success: function(response) {
                $('#wishlist_items').html(response);
                toastr.success('<?php echo site_phrase('please_wait') . '....'; ?>');
                setTimeout(
                  function() {
                    window.location.replace(urlToRedirect);
                  }, 1000);
              }
            });
        }
      }
    });
  }

  function handleEnrolledButton() {
    $.ajax({
      url: '<?php echo site_url('home/isLoggedIn?url_history='.base64_encode(current_url())); ?>',
      success: function(response) {
        if (!response) {
          window.location.replace("<?php echo site_url('login'); ?>");
        }
      }
    });
  }

  function handleAddToWishlist(elem) {
    $.ajax({
      url: '<?php echo site_url('home/isLoggedIn?url_history='.base64_encode(current_url())); ?>',
      success: function(response) {
        if (!response) {
          window.location.replace("<?php echo site_url('login'); ?>");
        }else{
          $.ajax({
            url: '<?php echo site_url('home/handleWishList'); ?>',
            type: 'POST',
            data: {
              course_id: elem.id
            },
            success: function(response) {
              if ($(elem).hasClass('active')) {
                $(elem).removeClass('active');
                $(elem).text("<?php echo site_phrase('add_to_wishlist'); ?>");
              } else {
                $(elem).addClass('active');
                $(elem).text("<?php echo site_phrase('added_to_wishlist'); ?>");
              }
              $('#wishlist_items').html(response);
            }
          });
        }
      }
    });
  }

  function pausePreview() {
    player.pause();
  }

  $('.course-compare').click(function(e) {
    e.preventDefault()
    var redirect_to = $(this).attr('redirect_to');
    window.location.replace(redirect_to);
  });

  function go_course_playing_page(course_id, lesson_id){
    var course_playing_url = "<?php echo site_url('home/lesson/'.slugify($course_details['title'])); ?>/"+course_id+'/'+lesson_id;

    $.ajax({
      url: '<?php echo site_url('home/go_course_playing_page/'); ?>'+course_id,
      type: 'POST',
      success: function(response) {
        if(response == 1){
          window.location.replace(course_playing_url);
        }
      }
    });
  }
</script>