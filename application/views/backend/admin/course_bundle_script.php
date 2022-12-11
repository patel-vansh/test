<script type="text/javascript">
  "use strict";
  function current_price_of_selected_courses(){
    var selected_course_id = $('#select_bundle_courses').val();
    $.ajax({
      type: 'post',
      url: '<?= site_url('addons/bundle/current_price_of_selected_courses'); ?>',
      data: {selected_course_id : selected_course_id},
      success: function(response){
        $('#current_price_of_the_courses').html(response);
      }
    });
  }

  function update_date_range(){
      var x = $("#selectedValue").html();
      $("#date_range").val(x);
  }
</script>