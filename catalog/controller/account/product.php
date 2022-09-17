<?php 

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;

class ControllerAccountProduct extends Controller {

	private $error = array();

	public function __construct($registry) {
        parent::__construct($registry);

        if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect(URL_HOME.'dang-nhap.html');
		}
    }

	public function add() {

		$this->load->language('account/product');

		$this->document->setTitle($this->language->get('heading_title_add'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_add'),
			'href' => $this->url->link('account/account', '', true)
		);
		

		//$this->document->addScript('https://unpkg.com/default-passive-events');
		$this->document->addScript('catalog/view/theme/beta/js/image-uploader.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addStyle('catalog/view/theme/beta/stylesheet/add_product.css?'.rand());
		$this->document->addStyle('catalog/view/theme/beta/stylesheet/select2.min.css');
		$this->document->addStyle('catalog/view/theme/beta/stylesheet/select2-bootstrap.css');
		$this->document->addStyle('catalog/view/theme/beta/stylesheet/image-uploader.css');
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/add_product.js?'.rand());


		// $this->document->addScript('catalog/view/theme/beta/js/angular/uploadCtrl.js');
		$this->document->addScript('catalog/view/theme/beta/js/select2.full.js');
		
		$data['country_id'] = $this->config->get('config_country_id');



		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right_product');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		// $data['customer_name'] = $this->customer->getTelephone();
		// $data['customer_telephone'] = $this->customer->getTelephone();
		// $data['customer_address'] = '';
		// $data['customer_email'] = $this->customer->getEmail();
		
		$this->response->setOutput($this->load->view('account/add_product', $data));
	}

	public function reviewProduct() {

		$this->load->language('account/product');

		$json = array();
		$json['status'] = false;

		
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$json['html'] = $this->getFormReviewProductList($this->request->post);

			$json['status'] = true;
			$json['message'] = 'Request success.';
		}

		if (isset($this->error['type_id'])) {
			$json['error_type_id'] = $this->error['type_id'];
		} else {
			$json['error_type_id'] = '';
		}

		if (isset($this->error['zone_id'])) {
			$json['error_zone_id'] = $this->error['zone_id'];
		} else {
			$json['error_zone_id'] = '';
		}

		if (isset($this->error['district_id'])) {
			$json['error_district_id'] = $this->error['district_id'];
		} else {
			$json['error_district_id'] = '';
		}

		if (isset($this->error['ward_id'])) {
			$json['error_ward_id'] = $this->error['ward_id'];
		} else {
			$json['error_ward_id'] = '';
		}

		if (isset($this->error['street_id'])) {
			$json['error_street_id'] = $this->error['street_id'];
		} else {
			$json['error_street_id'] = '';
		}

		if (isset($this->error['location'])) {
			$json['error_location'] = $this->error['location'];
		} else {
			$json['error_location'] = '';
		}

		if (isset($this->error['name'])) {
			$json['error_name'] = $this->error['name'];
		} else {
			$json['error_name'] = '';
		}

		if (isset($this->error['description'])) {
			$json['error_description'] = $this->error['description'];
		} else {
			$json['error_description'] = '';
		}

		if (isset($this->error['price'])) {
			$json['error_price'] = $this->error['price'];
		} else {
			$json['error_price'] = '';
		}

		if (isset($this->error['acreage'])) {
			$json['error_acreage'] = $this->error['acreage'];
		} else {
			$json['error_acreage'] = '';
		}

		// if (isset($this->error['customer_name'])) {
		// 	$json['error_customer_name'] = $this->error['customer_name'];
		// } else {
		// 	$json['error_customer_name'] = '';
		// }

		// if (isset($this->error['customer_telephone'])) {
		// 	$json['error_customer_telephone'] = $this->error['customer_telephone'];
		// } else {
		// 	$json['error_customer_telephone'] = '';
		// }

		// if (isset($this->error['customer_address'])) {
		// 	$json['error_customer_address'] = $this->error['customer_address'];
		// } else {
		// 	$json['error_customer_address'] = '';
		// }

		// if (isset($this->error['customer_email'])) {
		// 	$json['error_customer_email'] = $this->error['customer_email'];
		// } else {
		// 	$json['error_customer_email'] = '';
		// }

		// if (isset($this->error['post_type_id'])) {
		// 	$json['error_post_type_id'] = $this->error['post_type_id'];
		// } else {
		// 	$json['error_post_type_id'] = '';
		// }

		if (isset($this->error['image'])) {
			$json['error_image'] = $this->error['image'];
		} else {
			$json['error_image'] = '';
		}

		// if (isset($this->error['date_post_id'])) {
		// 	$json['error_date_post_id'] = $this->error['date_post_id'];
		// } else {
		// 	$json['error_date_post_id'] = '';
		// }

		if (isset($this->error['customer_address'])) {
			$json['error_customer_address'] = $this->error['customer_address'];
		} else {
			$json['error_customer_address'] = '';
		}


		if (isset($this->error['direction_house_id'])) {
			$json['error_direction_house_id'] = $this->error['direction_house_id'];
		} else {
			$json['error_direction_house_id'] = '';
		}

		// if (isset($this->error['direction_balcony_id'])) {
		// 	$json['error_direction_balcony_id'] = $this->error['direction_balcony_id'];
		// } else {
		// 	$json['error_direction_balcony_id'] = '';
		// }

		// if (isset($this->error['large_road'])) {
		// 	$json['error_large_road'] = $this->error['large_road'];
		// } else {
		// 	$json['error_large_road'] = '';
		// }

		// if (isset($this->error['facade'])) {
		// 	$json['error_facade'] = $this->error['facade'];
		// } else {
		// 	$json['error_facade'] = '';
		// }

		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	protected function getFormReviewProductList($param = array()) {

		$data = array();

		$data['product']['name'] 		= $param['name'];
		$data['product']['description'] = $param['description'];
		$data['product']['acreage'] 	= $param['acreage'];
		$data['product']['image']  		= $param['image'];
		$data['product']['bedroom']  	= $param['bedroom'];
		$data['product']['bathroom']  	= $param['bathroom'];
		$data['product']['customer_full_name']  	= $this->customer->getFullName();

		$this->load->model('tool/image');
		if ($this->customer->getImage()) {
			$data['product']['customer_image'] = $this->model_tool_image->resize($this->customer->getImage(), 74, 74);
		} else {
			$data['product']['customer_image'] = $this->model_tool_image->resize('data/avatar.jpg', 74, 74);
		}

		$price = str_replace(',', '', $param['price']);
		


		$data['product']['location'] 	= $param['zone_name'].', '.$param['ward_name'].', '.$param['district_name'].', '.$param['street_name'].', '.$param['location'];
		$data['product']['price'] 		= $this->currency->format($price, $this->config->get('config_currency'));

		

		return $this->load->view('account/form_review_product', $data);
	}

	public function getFormReviewProductDetail() {

		$data = array();

		return $this->load->view('account/form_review_product_detail', $data);
	}

	public function getFormConfirmProduct() {

		$json = array();
		$json['status'] = false;

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$param = $this->request->post;
			$data['name'] 		= $param['name'];
			$data['description'] = $param['description'];
			$data['acreage'] 	= $param['acreage'];

			if(isset($this->request->post['post_type_id'])) {
	            $post_type_id = (int)$this->request->post['post_type_id'];
	        } else {
	            $post_type_id = 0;
	        }

	        if(isset($this->request->post['date_post_id'])) {
	            $date_post_id = (int)$this->request->post['date_post_id'];
	        } else {
	            $date_post_id = 0;
	        }

	        if(isset($this->request->post['from_date'])) {
	            $from_date = $this->request->post['from_date'];
	        } else {
	            $from_date = '';
	        }

	        $to_date    = '';

	        if($from_date) {
	            $to_date    =  date('d/m/Y', strtotime($from_date. ' + '.(int)$date_post_id.' days'));
	        }

	        $from_date    =  date('d/m/Y');

	        $this->load->model('localisation/post_type');
			$post_type = $this->model_localisation_post_type->getPostTypeById($post_type_id);
			$price_service          = $post_type ? $post_type['price'] : 0;
			$json['price_service']          = $this->currency->format($price_service, $this->config->get('config_currency'));
			$json['service_name'] 	= $post_type ? $post_type['name'] : '';
			$json['date_post'] 	= $date_post_id.' Ngày';

			$json['from_date']         = $from_date;
            $json['to_date']           = $to_date;
			$price_vat              = $this->tax->calculate($price_service * $date_post_id, 9) - ($price_service * $date_post_id);
			$json['price_vat']         = $this->currency->format_default($price_vat, $this->config->get('config_currency'));
			$json['price_finnal']      = $this->currency->format_default($this->tax->calculate($price_service * $date_post_id, 9), $this->config->get('config_currency'));


			$this->load->model('account/customer_wallet');
			$total  = $this->tax->calculate($price_service * $date_post_id, 9);
			$amount = $this->model_account_customer_wallet->getTotalWallet();

			if($total > $amount) {
				$json['note_post'] = 'Tài khoản của bạn không đủ để thanh toán phí cho tin đăng này. Vui lòng nạp đúng số tiền để hoàn tất đăng tin.';
			} else {
				$json['note_post'] = '';
			}


			$json['status'] = true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}

	public function acceptAddProduct() {

		$json = array();
		$json['status'] = false;

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->load->model('account/product');

			$date_post_id = $this->request->post['date_post_id'];
			$from_date = $this->request->post['from_date'];

			$this->request->post['expiration_date'] =  date('d/m/Y', strtotime($from_date. ' + '.(int)$date_post_id.' days'));
			$this->request->post['price'] = $price = str_replace(',', '', $this->request->post['price']);

			if(isset($_FILES['files']) && $_FILES['files']) {
				$this->request->post['product_image'] = $_FILES['files'];
			}

			if(isset($this->request->post['from_date']) && $this->request->post['from_date']) {
				$this->request->post['from_date'] = date('Y-m-d', strtotime($this->request->post['from_date']));
			}

			if(isset($this->request->post['expiration_date']) && $this->request->post['expiration_date']) {
				$expiration_date = str_replace('/', '-', $this->request->post['expiration_date']);
				$this->request->post['expiration_date'] = date('Y-m-d', strtotime($expiration_date));
			}

			$product_id = $this->model_account_product->addProduct($this->request->post);
			$json['status'] = true;
			$json['redirect'] = $this->url->link('product/product', 'product_id=' . $product_id);
			$json['product_id'] = $product_id;
		}

		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateForm() {

		if (utf8_strlen($this->request->post['type_id']) <= 1) {
			$this->error['type_id'] = $this->language->get('error_type_id');
		}

		if (utf8_strlen($this->request->post['zone_id']) < 1) {
			$this->error['zone_id'] = $this->language->get('error_zone_id');
		}


		// if (empty((int)$this->request->post['post_type_id'])) {
		// 	$this->error['post_type_id'] = $this->language->get('error_post_type_id');
		// }

		if (empty((int)$this->request->post['direction_house_id'])) {
			$this->error['direction_house_id'] = $this->language->get('error_direction_house_id');
		}

		// if (empty((int)$this->request->post['direction_balcony_id'])) {
		// 	$this->error['direction_balcony_id'] = $this->language->get('error_direction_balcony_id');
		// }

		// if (empty((int)$this->request->post['large_road'])) {
		// 	$this->error['large_road'] = $this->language->get('error_large_road');
		// }

		// if (empty((int)$this->request->post['facade'])) {
		// 	$this->error['facade'] = $this->language->get('error_facade');
		// }



	
		if(empty($_FILES)) {
			$this->error['image'] = $this->language->get('error_image');
		} else {
			if (count($_FILES['files']['name']) < 3) {
				$this->error['image'] = $this->language->get('error_image');
			}
		}



		// if (utf8_strlen($this->request->post['image']) < 1 || $this->request->post['image'] == 'undefined') {
		// 	$this->error['image'] = $this->language->get('error_image');
		// }


		// if (empty((int)$this->request->post['date_post_id'])) {
		// 	$this->error['date_post_id'] = $this->language->get('error_date_post_id');
		// }

		if (utf8_strlen($this->request->post['district_id']) < 1) {
			$this->error['district_id'] = $this->language->get('error_district_id');
		}

		if (utf8_strlen($this->request->post['ward_id']) < 1) {
			$this->error['ward_id'] = $this->language->get('error_ward_id');
		}

		if (utf8_strlen($this->request->post['customer_address']) < 1) {
			$this->error['customer_address'] = $this->language->get('error_customer_address');
		}

		if ((utf8_strlen($this->request->post['location']) < 1) || (utf8_strlen($this->request->post['location']) > 255)) {
				$this->error['location'] = $this->language->get('error_location');
		}

		if (utf8_strlen($this->request->post['street_id']) < 1) {
			$this->error['street_id'] = $this->language->get('error_street_id');
		}

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
				$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['description']) < 1)) {
				$this->error['description'] = $this->language->get('error_description');
		}

		if (utf8_strlen($this->request->post['price']) <= 1) {
			$this->error['price'] = $this->language->get('error_price');
		}

		if (utf8_strlen($this->request->post['acreage']) < 1) {
			$this->error['acreage'] = $this->language->get('error_acreage');
		}

		// if ((utf8_strlen($this->request->post['customer_name']) < 1) || (utf8_strlen($this->request->post['customer_name']) > 255)) {
		// 		$this->error['customer_name'] = $this->language->get('error_customer_name');
		// }

		// if (utf8_strlen($this->request->post['customer_telephone']) < 1) {
		// 	$this->error['customer_telephone'] = $this->language->get('error_customer_telephone');
		// }

		// if (utf8_strlen($this->request->post['customer_email']) < 1) {
		// 	$this->error['customer_email'] = $this->language->get('error_customer_email');
		// }

		// if (utf8_strlen($this->request->post['customer_address']) < 1) {
		// 	$this->error['customer_address'] = $this->language->get('error_customer_address');
		// }

		

		// if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
		// 		$this->error['name'][$language_id] = $this->language->get('error_name');
		// }

		// if ((utf8_strlen($this->request->post['model']) > 64)) {
		// 	$this->error['model'] = $this->language->get('error_model');
		// }

		// if ($this->error && !isset($this->error['warning'])) {
		// 	$this->error['warning'] = $this->language->get('error_warning');
		// }

		return !$this->error;
	}

	/**
    printExcel C17 AccountingVoucher
  */
  public function excelProducts(){

  	  $json_selected = array();

  	  if(isset($this->request->get['selected'])) {
	      if($this->request->get['selected'] && $this->request->get['selected'] != 'undefined') {
	        $json_selected = str_replace('&quot;','', $this->request->get['selected']);
	        $json_selected = str_replace('[','', $json_selected);
	        $json_selected = str_replace(']','', $json_selected);
        	// $arr_selected = json_decode($json_selected, true);
	      }
      }

    


    if (isset($this->request->get['sort'])) {
		$sort = $this->request->get['sort'];
	} else {
		$sort = 'p.product_id';
	}

	if (isset($this->request->get['order'])) {
		$order = $this->request->get['order'];
	} else {
		$order = 'ASC';
	}

	if (isset($this->request->get['limit'])) {
		$limit = (int)$this->request->get['limit'];
	} else {
		$limit = 5;
	}

	if (isset($this->request->get['page'])) {
		$page = (int)$this->request->get['page'];
	} else {
		$page = 1;
	}

	

      $filter_data = array(
		'sort'            => $sort,
		'order'           => $order,
		'arr_selected'  => $json_selected,
		'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
		'limit'           => $limit
	);

     

      $this->load->model('account/product');

      $products  = $this->model_account_product->excelProducts($filter_data);

      $fileName             = 'DANH-SACH-CHUNG-TU.xlsx';
      $arr_No               = array();
      $arr_Model           = array();
      $arr_PostType           = array();
      $arr_Status           = array();
      $arr_Name      = array();
      $arr_Description           = array();
      $arr_FromDate        = array();
      $arr_ToDate      = array();
      $arr_Note   = array();

      foreach ($products as $key => $result) {
      	$note = '09/08/2022 Tài khoản KH không đủ thanh toán/chờ KH nạp tiền';
        array_push($arr_No,                         $key + 1);
        array_push($arr_Model,                      $result['model']);
        array_push($arr_PostType,                   $result['post_type_name']);
        array_push($arr_Status,                     $result['status'] ? 'Đã được duyệt' : 'Chưa được duyệt');
        array_push($arr_Name,                  		$result['name']);
        array_push($arr_Description,                strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')));
        array_push($arr_FromDate,             		($result['from_date'] != '0000-00-00 00:00:00') ? $result['from_date'] : '0000-00-00 00:00:00');
        array_push($arr_ToDate,               		($result['expiration_date'] != '0000-00-00 00:00:00') ? $result['expiration_date'] : '0000-00-00 00:00:00');
        array_push($arr_Note, $note);
      }

      PhpExcelTemplator::saveToFile("image/file_report/QUAN-LY-TIN-RAO.xlsx", $fileName, [
        '[arr_No]'              => $arr_No,
        '[arr_Model]'           => $arr_Model,
        '[arr_PostType]'        => $arr_PostType,
        '[arr_Status]'          => $arr_Status,
        '[arr_Name]'            => $arr_Name,
        '[arr_Description]'     => $arr_Description,
        '[arr_FromDate]'        => $arr_FromDate,
        '[arr_ToDate]'          => $arr_ToDate,
        '[arr_Note]'  			=> $arr_Note
      ]);
      //send result to browser
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . basename($fileName));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      header('Content-Length: ' . filesize($fileName));
      flush();
      readfile($fileName);
      unlink($fileName);
      exit;


  }
}