<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();

// CREATING BLOG TABLE
$blogs = array(
    'blog_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'blog_category_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'collation' => 'utf8_unicode_ci'
    ),
    'user_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'collation' => 'utf8_unicode_ci'
    ),
    'title' => array(
        'type' => 'VARCHAR',
        'constraint' => '255',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'description' => array(
        'type' => 'longtext',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'thumbnail' => array(
        'type' => 'VARCHAR',
        'constraint' => '100',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'banner' => array(
        'type' => 'VARCHAR',
        'constraint' => '100',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'is_popular' => array(
        'type' => 'INT',
        'constraint' => 11,
        'collation' => 'utf8_unicode_ci'
    ),
    'likes' => array(
        'type' => 'longtext',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'added_date' => array(
        'type' => 'VARCHAR',
        'constraint' => '100',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'updated_date' => array(
        'type' => 'VARCHAR',
        'constraint' => '100',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'status' => array(
        'type' => 'VARCHAR',
        'constraint' => '50',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_field($blogs);
$CI->dbforge->add_key('blog_id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('blogs', TRUE);


// CREATING BLOG_CATEGORY TABLE
$blog_category = array(
    'blog_category_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'title' => array(
        'type' => 'VARCHAR',
        'constraint' => '255',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'subtitle' => array(
        'type' => 'VARCHAR',
        'constraint' => '255',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'slug' => array(
        'type' => 'VARCHAR',
        'constraint' => '255',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'added_date' => array(
        'type' => 'VARCHAR',
        'constraint' => '100',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_field($blog_category);
$CI->dbforge->add_key('blog_category_id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('blog_category', TRUE);


// CREATING BLOG_COMMENTS TABLE
$blog_comments = array(
    'blog_comment_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'blog_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'collation' => 'utf8_unicode_ci'
    ),
    'user_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'collation' => 'utf8_unicode_ci'
    ),
    'parent_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'collation' => 'utf8_unicode_ci'
    ),
    'comment' => array(
        'type' => 'longtext',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'likes' => array(
        'type' => 'longtext',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'added_date' => array(
        'type' => 'VARCHAR',
        'constraint' => '100',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'updated_date' => array(
        'type' => 'VARCHAR',
        'constraint' => '100',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_field($blog_comments);
$CI->dbforge->add_key('blog_comment_id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('blog_comments', TRUE);


//ADD quiz_result COLUMN IN WATCH HISTORY
$watch_histories = array(
    'quiz_result' => array(
        'type' => 'longtext',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_column('watch_histories', $watch_histories);


//ADD BLOG SETTINGS IN frontend_settings table
$CI->db->insert('frontend_settings', array('key' => 'blog_page_title', 'value' => "Where possibilities begin"));
$CI->db->insert('frontend_settings', array('key' => 'blog_page_subtitle', 'value' => "We’re a leading marketplace platform for learning and teaching online. Explore some of our most popular content and learn something new."));
$CI->db->insert('frontend_settings', array('key' => 'blog_page_banner', 'value' => "6d4ac9547c72c45635ac564cffedd650.png"));
$CI->db->insert('frontend_settings', array('key' => 'instructors_blog_permission', 'value' => "0"));
$CI->db->insert('frontend_settings', array('key' => 'blog_visibility_on_the_home_page', 'value' => "0"));


//ADD payment_keys COLUMN IN USERS TABLE 
$payment_key_col = array(
    'payment_keys' => array(
        'type' => 'longtext',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_column('users', $payment_key_col);


$CI->db->where('role_id !=', 1);
$CI->db->where('is_instructor', 1);
$all_instructors = $CI->db->get('users');
if($all_instructors->num_rows() > 0){
    foreach($all_instructors->result_array() as $all_instructor){

        if($all_instructor['stripe_keys']){
            $stripe_keys = json_decode($all_instructor['stripe_keys'], true);
            if(is_array($stripe_keys)){
                // Update stripe keys
                $stripe['public_live_key'] = $stripe_keys[0]['public_live_key'];
                $stripe['secret_live_key'] = $stripe_keys[0]['secret_live_key'];
                $payment_keys['stripe'] = $stripe;
            }
        }

        if($all_instructor['paypal_keys']){
            $paypal_keys = json_decode($all_instructor['paypal_keys'], true);
            if(is_array($stripe_keys)){
                // Update paypal keys
                $paypal['production_client_id'] = $paypal_keys[0]['production_client_id'];
                $paypal['production_secret_key'] = $paypal_keys[0]['production_secret_key'];
                $payment_keys['paypal'] = $paypal;
            }
        }


        //All payment keys
        $update_data['payment_keys'] = json_encode($payment_keys);
        $CI->db->where('id', $all_instructor['id']);
        $CI->db->update('users', $update_data);
    }
}

// INSERT VERSION NUMBER INSIDE SETTINGS TABLE
$settings_data = array( 'value' => '5.5');
$CI->db->where('key', 'version');
$CI->db->update('settings', $settings_data);
?>
