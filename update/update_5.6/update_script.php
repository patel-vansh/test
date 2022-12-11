<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();

//ADD keywords COLUMN IN blogs TABLE 
$blogs = array(
    'keywords' => array(
        'type' => 'text',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_column('blogs', $blogs);


// update VERSION NUMBER INSIDE SETTINGS TABLE
$settings_data = array( 'value' => '5.6');
$CI->db->where('key', 'version');
$CI->db->update('settings', $settings_data);
?>
