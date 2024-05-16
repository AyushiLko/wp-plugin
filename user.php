<?php
/*
Plugin Name: User Page
Version: 1.0 
Description: This is user form. 
Author: Riya
*/


//function test(){
   // $name = "Ayushi";
    //return $name;
//}
//add_shortcode('my_name', 'test');

add_shortcode('user_data','user_data_page');
function user_data_page(){
   // ob_start();

    ?>
    <h1>USER FORM</h1>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
    <form action="" method="POST">
        <label for="username">User Name:</label>
        <input type="text" id="username"></br>
        <span id="username_Error" style="color:red"></span></br>
        <label for="useremail">User Email:</label>
        <input type="text" id="useremail"></br>
        <span id="useremail_Error" style="color:red"></span></br>
        <label for="userphone">User Phone:</label>
        <input type="text" id="userphone"></br>
        <span id="userphone_Error" style="color:red"></span></br>
        <label for="userdate">User Date:</label>
        <input type="date" id="userdate"></br>
        <span id="userdate_Error" style="color:red"></span></br>
        <label for="userpassword">User password:</label>
        <input type="password" id="userpassword"></br>
        <span id="userpassword_Error" style="color:red"></span></br>
        <button type="submit" id="submit">Submit</button>
</form>
<script>
    $(document).ready(function(){
        $("#submit").click(function(e){
            e.preventDefault();
            var name= $("#username").val();
            if(name==""){
                $("#username_Error").text("Please enter the data");
                return;
            } else{
                $("#username_Error").text("");
            }
            var email= $("#useremail").val();
            if(email==""){
                $("#useremail_Error").text("Please enter the data");
                return;
            } else{
                $("#useremail_Error").text("");
            }
            var phone= $("#userphone").val();
            if(phone==""){
                $("#userphone_Error").text("Please enter the data");
                return;
            } else{
                $("#userphone_Error").text("");
            }
            var date= $("#userdate").val();
            if(date==""){
                $("#userdate_Error").text("Please enter the data");
                return;
            } else{
                $("#userdate_Error").text("");
            }
            var password= $("#userpassword").val();
            if(password==""){
                $("#userpassword_Error").text("Please enter the data");
                return;
            } else{
                $("#userpassword_Error").text("");
            }
            jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'Registration_page',
            name: name,
            email: email,
            phone: phone,
            date: date,
            password: password
        },
        success: function (response) {
            if (response.success) {
                alert(response.data);
                location.reload();
            }
        },
        error: function (response) {
            if (response.error) {
                alert(response.data);
                location.reload();
               
            }
        }
      });
        });
        });
    
    </script>
    <?php
   // $output = ob_get_contents();
   // return $output;
   // ob_end_clean();
}
add_action('wp_ajax_Registration_page','Registration_page');
function Registration_page(){
    if(isset($_POST['name'])){
        $username = sanitize_text_field($_POST['name']);
        $useremail = sanitize_text_field($_POST['email']);
        $userphone = sanitize_text_field($_POST['phone']);
        $userdate = sanitize_text_field($_POST['userdate']);
        $userpassword = sanitize_text_field($_POST['userpassword']);

        $user_id=wp_create_user($username,$userpassword,$useremail);
        if($user_id){
            update_user_meta($user_id,"userphone",$userphone);
            update_user_meta($user_id,"userdate",$userdate);
            wp_send_json_success("User created successfully");
        }else{
            wp_send_json_error("User not created!");
        }
    }
    }

?>