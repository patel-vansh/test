<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo $page_title; ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<div class="row ">
    <div class="col-md-7 col-xl-8">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title my-3">
                    <?php echo get_phrase('configure_your_cloud_settings'); ?>
                    <a href="https://video.creativeitem.com" target="_blank" class="float-right" data-toggle="tooltip" data-placement="top" title="<?php echo get_phrase('my_cloud'); ?>"><i class="mdi mdi-open-in-new"></i></a>
                </h5>
                <form action="<?php echo site_url('admin/academy_cloud/update'); ?>" method="post">
                    <div class="form-group mb-3">
                        <label><?php echo get_phrase('your_domain'); ?></label>
                        <input class="form-control bg-light" type="text" value="<?php echo $_SERVER['SERVER_NAME']; ?>" readonly disabled>
                    </div>

                    <div class="form-group mb-3">
                        <label><?php echo get_phrase('access_token'); ?></label>
                        <input class="form-control" type="text" name="access_token" value="<?php echo get_settings('academy_cloud_access_token'); ?>" placeholder="<?php echo get_phrase('enter_your_valid_access_token'); ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary w-100"><?php echo get_phrase('save'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5 col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5><?php echo get_phrase('academy_cloud_information'); ?></h5>

                <?php if(!$subscription_details['expired_date']): ?>
                    <span class="badge badge-success-lighten d-block-inline float-right pt-1"><?php echo $subscription_details['subscription_status'] ?></span>
                <?php endif; ?>

                <h1 class="w-100 text-center mt-4 mb-1"><i class="mdi mdi-server-security text-success"></i></h1>
                <h4 class="w-100 text-center mb-3">
                    <?php echo $subscription_details['package_title']; ?>
                    <?php if($subscription_details['expired_date']): ?>
                        <span class="badge badge-warning-lighten d-block-inline pt-1 mt-2"><?php echo get_phrase('expired_on').' '.date('d M Y, h:i a', strtotime($subscription_details['expired_date'])) ?></span>
                    <?php endif; ?>
                    </h4>
                <hr>
                <?php $storage_limit_with_gb = round(($subscription_details['storage_limit']/1024)/1024, 2); ?>
                <?php $storage_available_with_gb = round(($subscription_details['storage_available']/1024)/1024, 2); ?>
                <p class="my-1"><small><?php echo get_phrase('storage_limit') ?></small> - <b><?php echo $storage_limit_with_gb.' '.get_phrase('GB'); ?></b></p>
                <p class="my-1"><small><?php echo get_phrase('storage_available') ?></small> - <b><?php echo $storage_available_with_gb.' '.get_phrase('GB'); ?></b></p>
                
                <p class="my-1"><small><?php echo get_phrase('package_validity'); ?></small> - <b><?php echo $subscription_details['package_validity']; ?></b></p>
                <hr>
                <p class="m-0">
                    <small>
                        <i class="text-success mdi mdi-checkbox-marked-circle"></i> <?php echo get_phrase('more_secured') ?>
                    </small>
                </p>
                <p class="m-0">
                    <small>
                        <i class="text-success mdi mdi-checkbox-marked-circle"></i> <?php echo get_phrase('reduces_stress_on_your_website') ?>
                    </small>
                </p>
                <p class="m-0">
                    <small>
                        <i class="text-success mdi mdi-checkbox-marked-circle"></i> <?php echo get_phrase('at_an_affordable_price') ?>
                    </small>
                </p>
                <a class="btn btn-success w-100 mt-2" href="https://video.creativeitem.com" target="_blank"><?php echo get_phrase('extend_storage'); ?> <i class="mdi mdi-open-in-new"></i></a>
            </div> <!-- end card-->
        </div>
    </div>
</div>