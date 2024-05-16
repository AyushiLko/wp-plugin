<?php
/*
Plugin Name: Custom Registration API
Description: Custom API for user registration.
Version: 1.0
Author: Custom API
*/

class Custom_Registration_API {
    
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    
    public function register_routes() {
        register_rest_route('api/v1', '/register', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_registration'),
            'permission_callback' => '__return_true',
        ));
    }
    
    public function handle_registration($request) {
        $para = $request->get_params();
        $name = $para['name'];
        $pass = $para['pass'];
        $email = $para['email'];
        $user_id = wp_create_user($name,$pass,$email);
        
        if (is_wp_error($user_id)) {
            return new WP_Error('registration_failed', 'User registration failed.', array('status' => 500));
        }
        return array(
            'status' => 'success',
            'message' => 'User registered successfully.',
            'user_id' => $user_id,
        );
    }
}
new Custom_Registration_API();
