<!-- Hero Section -->
<div class="container">
  <div class="bg-primary rounded mt-4">
      <div class="py-4 px-6">
        <h2 class="display-4 text-white">
          <?php echo site_phrase('my_bundles'); ?>
          <i class="fas fa-cubes float-right font-70" width="100"></i>
        </h2>
      </div>
  </div>
</div>
<!-- End Hero Section -->

<?php include "profile_menus.php"; ?>

<section class="my-courses-area pt-0">
    <div class="container">
        <div class="row align-items-baseline">
            <div class="col-lg-6 pb-3">
                <div class="my-course-filter-bar filter-box">
                  <h5><?= site_phrase('total').' '.count($my_bundles->result_array()).' '.site_phrase('bundles'); ?></h5>
                </div>
            </div>
            <div class="col-lg-6 pb-5">
              <div class="my-course-search-bar">
                <form action="javascript:;">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" id="search_my_courses_value" placeholder="<?php echo site_phrase('search_course_bundles'); ?>" onkeyup="getBundlesBySearchString(this.value)">
                        <div class="input-group-append">
                            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        </div>
        <div class="row" id = "my_bundles_area">
          <?php include "user_purchase_bundle.php"; ?>
        </div>
    </div>
</section>

<?php include "course_bundle_scripts.php"; ?>