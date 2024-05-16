<?php
/*
Plugin Name: Custom Table 
Description: This is a custom table. 
Version:1.0 
Author: Mr AS
*/
add_shortcode('custom_table','custom_table_page');
function custom_table_page(){
    ?>
    <h1>Table</h1>
    <?php
// Custom table 
global $wpdb;
$table_name = $wpdb->prefix . 'custom_table';

// Check if search query is submitted
if (isset($_GET['search'])) {
    // Sanitize the search query
    $search_query = sanitize_text_field($_GET['search']);
    
    // Query the custom table for matching records
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE column_name LIKE '%%%s%%'",
            $search_query
        )
    );

    // Display search results
    if ($results) {
        foreach ($results as $result) {
            // Display each result
            echo $result->column_name . '<br>';
        }
    } else {
        echo 'No results found.';
    }
}
?>

<!-- Search form -->
<form method="get" action="">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php
}
?>
