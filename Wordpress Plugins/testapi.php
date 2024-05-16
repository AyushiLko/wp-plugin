<?php
/*
Plugin Name: Test API
Description: For the test purpose.
Version: 1.0 
Author: Anjul
*/
add_action('rest_api_init', function(){
    register_rest_route('api/v1','/for_user_details', array(
        'methods' => 'POST',
        'callback' => 'for_user_details',
    ));
    register_rest_route('api/v1','/for_get_user_details', array(
        'methods' => 'POST',
        'callback' => 'for_get_user_details',
    ));
    register_rest_route('api/v1','/for_update_user_details', array(
        'methods' => 'POST',
        'callback' => 'for_update_user_details',
    ));
    register_rest_route('api/v1','/for_delete_user_details', array(
        'methods' => 'POST',
        'callback' => 'for_delete_user_details',
    ));

    function for_user_details($request){
     $params = $request->get_params();
     $name = $params['name'];
     $email = $params['email'];
     $password = $params['password'];
     $phone = $params['phone'];
     $address = $params['address'];
     $image = $_FILES['image'];
    
    $user_id = wp_create_user($name,$password,$email);
    if(!is_wp_error($user_id)){
        update_user_meta($user_id,'phone',$phone);
        update_user_meta($user_id,'address',$address);
        update_user_meta($user_id,'image',$image);
        if(!empty($_FILES['image'])){
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $attachment_id = media_handle_upload('image', $user_id);

            if(!is_wp_error($attachment_id)){
                set_post_thumbnail($user_id, $attachment_id);
                $success = array(
                    'successmsg' => "User data created successfully",
                    'success_code' => 200,
                    'status' => 'Success',
                );
                return new WP_REST_Response($success);
            } else{
                $error = array(
                    'errormsg' => "image uploading failed",
                    'error_code' => 403,
                    'status' => 'Error',
                );
                return new WP_REST_Response($error);
            }
            } else{
                $error = array(
                    'errormsg' => "Empty image field",
                    'error_code' => 403,
                    'status' => 'Error',
                );
                return new WP_REST_Response($error);
            }
            }else{
                $error = array(
                    'errormsg' => "User data not created",
                    'error_code' => 403,
                    'status' => 'Error',
                );
                return new WP_REST_Response($error);
            }
        }
        function for_get_user_details($request){
            $params = $request->get_params();
            $user_id = $params['user_id'];
            if(! $user_id){
                $error = array(
                    'errormsg' => "User is not found",
                    'error_code' => 403,
                    'status' => 'Error',
                );
                return new WP_REST_Response($error);
            }else{
                $user = get_userdata($user_id);
                $userdata = array(
                    'email' => $user->user_email,
                    'name' => get_user_meta($user_id,'name',true),
                    'phone' => get_user_meta($user_id,'phone',true),
                );
                $success = array(
                    'successmsg' => "user found successfully",
                    'success_code' => 200,
                    'status' => 'Success',
                    'user_data' => $userdata
                );
                return new WP_REST_Response($success);
            }
        }
        function for_update_user_details($request){
            global $wpdb;
            $params = $request->get_params();
            $name = $params['name'];
            $email = $params['email'];
            $password = $params['password'];
            $phone = $params['phone'];
            $address = $params['address'];
            $id = @$params['id'];
            if($id){
            $result = $wpdb->update('create_stu', array(
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone,
                    'address' => $address,
                ),
                array('id' => $id)
            );
            if($result){
                $success = array(
                    'successmsg' => "Student data updated successfully",
                    'success_code' => 200,
                    'status' => 'success',
                );
                return new WP_REST_Response($success);
            }else{
                $error = array(
                    'errormsg' => "Something went wrong! Please try again",
                    'error_code' => 403,
                    'status' => 'Error',
                );
                return new WP_REST_Response($error);
            }
            }else{
                $result = $wpdb->insert('create_stu', array(
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone,
                    'address' => $address
                ));
                if($result){
                    $success = array(
                        'successmsg' => "Data inserted successfully",
                        'success_code' => 200,
                        'status' => 'Success'
                    );
                    return new WP_REST_Response($success);
                }else{
                    $error = array(
                        'errormsg' => "Data not inserted",
                        'error_code' => 403,
                        'status' => 'Error',
                    );
                    return new WP_REST_Response($error);
                }
            }
        }
        function for_delete_user_details($request){
            global $wpdb;
            $params = $request->get_params();
            if(empty($params['id'])){
                $error = array(
                    'errormsg' => "Missing parameter! Product id missing",
                    'code_error' => 403,
                    'status' => 'Error',
                );
                return new WP_REST_Response($error);
            }
            $id = $params['id'];
            $delete = $wpdb->delete('create_stu',array(
                'id' => $id,
            ));
            $success = array(
                'successmsg' => "Data is deleted",
                'success_code' => 200,
                'status' => 'Success',
                'delete' => $id
            );
            return new WP_REST_Response($success);
            if(!$delete){
                $error = array(
                    'errormsg' => "Delete failed!",
                    'error_code' => 403,
                    'status' => 'Error',
                );
                return new WP_REST_Response($error);
            }
        }
    });

    

        
            

        
    

