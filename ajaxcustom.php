<?php
/*
Plugin Name: Custom Plugin
Version: 1.0 
Description: Making custom plugin.
Author: Custom
*/
add_shortcode('custom_plugin','customregistrationform');
function customregistrationform(){
    ?>
    <h1>CUSTOM FORM</h1>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<style>
    span{
        color:red;
    }
    </style>
    <form action="" method="POST">
        <label for="name"> Name:</label>
        <input type="text" id="name"></br>
        <span id="name_Error"></span></br>
        <label for="email">Email:</label>
        <input type="text" id="email"></br>
        <span id="email_Error"></span></br>
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone"></br>
        <span id="phone_Error"></span></br>
        <label for="address">Address:</label>
        <input type="text" id="address"></br>
        <span id="address_Error"></span></br>
        <label for="pincode">PinCode:</label>
        <input type="text" id="pincode"></br>
        <span id="pincode_Error"></span></br>
        <button type="submit" id="submit">Submit</button>
</form>
<script>
    $(document).ready(function(){
        $("#submit").click(function(e){
            e.preventDefault();
                var name= $("#name").val();
                if (name==""){
                    $("#name_Error").text("Please enter the name");
                    return;
                }else{
                    $("#name_Error").text("");
                }
                var email= $("#email").val();
                if (email==""){
                    $("#email_Error").text("Please enter the email");
                    return;
                }else{
                    $("#email_Error").text("");
                }
                var phone= $("#phone").val();
                if (phone==""){
                    $("#phone_Error").text("Please enter the phone");
                    return;
                }else{
                    $("#phone_Error").text("");
                }
                var address= $("#address").val();
                if (address==""){
                    $("#address_Error").text("Please enter the address");
                    return;
                }else{
                    $("#addressError").text("");
                }
                var pincode= $("#pincode").val();
                if (pincode==""){
                    $("#pincode_Error").text("Please enter the pincode");
                    return;
                }else{
                    $("#pincode_Error").text("");
                }



                var formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('address', address);
            formData.append('pincode', pincode);
            formData.append('action', 'custom_page');
            
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
            success: function (response){
                if(response.success) {
                    alert(response.data);
                    location.reload();
                }
            }
        });
    });
    });

    </script>
    <?php
}
add_action('wp_ajax_custom_page','custom_page');
function custom_page(){
    if(isset($_POST['name'])){
        global $wpdb;
        $name = ($_POST['name']);
        $email = ($_POST['email']);
        $phone = ($_POST['phone']);
        $address = ($_POST['address']);
        $pincode = ($_POST['pincode']);
        $table_name = $wpdb->prefix . 'students';
        $result = $wpdb->insert($table_name, array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'pincode' => $pincode
        ));
        if($result){
            wp_send_json_success("Data inserted successfully");
        }else{
            wp_send_json_error("Data not inserted!");
         }
      }
    }

?>

