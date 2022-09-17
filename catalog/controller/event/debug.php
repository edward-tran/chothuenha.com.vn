<?php
class ControllerEventDebug extends Controller {
	public function before(&$routing, &$args) {
		if ($routing == 'common/home') { // add the routing you want to test
			$this->session->data['debug'][$routing] = microtime();		
		}
	}
	
	public function after($routing, &$args, &$output) {
		if ($routing == 'common/home') { // add the routing you want to test
			if (isset($this->session->data['debug'][$routing])) {
				$log_data = array(
					'routing' => $routing,
					'time'  => microtime() - $this->session->data['debug'][$routing]
				);
				
				$this->log->write($log_data);
			}
		}
	}	
}
