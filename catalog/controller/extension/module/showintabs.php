<?php  
class ControllerExtensionModuleShowintabs extends Controller {
	public function index($setting) {
    	$this->load->language('extension/module/showintabs');
    	$this->load->model('catalog/product');
    	//Obetenemos datos de las pestañas
    	$tabs = $this->config->get('showinconfig_tab');
		$data['carousel'] = $setting['carousel'];
		$data['nav'] = $setting['nav'];
		$data['pagi'] = $setting['pagi'];
		$data['lg_items'] = $setting['lg_items'];
		//mod id calculation
		$data['mod_id'] = substr(md5($setting['name']), -4).rand(0, 20);
		$data['mod_in_column'] = $this->language->get('mod_in_column');
    	//preparamos pestañas de este modulo
    	$data['tabs'] = array();
		$this->load->model('tool/image');
		$tabs_module = isset($setting['tabs']) ? $setting['tabs'] : array();

    	foreach ($tabs_module as $keyTab ) {
	    	//Title de la pestaña
			if ( isset($tabs[$keyTab]['title'][$this->config->get('config_language_id')]) && !empty($tabs[$keyTab]['title'][$this->config->get('config_language_id')])) {
				$data['tabs'][$keyTab]['title'] = $tabs[$keyTab]['title'][$this->config->get('config_language_id')];
			}else{
				$data['tabs'][$keyTab]['title']  = $this->language->get('heading_default');
			}	
			//Productos de la pestaña
			$results = array();
			$products = array();
			switch ($tabs[$keyTab]['data_source']) {
				case 'SP': //Select Products
					$results = $this->getSelectProducts($tabs[$keyTab],$setting['limit']);
					break;
				case 'PG': //Product Group
					$results = $this->getProductGroups($tabs[$keyTab],$setting['limit']);
					break;
				case 'CQ': //Custom Query
					$results = $this->getCustomQuery($tabs[$keyTab],$setting['limit']);
					break;
				default:
					$this->log->write('SHOW_IN_TAB::ERROR: The tab don\'t have product configured.');
					break;
			}
			// Category and manufacturer link
			$data['tabs'][$keyTab]['link'] = '';
			if( $setting['show_link'] && $tabs[$keyTab]['data_source'] == 'CQ' ){
				if( $tabs[$keyTab]['filter_category']  != 'ALL' ){
					$data['tabs'][$keyTab]['link'] = str_replace ('%s', $this->url->link('product/category', 'path=' . $tabs[$keyTab]['filter_category']), $this->language->get('link_category'));
				}else if( $tabs[$keyTab]['filter_manufacturer'] != 'ALL' ){
					$data['tabs'][$keyTab]['link'] = str_replace ('%s', $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $tabs[$keyTab]['filter_manufacturer']), $this->language->get('link_manufacturer'));
				}
			}
			//Preparo la info de cada producto
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}	
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}
				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				if($result) {
					$products[] = array(
						'product_id'  => $result['product_id'],
						'location'    => $result['location'],
						'height'      => str_replace('.00000000', '', $result['height']),
						'thumb'   	  => $image,
						'name'    	  => $result['name'],
						'price'   	  => $price,
						'special' 	  => $special,
						'rating'      => $rating,
						'tax'         => $tax,
						'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
						'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 150) . '..'
					);
				}
			}

			$data['tabs'][$keyTab]['products'] = $products;
    	}
		 return $this->load->view('extension/module/showintabs', $data);
  	}
  	// Obtiene los productos de los grupos predefinidos de opencart
  	private function getProductGroups( $tabInfo , $limit ){
  		$results = array();
  		//Obtengo listado de productos en funcion del criterio
  		switch ( $tabInfo['product_group'] ) {
  			case 'BS':
  				$results = $this->model_catalog_product->getBestSellerProducts($limit);
  				break;
  			case 'LA':
  				$results = $this->model_catalog_product->getLatestProducts($limit);
  				break;
  			case 'SP':
  				$results = $this->model_catalog_product->getProductReviews(array('start' => 0,'limit' => $limit));
  				break;
  			case 'PP':
  				$results = $this->model_catalog_product->getPopularProducts($limit);
  				break;
  		}
  		return $results;
  	}
	// Obtiene los productos seleccionados por el user con toda la info necesaria
  	private function getSelectProducts( $tabInfo , $limit ){
  		$results = array();
  		if(isset($tabInfo['products'])){
  			$limit_count = 0;
			foreach ( $tabInfo['products'] as $product ) {
				if ($limit_count++ == $limit) break;
				$results[$product['product_id']] = $this->model_catalog_product->getProduct($product['product_id']);
			}
		}
		return $results;
  	}
  	//Obtiene los productos segun los datos del custom query
  	private function getCustomQuery( $tabInfo , $limit){
  		$results = array();
  		if ( $tabInfo['sort'] == 'rating' || $tabInfo['sort'] == 'p.date_added') {
  			$order = 'DESC';
  		}else{
  			$order = 'ASC';
  		}
  		$data = array(
  			'filter_category_id' => $tabInfo['filter_category']=='ALL' ? '' : $tabInfo['filter_category'], 
  			'filter_manufacturer_id' => $tabInfo['filter_manufacturer']=='ALL' ? '' : $tabInfo['filter_manufacturer'], 
  			'sort' => $tabInfo['sort'], 
  			'order' => $order,
  			'start' => 0,
  			'limit' => $limit
  		);
  		$results = $this->model_catalog_product->getProducts($data);
		return $results;
  	}
}
?>