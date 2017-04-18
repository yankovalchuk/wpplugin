<?php


class WsWpCustomerExporter {

    public static $customer_mappings;

    public static function update_customer( $u_id , $item , $email_address ){
        global $WsWp_i18n;
        $customer_mappings = self::$customer_mappings;
        WsWpLogger::append_customer_log( __( "Updated customer with Email: ", $WsWp_i18n->get_domain() ) . $email_address );
        if ($u_id) {
            foreach($customer_mappings as $key => $value){

                $clear_option = preg_replace('/(_S_|_B_)/si', '',$key, 50);
                if(strripos($key , '_B_')){
                    if (self::should_overwrite($key) == '1' && isset($item->$clear_option)) {
                        update_user_meta($u_id, $key, $item->$clear_option);
                        WsWpLogger::append_customer_log( __( "/Customer $key  Overwrite if data exists: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }elseif(self::should_d_overwrite($key) == '1' && isset($item->$clear_option)){
                        WsWpLogger::append_customer_log( __( "/Customer $key  Don't Overwrite if data exists: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }elseif(self::should_skip($key) == '1'){
                        WsWpLogger::append_customer_log( __( "/Customer $key  Skipped: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }
                }elseif(strripos($key , '_S_')){
                    if (self::should_overwrite($key) == '1' && isset($item->$clear_option)) {
                        update_user_meta($u_id, $key, $item->$clear_option);
                        WsWpLogger::append_customer_log( __( "/Customer $key  Overwrite if data exists: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }elseif(self::should_d_overwrite($key) == '1' && isset($item->$clear_option)){
                        WsWpLogger::append_customer_log( __( "/Customer $key  Don't Overwrite if data exists: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }elseif(self::should_skip($key) == '1'){
                        WsWpLogger::append_customer_log( __( "/Customer $key  Skipped: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }
                }elseif(!strripos($key, '_S_') && !strripos($value , '_B_')){
                    if (self::should_overwrite($key) == '1' && isset($item->$clear_option)) {
                        update_user_meta($u_id, $key, $item->$clear_option);
                        WsWpLogger::append_customer_log( __( "/Customer $key  Overwrite if data exists: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }elseif(self::should_d_overwrite($key) == '1' && isset($item->$clear_option)){
                        WsWpLogger::append_customer_log( __( "/Customer $key  Don't Overwrite if data exists: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }elseif(self::should_skip($key) == '1'){
                        WsWpLogger::append_customer_log( __( "/Customer $key  Skipped: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }
                }
            }

            WsWpLogger::append_dev( __( "Updated customer with Email: ", $WsWp_i18n->get_domain() ) . $email_address );
            return 2;
        }
    }

    public static function create_customer( $email_address , $item ){

        global $WsWp_i18n;
        $customer_mappings = self::$customer_mappings;
        $username = explode('@', $email_address)[0];
        $username =  preg_replace('%[^A-Za-zÀ-0-9]%', '', $username);
        $password = wp_generate_password(8, false);
        $user_id = wc_create_new_customer($email_address, $username, $password);
        WsWpLogger::append_customer_log( __( "Creating customer with Email: ", $WsWp_i18n->get_domain() ) . $item->Email );
        if ($user_id) {
            foreach($customer_mappings as $key => $value){

                $clear_option = preg_replace('/(_S_|_B_)/si', '',$key, 50);
                if(strripos($key , '_B_')){
                    if (self::should_skip($key) == '0') {
                        update_user_meta($user_id, $key, (isset($item->$clear_option)) ? $item->$clear_option : '');
                        WsWpLogger::append_customer_log( __( "/Customer $key  Skipped: ", $WsWp_i18n->get_domain() ) . __( 'No', $WsWp_i18n->get_domain() ) );
                    } elseif(self::should_skip($key) == '1') {
                        WsWpLogger::append_customer_log( __( "/Customer $key  Skipped: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }
                }elseif(strripos($key , '_S_')){
                    if (self::should_skip($key) == '0') {
                        update_user_meta($user_id, $key, (isset($item->$clear_option)) ? $item->$clear_option : '');
                        WsWpLogger::append_customer_log( __( "/Customer $key  Skipped: ", $WsWp_i18n->get_domain() ) . __( 'No', $WsWp_i18n->get_domain() ) );
                    } elseif(self::should_skip($key) == '1') {
                        WsWpLogger::append_customer_log( __( "/Customer $key  Skipped: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }
                }elseif(!strripos($key, '_S_') && !strripos($value , '_B_')){
                    if (self::should_skip($key) == '0') {
                        update_user_meta($user_id, $key, (isset($item->$clear_option)) ? $item->$clear_option : '');
                        WsWpLogger::append_customer_log( __( "/Customer $key  Skipped: ", $WsWp_i18n->get_domain() ) . __( 'No', $WsWp_i18n->get_domain() ) );
                    } elseif(self::should_skip($key) == '1') {
                        WsWpLogger::append_customer_log( __( "/Customer $key  Skipped: ", $WsWp_i18n->get_domain() ) . __( 'Yes', $WsWp_i18n->get_domain() ) );
                    }
                }

            }


            WsWpLogger::append_dev( __( "Creating customer with Email: ", $WsWp_i18n->get_domain() ) . $item->Email );
            return 1;
        }
    }



    public static function get_last_sync_id_customer() {
        $customer_id = get_option( 'ws_wp_last_sync_id_customer', 0 );

        return $customer_id;
    }

    public static function update_last_sync_id_customer( $id ) {
        update_option( 'ws_wp_last_sync_id_customer', $id );
    }

    public static function set_mappings() {
        self::set_customer_mappings();
    }

    public static function get_customer_mappings() {
        return self::$customer_mappings;
    }

    public static function set_customer_mappings() {
        global $WsWp_i18n;
        self::$customer_mappings = array(
            'username'              => array(
                'name'        => __( 'Customer Userame', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,
                ),
                'overwrite'   => 0,
                'd_overwrite' => 0,
                'skip'        => 1
            ),

            'password'              => array(
                'name'        => __( 'Customer Password', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,
                ),
                'overwrite'   => 0,
                'd_overwrite' => 0,
                'skip'        => 1
            ),

            'PriceGroup'              => array(
                'name'        => __( 'Customer Price Group', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,
                ),
                'overwrite'   => 1,
                'd_overwrite' => 0,
                'skip'        => 0
            ),

            '_B_Name'              => array(
                'name'        => __( 'Billing Customer Name', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,
                ),
                'overwrite'   => 0,
                'd_overwrite' => 1,
                'skip'        => 0
            ),

            '_B_Address1'              => array(
                'name'        => __( 'Billing Address1', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,
                ),
                'overwrite'   => 0,
                'd_overwrite' => 1,
                'skip'        => 0
            ),

            '_B_Address2'              => array(
                'name'        => __( 'Billing Address2', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,
                ),
                'overwrite'   => 0,
                'd_overwrite' => 1,
                'skip'        => 0
            ),
            '_B_City'       => array(
                'name'        => __( 'Billing City', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,

                ),
                'overwrite'   => 1,
                'd_overwrite' => 0,
                'skip'        => 0
            ),

            '_B_ZipCode'       => array(
                'name'        => __( 'Billing ZipCode', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,

                ),
                'overwrite'   => 1,
                'd_overwrite' => 0,
                'skip'        => 0
            ),



            'Phone'       => array(
                'name'        => __( 'Phone', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,

                ),
                'overwrite'   => 1,
                'd_overwrite' => 0,
                'skip'        => 0
            ),

            '_S_Name'              => array(
                'name'        => __( 'Shipping Customer  Name', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,
                ),
                'overwrite'   => 0,
                'd_overwrite' => 1,
                'skip'        => 0
            ),

            '_S_Address1'              => array(
                'name'        => __( 'Shipping Address1', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,
                ),
                'overwrite'   => 0,
                'd_overwrite' => 1,
                'skip'        => 0
            ),

            '_S_Address2'              => array(
                'name'        => __( 'Shipping Address2', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,
                ),
                'overwrite'   => 0,
                'd_overwrite' => 1,
                'skip'        => 0
            ),
            '_S_City'       => array(
                'name'        => __( 'Shipping City', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,

                ),
                'overwrite'   => 1,
                'd_overwrite' => 0,
                'skip'        => 0
            ),

            '_S_ZipCode'       => array(
                'name'        => __( 'Shipping ZipCode', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,

                ),
                'overwrite'   => 1,
                'd_overwrite' => 0,
                'skip'        => 0
            ),
            'CountryName'       => array(
                'name'        => __( 'CountryName', $WsWp_i18n->get_domain() ),
                'options'     => array(
                    'Description'  => 1,

                ),
                'overwrite'   => 1,
                'd_overwrite' => 0,
                'skip'        => 0
            ),
        );

        if ( self::$customer_mappings ) {
            foreach ( self::$customer_mappings as $key => $item ) {
                if ( isset( $item['hide'] ) && ( $item['hide'] == 1 ) ) {
                    continue;
                }
                $option = get_option( 'ws_wp_customer_mappings_' . $key );
                if ( $option ) {
                    $opt = self::$customer_mappings[ $key ];
                    foreach ( $option as $k => $value ) {
                        if ( isset( $opt[ $k ] ) ) {
                            if ( is_array( $value ) ) {
                                if ( $value ) {
                                    foreach ( $value as $sk => $svalue ) {
                                        if ( isset( $opt[ $k ][ $sk ] ) ) {
                                            $opt[ $k ][ $sk ] = $svalue;
                                        }
                                    }
                                }
                            } else {
                                $opt[ $k ] = $value;
                            }
                        }
                    }
                    self::$customer_mappings[ $key ] = $opt;
                }
            }
        }
    }

    public static function sync_item( $item ) {

        self::set_customer_mappings();

        if($item){
            $email_address = $item->Email;
            if (!empty($email_address) && is_email($email_address)) {

                if ($user = get_user_by('email', $email_address)) {

                    return self::update_customer($user->ID , $item ,$email_address);
                } else {

                    return self::create_customer($email_address, $item);
                }
            }
        }else{
            return false;
        }
    }

    public static function get_selected_option( $name ) {
        $customer_mappings = self::$customer_mappings;
        if ( $customer_mappings ) {
            if ( isset( $customer_mappings[ $name ] ) ) {
                if ( isset( $customer_mappings[ $name ]['options'] ) ) {
                    foreach ( $customer_mappings[ $name ]['options'] as $key => $checked ) {
                        if ( $checked ) {
                            return $key;
                        }
                    }
                }
            }
        }

        return null;
    }

    protected static function should_overwrite( $name ) {
        $customer_mappings = self::$customer_mappings;
        if ( $customer_mappings ) {
            if ( isset( $customer_mappings[ $name ] ) ) {
                if ( isset( $customer_mappings[ $name ]['overwrite'] ) && 1 == $customer_mappings[ $name ]['overwrite'] ) {
                    return 1;
                } elseif ( isset( $customer_mappings[ $name ]['overwrite'] ) && 0 == $customer_mappings[ $name ]['overwrite'] ) {
                  return 0;
                }
            }
        }

        return null;
    }


    protected static function should_d_overwrite( $name ) {
        $customer_mappings = self::$customer_mappings;
        if ( $customer_mappings ) {
            if ( isset( $customer_mappings[ $name ] ) ) {
                if ( isset( $customer_mappings[ $name ]['d_overwrite'] ) && 1 == $customer_mappings[ $name ]['d_overwrite'] ) {
                    return 1;
                } elseif ( isset( $customer_mappings[ $name ]['d_overwrite'] ) && 0 == $customer_mappings[ $name ]['d_overwrite'] ) {
                    return 0;
                }
            }
        }

        return null;
    }

    protected static function should_skip( $name ) {

        $customer_mappings = self::$customer_mappings;
        if ( $customer_mappings ) {
            if ( isset( $customer_mappings[ $name ] ) ) {
                if ( isset( $customer_mappings[ $name ]['skip'] ) && 1 == $customer_mappings[ $name ]['skip'] ) {
                    return 1;
                } elseif ( isset( $customer_mappings[ $name ]['skip'] ) && 0 == $customer_mappings[ $name ]['skip'] ) {
                    return 0;
                }
            }
        }

        return null;
    }

}