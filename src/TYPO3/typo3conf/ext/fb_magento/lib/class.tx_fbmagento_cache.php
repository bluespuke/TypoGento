<?php
/*                                                                        *
 * This script is part of the TypoGento project 						  *
 *                                                                        *
 * TypoGento is free software; you can redistribute it and/or modify it   *
 * under the terms of the GNU General Public License version 2 as         *
 * published by the Free Software Foundation.                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

require_once(t3lib_extmgm::extPath('fb_magento').'lib/class.tx_fbmagento_tools.php');

/**
 * TypoGento Cache
 *
 * @version $Id: class.tx_fbmagento_interface.php 25 2009-01-21 16:43:16Z vinai $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class tx_fbmagento_cache {
	
	
	/**
	 * Cache Handler
	 *
	 * @var ArrayObject
	 */
	protected $_handler = null;	
	
	/**
	 * Singleton instance
	 *
	 * @var tx_fbmagento_cache
	 */
	protected static $_instance = null;
	
    /**
     * Setter/Getter underscore transformation cache
     *
     * @var array
     */
    protected static $_underscoreCache = array();	
	
	/**
	 * Singleton pattern implementation makes "new" unavailable
	 *
	 * @return void
	 */
	private function __construct() {
	}
	
	/**
	 * Singleton pattern implementation makes "clone" unavailable
	 *
	 * @return void
	 */
	private function __clone() {
	}
	
	/**
	 * Returns an instance of tx_fbmagento_cache
	 *
	 * Singleton pattern implementation
	 *
	 * @param Zend_Cache_Core
	 * @param Zend_Db_Adapter_Abstract 
	 * @return Flagbit_Settings Provides a fluent interface
	 */
	public static function getInstance($type) {
		if (null === self::$_instance) {
			self::$_instance = new self ( );
			self::$_instance->init ( $type );
		}
		
		return self::$_instance;
	}

	/**
	 * init function
	 *
	 * @param string $type
	 */
	protected function init($type) {
		
		switch ($type) {
			
			case "memory":
				$config = tx_fbmagento_tools::getExtConfig();
				require_once ($config['path'] . 'lib/Zend/Registry.php');
				$this->_handler = Zend_Registry::getInstance();
				break;
							
		}	
	}
	
	/**
	 * return Handler
	 *
	 * @return ArrayObject
	 */
	private function getHandler(){
		return $this->_handler;
	}
	
    /**
     * Set/Get attribute wrapper
     *
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get' :
                $key = $this->_underscore(substr($method,3));
                return $this->getData($key);

            case 'set' :
                $key = $this->_underscore(substr($method,3));
                return $this->setData($key, $args[0]);

            case 'uns' :
                $key = $this->_underscore(substr($method,3));
                return $this->unsData($key);

            case 'has' :
                $key = $this->_underscore(substr($method,3));
                return $this->hasData($key);
        }
        
        tx_fbmagento_tools::throwException("Invalid method ".get_class($this)."::".$method."(".print_r($args,1).")");
    }

    /**
     * get Data
     *
     * @param string $key
     * @return unknown
     */
    public function getData($key){
    	return $this->getHandler()->{$key};
    }
    
    /**
     * set Data
     *
     * @param string $key
     * @param unknown_type $value
     * @return unknown
     */
    public function setData($key, $value){
    	return $this->getHandler()->{$key} = isset($value) ? $value : null;
    }    
    
    /**
     * isset Data
     *
     * @param string $key
     * @return unknown
     */
    public function hasData($key){
    	return isset($this->getHandler()->{$key});
    }

    /**
     * unset Data
     *
     * @param string $key
     * @return unknown
     */
    public function unsData($key){
    	unset($this->getHandler()->{$key});
    	return true;
    }    
    
    /**
     * Converts field names for setters and geters
     * Uses cache to eliminate unneccessary preg_replace
     *
     * @param string $name
     * @return string
     */
    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }

        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        self::$_underscoreCache[$name] = $result;
        return $result;
    }    
    
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fb_magento/lib/class.tx_fbmagento_cache.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fb_magento/lib/class.tx_fbmagento_cache.php']);
}

