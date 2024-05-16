<?php
/* 
Plugin Name: Crud Practise
Description: For the practise section. 
Version: 1.0 
Author:Crud Practise
*/

add_action('admin_menu','crud_practise_menu');
function crud_practise_menu()
{
    add_menu_page('for_practise','for_practise','manage_options','crud_practise','crud_practise_page');
    add_submenu_page('crud_practise','for_table','for_table','manage_options','product_list','product_list_page');
    function crud_practise_page()
    {
        if(isset($_POST['submit'])){
            $productname = sanitize_text_field($_POST['productname']);
            $productdescription = sanitize_text_field($_POST['productdescription']);
            $productprice = sanitize_text_field($_POST['productprice']);
            $productimage = sanitize_text_field($_FILES['productimage']);
        if(isset($_GET['product_id']) && isset($_GET['action'])){
                    $productid = $_GET['product_id'];
                $product_data = array(
                    'post_title' => $productname,
                    'post_content' => $productdescription,
                    'post_status' => 'publish',
                    'post_type' => 'product',
                    'ID' => $productid,
                );
                wp_update_post($product_data);
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
    
                $attachment_id = media_handle_upload( 'productimage',$productid);
                update_post_meta($productid,'_price',$productprice);
                update_post_meta($productid,'_thumbnail_id',$attachment_id);
                
                echo "Product updated successfully";
                }else{
            $product_data = array(
                'post_title' => $productname,
                'post_content' => $productdescription,
                'post_status' => 'publish',
                'post_type' => 'product',
            );
           
            $product_id = wp_insert_post($product_data);
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $attachment_id = media_handle_upload( 'productimage',$product_id);
            update_post_meta($product_id,'_price',$productprice);
            update_post_meta($product_id,'_thumbnail_id',$attachment_id);
            
            echo "Product inserted successfully";
        }
    }
        if(isset($_GET['delete']) && isset($_GET['product_id'])){
            $product_ID = $_GET['product_id'];
            $result= wp_delete_post($product_ID);
         if ($result == TRUE) {
            echo "Product deleted successfully.";
        }else{
            echo "Product is not deleted";
        }
        }
        if(isset($_GET['product_id']) && isset($_GET['action'])){
            $productID = $_GET['product_id'];
            $productname = get_the_title($productID);
            $productdescription = get_post_field('post_content',$productID);
            $productprice = get_post_meta($productID,'_price',true);
        }
        ?>
        <h1>For Products</h1>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Form</title>
        </head>
        <body>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="productname">Product Name:</label>
                <input type="text" name="productname" id="productname" value=<?php echo (isset($productname)) ? $productname: ''?>></br></br>
                <label for="productdescription">Product Description:</label>
                <input type="text" name="productdescription" id="productdescription" value=<?php echo (isset($productdescription)) ? $productdescription: ''?>></br></br>
                <label for="productprice">Product Price:</label>
                <input type="text" name="productprice" id="productprice" value=<?php echo (isset($productprice)) ? $productprice: ''?>></br></br>
                <label for="productimage">Product Image:</label>
                <input type="file" name="productimage" id="productimage" value=<?php echo (isset($productimage)) ? $productimage: ''?>></br></br>
                <button type="submit" name="submit" id="submit">Submit</button>
    </form>
        </body>
        </html>
        <?php
    }
}
        function product_list_page(){
            $search = '';
            if(isset($_GET['search']) ? $_GET['search'] : '');
            $search = $_GET['search'];
        ?>
        <h2>Table</h2>
        <form method="get" role="search">
                <input type="text" name="search" value="<?php echo $search; ?>">
                <input type="submit" value="search">
            </form>
        <table class="wp_list_table widefat fixed striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Price</th>
                    <th>Product Image</th>
                    <th>Update</th>
                    <th>Delete</th>
    </tr>
    </thead>
    <tbody>
        <?php
         $product = array(
            'post_status' => 'publish',
            'post_type' => 'product',
            's' => $search,
        );
        $products = get_posts($product);
        foreach($products as $row){
        $product_id = $row->ID;
        $productname = get_the_title($product_id);
        $productdescription = $row->post_content;
        $productprice = get_post_meta($product_id,'_price',true);
        $productimage = get_post_meta($product_id,'_thumbnail_id',true);
        $image_url = wp_get_attachment_url($productimage)
    
        ?>
        <tr>
            <td><?php echo $productname ?></td>
            <td><?php echo $productdescription ?></td>
            <td><?php echo $productprice ?></td>
            <td><img src="<?php echo $image_url; ?>" alt="image not found" style="width: 100px; height: 100px;"/></td>
            <td><a class="btn btn-success" href="http://localhost/wordpress/wp-admin/admin.php?page=crud_practise&&action=update&&product_id=<?php echo $product_id;?>">Update</a></td>
            <td><a class="btn btn-danger" href="http://localhost/wordpress/wp-admin/admin.php?page=crud_practise&&delete=delete&&product_id=<?php echo $product_id; ?>">Delete</a></td>

            
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    </tr>
    <?php
        }
        ?>
    </tbody>
    </table>
            <?php
        
    }




