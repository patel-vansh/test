<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Academy_cloud_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function upload_video(){
      $video_title = htmlspecialchars($this->input->post('title'));
      $video_file = $_FILES['cloud_video_file']['tmp_name'];

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://video.creativeitem.com/api/videos',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
          'title' => $video_title,
          'video'=> new CURLFILE($video_file, mime_content_type(realpath($video_file)), $_FILES['cloud_video_file']['name'])
        ),
        CURLOPT_HTTPHEADER => array(
          'Authorization: '.get_settings('academy_cloud_access_token'),
          'Domain: '.$_SERVER['SERVER_NAME']
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      
      $response_arr = json_decode($response, true);

      if(is_array($response_arr) && $response_arr['success']){
        $response_arr['data']['success'] = $response_arr['success'];
        $response_arr['data']['message'] = $response_arr['message'];
        $response_arr['data']['video_url'] = 'not_now';
          return $response_arr['data'];
      }else{
          $response_arr = array(
            "id" => null,
            "user_id" => null,
            "uuid" => null,
            "title" => null,
            "video_url" => null,
            "path" => null,
            "status" => null,
            "created_at" => null,
            "updated_at" => null,
            "success" => false,
            "message" => (is_array($response_arr)) ? $response_arr['data'] : get_phrase('something_is_wrong')
          );
          return $response_arr;
      }
    }

    function update_video($video_id = ""){
      $video_title = htmlspecialchars($this->input->post('title'));
      $video_file = $_FILES['cloud_video_file']['tmp_name'];

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://video.creativeitem.com/api/video/update/'.$video_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
          'title' => $video_title,
          'video'=> new CURLFILE($video_file, mime_content_type(realpath($video_file)), $_FILES['cloud_video_file']['name'])
        ),
        CURLOPT_HTTPHEADER => array(
          'Authorization: '.get_settings('academy_cloud_access_token')
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      
      $response_arr = json_decode($response, true);

      if(is_array($response_arr) && $response_arr['success']){
        $response_arr['data']['success'] = $response_arr['success'];
        $response_arr['data']['message'] = $response_arr['message'];
        $response_arr['data']['video_url'] = 'not_now';
          return $response_arr['data'];
      }else{
          $response_arr = array(
            "id" => null,
            "user_id" => null,
            "uuid" => null,
            "title" => null,
            "video_url" => null,
            "path" => null,
            "status" => null,
            "created_at" => null,
            "updated_at" => null,
            "success" => false,
            "message" => (is_array($response_arr)) ? $response_arr['data'] : get_phrase('something_is_wrong')
          );
          return $response_arr;
      }
    }

    function get_cloud_videos(){

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://video.creativeitem.com/api/videos',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Authorization: '.get_settings('academy_cloud_access_token'),
          'Domain: '.$_SERVER['SERVER_NAME']
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      $response_arr = json_decode($response, true);
      if(is_array($response_arr) && is_array($response_arr['data'])){
          return $response_arr['data'];
      }else{
          return array();
      }
    }

    function get_cloud_video_url($video_id = ""){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://video.creativeitem.com/api/videos/'.$video_id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.get_settings('academy_cloud_access_token'),
            'Domain: '.$_SERVER['SERVER_NAME']
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_arr = json_decode($response, true);
        if(is_array($response_arr) && count($response_arr) > 0){
            return $response_arr['data'];
        }else{
            return null;
        }
    }

    function delete_cloud_video($video_id = ""){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://video.creativeitem.com/api/videos/'.$video_id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'DELETE',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.get_settings('academy_cloud_access_token')
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return true;
    }


    function get_subscription_details(){

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://video.creativeitem.com/api/users',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Authorization: '.get_settings('academy_cloud_access_token')
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);


      $response_arr = json_decode($response, true);
      if(is_array($response_arr) && count($response_arr) > 0){
          return $response_arr;
      }else{
          return array(
            "subscription_status" => null,
            "subscription_date" => null,
            "expired_date" => null,
            "package_title" => null,
            "package_validity" => null,
            "storage_limit" => null,
            "storage_available" => null,
            "name" => null,
            "email" => null
          );
      }
    }


    function save_access_token(){
      $data['value'] = $this->input->post('access_token');
      $query = $this->db->get_where('settings', ['key' => 'academy_cloud_access_token']);
      if($query->num_rows() > 0){
        $this->db->where('key', 'academy_cloud_access_token');
        $this->db->update('settings', $data);
      }else{
        $data['key'] = 'academy_cloud_access_token';
        $this->db->update('settings', $data);
      }
    }







}