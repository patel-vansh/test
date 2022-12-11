<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date    : 09 July, 2020
*  Academy
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Paytm extends CI_Controller
{

    protected $unique_identifier = "paytm";
    // constructor
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('addons/course_bundle_model');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        // CHECK IF THE ADDON IS ACTIVE OR NOT
        $this->check_addon_status();
    }

    function admin_login_check()
    {
        if (!$this->session->userdata('admin_login')) {
            redirect(site_url('home/login'), 'refresh');
        }
    }

    public function update_settings()
    {
        // check if admin is logged in
        $this->admin_login_check();

        $paytm_info = array();
        if (empty($this->input->post('PAYTM_MERCHANT_KEY')) || empty($this->input->post('PAYTM_MERCHANT_MID')) || empty($this->input->post('PAYTM_MERCHANT_WEBSITE')) || empty($this->input->post('INDUSTRY_TYPE_ID')) || empty($this->input->post('CHANNEL_ID'))) {
            $this->session->set_flashdata('error_message', get_phrase('nothing_can_not_be_empty'));
            redirect(site_url('admin/payment_settings'), 'refresh');
        }

        $paytm['PAYTM_MERCHANT_KEY']     = $this->input->post('PAYTM_MERCHANT_KEY');
        $paytm['PAYTM_MERCHANT_MID']     = $this->input->post('PAYTM_MERCHANT_MID');
        $paytm['PAYTM_MERCHANT_WEBSITE'] = $this->input->post('PAYTM_MERCHANT_WEBSITE');
        $paytm['INDUSTRY_TYPE_ID']       = $this->input->post('INDUSTRY_TYPE_ID');
        $paytm['CHANNEL_ID']             = $this->input->post('CHANNEL_ID');

        array_push($paytm_info, $paytm);


        $data['value']    =   json_encode($paytm_info);
        $this->db->where('key', 'paytm_keys');
        $this->db->update('settings', $data);

        $this->session->set_flashdata('flash_message', get_phrase('paytm_updated'));
        redirect(site_url('admin/payment_settings'), 'refresh');
    }

    public function checkout($payment_request = 'web')
    {
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'mobile') {
            redirect('home', 'refresh');
        }

        //checking price
        if ($this->session->userdata('total_price_of_checking_out') == $this->input->post('total_price_of_checking_out')) :
            $total_price_of_checking_out = $this->input->post('total_price_of_checking_out');
        else :
            $total_price_of_checking_out = $this->session->userdata('total_price_of_checking_out');
        endif;

        if ($total_price_of_checking_out > 0) {
            $page_data['payment_request'] = $payment_request;
            $page_data['user_details']    = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
            $page_data['amount_to_pay']   = $total_price_of_checking_out;
            $this->load->view('payment/paytm_checkout', $page_data);
        } else {
            redirect('home', 'refresh');
        }
    }

    public function payThroughPaytm()
    {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        // following files need to be included
        require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        $paytm_keys = get_settings('paytm_keys');
        $paytm_keys = json_decode($paytm_keys, true);

        $checkSum = "";
        $paramList = array();

        //$ORDER_ID = $_POST["ORDER_ID"];
        $user_id = $this->session->userdata('user_id');
        $ORDER_ID = "ORDS" . date("dmYHis") . "_" . $user_id;
        $CUST_ID  = "CUST" . $user_id;
        $INDUSTRY_TYPE_ID = $paytm_keys[0]["INDUSTRY_TYPE_ID"];
        $CHANNEL_ID = $paytm_keys[0]["CHANNEL_ID"];

        //checking price
        if ($this->session->userdata('total_price_of_checking_out') == $this->input->post('amount_to_pay')) :
            $TXN_AMOUNT = $this->input->post('amount_to_pay');
        else :
            $TXN_AMOUNT = $this->session->userdata('total_price_of_checking_out');
        endif;

        // Inserting data into pending_payment table
        $this->db->insert("pending_payment", array(
            "user_id" => $user_id,
            "course_ids" => json_encode($this->crud_model->getCartItems()),
            "order_id" => $ORDER_ID,
            "bundle_ids" => json_encode($this->crud_model->getCartBundleItems()),
            "coupon" => $this->crud_model->get_coupon_details_by_code($this->session->userdata('applied_coupon'))->row_array()['code']
        ));

        //MOBILE APP VARIABLE
        $payment_request = $this->input->post('payment_request');

        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = $ORDER_ID;
        $paramList["CUST_ID"] = $CUST_ID;
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
        $paramList["CALLBACK_URL"] = site_url("addons/paytm/pgResponse/" . $payment_request);

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

        $page_data['paramList'] = $paramList;
        $page_data['checkSum'] = $checkSum;
        $this->load->view('payment/paytm_merchant_checkout', $page_data);
    }

    public function pgResponse($payment_request)
    {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");

        // following files need to be included
        require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";

        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

        //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.
        
        $order_id = $_POST['ORDERID'];

        $this->db->where('order_id', $order_id);
        $pending_payment = $this->db->get('pending_payment')->result_array();

        $user_id = $pending_payment[0]['user_id'];

        if (isset($user_id) || $user_id == "" || $user_id <= 0) {
            $query = $this->db->get_where('users', array('id' => $user_id));
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $this->session->set_userdata('custom_session_limit', (time()+604800));
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('role_id', $row->role_id);
                $this->session->set_userdata('role', get_user_role('user_role', $row->id));
                $this->session->set_userdata('name', $row->first_name . ' ' . $row->last_name);
                $this->session->set_userdata('is_instructor', $row->is_instructor);
                $this->session->set_userdata('user_login', '1');
                $this->session->set_userdata('cart_items', json_decode($pending_payment[0]['course_ids']));
            }
        }
        
        $coupon = $pending_payment[0]['coupon'];
        if (isset($coupon)) {
            $this->session->set_userdata('applied_coupon', $coupon);
        }

        $this->db->where('id', $pending_payment[0]['id']);
        $this->db->delete('pending_payment');

        if ($this->session->userdata('total_price_of_checking_out') == $this->input->post('amount_to_pay')) :
            $amount_paid = $this->input->post('amount_to_pay');
        else :
            $amount_paid = $this->session->userdata('total_price_of_checking_out');
        endif;

        if ($isValidChecksum == "TRUE") {
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                //THESE ARE THE TASKS HAVE TO AFTER A PAYMENT
                $transaction_id = $_POST['TXNID'];
                $this->crud_model->enrol_student($user_id, $transaction_id);
                $this->crud_model->course_purchase($user_id, 'paytm', $amount_paid, $transaction_id);
                $this->email_model->course_purchase_notification($user_id, 'paytm', $amount_paid);
                
                $this->course_bundle_model->bundle_purchase('Paytm', $transaction_id, $user_id);
                $this->email_model->bundle_purchase_notification($user_id);
                
                $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                if ($payment_request == 'mobile') :
                    $course_id = $this->crud_model->getCartItems();
                    redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
                else :
                    $this->db->where(array('id'=>$user_id))->update('users', array('cart_course'=>"[]", 'cart_bundle'=>"[]"));
                    redirect('home/my_courses', 'refresh');
                endif;
            } else {
                $this->session->set_flashdata('error_message', site_phrase('an_error_occurred_during_payment'));
                redirect('home', 'refresh');
            }

            if (isset($_POST) && count($_POST) > 0) {
                foreach ($_POST as $paramName => $paramValue) {
                    // YOU CAN PRINT PARAMNAMES AND PARAMVALUE HERE
                }
            }
        }elseif($payment_request == 'mobile'){
            echo site_phrase('an_error_occurred_during_payment');
        }else{
            $this->session->set_flashdata('error_message', site_phrase('Checksum_mismatched'));
            redirect('home', 'refresh');
        }
    }


    // CHECK IF THE ADDON IS ACTIVE OR NOT. IF NOT REDIRECT TO DASHBOARD
    public function check_addon_status()
    {
        $checker = array('unique_identifier' => $this->unique_identifier);
        $this->db->where($checker);
        $addon_details = $this->db->get('addons')->row_array();
        if ($addon_details['status']) {
            return true;
        } else {
            redirect(site_url(), 'refresh');
        }
    }















///////////////////BUNDLE///////////////////////
    public function bundle_checkout($payment_request = 'web'){
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'mobile') {
            redirect('home', 'refresh');
        }

        //checking price
        if ($this->session->userdata('checkout_bundle_price') > 0) {
            $page_data['payment_request'] = $payment_request;
            $page_data['user_details']    = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
            $page_data['amount_to_pay']   = $this->session->userdata('checkout_bundle_price');
            $this->load->view('bundle_payment/paytm/paytm_checkout', $page_data);
        } else {
            $this->session->set_flashdata('error_message', get_phrase('amount_less_than_1'));
            redirect(site_url('bundle_details/'.$this->session->userdata('checkout_bundle_id')), 'refresh');
        }
    }

    public function bundlePayThroughPaytm()
    {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        // following files need to be included
        require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        $paytm_keys = get_settings('paytm_keys');
        $paytm_keys = json_decode($paytm_keys, true);

        $checkSum = "";
        $paramList = array();

        //$ORDER_ID = $_POST["ORDER_ID"];
        $user_id = $this->session->userdata('user_id');
        $ORDER_ID = "ORDS" . rand(10000, 99999999) . "_" . $user_id;
        $CUST_ID  = "CUST" . $user_id;
        $INDUSTRY_TYPE_ID = $paytm_keys[0]["INDUSTRY_TYPE_ID"];
        $CHANNEL_ID = $paytm_keys[0]["CHANNEL_ID"];

        $bundle_id = $this->session->userdata('checkout_bundle_id');

        //checking price
        $TXN_AMOUNT = $this->session->userdata('checkout_bundle_price');

        // Inserting data into pending_payment table
        $this->db->insert("pending_payment", array(
            "user_id" => $user_id,
            "course_ids" => json_encode(array($bundle_id)),
            "order_id" => $ORDER_ID,
            "is_course" => 0
        ));

        //MOBILE APP VARIABLE
        $payment_request = $this->input->post('payment_request');

        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = $ORDER_ID;
        $paramList["CUST_ID"] = $CUST_ID;
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
        $paramList["CALLBACK_URL"] = site_url("addons/paytm/bundlePgResponse/" . $payment_request);

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

        $page_data['paramList'] = $paramList;
        $page_data['checkSum'] = $checkSum;
        $this->load->view('bundle_payment/paytm/paytm_merchant_checkout', $page_data);
    }

    public function bundlePgResponse($payment_request)
    {
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");

        // following files need to be included
        require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";

        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

        //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

        $order_id = $paramList['ORDERID'];
        
        $this->db->where('order_id', $order_id);
        $this->db->where('is_course', 0);
        $pending_payment = $this->db->get('pending_payment')->result_array();

        $user_id = $pending_payment[0]['user_id'];
        $bundle_id = json_decode($pending_payment[0]['course_ids'])[0];
        $bundle_price = $this->course_bundle_model->get_bundle_details($bundle_id)->row()->price;
        if (isset($user_id) || $user_id == "" || $user_id <= 0) {
            $query = $this->db->get_where('users', array('id' => $user_id));
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $this->session->set_userdata('custom_session_limit', (time()+604800));
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('role_id', $row->role_id);
                $this->session->set_userdata('role', get_user_role('user_role', $row->id));
                $this->session->set_userdata('name', $row->first_name . ' ' . $row->last_name);
                $this->session->set_userdata('is_instructor', $row->is_instructor);
                $this->session->set_userdata('user_login', '1');
                $this->session->set_userdata('cart_items', array());
                $this->session->set_userdata('checkout_bundle_id', $bundle_id);
                $this->session->set_userdata('checkout_bundle_price', $bundle_price);
            }
        }

        $this->db->where('id', $pending_payment[0]['id']);
        $this->db->delete('pending_payment');

        $amount_paid = $this->session->userdata('checkout_bundle_price');

        if ($isValidChecksum == "TRUE") {
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                //THESE ARE THE TASKS HAVE TO AFTER A PAYMENT
                $transaction_id = $_POST['TXNID'];
                $this->course_bundle_model->bundle_purchase('paytm', $amount_paid, $transaction_id, null);
                $this->email_model->bundle_purchase_notification($user_id, 'paytm', $amount_paid);

                if ($payment_request == 'mobile') :
                    $course_id = $this->session->userdata('cart_items');
                    redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
                else :
                    $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                    $this->session->set_userdata('checkout_bundle_price', null);
                    $this->session->set_userdata('checkout_bundle_id', null);
                    redirect('home/my_bundles', 'refresh');
                endif;
            } else {
                $this->session->set_flashdata('error_message', site_phrase('an_error_occurred_during_payment'));
                redirect('home', 'refresh');
            }

            if (isset($_POST) && count($_POST) > 0) {
                foreach ($_POST as $paramName => $paramValue) {
                    // YOU CAN PRINT PARAMNAMES AND PARAMVALUE HERE
                }
            }
        }elseif($payment_request == 'mobile'){
            echo site_phrase('an_error_occurred_during_payment');
        }else{
            $this->session->set_flashdata('error_message', site_phrase('Checksum_mismatched'));
            redirect('home', 'refresh');
        }
    }
}
