<?php
require APPPATH . '/libraries/TokenHandler.php';
//include Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class Messagesformobile extends REST_Controller {

    protected $token;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        // creating object of TokenHandler class at first
        $this->tokenHandler = new TokenHandler();
        header('Content-Type: application/json');
    }
    
    public function send_message_get() {
        $sender    = intval($_GET['sender']);
        $receiver  = intval($_GET['receiver']);
        $timestamp = time();
        $message   = $_GET['message'];
        
        $check1 = $this->db->get_where('message_thread', array('sender' => $sender, 'receiver' => $receiver))->num_rows();
        $check2 = $this->db->get_where('message_thread', array('receiver' => $sender, 'sender' => $receiver))->num_rows();
        if ($check1 == 0 && $check2 == 0) {
            $message_thread_code                        = substr(md5(rand(100000000, 20000000000)), 0, 15);
            $data_message_thread['message_thread_code'] = $message_thread_code;
            $data_message_thread['sender']              = $sender;
            $data_message_thread['receiver']            = $receiver;
            $this->db->insert('message_thread', $data_message_thread);
        }
        if ($check1 > 0) {
            $message_thread_code = $this->db->get_where('message_thread', array('sender' => $sender, 'receiver' => $receiver))->row()->message_thread_code;
        }
        if ($check2 > 0) {
            $message_thread_code = $this->db->get_where('message_thread', array('receiver' => $sender, 'sender' => $receiver))->row()->message_thread_code;
        }
        
        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);
        echo json_encode(array("status"=>"Success", "message"=>$message_thread_code));
    }
    
    public function read_messages_get() {
        $message_thread_code = $_GET['message_thread_code'];
        $user_id             = intval($_GET['user_id']);
        $this->crud_model->read_thread_code_messages($message_thread_code, $user_id);
        echo json_encode(array("status"=>"Success", "message"=>"Messages Red"));
    }
    
    public function messages_get() {
        $message_thread_code = $_GET['message_thread_code'];
        $user_id             = intval($_GET['user_id']);
        $messages_list       = $this->crud_model->get_messages_from_thread_code($message_thread_code)->result_array();
        foreach ($messages_list as $key => $message) {
            $message['self_img_url'] = $this->user_model->get_user_image_url($user_id);
            $messages_list[$key]     = $message;
        }
        echo json_encode(array("status"=>"Success", "message"=>$messages_list));
    }
    
    public function get_all_admins_and_instructors_get() {
        $instructors_list = $this->get_instructors_list()->result_array();
        $admins_list      = $this->user_model->get_admins()->result_array();
        $merged_list      = array_merge($instructors_list, $admins_list);
        $res = array();
        foreach ($merged_list as $user) {
            $_res              = array();
            $_res['id']        = $user['id'];
            $_res['user_name'] = $user['first_name'] . " " . $user['last_name'];
            $_res['img_url']   = $this->user_model->get_user_image_url($user['id']);
            $res[]             = $_res;
        }
        echo json_encode(array("status"=>"Success", "message"=>$res));
    }
    
    public function get_admins_and_instructors_threads_get() {
        $user_id          = intval($_GET['user_id']);
        $instructors_list = $this->get_instructors_list()->result_array();
        $admins_list      = $this->user_model->get_admins()->result_array();
        $merged_list      = array_merge($instructors_list, $admins_list);
        $res = array();
        $ids = array();
        foreach ($merged_list as $user) {
            $_res              = array();
            $ids[]             = intval($user['id']);
            $_res['id']        = $user['id'];
            $_res['user_name'] = $user['first_name'] . " " . $user['last_name'];
            $_res['img_url']   = $this->user_model->get_user_image_url($user['id']);
            $res[]             = $_res;
        }
        $this->db->where('sender', $user_id);
        $this->db->where_in('receiver', $ids);
        $message_thread_1 = $this->db->get('message_thread')->result_array();
        $this->db->where('receiver', $user_id);
        $this->db->where_in('sender', $ids);
        $message_thread_2 = $this->db->get('message_thread')->result_array();
        $message_threads  = array_merge($message_thread_1, $message_thread_2);
        foreach($message_threads as $key => $thread) {
            if ($thread['sender'] == $user_id) {
                $thread['self']               = "sender";
                $thread['other_user_details'] = $this->get_other_user_details($res, $thread['receiver']);
            } else {
                $thread['self']               = "receiver";
                $thread['other_user_details'] = $this->get_other_user_details($res, $thread['sender']);
            }
            $message_threads[$key] = $thread;
        }
        
        $admin_thread_available = false;
        foreach($message_threads as $key => $thread) {
            if ($thread['sender'] == 1 || $thread['receiver'] == 1) {
                $admin_thread_available = true;
            }
        }
        if (!$admin_thread_available) {
            $message_thread_code                        = substr(md5(rand(100000000, 20000000000)), 0, 15);
            $new_message_thread['message_thread_code'] = $message_thread_code;
            $new_message_thread['sender']              = $user_id;
            $new_message_thread['receiver']            = 1;
            $this->db->insert('message_thread', $new_message_thread);
        }
        echo json_encode(array("status"=>"Success", "message"=>$message_threads));
    }
    
    function get_other_user_details($users_list, $user_id) {
        $res = array();
        foreach($users_list as $key => $user) {
            if ($user['id'] == $user_id) {
                $res = $user;
                break;
            }
        }
        return $res;
    }
    
    public function get_instructors_list() {
        return $this->db->get_where("users", array("is_instructor" => 1));
    }
}