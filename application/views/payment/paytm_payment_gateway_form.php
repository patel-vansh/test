<!--Paytm payment gateway addon-->
<?php if(addon_status('paytm') == 1): ?>
    <form action="<?php echo site_url('addons/paytm/checkout'); ?>" method="post" class="paytm-form form hidden" style="display: block;">
        <hr class="border mb-4">
        <input type="hidden" name="total_price_of_checking_out" value="<?php echo htmlspecialchars($total_price_of_checking_out); ?>">
        <button type="submit" class="payment-button float-right"><?php echo get_phrase('pay_by_paytm'); ?></button>
    </form>
<?php endif; ?>
 