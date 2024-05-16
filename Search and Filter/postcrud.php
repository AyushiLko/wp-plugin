<?php
/*
Plugin Name: Post Maker
Description: The post maker type.
Version: 1.0 
Author: Siddharth
*/

add_action('admin_menu', 'post_file');
function post_file() {
    add_menu_page('Post Type', 'Post Type', 'manage_options', 'post_maker', 'post_maker_menu');
    add_submenu_page('post_maker', 'Post Subfields', 'Post Subfields', 'manage_options', 'post_file', 'post_file_menu');
}

function post_maker_menu() {
    if (isset($_POST['submit'])) {
        $postname = sanitize_text_field($_POST['postname']);
        $postemail = sanitize_text_field($_POST['postemail']);
        $postphone = sanitize_text_field($_POST['postphone']);
        $postaddress = sanitize_text_field($_POST['postaddress']);
        $postimage = $_FILES['postimage'];

        if (isset($_GET['post_id']) && isset($_GET['action']) && $_GET['action'] == 'update') {
            $post_id = intval($_GET['post_id']);
            $post_result = array(
                'ID' => $post_id,
                'post_title' => $postname,
                'post_status' => 'publish',
                'post_type' => 'Lucknow',
            );
            $post_id = wp_update_post($post_result);

            if ($postimage['size'] > 0) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                $attachment_id = media_handle_upload('postimage', $post_id);
                update_post_meta($post_id, '_thumbnail_id', $attachment_id);
                
            }
            update_post_meta($post_id, 'email', $postemail);
            update_post_meta($post_id, 'phone', $postphone);
            update_post_meta($post_id, 'address', $postaddress);

            echo "Post updated successfully";
        } else {
            $post_result = array(
                'post_title' => $postname,
                'post_status' => 'publish',
                'post_type' => 'Lucknow',
            );

            // Insert new post
            $post_id = wp_insert_post($post_result);

            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            $attachment_id = media_handle_upload('postimage', $post_id);
            update_post_meta($post_id, 'address', $postaddress);
            update_post_meta($post_id, '_thumbnail_id', $attachment_id);
            update_post_meta($post_id, 'phone', $postphone);
            update_post_meta($post_id, 'email', $postemail);

            echo "Post inserted successfully";
        }
    }

   
    if (isset($_GET['post_id']) && isset($_GET['action']) && $_GET['action'] == 'update') {
        $postID = intval($_GET['post_id']);
        $postname = get_the_title($postID);
        $postemail = get_post_meta($postID, 'email', true);
        $postphone = get_post_meta($postID, 'phone', true);
        $postaddress = get_post_meta($postID, 'address', true);
    }
    ?>
    <h1>Post Form</h1>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="postname">Post Name:</label>
            <input type="text" name="postname" id="postname" value="<?= isset($postname) ? $postname : '' ?>"><br><br>
            <label for="postemail">Post Email:</label>
            <input type="text" name="postemail" id="postemail" value="<?= isset($postemail) ? $postemail : '' ?>"><br><br>
            <label for="postphone">Post Phone:</label>
            <input type="text" name="postphone" id="postphone" value="<?= isset($postphone) ? $postphone : '' ?>"><br><br>
            <label for="postaddress">Post Address:</label>
            <input type="text" name="postaddress" id="postaddress" value="<?= isset($postaddress) ? $postaddress : '' ?>"><br><br>
            <label for="postimage">Post Image:</label>
            <input type="file" name="postimage" id="postimage"><br><br>
            <button type="submit" name="submit" id="submit">Submit</button>
        </form>
    </body>
    </html>
    <?php
}

function post_file_menu() {
    if (isset($_GET['delete']) && isset($_GET['post_id'])) {
        $post_ID = intval($_GET['post_id']);
        $result = wp_delete_post($post_ID);
        if ($result) {
            echo "Post deleted successfully.";
        } else {
            echo "Post could not be deleted.";
        }
    }

    ?>
    <h2>Table</h2>
    <table class="wp_list_table widefat fixed striped">
        <thead>
            <tr>
                <th>Post Name</th>
                <th>Post Email</th>
                <th>Post Phone</th>
                <th>Post Address</th>
                <th>Post Image</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $posts = get_posts(array(
            'post_status' => 'publish',
            'post_type' => 'Lucknow',
            'numberposts' => -1
        ));

        foreach ($posts as $post) {
            $post_id = $post->ID;
            $postname = get_the_title($post_id);
            $postemail = get_post_meta($post_id, 'email', true);
            $postphone = get_post_meta($post_id, 'phone', true);
            $postaddress = get_post_meta($post_id, 'address', true);
            $postimage = get_post_meta($post_id, '_thumbnail_id', true);
            $image_url = wp_get_attachment_url($postimage);
            ?>
            <tr>
                <td><?= $postname; ?></td>
                <td><?= $postemail; ?></td>
                <td><?= $postphone; ?></td>
                <td><?= $postaddress; ?></td>
                <td><img src="<?= $image_url; ?>" alt="Image" width="100" height="100"></td>
                <td><a class="btn btn-success" href="<?= admin_url('admin.php?page=post_maker&&action=update&&post_id=' . $post_id); ?>">Update</a></td>
                <td><a class="btn btn-danger" href="<?= admin_url('admin.php?page=post_file&&delete=delete&&post_id=' . $post_id); ?>">Delete</a></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}
?>
