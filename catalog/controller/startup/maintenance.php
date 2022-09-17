<?php
class ControllerStartupMaintenance extends Controller {
	public function index() {
		if ($this->config->get('config_maintenance')) {
			// Routing
			if (isset($this->request->get['routing']) && $this->request->get['routing'] != 'startup/routingr') {
				$routing = $this->request->get['routing'];
			} else {
				$routing = $this->config->get('action_default');
			}			
			
			$ignore = array(
				'common/language/language',
				'common/currency/currency'
			);
			
			// Show site if logged in as admin
			$this->user = new Cart\User($this->registry);

			if ((substr($routing, 0, 17) != 'extension/payment' && substr($routing, 0, 3) != 'api') && !in_array($routing, $ignore) && !$this->user->isLogged()) {
				return new Action('common/maintenance');
			}
		}
	}
}
