<?php
require APPPATH . '/libraries/TokenHandler.php';
//include Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class Quizformobile extends REST_Controller {

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
    
    public function get_quiz_questions_get() {
        $lesson_id = $_GET['lesson_id'];
        $lesson_details = $this->crud_model->get_lessons('lesson', $lesson_id)->result_array();
        $instructions = $lesson_details[0]['summary'];
        $time = $lesson_details[0]['duration'];
        $total_marks = strval(json_decode($lesson_details[0]['attachment'])->total_marks);
        $res = $this->crud_model->get_quiz_questions($lesson_id)->result_array();
        foreach($res as $key => $value) {
            $value['options'] = json_decode($value['options']);
            $value['correct_answers'] = json_decode($value['correct_answers']);
            foreach($value['options'] as $key2 => $value2) {
                $value2 = remove_js(htmlspecialchars_decode($value2));
                $value['options'][$key2] = $value2;
            }
            foreach($value['correct_answers'] as $key2 => $value2) {
                $value2 = remove_js(htmlspecialchars_decode($value2));
                $value['correct_answers'][$key2] = $value2;
            }
            $res[$key] = $value;
        }
        echo json_encode(array("status"=>"Success", "total_time"=>$time, "instruction"=>$instructions, "total_marks"=>$total_marks, "message"=>$res));
    }
}