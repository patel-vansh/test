<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{ 

    public function __construct()
    {
        parent::__construct(); 
        /* ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL); */ 
        $this->load->database(); 
        $this->load->library('session');
        $this->load->model('addons/course_bundle_model'); 
        $this->load->model('user_model');
        $this->load->model('crud_model');
        $this->load->model('api_model');
        header('Content-Type: application/json');
    }

    public function my_courses_get() {
        $response = array();
        $auth_token = $_GET['auth_token'];
        $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
    
        if ($logged_in_user_details['user_id'] > 0) {
          $response = $this->api_model->my_courses_get($logged_in_user_details['user_id']);
        }else{
    
        }
        return $this->set_response($response, REST_Controller::HTTP_OK);
  }


    public function get_my_course_bundles() {
        $response = array();
        $user_id = $_GET['user_id'];
        if ($user_id > 0) 
        {
          $my_bundles_1 = $this->db->select('bundle_id, id')->where(array('user_id'=>$user_id))->from('bundle_payment')->get()->result_array();
          $my_bundles_2 = $this->db->select('bundle_id, id')->where(array('user_id'=>$user_id))->from('bundle_payment_trash')->get()->result_array();
    
            $my_bundles = array_merge($my_bundles_1,$my_bundles_2);
            foreach ($my_bundles as $my_bundle) 
            {
                $bundle_id = $my_bundle['bundle_id'];
                $sql = $this->db->query("SELECT * FROM `course_bundle` WHERE `id`='$bundle_id'");
                $row = $sql->row_array();
                $course_ids_of_bundle = json_decode($row['course_ids']);
                foreach ($course_ids_of_bundle as $course_id_of_bundle) {
                    $course_id_of_bundle = intval($course_id_of_bundle);
                    $course_details = $this->db->get_where('course', array('id'=>$course_id_of_bundle))->row_array();
                    $number_of_ratings = $this->crud_model->get_ratings('course', $course_id_of_bundle)->num_rows();
                    if ($number_of_ratings > 0) {
                        $rating = ceil($total_rating / $number_of_ratings);
                    } else {
                        $rating = 0;
                    }
                    
                    if ($course_details['multi_instructor'] == 1) {
                        $course_details['user_id'] = explode(',', $course_details['user_id']);
                        $instructor_names = array();
                        foreach ($course_details['user_id'] as $instructor_id) {
                            $instructor_details = $this->user_model->get_all_user($instructor_id)->row_array();
                            $instructor_names[] = $instructor_details['first_name'] . ' ' . $instructor_details['last_name'];
                        }
                    } else {
                        $instructor_details = $this->user_model->get_all_user($course_details['user_id'])->row_array();
                        $instructor_names[] = $instructor_details['first_name'] . ' ' . $instructor_details['last_name'];
                        $course_details['user_id'] = array($course_details['user_id']);
                    }
                    $total_enrollment = $this->crud_model->enrol_history($course_id_of_bundle)->num_rows();
                    $link = site_url('home/course/' . slugify($course_details['title']) . '/' . $course_details['id']);
        
                    $total_number_of_completed_lessons = $this->api_model->get_completed_number_of_lesson($user_id, 'course', $course_id_of_bundle);
        
        
                    if($my_bundle['expired']=='1')
                    { 
                        $status_c = "Expired"; 
                        $e_date = date('Y-m-d', strtotime($row['created'])); 
                    }
                    else
                    { 
                        
                        $queryss = $this->db->query("SELECT `subscription_limit` FROM `course_bundle` WHERE `id`='$bundle_id'");
                        $rowss = $queryss->row_array();
                        $subscription_limit = '+'.$rowss['subscription_limit'].'day';
                        $status_c = "Active"; 
                        $e_date = date('Y-m-d', strtotime($subscription_limit, strtotime($row['created']))); 
                    }
                    $thumbnail = $this->api_model->get_image('course_thumbnail', $course_id_of_bundle);
                    $response[] = array(
                    'id' => $course_details['id'],
                    'title' => $course_details['title'],
                    'subscription_limit' => $course_details['subscription_limit'],
                    'short_description' => $course_details['short_description'],
                    'description' => $course_details['description'],
                    'language' => $course_details['language'],
                    'category_id' => $course_details['category_id'],
                    'sub_category_id' => $course_details['sub_category_id'],
                    'section' => '',
                    'price' => $course_details['price'],
                    'discount_flag' => $course_details['discount_flag'],
                    'discounted_price' => $course_details['discounted_price'],
                    'level' => $course_details['level'],
                    'user_id' =>  $course_details['user_id'],
                    'thumbnail' => $thumbnail,
                    'video_url' => $course_details['video_url'],
                    'date_added' =>$course_details['date_added'],
                    'last_modified' => $course_details['last_modified'],
                    'course_type' => $course_details['course_type'],
                    'is_top_course' => $course_details['is_top_course'],
                    'is_admin' => $course_details['is_admin'],
                    'status' => $course_details['is_admin'],
                    'course_overview_provider' =>$course_details['course_overview_provider'],
                    'meta_keywords' => $course_details['meta_keywords'],
                    'meta_description' => $course_details['meta_description'],
                    'is_free_course' => $course_details['is_free_course'],
                    'multi_instructor' => $course_details['multi_instructor'],
                    'creator' => $course_details['creator'],
                    'rating' => $rating,
                    'number_of_ratings' => $number_of_ratings,
                    'instructor_name' => $instructor_names,
                    'total_enrollment' => $total_enrollment,
                    'shareable_link' => $link,
                    'completion' => round(course_progress($course_id_of_bundle, $user_id)),
                    'totalNumberOfLessons' => $this->crud_model->get_lessons('course', $course_id_of_bundle)->num_rows(),
                    'totalNumberOfCompletedLessons' =>$total_number_of_completed_lessons,
                    'expired' => $status_c,
                    'expired_date' => $e_date
                    );
                }
    
            }
         }
         else
         {
         } 
        $this->sendResponse("1","Get Data Successfully",$response); 
    }  



   public function get_my_course_bundles_bckup()
    {
         
        $user_id = $_POST['user_id']; 
        $sq_bundle_pay = $this->db->query("SELECT `bundle_id` FROM `bundle_payment` WHERE `user_id`='$user_id'");
        $sq_bundle_pay_trash = $this->db->query("SELECT `bundle_id` FROM `bundle_payment_trash` WHERE `user_id`='$user_id'");

        $resu_pay = $sq_bundle_pay->result_array();
        $resu_pay1 = $sq_bundle_pay_trash->result_array();

        $array =array_merge($resu_pay1,$resu_pay);
        $idsss = array();
        for ($i=0; $i < count($array); $i++) 
        { 
           $array_2 = $array[$i];
            
            for ($j=0; $j < count($array_2); $j++) 
            { 
                $idsss[] =  $array_2['bundle_id'];
            }
        }
        $idss = implode(',',$idsss);
          
         
        $sql = $this->db->query("SELECT * FROM `course_bundle` WHERE  id IN ($idss) ");
        $rows = $sql->result_array();
        $course_bundle = array();
        foreach($rows as $row)
        {   
            $created_id = $row['user_id'];
            $instructor_details = $this->user_model->get_all_user($created_id)->row_array();
              
             $course_array = json_decode($row['course_ids']); 
             $count = count($course_array);
             $my_courses = array();

             for ($i=0; $i < $count ; $i++) 
             { 
                $course_id = $course_array[$i]; 
                $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
                array_push($my_courses, $course_details);
            }
            $my_courses = $this->course_data($my_courses);
            foreach ($my_courses as $key => $my_course) {
                if (isset($my_course['id']) && $my_course['id'] > 0) {
                    $my_courses[$key]['completion'] = round(course_progress($my_course['id'], $user_id));
                    $my_courses[$key]['total_number_of_lessons'] = $this->crud_model->get_lessons('course', $my_course['id'])->num_rows();
                    $my_courses[$key]['total_number_of_completed_lessons'] = $this->get_completed_number_of_lesson($user_id, 'course', $my_course['id']);
                }
            }




            $ratings = $this->course_bundle_model->get_bundle_wise_ratings($row['id']);
            $enrolled = $ratings->num_rows();

            $bundle_total_rating = $this->course_bundle_model->sum_of_bundle_rating($row['id']);
            if ($ratings->num_rows() > 0) {
                $bundle_average_ceil_rating = ceil($bundle_total_rating / $ratings->num_rows());
            }else {
                $bundle_average_ceil_rating = 0;
            } 




            $instructor_name = $instructor_details['first_name'].' '.$instructor_details['last_name'];

            $id = $row['id'];
            $check_sql1 = $this->db->query("SELECT `expired`,`created` FROM `bundle_payment` WHERE `user_id`='$user_id' AND `bundle_id`='$id'");
            $check_rows = $check_sql1->row_array();
            if(empty($check_rows))
            {
                $check_sql1 = $this->db->query("SELECT `expired`,`created` FROM `bundle_payment_trash` WHERE `user_id`='$user_id' AND `bundle_id`='$id'");
                $check_rows = $check_sql1->row_array();
            }
            $expired = $check_rows['expired']; 
            $created = $check_rows['created'];

             if($expired=='1')
             {
                
                $status_c = "Expired"; 
                $e_date = date('Y-m-d', strtotime($check_rows['created'])); 
                
             }
             else
             { 
                $status_c = "Active"; 
                $e_date = date('Y-m-d', strtotime('+365 day', strtotime($check_rows['created']))); 
             }


             $course_bundle[] = array(
                'id' => $row['id'],
                'admin_id' => $row['user_id'],
                'title' => $row['title'],
                'banner' => $row['banner'],
                'course_data' => $my_courses,
                'subscription_limit' => $row['subscription_limit'],
                'price' => $row['price'], 
                'bundle_details' => $row['bundle_details'],
                'status' => $row['status'],
                'created' => $row['created'],
                'already_purchased' => 'YES',
                'ratings' =>$bundle_average_ceil_rating,
                'enrolled' =>$enrolled,
                'instructor_name' => $instructor_name,
                'expired' => $status_c,
                'expried_date' => $e_date,
             );
            
        }
        $this->sendResponse("1","Get Data Successfully",$course_bundle);

    }  
    
    public function get_course_bundle_by_id() {
        $user_id = $_GET['user_id'];
        $id = $_GET['id'];
        
        $sql = $this->db->query("SELECT * FROM `course_bundle` WHERE `status`='1' AND `id`='$id'");
        $row = $sql->result_array()[0];
        $created_id = $row['user_id'];
        $instructor_details = $this->user_model->get_all_user($created_id)->row_array();

         $course_array = json_decode($row['course_ids']); 
         $count = count($course_array);
         $course_details = array();

         for ($i=0; $i < $count ; $i++) 
         { 
             $course_id = $course_array[$i];
             $sq_course = $this->db->query("SELECT * FROM `course` WHERE `id`='$course_id'");
             $thumbnail = $this->crud_model->get_course_thumbnail_url($course_id); 
             $rrrr = $sq_course->row_array();

             //Course rating start
            $this->db->where('id', $course_id);
            $this->db->where('status', 'active');
            $course = $this->db->get('course')->row_array();
            if($course == null) continue;

            //course ratings
            $total_rating =  $this->crud_model->get_ratings('course', $course_id, true)->row()->rating;
            $number_of_ratings = $this->crud_model->get_ratings('course', $course_id)->num_rows();
            if ($number_of_ratings > 0) {
                $average_ceil_rating = ceil($total_rating / $number_of_ratings);
            }else {
                $average_ceil_rating = 0;
            }
            //Course rating End
            
            $is_added_to_wishlist = 0;
            if (in_array($rrrr['id'], $this->crud_model->getWishLists($user_id))) {
                $is_added_to_wishlist = 1;
            }

             $sq_course_array = array(
                    "id" => $rrrr['id'],
                    "title" => $rrrr['title'],
                    "short_description" => $rrrr['short_description'],
                    "description" => $rrrr['description'],
                    "outcomes" => $rrrr['outcomes'],
                    "language" => $rrrr['language'],
                    "category_id" => $rrrr['category_id'],
                    "sub_category_id" => $rrrr['sub_category_id'],
                    "section" => $rrrr['section'],
                    "requirements" => $rrrr['requirements'],
                    "price" => $rrrr['price'],
                    "discount_flag" => $rrrr['discount_flag'],
                    "discounted_price" => $rrrr['discounted_price'],
                    "level" => $rrrr['level'],
                    "user_id" => $rrrr['user_id'],
                    "thumbnail" => $thumbnail,
                    "video_url" => $rrrr['video_url'],
                    "date_added" => $rrrr['date_added'],
                    "last_modified" => $rrrr['last_modified'],
                    "course_type" => $rrrr['course_type'],
                    "is_top_course" => $rrrr['is_top_course'],
                    "is_admin" => $rrrr['is_admin'],
                    "is_added_to_wishlist" => $is_added_to_wishlist,
                    "status" => $rrrr['status'],
                    "course_overview_provider" => $rrrr['course_overview_provider'],
                    "meta_keywords" => $rrrr['meta_keywords'],
                    "meta_description" => $rrrr['meta_description'],
                    "is_free_course" => $rrrr['is_free_course'],
                    "multi_instructor" => $rrrr['multi_instructor'],
                    "creator" => $rrrr['creator'],
                    "rating" => $average_ceil_rating,
                    );

             $course_details[] = $sq_course_array;   
         }
         
         $course_bundle_id =$row['id'];
         
         if($user_id != '')
         {
             $already_s = $this->db->query("SELECT * FROM `bundle_payment` WHERE `user_id`='$user_id' AND `bundle_id`='$course_bundle_id'");
             $already_num = $already_s->num_rows();
             if($already_num==0)
             {
                $status = 'NO';
             }
             else
             {
                $status = 'YES';
             } 
         }
         else
         {
            $status = 'NO';
         }

        $ratings = $this->course_bundle_model->get_bundle_wise_ratings($row['id']);
        $enrolled = $ratings->num_rows();
        $bundle_total_rating = $this->course_bundle_model->sum_of_bundle_rating($row['id']);
        if ($ratings->num_rows() > 0) {
            $bundle_average_ceil_rating = ceil($bundle_total_rating / $ratings->num_rows());
        }else {
            $bundle_average_ceil_rating = 0;
        }
        
        if (in_array($row['id'], $this->crud_model->getCartBundleItems($user_id))) {
    		 $is_added_to_cart = "1";
    	} else {
    		  $is_added_to_cart = "0";
    	}
        
        //$course_thumbnail_url = base_url().'uploads/thumbnails/course_thumbnails/';
        $instructor_name = $instructor_details['first_name'].' '.$instructor_details['last_name'];
        $course_bundle[] = array(
            'id' => $row['id'],
            'admin_id' => $row['user_id'],
            'title' => $row['title'],
            'banner' => $row['banner'],
            'course_data' => $course_details,
            'subscription_limit' => $row['subscription_limit'],
            'price' => $row['price'], 
            'bundle_details' => $row['bundle_details'],
            'status' => $row['status'],
            'created' => $row['created'],
            'already_purchased' => $status,
            'is_added_to_cart'=>$is_added_to_cart,
            'ratings' =>$bundle_average_ceil_rating,
            'enrolled' =>$enrolled,
            'instructor_name' => $instructor_name,
         );
        $this->sendResponse("1","Get Data Successfully",$course_bundle);
    }

     
    public function get_course_bundles()
    {
        $limit = $_POST['limit'];
        $user_id = $_POST['user_id']; 
        if($limit == '')
        {

        }
        else
        {
            $limit = "ORDER BY `id` ASC LIMIT $limit";
        }

        if($user_id == '')
        {
            $user_id = 0;
        }
         
        $sql = $this->db->query("SELECT * FROM `course_bundle` WHERE `status`='1' $limit");
        $rows = $sql->result_array();
        $course_bundle = array();
        foreach($rows as $row)
        {
        $created_id = $row['user_id'];
        $instructor_details = $this->user_model->get_all_user($created_id)->row_array();

         $course_array = json_decode($row['course_ids']); 
         $count = count($course_array);
         $course_details = array();

         for ($i=0; $i < $count ; $i++) 
         { 
             $course_id = $course_array[$i];
             $sq_course = $this->db->query("SELECT * FROM `course` WHERE `id`='$course_id'");
             $thumbnail = $this->crud_model->get_course_thumbnail_url($course_id); 
             $rrrr = $sq_course->row_array();

             //Course rating start
            $this->db->where('id', $course_id);
            $this->db->where('status', 'active');
            $course = $this->db->get('course')->row_array();
            if($course == null) continue;

            //course ratings
            $total_rating =  $this->crud_model->get_ratings('course', $course_id, true)->row()->rating;
            $number_of_ratings = $this->crud_model->get_ratings('course', $course_id)->num_rows();
            if ($number_of_ratings > 0) {
                $average_ceil_rating = ceil($total_rating / $number_of_ratings);
            }else {
                $average_ceil_rating = 0;
            }
            //Course rating End
            
            $is_added_to_wishlist = 0;
            if (in_array($rrrr['id'], $this->crud_model->getWishLists($user_id))) {
                $is_added_to_wishlist = 1;
            }

             $sq_course_array = array(
                    "id" => $rrrr['id'],
                    "title" => $rrrr['title'],
                    "short_description" => $rrrr['short_description'],
                    "description" => $rrrr['description'],
                    "outcomes" => $rrrr['outcomes'],
                    "language" => $rrrr['language'],
                    "category_id" => $rrrr['category_id'],
                    "sub_category_id" => $rrrr['sub_category_id'],
                    "section" => $rrrr['section'],
                    "requirements" => $rrrr['requirements'],
                    "price" => $rrrr['price'],
                    "discount_flag" => $rrrr['discount_flag'],
                    "discounted_price" => $rrrr['discounted_price'],
                    "level" => $rrrr['level'],
                    "user_id" => $rrrr['user_id'],
                    "thumbnail" => $thumbnail,
                    "video_url" => $rrrr['video_url'],
                    "date_added" => $rrrr['date_added'],
                    "last_modified" => $rrrr['last_modified'],
                    "course_type" => $rrrr['course_type'],
                    "is_top_course" => $rrrr['is_top_course'],
                    "is_admin" => $rrrr['is_admin'],
                    "is_added_to_wishlist" => $is_added_to_wishlist,
                    "status" => $rrrr['status'],
                    "course_overview_provider" => $rrrr['course_overview_provider'],
                    "meta_keywords" => $rrrr['meta_keywords'],
                    "meta_description" => $rrrr['meta_description'],
                    "is_free_course" => $rrrr['is_free_course'],
                    "multi_instructor" => $rrrr['multi_instructor'],
                    "creator" => $rrrr['creator'],
                    "rating" => $average_ceil_rating,
                    );

             $course_details[] = $sq_course_array;   
         }
         
         $course_bundle_id =$row['id'];
         
         if($user_id != '')
         {
             $already_s = $this->db->query("SELECT * FROM `bundle_payment` WHERE `user_id`='$user_id' AND `bundle_id`='$course_bundle_id'");
             $already_num = $already_s->num_rows();
             if($already_num==0)
             {
                $status = 'NO';
             }
             else
             {
                $status = 'YES';
             } 
         }
         else
         {
            $status = 'NO';
         }

        $ratings = $this->course_bundle_model->get_bundle_wise_ratings($row['id']);
        $enrolled = $ratings->num_rows();
        $bundle_total_rating = $this->course_bundle_model->sum_of_bundle_rating($row['id']);
        if ($ratings->num_rows() > 0) {
            $bundle_average_ceil_rating = ceil($bundle_total_rating / $ratings->num_rows());
        }else {
            $bundle_average_ceil_rating = 0;
        }
        //$course_thumbnail_url = base_url().'uploads/thumbnails/course_thumbnails/';
        $instructor_name = $instructor_details['first_name'].' '.$instructor_details['last_name'];
         $course_bundle[] = array(
            'id' => $row['id'],
            'admin_id' => $row['user_id'],
            'title' => $row['title'],
            'banner' => $row['banner'],
            'course_data' => $course_details,
            'subscription_limit' => $row['subscription_limit'],
            'price' => $row['price'], 
            'bundle_details' => $row['bundle_details'],
            'status' => $row['status'],
            'created' => $row['created'],
            'already_purchased' => $status,
            'ratings' =>$bundle_average_ceil_rating,
            'enrolled' =>$enrolled,
            'instructor_name' => $instructor_name,
         );
        
        }
        $this->sendResponse("1","Get Data Successfully",$course_bundle);

    }  

////////////////////// Paytm Payment Getway Start //////////////////////////
    function buy()
    {  

        $user_id = $_GET['user_id'];
        $bundle_id = $_GET['bundle_id'];  
        $this->session->set_userdata('user_id', $user_id); 
        $this->session->set_userdata('bundle_id', $bundle_id); 
        if($user_id == "" || $bundle_id == "") 
        {
            $redirect = base_url('addons/Api/failer');
            redirect($redirect);
            //$this->sendResponse("1","something is wrong",array());   
        }  
        $page_data['bundle_details'] = $this->course_bundle_model->get_bundle_details($bundle_id)->row_array();
        $page_data['instructor_details'] = $this->user_model->get_all_user($page_data['bundle_details']['user_id'])->row_array();
        $page_data['bundle_courses'] = $this->course_bundle_model->get_all_courses_by_bundle_id($page_data['bundle_details']['id'])->result_array();  
        $page_data['page_name'] = "payment_gateway";
        $page_data['user_id'] = $user_id;
        $page_data['page_title'] = site_phrase('buy_course_bundle');  

        $this->session->set_userdata('checkout_bundle_price', $page_data['bundle_details']['price']);
        $this->session->set_userdata('checkout_bundle_id', $page_data['bundle_details']['id']);
 
        $this->load->view('new_bundle_payment_web_view/index', $page_data);
    }  
 
  
    public function bundle_checkout($payment_request = 'web'){ 
 
         $user_id = $this->session->userdata('user_id');  
        if ($this->session->userdata('checkout_bundle_price') > 0)   
        {   
            $page_data['payment_request'] = $payment_request; 
            $page_data['user_details']    = $this->user_model->get_user($user_id)->row_array();
            $page_data['amount_to_pay']   = $this->session->userdata('checkout_bundle_price');
            $this->load->view('bundle_payment_web_view/paytm/paytm_checkout', $page_data);
        } else { 
            $redirect = base_url('addons/Api/failer');
            redirect($redirect);
            //$this->sendResponse("0","amount less than 1",array()); 
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
        $ORDER_ID = "ORDS" . rand(10000, 99999999);
        $user_id = $this->session->userdata('user_id');
        $CUST_ID  = "CUST" . $user_id;
        $INDUSTRY_TYPE_ID = $paytm_keys[0]["INDUSTRY_TYPE_ID"];
        $CHANNEL_ID = $paytm_keys[0]["CHANNEL_ID"]; 

        //checking price
        $TXN_AMOUNT = $this->session->userdata('checkout_bundle_price');
 
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
        $paramList["CALLBACK_URL"] = site_url("addons/api/bundlePgResponse/" . $payment_request);

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

        $page_data['paramList'] = $paramList;
        $page_data['checkSum'] = $checkSum;
        $this->load->view('bundle_payment_web_view/paytm/paytm_merchant_checkout', $page_data);
    }

    public function bundlePgResponse($payment_request)
    {
         ini_set('display_errors', 0); 
         ini_set('display_startup_errors', 0);
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
 
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.
 

        $user_id = $this->session->userdata('user_id');  
        if ($isValidChecksum == "TRUE") {
            if ($_POST["STATUS"] == "TXN_SUCCESS") { 
              
                $bundle_price = $this->session->userdata('checkout_bundle_price');
                $bundle_id = $this->session->userdata('bundle_id');
                $amount_paid = $_POST['TXNAMOUNT'];
                $transaction_id = $_POST['TXNID'];
                $data['user_id'] = $this->session->userdata('user_id');
                $data['bundle_creator_id'] = $this->get_bundle($bundle_id)->row('user_id');
                $data['bundle_id'] = $this->session->userdata('bundle_id');   
                $data['payment_method'] = 'Paytm'; 
                $data['transaction_id'] = $transaction_id;
                $data['amount'] = $amount_paid;
                $data['date_added'] = strtotime(date('d M Y'));
 

                if($bundle_price == $amount_paid) 
                {
                    $insert = $this->db->insert('bundle_payment', $data);
                    $this->email_model->bundle_purchase_notification($user_id, 'paytm', $amount_paid); 
                    //$this->sendResponse("1","Payment successfully done",array());

                    $redirect = base_url('addons/Api/success');
                    redirect($redirect);

                } 
                else
                {
                    $redirect = base_url('addons/Api/failer');
                    redirect($redirect);
                    //$this->sendResponse("0","Something is wrong",array());

                } 
   
            } else {
                
                $redirect = base_url('addons/Api/failer');
                    redirect($redirect);
                //$this->sendResponse("0","Something is wrong",array());

            }

            if (isset($_POST) && count($_POST) > 0) {
                foreach ($_POST as $paramName => $paramValue) {
                    // YOU CAN PRINT PARAMNAMES AND PARAMVALUE HERE
                }
            }
        }
        elseif($payment_request == 'mobile')
        { 
            $redirect = base_url('addons/Api/failer');
            redirect($redirect);
            //$this->sendResponse("0","an error occurred during payment",array()); 
        }
        else
        {
            $redirect = base_url('addons/Api/failer');
            redirect($redirect);
            //$this->sendResponse("0","Checksum Mismatched",array()); 
        }
    }

    public function get_bundle($id = ""){
        $this->db->order_by('id', 'desc');
        if($id > 0){
            $this->db->where('id', $id);
        }
        return $this->db->get('course_bundle');
    }

    public function success()
    {
         //$this->sendResponse("1","Payment successfully done",array()); 
    }

    public function failer()
    {
         //$this->sendResponse("0","an error occurred during payment",array()); 
    }

    

     


////////////////////// Paytm Payment Getway Over //////////////////////////

    public function sendResponse($status,$message,$response_data)
    { 
        $data = array("status" => $status, "message" => $message, "Response" => $response_data);
        print(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    public function get_completed_number_of_lesson($user_id = "", $type = "", $id = "")
    {
        $counter = 0;
        if ($type == 'section') {
            $lessons = $this->crud_model->get_lessons('section', $id)->result_array();
        } else {
            $lessons = $this->crud_model->get_lessons('course', $id)->result_array();
        }
        foreach ($lessons as $key => $lesson) {
            if (lesson_progress($lesson['id'], $user_id)) {
                $counter = $counter + 1;
            }
        }
        return $counter;
    }


    public function course_data($courses = array())
    {
        foreach ($courses as $key => $course) {
            $courses[$key]['requirements'] = json_decode($course['requirements']);
            $courses[$key]['outcomes'] = json_decode($course['outcomes']);
            $courses[$key]['thumbnail'] = $this->get_image('course_thumbnail', $course['id']);
            if ($course['is_free_course'] == 1) {
                $courses[$key]['price'] = get_phrase('free');
            } else {
                if ($course['discount_flag'] == 1) {
                    $courses[$key]['price'] = currency($course['discounted_price']);
                } else {
                    $courses[$key]['price'] = currency($course['price']);
                }
            }
            $total_rating =  $this->crud_model->get_ratings('course', $course['id'], true)->row()->rating;
            $number_of_ratings = $this->crud_model->get_ratings('course', $course['id'])->num_rows();
            if ($number_of_ratings > 0) {
                $courses[$key]['rating'] = ceil($total_rating / $number_of_ratings);
            } else {
                $courses[$key]['rating'] = 0;
            }
            $courses[$key]['number_of_ratings'] = $number_of_ratings;
            $instructor_details = $this->user_model->get_all_user($course['user_id'])->row_array();
            $courses[$key]['instructor_name'] = $instructor_details['first_name'] . ' ' . $instructor_details['last_name'];
            $courses[$key]['total_enrollment'] = $this->crud_model->enrol_history($course['id'])->num_rows();
            $courses[$key]['shareable_link'] = site_url('home/course/' . slugify($course['title']) . '/' . $course['id']);
        }

        return $courses;
    }

        // Get image for course, categories or user image
    public function get_image($type, $identifier)
    { // type is the flag to realize whether it is course, category or user image. For course, user image Identifier is id but for category Identifier is image name
        if ($type == 'user_image') {
            // code...
        } elseif ($type == 'course_thumbnail') {
            $course_media_placeholders = themeConfiguration(get_frontend_settings('theme'), 'course_media_placeholders');
            if (file_exists('uploads/thumbnails/course_thumbnails/course_thumbnail_' . get_frontend_settings('theme') . '_' . $identifier . '.jpg')) {
                return base_url() . 'uploads/thumbnails/course_thumbnails/course_thumbnail_' . get_frontend_settings('theme') . '_' . $identifier . '.jpg';
            } else {
                return base_url() . $course_media_placeholders['course_thumbnail_placeholder'];
            }
        } elseif ($type == 'category_thumbnail') {
            if (file_exists('uploads/thumbnails/category_thumbnails/' . $identifier) && $identifier != "") {
                return base_url() . 'uploads/thumbnails/category_thumbnails/' . $identifier;
            } else {
                return base_url() . 'uploads/thumbnails/category_thumbnails/category-thumbnail.png';
            }
        }
    }




    public function cron_expired_365_backup()
    { 
        // For bundle_payment Trash START
        $date = date('Y-m-d');
        $expired = date('Y-m-d', strtotime('-365 day', strtotime($date)));

       
        $query = $this->db->query("SELECT * FROM `bundle_payment` WHERE DATE(`created`) = '$expired'");
        $rows = $query->result_array();
        foreach($rows as $row)
        {

            $bundle_payment_data = array( 
            'user_id' => $row['user_id'],
            'bundle_creator_id' => $row['bundle_creator_id'],
            'bundle_id' => $row['bundle_id'],
            'payment_method' => $row['payment_method'],
            'session_id' => $row['session_id'],
            'transaction_id' => $row['transaction_id'],
            'amount' => $row['amount'],
            'date_added' => $row['date_added'],
            'expired' => '1',
             ); 
          $id = $row['id'];
          $insert = $this->db->insert('bundle_payment_trash',$bundle_payment_data); 
          $delete = $this->db->query("DELETE FROM `bundle_payment` WHERE id='$id'");
        } 
        // For bundle_payment Trash END 

        $query1 = $this->db->query("SELECT * FROM `enrol` WHERE DATE(`created`) = '$expired'");
        $rows1 = $query1->result_array();
        foreach($rows1 as $row1)
        { 
            $enrol_data = array( 
            'user_id' => $row1['user_id'],
            'course_id' => $row1['course_id'],
            'date_added' => $row1['date_added'],
            'last_modified' => $row1['last_modified'], 
            'expired' => '1',
             ); 
          $id = $row1['id'];
          $insert = $this->db->insert('enrol_trash',$enrol_data); 
          $delete = $this->db->query("DELETE FROM `enrol` WHERE id='$id'");
        } 


    }


    public function cron_expired_365()
    { 
        // For bundle_payment Trash START
        $s = $this->db->query("SELECT a.`id`,a.`created`,a.`user_id`,b.`subscription_limit` FROM `bundle_payment` as a JOIN course_bundle as b ON a.`bundle_id`=b.id");
        $r = $s->result_array();

        foreach($r as $rr)
        { 
            $user_id = $rr['user_id'];
            $id = $rr['id']; 
            $subscription_limit = '+'.$rr['subscription_limit'].'day';
            $date = date('Y-m-d', strtotime($rr['created']));
            $expired = date('Y-m-d', strtotime("$subscription_limit", strtotime($date))); 
            $today = date('Y-m-d');
           if($today == $expired)
           {
                $query = $this->db->query("SELECT * FROM `bundle_payment` WHERE id='$id'");
                $row = $query->row_array(); 
                $bundle_payment_data = array( 
                'user_id' => $row['user_id'],
                'bundle_creator_id' => $row['bundle_creator_id'],
                'bundle_id' => $row['bundle_id'],
                'payment_method' => $row['payment_method'],
                'session_id' => $row['session_id'],
                'transaction_id' => $row['transaction_id'],
                'amount' => $row['amount'],
                'date_added' => $row['date_added'],
                'expired' => '1',
                 ); 
                 
                $id = $row['id'];
                $insert = $this->db->insert('bundle_payment_trash',$bundle_payment_data); 
                $delete = $this->db->query("DELETE FROM `bundle_payment` WHERE id='$id'");  
           }
            
        }  


         // For bundle_payment Trash END


        $s1 = $this->db->query("SELECT  a.`id`,a.`created`,a.`user_id`,b.`subscription_limit` FROM `enrol` as a JOIN course as b ON a.`course_id` = b.id");
        $r1 = $s1->result_array();

        foreach($r1 as $rr1)
        { 

            $user_id = $rr1['user_id'];
            $id = $rr1['id']; 
              
            $subscription_limit = '+'.$rr1['subscription_limit'].'day';
            $date = date('Y-m-d', strtotime($rr1['created']));
            $expired = date('Y-m-d', strtotime("$subscription_limit", strtotime($date))); 
            
            $today = date('Y-m-d');
               if($today == $expired)
               {

                $query1 = $this->db->query("SELECT * FROM `enrol` WHERE id='$id'");
                $row1 = $query1->row_array(); 
                $enrol_data = array( 
                'user_id' => $row1['user_id'],
                'course_id' => $row1['course_id'],
                'date_added' => $row1['date_added'],
                'last_modified' => $row1['last_modified'], 
                'expired' => '1',
                 ); 

                $id = $row1['id'];
                $insert = $this->db->insert('enrol_trash',$enrol_data); 
                $delete = $this->db->query("DELETE FROM `enrol` WHERE id='$id'");
              }
        } 

    }



    public function cron_expired_365_bk2()
    { 
        
        $s = $this->db->query("SELECT a.`id`,b.`created`,a.`user_id`,b.`subscription_limit` FROM `bundle_payment` as a JOIN course_bundle as b ON a.`bundle_id`=b.id");
        $r = $s->result_array();

        foreach($r as $rr)
        {
            $id = $rr['id'];
            $user_id = $rr['user_id'];
            $created = $rr['created'];
            $subscription_limit = '+'.$rr['subscription_limit'].'day';
            $e_date = date('Y-m-d', strtotime("$subscription_limit", strtotime($rr['created'])));
            $today = date('Y-m-d');

            if($e_date == $today)
            {
                $bundle_id =  $id;
                $user_id =  $user_id;

                $query = $this->db->query("SELECT * FROM `bundle_payment` WHERE `user_id`='$user_id' AND `bundle_id`='$bundle_id'");
                $row = $query->row_array(); 

                $bundle_payment_data = array( 
                'user_id' => $row['user_id'],
                'bundle_creator_id' => $row['bundle_creator_id'],
                'bundle_id' => $row['bundle_id'],
                'payment_method' => $row['payment_method'],
                'session_id' => $row['session_id'],
                'transaction_id' => $row['transaction_id'],
                'amount' => $row['amount'],
                'date_added' => $row['date_added'],
                'expired' => '1',
                 ); 
                  $id = $row['id'];
                  $insert = $this->db->insert('bundle_payment_trash',$bundle_payment_data); 
                  $delete = $this->db->query("DELETE FROM `bundle_payment` WHERE id='$bundle_id'"); 

            } 

        } 
        

    }


    /*public function my_course()
    {
        $user_id = $_GET['user_id'];

        $my_courses_ids1 = $this->user_model->my_courses($user_id)->result_array();
        $my_courses_ids2 = $this->user_model->my_courses_trash($user_id)->result_array();

        $my_courses_ids = array_merge($my_courses_ids1,$my_courses_ids2);
        //$response = array();
        foreach ($my_courses_ids as $my_courses_id) 
        {   
            $c_id = $my_courses_id['course_id'];
            $sql = $this->db->query("SELECT * FROM `course` WHERE `id`='$c_id'");
            $row = $sql->row_array();
            $outcomes = json_decode($row['outcomes']);
            $requirements = json_decode($row['requirements']);


            $number_of_ratings = $this->crud_model->get_ratings('course', $c_id)->num_rows();
            if ($number_of_ratings > 0) {
                $rating = ceil($total_rating / $number_of_ratings);
            } else {
                $rating = 0;
            }

            $instructor_details = $this->user_model->get_all_user($row['user_id'])->row_array();
            $instructor_name = $instructor_details['first_name'] . ' ' . $instructor_details['last_name'];
            $total_enrollment = $this->crud_model->enrol_history($c_id)->num_rows();
            $link = site_url('home/course/' . slugify($row['title']) . '/' . $row['id']);

            $total_number_of_completed_lessons = $this->api_model->get_completed_number_of_lesson($user_id, 'course', $c_id);


            if($my_courses_id['expired']=='1')
            { 
                $status_c = "Expired"; 
                $e_date = date('Y-m-d', strtotime($my_courses_id['created'])); 
            }
            else
            { 
                
                $queryss = $this->db->query("SELECT `subscription_limit` FROM `course` WHERE `id`='$c_id'");
                $rowss = $queryss->row_array();
                $subscription_limit = '+'.$rowss['subscription_limit'].'day';
                $status_c = "Active"; 
                $e_date = date('Y-m-d', strtotime($subscription_limit, strtotime($my_courses_id['created']))); 
            }

            $response[] = array(
            'id' => $row['id'],
            'title' => $row['title'],
            'subscription_limit' => $row['subscription_limit'],
            'short_description' => $row['short_description'],
            'description' => $row['description'],
            'outcomes' => $outcomes,
            'language' => $row['language'],
            'category_id' => $row['category_id'],
            'sub_category_id' => $row['sub_category_id'],
            'section' => '',
            'requirements' =>$requirements,
            'price' => $row['price'],
            'discount_flag' => $row['discount_flag'],
            'discounted_price' => $row['discounted_price'],
            'level' => $row['level'],
            'user_id' =>  $row['user_id'],
            'thumbnail' => $row['thumbnail'],
            'video_url' => $row['video_url'],
            'date_added' =>$row['date_added'],
            'last_modified' => $row['last_modified'],
            'course_type' => $row['course_type'],
            'is_top_course' => $row['is_top_course'],
            'is_admin' => $row['is_admin'],
            'status' => $row['is_admin'],
            'course_overview_provider' =>$row['course_overview_provider'],
            'meta_keywords' => $row['meta_keywords'],
            'meta_description' => $row['meta_description'],
            'is_free_course' => $row['is_free_course'],
            'multi_instructor' => $row['multi_instructor'],
            'creator' => $row['creator'],
            'rating' => $rating,
            'number_of_ratings' => $number_of_ratings,
            'instructor_name' => $instructor_name,
            'total_enrollment' => $total_enrollment,
            'shareable_link' => $link,
            'completion' => round(course_progress($c_id, $user_id)),
            'total_number_of_lessons' => $this->crud_model->get_lessons('course', $c_id)->num_rows(),
            'total_number_of_completed_lessons' =>$total_number_of_completed_lessons,
            'expired' => $status_c,
            'expried_date' => $e_date
            );

        }

        print(json_encode($response, JSON_UNESCAPED_UNICODE));
         
    }*/
 
}
