<?php
/**
* Controller to show all the image utility examples
* 
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
* @license https://github.com/deanblackborough/g3d-zf1-lib/blob/master/LICENSE.md
*/
class Utility_ImageController extends Zend_Controller_Action
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
	public function cropAction() { }
	public function resizerAction() { }
	public function resizerClassAction() { }
	public function resizerClassPngAction() { }
	public function resizerClassJpgAction() { }
	public function resizerClassGifAction() { }
}