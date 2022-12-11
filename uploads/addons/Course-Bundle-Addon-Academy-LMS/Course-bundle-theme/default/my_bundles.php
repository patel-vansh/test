<?php include "profile_menus.php"; ?>

<section class="my-courses-area">
    <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <h5><?= site_phrase('total').' '.count($my_bundles->result_array()).' '.site_phrase('bundles'); ?></h5>
          </div>
          <div class="col-lg-6">
              <div class="my-course-search-bar">
                  <form action="javascript:;">
                      <div class="input-group">
                          <input type="text" class="form-control" placeholder="<?php echo site_phrase('search_my_bundles'); ?>" onkeyup="getBundlesBySearchString(this.value)">
                          <div class="input-group-append">
                              <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
        </div>
        <div class="row no-gutters" id = "my_bundles_area">
          <?php include "user_purchase_bundle.php"; ?>
        </div>
    </div>
</section>

<?php include "course_bundle_scripts.php"; ?>