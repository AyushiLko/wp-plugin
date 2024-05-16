<?php
/*
Plugin Name: Update Status
Description: Update Status Plugin
Author: Mukesh Kumar Maurya
*/
add_shortcode('update_status', 'update_status_page');
function update_status_page()
{
?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Resion</th>
                <th>Source</th>
                <th>Status</th>
                <th>Add Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'task';
            $result = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
            if ($result) {
                foreach ($result as $row) {
                    $status_text = '';

                    if ($row['status'] == 0) {
                        $status_text = 'Approved';
                    } elseif ($row['status'] == 1) {
                        $status_text = 'Rejected';
                    } elseif ($row['status'] == 2) {
                        $status_text = 'Pending';
                    } else {
                        $status_text = 'Unknown';
                    }
            ?>
                    <tr data-user-id="<?php echo $row['id']; ?>">
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['resion']; ?></td>
                        <td><?php echo $row['source']; ?></td>
                        <td><?php echo $status_text; ?></td>
                        <td>
                            <select class="status" data-user-id="<?php echo $row['id']; ?>">
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="pending">Pending</option>
                            </select>
                            <button class="submit">Add Status</button>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <script>
        jQuery(document).ready(function() {
            jQuery('.submit').click(function(e) {
                e.preventDefault();
                var status = jQuery(this).prev('select').val();
                var userid = jQuery(this).prev('select').data('user-id');
                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        status: status,
                        user_id: userid,
                        action: 'update_status'
                    },
                    success: function(response) {
                        if (response) {
                            alert('status updated successfully');
                            location.reload();
                        } else {
                            alert('error updating status:' + response.data);
                        }
                    }
                });
            });
        });
    </script>
<?php
}
add_action('wp_ajax_update_status', 'update_status');
function update_status()
{
    if (isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
        $status_data = $_POST['status'];
       
        $status_value = 0;
        if ($status_data  === 'rejected') {
            $status_value = 1;
        } elseif ($status_data === 'pending') {
            $status_value = 2;
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'task';
        $result = $wpdb->update(
            $table_name,
            array('status' => $status_value),
            array('id' => $user_id),
            array('%d'),
            array('%d')
        );
        if ($result !== false) {
            wp_send_json_success('status updated successfully');
        } else {
            wp_send_json_error('status not updated');
        }
    }
    wp_die();
}