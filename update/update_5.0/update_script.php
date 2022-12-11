<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();


// INSERT VERSION NUMBER INSIDE SETTINGS TABLE
$version_data = array('value' => '5.0');
$CI->db->where('key', 'version');
$CI->db->update('settings', $version_data);

// CREATING COUPONS TABLE
$coupons_table_fields = array(
    'id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'code' => array(
        'type' => 'VARCHAR',
        'constraint' => '255',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'discount_percentage' => array(
        'type' => 'VARCHAR',
        'constraint' => '255',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'created_at' => array(
        'type' => 'INT',
        'constraint' => '11',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'expiry_date' => array(
        'type' => 'INT',
        'constraint' => '11',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_field($coupons_table_fields);
$CI->dbforge->add_key('id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('coupons', TRUE);


// CREATING PERMISSIONS TABLE
$permissions_table_fields = array(
    'id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'admin_id' => array(
        'type' => 'INT',
        'constraint' => '11',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'permissions' => array(
        'type' => 'LONGTEXT',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_field($permissions_table_fields);
$CI->dbforge->add_key('id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('permissions', TRUE);


// ADDING MULTI INSTRUCTOR COLUMN IN COURSE TABLES
$multi_instructor_column = array(
    'multi_instructor' => array(
        'type' => 'INT',
        'constraint' => '11',
        'default' => 0,
        'null' => FALSE
    )
);

$this->dbforge->add_column('course', $multi_instructor_column);


// MODIFYING USER_ID COLUMN IN COURSE TABLES INT > VARCHAR
$user_id_column = array(
    'user_id' => array(
        'type' => 'VARCHAR',
        'constraint' => '255',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
);
$this->dbforge->modify_column('course', $user_id_column);

// ADDING CREATOR COLUMN IN COURSE TABLES
$creator_column = array(
    'creator' => array(
        'type' => 'INT',
        'constraint' => '11',
        'default' => null,
        'null' => TRUE
    )
);

$this->dbforge->add_column('course', $creator_column);

// PUTTING VALUES IN CREATOR TABLE
$courses = $CI->db->get('course')->result_array();
foreach ($courses as $key => $course) {
    $updater['creator'] = $course['user_id'];

    $CI->db->where('id', $course['id']);
    $CI->db->update('course', $updater);
}

// ADDING COUPON COLUMN IN PAYMENT TABLES
$coupon_column = array(
    'coupon' => array(
        'type' => 'VARCHAR',
        'constraint' => '255',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);

$this->dbforge->add_column('payment', $coupon_column);
