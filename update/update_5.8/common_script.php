<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();

$CI->db->where('key', 'drip_content_settings');
$query = $CI->db->get('settings');
if($query->num_rows() > 0){
	$CI->db->where('key', 'version');
	$CI->db->update('settings', array('value' => '5.7'));
}

$CI->db->where('key', 'allowed_device_number_of_loging');
$query = $CI->db->get('settings');
if($query->num_rows() > 0){
	$CI->db->where('key', 'version');
	$CI->db->update('settings', array('value' => '5.8'));
}
?>
