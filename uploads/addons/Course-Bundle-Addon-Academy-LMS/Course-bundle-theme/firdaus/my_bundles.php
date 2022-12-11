<section id="hero_in" class="courses">
    <div class="banner-img" style="background: url(<?= base_url("uploads/system/".get_frontend_settings('banner_image')); ?>) center center no-repeat;"></div>
    <div class="wrapper">
        <div class="container">
            <h1 class="fadeInUp"><span></span><?php echo site_phrase('my_bundles'); ?></h1>
        </div>
    </div>
</section>

<section class="my-courses-area">
    <div class="container">
        <div class="row mt-4">
          <div class="col-lg-6">
            <h5><?= site_phrase('total').' '.count($my_bundles->result_array()).' '.site_phrase('bundles'); ?></h5>
          </div>
          <div class="col-lg-6">
              <div class="my-course-search-bar">
                  <form action="javascript:;">
                      <div class="input-group">
                          <input type="text" class="form-control" placeholder="<?php echo site_phrase('search_my_bundles'); ?>" onkeyup="getBundlesBySearchString(this.value)">
                          <div class="input-group-append">
                              <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
        </div>
        <div class="row no-gutters py-4" id = "my_bundles_area">
          <?php include "user_purchase_bundle.php"; ?>
        </div>
    </div>
</section>

<?php include "course_bundle_scripts.php"; ?>