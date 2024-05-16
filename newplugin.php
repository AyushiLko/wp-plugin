<?php
/*
Plugin Name: WPBeginner Plugin
Version: 1.0 
Author: WPBeginner
*/

add_action('admin_menu', 'wlt_create_admin_menu');

function wlt_create_admin_menu() {
    add_menu_page('WP List Table', 'WP List Table', 'manage_options', 'wlt_create_post', 'wlt_create_admin_menu_cb');
}

function wlt_create_admin_menu_cb() {
    if (!class_exists('WP_List_Table')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php'); // Corrected typo in 'required_once'
    }

    class WLT_List_Table extends WP_List_Table {
        public function prepare_items() {
            $orderby = isset($_GET['orderby']) ? trim($_GET['orderby']) :"";
            $order = isset($_GET['order']) ? trim($_GET['order']) :"";
            $search_term = isset($_POST['s']) ? trim($_POST['s']) :"";
            $datas = $this->wlt_list_table_data($orderby,$order,$search_term); // Corrected function call
            $per_page = 3;
            $current_page = $this->get_pagenum();
            $total_items = count($datas);
            $this->set_pagination_args(array(
                "total_items" =>$total_items,
                "per_page" =>$per_page,
            ));

            $this->items= array_slice($datas,(($current_page -1) * $per_page),$per_page);
            $wlt_columns = $this->get_columns();
            $wlt_hidden = $this->get_hidden_columns();
            $wlt_sortable_columns = $this->get_sortable_columns(); // Corrected variable name
            $this->_column_headers = array($wlt_columns,$wlt_hidden,$wlt_sortable_columns); // Corrected assignment of column headers
        }

        public function wlt_list_table_data($orderby ='',$order ='',$search_term ='') {
            global $wpdb;
            if (!empty($search_term)){
                $my_posts = $wpdb->get_results(
                    "SELECT * from ".$wpdb->posts." WHERE post_title LIKE '% $search_term%' OR post_content LIKE '% $search_term%'"
                );
            } else {
                if ($orderby == "wlt_id" && $order == "desc"){
                    //wp_posts
                    $my_posts = $wpdb -> get_results ("SELECT * from ".$wpdb->posts. " WHERE post_type = 'post' AND post_status ='publish' ORDER BY ID DESC");
                } else {
                    $args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        
                    );
                    $my_posts = get_posts($args);
                }
            }
            $data_array = [];
          
           
            if($my_posts){
                foreach ($my_posts as $post){
                    $post_id = $post->ID;
                    $author_id = get_post_field('post_author',$post_id);
                    $author_name = get_the_author_meta('display_name',$author_id);
                    $data_array[] = [
                        'wlt_id' => $post_id,
                        'wlt_title' => get_the_title($post_id),
                        'wlt_publish_date' => get_the_date('Y,m,d', $post_id),
                        'wlt_post_type' => get_post_type($post_id),
                        'wlt_post_author' => $author_name
                    ];
                }
            }
            return $data_array;

        }

        public function get_columns() {
            $columns = array( // Corrected variable name and array syntax
                'wlt_id' => 'ID',
                'wlt_title' => 'Title',
                'wlt_publish_date' => 'Date',
                'wlt_post_type' => 'Post Type',
                'wlt_post_author' => 'Author'
            );
            return $columns;
        }
        public function get_hidden_columns()
    {
        return array();
    }
        public function get_sortable_columns() {
            return array(
                'wlt_id' => array('wlt_id', true),
                'wlt_title' => array('wlt_title', false),

            );
        }

        public function column_default($item, $column_name) {
            switch ($column_name) {
                case 'wlt_id':
                case 'wlt_title':
                case 'wlt_publish_date':
                    case 'wlt_post_type':
                    case 'wlt_post_author':
                    return $item[$column_name]; // Corrected array access
                default:
                    return 'No Post Found';
            }
        } 
    }
    function owt_show_data_list_table() {
        $owt_table = new WLT_List_Table();
        $owt_table->prepare_items();
        echo '<h3>This is List</h3>';
        echo '<form  method="Post" name="s" action="' .$_SERVER['PHP_SELF'].'?page=wlt_create_post">';
        $owt_table -> search_box("Search Post(s)","Search_post_id");
        echo '</form>';
        $owt_table->display();
    }
    owt_show_data_list_table();
}
?>
