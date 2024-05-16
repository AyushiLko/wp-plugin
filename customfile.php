<?php
/*
Plugin Name: Create Post
Version:1.0 
Author:Sachin
*/
add_action('admin_menu','create_post_menu');
function create_post_menu()
{
    add_menu_page ('Create Post','Create Post','manage_options','testing-page-post','testing_page_menu');
}
function testing_page_menu()
{
if(isset($_POST['submit']))
{
    $fname = sanitize_text_field($_POST['fname']);
    $lname = sanitize_text_field($_POST['lname']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);

    //$user_id = wp_create_user($email,$fname,$email);
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'student';
    $result = $wpdb->insert($table_name, array(
        'name' => $name,
        'email' => $email,
        'number' => $number
    ));

    if (is_wp_error($user_id)) {
        echo "Error creating user";
        return;
    }
        update_user_meta($user_id,"first_name",$fname);
        update_user_meta($user_id,"last_name",$lname);
        update_user_meta($user_id,"phone",$phone);
        
        // image upload
        if (isset($_FILES['image']['name'])) {
            $attachment_id = media_handle_upload('image',$user_id);

            if (!is_wp_error($attachment_id)) {
                update_user_meta($user_id, "wp_user_avatar",$attachment_id);
            } else {
                echo "Error uploading image";
                return;
            }
        }
    echo "Form submitted successfully";
}
    ?>
    
    <h1> Testing Form </h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="fname">First_Name:</label>
        <input type="fname" name="fname" id="fname"></br></br>
        <label for="lname">Last_Name:</label>
        <input type="lname" name="lname" id="lname"></br></br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email"></br></br>
        <label for="phone">Phone_Number:</label>
        <input type="phone" name="phone" id="phone"></br></br>
        <label for="image">Image:</label>
        <input type="file" name="image" id="image"/></br></br>
        <button type="submit" name="submit">Submit</button>
</form>       
    <?php
    

}
?>