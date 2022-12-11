<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('manage_course_bundle'); ?>
                  <a href="<?php echo site_url('addons/bundle/add_bundle_form'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_new_bundle'); ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<!-- Start page title end -->
<div class="row justify-content-center">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive-sm mt-4">
          <table id = "basic-datatable" class="table table-striped table-centered mb-0">
            <thead>
              <tr>
                <th><?php echo get_phrase('bundle'); ?></th>
                <th><?php echo get_phrase('courses'); ?></th>
                <th><?php echo get_phrase('subscription_limit'); ?></th>
                <th><?php echo get_phrase('price'); ?></th>
                <th><?php echo get_phrase('status'); ?></th>
                <th><?php echo get_phrase('action'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bundles as $bundle):
              	$courses = $this->course_bundle_model->get_course_by_ids(json_decode($bundle['course_ids']))->result_array();
              	?>
                <tr class="gradeU">
                  <td><a href="<?= site_url('addons/bundle/edit_bundle_form/'.$bundle['id']); ?>"><?php echo $bundle['title']; ?></a></td>
                  <td>
                  	<ul>
                  		<?php foreach($courses as $course): ?>
                  			<li><?= $course['title']; ?></li>
                  		<?php endforeach; ?>
                  	</ul>
                  </td>
                  <td><?php echo $bundle['subscription_limit'].' '.get_phrase('days'); ?></td>
                  <td><?php echo currency($bundle['price']); ?></td>
                  <td>
                  	<?php if($bundle['status']){ ?>
                  		<span class="badge badge-success"><?= get_phrase('active'); ?></span>
                  	<?php }else{ ?>
                  		<span class="badge badge-secondary"><?= get_phrase('deactive'); ?></span>
                  	<?php } ?>
                  </td>
                  
                  <td>
                    <div class="dropright dropright">
                      <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= site_url('addons/bundle/edit_bundle_form/'.$bundle['id']); ?>"><?php echo get_phrase('edit'); ?></a></li>

                        <?php if($bundle['status'] == 1): ?>
                          <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('addons/bundle/bundle_update_status/'.$bundle['id'].'/deactive'); ?>');"><?php echo get_phrase('deactive'); ?></a></li>
                        <?php else: ?>
                          <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('addons/bundle/bundle_update_status/'.$bundle['id'].'/active'); ?>');"><?php echo get_phrase('active'); ?></a></li>
                        <?php endif; ?>

                        <li><a class="dropdown-item" target="_blank" href="<?= site_url('bundle_details/'.$bundle['id'].'/'.slugify($bundle['title'])); ?>"><?php echo get_phrase('view_on_frontend'); ?></a></li>

                        <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('addons/bundle/bundle_delete/'.$bundle['id']); ?>');"><?php echo get_phrase('delete'); ?></a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>