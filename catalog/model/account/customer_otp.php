<?php

require_once DIR_HOME.'system/library/twilio-php/src/Twilio/autoload.php'; 
use Twilio\Rest\Client; 
class ModelAccountCustomerOtp extends Model {
	public function index() {

	}

	public function addOtp($telephone, $otp) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_otp WHERE telephone = '" . $this->db->escape($telephone) . "'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_otp` SET telephone = '" . $this->db->escape($telephone) . "', otp = '" . $this->db->escape($otp) . "', date_added = NOW()");
		$customer_otp_id = $this->db->getLastId();
		return $customer_otp_id;
	}

	public function getOtp($telephone) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_otp WHERE telephone = '" . $this->db->escape($telephone) . "'");
		return $query->row;
	}

	public function getOtpByTelephoneAndOtp($telephone, $otp) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_otp WHERE telephone = '" . $this->db->escape($telephone) . "' AND otp = '" . $this->db->escape($otp) . "'");
		return $query->row;
	}



	public function deleteOtp($telephone) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_otp WHERE telephone = '" . $this->db->escape($telephone) . "'");
	}

	public function sendOtp($telephone, $otp) {
		$sid    = "AC0c1bf5efd42a96dc1d74fd987b9a121b"; 
		$token  = "41210781ff5c67dca49180b02a444c44"; 
		$message = array();
		try {
			$twilio = new Client($sid, $token);
			$log = new log('tuananh.txt');
			$log->write($twilio);
			$message = $twilio->messages->create("+84983492410", // to 
		               array(  
		                   "messagingServiceSid" => "MGd00d2307f622fcc52bcb3396fd3cd5d9", 
		                   "body" => "Mã xác thực được gửi từ chothuenha.com.vn đến số điện thoại ".$telephone." của bạn là: ".$otp
		               ) 
		    ); 
			
			$_message = 'Send SMS success.';
		} catch (Exception $e) {
			$_message = $e;
		}

		

		

		if(isset($message->sid) && $message->sid) {
			$this->addOtp($telephone, $otp);
			return true;
		} else {
			return false;
		}        
	}
}