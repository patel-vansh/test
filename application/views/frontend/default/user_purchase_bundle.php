<?php 

foreach ($my_bundles->result_array() as $key => $my_bundle):
	$user_id = $this->session->userdata('user_id');
	$course_ids = json_decode($my_bundle['course_ids']);
	$iid = $my_bundle['id'];
	$sq = "SELECT `expired` FROM `bundle_payment` WHERE `bundle_id`='$iid' AND user_id='$user_id'";
	$query = $this->db->query($sq);
	$rr = $query->row_array();
	if(empty($rr))
	{
		$sq = "SELECT `expired` FROM `bundle_payment_trash` WHERE `bundle_id`='$iid' AND user_id='$user_id'";
		$query = $this->db->query($sq);
		$rr = $query->row_array();
	}

	$expired = $rr['expired'];
	if($expired=='1')
	{
		$style="style='pointer-events: none;'";
	}
	else
	{
		$style="";
	}
	?>
	<div class="col-lg-4 p-1 mb-3" <?php echo $style; ?>>
		<div class="w-100">
			<?php if(file_exists('uploads/course_bundle/banner/'.$my_bundle['banner'])): ?>
	            <img src="<?php echo base_url('uploads/course_bundle/banner/'.$my_bundle['banner']); ?>" alt="" class="img-fluid">
	        <?php else: ?>
	            <img src="<?php echo base_url('uploads/course_bundle/banner/thumbnail.png'); ?>" alt="" class="img-fluid">
	        <?php endif; ?>
		</div>
		<div id="accordion<?= $key; ?>">
			<div class="card px-3 pt-2">
				<h6 class="pt-2"><a href="<?= site_url('bundle_details/'.$my_bundle['id'].'/'.slugify($my_bundle['title'])); ?>" class="text-info"><?= $my_bundle['title']; ?></a></h6>
				<hr>
				<?php foreach($course_ids as $course_id):
	                $this->db->where('id', $course_id);
	                $course_details = $this->db->get('course')->row_array();
	                if($course_details['status'] != 'active'){
	                	continue;
	                }
	                ?>
	                <div class="card" style="margin-top: 2.5px; margin-bottom: 2.5px; background-color: #f7f7f7;">
	                    <div class="card-header border-0 collapsed p-0" type="button" data-toggle="collapse" data-target="#<?= 'course_'.$my_bundle['id'].$course_details['id']; ?>" aria-expanded="false" aria-controls="<?= 'course_'.$my_bundle['id'].$course_details['id']; ?>">
	                        <div class="row row-no-gutters">
	                            <div class="col-sm-3">
	                                <img src="<?php echo $this->crud_model->get_course_thumbnail_url($course_details['id']); ?>" alt="" class="img-fluid float-left " width="60px;">
	                            </div>
	                            <div class="col-sm-5">
	                                <a href="<?php echo site_url('home/course/'.rawurlencode(slugify($course_details['title'])).'/'.$course_details['id']); ?>" target="_blank" class="text-muted m-0 cursor-pointer text-12">
	                                    <?= $course_details['title']; ?>
	                                </a>
	                            </div>
	                            <div class="col-sm-4">
	                                <a class="text-12 float-right" href="<?= site_url('addons/course_bundles/lesson/'.rawurlencode(slugify($course_details['title'])).'/'.$my_bundle['id'].'/'.$course_id); ?>">
	                        	        <i class="fas fa-play-circle"></i>
	                                <?= site_phrase('start_lesson'); ?>
	                                </a>  
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <!--<hr>-->
	            <?php endforeach; ?>


				<div class="w-100 pb-2">
					<div class="status text-muted float-left w-100">
						<?= site_phrase('status'); ?> : 
						<?php if(get_bundle_validity($my_bundle['id']) == 'valid'): ?>
							<span class="badge bg-success"><?= site_phrase('active'); ?></span>
						<?php else: ?>
							<span class="badge bg-danger"><?= site_phrase('expired'); ?></span>
						<?php endif; ?>
						<?php if($my_bundle['status'] != 1): ?>
							<span class="badge bg-secondary"><?= site_phrase('currently_deactivate'); ?></span>
						<?php endif; ?>
						<br>
						<span class="expire-date mini-text">
							<?php $expire_date = $this->course_bundle_model->get_bundle_purchase_date($my_bundle['id'])+$my_bundle['subscription_limit']*86400; ?>
                            <?= site_phrase('expire_on_date').': '.date('d M Y', $expire_date); ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>


<?php 
// Trash Bundle Get 
$user_id =  $this->session->userdaTa('user_id');
$sql = $this->db->query("SELECT `bundle_id`,id FROM `bundle_payment_trash` WHERE `user_id`='$user_id'");
$rows = $sql->result_array();
foreach ($rows as  $coursess_ids)
{ 
$bundle_id = $coursess_ids['bundle_id'];
$quer = $this->db->query("SELECT * FROM `course_bundle` WHERE `id`='$bundle_id'");
$my_bundle = $quer->row_array();
// Trash Bundle Get END



	$user_id = $this->session->userdata('user_id');
	$course_ids = json_decode($my_bundle['course_ids']); 



	$id = $coursess_ids['id'];
	$sq = "SELECT * FROM `bundle_payment_trash` WHERE id='$id'";
	$query = $this->db->query($sq);
	$rr = $query->row_array(); 
	 
	if($rr['expired']=='1')
	{
		$style = "style='pointer-events: none;'";
        $div = "";
        $status_c = "Expired";
        $status_cc = "Expired";
        $status_c_style = "danger";
        $e_date = $rr['created'];
	}
	 

	?>
	<div class="col-lg-4 p-1 mb-3" >
		<div class="w-100">
			<?php if(file_exists('uploads/course_bundle/banner/'.$my_bundle['banner'])): ?>
	            <img src="<?php echo base_url('uploads/course_bundle/banner/'.$my_bundle['banner']); ?>" alt="" class="img-fluid">
	        <?php else: ?>
	            <img src="<?php echo base_url('uploads/course_bundle/banner/thumbnail.png'); ?>" alt="" class="img-fluid">
	        <?php endif; ?>
		</div>
		<div id="accordion<?= $key; ?>">
			<div class="card px-3 pt-2">
				<h6 class="pt-2"><a href="<?= site_url('bundle_details/'.$my_bundle['id'].'/'.slugify($my_bundle['title'])); ?>" class="text-info"><?= $my_bundle['title']; ?></a></h6>
				<hr>
				<?php foreach($course_ids as $course_id):
	                $this->db->where('id', $course_id);
	                $course_details = $this->db->get('course')->row_array();
	                if($course_details['status'] != 'active'){
	                	continue;
	                }
	                ?>
					<div class="">
	                    <div class="card-header bg-white border-0 collapsed p-0" type="button" data-toggle="collapse" data-target="#<?= 'course_'.$my_bundle['id'].$course_details['id']; ?>" aria-expanded="false" aria-controls="<?= 'course_'.$my_bundle['id'].$course_details['id']; ?>">
	                        <img src="<?php echo $this->crud_model->get_course_thumbnail_url($course_details['id']); ?>" alt="" class="img-fluid float-left " width="60px;">
	                        <div class="row">
	                            <div class="col-md-12">
	                                <a href="<?php echo site_url('home/course/'.rawurlencode(slugify($course_details['title'])).'/'.$course_details['id']); ?>" target="_blank" class="text-muted m-0 cursor-pointer text-12">
	                                    <?= $course_details['title']; ?>
	                                </a>
	                            </div>
	                        </div>
	                        <a class="text-12 float-right" <?php echo $style; ?> href="<?= site_url('addons/course_bundles/lesson/'.rawurlencode(slugify($course_details['title'])).'/'.$my_bundle['id'].'/'.$course_id); ?>">
	                        	<i class="fas fa-play-circle"></i>
	                            <?= site_phrase('start_lesson'); ?>
	                        </a>
	                    </div>
	                </div>
	                <hr>
	            <?php endforeach; ?>


				<div class="w-100 pb-2">
					<div class="status text-muted float-left w-100">
						<?= site_phrase('status'); ?> : 
						 
							<span class="badge badge-danger"><?= site_phrase('expired'); ?></span>
						 
						<div class="btn-group dropleft float-right">
							<button type="button" class="border-0 bg-white text-muted" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-ellipsis-v"></i>
							</button>
							<div class="dropdown-menu">
								<!-- <a class="dropdown-item text-muted" href="javascript:;" onclick="showAjaxModal('<?= site_url('addons/course_bundles/bundle_purchase_history/'.$my_bundle['id']); ?>')"><?= site_phrase('payment_history'); ?></a>
								  -->
									 
									 
									<a class="dropdown-item text-muted" href="<?= site_url('course_bundles/buy/'.$my_bundle['id']); ?>"><?= site_phrase('renew_subscription'); ?></a>
								 
							</div>
						</div>
						<br>
						<span class="expire-date mini-text">
							 <?php $expire_date = strtotime($e_date); ?>
                            Expired on date: <?php echo date('d M Y', $expire_date); ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php include 'course_bundle_scripts.php'; ?>