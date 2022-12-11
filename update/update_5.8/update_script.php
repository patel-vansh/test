<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();

//ADD sessions COLUMN IN watch_histories TABLE 
$sessions = array(
    'sessions' => array(
        'type' => 'LONGTEXT',
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_column('users', $sessions);


// CREATING quiz_results TABLE
$quiz_results = array(
    'quiz_result_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'quiz_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'user_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'user_answers' => array(
        'type' => 'longtext',
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'correct_answers' => array(
        'type' => 'longtext',
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'total_obtained_marks' => array(
        'type' => 'double',
        'collation' => 'utf8_unicode_ci'
    ),
    'date_added' => array(
        'type' => 'varchar',
        'constraint' => 100,
        'collation' => 'utf8_unicode_ci'
    ),
    'date_updated' => array(
        'type' => 'varchar',
        'constraint' => 100,
        'collation' => 'utf8_unicode_ci'
    ),
    'is_submitted' => array(
        'type' => 'INT',
        'constraint' => 11,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_field($quiz_results);
$CI->dbforge->add_key('quiz_result_id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('quiz_results', TRUE);

// CREATING custom_page TABLE
$custom_page = array(
    'custom_page_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'page_content' => array(
        'type' => 'longtext',
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'page_title' => array(
        'type' => 'varchar',
        'constraint' => 255,
        'collation' => 'utf8_unicode_ci'
    ),
    'page_url' => array(
        'type' => 'varchar',
        'constraint' => 255,
        'collation' => 'utf8_unicode_ci'
    ),
    'button_title' => array(
        'type' => 'varchar',
        'constraint' => 255,
        'collation' => 'utf8_unicode_ci'
    ),
    'button_position' => array(
        'type' => 'varchar',
        'constraint' => 255,
        'collation' => 'utf8_unicode_ci'
    ),
    'status' => array(
        'type' => 'INT',
        'constraint' => 11,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_field($custom_page);
$CI->dbforge->add_key('custom_page_id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('custom_page', TRUE);


$smtp_crypto = array( 'key' => 'smtp_crypto', 'value' => '');
$CI->db->insert('settings', $smtp_crypto);

$allowed_device_number_of_loging = array( 'key' => 'allowed_device_number_of_loging', 'value' => '1');
$CI->db->insert('settings', $allowed_device_number_of_loging);

// update VERSION NUMBER INSIDE SETTINGS TABLE
$settings_data = array( 'value' => '5.8');
$CI->db->where('key', 'version');
$CI->db->update('settings', $settings_data);
?>
