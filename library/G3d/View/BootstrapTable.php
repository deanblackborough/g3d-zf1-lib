<?php
/**
* Generates bootstrap valid HTML for a table
* 
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
* @license https://github.com/deanblackborough/g3d-zf1-lib/blob/master/LICENSE.md
*/
class G3d_View_BootstrapTable extends Zend_View_Helper_Abstract 
{
	/**
	* Override the hinting to allow code completion for our view helpers
	*
	* @var G3d_View_Codehinting
	*/
	public $view;
	
	private $caption;
	
	/**
	* Set options
	* 
	* @param string $caption Optional caption for table
	* @return G3d_View_BootstrapTable
	*/
	public function bootstrapTable($caption) 
	{
		$this->resetParams();
		
		$this->caption = trim($caption);
		
		return $this;
	}
	
	/**
	* Reset any internal params, interal properties need to be reset so that 
	* if the view helper is called within the view script each request is 
	* unique
	* 
	* @return void
	*/
	private function resetParams() 
	{
		$this->caption = '';
	}
	
	/**
	* Generate the final HTML for the table
	* 
	* @return string 
	*/
	private function render() 
	{
		return '';
	}
	
	/**
	* Define __toString to allow the result to be returned when echo 
	* and print are called on the object, simply calls the private render 
	* method
	* 
	* @return string The generated html
	*/
	public function __toString() 
	{
		return $this->render();
	}
}