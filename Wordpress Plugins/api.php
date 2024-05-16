<?php
/*
Plugin Name: User Management
Description: Api for users data management
Version: 1.0
Author: Mukesh Kumar Maurya
*/
add_action('rest_api_init', function () {
    register_rest_route('my-api/v1', '/add-user/', array(
        'methods' => 'POST',
        'callback' => 'add_user_endpoint',
    ));
});

function add_user_endpoint($data)
{
    $first_name = sanitize_text_field($data['first_name']);
    $last_name = sanitize_text_field($data['last_name']);
    $username = sanitize_text_field($data['username']);
    $email = sanitize_email($data['email']);
    $password = sanitize_text_field($data['password']);

    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        return rest_ensure_response(array('error' => $user_id->get_error_message()));
    }
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);

    $response = array('message' => 'User added successfully', 'user_id' => $user_id);
    return rest_ensure_response($response);
}

add_action('rest_api_init', function () {
    register_rest_route('my-api/v1', '/edit-user/', array(
        'methods' => 'POST',
        'callback' => 'edit_user_endpoint',
    ));
});

function edit_user_endpoint($data)
{
    $id = intval($data['id']);
    $first_name = sanitize_text_field($data['first_name']);
    $last_name = sanitize_text_field($data['last_name']);
    $username = sanitize_text_field($data['username']);
    $email = sanitize_email($data['email']);
    $password = sanitize_text_field($data['password']);

    $user_data = array(
        'ID' => $id,
        'user_email' => $email,
        'user_pass' => $password,
        'username' => $username,
    );
    $user_id = wp_update_user($user_data);
    if (is_wp_error($user_id)) {
        return rest_ensure_response(array('error' => $user_id->get_error_message()));
    }
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);

    $response = array('message' => 'User updated successfully', 'user_id' => $user_id);
    return rest_ensure_response($response);
}

add_action('rest_api_init', function () {
    register_rest_route('my-api/v1', '/delete-user/', array(
        'methods' => 'DELETE',
        'callback' => 'delete_user_endpoint',
    ));
});
function delete_user_endpoint($data)
{
    $id = intval($data['id']);
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    $result = $wpdb->delete(
        $table_name,
        array(
            'ID' => $id,
        )
    );
    if ($result) {
        echo 'user deleted successfully';
    } else {
        echo 'user not deleted';
    }
}