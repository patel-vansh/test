<!--Paytm payment gateway addon-->
<form action="<?php echo site_url('addons/api/bundle_checkout'); ?>" method="post" class="paytm-form form hidden" style="display: block;">
    <hr class="border mb-4"> 
    <input type="hidden" name="total_price_of_checking_out" value="<?php echo htmlspecialchars($bundle_details['price']); ?>">
    <button type="submit" class="payment-button float-right"><?php echo get_phrase('pay_by_paytm'); ?></button>
</form>
