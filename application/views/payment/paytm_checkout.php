<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo get_phrase("paytm_checkout").' | '.get_settings('system_title'); ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="favicon" href="<?php echo base_url().'assets/frontend/default/img/icons/favicon.ico' ?>">
    <link rel="stylesheet" href="<?php echo base_url().'assets/frontend/default/css/bootstrap.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url().'assets/frontend/default/css/responsive.css'; ?>">
    <script src="<?php echo base_url('assets/backend/js/jquery-3.3.1.min.js'); ?>"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Card -->
            <div class="card mx-xl-5">

                <!-- Card body -->
                <div class="card-body">

                    <!-- Default form subscription -->
                    <form method="post" action="<?php echo site_url('addons/paytm/payThroughPaytm'); ?>">
                        <p class="h4 text-center py-4"><img src="<?php echo base_url('assets/payment/paytm.png'); ?>" height="40"></p>
                        <!-- Default input -->
                        <label for="user" class="grey-text font-weight-light"><?php echo get_phrase('user'); ?></label>
                        <input type="text" class="form-control" id="user" tabindex="4" maxlength="12" size="12" name="user" autocomplete="off" value="<?php echo htmlspecialchars($user_details['first_name']).' '.htmlspecialchars($user_details['last_name']); ?>" readonly style="background: none;">
                        <br>

                        <!-- Default input -->
                        <label for="amount_to_pay" class="grey-text font-weight-light"><?php echo get_phrase('amount_to_pay'); ?> (INR)</label>
                        <input type="text" class="form-control" id="amount_to_pay" tabindex="4" maxlength="12" size="12" name="amount_to_pay" autocomplete="off" value="<?php echo htmlspecialchars($amount_to_pay); ?>" readonly style="background: none;">

                        <!-- MOBILE APP VARIABLE -->
                        <input type="hidden" name="payment_request" value="<?php echo htmlspecialchars($payment_request); ?>">
                        <div class="text-center py-4 mt-3">
                            <button class="btn btn-outline-primary" type="submit"><?php echo get_phrase('pay_via_paytm'); ?></button>
                        </div>
                    </form>
                    <!-- Default form subscription -->
                </div>
                <!-- Card body -->
            </div>
            <!-- Card -->
        </div>
    </div>
</div>

<script src="<?php echo base_url().'assets/frontend/default/js/vendor/jquery-3.2.1.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/frontend/default/js/bootstrap.min.js'; ?>"></script>
</body>
</html>
