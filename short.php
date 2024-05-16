<?php
/*
Plugin Name: Add Shortcode
Version:1.0 
Description: Add new shortcode to the data.
Author: Shortcode
*/
add_shortcode('custom_product', 'custom_product_display');
function custom_product_display()
{
    if(isset($_POST['submit'])){
        $productname = sanitize_text_field($_POST['product_name']);
        $productdescription = sanitize_text_field($_POST['product_description']);
        $productprice = sanitize_text_field($_POST['regular_price']);
        $productcategory = sanitize_text_field($_POST['category']);
        $productsaleprice = sanitize_text_field($_POST['sale_price']);
        $productimage = sanitize_text_field($_POST['product_image']);
        
        $productdata = array(
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
    ?>
    <h1>Form</h1>
    <div>
        <form method="post">      
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
            <select name="category">
                <option>Select Category</option>
                <?php
                $product_category = array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false
                );
                $categories = get_terms($product_category);
                if (empty($categories)){
                    echo "<option>No categories found</option>";
                }else{
                    foreach ($categories as $category){
                        ?>
                        <option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
                        <?php
                    }
                    ?>
                    </select>
                
                <div>
                    <label for="sale_price">Sale Price</label>
                    <input type="number" name="sale_price">
                </div>
                <div>
                <label for="product_image">Product Image</label>
                <input type="file" name="product_image">
            </div></br>
            <input type="submit" name="submit" value="Add Product">
        </div>
        </form>
    </div>
    <?php
                }
}
