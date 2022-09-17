<?php 
class ControllerExtensionModuleSlideCanvas extends Controller {
	public function canvas1() {

		$data['rand']  = rand();

		$this->response->setOutput($this->load->view('extension/module/canvas1', $data));
	}

	public function canvas2() {

		$data['rand']  = rand();

		$this->response->setOutput($this->load->view('extension/module/canvas2', $data));
		
	}
}