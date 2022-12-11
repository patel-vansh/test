<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-2">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('add_your_new_page'); ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<div class="row ">
    <div class="col-md-10">
    	<div class="card">
    		<div class="card-body">
    			<h4 class='mb-3'><?php echo get_phrase('page_information'); ?></h4>
		    	<form action="<?php echo site_url('admin/custom_page/add'); ?>" method="post" enctype="multipart/form-data">
		    		<div class="form-group">
		    			<label for="page_title"><?php echo get_phrase('page_title'); ?></label>
		    			<input type="text" class="form-control" name="page_title" id="page_title" placeholder="<?php echo get_phrase('enter_page_title'); ?>" required>
		    		</div>

		    		<div class="form-group">
		    			<label for="summernote-basic"><?php echo get_phrase('page_content'); ?></label>
		    			<textarea name="page_content" id="summernote-basic"></textarea>
		    		</div>

		    		<div class="form-group">
			    		<label for="button_title"><?php echo get_phrase('button_title'); ?></label>
			    		<input class="form-control" type="text" id="button_title" name="button_title">
			    	</div>

		    		<div class="form-group">
		    			<label for="button_position"><?php echo get_phrase('button_position'); ?></label>
		    			<select class="form-control select2" data-toggle="select2" name="button_position" id="button_position" required>
		    				<option value="footer"><?php echo get_phrase('footer'); ?></option>
		    				<option value="header"><?php echo get_phrase('header'); ?></option>
		    			</select>
		    		</div>

		    		<div class="form-group">
			    		<label for="page_url"><?php echo get_phrase('page_url'); ?></label>
			    		<div class="input-group">
			    			<div class="input-group-prepend">
			    				<span class="input-group-text"><?php echo site_url('page/'); ?></span>
			    			</div>
			    			<input class="form-control" type="text" id="page_url" name="page_url">
			    		</div>
			    	</div>

					<div class="form-group mt-4">
						<button class="btn btn-success"><?php echo get_phrase('add_page'); ?></button>
					</div>
		    	</form>
		    </div>
		</div>
	</div>
</div>