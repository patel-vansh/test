<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Course_bundle_model extends CI_Model {

    function __construct() {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
    
    function is_bundle_purchased($user_id, $bundle_id) {
        $query = $this->db->where(array("bundle_id"=>$bundle_id, "user_id"=>$user_id))->get('bundle_payment');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_bundle($id = ""){
    	$this->db->order_by('id', 'desc');
		if($id > 0){
			$this->db->where('id', $id);
		}
    	return $this->db->get('course_bundle');
    }

    function get_bundle_by_search_string($search_string = ""){
        $this->db->or_like('title', $search_string);
        $this->db->or_like('bundle_details', $search_string);
        $this->db->or_like('subscription_limit', $search_string);
        $this->db->or_like('price', $search_string);
        $this->db->where('status', 1);
        return $this->db->get('course_bundle');
    }

    function get_active_bundle($id = ""){
        $this->db->order_by('id', 'desc');
        if($id > 0){
            $this->db->where('id', $id);
        }
        $this->db->where('status', 1);
        return $this->db->get('course_bundle');
    }

    function get_bundle_details($id = ""){
        return $this->db->get_where('course_bundle', array('id'=>$id));
    }

    function get_courses_by_bundle_id($bundle_id = "", $limit = ""){
        $bundle_course_ids = $this->db->get_where('course_bundle', array('id' => $bundle_id))->row('course_ids');
        $course_id_array = json_decode($bundle_course_ids);
        unset($course_id_array[0], $course_id_array[1], $course_id_array[2]);
        $this->db->where_in('id', $course_id_array);
        $this->db->where('status', 'active');
        return $this->db->get('course');
    }

    function get_all_courses_by_bundle_id($bundle_id = ""){
        $bundle_course_ids = $this->db->get_where('course_bundle', array('id' => $bundle_id))->row('course_ids');
        $course_id_array = json_decode($bundle_course_ids);
        $this->db->where_in('id', $course_id_array);
        $this->db->where('status', 'active');
        return $this->db->get('course');
    }
    
    function get_original_price_of_bundle($bundle_id = "") {
        $course_ids = $this->get_all_courses_by_bundle_id($bundle_id);
        $original_price = 0;
        foreach ($course_ids->result_array() as $course) {
            if ($course['is_free_course'] != 1) {
                if ($course['discount_flag'] != 1) {
                    $original_price += intval($course["price"]);
                } else {
                    $original_price += intval($course["discounted_price"]);
                }
            }
        }
        return $original_price;
    }

    function current_price_of_selected_courses(){
    	$current_price = 0;
    	foreach(html_escape($this->input->post('selected_course_id')) as $course_id):
            $this->db->or_where('id', $course_id);
        endforeach;
        $courses = $this->db->get('course')->result_array();

        foreach($courses as $course):
            if($course['is_free_course'] !=1):
            	if($course['discount_flag'] == 1){
            		$current_price += $course['discounted_price'];
            	}else{
            		$current_price += $course['price'];
            	}
            endif;
    	endforeach;

    	return $current_price;
    }

    function add_course_bundle(){
    	$data['title'] = html_escape($this->input->post('title'));
    	$data['course_ids'] = json_encode(html_escape($this->input->post('course_ids')));
    	$data['price'] = html_escape($this->input->post('price'));
    	$data['subscription_limit'] = html_escape($this->input->post('subscription_limit'));
    	$data['banner'] = md5(rand(10000, 20000)).'.jpg';
        $data['date_added'] = strtotime(date('d M Y'));
    	$data['bundle_details'] = $this->input->post('bundle_details');
        $data['user_id'] = $this->session->userdata('user_id');
    	$data['status'] = 1;

    	if(is_numeric($data['price']) == true && $data['subscription_limit'] == true){
    		$this->db->insert('course_bundle', $data);
    		move_uploaded_file($_FILES['banner']['tmp_name'], 'uploads/course_bundle/banner/' . $data['banner']);
    		$this->session->set_flashdata('flash_message', get_phrase('course_bundle_create_successfully'));
		}else{
			$this->session->set_flashdata('error_message', get_phrase('please_enter_numeric_data'));
		}
    	
    }

    function update_course_bundle($id = ""){
    	$data['title'] = html_escape($this->input->post('title'));
    	$data['course_ids'] = json_encode(html_escape($this->input->post('course_ids')));
    	$data['price'] = html_escape($this->input->post('price'));
    	$data['subscription_limit'] = html_escape($this->input->post('subscription_limit'));
        $data['bundle_details'] = $this->input->post('bundle_details');
    	if(empty($_FILES['banner']['tmp_name']) != 1):
    		$data['banner'] = md5(rand(10000, 20000)).'.jpg';
    	endif;

    	if(is_numeric($data['price']) == true && $data['subscription_limit'] == true){
    		$this->db->where('id', $id);
    		$this->db->update('course_bundle', $data);

    		if(empty($_FILES['banner']['tmp_name']) != 1):
    			move_uploaded_file($_FILES['banner']['tmp_name'], 'uploads/course_bundle/banner/' . $data['banner']);
				unlink('uploads/course_bundle/banner/'.html_escape($this->input->post('old_image')));
    		endif;

    		$this->session->set_flashdata('flash_message', get_phrase('course_bundle_updated_successfully'));
		}else{
			$this->session->set_flashdata('error_message', get_phrase('please_enter_numeric_data'));
		}
    	
    }

    function bundle_update_status($bundle_id = "", $status = ""){
    	$data['status'] = $status;
    	$this->db->where('id', $bundle_id);
    	$this->db->update('course_bundle', $data);
    }

    function bundle_delete($bundle_id = ""){
    	unlink('uploads/course_bundle/banner/'.$this->course_bundle_model->get_bundle($bundle_id)->row('banner'));
    	$this->db->where('id', $bundle_id);
    	$this->db->delete('course_bundle');
    }

    function sum_of_bundle_rating($bundle_id = ""){
        $this->db->select_sum('rating');
        return $this->db->get_where('bundle_rating', array('bundle_id' => $bundle_id))->row('rating');
    }

    function get_bundle_wise_ratings($bundle_id = ""){
        $this->db->where('bundle_id', $bundle_id);
        return $this->db->get('bundle_rating');
    }

    function get_user_bundle_rating($user_id = "", $bundle_id = ""){
        $this->db->where('user_id', $user_id);
        $this->db->where('bundle_id', $bundle_id);
        return $this->db->get('bundle_rating');
    }
    
    function get_bundle_specific_rating($bundle_id = "", $rate = ""){
        $count_star_rating_number = $this->db->get_where('bundle_rating', array('bundle_id' => $bundle_id, 'rating' => $rate))->num_rows();
        if($count_star_rating_number > 0){
            return $count_star_rating_number;
        }else{
            return 0;
        }
    }

    function get_bundle_percentage_of_specific_rating($bundle_id = "", $rate = ""){
        $count_bundle_ratings = $this->get_bundle_wise_ratings($bundle_id)->num_rows();
        $count_star_rating_number = $this->db->get_where('bundle_rating', array('bundle_id' => $bundle_id, 'rating' => $rate))->num_rows();
        if($count_bundle_ratings > 0){
            return round($count_star_rating_number / ($count_bundle_ratings / 100),2);
        }else{
            return 0;
        }
    }

    function bundle_purchase($method = "", $transaction_id = "", $user_id, $coupon = ""){
        // $bundle_price = $this->session->userdata('checkout_bundle_price');
        // $bundle_id = $this->session->userdata('checkout_bundle_id');
        $bundle_ids = $this->crud_model->getCartBundleItems($user_id);
        
        if (!isset($coupon) || $coupon == "")
            $coupon = $this->session->userdata('applied_coupon');
        
        foreach ($bundle_ids as $bundle_id) {
            $data['user_id'] = $user_id;
            $data['bundle_creator_id'] = $this->get_bundle($bundle_id)->row('user_id');
            $data['bundle_id'] = $bundle_id;
            $data['payment_method'] = $method;
            $data['session_id'] = null;
            $data['transaction_id'] = $transaction_id;
            $data['amount'] = $this->get_bundle($bundle_id)->row('price');
            $data['coupon'] = $coupon;
            $data['date_added'] = strtotime(date('d M Y'));
            $this->db->insert('bundle_payment', $data);
        }
    }

    function my_bundles(){
        $data['user_id'] = $this->session->userdata('user_id');
        $bundle_payments = $this->db->get_where('bundle_payment', $data)->result_array();

        $arr = array(0);
        foreach($bundle_payments as $bundle_payment):
            array_push($arr, $bundle_payment['bundle_id']);
        endforeach;
        $this->db->where_in('id', $arr);
        return $this->db->get('course_bundle');

    }


    function my_bundles_trash(){
        $data['user_id'] = $this->session->userdaTa('user_id');
        $bundle_payments1 = $this->db->get_where('bundle_payment', $data)->result_array();
        $bundle_payments2 = $this->db->get_where('bundle_payment_trash', $data)->result_array();
        $bundle_payments = array_merge($bundle_payments1,$bundle_payments2);
        $arr = array(0);
        foreach($bundle_payments as $bundle_payment):
            array_push($arr, $bundle_payment['bundle_id']);
        endforeach;
        $this->db->where_in('id', $arr);
        return $this->db->get('course_bundle');

    }

    function my_bundles_by_search_string($search_string = ""){
        $data['user_id'] = $this->session->userdaTa('user_id');
        $arr = array(0);
        $bundle_payments = $this->db->get_where('bundle_payment', $data)->result_array();
        foreach($bundle_payments as $bundle_payment):
            array_push($arr, $bundle_payment['bundle_id']);
            //$this->db->or_where('id', $bundle_payment['bundle_id']);
        endforeach;
        $this->db->where_in('id', $arr);
        $this->db->like('title', $search_string);
        return $this->db->get('course_bundle');
    }

    function update_bundle_rating($bundle_id = "", $user_id = ""){
        $query = $this->db->get_where('bundle_rating', array('bundle_id' => $bundle_id, 'user_id' => $user_id));
        $data['rating'] = html_escape($this->input->post('rating'));
        $data['comment'] = html_escape($this->input->post('comment'));

        if($query->num_rows() > 0){
            $this->db->where('bundle_id', $bundle_id);
            $this->db->where('user_id', $user_id);
            $this->db->update('bundle_rating', $data);
        }else{
            $data['user_id'] = $user_id;
            $data['bundle_id'] = $bundle_id;
            $data['date_added'] = strtotime(date('d M Y'));
            $this->db->insert('bundle_rating', $data);
        }
    }

    function user_wise_purchase_history($user_id = ""){
        $this->db->where('user_id', $user_id);
        return $this->db->get('bundle_payment');
    }
    function bundle_wise_purchase_history($bundle_id = "", $user_id = ""){
        $this->db->where('bundle_id', $bundle_id);
        $this->db->where('user_id', $user_id);
        return $this->db->get('bundle_payment');
    }

    function get_bundle_payment($payment_id = ""){
        if($payment_id > 0){
            $this->db->where('id', $payment_id);
        }
        $this->db->order_by('id', 'desc');
        return $this->db->get('bundle_payment');
    }

    function filter_bundle_payment(){
        $bundle_id = html_escape($this->input->post('bundle_id'));
        $user_id = html_escape($this->input->post('user_id'));

        $date_range_arr = explode(" - ",$this->input->post('date_range'));
        $this->db->where('date_added >=', strtotime($date_range_arr[0]));
        $this->db->where('date_added <=', strtotime($date_range_arr[1]));
        if($bundle_id != ""){
            $this->db->where('bundle_id', $bundle_id);
        }
        if($user_id != ""){
            $this->db->where('user_id', $user_id);
        }


        return $this->db->get('bundle_payment');
    }

    function get_course_by_ids($course_ids = array(0)){
        $this->db->where_in('id', $course_ids);
        return $this->db->get('course');
    }

    function get_bundle_purchase_date($bundle_id = "", $user_id = ""){
        if($user_id == ""){
            $user_id = $this->session->userdata('user_id');
        }

        $this->db->limit(1);
        $this->db->order_by('id', 'desc');
        $this->db->where('bundle_id', $bundle_id);
        $this->db->where('user_id', $user_id);
        return $this->db->get('bundle_payment')->row('date_added');
    }

    function get_number_of_enrolled_student($bundle_id = ""){
        $this->db->select('user_id');
        $this->db->distinct('user_id');
        $this->db->where('bundle_id', $bundle_id);
        return $this->db->get('bundle_payment')->num_rows();
    }
}