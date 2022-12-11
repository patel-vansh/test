<?php $bundle_payments = $this->course_bundle_model->get_bundle_payment()->result_array(); ?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('active_subscription_report'); ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row justify-content-center">
  <div class="col-xl-12">
      <div class="card">
          <div class="card-body">
              <h4 class="mb-3 header-title"><?php echo get_phrase('subscription_report'); ?></h4>
              <ul class="nav nav-tabs nav-bordered mb-3">
                  <li class="nav-item">
                      <a href="<?= site_url('addons/bundle/subscription_report/active'); ?>" class="nav-link active">
                          <span class="d-lg-none d-block mr-1 badge badge-danger"><?php echo get_phrase('active'); ?></span>
                          <span class="d-none d-lg-block"><?php echo get_phrase('active'); ?></span>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a href="<?= site_url('addons/bundle/subscription_report/expire'); ?>" class="nav-link">
                          <span class="d-lg-none d-block mr-1 badge badge-danger"><?php echo get_phrase('expire'); ?></span>
                          <span class="d-none d-lg-block"><?php echo get_phrase('expire'); ?></span>
                      </a>
                  </li>
              </ul>

              <div class="row justify-content-center">
                <div class="col-xl-12">
                  <form class="form-inline justify-content-center" action="<?php echo site_url('addons/bundle/subscription_report/active/filter') ?>" method="post">
                    <div class="col-md-12 col-xl-3 pb-2">
                      <div class="form-group">
                        <select class="form-control select2" name="bundle_id" data-toggle="select2">
                          <option value=""><?= get_phrase('all_bundles'); ?></option>

                          <?php $bundle_details = $this->course_bundle_model->get_bundle()->result_array(); ?>
                          <?php foreach($bundle_details as $bundle_detail): ?>
                            <option value="<?= $bundle_detail['id']; ?>" <?php if($search_bundle_id == $bundle_detail['id']) echo 'selected'; ?>><?php echo $bundle_detail['title']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-12 col-xl-3 pb-2">
                      <div class="form-group">
                        <select class="form-control select2" name="user_id" data-toggle="select2">
                          <option value=""><?= get_phrase('all_users'); ?></option>

                          <?php $users = $this->user_model->get_all_user()->result_array(); ?>
                          <?php foreach($users as $user): ?>
                            <option value="<?= $user['id']; ?>" <?php if($search_user_id == $user['id']) echo 'selected'; ?>><?= $user['first_name'].' '.$user['last_name']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12 col-xl-4 pb-2">
                      <div class="form-group">
                        <div id="reportrange" class="form-control w-100 h-auto" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light w-100">
                            <i class="mdi mdi-calendar"></i>
                            <span id="selectedValue"><?php echo date('F d, Y', $start_time) . " - " . date("F d, Y", $end_time);?></span> <i class="mdi mdi-menu-down"></i>
                        </div>
                        <input id="date_range" type="hidden" name="date_range" value="">
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-2 pb-2">
                        <button type="submit" class="btn btn-info" id="submit-button" onclick="update_date_range();"> <?php echo get_phrase('filter');?></button>
                    </div>
                  </form>
                </div>
              </div>

              <div class="tab-pane" id="pending-b1">
                  <div class="table-responsive-sm mt-4">
                      <table id = "basic-datatable" class="table table-striped table-centered mb-0">
                          <thead>
                             <tr>
                                <th><?= get_phrase('user'); ?></th>
                                <th><?php echo get_phrase('bundle'); ?></th>
                                <th><?php echo get_phrase('included_courses'); ?></th>
                                <th><?php echo get_phrase('date'); ?></th>
                                <th><?php echo get_phrase('amount'); ?></th>
                                <th><?php echo get_phrase('action'); ?></th>
                             </tr>
                          </thead>
                          <tbody>
                            <?php
                            if(isset($filter_bundle_payments)){
                              $bundle_payments = $filter_bundle_payments->result_array();
                            }
                            foreach ($bundle_payments as $bundle_payment):
                              $user_data = $this->user_model->get_all_user($bundle_payment['user_id'])->row_array();
                              $bundle_details = $this->course_bundle_model->get_bundle($bundle_payment['bundle_id'])->row_array();
                              $courses = $this->course_bundle_model->get_course_by_ids(json_decode($bundle_details['course_ids']))->result_array();
                              
                              $expire_date = $bundle_payment['date_added']+$bundle_details['subscription_limit']*86400;
                              if($expire_date < strtotime(date('d M Y'))) continue;
                            ?>
                            <tr class="gradeU">
                              <td>
                                 <p class="m-0"><?= $user_data['first_name'].' '.$user_data['last_name']; ?></p>
                                 <small><?= $user_data['email']; ?></small>
                              </td>
                              <td><a target="_blank" href="<?= site_url('bundle_details/'.$bundle_details['id'].'/'.slugify($bundle_details['title'])); ?>"><?php echo $bundle_details['title']; ?></a></td>
                              <td>
                                 <ul>
                                    <?php foreach($courses as $course): ?>
                                    <li><?= $course['title']; ?></li>
                                    <?php endforeach; ?>
                                 </ul>
                              </td>
                              <td>
                                <p class="m-1"><?php echo get_phrase('purchase_date').': <b>'.date('d M Y', $bundle_payment['date_added']); ?></b></p>
                                <p class="m-1"><?php echo get_phrase('expire_date').': <b class="text-success">'.date('d M Y', $expire_date); ?></b></p>
                              </td>
                              <td><?php echo currency($bundle_details['price']); ?></td>
                              <td>
                                 <div class="dropright dropright">
                                    <a href="<?= site_url('addons/bundle/invoice/'.$bundle_payment['id']); ?>" class="btn btn-sm btn-outline-primary btn-rounded btn-icon">
                                    <i class="mdi mdi-printer"></i>
                                    </a>
                                 </div>
                              </td>
                            </tr>
                            <?php endforeach; ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div> <!-- end card-body-->
      </div> <!-- end card-->
  </div>
</div>
<?php include "course_bundle_script.php"; ?>