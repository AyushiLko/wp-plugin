<?php
/*
Plugin Name: Product Crud Plugin
Description: This is for the practice.
Version: 1.0 
Author: Crud
*/

add_action('admin_menu', 'product_crud');

function product_crud() {
    add_menu_page('Product Crud', 'Product Crud', 'manage_options', 'product_crud_menu', 'product_crud_menu_page');
}

function product_crud_menu_page() {
    ?>
    <h1>Product Form</h1>
    <form id="productForm" action="" method="POST">
        <label for="fname">First Name</label>
        <input type="text" name="fname" id="fname"></br></br>
        <label for="lname">Last Name</label>
        <input type="text" name="lname" id="lname"></br></br>
        <label for="email">Email</label>
        <input type="text" name="email" id="email"></br></br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password"></br></br>
        <button type="submit" name="submit" id="submit">Submit</button>
    </form>
    <script>
    jQuery(document).ready(function($) {
        $('#productForm').submit(function(e) {
            e.preventDefault();
            var fname = $('#fname').val();
            var lname = $('#lname').val();
            var email = $('#email').val();
            var password = $('#password').val();
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'create_product',
                    fname: fname,
                    lname: lname,
                    email: email,
                    password: password,
                },
                success: function(response) {
                    alert(response.data);
                },
                error: function(response) {
                    alert(response.data);
                }
            });
        });
    });
    </script>
    <?php
}

add_action('wp_ajax_create_product', 'create_product');

function create_product() {
    if (isset($_POST['fname'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user_id = wp_create_user($email, $password, $email);
        if ($user_id) {
            update_user_meta($user_id, 'first_name', $fname);
            update_user_meta($user_id, 'last_name', $lname);
            wp_send_json_success('Product created');
        } else {
            wp_send_json_error('Product not created');
        }
    } else {
        wp_send_json_error('No data received');
    }
}
?>
