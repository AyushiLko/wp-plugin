<?php
/*
Plugin Name: Practise CRUD
Description: This is for the practise.
Version: 1.0 
Author: CRUD
*/
add_action('admin_menu','crud_operator_page');
function crud_operator_page(){
    add_menu_page('CRUD OPERATOR','CRUD OPERATOR','manage_options','crud_menu','crud_menu_page');
    add_submenu_page('crud_menu','USER LIST','USER LIST','manage_options','user_list_submenu','user_listing_page'); 
}
function crud_menu_page(){
    if(isset($_POST['submit'])){
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_text_field($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $address = sanitize_text_field($_POST['address']);
        $password = sanitize_text_field($_POST['password']);
        $image = $_FILES['image'];

        if(isset($_GET['user_id']) ?$_GET['user_id']:''){
        $ID = isset($_GET['user_id'])?$_GET['user_id']:'';
            $user_data = array(
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'ID' => $ID,
        );
        $user_id = wp_update_user($user_data);
        }else{
            $user_id = wp_create_user($email, $password, $email);
        }
            if($user_id){
                update_user_meta($user_id,'name',$name);
                update_user_meta($user_id,'email',$email);
                update_user_meta($user_id,'phone',$phone);
                update_user_meta($user_id,'address',$address);
                update_user_meta($user_id,'password',$password);


                // image upload
                if (!empty($_FILES['image'])) {
                    // Include necessary files for media handling
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    
                    // Upload and attach the image to the post
                    $attachment_id = media_handle_upload('image', $user_id);
                    // Check if the upload was successful
                    if (!is_wp_error($attachment_id)) {
                        update_user_meta($user_id,'wp_user_avatar',$attachment_id);
                        echo "User created successfully";
                    } else {
                        // Display error message if the upload failed
                        echo "Error uploading image: " . $attachment_id->get_error_message();
                    }
                } else {
                    // Display a message if no image was uploaded
                    echo "Image not uploded!";
                }
            } else {
                echo "Error creating user";
            }

        }

          // Update

    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        $name = get_user_meta($user_id, 'name', true);
        $email = get_user_meta($user_id, 'email', true);
        $phone = get_user_meta($user_id, 'phone', true);
        $address = get_user_meta($user_id, 'address', true);
        $password = get_user_meta($user_id, 'password', true);
        $image = get_user_meta($user_id, 'wp_user_avatar', true);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRACTISE FORM</title>
</head>
<body>
    <h1>PRACTISE FORM</h1>
    <div>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name"  value="<?php echo isset($name) ? $name : ''; ?>" required><br/></br>
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" required></br></br>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" required></br></br>
        <label for="address">Address:</label>
        <input type="text" name="address" id="address" value="<?php echo isset($address) ? $address : ''; ?>" required></br></br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" value="<?php echo isset($password) ? $password : ''; ?>" required></br></br>
        <label for="image">Image:</label>
        <input type="file" name="image" id="image"></br></br>
        <button type="submit" name="submit" id="submit">Submit</button>
    </form>
</body>
</div>
</html>
<?php
}
function user_listing_page(){
     //Delete

     if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
       $result= wp_delete_user($user_id);
      
         if ($result == TRUE) {
            echo "Data deleted successfully.";
        }else{
            echo "Data is not deleted";
        }
    }
    ?>
    <h1>TABLE</h1>
    <form method="POST" role="search">
        <input type="text" name="search">
        <input type="submit" value="search"></br></br>
    </form>
    <table class="wp_list_table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Image</th>
                <th>Update</th>
                <th>Delete</th>
</tr>
</thead>
<tbody>
    <?php
    //Search Query
    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search_query=$_GET['search'] ? $_GET['search']:"";
    $args = array(
        'search'         => '*' . esc_attr( $search_query ) . '*',
        'search_columns' => array( 'name', 'email', 'phone', 'address','password','wp_user_avatar' ),
    );
    $users = get_users($args);
}else{
    $users = get_users();
}

    foreach($users as $row){
        $user_id = $row->ID;
        $name = get_user_meta($user_id,'name',true);
        $email = get_user_meta($user_id,'email',true);
        $phone = get_user_meta($user_id,'phone',true);
        $address = get_user_meta($user_id,'address',true);
        $password = get_user_meta($user_id,'password',true);
        $image = get_user_meta($user_id, "wp_user_avatar", true);
        $image_url = wp_get_attachment_url($image);
    
    ?>
    <tr>
        <td><?php echo $name; ?></td>
        <td><?php echo $email; ?></td>
        <td><?php echo $phone; ?></td>
        <td><?php echo $address; ?></td>
        <td><img src="<?php echo $image_url; ?>" alt="Image not found" style="width: 100px; height: 100px;"/></td>
        <td><a class="btn btn-success" href="http://localhost/wordpress/wp-admin/admin.php?page=crud_menu&&user_id=<?php echo $user_id; ?>">Update</a></td>
        <td><a class="btn btn-danger" href="http://localhost/wordpress/wp-admin/admin.php?page=user_list_submenu&&user_id=<?php echo $user_id; ?>">Delete</a></td>

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
