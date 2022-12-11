<?php if($user_bundle_rating->num_rows() > 0): ?>
	<?php $user_bundle_rating = $user_bundle_rating->row_array(); ?>
	<div class="rating-row text-13 mb-3">
	    <?php for($i = 1; $i <= 5; $i++):?>
	        <?php if ($i <= $user_bundle_rating['rating']): ?>
	            <i class="fas fa-star filled text-warning"></i>
	        <?php else: ?>
	            <i class="fas fa-star text-ccc"></i>
	        <?php endif; ?>
	    <?php endfor; ?>
	    <span>(<?= $user_bundle_rating['rating'].' '.site_phrase('star_rating'); ?>)</span>
	</div>

	<!-- bundle update form -->
	<div class="w-100">
		<form action="javascript:;">
			<div class="form-group">
				<select id="user_bundle_rating" name="rating" class="form-control" required>
					<option value=""><?= site_phrase('select_rating') ?></option>
					<option value="1" <?php if($user_bundle_rating['rating'] == 1) echo 'selected'; ?>>1 <?= site_phrase('out_of') ?> 5</option>
					<option value="2" <?php if($user_bundle_rating['rating'] == 2) echo 'selected'; ?>>2 <?= site_phrase('out_of') ?> 5</option>
					<option value="3" <?php if($user_bundle_rating['rating'] == 3) echo 'selected'; ?>>3 <?= site_phrase('out_of') ?> 5</option>
					<option value="4" <?php if($user_bundle_rating['rating'] == 4) echo 'selected'; ?>>4 <?= site_phrase('out_of') ?> 5</option>
					<option value="5" <?php if($user_bundle_rating['rating'] == 5) echo 'selected'; ?>>5 <?= site_phrase('out_of') ?> 5</option>
				</select>
			</div>
			<div class="form-group">
				<textarea id="user_bundle_comment" class="form-control" name="comment"><?= $user_bundle_rating['comment'] ?></textarea>
			</div>
			<div class="row">
				<div class="col-md-12">
					<button onclick="bundle_rating('<?= $bundle_id; ?>')" class="btn btn-success float-right" data-dismiss="modal"><?= site_phrase('update_rating') ?></button>
				</div>
			</div>
		</form>
	</div>
<?php else: ?>
	<div class="rating-row text-13 mb-3">
	    <?php for($i = 1; $i <= 5; $i++):?>
	        <i class="fas fa-star text-ccc"></i>
	    <?php endfor; ?>
	</div>

	<!-- bundle update form -->
	<div class="w-100">
		<form action="javascript:;">
			<div class="form-group">
				<select id="user_bundle_rating" name="rating" class="form-control" required>
					<option value=""><?= site_phrase('select_rating') ?></option>
					<option value="1">1 <?= site_phrase('out_of') ?> 5</option>
					<option value="2">2 <?= site_phrase('out_of') ?> 5</option>
					<option value="3">3 <?= site_phrase('out_of') ?> 5</option>
					<option value="4">4 <?= site_phrase('out_of') ?> 5</option>
					<option value="5">5 <?= site_phrase('out_of') ?> 5</option>
				</select>
			</div>
			<div class="form-group">
				<textarea id="user_bundle_comment" class="form-control" name="comment"></textarea>
			</div>
			<div class="row">
				<div class="col-md-12">
					<button onclick="bundle_rating('<?= $bundle_id; ?>')" class="btn btn-success float-right" data-dismiss="modal"><?= site_phrase('publish_rating') ?></button>
				</div>
			</div>
		</form>
	</div>
<?php endif; ?>