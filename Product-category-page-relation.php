<?php

/**

 * Plugin Name: Product category and page relation

 * Plugin URI: http://www.expertwebtechnologies.com

 * Description: This plugin adds  options to  select the page for category contnet  

 * Version: 1.0.0

 * Author: Shiv kumawat

 * Author URI: http://www.expertwebtechnologies.com

 * License: GPL2

 */

 

 function wcpr_plugin_path() {

 

  // gets the absolute path to this plugin directory

 

  return untrailingslashit( plugin_dir_path( __FILE__ ) );

 

}

 

 

 

add_filter( 'woocommerce_locate_template', 'wcpr_woocommerce_locate_template', 100, 3 );

 

 

 

function wcpr_woocommerce_locate_template( $template, $template_name, $template_path ) {

 

  global $woocommerce;

 

 

 

  $_template = $template;

 

  if ( ! $template_path ) $template_path = $woocommerce->template_url;

 

  $plugin_path  = wcpr_plugin_path() . '/woocommerce/';

 

 

 

  // Look within passed path within the theme - this is priority

 

  $template = locate_template(

 

    array(

 

      $template_path . $template_name,

 

      $template_name

 

    )

 

  );

 

 

 

  // Modification: Get the template from this plugin, if it exists

 

  if ( ! $template && file_exists( $plugin_path . $template_name ) )

 

    $template = $plugin_path . $template_name;

 

 

 

  // Use default template

 

  if ( ! $template )

 

    $template = $_template;

 

 

 

  // Return what we found

 

  return $template;

 

}



add_action( 'product_cat_add_form_fields', 'wcpr_group_field', 10, 2 );

function wcpr_group_field($taxonomy) {

    global $product_cats;

    ?> <div class="form-field term-group">

        <label for="wcpr-page"><?php _e('Page', 'wcpr'); ?></label>
        
 <?php $args = array(
    'child_of'     => 0,
    'sort_order'   => 'ASC',
    'sort_column'  => 'post_title',
    'post_type' => 'page',
	 'selected'              => 0,
	 'name'                  => 'wcpr-page'
);
?><?php wp_dropdown_pages($args); ?>
      <!--  <input type="text" class="wcpr-page" id="wcpr-page" name="wcpr-page">-->

     

    </div><?php

}



add_action( 'created_product_cat', 'wcpr_group_meta', 10, 2 );



function wcpr_group_meta( $term_id, $tt_id ){

    if( isset( $_POST['wcpr-page'] ) && '' !== $_POST['wcpr-page'] ){

        $group = sanitize_title( $_POST['wcpr-page'] );

        add_term_meta( $term_id, 'wcpr-page', $group, true );

    }

}



add_action( 'product_cat_edit_form_fields', 'edit_product_cat_field', 10, 2 );



function edit_product_cat_field( $term, $taxonomy ){

                //exit;

    global $product_cats;

          

    // get current group

    $product_cat = get_term_meta( $term->term_id, 'wcpr-page', true );

                

    ?>

    <tr class="form-field">

			<th valign="top" scope="row"><label for="wcpr-page"><?php _e('Page', 'wcpr'); ?></label></th>

			<td>
<?php $args = array(
    'child_of'     => 0,
    'sort_order'   => 'ASC',
    'sort_column'  => 'post_title',
    'post_type' => 'page',
	 'selected'              => $product_cat,
	 'name'                  => 'wcpr-page'
);
?><?php wp_dropdown_pages($args); ?>
			  

			</td>

		</tr>

    

     <?php

}





add_action( 'edited_product_cat', 'wcpr_update_meta', 10, 2 );



function wcpr_update_meta( $term_id, $tt_id ){

 

    

        $group = sanitize_title( $_POST['wcpr-page'] );

        update_term_meta( $term_id, 'wcpr-page', $group );

     

}



add_filter('manage_edit-product_cat_columns', 'wcpr_add_product_cat_column' );



function wcpr_add_product_cat_column( $columns ){

    $columns['page_slug'] = __( 'Page Slug', 'wcpr' );

    return $columns;

}





add_filter('manage_product_cat_custom_column', 'wcpr_add_product_cat_column_content', 10, 3 );



function wcpr_add_product_cat_column_content( $content, $column_name, $term_id ){

    global $product_cats;

 

    if( $column_name !== 'page_slug' ){

        return $content;

    }



    $term_id = absint( $term_id );

    echo  $wcpr_page = get_term_meta( $term_id, 'wcpr-page', true );

 

 

}

 