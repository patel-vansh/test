<?php
// Stripe API configuration
$stripe_keys = get_settings('stripe_keys');
$bundle_id = $this->session->userdata('checkout_bundle_id');
$userName = $user_details['first_name'].' '.$user_details['last_name'];
$currency = get_settings('stripe_currency');


// Convert product price to cent
$stripeAmount = round($amount_to_pay*100, 2);

$values = json_decode($stripe_keys);
if ($values[0]->testmode == 'on') {
    define('STRIPE_API_KEY', $values[0]->secret_key);
    define('STRIPE_PUBLISHABLE_KEY', $values[0]->public_key);
} else {
    define('STRIPE_API_KEY', $values[0]->secret_live_key);
    define('STRIPE_PUBLISHABLE_KEY', $values[0]->public_live_key);
}

define('STRIPE_SUCCESS_URL', site_url('addons/course_bundles/stripe_payment/'.$user_details['id'].'/'.$payment_request_from));
if ($payment_request_from == "from_web") {
    define('STRIPE_CANCEL_URL', site_url('course_bundles/buy/'.$bundle_id));
}else{
    define('STRIPE_CANCEL_URL', site_url('course_bundles/buy/stripe_payment_mobile/' . $bundle_id . '/' . $user_details['id'] . '/error'));
}

// Include Stripe PHP library
require_once APPPATH.'libraries/Stripe/init.php';

// Set API key
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

$response = array(
    'status' => 0,
    'error' => array(
        'message' => 'Invalid Request!'
    )
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $request = json_decode($input);
}

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode($response);
    exit;
}

if(!empty($request->checkoutSession)){
    // Create new Checkout Session for the order
    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'product_data' => [
                        'name' => $bundle_details['title'],
                        'images' => [base_url('uploads/course_bundle/banner/'.$bundle_details['banner'])]
                    ],
                    'unit_amount' => $stripeAmount,
                    'currency' => $currency,
                ],
                'quantity' => 1
            ]],
            'mode' => 'payment',
            'success_url' => STRIPE_SUCCESS_URL.'/{CHECKOUT_SESSION_ID}',
            'cancel_url' => STRIPE_CANCEL_URL,
        ]);
    }catch(Exception $e) {
        $api_error = $e->getMessage();
    }

    if(empty($api_error) && $session){
        $response = array(
            'status' => 1,
            'message' => 'Checkout Session created successfully!',
            'sessionId' => $session['id']
        );
    }else{
        $response = array(
            'status' => 0,
            'error' => array(
                'message' => 'Checkout Session creation failed! '.$api_error
            )
        );
    }
}

// Return response
echo json_encode($response);
