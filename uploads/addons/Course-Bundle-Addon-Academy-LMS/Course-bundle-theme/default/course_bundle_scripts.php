<script type="text/javascript">
	"use strict";
	function toggleBundleCourses(bundle_id, limit){
		if($('#course_of_bundle_'+bundle_id).html() == ''){
			$('#gif_loader_'+bundle_id).html("<div class='w-100 py-4 my-2 text-center'><img class='w-20-px' src='<?= base_url('assets/global/gif/page-loader-2.gif'); ?>'></div>");
			$.ajax({
				type: 'post',
				url: '<?= site_url('addons/course_bundles/load_more_courses_in_bundle/'); ?>'+bundle_id+'/'+limit,
				success: function (response){
					$('#gif_loader_'+bundle_id).hide();
					$('#course_of_bundle_'+bundle_id).html(response);
				}
			});
		}

		$('.bundle-arrow-down').show()
		if($('#course_of_bundle_'+bundle_id).hasClass('closed')){
			$('.bundle-slider').addClass('closed');
			$('#course_of_bundle_'+bundle_id).removeClass('closed');
			$('#gif_loader_'+bundle_id).removeClass('closed');
			$('#bundle_arrow_down_'+bundle_id).hide();
		}else{
			$('#course_of_bundle_'+bundle_id).addClass('closed');
			$('#gif_loader_'+bundle_id).addClass('closed');
		}
	}

	function getBundlesBySearchString(search_string) {
	    $.ajax({
	        type : 'POST',
	        url : '<?php echo site_url('addons/course_bundles/my_bundles_by_search_string'); ?>',
	        data : {search_string : search_string},
	        success : function(response){
	            $('#my_bundles_area').html(response);
	        }
	    });
	}

	function bundle_rating(bundle_id) {
		var rating = $('#user_bundle_rating').val();
		var comment = $('#user_bundle_comment').val();
	    $.ajax({
	        type : 'POST',
	        url : '<?php echo site_url('addons/course_bundles/update_bundle_rating/'); ?>'+bundle_id,
	        data : {rating : rating, comment : comment},
	        success : function(response){
	            if(response == 'success'){
	            	toastr.success('<?= site_phrase("rating_updated_successfully"); ?>.');
	            }
	            if(response == 0){
	            	toastr.error('<?= site_phrase("please_select_a_rating_greater_than_0"); ?>.');
	            }
	            if(response == 'expired'){
	            	toastr.error('<?= site_phrase("your_subscription_date_is_over_please_renew_your_bundle"); ?>.');
	            }
	        }
	    });
	}

	function update_bundle_rating(bundle_id){
		var rating = $('#select_rating'+bundle_id).val();
		var comment = $('#enter_comment'+bundle_id).val();
		$.ajax({
			type: "post",
			url: "<?= site_url('addons/course_bundles/update_bundle_rating/') ?>"+bundle_id,
			data: {rating:rating, comment:comment},
			success: function(respons){
				window.location.reload();
			}
		});
	}
</script>
