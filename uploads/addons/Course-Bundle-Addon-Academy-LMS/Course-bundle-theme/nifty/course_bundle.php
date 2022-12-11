<!-- Hero Section -->
<div class="container space-top-1">
	<div class="bg-primary rounded" style="background: url(<?= base_url('assets/frontend/nifty//svg/illustrations/knowledgebase-community-2.svg'); ?>) right bottom no-repeat;">
    	<div class="py-4 px-6">
      		<h1 class="display-4 text-white"><?php echo site_phrase('course_bundles'); ?></h1>
      		<p class="text-white mb-0">
        		<span class="font-weight-bold"><?php echo count($course_bundles->result_array()); ?> </span><?php echo site_phrase('bundles_on_this_page'); ?>
			</p>
		</div>
	</div>
</div>


<div class="container mt-5">
	<div class="row mb-8">
		<div class="col-sm-12 col-md-6"></div>
		<div class="col-sm-12 col-md-6">
            <form class="" action="<?= site_url('course_bundles/search/query'); ?>" method="get">
                <div class="input-group">
                    <input type="text" name="string" value="<?php if(isset($search_string)) echo $search_string; ?>" class="form-control" placeholder="<?= site_phrase('search_for_bundle'); ?>">
                    <button class="btn btn-primary m-0 rounded-0" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
		</div>
	</div>
	<div class="row justify-content-center">
    	<div class="col-lg-12">
			<div class="row">
				<?php foreach($course_bundles->result_array() as $bundle):
				    $course_ids = json_decode($bundle['course_ids']);
				    sort($course_ids);
					
					//Bundle Rating
			        $ratings = $this->course_bundle_model->get_bundle_wise_ratings($bundle['id']);
			        $bundle_total_rating = $this->course_bundle_model->sum_of_bundle_rating($bundle['id']);
			        if ($ratings->num_rows() > 0) {
			            $bundle_average_ceil_rating = ceil($bundle_total_rating / $ratings->num_rows());
			        }else {
			            $bundle_average_ceil_rating = 0;
			        }
			    ?>
			    <article class="col-md-6 col-lg-4 mb-5">
			      <!-- Article -->
			      <div class="card border h-100">

			        <div class="card-body pb-1">
			          <div class="mb-3">
			          	<div class="mb-3">
			            	<h5 class="mb-0 pb-0">
			              		<a class="text-inherit text-muted" href="<?php echo site_url('bundle_details/'.$bundle['id'].'/'.rawurlencode(slugify($bundle['title']))); ?>"><?= $bundle['title']; ?></a>
			            	</h5>
			            	<span class="text-13"><?= count($course_ids).' '.site_phrase('courses'); ?></span>
			            </div>
			            <?php $total_courses_price = 0; ?>
			            <?php foreach($course_ids as $key => $course_id):
			                ++$key;
			                $this->db->where('id', $course_id);
			                $this->db->where('status', 'active');
			                $course_details = $this->db->get('course')->row_array();

							if ($course_details['is_free_course'] != 1):
				                if ($course_details['discount_flag'] != 1): ?>
	                                <?php $total_courses_price += $course_details['price'];
	                            else:
	                            	$total_courses_price += $course_details['discounted_price'];
	                            endif;
                            endif;
                            if($key <= 3): ?>
				                <div class="row mb-2">
									<div class="col-md-12">
										<a href="<?php echo site_url('home/course/'.rawurlencode(slugify($course_details['title'])).'/'.$course_details['id']); ?>" target="_blank">
											<img src="<?php echo $this->crud_model->get_course_thumbnail_url($course_details['id']); ?>" alt="" class="img-fluid mr-2 float-left " width="60px;">
							                <p class="text-muted p-0 m-0 cursor-pointer text-13 lh-20">
				                                <span class="text-13"><?= $course_details['title']; ?></span>

				                                <?php if ($course_details['is_free_course'] == 1): ?>
				                                    <b><span class="float-right d-block"><?php echo site_phrase('free'); ?></span></b>
				                                <?php else: ?>
				                                    <?php if ($course_details['discount_flag'] != 1): ?>
				                                        <b><span class="float-right d-block"><?php echo currency($course_details['price']); ?></span></b>
				                                    <?php else: ?>
				                                        <b><span class="float-right d-block"><?php echo currency($course_details['discounted_price']); ?></span></b>
				                                    <?php endif; ?>
				                                <?php endif; ?>
				                            </p>
				                        </a>
			                        </div>
			                    </div>
			                    <hr>
		                	<?php endif; ?>
			            <?php endforeach; ?>
			          </div>
			          <div class="row bundle-arrow-down text-center cursor-pointer" id="bundle_arrow_down_<?= $bundle['id']; ?>" onclick="toggleBundleCourses('<?= $bundle['id']; ?>', '<?= count($course_ids); ?>')">
	                    <div class="col-md-12"><i class="fas fa-angle-down"></i></div>
	                  </div>

	                  <!-- This is loading gif -->
                      <div class="row bundle-slider closed" id="gif_loader_<?= $bundle['id']; ?>"></div>

                      <!--Here is load more bundle-->
                      <div class="row bundle-slider closed" id="course_of_bundle_<?= $bundle['id']; ?>"></div>
			        </div>

			        <div class="card-footer border-0 pt-0">
			          <div class="d-flex justify-content-between align-items-center">
		                <div class="ml-2">
				            <div class="d-flex align-items-center flex-wrap">
				              <ul class="list-inline mt-n1 mb-0 mr-2">
				                <?php for($i = 1; $i < 6; $i++):?>
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
							  <span class="text-13">(<?php echo $ratings->num_rows().' '.site_phrase('students'); ?>)</span>
				            </div>
				        </div>	
			            <div class="mr-2">
			            	<span class="d-block h5 text-lh-sm mb-0"><?php echo currency($bundle['price']); ?></span>
							<span class="d-block text-muted text-lh-sm text-13"><del><?php echo currency($total_courses_price); ?></del></span>
			            </div>
			          </div>
			        </div>
			      </div>
			      <!-- End Article -->
			    </article>
			  <?php endforeach; ?>
			  <!-- Pagination -->
			  <div class="col-md-12">
			    <div class="d-flex justify-content-between align-items-center mt-8">
			      <small class="d-none d-sm-inline-block text-body"></small>
			      <nav aria-label="Page navigation">
			        <?= $this->pagination->create_links(); ?>
			      </nav>
			    </div>
			  </div>
			  <!-- End Pagination -->
			</div>
		</div>
	</div>
</div>


	<!-- End Hero Section -->
<?php include "course_bundle_scripts.php"; ?>