<div class="col-md-12">
    <div class="load-bundle-card pb-3">
        <?php if(isset($courses)): ?>
            <?php foreach($courses as $key => $course_details): ?>
                <div class="accordion mb-2" id="<?= 'example_'.$bundle_details['id'].$course_details['id']; ?>">
                    <a href="<?php echo site_url('home/course/'.rawurlencode(slugify($course_details['title'])).'/'.$course_details['id']); ?>" target="_blank">
                        <div class="card" style="border: none; padding-bottom: 6px; background-color: #f8f8f8;">
                            <div class="card-header collapsed p-0" type="button" data-toggle="collapse" data-target="#<?= 'course_'.$bundle_details['id'].$course_details['id']; ?>" aria-expanded="false" aria-controls="<?= 'course_'.$bundle_details['id'].$course_details['id']; ?>" style="height: 50px;">
                                <img src="<?php echo $this->crud_model->get_course_thumbnail_url($course_details['id']); ?>" alt="" class="img-fluid float-left" width="60px;" style="height: inherit;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-muted m-0 cursor-pointer text-12">

                                            <?= $course_details['title']; ?>

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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="row mb-2 mt-3">
            <div class="col-md-12 text-center cursor-pointer" onclick="toggleBundleCourses('<?= $bundle_details['id']; ?>', '<?= count($bundle_details); ?>')">
                <i class="fas fa-angle-up d-block p-3"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="<?= site_url('bundle_details/'.$bundle_details['id'].'/'.slugify($bundle_details['title'])); ?>" class="btn-bundle-details"><?= site_phrase('bundle_details'); ?></a>

                <?php if(get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == 'invalid'): ?>
                    <a href="<?= site_url('course_bundles/buy/'.$bundle_details['id']); ?>" class="btn-buy-bundle"><?= site_phrase('buy'); ?></a>
                <?php elseif(get_bundle_validity($bundle_details['id'], $this->session->userdata('user_id')) == 'expire'): ?>
                    <a href="<?= site_url('course_bundles/buy/'.$bundle_details['id']); ?>" class="btn-buy-bundle"><?= currency($bundle_details['price']); ?> | <?= site_phrase('renew'); ?></a>
                <?php else: ?>
                    <a href="<?= site_url('home/my_bundles'); ?>" class="btn-buy-bundle"><?= site_phrase('purchased'); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>