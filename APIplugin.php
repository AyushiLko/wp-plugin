<?php
/*
Plugin Name: API Plugin
Description: This is API plugin.
Version:1.0 
Author: API
*/
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

//localhost/wordpress/wp-json/jwt-auth/v1/token
// wordpress automatically provides an api of login that is mentioned above it takes two parameters username, password


add_action('rest_api_init', function () {
    register_rest_route('api/v1', '/add-user', array(
        'methods' => 'POST',
        'callback' => 'add_user_api',
    ));
    register_rest_route('api/v1', '/get-user', array(
        'methods' => 'GET',
        'callback' => 'get_user_api',
    ));
    register_rest_route('api/v1', '/add_student_data', array(
        'methods' => 'POST',
        'callback' => 'add_student_data',
    ));
    register_rest_route('api/v1', '/get_student_data', array(
        'methods' => 'GET',
        'callback' => 'get_student_data',
    ));
    register_rest_route('api/v1', '/delete_student_data', array(
        'methods' => 'POST',
        'callback' => 'delete_student_data',
    ));
   // register_rest_route('api/v1', '/Update_student_data', array(
        //'methods' => 'POST',
        //'callback' => 'Update_student_data',
   // ));
    register_rest_route('api/v1', '/post_user_api', array(
        'methods' => 'POST',
        'callback' => 'post_user_api',
    ));
    register_rest_route('api/v1', '/create_custom_post', array(
        'methods' => 'POST',
        'callback' => 'create_custom_post',
    ));
    register_rest_route('api/v1', '/get_custom_post', array(
        'methods' => 'POST',
        'callback' => 'get_custom_post',
    ));
    register_rest_route('api/v1', '/post_product', array(
        'methods' => 'POST',
        'callback' => 'post_product',
    ));
    register_rest_route('api/v1', '/get_product_details', array(
        'methods' => 'POST',
        'callback' => 'get_product_details',
    ));
    register_rest_route('api/v1', '/get_all_products', array(
        'methods' => 'POST',
        'callback' => 'get_all_products',
    ));
    register_rest_route('api/v1', '/delete_product', array(
        'methods' => 'POST',
        'callback' => 'delete_product',
    ));
    register_rest_route('api/v1', '/get_user_detail_by_token', array(
        'methods' => 'POST',
        'callback' => 'get_user_detail_by_token',
    ));
    register_rest_route('api/v1', '/delete_user_detail_by_token', array(
        'methods' => 'POST',
        'callback' => 'delete_user_detail_by_token',
    ));


    

    //Token to Generate userIid

    function user_id_exists($user)
{
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $user));
    if ($count == 1) {
        return true;
    } else {
        return false;
    }
}

// token from header


function tokenFromHeader()
{
    $headers = getallheaders();
    $temp = array();
    if (isset($headers['Authorization'])) {
        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            $temp['token'] =  $matches[1];
        }
    }
    $temp['platform']        = @$headers['Platform'];
    $temp['timezone']        = @$headers['Timezone'];
    return $temp;
}

// Get user by id token

function GetUserByIdToken($token)
{
    $decoded_array = [];
    $user_id = 0;
    if ($token) {
        try {
            $decoded = JWT::decode($token, new Key(JWT_AUTH_SECRET_KEY, 'HS256'));
            $decoded_array = (array)$decoded;
            if (count($decoded_array) > 0) {
                $user_id = $decoded_array["data"]->user->id;
            }
            if (user_id_exists($user_id)) {
                return $user_id;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}



//Create user API


    function add_user_api($Request)
    {
        $para = $Request->get_params();
        $name = $para['name'];
        $pass = $para['pass'];
        $email = $para['email'];
        $fname = $para['fname'];
        $lname = $para['lname'];

        $user_id = wp_create_user($name,$pass,$email);
        
        if(!is_wp_error($user_id)){
            update_user_meta($user_id, 'first_name',$fname);
            update_user_meta($user_id, 'last_name',$lname);
   
            $success = array(
                'successmsg' => "User created successfully",
                'error_code' => NULL,
                'status' => 'Success',
            );
            return new WP_REST_Response($success);
        }
    }

    //
    function get_user_api($Request)
    {
        $para = $Request->get_params();
        $user_id = $para['user_id'];
        if(! $user_id){
            $error = array(
                'errormsg' => "User is not found",
                'error_code' => NULL,
                'status' => 'Error',
            );
            return new WP_REST_Response($error);
        }else{
            $user = get_userdata($user_id);
            $userdata = array(
                'email' => $user->user_email,
                'fname' => get_user_meta($user_id,'first_name',true),
                'lname' => get_user_meta($user_id,'last_name',true),
            );
                $success = array(
                    'successmsg' => "User found successfully",
                    'error_code' => NULL,
                    'status' => 'Success',
                    'user_data' => $userdata
                );
                return new WP_REST_Response($success);
        }
    }
    function add_student_data($request){
        global $wpdb;
        $param = $request->get_params();
        $first_name = $param['first_name'];
        $last_name = $param['last_name'];
        $email = $param['email'];
        $password = $param['password'];
        $id = @$param['id'];
        if($id){          
        $result = $wpdb->update('add_student', array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
        ),
            array('id' => $id)          
        );
        if($result){
            $success = array(
                'successmsg' => "Student data updated successfully",
                'error_code' => NULL,
                'status' => 'Success',
            );
            return new WP_REST_Response($success);
        }else{
            $error = array(
                'errormsg' => "Something went wrong! Please try again later",
                'code' => 403,
                'status' => 'error',
            );
            return new WP_REST_Response($error);
        }
       
        }else{
        $result = $wpdb->insert('add_student', array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password
        ));
       if($result ){
        $success = array(
            'successmsg' => "Student data added successfully",
            'code' => 200,
            'status' => 'success',
        );
        return new WP_REST_Response($success);
    
} else{
    $error = array(
        'errormsg' => "Something went wrong! Please try again later",
        'code' => 403,
        'status' => 'error',
    );
    return new WP_REST_Response($error);
}
    }
}
    function get_student_data($Request)
    {
        $para = $Request->get_params();
        global $wpdb;
        $student_data = $wpdb->get_results( "SELECT * FROM add_student" );
                $success = array(
                    'successmsg' => "Student data found successfully",
                    'error_code' => NULL,
                    'status' => 'Success',
                    'student_data' => $student_data
                );
                return new WP_REST_Response($success);
    }
    function delete_student_data($request) {
        $params = $request->get_params();
    
        // Check if user_id parameter is provided
        if (empty($params['user_id'])) {
            return new WP_REST_Response('missing_parameter', 'user_id parameter is missing', array('status' => 400));
        }
    
        $user_id = $params['user_id'];
    
        // Check if user exists
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return new WP_REST_Response('user_not_found', 'User not found', array('status' => 404));
        }
    
       global $wpdb;
       $deleted = $wpdb->delete('add_student',array('id'=> $user_id));
        if (!$deleted) {
            return new WP_REST_Response('delete_failed', 'Failed to delete user', array('status' => 403));
        }
    
        // Return success response
        $success = array(
            'successmsg' => 'Student data deleted successfully',
            'error_code' => null,
            'status' => 'Success',
            'deleted_user_id' => $user_id
        );
        return new WP_REST_Response($success, 200);
    }  

    //Update user

    //  function Update_student_data($request){
    //     global $wpdb;

    //     $params = $request->get_params();
    //     $first_name = $params['first_name'];
    //     $last_name = $params['last_name'];
    //     $email = $params['email'];
    //     $password = $params['password'];
    //     $id = $params['id'];
         
    //     $result = $wpdb->update('add_student', array(
    //         'first_name' => $first_name,
    //         'last_name' => $last_name,
    //         'email' => $email,
    //         'password' => $password,
    //     ),
    //         array('id' => $id)          
    //     );
    //     $success = array(
    //         'successmsg' => "Student data updated successfully",
    //         'error_code' => NULL,
    //         'status' => 'Success',
    //     );
    //     return new WP_REST_Response($success);
    //  } 

     // Post Created

     function post_user_api($request){
        $params = $request->get_params();
        $posttitle = $params['post_title'];
        $description = $params['description'];
        $price = $params['price'];
        $image = $_FILES['image'];

        $params = array(
            "post_title" => $posttitle,
            "post_content" => $description,
            "post_type" => "blogging",
            "post_status" => "publish",
            "post_author" => 1,
        );

        $post_id = wp_insert_post($params);
        if(!is_wp_error($post_id)){  
            if (!empty($_FILES['image'])) {
                // Include necessary files for media handling
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                $attachment_id = media_handle_upload('image', $post_id);
                
                // Check if the upload was successful
                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($post_id, $attachment_id);
                    $success = array(
                        'successmsg' => "Post inserted  successfully",
                        'error_code' => NULL,
                        'status' => 'Success',
                    );
                    return new WP_REST_Response($success);
                }else {
                    return new WP_REST_Response('image_uploading_failed', 'Failed to upload image', array('status' => 403));
                }
    } else {
        return new WP_REST_Response('Empty image field', 'Failed to upload image', array('status' => 403));
    }
 }else{
    return new WP_REST_Response('post_id_not_found', 'Failed to insert post', array('status' => 403));
}  
}  
function create_custom_post($request)
{
    $params = $request->get_params();
    $posttitle = $params['post_title'];
    $description = $params['description'];
    $price = $params['price'];
    $category = $params['category'];
    $image = $_FILES['image'];

    $para = array(
        "post_title" => $posttitle,
        "post_content" => $description,
        "post_type" => "testing",
        "post_status" => "publish",
        "post_author" => 1,
    );
    $post_id = wp_insert_post($para);
    if(!is_wp_error($post_id)){  
            update_post_meta($post_id, 'price',$price);
            update_post_meta($post_id, 'category',$category);
        if (!empty($_FILES['image'])) {
            // Include necessary files for media handling
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $attachment_id = media_handle_upload('image', $post_id);
            
            // Check if the upload was successful
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($post_id, $attachment_id);
                $success = array(
                    'successmsg' => "Custom post created  successfully",
                    'error_code' => NULL,
                    'status' => 'Success',
                );
                return new WP_REST_Response($success);
            }else {
                return new WP_REST_Response('image_uploading_failed', 'Failed to upload image', array('status' => 403));
            }
} else {
    return new WP_REST_Response('Empty image field', 'Failed to upload image', array('status' => 403));
}
}else{
return new WP_REST_Response('post_id_not_found', 'Failed to insert post', array('status' => 403));
}
}
function get_custom_post($request)
{
    $params = $request->get_params();
    $post_id = $params['post_id'];
    if(! $post_id){
        $error = array(
            'errormsg' => " Custom Post is not found",
            'error_code' => NULL,
            'status' => 'Error',
        );
        return new WP_REST_Response($error);
    }else{
        $post = get_post($post_id);
        $postdata = array(
            'post_data' =>$post,
            'price' => get_post_meta($post_id,'price',true),
            'category' => get_post_meta($post_id,'category',true),
        );
            $success = array(
                'successmsg' => "Post found successfully",
                'error_code' => NULL,
                'status' => 'Success',
                'post_data' => $postdata
            );
            return new WP_REST_Response($success);
    }
}
function post_product($request){
    $params = $request->get_params();
    $productname = $params['productname'];
    $productdescription = $params['productdescription'];
    $productregularprice = $params['productregularprice'];
    $productsaleprice = $params['productsaleprice'];
    $productimage = $_FILES['productimage'];
    $product_id = @$params['product_id'];
    if($product_id){
        $para = array(
            "post_title" => $productname,
            "post_content" => $productdescription,
            "post_type" => "product",
            "post_status" => "publish",
            "post_author" => 1,
            "ID" =>$product_id
        );
        wp_update_post($para);
        update_post_meta($product_id, '_regular_price',$productregularprice);
        update_post_meta($product_id, '_sale_price',$productsaleprice);
        if (!empty($_FILES['productimage'])) {
            // Include necessary files for media handling
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $attachment_id = media_handle_upload('productimage', $product_id);
            
            // Check if the upload was successful
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($product_id, $attachment_id);
                $success = array(
                    'successmsg' => "Product updated successfully",
                    'error_code' => NULL,
                    'status' => 'Success',
                );
                return new WP_REST_Response($success);
            } else {
                return new WP_REST_Response('image_uploading_failed', 'Failed to upload image', array('status' => 403));
            }
        } else {
            return new WP_REST_Response('Empty image field', 'Failed to upload image', array('status' => 403));
        }
    } else {
    $para = array(
        "post_title" => $productname,
        "post_content" => $productdescription,
        "post_type" => "product",
        "post_status" => "publish",
        "post_author" => 1,
    );
    $post_id = wp_insert_post($para);
    if(!is_wp_error($post_id)){  
        update_post_meta($post_id, '_regular_price',$productregularprice);
        update_post_meta($post_id, '_sale_price',$productsaleprice);
        if (!empty($_FILES['productimage'])) {
            // Include necessary files for media handling
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $attachment_id = media_handle_upload('productimage', $post_id);
            
            // Check if the upload was successful
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($post_id, $attachment_id);
                $success = array(
                    'successmsg' => "Product created successfully",
                    'error_code' => NULL,
                    'status' => 'Success',
                );
                return new WP_REST_Response($success);
            } else {
                return new WP_REST_Response('image_uploading_failed', 'Failed to upload image', array('status' => 403));
            }
        } else {
            return new WP_REST_Response('Empty image field', 'Failed to upload image', array('status' => 403));
        }
    } else {
        return new WP_REST_Response('post_id_not_found', 'Failed to insert post', array('status' => 403));
    }

    }
}
function get_product_details($request)
{
    $params = $request->get_params();
    $product_id = $params['product_id'];
    if(! $product_id){
        $error = array(
            'errormsg' => " Product is not found",
            'error_code' => 403,
            'status' => 'Error',
        );
        return new WP_REST_Response($error);
    }else{
        $product = get_post($product_id);
        $productdata = array(
            'product_data' =>$product,
            'productregularprice' => get_post_meta($product_id,'_regular_price',true),
            'productsaleprice' => get_post_meta($product_id,'_sale_price',true),
        );
            $success = array(
                'successmsg' => "Product found successfully",
                'error_code' => 200,
                'status' => 'Success',
                'product_data' => $productdata
            );
            return new WP_REST_Response($success);
    }
}
function get_all_products($request)
{
    $params = $request->get_params();
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    $products = get_posts($args);
    foreach($products as $product){
        $id = get_the_ID();
        $productname = get_the_title();
        $description = $product->post_content;
        $regularprice = get_post_meta($id,'_regular_price',true);
        $saleprice = get_post_meta($id,'_sale_price',true);
        $image = get_post_meta($id,'_thumbnail_id',true);
    };
    $success = array(
        'successmsg' => "Products found successfully",
        'error_code' => NULL,
        'status' => 'Success',
        'products' => $products
    );
    return new WP_REST_Response($success);

}
function delete_product($request)
{
    $params = $request->get_params();
         if (empty($params['product_id'])) {
            return new WP_REST_Response('missing_parameter', 'product_id parameter is missing', array('status' => 400));
        }
        $product_id = $params['product_id'];
        $deleted = wp_delete_post($product_id);
        if (!$deleted) {
            return new WP_REST_Response('delete_failed', 'Failed to delete product', array('status' => 403));
        }
        $success = array(
            'successmsg' => 'Product deleted successfully',
            'error_code' => null,
            'status' => 'Success',
            'deleted_product_id' => $product_id
        );
        return new WP_REST_Response($success, 200);
    }

    //Token

    function get_user_detail_by_token($request)
    {
        $params = $request->get_params();
        $token = $params['token'];
        $user_id = GetUserByIdToken($token);
        $user_information = get_userdata($user_id );

        $user = get_userdata($user_id);
        $userdata = array(
        'email' => $user->user_email,
        'fname' => get_user_meta($user_id,'first_name',true),
        'lname' => get_user_meta($user_id,'last_name',true),
        );

        $success = array(
            'successmsg' => "User find  successfully",
            'successcode' => 200,
            'status' => 'success',
            'user_data' => $userdata
        );
        return new WP_REST_Response($success,200);
        
    }
    function delete_user_detail_by_token($request)
    {
        global $wpdb;
        $params  = $request->get_params();
        $token   = $params['token'];
        $user_id = GetUserByIdToken($token);
        if (empty($user_id)){
            $error = array(
                'errormsg' => "Misssing user_id",
                'errorcode' => 403,
                'status' => 'error',
            );
            return new WP_REST_Response($error);
        }
        wp_delete_user($user_id);
        $success = array(
            'successmsg' => "User deleted successfully",
            'successcode' =>200,
            'status' => 'success',
            'user_id' => $user_id,
        );
        return new WP_REST_response($success);
       
    }
});







  