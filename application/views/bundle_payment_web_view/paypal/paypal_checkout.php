<!DOCTYPE html>
<html lang="en">
<head>
  <title>Paypal | <?php echo get_settings('system_name');?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?php echo base_url('assets/payment/css/stripe.css');?>"
  rel="stylesheet">
  <link name="favicon" type="image/x-icon" href="<?php echo base_url('uploads/system/'.get_frontend_settings('favicon'));?>" rel="shortcut icon" />
  <style type="text/css">
    .loader_modal{
      position: fixed;
      display: none;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #42477077;
      z-index: 1000;
      color: #fff;
      text-align: center;
      padding-top: 100px;
    }
    #paypal-button{
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <?php
  $paypal_keys = get_settings('paypal');
  $paypal = json_decode($paypal_keys);
  ?>
  <!--required for getting the stripe token-->
  <div class="package-details">
    <strong><?php echo site_phrase('student_name');?> | <?php echo $user_details['first_name'].' '.$user_details['last_name'];?></strong> <br>
    <strong><?php echo site_phrase('amount_to_pay');?> | <?php echo $amount_to_pay;?></strong> <br>
    <div id="paypal-button"></div><br>
  </div>

  <script src="<?php echo base_url('assets/backend/js/jquery-3.3.1.min.js'); ?>"></script>
  <script src="https://www.paypalobjects.com/api/checkout.js"></script>

  <script>
  paypal.Button.render({
    env: '<?php echo $paypal[0]->mode;?>', // 'sandbox' or 'production'
    style: {
      label: 'paypal',
      size:  'medium',    // small | medium | large | responsive
      shape: 'rect',     // pill | rect
      color: 'blue',     // gold | blue | silver | black
      tagline: false
    },
    client: {
      sandbox:    '<?php echo $paypal[0]->sandbox_client_id;?>',
      production: '<?php echo $paypal[0]->production_client_id;?>'
    },

    commit: true, // Show a 'Pay Now' button

    payment: function(data, actions) {
      return actions.payment.create({
        payment: {
          transactions: [
            {
              amount: { total: '<?php echo $amount_to_pay;?>', currency: '<?php echo get_settings('paypal_currency'); ?>' }
            }
          ]
        }
      });
    },

    onAuthorize: function(data, actions) {
      // executes the payment
      return actions.payment.execute().then(function() {
        // PASSING TO CONTROLLER FOR CHECKING
        var redirectUrl = '<?php echo site_url('addons/course_bundles/paypal_payment/'.$user_details['id'].'/'.$amount_to_pay);?>'+'/'+data.paymentID+'/'+data.paymentToken+'/'+data.payerID;
        $('#loader_modal').fadeIn(50);
          window.location = redirectUrl+'/<?= $payment_request_from; ?>';
      });
    }

  }, '#paypal-button');
</script>

<div id="loader_modal" class="loader_modal">Please wait....</div>
</body>
</html>
