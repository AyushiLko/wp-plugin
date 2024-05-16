<?php
/*
Plugin Name:  Practice jQuery
Version: 1.0 
Description: Perform jQuery functions on it.
Author: Johny
*/

// Enqueue jQuery
function enqueue_jquery() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery');

// Add menu page
function jquery_function_menu(){
    add_menu_page('User data','User data','manage_options','users_menu','users_page_post');
}
add_action('admin_menu','jquery_function_menu');

// Display the form
function users_page_post(){
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Validation</title>
<link rel="stylesheet" href="<?php echo plugins_url('styles.css', __FILE__); ?>">
</head>
<body>
<style>
.error {
    color: red;
    font-size: 14px;
}
</style>
<script>
    jQuery(document).ready(function($) {
    $('#myForm').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission
        
        // Clear previous error messages
        $('.error').text('');
        
        // Validate the form inputs
        var name = $('#name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var isValid = true;
        
        // Name validation
        if (name === '') {
            $('#nameError').text('Please enter your name.');
            isValid = false;
        }
        
        // Email validation
        if (email === '') {
            $('#emailError').text('Please enter your email address.');
            isValid = false;
        } else if (!isValidEmail(email)) {
            $('#emailError').text('Please enter a valid email address.');
            isValid = false;
        }
        
        // Password validation
        if (password === '') {
            $('#passwordError').text('Please enter your password.');
            isValid = false;
        } else if (password.length < 6) {
            $('#passwordError').text('Password must be at least 6 characters long.');
            isValid = false;
        }
        
        // If all validations pass, submit the form
        if (isValid) {
            // Perform form submission (e.g., AJAX)
            // Example: $.post('submit.php', $('#myForm').serialize(), function(response) {});
            console.log('Form submitted successfully!');
        }
    });
});

// Function to validate email format
function isValidEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

    </script>


<form id="myForm">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter your name">
        <span class="error" id="nameError"></span>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email">
        <span class="error" id="emailError"></span>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password">
        <span class="error" id="passwordError"></span>
    </div>
    <button type="submit">Submit</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo plugins_url('script.js', __FILE__); ?>"></script>

</body>
</html>
<?php
}
?>