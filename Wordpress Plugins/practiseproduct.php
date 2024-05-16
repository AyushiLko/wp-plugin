<?php
/*
Plugin Name: Practise Product
Description: Add product to the form
Version: 1.0 
Author:Product
*/
add_shortcode('product_listing','product_form');
function product_form()
{
   

    if(isset($_POST['submit'])){
        $productname = sanitize_text_field($_POST['productname']);
        $productdescription = sanitize_text_field($_POST['productdescription']);
        $productregular = sanitize_text_field($_POST['productregular']);
        $productsale = sanitize_text_field($_POST['productsale']);
        $productcategory = sanitize_text_field($_POST['productcategory']);
        $productimage = sanitize_text_field($_POST['productimage']);

        $product_data = array(
            'product_title' => $productname,
            'product_content' => $description,
            'product_status'=> 'publish',
            'product_type' => 'product', 
            'tax_input' => array(
                'product_cat' => $productcategory,
            ),
        );
        $post_id = wp_insert_post($product_data);
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        $attach_id = media_handle_upload( $_FILES['upl'], $rid );
 
        if($post_id){
            update_post_meta($post_id,'_productregular',$productregular);
            update_post_meta($post_id,'_productsale',$productsale);
            update_post_meta($post_id,'_productprice',$productsale);
            update_post_meta($post_id,'_thumbnail_id',$attach_id);
            echo "Product post successfully";
        }else{
            echo "Product not post";
        }
    }
    ?>
    <h1>PRODUCT</h1>
    <form action="" method="POST">
        <label for="productname">Product Name:</label>
        <input type="text" name="productname" id="productname"></br></br>
        <label for="productdescription">Product Description:</label>
        <input type="text" name="productdescription" id="productdescription"></br></br>
        <label for="productregular">Product Regular Price:</label>
        <input type="text" name="productregular" id="productregular"></br></br>
        <label for="productsale">Product Sale Price:</label>
        <input type="text" name="productsale" id="productsale"></br></br>
        <label for="productcategory">Product Category:</label>
        <select name="category">
            <option>Select Category</option>
</select>
        <label for="productimage">Product Image:</label>
        <input type="file" name="productimage" id="productimage"></br></br>
        <button type="submit" name="submit">Submit</button>
      
        
</form>
<?php

$paged = get_query_var('paged');
$items_per_page = 4;
$args = array(
    'post_type' => 'product',
    'paged' => $paged,
    'posts_per_page' => $items_per_page,
  
);

$query = new WP_Query($args);
    if ($query->have_posts()) {

?>
<table>
    <thead>
        <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Product Description </th>
            <th>Product Category</th>
            <th>Product Price</th>
</tr>
</head>
<tbody>
    <?php
    while ($query->have_post()){
        $query->the_post();
        $id = $query->the_ID();
        $product = wc_get_product($id);
        ?>

    <td><?php echo the_post_thumbnail(); ?></td>
    <td><?php echo the_title(); ?></td>
    <td><?php echo the_content(); ?></td>
    <td><?php echo the_terms(); ?></td>
    <td><?php echo $product->get_price(); ?></td>
    <?php
    }
    ?>
</tbody>
<?php
    }?>
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
?>
        