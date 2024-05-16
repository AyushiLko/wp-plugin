<?php
/*
Plugin Name: Job Listing
Description: The job listing profile. 
Version: 1.0 
Author: Job
*/

add_action('admin_menu', 'job_listing');
function job_listing(){
    add_menu_page('Job Profile','Job Profile','manage_options','job_profile','job_profile_menu');
    add_submenu_page('job_profile','Job Portal','Job Portal','manage_options','job_menu','job_menu_page');
}
function job_profile_menu()
{
    if (isset($_POST['submit'])) {
        global $wpdb;
        $name = sanitize_text_field($_POST['name']);
        $task = sanitize_text_field($_POST['task']);
        $taskstatus = sanitize_text_field($_POST['taskstatus']);

        $wpdb->insert("wp_custom_jobs", array(
            "name" => $name,
            "task" => $task,
            "task_status" => $taskstatus
        ));

        if ($wpdb->last_error) {
            echo "Data could not be inserted. Error: " . $wpdb->last_error;
        } else {
            echo "Data inserted successfully";
        }
    }
    ?>
    <h1>Job Listing Form</h1>
    <div>
    <form method="POST">
        <label for="task">Name:</label>
        <input type="text" name="name" id="name"></br></br>
        <label for="task">Task:</label>
        <input type="text" name="task" id="task"></br></br>
        <label for="task">Task Status:</label>
        <select name="taskstatus" id="taskstatus">
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
        </select></br></br>

        <input type="submit" name="submit" value="submit">
    </form>
</div>
    <?php
}
function job_menu_page(){
    global $wpdb;
    ?>
    <h1>TABLE</h1>
    <form method="GET" action="">
        <input type="text" name="search">
        <input type="submit" value="search"></br></br>
    </form>
    <form method="POST" action="">
    <select name="taskstatus">
           <?php
                foreach ($categories as $category) :
            ?>
                    <option value="<?php echo esc_attr($category->name); ?>" <?php selected($category->name, $_GET['f']); ?>><?php echo esc_html($category->name); ?></option>
            <?php 
            endforeach;
            ?>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
        </select>
        <input type="submit" name="submit" value="filter">
    </form>
    <table class="wp_list_table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Task</th>
                <th>Task Status</th>
            </tr>
        </thead>
        <tbody>
            <?php

            //Pagination

              global $wpdb;
              $table_name = $wpdb->prefix . `wp_custom_jobs`;
              $users_per_page = 3;
              $current_page = $_GET['paged'];
              $offset = ($current_page - 1) * $users_per_page;
              $total_users = $wpdb->get_var("SELECT COUNT(*) FROM  `wp_custom_jobs`");
            
            //Search

            if(isset($_GET['search']) && !empty($_GET['search'])) {
                $search = sanitize_text_field($_GET['search']);
                $data = $wpdb->get_results("SELECT * FROM `wp_custom_jobs` WHERE task_status LIKE '%$search%'", ARRAY_A);

            } else {
                $data = $wpdb->get_results("SELECT * FROM `wp_custom_jobs` LIMIT $users_per_page OFFSET $offset", ARRAY_A);
        

            }
            foreach ($data as $row){
                $name = $row['name'];
                $task = $row['task'];
                $taskstatus = $row['task_status'];
                ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $task; ?></td>
                    <td><?php echo $taskstatus; ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
    $total_pages = ceil($total_users / $users_per_page);
    if ($total_pages > 1){
        echo '<div class="pagination">';
        echo paginate_links(array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '?paged=%#%',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_text' => __('<< prev'),
            'next_text' => __('next >>'),
        ));
        echo '</div>';
    }
}
?>
