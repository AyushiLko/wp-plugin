<?php
/*
Plugin Name: Jquery
Version: 1.0 
Description: Perform jquery functions on it.
Author: John Williams
*/
add_action('admin_menu','jquery_function_page');
function jquery_function_page(){
    add_menu_page('User details','User details','manage_options','users_page','users_page_menu');
}
function users_page_menu(){
    ?>
    <h1>FORM</h1>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<script>
    $(document).ready(function(){
        $("#submit").click(function(e){
            e.preventDefault();
           var name= $("#username").val();
           if(name==""){
            $("#username_Error").text("Please enter username");
            return;
           }
           var email= $("#useremail").val();
           if(email==""){
            $("#useremail_Error").text("Please enter useremail");
            return;
           }
           var password= $("#userpassword").val();
           if(password==""){
            $("#userpassword_Error").text("Please enter userpassword");
            return;
           }
           alert(name+' '+email+' '+password);
        });
    });
    </script>
    <body>
    <p>This is a paragraph .</p>
    <form action="" method="POST">
        <label for="username">User Name:</label>
        <input type="text" id="username" name="username"></br>
        <span id="username_Error" style="color:red"></span></br>
        <label for="useremail">User Email:</label>
        <input type="text" id="useremail" name="useremail"></br>
        <span id="useremail_Error" style="color:red"></span></br>
        <label for="userpassword">User Password:</label>
        <input type="password" id="userpassword" name="userpassword"></br>
        <span id="userpassword_Error" style="color:red"></span></br>
        <button type="submit" id="submit" name="submit">Submit</button>
</form>
</body>

    <?php
}
?>