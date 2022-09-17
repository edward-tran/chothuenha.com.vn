<?php
class ControllerExtensionModuleOpenpos extends Controller {
    public $op_session;
	public function index() {
        header('Access-Control-Allow-Origin: *');
        global $_front_openpos;
        $_front_openpos = true;

        $this->load->library('openpos');
        $this->load->model('extension/module/openpos');
        $this->load->model('catalog/product');
        $this->load->model('setting/setting');
        $this->load->model('account/customer');
        $this->load->model('account/order');
        $this->load->language('extension/module/openpos');
        $this->op_session = $this->openpos->getPosSession();

        $result = array('status' => 0, 'message' => '','data' => array('framework'=>'opencart','version'=> 0,'params' => $this->request->post));

        $api_action = isset($this->request->post['pos_action']) ? $this->request->post['pos_action'] : '';
        $validate = false;

        if($api_action == 'login' || $api_action == 'logout')
        {
            $validate = true;
        }else{
            $session_id = trim($this->request->post['session']);
            if($session_id )
            {
                if($this->openpos->validateSession($session_id))
                {
                    $session_data = $this->_getSessionData();
                    $warehouse_id = isset($session_data['login_warehouse_id']) ? $session_data['login_warehouse_id'] : 0;
                    $this->config->set('config_store_id',$warehouse_id);
                    $store_info = $this->model_setting_setting->getSetting('config', 0);
                    $current_store_info = $this->model_setting_setting->getSetting('config', $warehouse_id);
                    foreach($current_store_info as $key => $value)
                    {
                        $store_info[$key] = $value;
                    }
                    foreach($store_info as $key => $val)
                    {
                        $ignores = array('config_ssl','config_url');
                        if(!in_array($key,$ignores))
                        {
                            $this->config->set($key,$val);
                        }
                    }

                    $validate = true;
                }else{
                    $validate = false;
                    $result['status'] = -1;
                }
            }
        }
        if($validate )
        {
            switch ($api_action)
            {
                case 'login':
                    if($login = $this->login())
                    {
                        $result = $login;
                    }
                    break;
                case 'logout':
                    if($logout = $this->logout())
                    {
                        $result = $logout;
                    }
                    break;
                case 'login_cashdrawer':
                    $result = $this->login_cashdrawer();
                    break;
                case 'update_qty_products':
                    $local_db_version = isset($this->request->post['local_db_version']) ? $this->request->post['local_db_version'] : 0;
                    if(!is_numeric($local_db_version))
                    {
                        $local_db_version = 0;
                    }
                    $result = $this->getProducts(true,$local_db_version);
                    break;
                case 'products':
                    $result = $this->getProducts();
                    break;
                case 'stock_over_view':
                    $result = $this->getStockOverView();
                    break;
                case 'orders':
                    //get online order --pending
                    break;
                case 'new-order':
                    $result = $this->add_order();
                    break;
                case 'payment-order':
                    $result = $this->payment_order();
                    break;
                case 'update-order':
                    $result = $this->update_order();
                    break;
                case 'customers':
                    $result = $this->search_customer();
                    break;
                case 'search-customer-by':
                    $result = $this->search_customer_by();
                    break;
                case 'update-customer':
                    $result = $this->update_customer();
                    break;
                case 'new-customer':
                    $result = $this->add_customer();
                    break;
                case 'new-transaction':
                    $result = $this->add_transaction();
                    break;
                case 'transactions':
                    //pending - get online transactions
                    break;
                case 'check-coupon':
                    $result = $this->check_coupon();
                    break;
                case 'refund-order':
                    $result = $this->refund_order();
                    break;
                case 'search-order':
                    $result = $this->search_order();
                    break;
                case 'pickup-order':
                    $result = $this->pickup_order();
                    break;
                case 'logon':
                    $result = $this->logon();
                    break;
                case 'latest-order':
                    $result = $this->get_latest_order();
                    break;
                case 'get_order_number':
                    $result = $this->get_order_number();
                    break;
                case 'check':
                    $result = $this->update_state();
                    break;
                case 'search_product':
                    $result = $this->search_product();
                    break;
                case 'get-customer-orders':
                    $result = $this->get_customer_orders();
                    break;
                case 'add_custom_product':
                    $result = $this->add_custom_product();
                    break;
                case 'get-carts':
                    $result = $this->get_carts();
                    break;
                case 'load-cart':
                    $result = $this->load_cart();
                    break;
                case 'get-customer-field':
                    $result = $this->get_customer_field();
                case 'upload_file_option':
                    $result = $this->upload_file_option();
                    break;
            }
        }
        $result['database_version'] = -1;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
	}
	public function login(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
            $user_name =  isset($this->request->post['username']) ?$this->request->post['username'] : '';
            $password =  isset($this->request->post['password']) ? $this->request->post['password'] : '';
            if(!$user_name || !$password)
            {
                throw new Exception('Your login information can not blank.');
            }

            $this->registry;
            $user = new \Cart\User($this->registry);

            $login = $user->login($user_name, html_entity_decode($password, ENT_QUOTES, 'UTF-8'));

            if ( !$login ) {
                throw new Exception('Your login information is incorrect. Please try again');
            }else{
                $user_name = $user->getUserName();
                $id = $user->getId();


                $sale_person = $this->getCashierList($id);
                $pos_balance = 0;
                $cash = array();
                $drawers = $this->getAllowCashdrawers($id);


                if(empty($drawers))
                {
                    throw new Exception('You do not assign to POS. Please contact with admin to resolve it.');
                }


                $price_included_tax = true;


                $user_data = $this->model_extension_module_openpos->getUser($id);

                session_start();
                $session_id = session_id();
                $ip = '';
                $user_login_data = array(
                    'user_id' => $id ,
                    'ip' => $ip,
                    'session' => $session_id ,
                    'username' =>  $user_name ,
                    'name' =>  implode(' ',array($user_data['firstname'],$user_data['lastname'])),
                    'email' =>  $user_data['email'] ,
                    'logged_time' => date('d-m-Y h:i:s'),
                    'session' => $session_id,
                    'sale_persons' => $sale_person,
                    'balance' => $pos_balance,
                    'cashes' => $cash,
                    'cash_drawers' => $drawers,
                    'price_included_tax' => $price_included_tax,
                    'location' => isset($this->request->post['location']) ? $this->request->post['location'] : ''
                );

                $result['data']= $user_login_data;


                $this->op_session->write($session_id,$result['data'] );
                $result['status'] = 1;
            }

        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }

        return $result;
    }
	public function logout(){
        $result = array('status' => 1, 'message' => '','data' => array('framework'=>'opencart','version'=> 0,'params' => $this->request->post));
        $session_id = isset($this->request->post['session']) ? trim($this->request->post['session']) : '';
        $session_data = $this->_getSessionData();
        if(!empty($session_data))
        {
            $this->op_session->clean($session_id);
        }
        return $result;
    }
	public function login_cashdrawer(){

        $result = array('status' => 0, 'message' => 'Unknown message','data' => array());
        $session_id = trim($this->request->post['session']);
        try{

            $session_data = $this->op_session->read($session_id);

            if(empty($session_data))
            {
                throw  new Exception('Your login session has been clean. Please try login again');
            }
            $cashdrawer_id = (int)$this->request->post['cashdrawer_id'];
            $cash_drawers = $session_data['cash_drawers'];
            $check = false;
            foreach($cash_drawers as $c)
            {
                if($c['id'] == $cashdrawer_id)
                {
                    $check = true;
                }
            }
            if($check)
            {
                $register = $this->model_extension_module_openpos->getRegister($cashdrawer_id);
                $warehouse_id = isset($register['store_id']) ? $register['store_id'] : 0;
                $setting = $this->openpos->getAllSettingValues($warehouse_id);

                $setting['openpos_customer_addition_fields'] = $this->getCustomerAdditionFields();

                $session_data['setting'] = $this->_formatSetting($setting);
                $session_data['payment_methods'] = $this->_formatPaymentMethod($setting['payment_methods']);

                $session_data['cash_drawer_balance'] = $this->model_extension_module_openpos->getRegisterBalance($cashdrawer_id);
                $session_data['balance'] = $this->model_extension_module_openpos->getRegisterBalance($cashdrawer_id);
                $session_data['default_display'] = isset($setting['dashboard_display']) ? $setting['dashboard_display'] : 'product';
                $session_data['sale_persons'] = $this->_getSalePerson($cashdrawer_id);
                $session_data['cashes'] = array();
                if(isset($session_data['setting']['pos_money']) && !empty($session_data['setting']['pos_money']))
                {
                    foreach($session_data['setting']['pos_money'] as $money)
                    {
                        $money['name'] = $this->currency->format($money['value'], $this->session->data['currency'],1);
                        $session_data['cashes'][] = $money;
                    }
                }
                $session_data['price_included_tax'] = true;

                $session_data['login_cashdrawer_id'] = $cashdrawer_id;
                $session_data['login_warehouse_id'] = $warehouse_id;


                $session_data['categories'] = $this->_formatCategories($setting);

                $session_data['currency_decimal'] = $this->_getCurrencyDecimal($warehouse_id);

                $session_data['time_frequency'] = isset($setting['time_frequency']) ? 1 * $setting['time_frequency'] : 3000;
                if($session_data['time_frequency'] < 1000)
                {
                    $session_data['time_frequency'] = 3000;
                }

                $session_data['product_sync'] = (isset($setting['pos_auto_sync']) && $setting['pos_auto_sync'] == 'no')? false : true;

                $this->op_session->clean($session_id);
                $this->op_session->write($session_id,$session_data);
                $result['data'] = $this->op_session->read($session_id);
                $result['status'] = 1;
            }
        }catch (Exception $e)
        {
            $this->op_session->clean($session_id);
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function getProducts($is_update = false,$db_version = 0){
        

        $session_data = $this->_getSessionData();
        $store_id = isset($session_data['login_warehouse_id']) ? $session_data['login_warehouse_id'] : 0;
        $this->config->set('config_store_id',$store_id);

        if($is_update)
        {
            $products = array();
            $product_ids = $this->model_extension_module_openpos->getOutOfDateProducts( (int)$db_version);
            foreach($product_ids as $product_id)
            {
                $products[] =  $this->model_catalog_product->getProduct($product_id['product_id']);
            }
            
            $data = array('total_page' => 1,'page' => 1);
            
        }else{
            $page = isset($this->request->post['page']) ? (int)$this->request->post['page'] : 1;
            $current = $page;
            $sortBy = 'p.sort_order';
            $order = 'DESC';
            $limit = 30;
    
            $filter_data = array(
                'sort'               => $sortBy,
                'order'              => $order,
                'start'              => ($page - 1) * $limit,
                'limit'              => $limit
            );
    
            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
            $products = $this->model_catalog_product->getProducts($filter_data);
            $data = array('total_page' => ceil($product_total / $limit) + 1,'page' => $current);
        }

        $data['product'] = array();
        $session_data = $this->_getSessionData();
        $login_cashdrawer_id = isset($session_data['login_cashdrawer_id']) ?  $session_data['login_cashdrawer_id'] : 0;
        $show_out_of_stock_setting = true;
        if($show_out_of_stock_setting == 'yes')
        {
            $show_out_of_stock = true;
        }


        foreach($products as $_product)
        {

            $warehouse_id = 0;
            if($login_cashdrawer_id > 0)
            {
                $warehouse_id = $session_data['login_warehouse_id'];

            }

            $product_data = $this->openpos->get_product_formatted_data($_product,$warehouse_id);

            if(!$product_data)
            {
                continue;
            }

            if(!$show_out_of_stock)
            {
                if( $product_data['manage_stock'] &&  is_numeric($product_data['qty']) && $product_data['qty'] <= 0)
                {
                    continue;
                }
            }
            if(empty($product_data))
            {
                continue;
            }
            $product_data['price_display_html'] = $this->currency->format(($product_data['price'] + $product_data['tax_amount']), $this->session->data['currency'],1);

            $data['product'][] = $product_data;

        }
        if($is_update)
        {
            return array(
                'product' => $data['product'],
                'total_page' => $data['total_page'],
                'current_page' => $data['page'],
                'version' => 1 * $this->model_extension_module_openpos->getCurrentDbVersion()
            );
        }else{
            return array(
                'product' => $data['product'],
                'total_page' => $data['total_page'],
                'current_page' => $data['page']
            );
        }
        

    }

    public function add_order(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
            $this->load->model('setting/store');
            $this->load->model('setting/setting');
            $this->load->model('tool/upload');
            $session_data = $this->_getSessionData();
           
            $login_cashdrawer_id = isset($session_data['login_cashdrawer_id']) ? $session_data['login_cashdrawer_id'] : 0;
            $login_warehouse_id = isset($session_data['login_warehouse_id']) ? $session_data['login_warehouse_id'] : 0;

            $store_info = $this->model_setting_setting->getSetting('config', 0);
            $current_store_info = $this->model_setting_setting->getSetting('config', $login_warehouse_id);
            foreach($current_store_info as $key => $value)
            {
                $store_info[$key] = $value;
            }

            $order_data = json_decode(html_entity_decode($this->request->post['order']),true);

            $order_parse_data = $order_data;
            $order_number = isset($order_parse_data['order_number']) ? $order_parse_data['order_number'] : 0;

            $store_setting = $this->openpos->getAllSettingValues($login_warehouse_id);

            $register_setting = $this->model_extension_module_openpos->getRegisterSetting($login_cashdrawer_id);

            $items = isset($order_parse_data['items']) ? $order_parse_data['items'] : array();
            if(empty($items))
            {
                throw new Exception('Item not found.');
            }
            $customer_id = 0;
            $customer = isset($order_parse_data['customer']) ? $order_parse_data['customer'] : array();
            if(!empty($customer) && isset($customer['id']))
            {
                $customer_id = $customer['id'];
            }
            $addition_informations =  isset($order_parse_data['addition_information']) ? $order_parse_data['addition_information'] : array();
            $tmp_addition_informations_str = array();
            foreach($addition_informations as $k => $v)
            {
                $tmp_addition_informations_str[] = $k.':'.$v;
            }
            $addition_informations_str = implode(', ',$tmp_addition_informations_str);

            $sub_total = isset($order_parse_data['sub_total']) ? floatval($order_parse_data['sub_total']) : 0;
            $tax_amount = isset($order_parse_data['tax_amount']) ? floatval($order_parse_data['tax_amount']) : 0;
            $discount_amount = isset($order_parse_data['discount_amount']) ? floatval($order_parse_data['discount_amount']) : 0;
            $discount_type = isset($order_parse_data['discount_code']) ? floatval($order_parse_data['discount_code']) : 0;
            $final_discount_amount = isset($order_parse_data['final_discount_amount']) ? floatval($order_parse_data['final_discount_amount']) : 0;
            $grand_total = isset($order_parse_data['grand_total']) ? floatval($order_parse_data['grand_total']) : 0;
            $point_paid = isset($order_parse_data['point_paid']) ? $order_parse_data['point_paid'] : 0;
            $discount_code = isset($order_parse_data['discount_code']) ? floatval($order_parse_data['discount_code']) : '';
            $discount_code_amount = isset($order_parse_data['discount_code_amount']) ? floatval($order_parse_data['discount_code_amount']) : 0;
            $payment_method = isset($order_parse_data['payment_method']) ? $order_parse_data['payment_method'] : array();
            $shipping_information = isset($order_parse_data['shipping_information']) ? $order_parse_data['shipping_information'] : array();
            $sale_person_id = isset($order_parse_data['sale_person']) ? intval($order_parse_data['sale_person']) : 0;
            $sale_person_name = isset($order_parse_data['sale_person_name']) ? $order_parse_data['sale_person_name'] : '';
            $created_at = isset($order_parse_data['created_at']) ? $order_parse_data['created_at'] : current_time( 'timestamp', true );
            $order_id = isset($order_parse_data['order_id']) ? $order_parse_data['order_id'] : '';
            $store_id = $login_warehouse_id;
            $is_online_payment = ($order_parse_data['online_payment'] == 'true') ? true : false;
            $order_state = isset($order_parse_data['state']) ? $order_parse_data['state'] : 'completed';
            $order_session = isset($order_parse_data['session']) ? $order_parse_data['session'] : '';


            $tmp_setting_order_status = 0;//$this->settings_api->get_option('pos_order_status','openpos_general');
            $setting_order_status =  $tmp_setting_order_status;
            if($order_state == 'pending_payment')
            {
                $is_online_payment = true;
            }

            $point_discount = isset($order_parse_data['point_discount']) ? $order_parse_data['point_discount'] : array();

            $shipping_cost = isset($order_parse_data['shipping_cost']) ? $order_parse_data['shipping_cost'] : 0;
            $shipping_address_id = isset($shipping_information['address_id']) ? $shipping_information['address_id'] : 0;
            $shipping_first_name  = '';
            $shipping_last_name = '';
            if(isset($shipping_information['name']))
            {
                $name = trim($shipping_information['name']);
                $tmp = explode(' ',$name);
                if(count($tmp) > 0)
                {
                    $shipping_first_name = $tmp['0'];
                    $shipping_last_name = substr($name,strlen($shipping_first_name));
                }
            }

            $cashier_id = $session_data['user_id'];
            $note = isset($order_parse_data['note']) ? $order_parse_data['note'] : '';

            $check_order = $this->model_extension_module_openpos->checkOrderExist($order_id);
            $db_version = time();
            
            if(!$check_order )
            {

                $order_data = array();
                $payment_custom_fields = array(
                    'openpos_sale_person_id' =>$sale_person_id,
                    'openpos_sale_person_name' =>$sale_person_name,
                    'openpos_created_at' =>$created_at,
                    'openpos_payment_methods' => $payment_method
                );
                // Store Details
                $order_data['order_id'] = $order_number;
                $order_data['invoice_prefix'] = $store_info['config_invoice_prefix'];
                $order_data['store_id'] = $store_id;

                $order_data['store_name'] = $this->config->get('config_name');
                $order_data['store_url'] = $this->config->get('config_url');

                // Customer Details

                $customer_details = $this->model_extension_module_openpos->getCustomerInformation($customer_id);
                $order_data['customer_id'] = $customer_details['customer_id'];
                $order_data['customer_group_id'] = $customer_details['customer_group_id'];
                $order_data['firstname'] = $customer_details['firstname'];
                $order_data['lastname'] = $customer_details['lastname'];
                $order_data['email']  = $customer_details['email'];
                $order_data['telephone'] = $customer_details['telephone'];
                $order_data['custom_field']  = array();


                // Payment Details
                $order_data['payment_firstname'] = $customer_details['firstname'];
                $order_data['payment_lastname'] = $customer_details['lastname'];
                $order_data['payment_company'] = isset($customer_details['company']) ? $customer_details['company'] : '';
                $order_data['payment_address_1'] = isset($customer_details['address_1']) ? $customer_details['address_1'] : '';
                $order_data['payment_address_2'] = isset($customer_details['address_2']) ? $customer_details['address_2'] : '';
                $order_data['payment_city'] = isset($customer_details['city']) ? $customer_details['city'] : '';
                $order_data['payment_postcode'] = isset($customer_details['postcode']) ? $customer_details['postcode'] : '';
                $order_data['payment_zone'] = isset($customer_details['zone']) ? $customer_details['zone'] : '';
                $order_data['payment_zone_id'] = isset($customer_details['zone_id']) ? $customer_details['zone_id'] : '';
                $order_data['payment_country'] = isset($customer_details['country']) ? $customer_details['country'] : '';
                $order_data['payment_country_id'] = isset($customer_details['country_id']) ? $customer_details['country_id'] : '';
                $order_data['payment_address_format'] = '';
                $order_data['payment_custom_field'] = $payment_custom_fields;
                $order_data['payment_method'] =  $this->language->get('text_op_payment_method');
                $order_data['payment_code'] = 'openpos';

                // Shipping Details

                $order_data['shipping_firstname'] = $shipping_first_name;
                $order_data['shipping_lastname'] = $shipping_last_name;
                $order_data['shipping_company'] = '';
                $order_data['shipping_address_1'] = '';
                $order_data['shipping_address_2'] = '';
                $order_data['shipping_city'] = '';
                $order_data['shipping_postcode'] = '';
                $order_data['shipping_zone'] = '';
                $order_data['shipping_zone_id'] = '';
                $order_data['shipping_country'] = '';
                $order_data['shipping_country_id'] = '';
                $order_data['shipping_address_format'] = '';
                $order_data['shipping_custom_field'] = array();
                $order_data['shipping_method'] = '';
                $order_data['shipping_code'] = '';
                if($shipping_address_id > 0)
                {
                    $customer_address = $this->model_extension_module_openpos->getAddress($shipping_address_id);
                    if($customer_address)
                    {
                        $order_data['shipping_company'] = $customer_address['company'];
                        $order_data['shipping_address_1'] = $customer_address['address_1'];
                        $order_data['shipping_address_2'] = $customer_address['address_2'];
                        $order_data['shipping_city'] = $customer_address['city'];
                        $order_data['shipping_postcode'] = $customer_address['postcode'];
                        $order_data['shipping_zone'] = $customer_address['zone'];
                        $order_data['shipping_zone_id'] = $customer_address['zone_id'];
                        $order_data['shipping_country'] = $customer_address['country'];
                        $order_data['shipping_country_id'] = $customer_address['country_id'];
                        $order_data['shipping_address_format'] = $customer_address['address_format'];
                    }
                }
                if(isset($order_parse_data['shipping_information']) && !empty($order_parse_data['shipping_information']) && isset($order_parse_data['add_shipping']) && $order_parse_data['add_shipping'])
                {
                    if($shipping_information['address']){
                        $order_data['shipping_address_1'] = $shipping_information['address'];
                    }
                    if($shipping_information['phone']){
                        $order_data['shipping_custom_field']['telephone'] = $shipping_information['phone'];
                    }
                    if($shipping_information['email']){
                        $order_data['shipping_custom_field']['email'] = $shipping_information['email'];
                    }
                    if($shipping_cost){
                        $order_data['shipping_custom_field']['cost'] = $shipping_cost;
                    }

                    if($shipping_information['shipping_method']){
                        $order_data['shipping_method'] = $shipping_information['shipping_method'];
                        $order_data['shipping_code'] = $shipping_information['shipping_method'];
                    }
                }
                if($order_parse_data['note'])
                {
                    $order_data['shipping_custom_field']['note'] = $order_parse_data['note'];
                }

                $is_custom_tax = false;
                if(isset($order_parse_data['custom_tax_rate']) &&  $order_parse_data['custom_tax_rate'] > 0)
                {
                    $is_custom_tax = true;
                }
                // Products
                $order_data['products'] = array();
                $order_item_note = array();


                foreach ($items as $product) {

                    $option_data = array();
                    if(isset($product['options']))
                    {
                        $product_options = $this->model_catalog_product->getProductOptions($product['product_id']);
                        
                        foreach ($product['options'] as $option) {
                            $product_option_id = $option['option_id'];
                            $op_data = array();
                            foreach($product_options as $product_option)
                            {
                                if($product_option['product_option_id'] == $product_option_id)
                                {
                                    $op_data = $product_option;
                                }
                            }

                            if(!empty($op_data))
                            {
                                $product_option_value = $op_data['product_option_value'];

                                

                                foreach($option['value_id'] as $key => $value_id)
                                {
                                    $option_value_id = '';
                                    if($option['type'] == 'upload')
                                    {
                                       
                                        //$file_name = 
                                        //move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);
                                        $filename = $value_id['filename'];
                                        $file = $filename . '.' . token(32);
                                        $file_path =  DIR_UPLOAD . $file ;
                                        file_put_contents($file_path ,base64_decode($value_id['value']));
                                        $option_value_id = $this->model_tool_upload->addUpload($filename, $file);
                                        $upload_info = $this->model_tool_upload->getUploadByCode($option_value_id);
                                        
                                        $option_data[] = array(
                                            'product_option_id'       => $option['option_id'],
                                            'product_option_value_id' => 0,
                                            'option_id'               => $op_data['option_id'],
                                            'option_value_id'         => $option_value_id , //
                                            'name'                    => 'File',
                                            'value'                   => $option_value_id,
                                            'type'                    => 'file'
                                        );
                                      
                                    }else{
                                        foreach($product_option_value as $p_o_v)
                                        {
                                            if($p_o_v['product_option_value_id'] == $value_id)
                                            {
                                                $option_value_id = $p_o_v['option_value_id'];
                                            }
                                        }
                                        $option_data[] = array(
                                            'product_option_id'       => $option['option_id'],
                                            'product_option_value_id' => $value_id,
                                            'option_id'               => $op_data['option_id'],
                                            'option_value_id'         => $option_value_id, //
                                            'name'                    => $op_data['name'],
                                            'value'                   => isset($option['value_label'][$key]) ? $option['value_label'][$key] : '',
                                            'type'                    => $option['type']
                                        );
                                    }
                                }
                            }




                        }
                    }


                    $product_data = $this->model_catalog_product->getProduct($product['product_id']);


                    if($addition_informations_str)
                    {
                        $product['note'] .= $addition_informations_str;
                    }

                    if($product['note'])
                    {
                        //$order_item_note[] = $product['name'].'(x'.$product['qty'].') - '.$product['note'];

                        $option_data[] = array(
                            'product_option_id'       => 0,
                            'product_option_value_id' => 0,
                            'option_id'               => 0,
                            'option_value_id'         => 0, //
                            'name'                    => 'Note',
                            'value'                   =>  $product['note'],
                            'type'                    => 'text'
                        );


                    }
                    $product_model = isset($product['product']['model']) ? $product['product']['model'] : $product['product']['sku'];
                    if($product_data && $product_data['model'])
                    {
                        $product_model = $product_data['model'];
                    }
                    if($is_custom_tax)
                    {
                        $order_data['products'][] = array(
                            'product_id' => $product['product_id'],
                            'name'       => $product['name'],
                            'model'      => $product_model,
                            'option'     => $option_data,
                            'quantity'   => $product['qty'],
                            'subtract'   => $product_data['subtract'],
                            'price'      => $product['final_price'],
                            'total'      => $product['total'],
                            'tax'        => 0,
                            'reward'     => $product_data['reward']
                        );
                    }else{
                        $order_data['products'][] = array(
                            'product_id' => $product['product_id'],
                            'name'       => $product['name'],
                            'model'      => $product_model,
                            'option'     => $option_data,
                            'quantity'   => $product['qty'],
                            'subtract'   => $product_data['subtract'],
                            'price'      => $product['final_price'],
                            'total'      => $product['total'],
                            'tax'        => $product['product']['tax_amount'],
                            'reward'     => $product_data['reward']
                        );
                    }
                    if($product['product_id'] > 0)
                    {
                        $this->model_extension_module_openpos->addProductLog($product['product_id'],$db_version);
                    }
                    
                }

                // Gift Voucher
                $order_data['vouchers'] = array();


                // Order Totals
                $this->load->model('setting/extension');

                $totals = array();
                $taxes = array();
                $total = 0;

                // Because __call can not keep var references so we put them into an array.
                $total_data = array(
                    'totals' => &$totals,
                    'taxes'  => &$taxes,
                    'total'  => &$total
                );

                //sub_total , grand_total

                $sort_order = array();

                $results = $this->model_setting_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get('total_' . $result['code'] . '_status')) {
                        $this->load->model('extension/total/' . $result['code']);

                        // We have to put the totals in an array so that they pass by reference.
                        $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                    }
                }

                $sort_order = array();
                $tax_total_key = -1;
                $grand_total_sort_order = -1;
                foreach ($total_data['totals'] as $key => $value) {
                    if($value['code'] == 'sub_total')
                    {
                        $total_data['totals'][$key]['value'] = $sub_total;
                    }
                    if($value['code'] == 'total')
                    {
                        $total_data['totals'][$key]['value'] = $grand_total;
                        $grand_total_sort_order = $value['sort_order'];
                    }
                    $sort_order[$key] = $value['sort_order'];
                    if($value['code'] == 'tax')
                    {
                        $tax_total_key = $key;
                    }
                }
                $tax_amount = $order_parse_data['tax_amount'];
                if($is_custom_tax || $tax_amount > 0)
                {

                    if($tax_total_key >= 0)
                    {
                        $total_data['totals'][$tax_total_key]['value'] = $tax_amount;
                    }else{
                        $total_data['totals'][] = array(
                            'sort_order' => ($grand_total_sort_order - 1),
                            'value' => $tax_amount,
                            'code' => 'tax',
                            'title' => 'Tax('.$order_parse_data['custom_tax_rate'].'%)'
                        );
                    }
                }

               if(count($total_data['totals']) > 1)
               {
                   @array_multisort($sort_order, SORT_ASC, $total_data['totals']);
               }

                $order_data = array_merge($order_data, $total_data);

                if (isset($this->request->post['comment'])) {
                    $order_data['comment'] = $this->request->post['comment'];
                } else {
                    $order_data['comment'] = '';
                }
                if(!empty($order_item_note))
                {

                    $order_data['comment'] .= PHP_EOL;
                    $order_data['comment'] .= implode(PHP_EOL,$order_item_note);

                }

                $order_data['affiliate_id'] = 0;
                $order_data['commission'] = 0;
                $order_data['marketing_id'] = 0;
                $order_data['tracking'] = '';


                $order_data['language_id'] = $this->config->get('config_language_id');
                $order_data['currency_id'] = $register_setting['currency_id'];
                $order_data['currency_code'] = $register_setting['currency_code'];
                $order_data['currency_value'] = $register_setting['currency_value'];

                $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

                if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                    $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                    $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
                } else {
                    $order_data['forwarded_ip'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data['user_agent'] = '';
                }

                if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                    $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
                } else {
                    $order_data['accept_language'] = '';
                }

                $this->load->model('checkout/order');

                $order_id = $this->model_extension_module_openpos->addOrder($order_data);
                $pos_order_data = array(
                    'cashier_user_id' => $cashier_id,
                    'seller_user_id' => $sale_person_id,
                    'register_id' => $login_cashdrawer_id,
                    'comment' =>$note,
                    'order_content' =>$order_data,
                    'local_id' => $order_id,
                    'session' => $order_session
                );

                $this->model_extension_module_openpos->addPosOrder($order_id,$pos_order_data);

                $payment_method_data = array();
                foreach ( $payment_method as $payment)
                {
                    $tmp = array(
                        'payment_code' => $payment['code'],
                        'payment_title' => $payment['name'],
                        'amount' => ($payment['paid'] - $payment['return']),
                        'ref' => isset($payment['ref']) ? $payment['ref'] : '',
                    );
                    if($tmp['amount'] > 0)
                    {
                        $payment_method_data[] = $tmp;
                    }
                }

                $this->model_extension_module_openpos->addOrderPayment($order_id,$payment_method_data);

                // Set the order history

                $order_status_id = $store_setting['pos_order_status'];


                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id,'Completed on POS' );

                if($customer_id && !empty($point_discount))
                {
                    $used_point = $point_discount['point'];
                    $used_point_money = $point_discount['point_money'];
                    if($used_point > 0 && $used_point_money > 0)
                    {
                        $sub_total_index = 0;
                        $pos_total_data = array(
                            'code' => 'pos_used_point',
                            'title' => 'Customer Point Redeem( '.$used_point.' )',
                            'value' => (0 - $used_point_money),
                            'sort_order' => $sub_total_index,
                        );

                        $this->model_extension_module_openpos->addPosOrderTotal($order_id,$pos_total_data);
                        //reduct customer reward point
                        $this->model_extension_module_openpos->addCustomerReward($customer_id,(0 - $used_point),'#'.$order_id.' - '.$pos_total_data['title']);

                    }

                }
                //custom cart discount
                if($order_parse_data['add_discount'] && $order_parse_data['final_discount_amount'] > 0)
                {
                    $pos_total_data = array(
                        'code' => 'pos_cart_discount',
                        'title' => $this->language->get('text_op_discount'),
                        'value' => (0 - 1*$order_parse_data['final_discount_amount']),
                        'sort_order' => $this->config->get('total_coupon_sort_order'),
                    );
                    if($order_parse_data['discount_code'] && $order_parse_data['discount_code_amount'] > 0)
                    {
                        $pos_total_data['title'] .= '( '.$order_parse_data['discount_code'].' - '.$order_parse_data['discount_code_amount'].')';
                    }
                    $this->model_extension_module_openpos->addPosOrderTotal($order_id,$pos_total_data);
                }
                if($shipping_cost > 0 && isset($order_parse_data['add_shipping']) && $order_parse_data['add_shipping'])
                {
                    $pos_total_data = array(
                        'code' => 'shipping',
                        'title' => $this->language->get('text_op_shipping'),
                        'value' => (1* $shipping_cost),
                        'sort_order' => 8,
                    );
                    $this->model_extension_module_openpos->addPosOrderTotal($order_id,$pos_total_data);
                }
                if( $order_parse_data['discount_code'] && $order_parse_data['discount_code_amount'] > 0)
                {
                    $this->update_used_coupon($order_id,$customer_id,$order_parse_data['discount_code'],$order_parse_data['discount_code_amount']);
                }
                $result['data'] = $this->_formatOrder($order_id);
            }else{
                $result['data'] = $this->_formatOrder($check_order);
            }

            $result['status'] = 1;
            //shop_order

        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }

        return $result;
    }

	public function getStockOverView(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try {
            $session_data = $this->_getSessionData();
            $store_id = $session_data['login_warehouse_id'];
            $barcode = $this->request->post['barcode'];
            if(!$barcode)
            {
                throw new Exception('Barcode not found. Please check again.');
            }
            $product_id = $this->openpos->getProductIdByBarcode($barcode,$store_id);
            if(!$product_id)
            {
                throw new Exception('Barcode not found. Please check again.');
            }
            $store_name = 'Default Store';

            $qty = $this->openpos->getProductQty($product_id,$store_id);
            $result['data'][]  = array( 'warehouse' => $store_name , 'qty' => $qty );
            $result['status'] = 1;
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }

	public function payment_order(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try {

        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function search_customer(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try {
            $this->load->model('account/customer');
            $this->load->model('account/address');

            $term = $this->request->post['term'];
            $rows =  $this->model_extension_module_openpos->customers($term);
            $customers = array();

            foreach($rows as $r)
            {
                $address = '';
                $address_2 = '';
                $city = '';
                $state = '';
                $postcode = '';
                $country = '';
                if($r['address_id'] > 0)
                {

                    $address_data = $this->model_extension_module_openpos->getAddress($r['address_id']);
                    
                    if($address_data)
                    {
                        $address = $address_data['address_1'];
                        $address_2 = $address_data['address_2'];
                        $postcode = $address_data['postcode'];
                        $city = $address_data['city'];
                        $state = $address_data['zone_id'];
                        $country = $address_data['country_id'];
                    }

                }
                $reward = $this->model_account_customer->getRewardTotal($r['customer_id']);
                if(!$this->_getPointRate())
                {
                    $reward = 0;
                }
               
                $customer_data = array(
                    'id' => $r['customer_id'],
                    'customer_group_id' => $r['customer_group_id'],
                    'name' => implode(' ',array( trim($r['firstname']), trim($r['lastname']))),
                    'firstname' => trim($r['firstname']),
                    'lastname' =>  trim($r['lastname']),
                    'address' => $address,
                    'address_2' => $address_2,
                    'city' => $city,
                    'state' => $state,
                    'postcode' => $postcode,
                    'country' => $country,
                    'phone' => $r['telephone'],
                    'email' => $r['email'],
                    'billing_address' => $address,
                    'point' => (1 * $reward),
                    'point_rate' => $this->_getPointRate(),
                    'point_rules' => array(),
                    'discount' => 0,
                    'shipping_address' => $this->model_extension_module_openpos->getShippingAddress($r['customer_id'])
                );
                $customers[] = $customer_data;
            }
            $result['data'] = $customers;
            $result['status'] = 1;
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
	    return $result;
    }
    function update_order(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
            $this->load->model('checkout/order');
            $order_data = json_decode(html_entity_decode($this->request->post['order']),true);

            $order_number = isset($order_data['order_number']) ? $order_data['order_number'] : 0;

            if(isset($order_data['refunds']) && !empty($order_data['refunds']))
            {
                $refund = end($order_data['refunds']);
                $reason = isset($refund['reason']) ? $refund['reason'] : 'Refund from POS';
                $order_status_id = '11';
                $order_id = $order_number;
                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id,$reason );
            }

            $result['status'] = 1;
        }catch (Exception $e) {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
    function update_customer(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
            $this->load->model('account/customer');
            $this->load->model('account/address');
            $customer_id = $this->request->post['id'];
            $name = $this->request->post['name'];
            $address = $this->request->post['address'];
            $phone = $this->request->post['phone'];
            $email = $this->request->post['email'];
            $tmp_name = explode(' ',$name);
            $firstname = '';
            $lastname = '';
            if(!empty($tmp_name))
            {
                $firstname = trim($tmp_name[0]);
                $lastname = str_replace($firstname,'',$name);
            }

            if(!$email || !$phone)
            {
                throw new Exception('Please enter customer email and customer phone');
            }

            $address_2 = $this->request->post['address_2'];
            $city = $this->request->post['city'];
            $state = (int)$this->request->post['state'];
            $postcode = $this->request->post['postcode'];
            $country = (int)$this->request->post['country'];

            $customer_by_email = $this->model_account_customer->getCustomerByEmail($email);
            $customer = $this->model_account_customer->getCustomer($customer_id);
            if(!$customer_by_email || !$customer)
            {
                throw new Exception('Your customer is not found. Please add new');
            }
            if($customer_by_email['customer_id'] != $customer['customer_id'])
            {
                throw new Exception('Your customer is not found. Please add new');
            }
            $customer_data = array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $customer['email'],
                'telephone' => $phone,
                'custom_field' => $customer['custom_field'],
            );

            $this->model_account_customer->editCustomer($customer['customer_id'],$customer_data);

            if($address || $address_2 || $country || $city || $state || $postcode || $state)
            {
                $address_data = array(
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'company' => '',
                    'address_1' => $address,
                    'address_2' => $address_2,
                    'postcode' => $postcode,
                    'city' => $city,
                    'zone_id' => $state,
                    'country_id' => $country
                );

                if($customer['address_id'] > 0)
                {
                    $address_data = array(
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'address_1' => $address,
                        'address_2' => $address_2,
                        'postcode' => $postcode,
                        'city' => $city,
                        'zone_id' => $state,
                        'country_id' => $country
                    );
                    $old_address = $this->model_extension_module_openpos->getAddress($customer['address_id']);

                    if($old_address)
                    {
                        $address_data = array_merge($old_address,$address_data);
                    }
                  
                    $this->model_extension_module_openpos->editAddress($customer['address_id'],$address_data);
                }else{
                    $address_data['default'] = 1;
                    $this->model_account_address->addAddress($customer['customer_id'],$address_data);
                }

            }
            $result['data'] = array(
                'id' => $customer_id,
                'customer_group_id' => $customer['customer_group_id'],
                'name' => implode(' ',array( trim($firstname), trim($lastname))),
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $customer['email'],
                'phone' => $phone,
                'address' => $address,
                'address_2' => $address_2,
                'postcode' => $postcode,
                'city' => $city,
                'state' => $state,
                'country' => $country
            );

            $result['status'] = 1;
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function search_customer_by(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try {
            $this->load->model('account/customer');
            $this->load->model('account/address');
            $term = '';

            $by_data = json_decode(html_entity_decode($this->request->post['by_data']),true);
            $by = $this->request->post['by'];
            if(isset($by_data[$by]))
            {
                $term = $by_data[$by];
            }
            $customers = array();
            if(in_array($by,array('email','phone')) && $term != '')
            {
                if($by == 'phone')
                {
                    $by = 'telephone';
                }

                $rows =  $this->model_extension_module_openpos->customer_by($by,$term);


                foreach($rows as $r)
                {
                    $address = '';
                    $address_2 = '';
                    $city = '';
                    $state = '';
                    $postcode = '';
                    $country = '';

                    if($r['address_id'] > 0)
                    {

                        $address_data = $this->model_extension_module_openpos->getAddress($r['address_id']);

                        if($address_data)
                        {
                            $address = $address_data['address_1'];

                            $address_2 = $address_data['address_2'];
                            $postcode = $address_data['postcode'];
                            $city = $address_data['city'];
                            $state = $address_data['zone_id'];
                            $country = $address_data['country_id'];
                        }

                    }

                    $reward = $this->model_account_customer->getRewardTotal($r['customer_id']);
                    if(!$this->_getPointRate())
                    {
                        $reward = 0;
                    }
                    $customer_data = array(
                        'id' => $r['customer_id'],
                        'customer_group_id' => $r['customer_group_id'],
                        'name' => implode(' ',array( trim($r['firstname']), trim($r['lastname']))),
                        'firstname' => trim($r['firstname']),
                        'lastname' =>  trim($r['lastname']),
                        'address' => $address,
                        'address_2' => $address_2,
                        'postcode' => $postcode,
                        'city' => $city,
                        'state' => $state,
                        'country' => $country,
                        'phone' => $r['telephone'],
                        'email' => $r['email'],
                        'billing_address' => $address,
                        'point' => (1*$reward),
                        'point_rate' => $this->_getPointRate(),
                        'point_rules' => array(),
                        'discount' => 0,
                        'shipping_address' => $this->model_extension_module_openpos->getShippingAddress($r['customer_id'])
                    );
                    $customers = $customer_data;
                    $result['status'] = 1;
                }
            }

            $result['data'] = $customers;

        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function add_customer(){
        $result = array('status' => 0, 'message' => '','data' => array());
        $this->load->model('account/customer');
        $this->load->model('account/address');
        try {
            $name = $this->request->post['name'];
            $tmp_name = explode(' ',$name);
            $firstname = '';
            $lastname = '';
            if(!empty($tmp_name))
            {
                $firstname = trim($tmp_name[0]);
                $lastname = str_replace($firstname,'',$name);
            }
            $address = $this->request->post['address'];
            $email = strtolower($this->request->post['email']);
            $phone = $this->request->post['phone'];
            $this->load->model('account/customer');

            if(!$email || !$phone)
            {
                throw new Exception('Please enter customer email and customer phone');
            }

            $customer_by_email = $this->model_account_customer->getCustomerByEmail($email);

            if($customer_by_email && isset($customer_by_email['customer_id']) && $customer_by_email['customer_id'] > 0)
            {
                throw new Exception('Customer alrady exist. Please search to add');
            }

            $address_2 = $this->request->post['address_2'];
            $city = $this->request->post['city'];
            $state = (int)$this->request->post['state'];
            $postcode = $this->request->post['postcode'];
            $country = (int)$this->request->post['country'];

            $customer_data = array(
                'firstname' => trim($firstname),
                'lastname' => trim($lastname),
                'email' => $email,
                'telephone' => $phone,
                'password' => rand(1000000,9999999),
                'custom_field' => array(
                    'source' => 'openpos',
                    'address' => $address,
                ),
            );

            $customer_id = $this->model_account_customer->addCustomer($customer_data);
            if($customer_id && $address)
            {
                $address_data = array(
                    'firstname' => trim($firstname),
                    'lastname' => trim($lastname),
                    'company' => '',
                    'address_1' => $address,
                    'address_2' => $address_2,
                    'postcode' => $postcode,
                    'city' => $city,
                    'zone_id' => $state,
                    'country_id' => $country,
                    'default' => 1
                );
                $this->model_account_address->addAddress($customer_id,$address_data);


            }
            if($customer_id)
            {
                $customer = $this->model_account_customer->getCustomer($customer_id);
                
                $customer_data = array(
                    'id' => $customer['customer_id'],
                    'customer_group_id' => $customer['customer_group_id'],
                    'name' => implode(' ',array($customer['firstname'],$customer['lastname'])),
                    'firstname' => $customer['firstname'],
                    'lastname' => $customer['lastname'],
                    'address' => $address,
                    'address_2' => $address_2,
                    'postcode' => $postcode,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'phone' => $customer['telephone'],
                    'email' => $customer['email'],
                    'billing_address' => $address,
                    'point' => 0,
                    'discount' => 0
                );
                $result['status'] = 1;
                $result['data'] = $customer_data;
            }
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function add_transaction(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
            $session_data = $this->_getSessionData();

            $login_cashdrawer_id = isset($session_data['login_cashdrawer_id']) ? $session_data['login_cashdrawer_id'] : 0;
            $login_warehouse_id = isset($session_data['login_warehouse_id']) ? $session_data['login_warehouse_id'] : 0;
           

            $transaction_data = json_decode(html_entity_decode($this->request->post['transaction']),true);
            $local_id = json_decode(html_entity_decode($this->request->post['id']),true);
            $session = isset($transaction_data['session']) ? $transaction_data['session'] : '';
            $transaction = array(
                'created_by' => $session_data['user_id'],
                'ref' => $transaction_data['ref'],
                'in_amount' => isset($transaction_data['in_amount']) ? $transaction_data['in_amount'] : 0,
                'out_amount' => isset($transaction_data['out_amount']) ? $transaction_data['out_amount'] : 0,
                'register_id' => $login_cashdrawer_id,
                'store_id' => $login_warehouse_id,
                'session' => $session,
                'content' => $transaction_data
            );

            $next_transaction_id = $this->model_extension_module_openpos->addTransaction($local_id,$transaction);
            $result['status'] = 1;
            $result['data'] = $next_transaction_id;
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;

    }
    public function update_used_coupon($order_id,$customer_id,$coupon,$amount)
    {

        $coupon_info = $this->model_extension_total_coupon->getCoupon($coupon);
        if($coupon_info)
        {
            $coupon_data = array(
                'coupon_id' => $coupon_info['coupon_id'],
                'order_id' => $order_id,
                'customer_id'=> $customer_id,
                'value' => $amount,
            );
            $this->model_extension_module_openpos->addCouponHistory($coupon_data);
        }
    }
	public function check_coupon(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try {
            $this->load->language('api/coupon');
            $this->load->model('extension/total/coupon');
            $coupon = $this->request->post['code'];
            $coupon_info = $this->model_extension_total_coupon->getCoupon($coupon);
            $valid = false;
            if ($coupon_info && $coupon_info['status'] == 1) {

                $valid = true;
            } else {
                $result['message'] = $this->language->get('error_coupon');
            }

            if($valid)
            {

                $cart = json_decode(html_entity_decode($this->request->post['cart']),true);

                $current_time = time();
                if(strtotime($coupon_info['date_start']) > $current_time || strtotime($coupon_info['date_end'].' 23:59:59') < $current_time)
                {
                    throw new Exception('Your coupon invalid');
                }

                $coupon_total = $this->validCoupon($coupon_info,$cart);
                $result['valid'] = $valid;
                $result['amount'] = $coupon_total;
                $result['discount_type'] = $coupon_info['type'] == 'F' ? 'fixed' : 'percent';

                $result['data']['code'] = $coupon;
                $result['data']['amount'] = $coupon_total;
                $result['status'] = 1;
            }

        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function refund_order(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try {
            $this->load->model('checkout/order');
            $order = json_decode(html_entity_decode($this->request->post['order']),true);
            $refund_amount = $this->request->post['refund_amount'];
            $refund_qty = $this->request->post['refund_qty']; // true or false
            $refund_reason = $this->request->post['refund_reason'];
            $order_id = $order['order_id']; //order_id
            $session_data = $this->_getSessionData();

            $order_status_id = 11;
            $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, 'POS - '.$refund_reason,  false);

            $refund_data = array(
                'amount'     => $refund_amount,
                'reason'     => $refund_reason,
                'order_id'   => $order_id,
                'line_items' => array(),
                'restock_items' => $refund_qty
            );
            $result['data'] = $refund_data;
            $result['status'] = 1;
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
            $result['message'] = $e->getTraceAsString();
        }
        return $result;
    }
	public function search_order(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
            $term = $_REQUEST['term'];
            if(strlen($term) > 1)
            {

                $orders = $this->model_extension_module_openpos->searchOrders($term);

                if(count($orders) > 0)
                {
                    foreach($orders as $_order)
                    {
                        $formatted_order = $this->_formatOrder($_order['order_id']);
                        $result['data'][] = $formatted_order;
                    }
                    $result['status'] = 1;
                }else{
                    throw new Exception('Order is not found');
                }

            }else{
                throw new Exception('Order number too short');

            }
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function pickup_order(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try{

        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function logon(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try {
            $password =  isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
            $session_data = $this->_getSessionData();

            if(!$password)
            {
                throw new Exception('Please enter password');
            }
            $username = $session_data['username'];
            $user = new \Cart\User($this->registry);

            $login = $user->login($username, html_entity_decode($password, ENT_QUOTES, 'UTF-8'));

            if ( !$login ) {
                throw new Exception('Your password is incorrect. Please try again.');
            }
            $result['data'] = $session_data;
            $result['status'] = 1;
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function get_latest_order(){
        $result = array('status' => 0, 'message' => '','data' => array(
            'orders' => array(),
            'total_page' => 1
        ));
        try{
            $page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
            $list_type = isset($_REQUEST['list_type']) ? $_REQUEST['list_type'] : 'latest';
            $perpage = 10;
            if($list_type == 'latest')
            {
                $total_page = ceil($this->model_extension_module_openpos->getTotalOrders() / $perpage);
                $result['data']['total_page'] = $total_page;
                
                $orders = $this->model_extension_module_openpos->latestOrders($page,$perpage,$list_type);
            }else{
                $orders = $this->model_extension_module_openpos->latestOrders($page,$perpage,$list_type);
            }
            

            if(count($orders) > 0)
            {
                foreach($orders as $_order)
                {
                    $formatted_order = $this->_formatOrder($_order['order_id']);
                    $result['data']['orders'][] = $formatted_order;
                }
                $result['status'] = 1;
            }else{
                throw new Exception('Order is not found');
            }


        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function get_order_number(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
            $data = array(
                'invoice_prefix' => '',
                'store_id' => 0,
                'store_url' => '',
                'store_name' => '',
            );
            $next_order_id = $this->model_extension_module_openpos->renewOrderNumber($data);
            $result['status'] = 1;
            $result['data'] = array('order_number' => $next_order_id);
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
	public function update_state(){
        $result = array('status' => 1, 'message' => '','data' => array('framework'=>'opencart','version'=> 0,'params' => $this->request->post));
        $params = $this->request->post;
        $cart = array();
        if(isset($params['cart']))
        {
            $cart = json_decode(html_entity_decode($params['cart']),true);

        }
        $session_data = $this->_getSessionData();
        if(isset($session_data['login_cashdrawer_id']))
        {
            $register_id = (int)$session_data['login_cashdrawer_id'];
            $session_obj = $this->openpos->getPosSession();
            $session_obj->save_cart($register_id,$cart);
        }
        return $result;
    }

	public function getCashierList($id){
        return array();
    }
	public function getAllowCashdrawers($id){
        $this->load->model('extension/module/openpos');
        $registers = $this->model_extension_module_openpos->getAllowCashdrawers($id);
        $result = array();
        foreach($registers as $r)
        {
            $tmp = array(
                'id' => $r['register_id'],
                'name' => $r['register_name'],
            );
            $result[] = $tmp;
        }
        return $result;
    }
    public function _formatSetting($setting,$warehouse_id = 0)
    {
        if($setting['pos_tax_class'] == 'op_productax')
        {
            $setting['pos_incl_tax_mode'] = 'yes';
            $setting['pos_item_incl_tax_mode'] = 'yes';
            $setting['pos_cart_discount'] = 'after_tax';
        }else{
            $setting['pos_tax_details'] =  array('rate'=> 0,'compound' => '0','rate_id' => 0,'shipping' => 'no','label' => 'Tax');
        }

        $this->load->language('extension/module/openpos');
        $setting['pos_allow_online_payment'] = false;
        $setting['pos_order_item_refund'] = 'no';


        // receipt_template_footer
        if(isset($setting['receipt_template_footer']))
        {
            $setting['receipt_template_footer'] = $this->openpos->_formatReceiptTemplate($setting['receipt_template_footer']);
        }else{
            $setting['receipt_template_footer'] = '';
        }
        //receipt_template_header
        if(isset($setting['receipt_template_header']))
        {
            $setting['receipt_template_header'] = $this->openpos->_formatReceiptTemplate($setting['receipt_template_header']);
        }else{
            $setting['receipt_template_header'] = '';
        }

        $setting['receipt_template'] =  $this->openpos->receiptBodyTemplate();
        $setting['pos_incl_tax_mode'] =  'yes';
        $setting['pos_tax_on_item_total'] =  'yes';
        $setting['pos_stock_manage'] =  'yes';

        // default customer
        $setting['pos_default_customer'] = array(
            'id' => 0,
            'group_id' =>  $this->config->get('config_customer_group_id'),
            'name' => 'Guest',
            'email' => '',
            'address' => '',
            'address_2' => '',
            'state' => '',
            'city' => '',
            'country' => '',
            'phone' => '',
            'point' => 0,
            'point_rate' => 0,
            'discount' => 0
        );
        //end
        $setting['currency'] = array(
        	'decimal' => $this->_getCurrencyDecimal($warehouse_id),
			'decimal_separator' =>  $this->_getCurrencyDecimalSeparator($warehouse_id),
			'thousand_separator' =>  $this->_getCurrencyThousandSeparator($warehouse_id),
        );
        return $setting;
    }
    public function _getSessionData()
    {
        $session_id = isset($this->request->post['session']) ? trim($this->request->post['session']) : '';
        if($session_id && $this->op_session->validate($session_id))
        {
            return $this->op_session->read($session_id);
        }
        return array();
    }
    public function _formatOrder($order_id){
	    $this->load->model('checkout/order');
        $order = $this->model_checkout_order->getOrder($order_id);
        
        $order_totals = $this->model_checkout_order->getOrderTotals($order_id);
        $sale_person_name = 'Unknown';
        $_pos_order_id = $order_id;
        $payments = array();
        $first_name = $order['firstname'];
        $last_name = $order['lastname'];
        $address = '';
        $phone = $order['telephone'];
        $email = $order['email'];
        $items = array();
        $sub_total = 0;
        $shipping_cost = 0;
        $final_discount_amount = 0;
        $tax_amount = 0;
        $grand_total = $order['total'];
        $created_at = $order['date_added'];
        $continue_pay_url = '';
        $allow_refund = 0;
        $allow_pickup = 0;

        $order_products = $this->model_checkout_order->getOrderProducts($order_id);
        if($order_id == 24)
        {
           // print_r($order_products);die;
        }
        foreach($order_products as $p)
        {
            $tmp = array(
                'name' => $p['name'],
                'sub_name' => '',
                'quantity' => 1 * $p['quantity'],
                'qty' => 1 * $p['quantity'],
                'refund_qty' => 0,
                'exchange_qty' => 0,
                'options' => array(),
                'final_price' => 1 * $p['price'],
                'final_discount_amount' => 0,
                'total' => 1 * $p['total'],
                'refund_total' => 0,
                'tax_amount' => 0,
            );
            $items[] = $tmp;
        }

        if($order['payment_code'] == 'openpos')
        {
            $pos_order = $this->model_extension_module_openpos->getPosOrder($order_id);
            $created_at = $pos_order['date_added'];

            $pos_order_payments = $this->model_extension_module_openpos->getPosOrderPayments($order_id);
            $seller_id = $pos_order['seller_user_id'];
            $cashier_user_id = $pos_order['cashier_user_id'];
            if(!$seller_id)
            {
                $seller_id = $cashier_user_id;
            }
            $seller = $this->model_extension_module_openpos->getUser($seller_id);
            if($seller)
            {
                $sale_person_name = $seller['username'];
            }
            foreach ($pos_order_payments as $p)
            {
                $payments[] = array(
                    'name' => $p['payment_title'],
                    'paid' => $p['amount'],
                    'return' => 0,
                    'ref' => $p['comment'],
                );
            }

        }else{

            $payments[] = array(
                'name' => $order['payment_method'],
                'paid' => $order['total'],
                'return' => 0,
                'ref' => '',
            );
        }

        foreach($order_totals as $total)
        {

            if($total['code'] == 'sub_total')
            {
                $sub_total = $total['value'];
            }
            if($total['code'] == 'total')
            {
                $grand_total = $total['value'];
            }
        }


        $result = array(
            'order_number' => $order_id,
            'order_number_format' => '#'.$order_id,
            'order_id' => $order_id,
            'system_order_id' => $order_id,
            'sale_person_name' => $sale_person_name,
            'payment_method' => $payments, //ref , paid , return
            'pos_order_id' => $_pos_order_id,
            'customer' => array(
                'firstname' => $first_name,
                'lastname' => $last_name,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
            ),
            'items' => $items,
            'sub_total' => 1 * $sub_total,
            'shipping_cost' => 1 *$shipping_cost,
            'final_discount_amount' => 1 *$final_discount_amount,
            'tax_amount' => 1 *$tax_amount,
            'grand_total' => 1 * $grand_total,
            'created_at' =>$created_at,
            'checkout_url' => $continue_pay_url,
            'allow_refund' => $allow_refund,
            'allow_pickup' => $allow_pickup,
            'note' => '',
            'status' => $order['order_status']
        );

        return $result;
    }
    public function _formatPaymentMethod($methods){
	    $result = array();
	    $availablePayments = $this->openpos->getAvailablePayments(true);
	    foreach($methods as $m)
        {
            $name = $m;
            foreach($availablePayments as $p)
            {
                if($p['code'] == $m)
                {
                    $name = $p['name'];
                }
            }
            $tmp = array(
                'code' => $m,
                'description' => '',
                'hasRef' => true,
                'name' => $name,
                'online_type' => '',
                'type' => 'offline',
            );
            $result[] = $tmp;
        }
	    return $result;
    }
    public function _formatCategories($setting)
    {
        $this->load->model('tool/image');
        $this->load->model('catalog/category');
        $result = array();
        if(isset($setting['pos_categories']) && is_array($setting['pos_categories']))
        {
            foreach($setting['pos_categories'] as $category_id)
            {
                $category_info = $this->model_catalog_category->getCategory($category_id);
                if($category_info && !empty($category_info))
                {

                    $image = $category_info['image'];
                    if($image)
                    {
                        $image = $this->model_tool_image->resize($category_info['image'], 419, 195);
                    }
                    $parent_id = $category_info['parent_id'];
                    $tmp = array(
                        'id' => $category_id,
                        'name' => $category_info['name'],
                        'image' => $image,
                        'description' => '',
                        'parent_id' => $parent_id,
                        'child' => array()
                    );
                    $result[] = $tmp;
                }
                
            }
        }
        if(!empty($result))
        {
            $tree = $this->openpos->buildTree($result);
        }else{
            $tree = [];
        }

        return $tree;
    }
    public function _getCurrencyDecimal($store_id){
	    $currency = $this->model_extension_module_openpos->getSystemConfigByKey('config_currency',$store_id);
	    $currency_code = $this->config->get('config_currency');
	    if($currency)
        {
            $currency_code = $currency['value'];
        }
        return  $this->currency->getDecimalPlace($currency_code);
    }
    public function _getCurrencyDecimalSeparator($store_id){
        return $this->language->get('decimal_point');
        
     }
     public function _getCurrencyThousandSeparator($store_id){
         return $this->language->get('thousand_point');
     }

    public function _getSalePerson($cashdrawer_id){
	    $result = array();
	    // id , name
        $cashiers = $this->model_extension_module_openpos->getCashiers($cashdrawer_id);
        foreach($cashiers as $cashier)
        {
            $tmp = array(
                'id' => $cashier['user_id'],
                'name' => implode(' ',array($cashier['firstname'],$cashier['lastname']))
            );
            $result[] = $tmp;
        }
	    return $result;
    }

    public function _getPointRate(){
	    $session_data = $this->_getSessionData();
        $login_warehouse_id = isset($session_data['login_warehouse_id']) ? $session_data['login_warehouse_id'] : 0;
	    $settings = $this->openpos->getAllSettingValues($login_warehouse_id);
	    if(isset($settings['pos_point_rate']))
        {
            return (float)$settings['pos_point_rate'];
        }
	    return 0; //pos_point_rate
    }

    public function validCoupon($coupon_info,$cart){

        $discount_total = 0;
        if (!$coupon_info['product']) {
            $sub_total = $cart['sub_total'];
        } else {
            $sub_total = 0;

            foreach ($cart['items'] as $item) {
                $product = $item['product'];
                if (in_array($product['id'], $coupon_info['product'])) {
                    $sub_total += $item['total'];
                }
            }
        }

        if ($coupon_info['type'] == 'F') {
            $coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
        }

        foreach ($cart['items'] as $item) {
            $product = $item['product'];
            $discount = 0;

            if (!$coupon_info['product']) {
                $status = true;
            } else {
                $status = in_array($product['id'], $coupon_info['product']);
            }

            if ($status) {
                if ($coupon_info['type'] == 'F') {
                    $discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
                } elseif ($coupon_info['type'] == 'P') {
                    $discount = $item['total'] / 100 * $coupon_info['discount'];
                }


            }

            $discount_total += $discount;
        }

        return $discount_total;

    }

    public function bill(){
        $this->load->library('openpos');
        $session_obj = $this->openpos->getPosSession();
        $params = $this->request->get;
        $cart = array();
        if(isset($params['id']) && $params['id'])
        {
            $cart  = $session_obj->read_cart($params['id']);
        }
        if(empty($cart))
        {
            $cart = array(
                'items' => array(),
                'grand_total' => 0,
                'sale_person_name' => 'unknown'
            );
        }
        echo json_encode($cart);exit;

    }

    public function getCustomerAdditionFields(){
        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');
        $address_2_field = array(
            'code' => 'address_2',
            'type' => 'text',
            'label' => 'Address 2',
            'options' => array(),
            'placeholder' => 'Address 2',
            'description' => '',
            'onchange_load' => false,
            'allow_shipping' => 'yes',
        );


        $postcode_field = array(
            'code' => 'postcode',
            'type' => 'text',
            'label' =>  'PostCode / Zip',
            'options' => array(),
            'placeholder' => 'PostCode / Zip',
            'description' => '',
            'onchange_load' => false,
            'allow_shipping' => 'yes',
        );

        $city_field = array(
            'code' => 'city',
            'type' => 'text',
            'label' => 'City',
            'options' => array(),
            'placeholder' => 'City',
            'description' => '',
            'onchange_load' => false,
            'allow_shipping' => 'yes',
        );

        $state_field = array(
            'code' => 'state',
            'type' => 'text',
            'label' =>  'State',
            'options' => array(),
            'placeholder' => 'State',
            'description' => '',
            'onchange_load' => false,
            'allow_shipping' => 'yes',
        );

        $store_country = $this->config->get('config_country_id');
        $store_state  = $this->config->get('config_zone_id');
        $states = array();
        if($store_country)
        {

            $tmp_states     = $this->model_localisation_zone->getZonesByCountryId($store_country);
            
            foreach($tmp_states as $key => $val)
            {
                $_tmp_state = array(
                        'value' => $val['zone_id'],
                        'label' => $val['name']
                );
                $states[] = $_tmp_state;
            }
        }
        if(!empty($states))
        {
            $state_field = array(
                'code' => 'state',
                'type' => 'select',
                'label' =>  'State',
                'options' => $states,
                'placeholder' => 'State',
                'description' => '',
                'onchange_load' => false,
                'allow_shipping' => 'yes',
                'default' => $store_state
            );
        }
        
        $countries   =  $this->model_extension_module_openpos->getCountries();
        
        $country_options = array();
        foreach($countries as $key => $country)
        {
            $country_options[] = ['value' => $country['country_id'],'label' => $country['name']];
        }
        $select_contry = array(
            'code' => 'country',
            'type' => 'select',
            'label' =>  'Country',
            'options' => $country_options,
            'placeholder' => 'Choose Country',
            'description' => '',
            'default' => $store_country,
            'allow_shipping' => 'yes',
            'onchange_load' => true
        );

        $fields = array(
                $address_2_field,
                $city_field,
                $postcode_field,
                $state_field,
                $select_contry
        );

        return $fields;
    }

    public function search_product(){
        $this->load->library('openpos');
        try{
            $session_data = $this->_getSessionData();
            $warehouse_id = isset($session_data['login_warehouse_id']) ? $session_data['login_warehouse_id'] : 0;
            $term = isset($_REQUEST['term']) ? $_REQUEST['term'] : '' ;
            if($term)
            {
                $term = strtolower($term);
                $sortBy = 'p.sort_order';
                $order = 'DESC';
                $limit = 10;
                $filter_data = array(
                    'filter_name'        => strtolower($term),
                    'sort'               => $sortBy,
                    'order'              => $order,
                    'start'              => 0,
                    'limit'              => $limit
                );
                
                $result_products = $this->model_catalog_product->getProducts($filter_data);
                
                $products        = array();

                
                foreach ( $result_products as $_product ) {
                    if($_product)
                    {
                            $product_data = $this->openpos->get_product_formatted_data($_product,$warehouse_id);
                            if(!empty($product_data))
                            {
                                $products[] =  $product_data;
                            }
                    }
                }
                $result['data']['term'] = $term;
                $result['data']['products'] = $products;
                $result['status'] = 1;
            }
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
    public function get_customer_orders(){
        
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
            $customer_id = (int)$_REQUEST['customer_id'];
            $current_page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
            if(!$customer_id)
            {
                throw new Exception(__('Customer do not exist','openpos'));
            }
            $customer = $this->model_account_customer->getCustomer($customer_id);
            
            if(!$customer || empty($customer))
            {
                throw new Exception(__('Customer do not exist','openpos'));
            }

            $total_order_count = $this->model_extension_module_openpos->getTotalCustomerOrders($customer_id);
            $per_page = 10;

            $total_page = ceil($total_order_count / $per_page);

            $data['status'] = 1;
            $data['total_page'] = $total_page;

            $data['orders'] = array();
            $offset = ($current_page -1) * $per_page;
            
            $customer_orders = $this->model_extension_module_openpos->getCustomerOrders( $customer_id,$offset,$per_page );

           
            foreach($customer_orders as $customer_order)
            {
                
                $formatted_order = $this->_formatOrder($customer_order['order_id']);
                $data['orders'][] =  $formatted_order;
            }

            $result['data'] = $data;
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
    public function add_custom_product(){
        $result = array('status' => 0, 'message' => 'Add Custom Product In develop','data' => array());
        return $result;
    }
    public function get_carts(){
        $result = array('status' => 0, 'message' => 'Get List Cross Carts - In develop','data' => array());
        return $result;
    }
    public function load_cart(){
        $result = array('status' => 0, 'message' => 'Load Cross Cart - In develop','data' => array());
        return $result;
    }
    public function get_customer_field(){
        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
            $by_data = json_decode(stripslashes($_REQUEST['by_data']),true);
            $country = $by_data['country'];
            $data = array();
            if($country )
            {
		        $data_country = $this->model_localisation_country->getCountry($country);
                $states = $this->model_localisation_zone->getZonesByCountryId($country);
                if(!$states || empty($states))
                {
                    $data['state'] = array(
                        'type' => 'text',
                        'default' => ''
                    );
                }else{
                    $state_options = array();
                    foreach($states as $key => $state)
                    {
                        $state_options[] = ['value' => $state['zone_id'],'label' => $state['name']];
                    }
                    $data['state'] = array(
                        'type' => 'select',
                        'default' => '',
                        'options' => $state_options
                    );
                }
            }
            $result['data'] = $data;
            $result['status'] = 1;
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
    public function upload_file_option(){
        $result = array('status' => 0, 'message' => '','data' => array());
        try{
           
            $result['status'] = 1;
        }catch (Exception $e)
        {
            $result['status'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
}
