<?php
/*
Plugin Name: Ajax Crud
Version: 1.0 
Description: Making  plugin.
Author: Ajax
*/
add_shortcode('ajax_crud', 'ajax_crud_practise');
function ajax_crud_practise()
{
    global $wpdb;

    $items_per_page = 5;
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM `wp_crud2`");
    $current_page = max(1, get_query_var('paged'));
    $offset = ($current_page - 1) * $items_per_page;
    $result = $wpdb->get_results("SELECT * FROM `wp_crud2` LIMIT $items_per_page OFFSET $offset", ARRAY_A);
?>
    <div>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email">
            <label for="address">Address:</label>
            <input type="text" name="address" id="address">
            <label for="date">Date:</label>
            <input type="date" name="date" id="date">
            <input type="submit" id="submit" class="submit" value="submit">
            <input type="submit" id="update" class="update" value="update" style="display: none;">
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <?php
            if ($result) {
                foreach ($result as $row) {
            ?>
                    <tr data-id="<?php echo $row['id']; ?>">
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td>
                            <button type="submit" class="edit" data-id="<?php echo $row['id']; ?>">Edit</button>
                            <button type="submit" class="delete" data-id="<?php echo $row['id']; ?>">Delete</button>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php
    $total_pages = Ceil($total_items / $items_per_page);
    if ($total_pages > 1) {
        echo '<div class="pagination">';
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '?paged=%#%',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_text' => __('<< prev'),
            'next_text' => __('next >>'),
        ));
        echo '</div>';
    }
    ?>
    <script>
       jQuery(document).ready(function() {
    jQuery('#submit').click(function(e) {
        e.preventDefault();
        var $name = jQuery('#name').val();
        var $email = jQuery('#email').val();
        var $address = jQuery('#address').val();
        var $date = jQuery('#date').val();
        
        if ($name === '' || $email === '' || $address === '' || $date === '') {
            alert('Please fill all fields');
            return;
        } else {
            var formData = new FormData();
            formData.append('name', $name);
            formData.append('email', $email);
            formData.append('address', $address);
            formData.append('date', $date);
            formData.append('action', 'add_mukesh');
            
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                        alert('Data saved successfully');
                        load_data();
                }
            });
        }
    }); 
            jQuery('.delete').click(function(e) {
                e.preventDefault();
                var confirmdelete = confirm('Are you sure you want to delete this item?');
                if (confirmdelete) {
                    var id = jQuery(this).data('id');
                    jQuery.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            id: id,
                            action: 'delete_mukesh'
                        },
                        success: function(response) {
                            if (response) {
                                alert('data deleted successfully');
                                load_data();
                            } else {
                                alert('data not deleted' + response.data);
                            }
                        }
                    });
                }
            });
            jQuery('.edit').click(function(e) {
                e.preventDefault();
                $id = jQuery(this).data('id');
                $name = jQuery(this).closest('tr').find('td:eq(0)').text();
                $email = jQuery(this).closest('tr').find('td:eq(1)').text();
                $address = jQuery(this).closest('tr').find('td:eq(2)').text();
                $date = jQuery(this).closest('tr').find('td:eq(3)').text();

                jQuery('#name').val($name);
                jQuery('#email').val($email);
                jQuery('#address').val($address);
                jQuery('#date').val($date);
                jQuery('.submit').hide();
                jQuery('.update').show().data('id', $id);
            });
            jQuery('#update').click(function(e) {
                e.preventDefault();
                var id = jQuery(this).data('id');
                var data = {
                    name: jQuery('#name').val(),
                    email: jQuery('#email').val(),
                    address: jQuery('#address').val(),
                    date: jQuery('#date').val(),
                    id: id,
                }
                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        edit_data: data,
                        action: 'edit_mukesh'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('data edited successfully');
                            load_data();
                        } else {
                            alert('data not edited' + response.data);
                        }
                    }
                });
            });
            function load_data() {
                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'show_mukesh',
                    },
                    success: function(response) {
                        if (response) {
                            jQuery('#table-body').html(response);
                        } else {
                            alert('data not found');
                        }
                    }
                });
            }
        });
    </script>
    <?php
}
add_action('wp_ajax_add_mukesh', 'add_mukesh');
function add_mukesh()
{
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $date = $_POST['date'];
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'crud2';
        $result = $wpdb->insert($table_name, array(
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'date' => $date,
        ));
        if ($result) {
            wp_send_json_success('data inserted successfully');
        } else {
            wp_send_json_error('data not inserted');
        }
    }
    wp_die();
}
add_action('wp_ajax_delete_mukesh', 'delete_mukesh');
function delete_mukesh()
{
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'crud2';
        $result = $wpdb->delete($table_name, array(
            'id' => $id
        ));
        if ($result) {
            wp_send_json_success('data deleted successfully');
        } else {
            wp_send_json_error('data not delete');
        }
    }
    wp_die();
}
add_action('wp_ajax_edit_mukesh', 'edit_mukesh');
function edit_mukesh()
{
    if (isset($_POST['edit_data'])) {
        $edit_value = $_POST['edit_data'];
        $id = intval($edit_value['id']);
        $name = sanitize_text_field($edit_value['name']);
        $email = sanitize_text_field($edit_value['email']);
        $address = sanitize_text_field($edit_value['address']);
        $date = sanitize_text_field($edit_value['date']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'crud2';
        $result = $wpdb->update(
           $table_name ,
            array(
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'date' => $date,
            ),
            array('id' => $id)
        );
        if ($result) {
            wp_send_json_success('data updated successfully');
        } else {
            wp_send_json_error('data not updated');
        }
    }
    wp_die();
}
add_action('wp_ajax_show_mukesh', 'show_mukesh');
function show_mukesh()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'crud2';
    $result = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    if ($result) {
        foreach ($result as $row) {
    ?>
            <tr data-id="<?php echo $row['id']; ?>">
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td>
                    <button type="submit" class="edit" data-id="<?php echo $row['id']; ?>">Edit</button>
                    <button type="submit" class="delete" data-id="<?php echo $row['id']; ?>">Delete</button>
                </td>
            </tr>
<?php
        }
    }
    wp_die();
}

