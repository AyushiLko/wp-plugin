<?php
/*
Plugin Name: Custom filter post
Description: This is a custom post plugin
Author: Mukesh Kumar Maurya
Version: 3.0
*/
add_shortcode('custom_filter', 'custom_filter_post_page');
function custom_filter_post_page()
{
    $search = '';
    if(isset($_GET['search']) ? $_GET['search'] : '');
    $search = $_GET['search'];
    $categories = get_categories();
?>
    <form method="get" role="search">
        <input type="text" name="search" value="<?php echo $search; ?>">
        <input type="submit" value="search">
    </form>
    <form method="get">
        <label for="filter">Filter</label>
        <select name="f">
            <option>select category</option>
            <?php
                foreach ($categories as $category) :
            ?>
                    <option value="<?php echo esc_attr($category->name); ?>" <?php selected($category->name, $_GET['f']); ?>><?php echo esc_html($category->name); ?></option>
            <?php 
            endforeach;
            ?>
        </select>
        <input type="submit" value="filter"/>
    </form>
    <?php
    $paged = get_query_var('paged');
    $posts_per_page = 5;
    $post_args = array(
        'post_type' => 'custompost',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        's' => $search,
    );
    if(isset($_GET['f']) ? $_GET['f']:''); {
        $post_args['tax_query'] = array(
            array(
            'taxonomy' =>'category',
            'field' => 'slug',
            'terms' => $_GET['f'],
            ),
        );
    }
    $posts_data = new WP_Query($post_args);
    if ($posts_data->have_posts()) :
    ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Post Image</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($posts_data->have_posts()) :
                    $posts_data->the_post();
                ?>
                    <tr>
                        <td><?php echo the_title(); ?></td>
                        <td><?php echo the_content(); ?></td>
                        <td><?php echo the_post_thumbnail('thumbnail'); ?></td>
                        <td><?php echo the_category('name'); ?></td>
                    </tr>
                <?php
                endwhile;
                ?>
            </tbody>
        </table>
<?php
        $total_pages = $posts_data->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="pagination">';
            echo paginate_links(array(
                'base' => str_replace('999999999', '%#%', esc_url(get_pagenum_link('999999999'))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $total_pages,
                'prev_text' => __('<< prev'),
                'next_text' => __('next >>'),
            ));
            echo '</div>';
        }
        wp_reset_postdata();
    else :
        echo __('not found', 'textdomain');
    endif;
}
add_action('get_search_form', 'custom_filter_post_page');
?>