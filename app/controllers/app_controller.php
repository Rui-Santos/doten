<?php
/**
  * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
App::import('Core', 'l10n');
class AppController extends Controller {

	var $components = array('Auth');

	function beforeFilter() { 
		// Authenticate
		$this->Auth->allow('display');
		$this->Auth->allow('home', 'howto', 'FAQ', 'about');
		$this->Auth->allow('register', 'forgotPassword', 'confirm', 'reset', 'setPassword');
		$this->Auth->allow('check', 'finished', 'btcc_output');
		/* this is for the login function in users to use cookie */
		$this->Auth->autoRedirect = false;
		/* this is default, no need to say again */
		//$this->Auth->loginAction = array('controller' => 'Users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'Accounts', 'action' => 'index');
		$this->Auth->loginError = __d('errors', 'USERS_LOGIN_FAILED', TRUE);
		$this->Auth->logoutRedirect = '/';
	}
} 
?>
