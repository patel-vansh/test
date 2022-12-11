<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bundle extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->model('addons/course_bundle_model');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        if ($this->session->userdata('admin_login') != true) {
            redirect(site_url('login'), 'refresh');
        }
    }

    function add_bundle_form(){
        $page_data['page_title'] = get_phrase('add_course_bundle');
        $page_data['page_name'] = 'add_bundle';
        $this->load->view('backend/index', $page_data);
    }

    function add_course_bundle(){
        $this->course_bundle_model->add_course_bundle();
        redirect(site_url('addons/bundle/manage_bundle'), 'refresh');
    }

    function edit_bundle_form($id = ""){
        $page_data['bundle'] = $this->course_bundle_model->get_bundle($id)->row_array();
        $page_data['page_title'] = get_phrase('edit_course_bundle');
        $page_data['page_name'] = 'edit_bundle';
        $this->load->view('backend/index', $page_data);
    }

    function update_course_bundle($id = ""){
        $this->course_bundle_model->update_course_bundle($id);
        redirect(site_url('addons/bundle/manage_bundle'), 'refresh');
    }

    function manage_bundle($param1 = ''){
        
        $page_data['bundles'] = $this->course_bundle_model->get_bundle()->result_array();
        $page_data['page_title'] = get_phrase('manage_course_bundle');
        $page_data['page_name'] = 'manage_course_bundle';
        $this->load->view('backend/index', $page_data);
    }

    function bundle_update_status($bundle_id = "", $status = ""){
        if($status == 'active'){
            $this->course_bundle_model->bundle_update_status($bundle_id, 1);
        }else{
            $this->course_bundle_model->bundle_update_status($bundle_id, 0);
        }
        $this->session->set_flashdata('flash_message', get_phrase('bundle_status_updated_successfully'));
        redirect(site_url('addons/bundle/manage_bundle'), 'refresh');
    }

    function bundle_delete($bundle_id = ""){
        $this->course_bundle_model->bundle_delete($bundle_id);
        $this->session->set_flashdata('flash_message', get_phrase('bundle_deleted_successfully'));
        redirect(site_url('addons/bundle/manage_bundle'), 'refresh');
    }

    function current_price_of_selected_courses(){
        if(html_escape($this->input->post('selected_course_id')) != null){
            echo get_phrase('current_price_of_the_courses_is').' '.currency($this->course_bundle_model->current_price_of_selected_courses());
        }else{
            echo get_phrase('current_price_of_the_courses_is').' '.currency('0');
        }
    }

    function subscription_report($status = "", $param1 = ""){
        if($param1 == 'filter'):
            $page_data['filter_bundle_payments'] = $this->course_bundle_model->filter_bundle_payment();
            $date_range_arr = explode(" - ",$this->input->post('date_range'));
            $page_data['start_time'] = strtotime($date_range_arr[0]);
            $page_data['end_time'] = strtotime($date_range_arr[1]);
        else:
            $page_data['start_time'] = strtotime(date('F 01, Y'));
            $page_data['end_time'] = strtotime(date("F t, Y"));
        endif;

        $page_data['search_bundle_id'] = html_escape($this->input->post('bundle_id'));
        $page_data['search_user_id'] = html_escape($this->input->post('user_id'));

        $page_data['page_title'] = get_phrase('subscription_report');
        if($status == 'active'){
            $page_data['page_name'] = 'active_bundle_subscription_report';
        }else{
            $page_data['page_name'] = 'expire_bundle_subscription_report';
        }
        $this->load->view('backend/index', $page_data);
    }

    function invoice($payment_id = ""){
        $page_data['bundle_payment'] = $this->course_bundle_model->get_bundle_payment($payment_id)->row_array();
        $page_data['page_title'] = get_phrase('invoice');
        $page_data['page_name'] = 'bundle_invoice';
        $this->load->view('backend/index', $page_data);
    }
}