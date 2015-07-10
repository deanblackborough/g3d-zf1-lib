<?php
/**
* Generates the bootstrap badge html.
* 
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
* @license https://github.com/deanblackborough/g3d-zf1-lib/blob/master/LICENSE.md
*/
class G3d_View_BootstrapBadge extends Zend_View_Helper_Abstract 
{
	/**
	* Override the hinting to allow code completion for our view helpers
	*
	* @var G3d_View_Codehinting
	*/
	public $view;
	
	private $badge_count;
		
	/**
	* Set options
	* 
	* @param string $badge_count Count for the badge
	* @return G3d_View_BootstrapBadge
	*/
	public function bootstrapBadge($badge_count=0) 
	{
		$this->resetParams();
		
		$this->badge_count = intval($badge_count);
		
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
		$this->badge_count = 0;
	}
	
	/**
	* Generate the label HTML
	* 
	* @return string 
	*/
	private function render() 
	{
		return '<span class="badge">' . $this->badge_count . 
			'</span>';
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