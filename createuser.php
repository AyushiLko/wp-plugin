<?php
/*
Plugin Name: Create User
Version: 1.0
Author: John Doe
*/
//add_shortcode('user_listing','create_user_page');
add_action('admin_menu', 'create_user_menu');
function create_user_menu()
{
    add_menu_page('Create User', 'Create User', 'manage_options', 'create_user_menu', 'create_user_page');
}

function create_user_page()
{ 
    $user_ID = isset($_GET['user_id']) ? $_GET['user_id'] : null;

    if (isset($_POST['submit'])) {
        $fname = sanitize_text_field($_POST['fname']);
        $lname = sanitize_text_field($_POST['lname']);
        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
        $date_of_birth = sanitize_text_field($_POST['date_of_birth']);
        $phone = sanitize_text_field($_POST['phone']);

        if ($user_ID) {
            // Update existing user
            $user_data = array(
                'ID' => $user_ID,
                'user_email' => $email,
                'user_pass' => $password,
                'first_name' => $fname,
                'last_name' => $lname,
            );

            $user_id = wp_update_user($user_data);
        } else {
            // Create new user
            $user_id = wp_create_user($email, $password, $email);

            if (is_wp_error($user_id)) {
                echo "Error creating user";
                return;
            }
        }

        // Update user meta
        update_user_meta($user_id, "first_name", $fname);
        update_user_meta($user_id, "last_name", $lname);
        update_user_meta($user_id, "date_of_birth", $date_of_birth);
        update_user_meta($user_id, "phone", $phone);

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $attachment_id = media_handle_upload('image', $user_id);

            if (!is_wp_error($attachment_id)) {
                update_user_meta($user_id, "wp_user_avatar", $attachment_id);
            } else {
                echo "Error uploading image";
                return;
            }
        }

        echo "Form submitted successfully";
    }

    // Retrieve user data if editing
    if ($user_ID) {
        $user = get_userdata($user_ID);
        $fname = get_user_meta($user_ID, 'first_name', true);
        $lname = get_user_meta($user_ID, 'last_name', true);
        $date_of_birth = get_user_meta($user_ID, 'date_of_birth', true);
        $phone = get_user_meta($user_ID, 'phone', true);
        $email = $user->user_email;
    }
    ?>
    
    <h1>Sign IN Form</h1>
    <form action="" method="POST" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
        <label for="fname">First Name:</label>
        <input type="text" name="fname" id="fname" value="<?php echo isset($fname) ? $fname : ''; ?>" required><br/>
        <label for="lname">Last Name:</label>
        <input type="text" name="lname" id="lname" value="<?php echo isset($lname) ? $lname : ''; ?>" required><br/>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" required><br/> <!-- Use type="email" for email fields -->
        <label for="password">Password:</label>
        <input type="password" name="password" id="password"  required><br/> <!-- Use type="password" for password fields -->
        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" name="date_of_birth" id="date_of_birth" value="<?php echo isset($date_of_birth) ? $date_of_birth : ''; ?>" required><br/>
        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" id="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" required><br/>
        <label for="image">Image:</label>
        <input type="file" name="image" id="image"/><br/>
        <button type="submit" name="submit">Submit</button>
    </form>
<?php
}
?>

