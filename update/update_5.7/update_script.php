<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();

//ADD enable_drip_content COLUMN IN course TABLE 
$enable_drip_content = array(
    'enable_drip_content' => array(
        'type' => 'int',
        'default' => 0,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_column('course', $enable_drip_content);

//ADD completed_lesson COLUMN IN watch_histories TABLE 
$completed_lesson = array(
    'completed_lesson' => array(
        'type' => 'longtext',
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_column('watch_histories', $completed_lesson);

//ADD course_progress COLUMN IN watch_histories TABLE 
$course_progress = array(
    'course_progress' => array(
        'type' => 'int',
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_column('watch_histories', $course_progress);


// CREATING watched_duration TABLE
$watched_duration = array(
    'watched_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'watched_student_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'watched_course_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'watched_lesson_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'watched_counter' => array(
        'type' => 'longtext',
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_field($watched_duration);
$CI->dbforge->add_key('watched_id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('watched_duration', TRUE);

//drip content settings insert
$drip_content_settings = array( 'key' => 'drip_content_settings', 'value' => '{"lesson_completion_role":"duration","minimum_duration":60,"minimum_percentage":"50","locked_lesson_message":"&lt;h3 xss=&quot;removed&quot; style=&quot;text-align: center; &quot;&gt;&lt;span xss=&quot;removed&quot;&gt;&lt;strong&gt;Permission denied!&lt;\/strong&gt;&lt;\/span&gt;&lt;\/h3&gt;&lt;p xss=&quot;removed&quot; style=&quot;text-align: center; &quot;&gt;&lt;span xss=&quot;removed&quot;&gt;This course supports drip content, so you must complete the previous lessons.&lt;\/span&gt;&lt;\/p&gt;"}');
$CI->db->insert('settings', $drip_content_settings);

//course accessibility insert
$course_accessibility = array( 'key' => 'course_accessibility', 'value' => 'publicly');
$CI->db->insert('settings', $course_accessibility);

// update VERSION NUMBER INSIDE SETTINGS TABLE
$settings_data = array( 'value' => '5.7');
$CI->db->where('key', 'version');
$CI->db->update('settings', $settings_data);
?>
