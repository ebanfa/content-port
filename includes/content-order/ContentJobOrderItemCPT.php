<?php


class ContentJobOrderItemCPT {

  public static $prefix = ''; 
  
  public static $custom_fields =  array(
    array('name' => 'joitem_code',
            'title' => 'Order Item Code',
            'description' => 'Order item code field',
            'type' => 'text',
        ),
        array('name' => 'joitem_order_code',
            'title' => 'Order Code',
            'description' => 'Order code field',
            'type' => 'text'
        ),
        array('name' => 'joitem_user_name',
            'title' => 'User name',
            'description' => 'User name field',
            'type' => 'text'
        ),
        array('name' => 'joitem_description',
              'title' => 'Description',
              'description' => 'Description field',
              'type' => 'text'
        ), 
        array('name' => 'joitem_unit_price',
              'title' => 'Unit Price',
              'description' => 'Unit price field',
              'type' => 'text'
        ),  
        array('name' => 'joitem_quantity',
              'title' => 'Quantity',
              'description' => 'Quantity field',
              'type' => 'text'
        ),  
        array('name' => 'joitem_total',
              'title' => 'Line Total',
              'description' => 'Line total field',
              'type' => 'text'
        ), 
        array('name' => 'joitem_discount',
              'title' => 'Discount',
              'description' => 'Discount field',
              'type' => 'text'
        ),
        array('name' => 'joitem_date',
              'title' => 'Order Date',
              'description' => 'Order date field',
              'type' => 'text'
        ),  
        array('name' => 'joitem_term',
              'title' => 'Term',
              'description' => 'Term field',
              'type' => 'text'
        ),
  );

  /**
   * Register the custom post type so it shows up in menus
   */
  public static function register_custom_post_type()
  {
    register_post_type( 'content_joitems', 
      array(
        'label' => 'Orders Items',
        'labels' => array(
          'add_new'       => 'Add New',
          'add_new_item'    => 'Add New Order Item',
          'edit_item'     => 'Edit Order Item',
          'new_item'      => 'New Order Item',
          'view_item'     => 'View Order Item',
          'search_items'    => 'Search Order  Items',
          'not_found'     => 'No Order  Items Found',
          'not_found_in_trash'=> 'Not Found in Trash',
        ),
        'description' => 'Reusable orders items',
        'public' => true,
        'show_ui' => true,
        'menu_position' => 5,
        'supports' => array('title', 'custom-fields'),
        'has_archive'   => true,
        'rewrite'   => true,
      )
    );    
  }

  /**
   * Customize the Order CPT table header. 
   */
  public static function content_joitems_table_head($defaults) 
  {
        $defaults['joitems_orderno'] = 'Order No';
        $defaults['joitems_description'] = 'Discription';
        $defaults['joitems_amount'] = 'Amount';
        $defaults['joitems_discount'] = 'Discount';
        $defaults['joitems_term'] = 'Term';
        return $defaults;
    }

  /**
   * Customize the Order CPT table content
   */
    public static function content_joitems_table_content($column_name, $post_id) 
    {
      if ($column_name == 'joitems_orderno') 
      {
          $user_name = get_post_meta($post_id, 'joitem_code', true );
          echo $user_name;
      }
      if ($column_name == 'joitems_description') 
      {
          $order_no = get_post_meta($post_id, 'joitem_description', true );
          echo $order_no;
      }
      if ($column_name == 'joitems_amount') 
      {
          $total = get_post_meta($post_id, 'joitem_total', true );
          echo $total;
      }
      if ($column_name == 'joitems_discount') 
      {
            $pymnt_status = get_post_meta($post_id, 'joitem_discount', true );
            echo $pymnt_status;
      }
      if ($column_name == 'joitems_term') 
      {
            $pymnt_status = get_post_meta($post_id, 'joitem_term', true );
            echo $pymnt_status;
      }
    }

   /**
    * Customize Orders CPT admin table sortable columns.
    */
    public static function content_joitems_table_sorting($columns)
    {
        $columns['joitems_amount'] = 'joitem_total';
        return $columns;
    }

   /**
    * Sort the Order CPT table by the order number. Delegates to CPT class.
    */
    public static function content_joitems_columns_orderby($vars) 
    {
        if ( isset( $vars['post_type'] ) && 'content_joitems' == $vars['post_type'] ) 
        {
            if ( isset( $vars['orderby'] ) && 'joitems_amount' == $vars['orderby'] ) 
            {
                $vars = array_merge( $vars, array('meta_key' => 'joitem_total', 'orderby' => 'meta_value_num'));
            }
        }
        return $vars;
    }


  /*------------------------------------------------------------------------------
  Save the new Custom Fields values
  INPUT:
    $post_id (int) id of the post these custom fields are associated with
    $post (obj) the post object
  ------------------------------------------------------------------------------*/
  public static function save_custom_fields( $post_id, $post ) 
  {
    if ( $post->post_type == 'content_joitems') {
        // The 2nd arg here is important because there are multiple nonces on the page
        if ( !empty($_POST))// && check_admin_referer('update_custom_content_fields','custom_content_fields_nonce') )
        {     
            CustomFieldsUtils::save_custom_fields($post_id, $post, self::$custom_fields);
        }
    }
  }

}
?>