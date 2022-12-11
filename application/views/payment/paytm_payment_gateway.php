<!--paytm gateway addon-->
<?php if(addon_status('paytm')): ?>
    <div class="row payment-gateway paytm" onclick="selectedPaytmGateway()" style="border: 2px solid rgb(0, 208, 79);">
        <div class="col-12">
            <img class="tick-icon paytm-icon" src="<?php echo base_url('assets/payment/tick.png'); ?>" style="display: inline;">
            <img class="payment-gateway-icon" src="<?php echo base_url('assets/payment/paytm.png'); ?>">
        </div>
    </div>
<?php endif; ?> 
 
<script type="text/javascript"> 
function selectedPaytmGateway() {
    $(".payment-gateway").css("border", "2px solid #D3DCDD");
    $('.tick-icon').hide();
    $('.form').hide();

    $(".paytm").css("border", "2px solid #00D04F");
    $('.paytm-icon').show();
    $('.paytm-form').show();
}
</script> 
