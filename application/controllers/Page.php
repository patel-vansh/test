<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        $this->load->library('session');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        $this->user_model->check_session_data();       
    }

    function index($page_suffix = ""){
        $this->db->where('page_url', $page_suffix);
        $custom_page = $this->db->get('custom_page')->row_array();


        $page_data['page_url'] = $custom_page['page_url'];
        $page_data['page_content'] = $custom_page['page_content'];
        $page_data['page_title'] = $custom_page['page_title'];
        $page_data['page_name'] = 'custom_page_viewer';
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

}