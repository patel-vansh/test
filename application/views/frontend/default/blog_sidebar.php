<?php
    $popular_categories = $this->crud_model->get_categories_with_blog_number(6);
    $latest_blogs = $this->crud_model->get_latest_blogs(3);
?>
<div style="position: sticky; top: 20px;" class="py-2">
    <h6 class="m-0 px-2 pb-3 fw-700 border-bottom"><?php echo site_phrase('search'); ?></h6>
    <form action="<?php echo site_url('blogs'); ?>" method="get" class="w-100">
        <div class="form-floating blog-search-box d-flex">
            <input class="form-control blog-search-input" value="<?php if(isset($search_string) && !empty($search_string)) echo $search_string; ?>" type="text" id="blog_search" placeholder="<?php echo site_phrase('search'); ?>" name="search" onkeyup="show_submit_button(this)" onblur="show_submit_button(this)">
            <button type="submit" id="blog_search_button" class="blog-search-button" <?php if(!empty($search_string))echo 'style="display: inline-block;"'; ?>><i class="fas fa-search"></i></button>
            <label for="blog_search"><?php echo site_phrase('enter_your_search_string'); ?></label>
        </div>
    </form>

    <h6 class="m-0 px-2 pt-5 pb-3 fw-700 border-bottom"><?php echo site_phrase('popular_categories'); ?></h6>
    <div class="list-group">
        <?php foreach($popular_categories as $popular_category): ?>
            <?php $blog_category = $this->crud_model->get_blog_categories($popular_category['blog_category_id'])->row_array(); ?>
            <a href="<?php echo site_url('blogs?category='.$blog_category['slug']); ?>" class="py-3 px-2 list-group-item-action <?php if(isset($_GET['category']) && $_GET['category'] == $blog_category['slug'])echo 'bg-light'; ?>" aria-current="true">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1"><?php echo $blog_category['title']; ?></h6>
                    <?php if($popular_category['blog_number'] > 0): ?>
                        <span class="badge rounded-pill bg-primary d-inline-flex align-items-center justify-content-center"><?php echo $popular_category['blog_number']; ?></span>
                    <?php endif; ?>
                </div>
                <small class="ellipsis-line-2"><?php echo $blog_category['subtitle']; ?></small>
            </a>
        <?php endforeach; ?>
        <a class="text-14px ps-2 my-2 text-muted" href="<?php echo site_url('blog/categories'); ?>"><?php echo site_phrase('all_categories'); ?></a>
    </div>

    <h6 class="m-0 px-2 pt-5 pb-3 fw-700 border-bottom"><?php echo site_phrase('latest_blogs'); ?></h6>
    <div class="list-group">
        <?php foreach($latest_blogs->result_array() as $latest_blog): ?>
            <a href="<?php echo site_url('blog/details/'.slugify($latest_blog['title']).'/'.$latest_blog['blog_id']); ?>" class="px-2 py-3 bg-transparent list-group-item list-group-item-action">
                <div>
                    <?php $blog_banner = 'uploads/blog/banner/'.$latest_blog['banner']; ?>
                    <?php if(file_exists($blog_banner) && is_file($blog_banner)): ?>
                        <img src="<?php echo base_url($blog_banner); ?>" class="card-img-top" alt="<?php echo $latest_blog['title']; ?>">
                    <?php else: ?>
                        <img src="<?php echo base_url('uploads/blog/banner/placeholder.png'); ?>" class="card-img-top" alt="<?php echo $latest_blog['title']; ?>">
                    <?php endif; ?>
                </div>
                <div>
                    <div class="d-flex w-100 justify-content-between">
                        <font face="Rasa" size="5px">
                                    <!--<a href="<?php echo site_url('blog/details/'.slugify($latest_blog['title']).'/'.$latest_blog['blog_id']); ?>"><?php echo $latest_blog['title']; ?></a>-->
                            <p style="line-height: 27px; margin-top: 15px; font-weight: 500" class="card-text ellipsis-line-2">
                                            <?php echo strip_tags(htmlspecialchars_decode($latest_blog['title'])); ?>
                            </p>
                        </font>
                    </div>
                    <!--<small class="text-muted ellipsis-line-2"><?php echo strip_tags(htmlspecialchars_decode($latest_blog['description'])); ?></small>-->
                    <font face="Rasa" size="4px"><p style="line-height: 20px; margin-top: 5px;" class="card-text ellipsis-line-3">
                                    <?php echo strip_tags(htmlspecialchars_decode($latest_blog['description'])); ?>
                                </p></font>
                    <p class="mt-2 mb-0 text-12px"><?php echo get_past_time($latest_blog['added_date']); ?></p>
                </div>
            </a>
        <?php endforeach; ?>
        <a class="text-14px ps-2 my-2 text-muted" href="<?php echo site_url('blogs'); ?>"><?php echo site_phrase('all_blogs'); ?></a>
    </div>
</div>

<script type="text/javascript">
    function show_submit_button(e){
        var search_string = $(e).val();
        if(search_string){
            $('#blog_search_button').show();
        }else{
            $('#blog_search_button').hide();
        }
    }
</script>