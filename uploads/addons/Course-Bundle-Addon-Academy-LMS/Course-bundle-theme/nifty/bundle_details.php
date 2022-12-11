<?php
	if(file_exists('uploads/course_bundle/banner/'.$bundle_details['banner'])):
		$bundle_banner = base_url('uploads/course_bundle/banner/'.$bundle_details['banner']);
	else:
		$bundle_banner = base_url('uploads/course_bundle/banner/thumbnail.png');
	endif;

	//Bundle Rating
	$ratings = $this->course_bundle_model->get_bundle_wise_ratings($bundle_details['id']);
	$bundle_total_rating = $this->course_bundle_model->sum_of_bundle_rating($bundle_details['id']);
	if ($ratings->num_rows() > 0) {
		$bundle_average_ceil_rating = ceil($bundle_total_rating / $ratings->num_rows());
	}else {
		$bundle_average_ceil_rating = 0;
	}
?>


<div class="position-relative">
  <!-- Hero Section -->
  <div class="gradient-y-overlay-lg-white bg-img-hero py-5" style="background: url(<?= $bundle_banner; ?>) right bottom no-repeat; background-size: cover;">
    <div class="container">
      <div class="row">
        <div class="col-md-10">
          <h1 class=""><?= $bundle_details['title']; ?></h1>

          <div class="d-flex align-items-center flex-wrap w-100">
            <div class="d-flex align-items-center flex-wrap w-100">
              <!-- Authors -->
              <div class="d-flex align-items-center">
                <div class="avatar-group">
                  <span class="avatar avatar-xs avatar-circle">
                    <img class="avatar-img" src="<?= $this->user_model->get_user_image_url($bundle_details['user_id']); ?>" alt="Image Description">
                  </span>
                </div>
                <span class="pl-2"><?= site_phrase('created_by'); ?> <a class="link-underline" href="<?php echo site_url('home/instructor_page/'.$instructor_details['id']) ?>"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></a></span>, 
                <span class="text-13 pl-1 pt-1"> <?= date('D, d-M-Y', $bundle_details['date_added']); ?> </span>
              </div>
              <!-- End Authors -->
              <hr class="w-100 mt-5"> 
              <i class="fa fa-comments"></i>
              <ul class="list-inline mt-n1 mb-0 mr-2 ml-2">
                <?php for($i = 1; $i <= 5; $i++):?>
                    <?php if ($i <= $bundle_average_ceil_rating): ?>
                      <li class="list-inline-item mx-0">
                        <img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star.svg'); ?>" alt="Review rating" width="14">
                      </li>
                    <?php else: ?>
                        <li class="list-inline-item mx-0">
                          <img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star-muted.svg'); ?>" alt="Review rating" width="14">
                        </li>
                    <?php endif; ?>
                  <?php endfor; ?>
              </ul>
              <span class="d-inline-block">
                <span class="text-dark font-weight-bold mr-1"><?php echo $bundle_average_ceil_rating; ?></span>
                <span class="text-muted">(<?= $ratings->num_rows().' '.site_phrase('reviews'); ?>), </span>
              </span>
              <span class="d-inline-block">
                <span class="text-dark font-weight-bold ml-2"> <?php echo $this->course_bundle_model->get_number_of_enrolled_student($bundle_details['id']); ?></span>
                <span class="text-muted"><?= site_phrase('students_enrolled'); ?></span>
              </span>
              <span class="d-inline-block w-100 mt-2">
              	<div class="p-3 text-center float-right bg-e1eaff">
	              	<p class="text-15"><?= site_phrase('subscription'); ?> <?= $bundle_details['subscription_limit']; ?> <?= site_phrase('days'); ?></p>
					<?php if(get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == 'invalid'): ?>
						<a href="<?= site_url('course_bundles/buy/'.$bundle_details['id']); ?>" class="btn btn-success"><?= currency($bundle_details['price']); ?> | <?= site_phrase('buy'); ?></a>
					<?php elseif(get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == 'expire'): ?>
						<a href="<?= site_url('course_bundles/buy/'.$bundle_details['id']); ?>" class="btn btn-danger"><?= currency($bundle_details['price']); ?> | <?= site_phrase('renew'); ?></a>
					<?php else: ?>
						<a href="<?= site_url('home/my_bundles'); ?>" class="btn btn-info"><?= site_phrase('purchased'); ?></a>
					<?php endif; ?>
				</div>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <div class="gradient-y-overlay-lg-white bg-img-hero mt-5" style="background-image: url(<?= base_url($bundle_banner); ?>);">
    <div class="container">
      <div class="row">
        <div class="col-md-10">
          <h3 class="text-lh-sm"><?= site_phrase('included_courses'); ?></h3>
          <div class="row">
				<?php foreach(json_decode($bundle_details['course_ids']) as $key => $course_id):
	            $this->db->where('id', $course_id);
	            $this->db->where('status', 'active');
	            $course = $this->db->get('course')->row_array();
	            if($course == null) continue;

	            //course ratings
	            $total_rating =  $this->crud_model->get_ratings('course', $course['id'], true)->row()->rating;
				$number_of_ratings = $this->crud_model->get_ratings('course', $course['id'])->num_rows();
				if ($number_of_ratings > 0) {
					$average_ceil_rating = ceil($total_rating / $number_of_ratings);
				}else {
					$average_ceil_rating = 0;
				}
	            ?>

				<div class="col-md-6 col-lg-4 col-xl-3 p-0">
					<div class="course-box-wrap m-1 pb-3 pt-2">
						<div class="course-box course-bundle-box border-left border-right border-bottom">
							<a href="<?php echo site_url('home/course/'.rawurlencode(slugify($course['title'])).'/'.$course['id']); ?>">
								<div class="course-image">
									<img class="w-100" src="<?php echo $this->crud_model->get_course_thumbnail_url($course['id']); ?>" alt="" class="img-fluid">
								</div>
							</a>
							<div class="course-details p-3">
								<a href="<?php echo site_url('home/course/'.rawurlencode(slugify($course['title'])).'/'.$course['id']); ?>">
									<div class="title text-muted m-0 text-15"><?php echo $course['title']; ?></div>
								</a>
								<small class="text-dark text-muted">
									<?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?>
								</small>

								<!--Price-->
								<?php if ($course['is_free_course'] == 1): ?>
									<span class="price d-block float-right text-muted"><?php echo site_phrase('free'); ?></span>
								<?php else: ?>
									<?php if ($course['discount_flag'] == 1): ?>
										<span class="price d-block float-right text-muted">
											<strike class="text-13">
												<?php echo currency($course['price']); ?>
											</strike>
											<?php echo currency($course['discounted_price']); ?></span>
									<?php else: ?>
										<span class="price d-block float-right text-muted"><?php echo currency($course['price']); ?></span>
									<?php endif; ?>
								<?php endif; ?>
								<!--End Price-->

								<div class="rating">
									<?php for($i = 1; $i <= 5; $i++):?>
										<?php if ($i <= $average_ceil_rating): ?>
											<li class="list-inline-item mx-0">
					                        	<img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star.svg'); ?>" alt="Review rating" width="14">
					                    	</li>
										<?php else: ?>
											<li class="list-inline-item mx-0">
                        						<img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star-muted.svg'); ?>" alt="Review rating" width="14">
                        					</li>
										<?php endif; ?>
									<?php endfor; ?>
								</div>
								<div class="course-meta course-more-details pb-3">
				                    <small class="text-muted"><i class="fas fa-play-circle"></i>
				                        <?php
				                            $number_of_lessons = $this->crud_model->get_lessons('course', $course['id'])->num_rows();
				                            echo $number_of_lessons.' '.site_phrase('lessons');
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


				<!-- Info -->
				<div class="mt-7 p-3 w-100">
					<h3 class="mb-4"><?= site_phrase('description'); ?></h3>
					<?php if (strlen($bundle_details['bundle_details']) > 500) { ?>
					<div class="limited-description"><?php echo substr($bundle_details['bundle_details'], 0, 500); ?></div>
					<div class="collapse full-description" id="collapseDescriptionSection">
						<?php echo $bundle_details['bundle_details']; ?>
					</div>
					<!-- Link -->
					<a class="link link-collapse small font-size-1 font-weight-bold pt-1" data-toggle="collapse" href="#collapseDescriptionSection" role="button" aria-expanded="false" aria-controls="collapseDescriptionSection">
						<span class="link-collapse-default" onclick="limitedDescription('read_more')"><?= site_phrase('read_more'); ?> <span class="ml-1">+</span></span>
						<span class="link-collapse-active" onclick="limitedDescription('read_less')"><?= site_phrase('read_less'); ?> <span class="ml-1">-</span></span>
					</a>
					<!-- End Link -->
					<?php }else{ ?>
						<?php echo $bundle_details['bundle_details']; ?>
					<?php } ?>
				</div>
				<!-- End Info -->


				<!-- Ratings & Reviews -->
          		<div class="border-top my-7 p-3 w-100">
					<!-- Overall Ratings -->
		            <div class="my-7">
		              <h3 class="mb-4"><?= site_phrase('student_feedback'); ?></h3>

		              <div class="row align-items-center">
		                <div class="col-lg-4 mb-4 mb-lg-0">
		                  <!-- Overall Review Rating -->
		                  <div class="card bg-primary text-white text-center py-4 px-3">
		                    <span class="display-4"><?= $bundle_average_ceil_rating.' '.site_phrase('rating'); ?></span>
		                    <ul class="list-inline mb-2">
		                      <?php for($i = 1; $i <= 5; $i++):?>
		                        <?php if ($i <= $bundle_average_ceil_rating): ?>
		                          <li class="list-inline-item mx-0">
		                            <img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star.svg'); ?>" alt="Review rating" width="14">
		                          </li>
		                        <?php else: ?>
		                            <li class="list-inline-item mx-0">
		                              <img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star-muted.svg'); ?>" alt="Review rating" width="14">
		                            </li>
		                        <?php endif; ?>
		                      <?php endfor; ?>
		                    </ul>
		                    <span><?= site_phrase('average_rating'); ?></span>
		                  </div>
		                  <!-- End Overall Review Rating -->
		                </div>

		                <div class="col-lg-8">
		                  <ul class="list-unstyled list-sm-article mb-0">
		                    <?php for($i = 5; $i >= 1; $i--): ?>
		                      <?php $percentage_of_rating = $this->course_bundle_model->get_bundle_percentage_of_specific_rating($bundle_details['id'], $i); ?>
		                      <li>
		                        <!-- Review Rating -->
		                        <a class="d-flex align-items-center font-size-1" href="javascript:;">
		                          <div class="progress w-100">
		                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage_of_rating; ?>%;" aria-valuenow="<?= $percentage_of_rating; ?>" aria-valuemin="0" aria-valuemax="100"></div>
		                          </div>
		                          <div class="d-flex align-items-center min-w-21rem ml-3">
		                            <ul class="list-inline mr-1 mb-2">
		                              <?php for($j = 5; $j >= 1; $j--): ?>
		                                <?php if($i >= $j): ?>
		                                <li class="list-inline-item mr-1"><img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star.svg'); ?>" alt="Review rating" width="16"></li>
		                                <?php else: ?>
		                                  <li class="list-inline-item mr-1"><img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star-muted.svg'); ?>" alt="Review rating" width="16"></li>
		                                <?php endif; ?>
		                              <?php endfor; ?>
		                            </ul>
		                            <span><?php echo $percentage_of_rating; ?>%</span>
		                          </div>
		                        </a>
		                        <!-- End Review Rating -->
		                      </li>
		                    <?php endfor; ?>
		                  </ul>
		                </div>
		              </div>
		            </div>
		            <!-- End Overall Ratings -->




		        	<!-- Reviews Header -->
		            <div class="row justify-content-md-between align-items-md-center">
		              <div class="col-md-12 mb-5">
				            <?php if(count($ratings->result_array()) > 0): ?>
								<h3><?= site_phrase('reviews'); ?></h3>
							<?php endif; ?>
		              </div>
		            </div>
		            <!-- End Reviews Header -->

		            <?php foreach($ratings->result_array() as $rating):?>
		              <!-- Review -->
		              <div class="border-top pt-5 mb-5">
		                <div class="row mb-2">
		                  <div class="col-lg-4 mb-3 mb-lg-0">
		                    <!-- Review -->
		                    <div class="media align-items-center">
		                      <div class="avatar avatar-circle mr-3">
		                        <img class="avatar-img" src="<?php echo $this->user_model->get_user_image_url($rating['user_id']); ?>" alt="Image Description">
		                      </div>
		                      <div class="media-body">
		                        <span class="d-block text-body font-size-1"><?php echo date('D, d-M-Y', $rating['date_added']); ?></span>
		                        <h4 class="mb-0">
		                          <?php
		                            $user_details = $this->user_model->get_user($rating['user_id'])->row_array();
		                            echo $user_details['first_name'].' '.$user_details['last_name'];
		                          ?>
		                        </h4>
		                      </div>
		                    </div>
		                    <!-- End Review -->
		                  </div>

		                  <div class="col-lg-8">
		                    <ul class="list-inline mb-2">
		                      <?php for($i = 1; $i < 6; $i++):?>
		                        <?php if ($i <= $rating['rating']): ?>
		                          <li class="list-inline-item mr-1"><img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star.svg'); ?>" alt="Review rating" width="16"></li>
		                        <?php else: ?>
		                          <li class="list-inline-item mr-1"><img src="<?= base_url('assets/frontend/nifty/svg/illustrations/star-muted.svg'); ?>" alt="Review rating" width="16"></li>
		                        <?php endif; ?>
		                      <?php endfor; ?>
		                    </ul>

		                    <p><?php echo $rating['comment']; ?></p>
		                  </div>
		                </div>
		              </div>
		              <!-- End Review -->
		            <?php endforeach; ?>
		        </div>
		        <!-- End Ratings & Reviews -->
			</div>
      	</div>
      </div>
  	</div>
  </div>
</div>