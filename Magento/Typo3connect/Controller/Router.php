<?php


class Flagbit_Typo3connect_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard{

	public function match(Zend_Controller_Request_Http $request)
	{


		if(!Mage::getSingleton('Flagbit_Typo3connect/Core')->isEnabled()){
			return parent::match($request);
		}

		// get Params from TYPO3
		$params = Mage::getSingleton('Flagbit_Typo3connect/Core')->getParams();
		extract($params);
		$module = $route;

		if (Mage::app()->getStore()->isAdmin()) {
			return false;
		}


		$this->fetchDefault();
		$front = $this->getFront();

		$realModule = $this->getModuleByFrontName($module);
		if (!$realModule) {
			if ($moduleFrontName = array_search($module, $this->_modules)) {
				$realModule = $module;
				$module = $moduleFrontName;
			} else {
				return false;
			}
		}

		$request->setRouteName($this->getRouteByFrontName($module));

		if(!$controller){
			$controller = $front->getDefault('controller');
		}
		if(!$action){
			$action = $front->getDefault('action');
		}
		

		$controllerFileName = $this->getControllerFileName($realModule, $controller);
		if (!$this->validateControllerFileName($controllerFileName)) {
			return false;
		}

		$controllerClassName = $this->getControllerClassName($realModule, $controller);
		if (!$controllerClassName) {
			return false;
		}

		// include controller file if needed
		if (!class_exists($controllerClassName, false)) {
			if (!file_exists($controllerFileName)) {
				return false;
			}
			include $controllerFileName;

			if (!class_exists($controllerClassName, false)) {
				throw Mage::exception('Mage_Core', Mage::helper('core')->__('Controller file was loaded but class does not exist'));
			}
		}

		// instantiate controller class
		$controllerInstance = new $controllerClassName($request, new Flagbit_Typo3connect_Controller_Response);

		if (!$controllerInstance->hasAction($action)) {
			return false;
		}

		$request->setModuleName($route);
		$request->setControllerName($controller);
		$request->setActionName($action);
		$request->setParams($params);

		// dispatch action
		$request->setDispatched(true);
		$controllerInstance->dispatch($action);

		return true;#$request->isDispatched();
	}


}