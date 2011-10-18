<?php
/**
 * Catalog App Controller
 *
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class CatalogsAppController extends AppController {

	
	/*
	 * Override the $components in Zuha's AppController for removing ACL  
	 */
	
	var $components = array('RequestHandler', 'Email', 'Auth' , 'Session');
	
	
	/*
	 * Check if the content belongs to the specified user .
	 * If admin always returns true.
	 * @param {string} model -> The model name 
	 * @param {int} id -> The id of the record
	 * @return bool
	 */
	
	function beforeFilter(){
		parent::beforeFilter();
		if($this->Auth->user('isadmin')){
			$this->set('user_is_admin' , true);
		}else{
			$this->set('user_is_admin' , false);
		}
	}
	
	function __content_belongs($model , $id){
		$uid = $this->Auth->user('id');
		//check if user is admin
		if($this->Auth->user('isadmin')){
			//Approve the action if user is admin
			return true;
		}else{
			//if user is not admin get the necessary data
			$this->loadModel($model);
			$dat = $this->$model->findById($id);
			if($dat[$model]["creator_id"] == $uid){	
				return true;
			}else{
				return false;
			}
		}
		
	}
	
	/*
	 * Cehck if user is admin for admin functions
	 * @return {bool}
	 */
	
	function __is_admin(){
		if($this->Auth->user('isadmin')){
			return true;
		}else{
			$this->redirect(array('plugin'=>null, 'controller'=>'pages' , 'action'=>'auth' , 'admin'=>false));
		}
	}
}

?>