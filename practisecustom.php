<?php
/*
Plugin Name: Custom Post 
Description: For the custom post.
Version: 1.0 
Author: vidya
*/
add_shortcode('custom_post','custom_post_page');
function custom_post_page()
{
    global $wpdb;
    // Handling form submission
    if(isset($_POST['submit'])){
        $customname = sanitize_text_field($_POST['customname']);
        $customemail = sanitize_text_field($_POST['customemail']);
        $customphone = sanitize_text_field($_POST['customphone']);
        $customimage = sanitize_text_field($_FILES['customimage']);
       
        // Check if an ID is provided for updating
        if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'update'){
            $id = intval($_GET['id']);
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
    
            $attachment_id = media_handle_upload( 'customimage', 0);
            // Update existing record
            $result = $wpdb->update('custom', array(
                'customname' => $customname,
                'customemail' => $customemail,
                'customphone' => $customphone,
                'customimage' => $attachment_id,
            ), array('id' => $id));

            if($result === false){
                echo "Error updating post";
            }else{
                echo "Post updated successfully";
            }
            return;
        }

        // Insert new record
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $attachment_id = media_handle_upload( 'customimage', 0);
        $result = $wpdb->insert('custom', array(
            'customname' => $customname,
            'customemail' => $customemail,
            'customphone' => $customphone,
            'customimage' => $attachment_id,
        ));
        if (is_wp_error($result)){
            echo "Error creating post";
        }else{
            echo "Form submitted successfully";
        }
    }
    // delete 
    if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])){
        $id = intval($_GET['id']);
        $result = $wpdb->delete('custom', array('id' => $id));
        if($result === false){
            echo "Error deleting post";
        }else{
            echo "Post deleted successfully";
        }
    }
    if(isset($_GET['id']) && isset($_GET['action'])){
    $id = intval($_GET['id']);
    $data = $wpdb->get_row("SELECT * FROM `custom` WHERE id=$id");
    $customname = $data->customname;
    $customemail = $data->customemail;
    $customphone = $data->customphone;
    $customimage = $data->customimage;
    }
    ?>
    <h1>Custom Table</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="customname">Custom Name:</label>
        <input type="text" name="customname" id="customname" value="<?php echo isset($customname) ? $customname : ''; ?>"><br><br>
        <label for="customemail">Custom Email:</label>
        <input type="text" name="customemail" id="customemail" value="<?php echo isset($customemail) ? $customemail : ''; ?>"><br><br>
        <label for="customphone">Custom Phone:</label>
        <input type="text" name="customphone" id="customphone" value="<?php echo isset($customphone) ? $customphone : ''; ?>"><br><br>
        <label for="customimage">Custom Image:</label>
        <input type="file" name="customimage" id="customimage" value="<?php echo isset($customimage) ? $customimage : ''; ?>"><br><br>
        <button type="submit" name="submit" id="submit"><?php echo isset($_GET['id']) ? 'Update' : 'Submit'; ?></button>
    </form>
    <h2>Custom Table</h2>
    <table class="wp_list_table widefat fixed striped">
        <thead>
            <tr>
                <th>Custom Name</th>
                <th>Custom Email</th>
                <th>Custom Phone</th>
                <th>Custom Image</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $results = $wpdb->get_results("SELECT * FROM `custom`", ARRAY_A);
            foreach($results as $rows){
                $customname = $rows['customname'];
                $customemail = $rows['customemail'];
                $customphone = $rows['customphone'];
                $customimage = $rows['customimage'];
                $image_url = wp_get_attachment_url($customimage);
                $custom_id = $rows['id'];
            ?>
            <tr>
                <td><?php echo $customname ?></td>
                <td><?php echo $customemail ?></td>
                <td><?php echo $customphone ?></td>
                <td><img src="<?php echo $image_url; ?>" alt="image not found" style="width: 100px; height: 100px;"></td>
                <td><a href="?action=update&id=<?php echo $custom_id; ?>" class="btn btn-success">Update</a></td>
                <td><a href="?action=delete&id=<?php echo $custom_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a></td>

                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}

?>
