<?php
/*
Plugin Name: Custom Product Display
Description: This plugin is responsible for displaying custom product data.
Version: 1.0
Author: Mukesh
*/

add_shortcode('custom_product_display', 'custom_product_data');
function custom_product_data()
{
    if (isset($_POST['submit'])) {
        $product_name = sanitize_text_field($_POST['product_name']);
        $product_description = sanitize_text_field($_POST['product_description']);
        $regular_price = sanitize_text_field($_POST['regular_price']);
        $sale_price = sanitize_text_field($_POST['sale_price']);
        $product_category = sanitize_text_field($_POST['category']);
        $product_data = array(
            'post_title' => $product_name,
            'post_content' => $product_description,
            'post_status' => 'publish',
            'post_type' => 'product',
            'tax_input' => array(
                'product_cat' => $product_category,
            ),
        );
        $post_id = wp_insert_post($product_data);
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $attachment_id = media_handle_upload('product_image', $post_id);
        if ($post_id) {
            update_post_meta($post_id, '_regular_price', $regular_price);
            update_post_meta($post_id, '_sale_price', $sale_price);
            update_post_meta($post_id, '_price', $sale_price);
            update_post_meta($post_id, '_thumbnail_id', $attachment_id);
            echo 'Product added successfully';
        } else {
            echo 'Product not added';
        }
    }
    $search_data = '';
    if(isset($_GET['search'])? $_GET['search'] :''){
        $search_data = $_GET['search'];
    }
?>
    <form method="post" enctype="multipart/form-data">
        <div>
            <div>
                <label for="product_name">Product Name</label>
                <input type="text" name="product_name">
            </div>
            <div>
                <label for="product_description">Product Description</label>
                <input type="text" name="product_description">
            </div>
            <div>
                <label for="regular_price">Regular Price</label>
                <input type="number" name="regular_price">
            </div>
            <div>
                <label for="sale_price">Sale Price</label>
                <input type="number" name="sale_price">
            </div>
            <div>
                <select name="category">
                    <option>Select Category</option>
                    <?php
                    $product_category = array(
                        'post_type' => 'product',
                        'taxonomy' => 'product_cat'
                    );
                    $categories = get_categories($product_category);
                    foreach ($categories as $category) {
                    ?>
                        <option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="product_image">Product Image</label>
                <input type="file" name="product_image">
            </div>
            <input type="submit" name="submit" value="Add Product">
        </div>
    </form>
    <form method="get">
        <input type="text" name="search" value="<?php echo $search_data; ?>">
        <input type="submit" value="search">
    </form>
    <form method="get">
        <select name="f">
            <option>select category</option>
            <?php
            $product_category = array(
                'post_type' => 'product',
                'taxonomy' => 'product_cat'
            );
            $categories = get_categories($product_category);
            foreach ($categories as $category) {
            ?>
                <option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
            <?php
            }
            ?>
        </select>
        <input type="submit" value="Filter">
        <a href="http://localhost/wordpress/index.php/filter-data/get-category/">RESET</a>
    </form>
    <?php
    $paged = get_query_var('paged');
    $items_per_page = 4;
    $args = array(
        'post_type' => 'product',
        'paged' => $paged,
        'posts_per_page' => $items_per_page,
        's' => $search_data,
    );
    if (isset($_GET['f']) ? $_GET['f'] : ''){
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $_GET['f'],
            ),
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()) {
    ?>
        <table>
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Category</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($query->have_posts()) {
                    $query->the_post();
                    $id = $query->the_ID();
                    $product = wc_get_product($id);
                ?>
                    <tr>
                        <td><?php the_post_thumbnail(); ?></td>
                        <td><?php the_title(); ?></td>
                        <td><?php the_content(); ?></td>
                        <td><?php the_terms($id, 'product_cat'); ?></td>
                        <td><?php echo $product->get_price(); ?></td>
                    </tr>
            <?php
                }
            }
            ?>
            </tbody>
        </table>
        <?php
        $total_pages = $query->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="pagination">';
            echo paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $total_pages,
                'prev_text' => __('<< prev'),
                'next_text' => __('next >>'),
            ));
            echo '</div>';
        }
        ?>
    <?php
}