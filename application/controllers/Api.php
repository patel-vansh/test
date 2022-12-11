<?php
require APPPATH . '/libraries/TokenHandler.php';
//include Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller {

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
  
  public function enrol_courses_and_bundles_after_payment_get() {
      $auth_token = $_GET['auth_token'];
      $user_details = json_decode($this->token_data_get($auth_token), true);
      if ($user_details['user_id'] != "") {
          $cart_items = $this->api_model->enrol_courses_and_bundles_after_payment_get($user_details['user_id']);
          $this->set_response($cart_items, REST_Controller::HTTP_OK);
      }
  }
  
  public function get_payment_token_get() {
      $auth_token = $_GET['auth_token'];
      $user_details = json_decode($this->token_data_get($auth_token), true);
      if ($user_details['user_id'] != "") {
          $cart_items = $this->api_model->get_payment_token_get($user_details['user_id']);
          $this->set_response($cart_items, REST_Controller::HTTP_OK);
      }
  }
  
  public function get_all_payment_gateways_get() {
        $response = $this->api_model->get_all_payment_gateways_get();
        return $this->set_response($response, REST_Controller::HTTP_OK);
    }
  
  public function enrol_to_free_course_get() {
      $auth_token = $_GET['auth_token'];
      $user_details = json_decode($this->token_data_get($auth_token), true);
      if ($user_details['user_id'] != "") {
          $cart_items = $this->api_model->enrol_to_free_course_get($user_details['user_id']);
          $this->set_response($cart_items, REST_Controller::HTTP_OK);
      }
  }
  
  public function get_cart_price_with_discount_get() {
      $auth_token = $_GET['auth_token'];
      $user_details = json_decode($this->token_data_get($auth_token), true);
      if ($user_details['user_id'] != "") {
          $cart_items = $this->api_model->get_cart_price_with_discount_get($user_details['user_id']);
          $this->set_response($cart_items, REST_Controller::HTTP_OK);
      }
  }
  
  public function toggle_cart_item_get() {
      $auth_token = $_GET['auth_token'];
      $user_details = json_decode($this->token_data_get($auth_token), true);
      if ($user_details['user_id'] != "") {
          $cart_items = $this->api_model->toggle_cart_item_get($user_details['user_id']);
          $this->set_response($cart_items, REST_Controller::HTTP_OK);
      }
  }
  
  public function get_all_cart_items_get() {
      $auth_token = $_GET['auth_token'];
      $user_details = json_decode($this->token_data_get($auth_token), true);
      if ($user_details['user_id'] != "") {
          $cart_items = $this->api_model->get_all_cart_items_get($user_details['user_id']);
          $this->set_response($cart_items, REST_Controller::HTTP_OK);
      }
  }
  
  public function forgot_password_for_mobile_get() {
    $email = $_GET["email"];
    $email = str_replace("%40", "@", $email);
    $query = $this->db->get_where('users', array('email' => $email, 'status' => 1));
    if ($query->num_rows() > 0) {
        $verification_code = str_replace('=', '', base64_encode($email.'_Uh6#@#6hU_'.rand(111111, 9999999)));
        $this->db->where('email', $email);
        $this->db->update('users', array('verification_code' => $verification_code, 'last_modified' => time()));
        $this->email_model->password_reset_email_for_mobile($verification_code, $email);
        echo json_encode(array("status"=>"Success", "message"=>"Mail Sent"));
    } else {
        echo json_encode(array("status"=>"Failure", "message"=>"User Not Found"));
    }
  }
  
  public function blogs_get() {
      $blogs = $this->api_model->blogs_get();
      $this->set_response($blogs, REST_Controller::HTTP_OK);
  }
  
  public function get_overall_progress_get() {
      $auth_token = $_GET['auth_token'];
      $user_details = json_decode($this->token_data_get($auth_token), true);
      $overall_progress = $this->api_model->get_overall_progress_get($user_details['user_id']);
      $this->set_response($overall_progress, REST_Controller::HTTP_OK);
  }

  public function web_redirect_to_buy_course_get($auth_token = "", $course_id = "", $app_url = ""){
    $this->load->library('session');
    $price = 0;
    if($auth_token != "" && $course_id != "" && is_numeric($course_id)){

      //decode user auth token
      $user_details = json_decode($this->token_data_get($auth_token), true);
      $query = $this->user_model->get_all_user($user_details['user_id']);

      //user login
      if ($query->num_rows() > 0) {
          $row = $query->row();
          $this->session->set_userdata('custom_session_limit', (time()+604800));
          $this->session->set_userdata('user_id', $row->id);
          $this->session->set_userdata('role_id', $row->role_id);
          $this->session->set_userdata('role', get_user_role('user_role', $row->id));
          $this->session->set_userdata('name', $row->first_name . ' ' . $row->last_name);
          $this->session->set_userdata('is_instructor', $row->is_instructor);

          if ($row->role_id == 1) {
              $this->session->set_userdata('admin_login', '1');
          } else if ($row->role_id == 2) {
              $this->session->set_userdata('user_login', '1');
          }
          $this->session->set_userdata('app_url', $app_url.'://');
          $this->session->set_flashdata('flash_message', 'Welcome' . ' ' . $row->first_name . ' ' . $row->last_name);


          //add item to cart
          if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
          }
          $previous_cart_items = $this->session->userdata('cart_items');
          if (in_array($course_id, $previous_cart_items)) {
              // $key = array_search($course_id, $previous_cart_items);
              // unset($previous_cart_items[$key]);
          } else {
              array_push($previous_cart_items, $course_id);
          }
          foreach($previous_cart_items as $course_id):
            $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
            if($course_details['discount_flag'] == 1){
              $price += $course_details['discounted_price'];
            }else{
              $price += $course_details['price'];
            }
          endforeach;

          $this->session->set_userdata('total_price_of_checking_out', $price);
          $this->session->set_userdata('cart_items', $previous_cart_items);

          //redirect to payment page
          redirect(site_url('home/payment'), 'refresh');
      } else {
          $this->session->set_flashdata('error_message', 'Invalid auth token');
          redirect(site_url('home/login'), 'refresh');
      }
    }else{
      $this->session->set_flashdata('error_message', 'Something is wrong');
      redirect(site_url('home/login'), 'refresh');
    }
  }

  // Unprotected routes will be located here.
  // Fetch all the top courses
  public function top_courses_get($top_course_id = "") {
    $top_courses = array();
    $top_courses = $this->api_model->top_courses_get($top_course_id);
    $this->set_response($top_courses, REST_Controller::HTTP_OK);
  }

  public function app_logo_get(){
    $response = array();
    $response['banner_image'] = base_url('uploads/system/'.get_frontend_settings('banner_image'));
    $response['light_logo'] = base_url('uploads/system/'.get_frontend_settings('light_logo'));
    $response['dark_logo'] = base_url('uploads/system/'.get_frontend_settings('dark_logo'));
    $response['small_logo'] = base_url('uploads/system/'.get_frontend_settings('small_logo'));
    $response['favicon'] = base_url('uploads/system/'.get_frontend_settings('favicon'));

    $this->set_response($response, REST_Controller::HTTP_OK);
  }


  // Fetch all the categories
  public function all_categories_get() {
    $categories = array();
    $categories = $this->api_model->all_categories_get();
    $this->set_response($categories, REST_Controller::HTTP_OK);
  }

  public function categories_get($category_id = "") {
    $categories = array();
    $categories = $this->api_model->categories_get($category_id);
    $this->set_response($categories, REST_Controller::HTTP_OK);
  }
  // Fetch all the categories
  public function sub_categories_get($parent_category_id = "") {
    $categories = array();
    $categories = $this->api_model->sub_categories_get($parent_category_id);
    $this->set_response($categories, REST_Controller::HTTP_OK);
  }

  // Fetch all the courses belong to a certain category
  public function category_wise_course_get() {
    $category_id = $_GET['category_id'];
    $auth_token = $_GET['auth_token'];
    $user_details = json_decode($this->token_data_get($auth_token), true);
    $courses = $this->api_model->category_wise_course_get($user_details['user_id'], $category_id);
    $this->set_response($courses, REST_Controller::HTTP_OK);
  }

  // Fetch all the courses belong to a certain category
  public function languages_get() {
    $languages = $this->api_model->languages_get();
    $this->set_response($languages, REST_Controller::HTTP_OK);
  }

  // Filter course
  public function filter_course_get() {
    $courses = $this->api_model->filter_course();
    $this->set_response($courses, REST_Controller::HTTP_OK);
  }

  // Filter course
  public function courses_by_search_string_get() {
    $search_string = $_GET['search_string'];
    $courses = $this->api_model->courses_by_search_string_get($search_string);
    $this->set_response($courses, REST_Controller::HTTP_OK);
  }
  // get system settings
  public function system_settings_get() {
    $system_settings_data = $this->api_model->system_settings_get();
    $this->set_response($system_settings_data, REST_Controller::HTTP_OK);
  }

  // Login Api
  public function login_get() {
    $userdata = $this->api_model->login_get();
    if ($userdata['validity'] == 1) {
      $userdata['token'] = $this->tokenHandler->GenerateToken($userdata);
    }
    return $this->set_response($userdata, REST_Controller::HTTP_OK);
  }
  
  public function change_uid_for_mobile_get() {
      $response = $this->api_model->change_uid_for_mobile_get();
      return $this->set_response($response, REST_Controller::HTTP_OK);
  }
  
  public function check_uid_for_mobile_get() {
      $response = $this->api_model->check_uid_for_mobile_get();
      return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // // For single device Login Api
  // public function login_get() {
  //   $this->load->library('session');
  //   $credential = array('email' => $_GET['email'], 'password' => sha1($_GET['password']), 'status' => 1);
  //   $query = $this->db->get_where('users', $credential);
  //   if ($query->num_rows() > 0) {
  //     $row = $query->row_array();
  //     $session_id = $this->crud_model->store_session_in_user($row['id']);
  //   }else{
  //       $session_id = '';
  //   }

  //   $userdata = $this->api_model->login_get($session_id);
  //   if ($userdata['validity'] == 1) {
  //     $userdata['token'] = $this->tokenHandler->GenerateToken($userdata);
  //   }
  //   return $this->set_response($userdata, REST_Controller::HTTP_OK);
  // }

  // function device_identification_get($auth_token = ""){
  //   $this->load->library('session');
  //   $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
  //   $session_id = $logged_in_user_details['session_id'];
    
    
    
  //   $this->db->where('id', $logged_in_user_details['user_id']);
  // $user_sessions = $this->db->get('users')->row('session_id');
  // $pre_session = json_decode($user_sessions);

  //   if(in_array($session_id, $pre_session)){
  //     $response['status'] = 1;
  //   }else{
  //     $response['status'] = 0;
  //   }
  //   return $this->set_response($response, REST_Controller::HTTP_OK);
  // }
  
  // Get the latest version of app
  public function get_uploaded_version_get() {
        $response = array();
        $response = $this->api_model->get_uploaded_version_get();
        return $this->set_response($response, REST_Controller::HTTP_OK);
  }
  
  // Signup Api For Mobile
  public function signup_for_mobile_get() {
    $response = array();
    $response = $this->api_model->signup_for_mobile_get();
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }
  
  // Verify Email Api For Mobile
  public function verify_email_address_for_mobile_get(){
    $response = array();
    $response = $this->api_model->verify_email_address_for_mobile_get();
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // Resend Verification Code Api For Mobile
  public function resend_verification_code_for_mobile_get(){
    $response = array();
    $response = $this->api_model->resend_verification_code_for_mobile_get();
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // Signup Api
  public function signup_post() {
    $response = array();
    $response = $this->api_model->signup_post();
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // Verify Email Api
  public function verify_email_address_post(){
    $response = array();
    $response = $this->api_model->verify_email_address_post();
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // Resend Verification Code Api
  public function resend_verification_code_post(){
    $response = array();
    $response = $this->api_model->resend_verification_code_post();
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function course_object_by_id_get() {
    $course = $this->api_model->course_object_by_id_get();
    $this->set_response($course, REST_Controller::HTTP_OK);
  }
  //Protected APIs. This APIs will require Authorization.
  // My Courses API
//   public function my_courses_get() {
//     $response = array();
//     $auth_token = $_GET['auth_token'];
//     $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

//     if ($logged_in_user_details['user_id'] > 0) {
//       $response = $this->api_model->my_courses_get($logged_in_user_details['user_id']);
//     }else{

//     }
//     return $this->set_response($response, REST_Controller::HTTP_OK);
//   }

  public function my_courses_get() {
    $response = array();
     $auth_token = $_GET['auth_token'];
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if ($logged_in_user_details['user_id'] > 0) 
    { 
      $user_id = $logged_in_user_details['user_id'];
      //$response = $this->api_model->my_courses_get($logged_in_user_details['user_id']);
      //$response = $this->api_model->my_courses_get(6);
      $my_courses_ids1 = $this->user_model->my_courses($user_id)->result_array();
        $my_courses_ids2 = $this->user_model->my_courses_trash($user_id)->result_array();

        $my_courses_ids = array_merge($my_courses_ids1,$my_courses_ids2);
        //$response = array();
        foreach ($my_courses_ids as $my_courses_id) 
        {   
            $c_id = $my_courses_id['course_id'];
            $sql = $this->db->query("SELECT * FROM `course` WHERE `id`='$c_id'");
            if ($sql->num_rows() > 0) {
                $row = $sql->row_array();
                $outcomes = json_decode($row['outcomes']);
                $requirements = json_decode($row['requirements']);
    
    
                $number_of_ratings = $this->crud_model->get_ratings('course', $c_id)->num_rows();
                if ($number_of_ratings > 0) {
                    $rating = ceil($total_rating / $number_of_ratings);
                } else {
                    $rating = 0;
                }
                $instructor_names = array();
                if ($row['multi_instructor'] == 1) {
    		        $row['user_id'] = explode(',', $row['user_id']);
    		        $instructor_names = array();
    		        $instructor_images = array();
    		        foreach ($row['user_id'] as $instructor_id) {
        		        $instructor_details = $this->user_model->get_all_user($instructor_id)->row_array();
        		        $instructor_names[] = $instructor_details['first_name'] . ' ' . $instructor_details['last_name'];
        		        $instructor_images[] = $this->user_model->get_user_image_url($instructor_details['id']);
    		        }
    		    } else {
    		        $row['user_id'] = array($row['user_id']);
    		        $instructor_details = $this->user_model->get_all_user($row['user_id'][0])->row_array();
    			    $instructor_names[] = $instructor_details['first_name'] . ' ' . $instructor_details['last_name'];
    			    $instructor_images[] = $this->user_model->get_user_image_url($instructor_details['id']);
    		    }
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
                $thambnail = $this->crud_model->get_course_thumbnail_url($c_id);
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
                'thumbnail' => $thambnail,
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
                'instructor_name' => $instructor_names,
                'total_enrollment' => $total_enrollment,
                'shareable_link' => $link,
                'completion' => round(course_progress($c_id, $user_id)),
                'total_number_of_lessons' => $this->crud_model->get_lessons('course', $c_id)->num_rows(),
                'total_number_of_completed_lessons' =>$total_number_of_completed_lessons,
                'expired' => $status_c,
                'expried_date' => $e_date
                );
            }

        }
     }
     else
     {
     } 
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // My Courses API
  public function my_wishlist_get() {
    $response = array();      
    $auth_token = $_GET['auth_token'];
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if ($logged_in_user_details['user_id'] > 0) {
      $response = $this->api_model->my_wishlist_get($logged_in_user_details['user_id']);
    }else{

    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // Get all the sections
  public function sections_get() {
    $response = array();
    $auth_token = $_GET['auth_token'];
    $course_id  = $_GET['course_id'];
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if ($logged_in_user_details['user_id'] > 0) {
      $response = $this->api_model->sections_get($course_id, $logged_in_user_details['user_id']);
    }else{
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  //Get all lessons, section wise.
  public function section_wise_lessons_get() {
    $response = array();
    $auth_token = $_GET['auth_token'];
    $section_id = $_GET['section_id'];
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
    if ($logged_in_user_details['user_id'] > 0) {
      $response = $this->api_model->section_wise_lessons($section_id, $logged_in_user_details['user_id']);
    }else{
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // Remove from wishlist
  public function toggle_wishlist_items_get() {
    $auth_token = $_GET['auth_token'];
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
    if ($logged_in_user_details['user_id'] > 0) {
      $status = $this->api_model->toggle_wishlist_items_get($logged_in_user_details['user_id'], $logged_in_user_details['user_id']);
    }
    $response['status'] = $status;
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // Lesson Details
  public function lesson_details_get() {
    $response = array();
    $auth_token = $_GET['auth_token'];
    $lesson_id = $_GET['lesson_id'];

    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
    if ($logged_in_user_details['user_id'] > 0) {
      $response = $this->api_model->lesson_details_get($logged_in_user_details['user_id'], $lesson_id);
    }else{

    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // Course Details
  public function course_details_by_id_get() {
    $response = array();
    $course_id = $_GET['course_id'];
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
    }else{
      $logged_in_user_details['user_id'] = 0;
    }
    if ($logged_in_user_details['user_id'] > 0) {
      $response = $this->api_model->course_details_by_id_get($logged_in_user_details['user_id'], $course_id);
    }else{
      $response = $this->api_model->course_details_by_id_get(0, $course_id);
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // submit quiz view
  public function submit_quiz_post() {
    $submitted_quiz_info = array();
    $container = array();
    $quiz_id = $this->input->post('lesson_id');
    $quiz_questions = $this->crud_model->get_quiz_questions($quiz_id)->result_array();
    $total_correct_answers = 0;
    foreach ($quiz_questions as $quiz_question) {
      $submitted_answer_status = 0;
      $correct_answers = json_decode($quiz_question['correct_answers']);
      $submitted_answers = array();
      foreach ($this->input->post($quiz_question['id']) as $each_submission) {
        if (isset($each_submission)) {
          array_push($submitted_answers, $each_submission);
        }
      }
      sort($correct_answers);
      sort($submitted_answers);
      if ($correct_answers == $submitted_answers) {
        $submitted_answer_status = 1;
        $total_correct_answers++;
      }
      $container = array(
        "question_id" => $quiz_question['id'],
        'submitted_answer_status' => $submitted_answer_status,
        "submitted_answers" => json_encode($submitted_answers),
        "correct_answers"  => json_encode($correct_answers),
      );
      array_push($submitted_quiz_info, $container);
    }
    $page_data['submitted_quiz_info']   = $submitted_quiz_info;
    $page_data['total_correct_answers'] = $total_correct_answers;
    $page_data['total_questions'] = count($quiz_questions);
    $this->load->view('lessons/quiz_result', $page_data);
  }

  public function save_course_progress_get() {
    $response = array();
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      $response = $this->api_model->save_course_progress_get($logged_in_user_details['user_id']);
    }else{

    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  //Upload user image
  public function upload_user_image_post() {
    $response = array();
    if (isset($_POST['auth_token']) && !empty($_POST['auth_token'])) {
        $auth_token = $_POST['auth_token'];
        $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
        if ($logged_in_user_details['user_id'] > 0) {
            if (isset($_FILES['file']) && $_FILES['file']['name'] != "") {
                $user_image = $this->db->get_where('users', array('id' => $logged_in_user_details['user_id']))->row('image').'.jpg';
                if(file_exists('uploads/user_image/' . $user_image)) {
                    unlink('uploads/user_image/' . $user_image);
                }
                $data['image'] = md5(rand(10000, 10000000));
                $this->db->where('id', $logged_in_user_details['user_id']);
                $this->db->update('users', $data);
                // move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/user_image/'.$data['image'].'.jpg');
                $errors = $this->user_model->upload_user_image($data['image'], 'file', FALSE);
                if ($errors) {
                    $response['message'] = json_encode($errors);
                }
            }
            $response['status'] = 'success';
        }
    } else {
        $response['status'] = 'failed';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // update user data
  public function update_userdata_post() {
    $response = array();
    if (isset($_POST['auth_token']) && !empty($_POST['auth_token'])) {
      $auth_token = $_POST['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      if ($logged_in_user_details['user_id'] > 0) {
        $response = $this->api_model->update_userdata_post($logged_in_user_details['user_id']);
      }
    }else{
      $response['status'] = 'failed';
      $response['error_reason'] = 'Unauthorized login';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // password reset
  public function update_password_post() {
    $response = array();
    if (isset($_POST['auth_token']) && !empty($_POST['auth_token'])) {
      $auth_token = $_POST['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      if ($logged_in_user_details['user_id'] > 0) {
        $response = $this->api_model->update_password_post($logged_in_user_details['user_id']);
      }
    }else{
      $response['status'] = 'failed';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // Get user data
  public function userdata_get() {
    $response = array();
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      $response = $this->api_model->userdata_get($logged_in_user_details['user_id']);
      $response['status'] = 'success';
    }else{
      $response['status'] = 'failed';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // check whether certificate addon is installed and get certificate
  public function certificate_addon_get() {
    $response = array();
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      $user_id = $logged_in_user_details['user_id'];
      $course_id = $_GET['course_id'];

      $response = $this->api_model->certificate_addon_get($user_id, $course_id);
    }else{
      $response['status'] = 'failed';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }
  /////////// Generating Token and put user data into  token ///////////

  //////// get data from token ////////////
  public function GetTokenData()
  {
    $received_Token = $this->input->request_headers('Authorization');
    if (isset($received_Token['Token'])) {
      try
      {
        $jwtData = $this->tokenHandler->DecodeToken($received_Token['Token']);
        return json_encode($jwtData);
      }
      catch (Exception $e)
      {
        http_response_code('401');
        echo json_encode(array( "status" => false, "message" => $e->getMessage()));
        exit;
      }
    }else{
      echo json_encode(array( "status" => false, "message" => "Invalid Token"));
    }
  }

  public function token_data_get($auth_token)
  {
    //$received_Token = $this->input->request_headers('Authorization');
    if (isset($auth_token)) {
      try
      {

        $jwtData = $this->tokenHandler->DecodeToken($auth_token);
        return json_encode($jwtData);
      }
      catch (Exception $e)
      {
        echo 'catch';
        http_response_code('401');
        echo json_encode(array( "status" => false, "message" => $e->getMessage()));
        exit;
      }
    }else{
      echo json_encode(array( "status" => false, "message" => "Invalid Token"));
    }
  }

  public function enroll_free_course_get(){
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $course_id = $_GET['course_id'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      
      $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
      if ($course_details['is_free_course'] == 1) {
          $data['course_id'] = $course_id;
          $data['user_id']   = $logged_in_user_details['user_id'];
          if ($this->db->get_where('enrol', $data)->num_rows() > 0) {
              $response['message'] = 'already_enrolled';
              $response['status'] = 'failed';
          } else {
              $data['date_added'] = strtotime(date('D, d-M-Y'));
              $this->db->insert('enrol', $data);
              $response['message'] = 'success';
              $response['status'] = 'success';
          }
      } else {
          $response['message'] = 'This course is not free';
          $response['status'] = 'failed';
      }

    }else{
      $response['message'] = 'Invalid auth token';
      $response['status'] = 'failed';
    }

    return $this->set_response($response, REST_Controller::HTTP_OK);
  }


  function addon_status_get(){
    if(addon_status($_GET['unique_identifier'])){
      $response['status'] = true;
    }else{
      $response['status'] = false;
    }

    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  function zoom_live_class_get(){
    $course_id = $_GET['course_id'];
    $auth_token = $_GET['auth_token'];

    $user_details = json_decode($this->token_data_get($auth_token), true);
    //check live class access ability | valid users
    $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
    if ($course_details['user_id'] != $user_details['user_id']) {
        $enrolled_history = $this->db->get_where('enrol' , array('user_id' => $user_details['user_id'], 'course_id' => $course_id))->num_rows();
        if ($enrolled_history > 0) {
          $access = true;
        }else {
          $access = false;
        }
    }else {
        $access = true;
    }

    if($access && $course_id > 0){
      $live_class = $this->db->get_where('live_class', array('course_id' => $course_id));
      if($live_class->num_rows() > 0){
        $response['zoom_live_class_details'] = $live_class->row_array();
      }else{
        $response['zoom_live_class_details'] = array();
      }
      $response['zoom_api_key'] = get_settings('zoom_api_key');
      $response['zoom_secret_key'] = get_settings('zoom_secret_key');
    }else{
      $response['zoom_live_class_details'] = array();
      $response['zoom_api_key'] = '';
      $response['zoom_secret_key'] = '';
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }


  public function forgot_password_post(){
    $response = array();
    if(isset($_POST['email']) && !empty($_POST['email'])){
      $email = $this->input->post('email');
      $query = $this->db->get_where('users', array('email' => $email, 'status' => 1));
      if ($query->num_rows() > 0) {
          $this->api_model->forgot_password_post();
          $response['message'] = 'Successfully sent the verification link to your inbox';
          $response['status'] = 200;
          $response['validity'] = true;
      } else {
          $response['message'] = 'User not found';
          $response['status'] = 403;
          $response['validity'] = false;
      }
    }else{
      $response['message'] = 'Access denied';
      $response['status'] = 403;
      $response['validity'] = false;
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }


  // Fetch all the bundle courses
  public function bundles_get($bundle_id = "") {
    $bundle_courses = array();
    $bundle_courses = $this->api_model->bundles_get(10);
    $this->set_response($bundle_courses, REST_Controller::HTTP_OK);
  }

  public function bundle_courses_get($bundle_id = "") {
    $bundle_courses = array();
    if(isset($_GET['auth_token'])){
    $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
    }

    if (isset($_GET['auth_token']) && $logged_in_user_details['user_id'] > 0) {
      $bundle_courses = $this->api_model->bundle_courses_get($bundle_id, $logged_in_user_details['user_id']);
    }else{
      $bundle_courses = $this->api_model->bundle_courses_get($bundle_id);
    }
    $this->set_response($bundle_courses, REST_Controller::HTTP_OK);
  }

  // My Bundles API
  public function my_bundles_get() {
    $response = array();
    $auth_token = $_GET['auth_token'];
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if ($logged_in_user_details['user_id'] > 0) {
      $response = $this->api_model->my_bundles_get($logged_in_user_details['user_id']);
    }else{

    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function my_bundle_course_details_get($bundle_id = "", $course_id = "") {
    $bundle_course = array();
    $auth_token = $_GET['auth_token'];
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if ($logged_in_user_details['user_id'] > 0) {
      $bundle_course = $this->api_model->my_bundle_course_details_get($logged_in_user_details['user_id'], $bundle_id, $course_id);
    }else{

    }
    $this->set_response($bundle_course, REST_Controller::HTTP_OK);
  }

  public function web_redirect_to_buy_bundle_get($auth_token = "", $bundle_id = "", $app_url = ""){
    $this->load->library('session');
    if($auth_token != "" && $bundle_id != "" && is_numeric($bundle_id)){

      //decode user auth token
      $user_details = json_decode($this->token_data_get($auth_token), true);
      $query = $this->user_model->get_all_user($user_details['user_id']);

      //user login
      if ($query->num_rows() > 0) {
          $row = $query->row();
          $this->session->set_userdata('custom_session_limit', (time()+604800));
          $this->session->set_userdata('user_id', $row->id);
          $this->session->set_userdata('role_id', $row->role_id);
          $this->session->set_userdata('role', get_user_role('user_role', $row->id));
          $this->session->set_userdata('name', $row->first_name . ' ' . $row->last_name);
          $this->session->set_userdata('is_instructor', $row->is_instructor);
          if ($row->role_id == 1) {
              $this->session->set_userdata('admin_login', '1');
          } else if ($row->role_id == 2) {
              $this->session->set_userdata('user_login', '1');
          }
          $this->session->set_userdata('app_url', $app_url.'://');
          $this->session->set_flashdata('flash_message', 'Welcome' . ' ' . $row->first_name . ' ' . $row->last_name);

          //redirect to payment page
          redirect(site_url('course_bundles/buy/'.$bundle_id), 'refresh');
      } else {
          $this->session->set_flashdata('error_message', 'Invalid auth token');
          redirect(site_url('home/login'), 'refresh');
      }
    }else{
      $this->session->set_flashdata('error_message', 'Something is wrong');
      redirect(site_url('home/login'), 'refresh');
    }
  }
  //End Bundle



  //Start Form addon
  public function forum_add_questions_post($course_id = "") {
    $response = array('status' => 403, 'message' => 'Invalid request');

    $auth_token = $_POST['auth_token'];
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if($logged_in_user_details['user_id'] > 0 && $course_id > 0){
      $response = $this->api_model->forum_add_questions_post($logged_in_user_details['user_id'], $course_id);
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function forum_questions_get($auth_token, $course_id = "", $page_number = 0, $limit = 20) {
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if($logged_in_user_details['user_id'] > 0 && $course_id > 0){
      $forum_questions = $this->api_model->forum_questions_get($logged_in_user_details['user_id'], $course_id, $page_number, $limit);
    }
    $this->set_response($forum_questions, REST_Controller::HTTP_OK);
  }

  public function search_forum_questions_get($auth_token, $course_id = "") {
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if($logged_in_user_details['user_id'] > 0 && isset($_GET['search']) && !empty($_GET['search']) && !empty($course_id)){
      $forum_questions = $this->api_model->search_forum_questions_get($logged_in_user_details['user_id'], $course_id);
    }
    $this->set_response($forum_questions, REST_Controller::HTTP_OK);
  }

  public function add_questions_reply_post($parent_id = "") {
    $response = array('status' => 403, 'message' => 'Invalid request');

    $auth_token = $_POST['auth_token'];
    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if($logged_in_user_details['user_id'] > 0 && $parent_id > 0){
      $response = $this->api_model->add_questions_reply_post($logged_in_user_details['user_id'], $parent_id);
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function forum_child_questions_get($parent_question_id = "") {
    $child_questions = array();

    if($parent_question_id != ""){
      $child_questions = $this->api_model->forum_child_questions_get($parent_question_id);
    }
    $this->set_response($child_questions, REST_Controller::HTTP_OK);
  }

  public function forum_question_vote_get($question_id = "", $auth_token = "") {
    $response = array('status' => 403, 'message' => 'Invalid request');

    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if($logged_in_user_details['user_id'] > 0 && $question_id > 0){
      $response = $this->api_model->forum_question_vote_get($logged_in_user_details['user_id'], $question_id);
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function forum_question_delete_get($question_id = "", $auth_token = "") {
    $response = array('status' => 403, 'message' => 'Invalid request');

    $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);

    if($logged_in_user_details['user_id'] > 0 && $question_id > 0){
      $response = $this->api_model->forum_question_delete_get($logged_in_user_details['user_id'], $question_id);
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }
//End Forum addon

























}
