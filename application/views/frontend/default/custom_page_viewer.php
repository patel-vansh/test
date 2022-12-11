<section class="category-header-area bg-blue">
    <div class="image-placeholder-1"></div>
    <div class="container-lg breadcrumb-container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item display-6 fw-bold">
                <a href="<?php echo site_url('home'); ?>">
                    <?php echo site_phrase('home'); ?>
                </a>
            </li>
            <li class="breadcrumb-item active text-light display-6 fw-bold">
                <?php echo site_phrase($page_title); ?>
            </li>
          </ol>
        </nav>
    </div>
</section>

<div class="container-xl pt-4">
	<?php echo remove_js(htmlspecialchars_decode($page_content)); ?>
</div>