<?php
/**
* Base controller, this will contain examples for all the view helpers
* 
* @author Dean Blackborougb <dean@g3d-development.com>
* @copyright G3D Development Limited
*/
class Index_IndexController extends Zend_Controller_Action
{
	/**
	* Zend layout object, required if we want to pass vars up to the layout 
	* object or set layout properties
	* 
	* @var Zend_Layout
	*/
	private $layout;
	
	/**
	* Init controller, run any code required by all the actions in the 
	* controller
	* 
	* @return void
	*/
	public function init()
	{
		$this->layout = Zend_Layout::getMvcInstance();
	}

	/**
	* @return void
	*/
	public function indexAction()
	{
		
	}
}