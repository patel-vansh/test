<div class="container">
	<div class="row justify-content-center mb-5">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12">
					<span class="payment-header-text float-left"><b><?php echo get_phrase('make_payment'); ?></b></span>
					<a href="<?= site_url('bundle_details/'.$bundle_details['id'].'/'.slugify($bundle_details['title'])); ?>" class="close-btn-light float-right"><i class="fa fa-times"></i></a>
				</div>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-3">
					<p class="pb-2 payment-header"><?php echo get_phrase('select_payment_gateway'); ?></p>

					<?php if ($paypal[0]->active != 0) : ?>
						<div class="row payment-gateway paypal" onclick="selectedPaymentGateway('paypal')">
							<div class="col-12">
								<img class="tick-icon paypal-icon" src="<?php echo base_url('assets/payment/tick.png'); ?>">
								<img class="payment-gateway-icon" src="<?php echo base_url('assets/payment/paypal.png'); ?>">
							</div>
						</div>
					<?php endif;
					if ($stripe[0]->active != 0) : ?>
					<div class="row payment-gateway stripe" onclick="selectedPaymentGateway('stripe')">
						<div class="col-12">
							<img class="tick-icon stripe-icon" src="<?php echo base_url('assets/payment/tick.png'); ?>">
							<img class="payment-gateway-icon" src="<?php echo base_url('assets/payment/stripe.png'); ?>">
						</div>
					</div>
				<?php endif; ?>

				<!--paystack payment gateway addon-->
				<?php
				if (addon_status('paystack') == 1) :
					include "paystack/paystack_payment_gateway.php";
				endif;
				?>

				<!--payumoney payment gateway addon-->
				<?php
				if (addon_status('payumoney') == 1) :
					include "payumoney/payumoney_payment_gateway.php";
				endif;
				?>
				<!--razorpay payment gateway addon-->
				<?php
				if (addon_status('razorpay') == 1) :
					include "razorpay/razorpay_payment_gateway.php";
				endif;
				?>
				<!--instamojo payment gateway addon-->
				<?php
				if (addon_status('instamojo') == 1) :
					include "instamojo/instamojo_payment_gateway.php";
				endif;
				?>
				<!--pagseguro payment gateway addon-->
				<?php
				if (addon_status('pagseguro') == 1) :
					include "pagseguro/pagseguro_payment_gateway.php";
				endif;
				?>
				<!--mercadopago payment gateway addon-->
				<?php
				if (addon_status('mercadopago') == 1) :
					include "mercadopago/mercadopago_payment_gateway.php";
				endif;
				?>
				<!--ccavenue payment gateway addon-->
				<?php
				if (addon_status('ccavenue') == 1) :
					include "ccavenue/ccavenue_payment_gateway.php";
				endif;
				?>
				<!--flutterwave payment gateway addon-->
				<?php
				if (addon_status('flutterwave') == 1) :
					include "flutterwave/flutterwave_payment_gateway.php";
				endif;
				?>
				<!--paytm payment gateway addon-->
				<?php
				if (addon_status('paytm') == 1) :
					include "paytm/paytm_payment_gateway.php";
				endif;
				?>
			</div>

			<div class="col-md-1"></div>

				<div class="col-md-8">
					<div class="w-100">
						<p class="pb-2 payment-header"><?php echo get_phrase('order'); ?> <?php echo get_phrase('summary'); ?></p>
						<p class="item float-left">
							<span class="count-item"><i class="fas fa-cubes"></i></span>
							<span class="item-title">
								<?php echo $bundle_details['title']; ?>
								<span class="item-price">
									<?php echo currency($bundle_details['price']); ?>
								</span>
							</span>
							<span class="by-owner">
								<?php echo get_phrase('by'); ?>
								<?php echo $instructor_details['first_name'] . ' ' . $instructor_details['last_name']; ?>
							</span>
						</p>
						<p class="float-left mt-4"><?= site_phrase('included_courses'); ?></p>
						<?php $counter = 0; $course_actual_price = 0; ?>
						<?php foreach ($bundle_courses as $course_details) :
							$counter++; ?>

							<p class="item float-left m-0 height-25">
								<span>-</span>
								<span class="text-muted text-12"><?php echo $course_details['title']; ?>
									<span class="item-price">
										<?php if ($course_details['discount_flag'] == 1) :
											$course_actual_price += $course_details['discounted_price'];
										else :
											$course_actual_price += $course_details['price'];
										endif; ?>
									</span>
								</span>
							</p>
						<?php endforeach; ?>
					</div>
					<div class="w-100 float-left mt-4 indicated-price">
						<div class="float-right total-price"><small class="text-muted"><strike><?php echo currency($course_actual_price); ?></strike></small> | <?php echo currency($bundle_details['price']); ?></div>
						<div class="float-right total"><?php echo get_phrase('total'); ?></div>
					</div>
					<div class="w-100 float-left">
						<form action="<?php echo site_url('addons/course_bundles/paypal_checkout'); ?>" method="post" class="paypal-form form">
							<input type="hidden" name="payment_request_from" value="from_web">
							<hr class="border mb-4">
							<input type="hidden" name="bundle_price" value="<?php echo currency($bundle_details['price']); ?>">
							<button type="submit" class="payment-button float-right"><?php echo get_phrase('pay_by_paypal'); ?></button>
						</form>

						<div class="stripe-form form">
							<hr class="border mb-4">
							<?php include "stripe/stripe_payment_gateway_form.php"; ?>
						</div>

						<!--Paystack payment gateway addon-->
						<?php
						if (addon_status('paystack') == 1) :
							include "paystack/paystack_payment_gateway_form.php";
						endif;
						?>

						<!--payumoney payment gateway addon-->
						<?php
						if (addon_status('payumoney') == 1) :
							include "payumoney/payumoney_payment_gateway_form.php";
						endif;
						?>

						<!--razorpay payment gateway addon-->
						<?php
						if (addon_status('razorpay') == 1) :
							include "razorpay/razorpay_payment_gateway_form.php";
						endif;
						?>

						<!--instamojo payment gateway addon-->
						<?php
						if (addon_status('instamojo') == 1) :
							include "instamojo/instamojo_payment_gateway_form.php";
						endif;
						?>

						<!--pagseguro payment gateway addon-->
						<?php
						if (addon_status('pagseguro') == 1) :
							include "pagseguro/pagseguro_payment_gateway_form.php";
						endif;
						?>

						<!--mercadopago payment gateway addon-->
						<?php
						if (addon_status('mercadopago') == 1) :
							include "mercadopago/mercadopago_payment_gateway_form.php";
						endif;
						?>

						<!--ccavenue payment gateway addon-->
						<?php
						if (addon_status('ccavenue') == 1) :
							include "ccavenue/ccavenue_payment_gateway_form.php";
						endif;
						?>

						<!--flutterwave payment gateway addon-->
						<?php
						if (addon_status('flutterwave') == 1) :
							include "flutterwave/flutterwave_payment_gateway_form.php";
						endif;
						?>

						<!--paytm payment gateway addon-->
						<?php
						if (addon_status('paytm') == 1) :
							include "paytm/paytm_payment_gateway_form.php";
						endif;
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>