<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();


$skills_column = array(
    'skills' => array(
        'type' => 'longtext',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_column('users', $skills_column);

$CI->db->insert('frontend_settings', array('key' => 'facebook'));
$CI->db->insert('frontend_settings', array('key' => 'twitter'));
$CI->db->insert('frontend_settings', array('key' => 'linkedin'));


// INSERT VERSION NUMBER INSIDE SETTINGS TABLE
$settings_data = array( 'value' => '5.4');
$CI->db->where('key', 'version');
$CI->db->update('settings', $settings_data);
?>
