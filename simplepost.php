<?php
/*
Plugin Name: Simple Post
Version: 1.0
Author: Ayushi
*/

// Adding admin menu
add_action('admin_menu', 'simple_post_menu_admin');
function simple_post_menu_admin(){
    add_menu_page('Simple Post', 'Simple Post', 'manage_options', 'simple-post', 'simple_post_page');
}

// Creating the form for adding a post
function simple_post_page(){
    if(isset($_POST['submit'])){
        // Sanitize input data
        $posttitle = sanitize_text_field($_POST['posttitle']);
        $description = sanitize_text_field($_POST['description']);
        $date = sanitize_text_field($_POST['date']);
        
        // Prepare post data
        $post_data = array(
            "post_title" => $posttitle,
            "post_content" => $description,
            "post_type" => "testing",
            "post_status" => "publish",
            "post_author" => 1, // Set post author ID here
        );
        
        // Insert the post into the database
        $post_id = wp_insert_post($post_data);
        if ($post_id) {
            // Handle file upload
            if (!empty($_FILES['image']['name'])) {
                // Include necessary files for media handling
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                
                // Upload and attach the image to the post
                $attachment_id = media_handle_upload('image', $post_id);
                
                // Check if the upload was successful
                if (!is_wp_error($attachment_id)) {
                    // Set the uploaded image as the post thumbnail
                    set_post_thumbnail($post_id, $attachment_id);
                    echo "Post created successfully";
                } else {
                    // Display error message if the upload failed
                    echo "Error uploading image: " . $attachment_id->get_error_message();
                }
            } else {
                // Display a message if no image was uploaded
                echo "Post created successfully";
            }
        } else {
            // Display error message if post creation failed
            echo "Error creating post";
        }
    }
    ?>
    <!-- HTML form for adding a post -->
    <h1>Sample Post</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="posttitle">Post Title:</label>
        <input type="text" name="posttitle" id="posttitle"><br><br>
        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="6" cols="50"></textarea><br><br>
        <label for="date">Date:</label>
        <input type="date" name="date" id="date"><br><br>
        <label for="image">Image:</label>
        <input type="file" name="image" id="image"><br><br>
        <button type="submit" name="submit" value="submit">Submit</button>
    </form>
    <?php
}
