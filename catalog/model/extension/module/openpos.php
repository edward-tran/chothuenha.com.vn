<?php
class ModelExtensionModuleOpenPos extends Model {
    public function getAllowCashdrawers($user_id)
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "openpos_register_user` u LEFT JOIN `" . DB_PREFIX . "openpos_register` r ON u.register_id = r.register_id  WHERE u.user_id = '" . (int)$user_id . "' AND r.status = 1");
        $registers = $query->rows;
        return $registers;
    }
    public function getUser($user_id) {
        $query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

        return $query->row;
    }
    public function getRegister($register_id){
        $query = $this->db->query("SELECT *  FROM `" . DB_PREFIX . "openpos_register` ug WHERE ug.register_id = '" . (int)$register_id . "'");
        return $query->row;
    }

    public function renewOrderNumber($data){
        $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', date_added = NOW(), date_modified = NOW()");
        $order_id = $this->db->getLastId();

        return $order_id;
    }

    public function checkOrderExist($order_id){
        $query = $this->db->query("SELECT *  FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND order_status_id > 0");

        if(count($query->rows) > 0)
        {
            $order = end($query->rows);
            return $order['order_id'];
        }
        return false;
    }


    public function addOrder($data) {

        $is_new = true;
        if(isset($data['order_id']) && $data['order_id'] > 0)
        {
            $is_new = false;
        }
        if($is_new)
        {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? json_encode($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? json_encode($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', marketing_id = '" . (int)$data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW()");

            $order_id = $this->db->getLastId();
        }else{
            $order_id = $data['order_id'];

            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? json_encode($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? json_encode($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', marketing_id = '" . (int)$data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW() WHERE order_id = ".(int)$order_id);

        }


        // Products
        if (isset($data['products'])) {
            //delete all old product
            $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");
            //end
            foreach ($data['products'] as $product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

                $order_product_id = $this->db->getLastId();

                foreach ($product['option'] as $option) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
                }
            }
        }

        // Gift Voucher
        $this->load->model('extension/total/voucher');

        // Vouchers
        if (isset($data['vouchers'])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");
            foreach ($data['vouchers'] as $voucher) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

                $order_voucher_id = $this->db->getLastId();

                $voucher_id = $this->model_extension_total_voucher->addVoucher($order_id, $voucher);

                $this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
            }
        }

        // Totals
        $grand_total = 0;
        if (isset($data['totals'])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");
            foreach ($data['totals'] as $total) {
                if($total['code'] == 'total')
                {
                    $grand_total = $total['value'];
                }
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            }
        }
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET total= '".$grand_total."' WHERE order_id = '" . (int)$order_id . "'");
        return $order_id;
    }




    public function addOrderPayment($order_id,$payments){
        $this->db->query("DELETE FROM " . DB_PREFIX . "openpos_order_payment WHERE order_id=".(int)$order_id);
        foreach($payments as $payment)
        {
            $sql = "INSERT INTO " . DB_PREFIX . "openpos_order_payment SET payment_code = '" . $this->db->escape($payment['payment_code']) . "',payment_title = '" . $this->db->escape($payment['payment_title']) . "',amount = '" . (float)$payment['amount'] . "',comment= '" . $this->db->escape($payment['ref']) . "',order_id = '" . (int)$order_id . "',date_added = NOW()";

            $this->db->query($sql);
        }
    }

    public function addPosOrder($order_id,$pos_order_data){
        $this->db->query("DELETE FROM " . DB_PREFIX . "openpos_order WHERE order_id=".(int)$order_id);
        $sql = "INSERT INTO " . DB_PREFIX . "openpos_order SET cashier_user_id = '" . $this->db->escape($pos_order_data['cashier_user_id']) . "',seller_user_id = '" . (int)$pos_order_data['seller_user_id'] . "',register_id = '" . (int)$pos_order_data['register_id'] . "',comment = '" . $this->db->escape($pos_order_data['comment']) . "',order_content = '" . $this->db->escape(json_encode($pos_order_data['order_content'])) . "',session = '" . $this->db->escape($pos_order_data['session']) . "',local_id = '" . $this->db->escape($pos_order_data['local_id']) . "',order_id = '" . (int)$order_id . "',date_added = NOW()";
        $this->db->query($sql);
    }

    public function searchOrders($term)
    {

        $sql = "SELECT * FROM " . DB_PREFIX . "order WHERE order_id LIKE '%" . $this->db->escape($term). "%' AND  " . DB_PREFIX . "order.order_status_id > 0";
        $sql.= " LIMIT 0,5";

        $query = $this->db->query($sql);
        return $query->rows;

    }
    public function getTotalOrders()
    {
        $result = array();
       
        $sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "order WHERE  " . DB_PREFIX . "order.order_status_id > 0  ORDER BY  " . DB_PREFIX . "order.order_id DESC";
        
        $query = $this->db->query($sql);

        $result = $query->row;
        
        
        return $result['total'];

    }
    public function latestOrders($page,$per_page = 10,$list_type = 'latest')
    {
        $result = array();
        if($list_type == 'pending')
        {
            $date = date('Y-m-d');
            $result = $this->getOrdersByDate($date);
        }else{
            $sql = "SELECT * FROM " . DB_PREFIX . "order WHERE  " . DB_PREFIX . "order.order_status_id > 0  ORDER BY  " . DB_PREFIX . "order.order_id DESC";
            //$sql.= " LIMIT 0,".$per_page;
            $start = 0;
            if($page > 1)
            {
                $start = $per_page * ($page - 1);
            }
            $sql .= " LIMIT " . (int)$start . "," . (int)$per_page;
            $query = $this->db->query($sql);

            $result = $query->rows;
        }
        
        return $result;

    }
    public function getOrdersByDate($date)
    {
        $next_date = $date.' 23:59:59';
        $sql = "SELECT *  FROM  " . DB_PREFIX . "order o ";
        $sql .= " WHERE o.order_status_id > 0  AND DATE(o.date_added) >= DATE('" . $this->db->escape($date) . "') AND DATE(o.date_added) <= DATE('" . $this->db->escape($next_date) . "')";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getPosOrder($order_id){
        $sql = "SELECT * FROM " . DB_PREFIX . "openpos_order WHERE order_id = '" .(int)$order_id. "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getPosOrderPayments($order_id){
        $sql = "SELECT * FROM " . DB_PREFIX . "openpos_order_payment WHERE order_id = '" .(int)$order_id. "'";

        $query = $this->db->query($sql);
        return $query->rows;
    }
    public function addTransaction($local_id,$transaction_data){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "openpos_transaction WHERE local_id = '" . $this->db->escape($local_id) . "'");
        $transaction_row = $query->rows;
        if(count($transaction_row) > 0)
        {
            $transaction = end($transaction_row);
            return $transaction['transaction_id'];
        }
        $this->db->query("INSERT INTO " . DB_PREFIX . "openpos_transaction SET created_by = '".(int)$transaction_data['created_by']."',session = '" . $this->db->escape($transaction_data['session']) . "',transaction_content = '" . $this->db->escape(json_encode($transaction_data['content'])) . "', local_id = '" . $this->db->escape($local_id) . "', `comment` = '" . $this->db->escape($transaction_data['ref']) . "', `in_amount` = '" . (float)$transaction_data['in_amount'] . "',`out_amount` = '" . (float)$transaction_data['out_amount'] . "', register_id = '" . (int)$transaction_data['register_id'] . "', store_id = '" . (int)$transaction_data['store_id'] . "'");
        return $this->db->getLastId();
    }

    public function getCustomerInformation($customer_id = 0){
        if($customer_id)
        {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
            $customer_row = $query->row;
            if(!empty($customer_row))
            {
                $customer_data = array(
                    'customer_id' => $customer_row['customer_id'],
                    'customer_group_id' => $customer_row['customer_id'],
                    'firstname' => $customer_row['firstname'],
                    'lastname' => $customer_row['lastname'],
                    'email' => $customer_row['email'],
                    'telephone' => $customer_row['telephone'],
                );
                if($customer_row['address_id'])
                {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$customer_row['address_id'] . "'");
                    $address_row = $query->row;
                    $customer_data['company'] = $address_row['company'];
                    $customer_data['address_1'] = $address_row['address_1'];
                    $customer_data['address_2'] = $address_row['address_2'];
                    $customer_data['city'] = $address_row['city'];
                    $customer_data['postcode'] = $address_row['postcode'];
                    $customer_data['country_id'] = $address_row['country_id'];
                    $customer_data['zone_id'] = $address_row['zone_id'];
                }
                return $customer_data;
            }
        }
        return array(
            'customer_id' => 0,
            'customer_group_id' => 0,
            'firstname' => 'Guest',
            'lastname' => '',
            'email' => 'guest-user@mail.com',
            'telephone' => ''
        );

    }



    public function getRegisterSetting($register_id){
        $register = $this->getRegister($register_id);
        $store_id = $register['store_id'];
        $currency = $this->getSystemConfigByKey('config_currency',$store_id);
        $currency_code = $this->config->get('config_currency');
        if($currency)
        {
            $currency_code = $currency['value'];
        }
        $currency_id = 2;
        $currency_value = 1;
        $sql = "SELECT * FROM " . DB_PREFIX . "currency WHERE `code` = '%" . $this->db->escape($currency_code). "%'";
        $query = $this->db->query($sql);
        $currency_data = $query->row;
        if(!empty($currency_data))
        {
            $currency_id = $currency_data['currency_id'];
            $currency_value = $currency_data['value'];
        }
        return array(
            'currency_id' => $currency_id,
            'currency_code' => $currency_code,
            'currency_value' => $currency_value,
        );
    }

    public function customers($term){
            $sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE firstname LIKE '%" . $this->db->escape($term). "%' OR lastname LIKE '%" . $this->db->escape($term). "%' OR email LIKE '%" . $this->db->escape($term). "%' OR telephone LIKE '%" . $this->db->escape($term). "%'";
            $sql.= " LIMIT 0,5";

            $query = $this->db->query($sql);
            return $query->rows;
    }

    public function customer_by($by,$term){
        $sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE ".$by." LIKE '%" . $this->db->escape($term). "%'";
        $sql.= " LIMIT 0,1";
        $query = $this->db->query($sql);
        return $query->rows;
    }


    public function editAddress($address_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? json_encode($data['custom_field']['address']) : '') . "' WHERE address_id  = '" . (int)$address_id . "'");

    }

    public function getAddress($address_id){
        $address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

        if ($address_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

            if ($country_query->num_rows) {
                $country = $country_query->row['name'];
                $iso_code_2 = $country_query->row['iso_code_2'];
                $iso_code_3 = $country_query->row['iso_code_3'];
                $address_format = $country_query->row['address_format'];
            } else {
                $country = '';
                $iso_code_2 = '';
                $iso_code_3 = '';
                $address_format = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

            if ($zone_query->num_rows) {
                $zone = $zone_query->row['name'];
                $zone_code = $zone_query->row['code'];
            } else {
                $zone = '';
                $zone_code = '';
            }

            $address_data = array(
                'address_id'     => $address_query->row['address_id'],
                'firstname'      => $address_query->row['firstname'],
                'lastname'       => $address_query->row['lastname'],
                'company'        => $address_query->row['company'],
                'address_1'      => $address_query->row['address_1'],
                'address_2'      => $address_query->row['address_2'],
                'postcode'       => $address_query->row['postcode'],
                'city'           => $address_query->row['city'],
                'zone_id'        => $address_query->row['zone_id'],
                'zone'           => $zone,
                'zone_code'      => $zone_code,
                'country_id'     => $address_query->row['country_id'],
                'country'        => $country,
                'iso_code_2'     => $iso_code_2,
                'iso_code_3'     => $iso_code_3,
                'address_format' => $address_format,
                'custom_field'   => json_decode($address_query->row['custom_field'], true)
            );

            return $address_data;
        } else {
            return false;
        }
    }

    public function getRegisterBalance($register_id){
        $sql = "SELECT SUM(r.in_amount) as total_in_amount , SUM(r.out_amount) as total_out_amount  FROM " . DB_PREFIX . "openpos_transaction r ";

            $sql .= " WHERE r.register_id=".(int)$register_id;

        $query = $this->db->query($sql);
        $row = $query->row;
        return 1*($row['total_in_amount'] - $row['total_out_amount']);
    }
    public function getSystemConfigByKey($key,$store_id=0)
    {
        $sql = "SELECT *  FROM " . DB_PREFIX . "setting WHERE store_id='".(int)$store_id."' AND `key` ='".$key."' AND `code` = 'config' ";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getCashiers($register_id){
        $sql = "SELECT *  FROM " . DB_PREFIX . "openpos_register_user r LEFT JOIN " . DB_PREFIX . "user u ON u.user_id = r.user_id  WHERE r.register_id = '".(int)$register_id."'";
        $query = $this->db->query($sql);
        return $query->rows;

    }

    public function addPosOrderTotal($order_id,$total_data){
        $sql = "INSERT INTO " . DB_PREFIX . "order_total SET code = '" . $this->db->escape($total_data['code']) . "',order_id = '" . (int)$order_id . "',value = '" . (float)$total_data['value'] . "',title= '" . $this->db->escape($total_data['title']) . "',sort_order = '" . (int)$total_data['sort_order'] . "'";
        $this->db->query($sql);
    }
    public function addCustomerReward($customer_id,$point,$comment='',$order_id = 0)
    {
        $sql = "INSERT INTO " . DB_PREFIX . "customer_reward SET description = '" . $this->db->escape($comment) . "',order_id = '" . (int)$order_id . "',points = '" . (int)$point . "',customer_id = '" . (int)$customer_id . "',date_added = NOW()";
        $this->db->query($sql);
    }

    public function addCouponHistory($coupon_data)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET coupon_id = '" . (int)$coupon_data['coupon_id'] . "', order_id = '" . (int)$coupon_data['order_id'] . "', customer_id = '" . (int)$coupon_data['customer_id'] . "', amount = '" . (float)$coupon_data['value'] . "', date_added = NOW()");
    }

    public function getProductSpecials($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'  AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC");
        return $query->rows;
    }

    public function getProductDiscounts($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND  quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

        return $query->rows;
    }



    public function getShippingAddress($customer_id){

        $addresses = array();


        /* $address = array(
                'id' => 1,
                'title' => $address,
                'name' => implode(' ',array($first_name,$last_name)),
                'address' => $address,
                'phone' => $phone,
            );
         */



        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

        foreach ($query->rows as $result) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");

            if ($country_query->num_rows) {
                $country = $country_query->row['name'];
                $iso_code_2 = $country_query->row['iso_code_2'];
                $iso_code_3 = $country_query->row['iso_code_3'];
                $address_format = $country_query->row['address_format'];
            } else {
                $country = '';
                $iso_code_2 = '';
                $iso_code_3 = '';
                $address_format = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

            if ($zone_query->num_rows) {
                $zone = $zone_query->row['name'];
                $zone_code = $zone_query->row['code'];
            } else {
                $zone = '';
                $zone_code = '';
            }
            $address = $result['address_1'] ? $result['address_1']: $result['address_2'];
            $addresses[] = array(
                'id'     => $result['address_id'],
                'name'   => implode(' ',array($result['firstname'],$result['lastname'])),
                'title'      => $address,
                'address'      =>  $address ,
                'phone'      => ''
            );
        }




        return $addresses;

    }
    public function refundOrder($order_id) {

    }

    public function getTotalCustomerOrders($customer_id){
        $sql = "SELECT count(*) as total FROM " . DB_PREFIX . "order WHERE customer_id = '" .(int)$customer_id. "'";

        $query = $this->db->query($sql);
        $row = $query->row;
        if(!isset($row['total']))
        {
            return 0;
        }
        return $row['total'];
    }
    public function getCustomerOrders($customer_id,$offset,$limit){
        $sql = "SELECT * FROM " . DB_PREFIX . "order WHERE customer_id = '" .(int)$customer_id. "' ORDER BY date_added DESC LIMIT ".$offset.",".$limit;
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function formatOrder($order_row){
        $order_id = $order_row['order_id'];
        $order_number = $order_id;
        $items = array();
        $customer_data = array();
        $payments = array();
        $payment_status = 'paid';
        $sub_total = 0;
        $tax_amount = 0;
        $discount_amount = 0;
        $final_discount_amount = 0;
        $discount_tax_amount = 0;
        $discount_excl_tax = 0;
        $grand_total = $order_row['total'];
        $shipping_cost = 0;
        $shipping_tax = 0;
        $sale_person_name = '';
        $created_at = '';
        $discount_tax_details =  array();
        $status =  'ok';

        $result = array(
            'id' => $order_id,
            'order_id' => $order_id,
            'system_order_id' => $order_id,
            'pos_order_id' => $order_id,
            'order_number' => $order_number,
            'order_number_format' => '#'.$order_number,
            'title' => '',
            'items' => $items,
            'customer' => $customer_data,
            'sub_total' => $sub_total, //excl tax
            'sub_total_incl_tax' => $sub_total, // incl tax
            'tax_amount' => $tax_amount,
            'discount_amount' => (float)$discount_amount,
            'discount_type' => 'fixed',
            'discount_final_amount' => (float)$discount_amount,
            'final_items_discount_amount' => 0,
            'final_discount_amount' => (float)$final_discount_amount,
            'discount_tax_amount' => $discount_tax_amount,
            'discount_excl_tax' => $discount_excl_tax,
            'grand_total' => 1 * $grand_total,
            'discount_code' => '',
            'discount_codes' => array(),
            'discount_code_amount' => 0,
            'discount_code_tax_amount' => 0,
            'discount_code_excl_tax' => 0,
            'payment_method' => $payments, //ref , paid , return
            'shipping_cost' => $shipping_cost,
            'shipping_tax' => $shipping_tax,
            'shipping_rate_id' => '',
            'shipping_information' => array(),
            'sale_person' => 0,
            'sale_person_name' => $sale_person_name,
            'note' => '',
            'created_at' => $created_at,
            'state' => ($payment_status == 'paid') ? 'completed' : 'pending_payment',
            'online_payment' => false,
            'print_invoice' => true,
            'point_discount' => 0,
            'add_discount' => ($final_discount_amount > 0),
            'add_shipping' => false,
            'add_tax' => true,
            'custom_tax_rate' => '',
            'custom_tax_rates' => array(),
            'tax_details' => array(),
            'discount_tax_details' => $discount_tax_details,
            'source_type' => 'online',
            'source' => '#'.$order_number,
            'available_shipping_methods' => array(),
            'mode' => '',
            'is_takeaway' => false,
            'checkout_url' => '',
            'payment_status' => $payment_status,
            'status' => $status,
            'allow_refund' => false,
            'allow_pickup' => false,
            'allow_checkout' => false
        );
        return $result;
    }

    public function getCountries(){
        $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country`");
        
        return $country_query->rows;
    }
    public function getCurrentDbVersion(){
        $result = 0;
        $version_query = $this->db->query("SELECT MAX(version_id) as current_version FROM `" . DB_PREFIX . "openpos_product_log`");
        $query_result = $version_query->row;
        
        if(!empty($query_result))
        {
           
            $result = $query_result['current_version'];
        }
        return $result;
    }
    public function addProductLog($product_id , $version_id){
        $sql = "INSERT INTO " . DB_PREFIX . "openpos_product_log SET product_id = '" . (int)$product_id . "',version_id = '" . (int)$version_id . "'";
        $this->db->query($sql);
    }
    public function getOutOfDateProducts($from_version = 0){
        $result = array();
        $version_query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "openpos_product_log` WHERE version_id > ".(int)$from_version);
        $query_result = $version_query->rows;
        
        if(!empty($query_result))
        {
           
            $result = $query_result;
        }
        return $result;
    }
    public function addZReport($data){
        $session = issset($data['session']) ? $data['session'] : '';
        if($session)
        {
            $content = issset($data['content']) ? json_encode($data['content']) : '';
            $sql = "INSERT INTO " . DB_PREFIX . "openpos_z_report SET session = '" . $this->db->escape($session). "',report_content = '" . $this->db->escape($content) . "'";
        }
        $this->db->query($sql);
    }

}