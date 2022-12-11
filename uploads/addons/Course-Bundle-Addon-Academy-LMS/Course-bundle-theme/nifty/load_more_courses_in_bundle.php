<div class="mb-3 w-100">
    <?php if(isset($courses)):
        foreach($courses as $key => $course_details): ?>
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
        <?php endforeach;
    endif; ?>
    <div class="row mb-2">
        <div class="col-md-12 text-center cursor-pointer" onclick="toggleBundleCourses('<?= $bundle_details['id']; ?>', '<?= count($bundle_details); ?>')">
            <i class="fas fa-angle-up d-block p-2"></i>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-6">
            <a href="<?= site_url('bundle_details/'.$bundle_details['id'].'/'.slugify($bundle_details['title'])); ?>" class="btn btn-outline-primary w-100 p-2 mb-2"><?= site_phrase('bundle_details'); ?></a>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-6">
            <?php if(get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == 'invalid'): ?>
                <a href="<?= site_url('course_bundles/buy/'.$bundle_details['id']); ?>" class="btn btn-outline-success w-100 p-2 mb-2"><?= site_phrase('buy'); ?></a>
            <?php elseif(get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == 'expire'): ?>
                <a href="<?= site_url('course_bundles/buy/'.$bundle_details['id']); ?>" class="btn btn-outline-danger w-100 p-2 mb-2"><?= currency($bundle_details['price']); ?> | <?= site_phrase('renew'); ?></a>
            <?php else: ?>
                <a href="<?= site_url('home/my_bundles'); ?>" class="btn btn-outline-info w-100 p-2 mb-2"><?= site_phrase('purchased'); ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>