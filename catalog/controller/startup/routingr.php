<?php
class ControllerStartupRoutingr extends Controller {
	public function index() {
		// Routing
		if (isset($this->request->get['routing']) && $this->request->get['routing'] != 'startup/routingr') {
			$routing = $this->request->get['routing'];
		} else {
			$routing = $this->config->get('action_default');
		}
		
		// Sanitize the call
		$routing = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$routing);
		
		// Trigger the pre events
		$result = $this->event->trigger('controller/' . $routing . '/before', array(&$routing, &$data));
		
		if (!is_null($result)) {
			return $result;
		}
		
		// We dont want to use the loader class as it would make an controller callable.
		$action = new Action($routing);
		
		// Any output needs to be another Action object.
		$output = $action->execute($this->registry); 
		
		// Trigger the post events
		$result = $this->event->trigger('controller/' . $routing . '/after', array(&$routing, &$data, &$output));
		
		if (!is_null($result)) {
			return $result;
		}
		
		return $output;
	}
}
