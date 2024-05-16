<?php
/*
Plugin Name: Create API
Version: 1.0 
Description: Create an API to work on it.
Author: Create API
*/
add_action('rest_api_init', function(){
    register_rest_route('api/v1','/add_user_details', array(
        'methods' => 'POST',
        'callback' => 'add_user_details',
    ));
    register_rest_route('api/v1','/practise_product_data', array(
        'methods' => 'POST',
        'callback' => 'practise_product_data',
    ));
    register_rest_route('api/v1','/practise_product_delete', array(
        'methods' => 'POST',
        'callback' => 'practise_product_delete',
    ));
    register_rest_route('api/v1','/practise_user_details', array(
        'methods' => 'POST',
        'callback' => 'practise_user_details',
    ));
    function add_user_details($req)
    {
        $parameter = $req->get_params();
        $name = $parameter['name'];
        $password = $parameter['password'];
        $email = $parameter['email'];
        $address = $parameter['address'];
        $image = $_FILES['image'];

        $user_id = wp_create_user($name,$password,$email);
        if(!is_wp_error($user_id)){
            update_user_meta($user_id,'address',$address);
            update_user_meta($user_id,'image',$image);
            if (!empty($_FILES['image'])) {
                // Include necessary files for media handling
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                $attachment_id = media_handle_upload('image', $user_id);
                
                // Check if the upload was successful
                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($user_id, $attachment_id);

            $success = array(
                'successsmsg' => "User data created successfully",
                'error_code' => 200,
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
return new WP_REST_Response('user_id_not_found', 'Failed to insert post', array('status' => 403));
}
}

//Create user

function practise_user_details()
{
    $params = $req->get_params();
    
}
//Insert and update of a product
function practise_product_data($req)     
{
    $params = $req->get_params();
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
            "ID" => $product_id
        );
        wp_update_post($para);
        update_post_meta($product_id,'_regular_price',$productregularprice);
        update_post_meta($product_id,'_sale_price',$productsaleprice);
            if (!empty($_FILES['productimage']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $attachment_id = media_handle_upload('productimage',$product_id);

            if (!is_wp_error($attachment_id)){
                set_post_thumbnail($product_id,$attachment_id);
                $success  = array(
                    'successmsg' => "Product updated successfully",
                    'error_code' => NULL,
                    'status' => 'Success',
                );
                return new WP_REST_Response($success);
            } else{
                $error = array(
                    'errormsg' => "Image_Upload_failed",
                    'codeerror' => 403,
                    'status' => 'error',
                );
                return new WP_REST_Response($error);
            } 
        } else{
            $error = array(
                'errormsg' => "Empty image failed! Failed to upload image",
                'codeerror' => 403,
                'status' => 'error',
            );
            return new WP_REST_Response($error);
        }
    }else{
                $para = array(
                    "post_title" => $productname,
                    "post_content" => $productdescription,
                    "post_type" => "product",
                    "post_status" => "publish",
                    "post_author" => 1,
                );
                $post_id = wp_insert_post($para);
                if(!is_wp_error($post_id)){
                    update_post_meta($post_id,'_regular_price',$productregularprice);
                    update_post_meta($post_id,'_sale_price',$productsaleprice);
                    if (!empty($_FILES['productimage'])){
                        require_once(ABSPATH . 'wp-admin/includes/media.php');
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        require_once(ABSPATH . 'wp-admin/includes/file.php');
                        $attachment_id = media_handle_upload('productimage',$post_id);

                        if (!is_wp_error($attachment_id)){
                            set_post_thumbnail($post_id,$attachment_id);
                            $success =  array(
                                'successmsg' => "Product created successfully",
                                'errorcode'=> 200,
                                'status' => 'success',
                            );
                            return new WP_REST_Response($success);
                        } else{
                            $error = array(
                                'errormsg' => "Image_Upload_failed",
                                'codeerror' => 403,
                                'status' => 'error',
                            );
                            return new WP_REST_Response($error);
                        } 

            }else{
                $error = array(
                    'errormsg' => "Empty image failed! Failed to upload image",
                    'codeerror' => 403,
                    'status' => 'error',
                );
                return new WP_REST_Response($error);
        } 
    }else{
        $error = array(
            'errormsg' => "Product_id_not_found! Failed to upload product",
            'codeerror' => 403,
            'status' => 'error',
        );
        return new WP_REST_Response($error);
}
} 
}

//Delete of Product

function practise_product_delete($req)
{
    $params = $req->get_params();
    if (empty($params['pro_id'])){
        $error = array(
            'errormsg' => "Missing parameter! Product id missing",
            'codeerror' => 403,
            'status' => 'error',
        );
        return new WP_REST_Response($error);
    }
    $pro_id = $params['pro_id'];
    $delete = wp_delete_post($pro_id);
    $success = array(
        'successmsg' => "Product deleted successfully",
        'codesuccess' =>200,
        'status' => 'success',
        'delete_pro_id' => $pro_id
    );
    return new WP_REST_Response($success);
    if (!$delete){
        $error = array(
            'errormsg' => "Delete failed! failed to delete product",
            'codeerror' => 403,
            'status' => 'error',
        );
        return new WP_REST_Response($error);
    }
}
});




