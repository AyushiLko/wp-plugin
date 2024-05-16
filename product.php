<?php
/*
Plugin name: Add Products
Version:1.0 
Author: Product Name
*/
add_shortcode('product_name','product_page');
function product_page(){
    if(isset($_POST['submit'])){
        $productname = sanitize_text_field($_POST['productname']);
        $productdescription = sanitize_text_field($_POST['productdescription']);
        $productprice = sanitize_text_field($_POST['productprice']);

        $post_data = array(
            "post_title" => $productname,
            "post_content" => $productdescription,
            "post_type" => "product",
            "post_status" => "publish",
            "post_author" => 1,
        );
        $post_id = wp_insert_post($post_data);
        update_post_meta($post_id,'_price',$productprice);
        if ($post_id) {
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
                    update_post_meta($post_id,'_thumbnail_id',$attachment_id);
                    echo "Post created successfully";
                } else {
                    // Display error message if the upload failed
                    echo "Error uploading image: " . $attachment_id->get_error_message();
                }
            } else {
                // Display a message if no image was uploaded
                echo "Image not uploded!";
            }
        } else {
            echo "Error creating post";
        }
    }
    ?>
    <h1>PRODUCT FORM</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="productname">Product Name:</label>
        <input type="text" name="productname" id="productname"></br></br>
        <label for="productdescription">Product Description:</label>
        <textarea name="productdescription" id="productdescription" rows="6" cols="50"></textarea></br></br>
        <label for="productimage">Product Image:</label>
        <input type="file" name="image" id="image"></br></br>
        <label for="productprice">Product Price:</label>
        <input type="text" name="productprice" id="productprice"></br></br>
        <button type="submit" name="submit" id="submit">Submit</button>
</form>
    <?php
}
?>