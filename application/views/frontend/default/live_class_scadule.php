<?php
	$has_upcoming_live_class = 0;
	$live_class_time = null;

if (addon_status('live-class')):
		$this->db->where('course_id', $course_details['id']);
		$live_class = $this->db->get('live_class');
		$live_class_time = date('d M Y ', $live_class->row('date')).date('h:i a', $live_class->row('time'));

		if ($live_class->num_rows() > 0 && strtotime($live_class_time) > time()):
		    $live_class = $live_class->row_array();
		    $has_upcoming_live_class = 1;
		endif;
endif; ?>

<?php if(addon_status('jitsi-live-class')):
		$this->db->where('course_id', $course_details['id']);
		$jitsi_live_class = $this->db->get('jitsi_live_class');
		$jitsi_live_class_time = date('d M Y ', $jitsi_live_class->row('date')).date('h:i a', $jitsi_live_class->row('time'));

		if ($jitsi_live_class->num_rows() > 0):
		    if($has_upcoming_live_class == 1 && strtotime($live_class_time) > strtotime($jitsi_live_class_time)){
		    	$live_class_time = $jitsi_live_class_time;
		    }elseif($has_upcoming_live_class == 0){
		    	$has_upcoming_live_class = 1;
		    	$live_class_time = $jitsi_live_class_time;
		    }
		endif;
endif?>


<?php if($has_upcoming_live_class == 1): ?>
	<div class="col-md-12 px-4">
      <div class="alert alert-primary box-shadow-3 text-center text-13px" role="alert">
        <?php echo get_phrase('live_class'); ?> <strong><?php echo date('h:i A, d M Y', strtotime($live_class_time)); ?></strong>.
      </div>
    </div>
<?php endif; ?>