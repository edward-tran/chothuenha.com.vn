<?php 
class ControllerAccountMenuLeftProfile extends Controller {
	public function index() {

		$this->load->language('account/customer');
		$this->load->language('account/edit');
		$this->load->model('account/customer');
		$this->load->model('tool/image');

		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['nganluong'] = $this->url->link('payment_custom/nganluong', '', true);

		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

		$data['fullname'] = $customer_info['full_name'];
		$data['image'] = $this->model_tool_image->resize($customer_info['image'], 108, 108);

		$data['lastname'] = $customer_info['lastname'];

		$data['email'] = $customer_info['email'];

		$data['telephone'] = $customer_info['telephone'];

		$data['add_product'] = URL_HOME.'dang-tin.html';
		$data['payment'] = URL_HOME.'nap-tien';
		$data['dashboard'] = URL_HOME.'dashboard';
		$data['transaction_payment'] = $this->url->link('account/transaction_payment', '', true);
		$data['transaction_pay'] = $this->url->link('account/transaction_pay', '', true);
		$data['report'] = $this->url->link('account/report', '', true);
		$data['wallet'] = $this->url->link('account/wallet', '', true);

		return $this->load->view('account/menu_left_profile', $data);
	}

	public function uploadAvata() {

		$json 					= array();

    	$json['status']  		=  false;

        $json['url'] 			= '';

        $json['message']  		=  "Possible file upload attack!\n";

       	$error 					= true;



        if(isset($_FILES) && $_FILES) {

        		$uploadDir = 'catalog/customer/avata/';
        		$path_image_root = 'image/';

        		$name = $_FILES['image']['name'];

        		$path_folder = DIR_HOME.$path_image_root.$uploadDir;

        		$url_file = URL_HOME.$path_image_root.$uploadDir.$name;
        		$url_file_catalog = $path_image_root.$uploadDir.$name;

        		if (!file_exists($path_folder)){
        			mkdir($path_folder, 0777, true);
        		}

				$targetFile = $path_folder.'/'.$this->customer->getId().'_'.$name;

				if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {

					$error 				= false;
					$url_image 			=  $uploadDir.$this->customer->getId().'_'.$name;
	            }

	            if(!$error) {
	            	$this->load->model('account/customer');
	            	$this->load->model('tool/image');
	            	$this->model_account_customer->editAvataCustomer($this->customer->getId(), $url_image);
	            	$json['status'] 		=  true;
	            	$json['url_image'] 		=  $this->model_tool_image->resize($url_image, 108, 108);
	                $json['message'] 		=  "File is valid, and was successfully uploaded.\n";

	            }
        }

     	$this->response->addHeader('Content-Type: application/json');

	    $this->response->setOutput(json_encode($json));
	}
}