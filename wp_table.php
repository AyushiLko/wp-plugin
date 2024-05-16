<?php
/*
Plugin Name: Wp_list_table
Version:1.0 
Author: Mr WP
*/
add_action('admin_menu','wp_list_table');
  function wp_list_table(){
    add_menu_page('WP List','WP List','manage_options','wp-list-post','wp_list_menu');
  }
  function wp_list_menu(){

    if (isset($_POST['submit'])) {
        global $wpdb;
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $address = sanitize_text_field($_POST['address']);
        $pincode = sanitize_text_field($_POST['pincode']);
        $table_name = $wpdb->prefix . 'students';
        $result = $wpdb->insert($table_name, array(
            'Name' => $name,
            'Email' => $email,
            'PhoneNumber' => $phone,
            'Address' => $address,
            'Pincode' => $pincode
        ));
        echo "Form submitted successfully";
    }
    ?>
    <h1>Student Form</h1>
    <form action="" method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name"></br></br>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email"></br></br>
    <label for="phone">PhoneNumber:</label>
    <input type="text" id="phone" name="phone"></br></br>
    <label for="address">Address:</label>
    <input type="text" id="address" name="address"></br></br>
    <label for="pincode">Pincode:</label>
    <input type="text" id="pincode" name="pincode"></br></br>
    <button type="submit" value="submit" name="submit">Submit</button>
  </form>
    <?php
     if (!class_exists('WP_List_Table')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php'); // Corrected typo in 'required_once'
    }
    class Student_List_Table extends WP_List_Table{
        public function prepare_items() {
            $orderby = isset($_GET['orderby']) ? trim($_GET['orderby']) : 'name';
            $order = isset($_GET['order']) ? trim($_GET['order']) : 'ASC';
            $search_term = isset($_POST['s']) ? trim($_POST['s']) :"";
            $datas = $this->student_table($orderby,$order,$search_term);
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
        public function student_table($orderby ='',$order ='',$search_term ='') {
            global $wpdb;
            $table_name = $wpdb->prefix . 'students';
            $query = "SELECT * FROM $table_name";
            if (!empty($search_term)){
                $query .= $wpdb->prepare(" WHERE Name LIKE %s", $search_term);
            }
            if ($orderby == 'name') {
                $query .= " ORDER BY Name $order";
            }
            $result = $wpdb->get_results($query, ARRAY_A);        
            $student_data = array();
            if (count($result) > 0) {
                foreach ($result as $data) {
                    $student_data[] = [
                        'id' => $data['Id'],
                        'name' => $data['Name'],
                        'email' => $data['Email'],
                        'phone_number' => $data['PhoneNumber'],
                        'address' => $data['Address'],
                        'pincode' => $data['Pincode'],
                    ];
                }
                return $student_data;
            }
        }
        public function get_columns() {
            $columns = array(
                'id' => 'ID',
                'name' => 'Name',
                'email' => 'Email',
                'phone_number' => 'Phone Number',
                'address' => 'Address',
                'pincode' => 'Pincode'
            );
            return $columns;
        }
        public function get_hidden_columns()
    {
        return array();
    }
        public function get_sortable_columns() {
            return array(
                'name' => array ('name', true),
            );
        }
        public function column_default($item, $column_name) {
            switch ($column_name) {
                case 'id':
                case 'name':
                case 'email':
                case 'phone_number':
                    case 'address':
                    case 'pincode':
                    return $item[$column_name];
                default:
                    return 'No Post Found';
            }
        } 
    }
    function student_list_table() {
        $owt_table = new Student_List_Table();
        $owt_table->prepare_items();        
        echo '<h2>This is List</h2>';  
        echo '<form  method="Post" name="s" action="' .$_SERVER['PHP_SELF'].'?page=wp-list-post">';
        $owt_table -> search_box("Search Post(s)","Search_post_id");
        echo '</form>';    
        $owt_table->display();
    }
    student_list_table(); 
  }
  ?>
