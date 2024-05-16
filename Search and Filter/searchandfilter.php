<?php
/*
Plugin Name: Search Filter Pagination
Description: Adding Search Filter And Pagination
Version: 1.0 
Author: BY Search
*/
add_shortcode('search_filter','search_filter_page');
function search_filter_page(){
    if(isset($_POST['submit'])){
        $name = sanitize_text_field($_POST['name']);
        $task = sanitize_text_field($_POST['task']);
        $task_status = sanitize_text_field($_POST['taskstatus']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_jobs';
        $result = $wpdb->insert($table_name, array(
            'name' => $name,
            'task' => $task,
            'task_status' => $task_status
        ));
        if ($result){
            echo "Form submitted successfully";
        } else {
            echo "Form not submitted";
        }
    }
    // Display the form
    ?>
    <h1>Search Form</h1>
    <div>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name">
            <label for="task">Task:</label>
            <input type="text" name="task" id="task">
            <label for="taskstatus">Task Status:</label>
            <select name="taskstatus" id="taskstatus">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>
    <?php
}
?>
