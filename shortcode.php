<?php
/*
Plugin Name: Shortcode Post
Version: 1.0
Author: William
*/
add_shortcode('admin_menu','short_code_page');
//creating post
function short_code_page(){
    if(isset($_POST['submit'])){
        $posttitle = sanitize_text_field($_POST['title']);
        $description = sanitize_text_field($_POST['description']);
        $address = sanitize_text_field($_POST['address']);
        $phone = sanitize_text_field($_POST['phone']);
        $dateofbirth = sanitize_text_field($_POST['dateofbirth']);
        $state = sanitize_text_field($_POST['state']);
        //post data
        $post_data = array(
            "post_title" => $posttitle,
            "post_content" => $description,
            "post_type" => "testing",
            "post_status" => "publish",
            "post_author" => 1,
        );
        $post_id = wp_insert_post($post_data);
        update_post_meta($post_id,'address',$address);
        update_post_meta($post_id,'phone',$phone);
        update_post_meta($post_id,'dateofbirth',$dateofbirth);
        update_post_meta($post_id,'state',$state);
        if($post_id){
            echo "Post created successfully";
        }else{
            echo "post not created!";
        }
    }
    ?>
    <h1>FORM</h1>
    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title"></br></br>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description"></br></br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address"></br></br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone"></br></br>
        <label for="dateofbirth">Date Of Birth:</label>
        <input type="date" id="dateofbirth" name="dateofbirth"></br></br>
        <label for="state">State:</label>
        <input type="text" id="state" name="state"></br></br>
        <button type="submit" id="submit" name="submit">Submit</button>
</form>
    <?php

}
?>