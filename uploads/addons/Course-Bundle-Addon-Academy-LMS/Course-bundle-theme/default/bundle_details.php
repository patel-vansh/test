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

<section class="course-header-area bundle-bg-image" style="background-image: url('<?= $bundle_banner; ?>');">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-9 col-lg-10 p-1">
				<div class="course-bundle-details-header">
					<p class="title"><?php echo $bundle_details['title']; ?></p>

					<p class="created-by">
						<?php echo site_phrase('created_by'); ?>
						<a class="by-name" href="<?php echo site_url('home/instructor_page/'.$bundle_details['user_id']); ?>">
							<span class="badge badge-info p-2"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></span>
						</a>
						<span class="last-updated-date"><?php echo date('D, d-M-Y', $bundle_details['date_added']); ?></span>
					</p>
				</div>

				<div class="rating-row my-3">
					<?php for($i = 1; $i <= 5; $i++):?>
						<?php if ($i <= $bundle_average_ceil_rating): ?>
							<i class="fas fa-star filled text-warning"></i>
						<?php else: ?>
							<i class="fas fa-star"></i>
						<?php endif; ?>
					<?php endfor; ?>
					<span class="enrolled-num">
						(<?php echo $ratings->num_rows().' '.site_phrase('students'); ?>)
					</span>
				</div>

				<p class="w-100"><?= site_phrase('total').' <b>'.count(json_decode($bundle_details['course_ids'])).'</b> '.site_phrase('courses_included'); ?></p>
			</div>
			<div class="col-md-3 col-lg-2 p-1">
				<div href="javascript:;" class="bundle-buy-button">
					<p class="text-15"><?= site_phrase('subscription'); ?> <?= $bundle_details['subscription_limit']; ?> <?= site_phrase('days'); ?></p>
					<hr class="m-1">
					<?php if(get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == 'invalid'): ?>
						<a href="<?= site_url('course_bundles/buy/'.$bundle_details['id']); ?>" class="btn"><?= currency($bundle_details['price']); ?> | <?= site_phrase('buy'); ?></a>
					<?php elseif(get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == 'expire'): ?>
						<a href="<?= site_url('course_bundles/buy/'.$bundle_details['id']); ?>" class="btn"><?= currency($bundle_details['price']); ?> | <?= site_phrase('renew'); ?></a>
					<?php else: ?>
						<a href="<?= site_url('home/my_bundles'); ?>" class="btn"><?= site_phrase('purchased'); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
			
	</div>
</section>


<section class="course-content-area">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-9">
				<h3 class="my-3 pt-3"><?= site_phrase('included_courses'); ?></h3>
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
						<div class="course-box-wrap">
							<a href="<?php echo site_url('home/course/'.rawurlencode(slugify($course['title'])).'/'.$course['id']); ?>">
							<div class="course-box course-bundle-box">
								<div class="course-image">
									<img src="<?php echo $this->crud_model->get_course_thumbnail_url($course['id']); ?>" alt="" class="img-fluid">
								</div>
								<div class="course-details">
									<div class="title text-muted m-0"><?php echo $course['title']; ?></div>
									<small class="text-dark text-muted">
										<?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?>
									</small>

									<!--Price-->
									<?php if ($course['is_free_course'] == 1): ?>
										<span class="price d-block float-right"><?php echo site_phrase('free'); ?></span>
									<?php else: ?>
										<?php if ($course['discount_flag'] == 1): ?>
											<span class="price d-block float-right"><small><?php echo currency($course['price']); ?></small><?php echo currency($course['discounted_price']); ?></span>
										<?php else: ?>
											<span class="price d-block float-right"><?php echo currency($course['price']); ?></span>
										<?php endif; ?>
									<?php endif; ?>
									<!--End Price-->

									<div class="rating">
										<?php for($i = 1; $i <= 5; $i++):?>
											<?php if ($i <= $average_ceil_rating): ?>
												<i class="fas fa-star filled text-warning"></i>
											<?php else: ?>
												<i class="fas fa-star"></i>
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
			</div>
			<div class="col-lg-9">
				<div class="student-feedback-box">
					<div class="row">
						<div class="col-lg-3">
							<div class="average-rating">
								<div class="num">
									<?= $bundle_average_ceil_rating; ?>
								</div>
								<div class="rating">
									<?php for($i = 1; $i <= 5; $i++):?>
										<?php if ($i <= $bundle_average_ceil_rating): ?>
											<i class="fas fa-star filled text-warning"></i>
										<?php else: ?>
											<i class="fas fa-star"></i>
										<?php endif; ?>
									<?php endfor; ?>
								</div>
								<div class="title mb-3"><?php echo site_phrase('average_rating'); ?></div>
							</div>
						</div>
						<div class="col-lg-9">
							<div class="individual-rating">
								<ul>
									<?php for($i = 1; $i <= 5; $i++): ?>
										<?php $percentage_of_rating = $this->course_bundle_model->get_bundle_percentage_of_specific_rating($bundle_details['id'], $i); ?>
									<li>
										<div class="progress">
											<div class="progress-bar" style="width: <?= $percentage_of_rating; ?>%"></div>
											</div>
										<div>
											<span class="rating">
												<?php for($j = 1; $j <= (5-$i); $j++): ?>
													<i class="fas fa-star"></i>
												<?php endfor; ?>
												<?php for($j = 1; $j <= $i; $j++): ?>
													<i class="fas fa-star filled"></i>
												<?php endfor; ?>
											</span>
											<span><?php echo $percentage_of_rating; ?>%</span>
										</div>
									</li>
									<?php endfor; ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-9">
				<?php if(count($ratings->result_array()) > 0): ?>
					<h3><?= site_phrase('reviews'); ?></h3>
				<?php endif; ?>
				<?php foreach($ratings->result_array() as $rating):?>
					<hr class="">
					<div class="row mb-4">
						<div class="col-1">
							<img width="50" class="rounded-circle" src="<?php echo $this->user_model->get_user_image_url($rating['user_id']); ?>" alt="">
						</div>
						<div class="col-md-11">
							<p class="text-muted m-0">
								<?php $user_details = $this->user_model->get_user($rating['user_id'])->row_array();
								echo $user_details['first_name'].' '.$user_details['last_name']; ?>
							</p>
							<small class="text-muted"><?php echo date('D, d-M-Y', $rating['date_added']); ?></small>
							<div class="review-details float-right">
								<div class="rating">
									<?php for($i = 1; $i <= 5; $i++):?>
										<?php if ($i <= $rating['rating']): ?>
											<i class="fas fa-star filled text-warning"></i>
										<?php else: ?>
											<i class="fas fa-star"></i>
										<?php endif; ?>
									<?php endfor; ?>
								</div>
							</div>
							<p class="text-muted mt-2">
								<?php echo $rating['comment']; ?>
							</p>
						</div>
					</div>
				<?php endforeach; ?>
				
			</div>
		</div>
	</div>
</section>