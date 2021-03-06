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

/**
 * TypoGento User Model
 *
 * @version $Id: User.php 19 2008-11-25 17:50:44Z weller $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Flagbit_Typo3connect_Model_Typo3_Frontend_User extends Mage_Core_Model_Abstract {

	/**
	 * Class Constructor
	 *
	 */
    public function _construct()
    {
    
        $this->_init('Flagbit_Typo3connect/typo3_frontend_user');
    }	
    
    /**
     * get fe_user by uid
     *
     * @param int $id
     * @return array User Data
     */
    public function getUserById($id){
    	return $this->_getResource()->getUserById($id);
    }

}
