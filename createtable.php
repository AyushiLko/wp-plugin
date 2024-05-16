<?php
/*
Plugin Name: User Listing
Version:1.0 
Author: Harry
*/

add_action('admin_menu','User_Listing');
    function User_Listing(){
        add_menu_page('Users Listing','Users Listing','manage_options','Listing-post','Listing_Post_Menu');

    }
    function Listing_Post_Menu(){
        $users = get_users();
        //Delete Data
        if(isset($_GET['user_id'])){
        $user_ID = $_GET['user_id'];
        wp_delete_user($user_ID);
        echo "user deleted successfully";
        }
        ?>
        
        <h1> USER LISTING TABLE </h1>
        <table class="wp-list-table widefat fixed striped">
        <tr>
            <thead>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Date Of Birth</th>
                <th>Phone Number</th>
                <th>Image</th>
                <th>Delete</th>
                <th>Update</th>
    </tr>
    </thead>
    <tbody>
        <?php
        foreach($users as $row){
        $user_id = $row->ID;
        $fname = get_user_meta($user_id, "first_name", true);
        $lname = get_user_meta($user_id, "last_name", true);
        $email = $row->user_email;
        $date_of_birth = get_user_meta($user_id, "date_of_birth", true);
        $phone = get_user_meta($user_id, "phone", true);
        $image = get_user_meta($user_id, "wp_user_avatar", true);
        $image_url = wp_get_attachment_url($image);
        //print_r($fname);
        ?>
        <tr>
            <td><?php echo $fname; ?></td>
            <td><?php echo $lname; ?></td>
            <td><?php echo $email; ?></td>
            <td><?php echo $date_of_birth; ?></td>
            <td><?php echo $phone; ?></td>
            <td><img src="<?php echo $image_url; ?>" alt="Image not found" style="width: 100px; height: 100px;"/></td>
            <td><a class="btn btn-danger" href="http://localhost/wordpress/wp-admin/admin.php?page=Listing-post&&user_id=<?php echo $user_id; ?>">Delete</a></td>
            <td><a class="btn btn-success" href="http://localhost/wordpress/wp-admin/admin.php?page=create_user_menu&&user_id=<?php echo $user_id; ?>">Update</a></td>
            
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
